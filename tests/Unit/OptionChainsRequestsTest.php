<?php

namespace MichaelDrennen\SchwabAPI\Tests\Unit;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use PHPUnit\Framework\TestCase;

class OptionChainsRequestsTest extends TestCase {

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
    public function testChainsWithDefaultParameters(): void {
        $expectedResponse = [
            'symbol' => 'AAPL',
            'status' => 'SUCCESS',
            'strategy' => 'SINGLE',
            'numberOfContracts' => 10,
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                return str_contains($url, '/marketdata/v1/chains')
                    && str_contains($url, 'symbol=AAPL')
                    && str_contains($url, 'contractType=ALL')
                    && str_contains($url, 'strategy=SINGLE');
            }))
            ->willReturn($mockResponse);

        $result = $this->api->chains('AAPL');

        $this->assertIsArray($result);
        $this->assertEquals('AAPL', $result['symbol']);
        $this->assertEquals('SUCCESS', $result['status']);
    }

    /**
     * @test
     */
    public function testChainsWithCustomParameters(): void {
        $expectedResponse = ['symbol' => 'MSFT', 'status' => 'SUCCESS'];
        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                return str_contains($url, '/marketdata/v1/chains')
                    && str_contains($url, 'symbol=MSFT')
                    && str_contains($url, 'contractType=CALL')
                    && str_contains($url, 'strikeCount=5')
                    && str_contains($url, 'includeUnderlyingQuote=TRUE')
                    && str_contains($url, 'range=ITM')
                    && str_contains($url, 'fromDate=2026-09-01')
                    && str_contains($url, 'toDate=2026-10-01');
            }))
            ->willReturn($mockResponse);

        $result = $this->api->chains(
            symbol: 'MSFT',
            contractType: 'CALL',
            strikeCount: 5,
            includeUnderlyingQuote: true,
            strategy: 'SINGLE',
            interval: 2.5,
            strike: 300.0,
            range: 'ITM',
            fromDate: Carbon::parse('2026-09-01'),
            toDate: Carbon::parse('2026-10-01'),
            volatility: 25.0,
            underlyingPrice: 310.0,
            interestRate: 0.05,
            daysToExpiration: 30,
            expMonth: 'OCT',
            optionType: 'S',
            entitlement: 'PN'
        );

        $this->assertIsArray($result);
        $this->assertEquals('MSFT', $result['symbol']);
    }

    /**
     * @test
     */
    public function testChainsThrowsOnInvalidContractType(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid contract type 'INVALID'.");

        $this->api->chains('AAPL', 'INVALID');
    }

    /**
     * @test
     */
    public function testChainsThrowsOnInvalidStrategy(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Invalid strategy type 'INVALID'.");

        $this->api->chains('AAPL', 'ALL', null, null, 'INVALID');
    }
}
