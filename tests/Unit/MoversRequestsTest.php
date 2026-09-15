<?php

namespace MichaelDrennen\SchwabAPI\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use PHPUnit\Framework\TestCase;

class MoversRequestsTest extends TestCase {

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

        $reflection = new \ReflectionClass($this->api);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->api, $this->mockClient);
    }

    /**
     * @test
     */
    public function testMoversWithDefaultSortAndFrequency(): void {
        $expectedResponse = [
            'screeners' => [
                ['symbol' => 'AAPL', 'change' => 2.5]
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                return str_contains($url, '/marketdata/v1/movers/%24DJI');
            }))
            ->willReturn($mockResponse);

        $result = $this->api->movers('$DJI');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('screeners', $result);
    }

    /**
     * @test
     */
    public function testMoversWithSortAndFrequency(): void {
        $expectedResponse = ['screeners' => []];
        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                return str_contains($url, '/marketdata/v1/movers/%24COMPX')
                    && str_contains($url, 'sort=VOLUME')
                    && str_contains($url, 'frequency=5');
            }))
            ->willReturn($mockResponse);

        $result = $this->api->movers('$COMPX', 'VOLUME', 5);

        $this->assertIsArray($result);
    }

    /**
     * @test
     */
    public function testMoversThrowsOnInvalidSymbol(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("You entered a symbol of 'INVALID'");

        $this->api->movers('INVALID');
    }

    /**
     * @test
     */
    public function testMoversThrowsOnInvalidSort(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("You entered a sort of 'INVALID'");

        $this->api->movers('$DJI', 'INVALID');
    }

    /**
     * @test
     */
    public function testMoversThrowsOnInvalidFrequency(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("You entered a frequency of '99'");

        $this->api->movers('$DJI', 'VOLUME', 99);
    }
}
