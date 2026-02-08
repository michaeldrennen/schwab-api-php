<?php

namespace MichaelDrennen\SchwabAPI\Tests\Unit;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use PHPUnit\Framework\TestCase;

class OrderRequestsTest extends TestCase {

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
    public function testOrdersWithoutParameters(): void {
        $expectedResponse = [
            ['orderId' => 12345, 'status' => 'FILLED'],
            ['orderId' => 67890, 'status' => 'WORKING']
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('/trader/v1/orders'))
            ->willReturn($mockResponse);

        $result = $this->api->orders();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    /**
     * @test
     */
    public function testOrdersWithAllParameters(): void {
        $fromTime = Carbon::now()->subDays(7);
        $toTime = Carbon::now();

        $expectedResponse = [
            ['orderId' => 12345, 'status' => 'FILLED']
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->callback(function ($url) {
                return str_contains($url, 'fromEnteredTime') &&
                       str_contains($url, 'toEnteredTime') &&
                       str_contains($url, 'status=FILLED') &&
                       str_contains($url, 'maxResults=50');
            }))
            ->willReturn($mockResponse);

        $result = $this->api->orders($fromTime, $toTime, 50, 'FILLED');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('FILLED', $result[0]['status']);
    }

    /**
     * @test
     */
    public function testOrdersThrowsExceptionWhenOnlyFromTimeProvided(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('If you set fromTime, you are required to set toTime as well.');

        $this->api->orders(Carbon::now(), null);
    }

    /**
     * @test
     */
    public function testOrdersThrowsExceptionWhenOnlyToTimeProvided(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('If you set fromTime, you are required to set toTime as well.');

        $this->api->orders(null, Carbon::now());
    }

    /**
     * @test
     */
    public function testOrdersThrowsExceptionForInvalidStatus(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid status');

        $mockResponse = new Response(200, [], '[]');

        $this->mockClient
            ->expects($this->never())
            ->method('get');

        $this->api->orders(null, null, null, 'INVALID_STATUS');
    }

    /**
     * @test
     */
    public function testOrdersForAccount(): void {
        $fromTime = Carbon::now()->subDays(30);
        $toTime = Carbon::now();

        $expectedResponse = [
            ['orderId' => 12345, 'status' => 'FILLED', 'symbol' => 'AAPL']
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('/trader/v1/accounts/HASH123/orders'))
            ->willReturn($mockResponse);

        $result = $this->api->ordersForAccount('HASH123', 100, $fromTime, $toTime, 'FILLED');

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('AAPL', $result[0]['symbol']);
    }

    /**
     * @test
     */
    public function testOrderForAccount(): void {
        $expectedResponse = [
            'orderId' => 12345,
            'status' => 'FILLED',
            'symbol' => 'AAPL',
            'quantity' => 10
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('/trader/v1/accounts/HASH123/orders/12345'))
            ->willReturn($mockResponse);

        $result = $this->api->orderForAccount('HASH123', 12345);

        $this->assertIsArray($result);
        $this->assertEquals(12345, $result['orderId']);
        $this->assertEquals('FILLED', $result['status']);
    }

    /**
     * @test
     */
    public function testPlaceBuyOrder(): void {
        $mockResponse = new Response(201, []);

        $this->mockClient
            ->expects($this->once())
            ->method('post')
            ->with(
                $this->stringContains('/trader/v1/accounts/HASH123/orders'),
                $this->callback(function ($options) {
                    return isset($options['body']) &&
                           isset($options['headers']['Content-Type']) &&
                           $options['headers']['Content-Type'] === 'application/json';
                })
            )
            ->willReturn($mockResponse);

        $result = $this->api->placeBuyOrder('HASH123', 'AAPL', 10);

        $this->assertEquals(201, $result);
    }

    /**
     * @test
     */
    public function testPlaceSellOrder(): void {
        $mockResponse = new Response(201, []);

        $this->mockClient
            ->expects($this->once())
            ->method('post')
            ->with(
                $this->stringContains('/trader/v1/accounts/HASH123/orders'),
                $this->callback(function ($options) {
                    return isset($options['body']);
                })
            )
            ->willReturn($mockResponse);

        $result = $this->api->placeSellOrder('HASH123', 'AAPL', 10);

        $this->assertEquals(201, $result);
    }

    /**
     * @test
     */
    public function testReplaceOrder(): void {
        $mockResponse = new Response(200, []);

        $orderPayload = json_encode([
            'orderType' => 'LIMIT',
            'session' => 'NORMAL',
            'duration' => 'DAY',
            'price' => 150.00
        ]);

        $this->mockClient
            ->expects($this->once())
            ->method('put')
            ->with(
                $this->stringContains('/trader/v1/accounts/HASH123/orders/12345'),
                $this->callback(function ($options) {
                    return isset($options['body']) &&
                           isset($options['headers']['Content-Type']) &&
                           $options['headers']['Content-Type'] === 'application/json';
                })
            )
            ->willReturn($mockResponse);

        $result = $this->api->replaceOrder('HASH123', 12345, $orderPayload);

        $this->assertEquals(200, $result);
    }

    /**
     * @test
     */
    public function testCancelOrder(): void {
        $mockResponse = new Response(200, []);

        $this->mockClient
            ->expects($this->once())
            ->method('delete')
            ->with($this->stringContains('/trader/v1/accounts/HASH123/orders/12345'))
            ->willReturn($mockResponse);

        $result = $this->api->cancelOrder('HASH123', 12345);

        $this->assertEquals(200, $result);
    }

    /**
     * @test
     */
    public function testPreviewOrder(): void {
        $expectedResponse = [
            'orderBalance' => [
                'orderValue' => 1500.00,
                'projectedAvailableFund' => 8500.00,
                'projectedBuyingPower' => 17000.00,
                'projectedCommission' => 0.00
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $orderPayload = json_encode([
            'orderType' => 'LIMIT',
            'session' => 'NORMAL',
            'duration' => 'DAY',
            'price' => 150.00,
            'orderLegCollection' => [
                [
                    'instruction' => 'BUY',
                    'quantity' => 10,
                    'instrument' => [
                        'symbol' => 'AAPL',
                        'assetType' => 'EQUITY'
                    ]
                ]
            ]
        ]);

        $this->mockClient
            ->expects($this->once())
            ->method('post')
            ->with(
                $this->stringContains('/trader/v1/accounts/HASH123/previewOrder'),
                $this->callback(function ($options) {
                    return isset($options['body']);
                })
            )
            ->willReturn($mockResponse);

        $result = $this->api->previewOrder('HASH123', $orderPayload);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('orderBalance', $result);
        $this->assertEquals(1500.00, $result['orderBalance']['orderValue']);
    }

    /**
     * @test
     */
    public function testValidStatusConstants(): void {
        $reflection = new \ReflectionClass($this->api);

        // Verify all status constants are defined
        $this->assertTrue($reflection->hasConstant('FILLED'));
        $this->assertTrue($reflection->hasConstant('WORKING'));
        $this->assertTrue($reflection->hasConstant('CANCELED'));
        $this->assertTrue($reflection->hasConstant('REJECTED'));

        // Verify VALID_STATUSES array exists
        $this->assertTrue($reflection->hasConstant('VALID_STATUSES'));

        // Get the constant values
        $validStatuses = $reflection->getConstant('VALID_STATUSES');

        // Verify it's an array and contains expected values
        $this->assertIsArray($validStatuses);
        $this->assertContains('FILLED', $validStatuses);
        $this->assertContains('WORKING', $validStatuses);
        $this->assertContains('CANCELED', $validStatuses);
    }
}
