<?php

namespace MichaelDrennen\SchwabAPI\Tests\Unit;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use PHPUnit\Framework\TestCase;

class TransactionRequestsTest extends TestCase {

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
    public function testTransactionsWithRequiredParametersOnly(): void {
        $startDate = Carbon::now()->subMonths(3);
        $endDate = Carbon::now();

        $expectedResponse = [
            [
                'activityId' => 123456789,
                'time' => '2024-01-15T10:30:00Z',
                'type' => 'TRADE',
                'status' => 'VALID',
                'netAmount' => -1500.00
            ],
            [
                'activityId' => 987654321,
                'time' => '2024-01-16T14:20:00Z',
                'type' => 'TRADE',
                'status' => 'VALID',
                'netAmount' => 2000.00
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                return str_contains($url, '/trader/v1/accounts/HASH123/transactions') &&
                       str_contains($url, 'startDate=') &&
                       str_contains($url, 'endDate=');
            }))
            ->willReturn($mockResponse);

        $result = $this->api->transactions('HASH123', $startDate, $endDate);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals(123456789, $result[0]['activityId']);
        $this->assertEquals('TRADE', $result[0]['type']);
    }

    /**
     * @test
     */
    public function testTransactionsWithSymbolFilter(): void {
        $startDate = Carbon::now()->subMonths(1);
        $endDate = Carbon::now();

        $expectedResponse = [
            [
                'activityId' => 123456789,
                'type' => 'TRADE',
                'symbol' => 'AAPL',
                'netAmount' => -1500.00
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                return str_contains($url, 'symbol=AAPL');
            }))
            ->willReturn($mockResponse);

        $result = $this->api->transactions('HASH123', $startDate, $endDate, 'AAPL');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('AAPL', $result[0]['symbol']);
    }

    /**
     * @test
     */
    public function testTransactionsWithTypeFilter(): void {
        $startDate = Carbon::now()->subWeeks(2);
        $endDate = Carbon::now();

        $expectedResponse = [
            [
                'activityId' => 123456789,
                'type' => 'TRADE',
                'netAmount' => -1500.00
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                return str_contains($url, 'types=TRADE');
            }))
            ->willReturn($mockResponse);

        $result = $this->api->transactions('HASH123', $startDate, $endDate, null, 'TRADE');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('TRADE', $result[0]['type']);
    }

    /**
     * @test
     */
    public function testTransactionsWithAllFilters(): void {
        $startDate = Carbon::now()->subMonths(1);
        $endDate = Carbon::now();

        $expectedResponse = [
            [
                'activityId' => 123456789,
                'type' => 'TRADE',
                'symbol' => 'AAPL',
                'netAmount' => -1500.00
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                return str_contains($url, '/trader/v1/accounts/HASH123/transactions') &&
                       str_contains($url, 'startDate=') &&
                       str_contains($url, 'endDate=') &&
                       str_contains($url, 'symbol=AAPL') &&
                       str_contains($url, 'types=TRADE');
            }))
            ->willReturn($mockResponse);

        $result = $this->api->transactions('HASH123', $startDate, $endDate, 'AAPL', 'TRADE');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('AAPL', $result[0]['symbol']);
        $this->assertEquals('TRADE', $result[0]['type']);
    }

    /**
     * @test
     */
    public function testTransactionsUppercasesSymbol(): void {
        $startDate = Carbon::now()->subWeeks(1);
        $endDate = Carbon::now();

        $expectedResponse = [];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                // Verify symbol is uppercased
                return str_contains($url, 'symbol=AAPL') && !str_contains($url, 'symbol=aapl');
            }))
            ->willReturn($mockResponse);

        $result = $this->api->transactions('HASH123', $startDate, $endDate, 'aapl');

        $this->assertIsArray($result);
    }

    /**
     * @test
     */
    public function testTransaction(): void {
        $expectedResponse = [
            'activityId' => 123456789,
            'time' => '2024-01-15T10:30:00Z',
            'type' => 'TRADE',
            'status' => 'VALID',
            'subAccount' => '1',
            'tradeDate' => '2024-01-15',
            'settlementDate' => '2024-01-17',
            'netAmount' => -1500.00,
            'activityType' => 'EXECUTION',
            'transferItems' => [
                [
                    'instrument' => [
                        'symbol' => 'AAPL',
                        'cusip' => '037833100',
                        'assetType' => 'EQUITY'
                    ],
                    'amount' => 10.0,
                    'cost' => 150.00,
                    'price' => 150.00
                ]
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('/trader/v1/accounts/HASH123/transactions/TXN123456'))
            ->willReturn($mockResponse);

        $result = $this->api->transaction('HASH123', 'TXN123456');

        $this->assertIsArray($result);
        $this->assertEquals(123456789, $result['activityId']);
        $this->assertEquals('TRADE', $result['type']);
        $this->assertEquals('VALID', $result['status']);
        $this->assertEquals(-1500.00, $result['netAmount']);
        $this->assertArrayHasKey('transferItems', $result);
    }

    /**
     * @test
     */
    public function testTransactionReturnsEmptyArray(): void {
        $expectedResponse = [];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($mockResponse);

        $result = $this->api->transaction('HASH123', 'TXN999999');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }
}
