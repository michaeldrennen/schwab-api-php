<?php

namespace MichaelDrennen\SchwabAPI\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use PHPUnit\Framework\TestCase;

class AccountRequestsTest extends TestCase {

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
    public function testAccountNumbersReturnsArray(): void {
        $expectedResponse = [
            ['accountNumber' => '12345', 'hashValue' => 'ABC123'],
            ['accountNumber' => '67890', 'hashValue' => 'DEF456']
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('/trader/v1/accounts/accountNumbers'))
            ->willReturn($mockResponse);

        $result = $this->api->accountNumbers();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('12345', $result[0]['accountNumber']);
        $this->assertEquals('ABC123', $result[0]['hashValue']);
    }

    /**
     * @test
     */
    public function testAccountsWithoutPositions(): void {
        $expectedResponse = [
            [
                'securitiesAccount' => [
                    'accountNumber' => '12345',
                    'type' => 'MARGIN',
                    'currentBalances' => ['cashBalance' => 10000]
                ]
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('/trader/v1/accounts'))
            ->willReturn($mockResponse);

        $result = $this->api->accounts(false);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('12345', $result[0]['securitiesAccount']['accountNumber']);
    }

    /**
     * @test
     */
    public function testAccountsWithPositions(): void {
        $expectedResponse = [
            [
                'securitiesAccount' => [
                    'accountNumber' => '12345',
                    'positions' => [
                        ['symbol' => 'AAPL', 'quantity' => 10]
                    ]
                ]
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('fields=positions'))
            ->willReturn($mockResponse);

        $result = $this->api->accounts(true);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('positions', $result[0]['securitiesAccount']);
    }

    /**
     * @test
     */
    public function testAccountsByNumber(): void {
        $expectedResponse = [
            [
                'securitiesAccount' => [
                    'accountNumber' => '12345',
                    'type' => 'MARGIN'
                ]
            ],
            [
                'securitiesAccount' => [
                    'accountNumber' => '67890',
                    'type' => 'CASH'
                ]
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($mockResponse);

        $result = $this->api->accountsByNumber();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('12345', $result);
        $this->assertArrayHasKey('67890', $result);
        $this->assertEquals('MARGIN', $result['12345']['securitiesAccount']['type']);
    }

    /**
     * @test
     */
    public function testAccountByNumberFound(): void {
        $expectedResponse = [
            [
                'securitiesAccount' => [
                    'accountNumber' => '12345',
                    'type' => 'MARGIN'
                ]
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($mockResponse);

        $result = $this->api->accountByNumber(12345);

        $this->assertIsArray($result);
        $this->assertEquals('12345', $result['securitiesAccount']['accountNumber']);
    }

    /**
     * @test
     */
    public function testAccountByNumberNotFound(): void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Unable to find account with accountNumber: 99999');

        $expectedResponse = [
            [
                'securitiesAccount' => [
                    'accountNumber' => '12345',
                    'type' => 'MARGIN'
                ]
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($mockResponse);

        $this->api->accountByNumber(99999);
    }

    /**
     * @test
     */
    public function testAccountByHashValue(): void {
        $expectedResponse = [
            'securitiesAccount' => [
                'accountNumber' => '12345',
                'type' => 'MARGIN',
                'positions' => []
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->with($this->stringContains('/trader/v1/accounts/HASH123'))
            ->willReturn($mockResponse);

        $result = $this->api->account('HASH123', ['positions']);

        $this->assertIsArray($result);
        $this->assertEquals('12345', $result['securitiesAccount']['accountNumber']);
    }

    /**
     * @test
     */
    public function testGetLongEquityPositionsReturnsEmpty(): void {
        $expectedResponse = [
            'securitiesAccount' => [
                'accountNumber' => '12345'
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($mockResponse);

        $result = $this->api->getLongEquityPositions('HASH123');

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function testGetLongEquityPositionsReturnsFiltered(): void {
        $expectedResponse = [
            'securitiesAccount' => [
                'accountNumber' => '12345',
                'positions' => [
                    [
                        'longQuantity' => 10.0,
                        'shortQuantity' => 0.0,
                        'instrument' => [
                            'assetType' => 'EQUITY',
                            'symbol' => 'AAPL',
                            'cusip' => '037833100',
                            'netChange' => 1.5
                        ]
                    ],
                    [
                        'longQuantity' => 0.0,
                        'shortQuantity' => 5.0,
                        'instrument' => [
                            'assetType' => 'EQUITY',
                            'symbol' => 'MSFT',
                            'cusip' => '594918104',
                            'netChange' => -0.5
                        ]
                    ],
                    [
                        'longQuantity' => 5.0,
                        'shortQuantity' => 0.0,
                        'instrument' => [
                            'assetType' => 'OPTION',
                            'symbol' => 'AAPL_123456',
                            'cusip' => '123456789',
                            'netChange' => 0.25
                        ]
                    ]
                ]
            ]
        ];

        $mockResponse = new Response(200, [], json_encode($expectedResponse));

        $this->mockClient
            ->expects($this->once())
            ->method('get')
            ->willReturn($mockResponse);

        $result = $this->api->getLongEquityPositions('HASH123');

        // Should only return long equity positions (not short, not options)
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('AAPL', $result[0]['symbol']);
        $this->assertEquals('EQUITY', $result[0]['assetType']);
        $this->assertEquals(10.0, $result[0]['longQuantity']);
    }
}
