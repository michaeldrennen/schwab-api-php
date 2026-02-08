<?php

namespace MichaelDrennen\SchwabAPI\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use PHPUnit\Framework\TestCase;

class UserPreferenceRequestsTest extends TestCase {

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
    public function testUserPreferenceReturnsData(): void {
        $expectedResponse = [
            'accounts' => [
                [
                    'accountNumber' => '12345',
                    'primaryAccount' => true,
                    'type' => 'MARGIN',
                    'nickName' => 'My Trading Account',
                    'displayAcctId' => '****5678',
                    'autoPositionEffect' => true
                ]
            ],
            'streamerInfo' => [
                [
                    'streamerSocketUrl' => 'wss://streamer.schwab.com',
                    'schwabClientCustomerId' => 'CUSTOMER123',
                    'schwabClientCorrelId' => 'CORREL456'
                ]
            ],
            'offers' => [
                [
                    'level2Permissions' => true,
                    'mktDataPermission' => 'PROFESSIONAL'
                ]
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('/trader/v1/userPreference'))
            ->willReturn($mockResponse);

        $result = $this->api->userPreference();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('accounts', $result);
        $this->assertArrayHasKey('streamerInfo', $result);
        $this->assertArrayHasKey('offers', $result);
        $this->assertCount(1, $result['accounts']);
        $this->assertEquals('12345', $result['accounts'][0]['accountNumber']);
        $this->assertTrue($result['accounts'][0]['primaryAccount']);
    }

    /**
     * @test
     */
    public function testUserPreferenceReturnsEmptyArray(): void {
        $mockResponse = new Response(200, [], '[]');

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($mockResponse);

        $result = $this->api->userPreference();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function testUserPreferenceUsesCorrectEndpoint(): void {
        $mockResponse = new Response(200, [], '{}');

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                return str_contains($url, 'https://api.schwabapi.com/trader/v1/userPreference');
            }))
            ->willReturn($mockResponse);

        $this->api->userPreference();
    }
}
