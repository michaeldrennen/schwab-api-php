<?php

namespace MichaelDrennen\SchwabAPI\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MichaelDrennen\SchwabAPI\Exceptions\RequestException;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use PHPUnit\Framework\TestCase;

class SchwabAPIAuthenticationTest extends TestCase {

    /**
     * @test
     */
    public function testConstructorValidatesApiKey(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('API Key is required and cannot be empty');

        new SchwabAPI(
            apiKey: '',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code'
        );
    }

    /**
     * @test
     */
    public function testConstructorValidatesApiSecret(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('API Secret is required and cannot be empty');

        new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: '',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code'
        );
    }

    /**
     * @test
     */
    public function testConstructorValidatesCallbackUrl(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('API Callback URL is required and cannot be empty');

        new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: '',
            authenticationCode: 'test_code'
        );
    }

    /**
     * @test
     */
    public function testConstructorRequiresEitherCodeOrAccessToken(): void {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Either authentication code or access token must be provided');

        new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback'
        );
    }

    /**
     * @test
     */
    public function testConstructorAcceptsValidParameters(): void {
        $api = new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code'
        );

        $this->assertInstanceOf(SchwabAPI::class, $api);
    }

    /**
     * @test
     */
    public function testConstructorAcceptsAccessTokenInsteadOfCode(): void {
        $api = new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            accessToken: 'test_access_token'
        );

        $this->assertInstanceOf(SchwabAPI::class, $api);
    }

    /**
     * @test
     */
    public function testGetAuthorizeUrl(): void {
        $api = new SchwabAPI(
            apiKey: 'my_api_key',
            apiSecret: 'my_api_secret',
            apiCallbackUrl: 'https://example.com/callback',
            authenticationCode: 'test_code'
        );

        $url = $api->getAuthorizeUrl();

        $this->assertStringContainsString('https://api.schwabapi.com/v1/oauth/authorize', $url);
        $this->assertStringContainsString('client_id=my_api_key', $url);
        $this->assertStringContainsString('redirect_uri=https://example.com/callback', $url);
    }

    /**
     * @test
     */
    public function testRequestTokenSuccess(): void {
        $mockClient = $this->createMock(Client::class);

        $responseData = [
            'expires_in' => 1800,
            'token_type' => 'Bearer',
            'scope' => 'api',
            'refresh_token' => 'test_refresh_token',
            'access_token' => 'test_access_token',
            'id_token' => 'test_id_token'
        ];

        $mockResponse = new Response(200, [], json_encode($responseData));

        $mockClient
            ->expects($this->once())
            ->method('post')
            ->with(
                'https://api.schwabapi.com/v1/oauth/token',
                $this->callback(function ($options) {
                    return isset($options['headers']['Authorization']) &&
                           str_contains($options['headers']['Authorization'], 'Basic ') &&
                           $options['form_params']['grant_type'] === 'authorization_code';
                })
            )
            ->willReturn($mockResponse);

        $api = new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code'
        );

        // Inject mock client
        $reflection = new \ReflectionClass($api);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($api, $mockClient);

        $api->requestToken();

        $this->assertEquals('test_access_token', $api->getAccessToken());
        $this->assertEquals('test_refresh_token', $api->getRefreshToken());
        $this->assertEquals(1800, $api->getExpiresIn());
    }

    /**
     * @test
     */
    public function testRequestTokenRefresh(): void {
        $mockClient = $this->createMock(Client::class);

        $responseData = [
            'expires_in' => 1800,
            'token_type' => 'Bearer',
            'scope' => 'api',
            'refresh_token' => 'new_refresh_token',
            'access_token' => 'new_access_token',
            'id_token' => 'new_id_token'
        ];

        $mockResponse = new Response(200, [], json_encode($responseData));

        $mockClient
            ->expects($this->once())
            ->method('post')
            ->with(
                'https://api.schwabapi.com/v1/oauth/token',
                $this->callback(function ($options) {
                    return $options['form_params']['grant_type'] === 'refresh_token' &&
                           isset($options['form_params']['refresh_token']);
                })
            )
            ->willReturn($mockResponse);

        $api = new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code',
            accessToken: 'old_access_token',
            refreshToken: 'old_refresh_token'
        );

        // Inject mock client
        $reflection = new \ReflectionClass($api);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($api, $mockClient);

        $api->requestToken(doRefreshToken: true);

        $this->assertEquals('new_access_token', $api->getAccessToken());
        $this->assertEquals('new_refresh_token', $api->getRefreshToken());
    }

    /**
     * @test
     */
    public function testRequestTokenRefreshWithoutRefreshTokenThrowsException(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("You are asking to refresh the access token, but you don't have a refresh token.");

        $api = new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code',
            accessToken: 'test_access_token'
        );

        $api->requestToken(doRefreshToken: true);
    }

    /**
     * @test
     */
    public function testRequestTokenHandlesInvalidJson(): void {
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Failed to decode JSON response from Schwab API');

        $mockClient = $this->createMock(Client::class);
        $mockResponse = new Response(200, [], 'invalid json');

        $mockClient
            ->expects($this->once())
            ->method('post')
            ->willReturn($mockResponse);

        $api = new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code'
        );

        $reflection = new \ReflectionClass($api);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($api, $mockClient);

        $api->requestToken();
    }

    /**
     * @test
     */
    public function testRequestTokenHandlesMissingFields(): void {
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage("Missing required field 'refresh_token' in token response");

        $mockClient = $this->createMock(Client::class);

        $incompleteResponse = [
            'expires_in' => 1800,
            'token_type' => 'Bearer',
            'scope' => 'api',
            'access_token' => 'test_access_token',
            'id_token' => 'test_id_token'
            // Missing: refresh_token
        ];

        $mockResponse = new Response(200, [], json_encode($incompleteResponse));

        $mockClient
            ->expects($this->once())
            ->method('post')
            ->willReturn($mockResponse);

        $api = new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code'
        );

        $reflection = new \ReflectionClass($api);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($api, $mockClient);

        $api->requestToken();
    }

    /**
     * @test
     */
    public function testRequestTokenHandlesClientException(): void {
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Schwab API client error (HTTP 401)');

        $mockClient = $this->createMock(Client::class);
        $mockRequest = new Request('POST', 'https://api.schwabapi.com/v1/oauth/token');
        $mockResponse = new Response(401, [], 'Unauthorized');

        $mockClient
            ->expects($this->once())
            ->method('post')
            ->willThrowException(new ClientException('Unauthorized', $mockRequest, $mockResponse));

        $api = new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code'
        );

        $reflection = new \ReflectionClass($api);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($api, $mockClient);

        $api->requestToken();
    }

    /**
     * @test
     */
    public function testRequestTokenHandlesServerException(): void {
        $this->expectException(RequestException::class);
        $this->expectExceptionMessage('Schwab API server error (HTTP 500)');

        $mockClient = $this->createMock(Client::class);
        $mockRequest = new Request('POST', 'https://api.schwabapi.com/v1/oauth/token');
        $mockResponse = new Response(500, [], 'Internal Server Error');

        $mockClient
            ->expects($this->once())
            ->method('post')
            ->willThrowException(new ServerException('Server Error', $mockRequest, $mockResponse));

        $api = new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code'
        );

        $reflection = new \ReflectionClass($api);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($api, $mockClient);

        $api->requestToken();
    }

    /**
     * @test
     */
    public function testToStringMasksSensitiveData(): void {
        $api = new SchwabAPI(
            apiKey: 'my_secret_api_key_12345',
            apiSecret: 'my_secret_api_secret_67890',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code',
            accessToken: 'my_access_token_abcdef',
            refreshToken: 'my_refresh_token_ghijkl'
        );

        $stringRepresentation = (string) $api;

        // Should contain masked versions
        $this->assertStringContainsString('****', $stringRepresentation);

        // Should NOT contain full secrets
        $this->assertStringNotContainsString('my_secret_api_key_12345', $stringRepresentation);
        $this->assertStringNotContainsString('my_secret_api_secret_67890', $stringRepresentation);
        $this->assertStringNotContainsString('my_access_token_abcdef', $stringRepresentation);
        $this->assertStringNotContainsString('my_refresh_token_ghijkl', $stringRepresentation);
    }

    /**
     * @test
     */
    public function testSetters(): void {
        $api = new SchwabAPI(
            apiKey: 'test_key',
            apiSecret: 'test_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'initial_code'
        );

        $api->setCode('new_code');
        $api->setSession('new_session');

        // We can't directly assert these without getters for code/session,
        // but we verify the methods exist and don't throw exceptions
        $this->assertTrue(method_exists($api, 'setCode'));
        $this->assertTrue(method_exists($api, 'setSession'));
    }
}
