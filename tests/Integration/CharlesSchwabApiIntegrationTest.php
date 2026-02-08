<?php

namespace MichaelDrennen\SchwabAPI\Tests\Integration;

use GuzzleHttp\Client;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for the Schwab API
 * These tests require valid credentials and will make real API calls
 *
 * @group integration
 */
class CharlesSchwabApiIntegrationTest extends TestCase {

    protected string $code;
    protected string $session;
    protected SchwabAPI $api;

    protected function setUp(): void {
        $this->code     = $_ENV['CODE'];
        $this->session  = $_ENV['SESSION'] ?? '';
        $apiKey         = $_ENV['SCHWAB_API_KEY'];
        $apiSecret      = $_ENV['SCHWAB_API_SECRET'];
        $apiCallbackUri = $_ENV['SCHWAB_TOKEN_CALLBACK_URL'];

        $this->api = new SchwabAPI(
            apiKey: $apiKey,
            apiSecret: $apiSecret,
            apiCallbackUrl: $apiCallbackUri,
            authenticationCode: $this->code,
            accessToken: null,
            refreshToken: null,
            debug: true
        );
    }

    /**
     * @test
     */
    public function testConstructorShouldCreateApiObject(): void {
        $this->assertInstanceOf(SchwabAPI::class, $this->api);
    }

    /**
     * @test
     * @group authentication
     */
    public function testRequestTokenShouldGetToken(): void {
        $this->api->requestToken();

        $accessToken = $this->api->getAccessToken();
        $refreshToken = $this->api->getRefreshToken();
        $expiresIn = $this->api->getExpiresIn();

        $this->assertNotEmpty($accessToken);
        $this->assertNotEmpty($refreshToken);
        $this->assertGreaterThan(0, $expiresIn);
        $this->assertLessThanOrEqual(1800, $expiresIn); // 30 minutes max
    }

    /**
     * @test
     * @group accounts
     */
    public function testAccountNumbersShouldReturnArray(): void {
        // First get a token
        $this->api->requestToken();

        $accountNumbers = $this->api->accountNumbers();

        $this->assertIsArray($accountNumbers);
        $this->assertNotEmpty($accountNumbers);

        // Each account should have accountNumber and hashValue
        foreach ($accountNumbers as $account) {
            $this->assertArrayHasKey('accountNumber', $account);
            $this->assertArrayHasKey('hashValue', $account);
        }
    }

    /**
     * @test
     * @group accounts
     */
    public function testAccountsShouldReturnData(): void {
        $this->api->requestToken();

        $accounts = $this->api->accounts();

        $this->assertIsArray($accounts);
        $this->assertNotEmpty($accounts);

        // Each account should have securitiesAccount
        foreach ($accounts as $account) {
            $this->assertArrayHasKey('securitiesAccount', $account);
        }
    }

    /**
     * @test
     * @group accounts
     */
    public function testAccountsWithPositionsShouldReturnPositions(): void {
        $this->api->requestToken();

        $accounts = $this->api->accounts(positions: true);

        $this->assertIsArray($accounts);
    }

    /**
     * @test
     * @group preferences
     */
    public function testUserPreferenceShouldReturnData(): void {
        $this->api->requestToken();

        $preferences = $this->api->userPreference();

        $this->assertIsArray($preferences);
    }

    /**
     * @test
     * @group markets
     */
    public function testInvalidMarketHoursSymbolShouldThrowException(): void {
        $this->expectException(\Exception::class);

        $this->api->requestToken();
        $this->api->markets(['equity']);
    }

    /**
     * @test
     * @group markets
     */
    public function testGetNextOpenDateForMarket(): void {
        $this->api->requestToken();

        $carbonDate = $this->api->getNextOpenDateForMarket('equity');

        $this->assertInstanceOf(\Carbon\Carbon::class, $carbonDate);
    }

    /**
     * @test
     * @group authentication
     */
    public function testGetAuthorizeUrlReturnsValidUrl(): void {
        $url = $this->api->getAuthorizeUrl();

        $this->assertStringStartsWith('https://api.schwabapi.com/v1/oauth/authorize', $url);
        $this->assertStringContainsString('client_id=', $url);
        $this->assertStringContainsString('redirect_uri=', $url);
    }

    /**
     * @test
     * @group authentication
     */
    public function testToStringDoesNotExposeSensitiveData(): void {
        $stringRepresentation = (string) $this->api;

        // Should contain masked data indicators
        $this->assertStringContainsString('****', $stringRepresentation);

        // Should NOT contain the actual API key or secret
        $this->assertStringNotContainsString($_ENV['SCHWAB_API_KEY'], $stringRepresentation);
        $this->assertStringNotContainsString($_ENV['SCHWAB_API_SECRET'], $stringRepresentation);
    }
}
