<?php

namespace MichaelDrennen\SchwabAPI\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use MichaelDrennen\SchwabAPI\Exceptions\RequestException;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use PHPUnit\Framework\TestCase;

class ErrorHandlingTest extends TestCase {

    private SchwabAPI $api;
    private Client $mockClient;

    protected function setUp(): void {
        $this->mockClient = $this->createMock(Client::class);

        $this->api = new SchwabAPI(
            apiKey: 'test_api_key',
            apiSecret: 'test_api_secret',
            apiCallbackUrl: 'https://test.com/callback',
            authenticationCode: 'test_code',
            accessToken: 'test_access_token',
            debug: false
        );

        // Inject mock client using reflection
        $reflection = new \ReflectionClass($this->api);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->api, $this->mockClient);
    }

    /**
     * @test
     */
    public function testJsonMethodThrowsExceptionOnInvalidJson(): void {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to decode JSON response');

        $mockResponse = new Response(200, [], 'invalid json {{{');

        $this->api->json($mockResponse);
    }

    /**
     * @test
     */
    public function testJsonMethodReturnsEmptyArrayForNullJson(): void {
        // JSON null should return empty array
        $mockResponse = new Response(200, [], 'null');

        $result = $this->api->json($mockResponse);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function testJsonMethodHandlesValidJson(): void {
        $expectedData = ['key' => 'value', 'number' => 123];
        $mockResponse = new Response(200, [], json_encode($expectedData));

        $result = $this->api->json($mockResponse);

        $this->assertIsArray($result);
        $this->assertEquals($expectedData, $result);
    }

    /**
     * @test
     */
    public function testJsonMethodHandlesEmptyString(): void {
        $this->expectException(\RuntimeException::class);

        $mockResponse = new Response(200, [], '');

        $this->api->json($mockResponse);
    }

    /**
     * @test
     */
    public function testResponseCodeReturnsCorrectCode(): void {
        $mockResponse = new Response(200);
        $this->assertEquals(200, $this->api->responseCode($mockResponse));

        $mockResponse = new Response(201);
        $this->assertEquals(201, $this->api->responseCode($mockResponse));

        $mockResponse = new Response(404);
        $this->assertEquals(404, $this->api->responseCode($mockResponse));

        $mockResponse = new Response(500);
        $this->assertEquals(500, $this->api->responseCode($mockResponse));
    }

    /**
     * @test
     */
    public function testRequestExceptionPreservesContext(): void {
        $responseBody = json_encode(['error' => 'Invalid request', 'code' => 'ERR_001']);
        $previousException = new \Exception('Previous error');

        $exception = new RequestException(
            'Test error message',
            400,
            $previousException,
            $responseBody
        );

        $this->assertEquals('Test error message', $exception->getMessage());
        $this->assertEquals(400, $exception->getCode());
        $this->assertEquals($responseBody, $exception->getResponseBody());
        $this->assertSame($previousException, $exception->getPrevious());
    }

    /**
     * @test
     */
    public function testRequestExceptionWithNullResponseBody(): void {
        $exception = new RequestException('Test error', 500);

        $this->assertEquals('Test error', $exception->getMessage());
        $this->assertEquals(500, $exception->getCode());
        $this->assertEmpty($exception->getResponseBody());
    }

    /**
     * @test
     */
    public function test404ErrorHandling(): void {
        $mockRequest = new Request('GET', 'https://api.schwabapi.com/trader/v1/accounts/INVALID');
        $mockResponse = new Response(404, [], json_encode(['error' => 'Not found']));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willThrowException(new ClientException('Not Found', $mockRequest, $mockResponse));

        $this->expectException(\Exception::class);

        // This will attempt to make a request and should throw an exception
        $this->api->account('INVALID_HASH');
    }

    /**
     * @test
     */
    public function test401UnauthorizedHandling(): void {
        $mockRequest = new Request('GET', 'https://api.schwabapi.com/trader/v1/accounts');
        $mockResponse = new Response(401, [], json_encode(['error' => 'Unauthorized']));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willThrowException(new ClientException('Unauthorized', $mockRequest, $mockResponse));

        $this->expectException(\Exception::class);

        $this->api->accounts();
    }

    /**
     * @test
     */
    public function test500ServerErrorHandling(): void {
        $mockRequest = new Request('GET', 'https://api.schwabapi.com/trader/v1/accounts');
        $mockResponse = new Response(500, [], 'Internal Server Error');

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willThrowException(new ServerException('Server Error', $mockRequest, $mockResponse));

        $this->expectException(\Exception::class);

        $this->api->accounts();
    }

    /**
     * @test
     */
    public function test503ServiceUnavailableHandling(): void {
        $mockRequest = new Request('GET', 'https://api.schwabapi.com/trader/v1/accounts');
        $mockResponse = new Response(503, [], 'Service Unavailable');

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willThrowException(new ServerException('Service Unavailable', $mockRequest, $mockResponse));

        $this->expectException(\Exception::class);

        $this->api->accounts();
    }

    /**
     * @test
     */
    public function testNetworkConnectionError(): void {
        $mockRequest = new Request('GET', 'https://api.schwabapi.com/trader/v1/accounts');

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willThrowException(new ConnectException('Connection refused', $mockRequest));

        $this->expectException(\Exception::class);

        $this->api->accounts();
    }

    /**
     * @test
     */
    public function testMalformedResponseHandling(): void {
        // Response with invalid JSON
        $mockResponse = new Response(200, [], '{broken json');

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($mockResponse);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to decode JSON response');

        $this->api->accounts();
    }

    /**
     * @test
     */
    public function testEmptyResponseHandling(): void {
        // Empty but valid JSON response
        $mockResponse = new Response(200, [], '[]');

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($mockResponse);

        $result = $this->api->accounts();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function testRateLimitHandling(): void {
        // 429 Too Many Requests
        $mockRequest = new Request('GET', 'https://api.schwabapi.com/trader/v1/accounts');
        $mockResponse = new Response(429, ['Retry-After' => '60'], json_encode(['error' => 'Rate limit exceeded']));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willThrowException(new ClientException('Too Many Requests', $mockRequest, $mockResponse));

        $this->expectException(\Exception::class);

        $this->api->accounts();
    }

    /**
     * @test
     */
    public function testTimeoutHandling(): void {
        $mockRequest = new Request('GET', 'https://api.schwabapi.com/trader/v1/accounts');

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willThrowException(new ConnectException('Operation timed out', $mockRequest));

        $this->expectException(\Exception::class);

        $this->api->accounts();
    }
}
