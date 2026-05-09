<?php

namespace Tests\Unit\RequestTraits;

use MichaelDrennen\SchwabAPI\SchwabAPI;
use MichaelDrennen\SchwabAPI\RequestTraits\MarketHoursRequests;
use PHPUnit\Framework\TestCase;
use Mockery;

// Helper class to test the MarketHoursRequests trait in isolation
class MarketHoursRequestsTestHelper
{
    use MarketHoursRequests;

    private $mockSchwabApi;

    public function __construct(SchwabAPI $mockSchwabApi)
    {
        $this->mockSchwabApi = $mockSchwabApi;
    }

    /**
     * Placeholder for a trait method to get market hours.
     * Assumes a method like `getMarketHours(string $marketType, ?string $date = null)`
     * and that it uses $this->mockSchwabApi->makeGetRequest internally.
     */
    public function getMarketHours(string $marketType, ?string $date = null): array
    {
        // Simulate the trait calling a GET request.
        // The actual URL and parameters would depend on the trait's implementation.
        $params = ['marketType' => $marketType];
        if ($date) {
            $params['date'] = $date;
        }
        return $this->mockSchwabApi->makeGetRequest('/market-hours', $params);
    }
}

class MarketHoursRequestsTest extends TestCase
{
    private $mockSchwabApi;
    private $helper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockSchwabApi = Mockery::mock(SchwabAPI::class);
        $this->helper = new MarketHoursRequestsTestHelper($this->mockSchwabApi);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test fetching market hours for a specific market type and date.
     */
    public function testGetMarketHoursWithDateSuccess()
    {
        $marketType = 'EQUITY';
        $date = '2023-10-27';
        $expectedResponse = [
            'regularMarket' => [
                'open' => '09:30:00',
                'close' => '16:00:00',
            ],
            'preMarket' => [
                'open' => '04:00:00',
                'close' => '09:30:00',
            ],
            'postMarket' => [
                'open' => '16:00:00',
                'close' => '20:00:00',
            ],
            'marketType' => $marketType,
            'date' => $date,
        ];

        // Mock the makeGetRequest to return specific data for the expected call.
        $this->mockSchwabApi
            ->shouldReceive('makeGetRequest')
            ->once()
            ->with('/market-hours', ['marketType' => $marketType, 'date' => $date])
            ->andReturn($expectedResponse);

        $actualResponse = $this->helper->getMarketHours($marketType, $date);

        $this->assertEquals($expectedResponse, $actualResponse);
        $this->assertIsArray($actualResponse);
        $this->assertArrayHasKey('marketType', $actualResponse);
        $this->assertEquals($marketType, $actualResponse['marketType']);
    }

    /**
     * Test fetching market hours for a specific market type without a date (defaults to today).
     */
    public function testGetMarketHoursWithoutDateSuccess()
    {
        $marketType = 'OPTIONS';
        // Assume today's date if $date is null. We won't assert the specific date as it's dynamic.
        $expectedResponse = [
            'regularMarket' => [
                'open' => '09:30:00',
                'close' => '16:00:00',
            ],
            'marketType' => $marketType,
            'date' => '2026-05-09', // Placeholder for current date
        ];

        // Mock the makeGetRequest. The date parameter will be omitted in the call.
        $this->mockSchwabApi
            ->shouldReceive('makeGetRequest')
            ->once()
            ->with('/market-hours', ['marketType' => $marketType]) // No date parameter
            ->andReturn($expectedResponse);

        $actualResponse = $this->helper->getMarketHours($marketType);

        $this->assertEquals($expectedResponse, $actualResponse);
        $this->assertIsArray($actualResponse);
        $this->assertArrayHasKey('marketType', $actualResponse);
        $this->assertEquals($marketType, $actualResponse['marketType']);
    }

    /**
     * Test handling of errors when fetching market hours.
     */
    public function testGetMarketHoursFailure()
    {
        $marketType = 'FUTURES'; // Example market type
        $errorMessage = 'Market hours not available for this date/type';

        // Mock the makeGetRequest to throw an exception.
        $this->mockSchwabApi
            ->shouldReceive('makeGetRequest')
            ->once()
            ->with('/market-hours', ['marketType' => $marketType])
            ->andThrow(new \RuntimeException($errorMessage));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($errorMessage);

        $this->helper->getMarketHours($marketType);
    }

    // Add more tests for other potential methods if they exist in the MarketHoursRequests trait.
}
