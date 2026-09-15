<?php

namespace MichaelDrennen\SchwabAPI\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use PHPUnit\Framework\TestCase;

class InstrumentsRequestsRealTest extends TestCase {

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
    public function testInstruments(): void {
        $expectedResponse = [
            'instruments' => [
                [
                    'cusip' => '037833100',
                    'symbol' => 'AAPL',
                    'description' => 'Apple Inc',
                    'exchange' => 'NASDAQ',
                    'assetType' => 'EQUITY'
                ]
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                return str_contains($url, '/marketdata/v1/instruments')
                    && str_contains($url, 'symbol=AAPL')
                    && str_contains($url, 'projection=symbol-search');
            }))
            ->willReturn($mockResponse);

        $result = $this->api->instruments('AAPL', 'symbol-search');
        $this->assertIsArray($result);
        $this->assertEquals('AAPL', $result['instruments'][0]['symbol']);
    }

    /**
     * @test
     */
    public function testInstrumentByCusip(): void {
        $expectedResponse = [
            'instruments' => [
                [
                    'cusip' => '037833100',
                    'symbol' => 'AAPL'
                ]
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('/marketdata/v1/instruments/037833100'))
            ->willReturn($mockResponse);

        $result = $this->api->instrumentByCusip('037833100');
        $this->assertIsArray($result);
        $this->assertEquals('AAPL', $result['instruments'][0]['symbol']);
    }

    /**
     * @test
     */
    public function testGetInstrumentFromTicker(): void {
        $searchResponse = [
            'instruments' => [
                [
                    'cusip' => '037833100',
                    'symbol' => 'AAPL'
                ]
            ]
        ];

        $cusipResponse = [
            'instruments' => [
                [
                    'cusip' => '037833100',
                    'symbol' => 'AAPL',
                    'description' => 'Apple Inc'
                ]
            ]
        ];

        $mockSearch = new Response(200, [], json_encode($searchResponse));
        $mockCusip = new Response(200, [], json_encode($cusipResponse));

        $this->mockClient
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls($mockSearch, $mockCusip);

        $result = $this->api->getInstrumentFromTicker('AAPL');
        $this->assertIsArray($result);
        $this->assertEquals('Apple Inc', $result['instruments'][0]['description']);
    }
}
