<?php

namespace MichaelDrennen\SchwabAPI\Tests\Integration;

use GuzzleHttp\Client;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use MichaelDrennen\SchwabAPI\Tests\Helpers\OAuthAutomation;
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
        $apiKey         = $_ENV['SCHWAB_API_KEY'];
        $apiSecret      = $_ENV['SCHWAB_API_SECRET'];
        $apiCallbackUri = $_ENV['SCHWAB_TOKEN_CALLBACK_URL'];
        $chromePath     = $_ENV['CHROME_PATH'] ?? '';
        $username       = $_ENV['SCHWAB_USERNAME'] ?? '';
        $password       = $_ENV['SCHWAB_PASSWORD'] ?? '';

        // Attempt to get a fresh OAuth code using automation
        // Only if username, password, and Chrome path are provided
        if (!empty($username) && !empty($password) && !empty($chromePath) && file_exists($chromePath)) {
            try {
                $automation = new OAuthAutomation(
                    chromePath: $chromePath,
                    apiKey: $apiKey,
                    callbackUrl: $apiCallbackUri,
                    username: $username,
                    password: $password
                );

                $authData = $automation->getAuthorizationCode();
                $this->code = $authData['code'];
                $this->session = $authData['session'];

                echo "\n✓ Successfully obtained fresh OAuth code via automation\n";
            } catch (\Exception $e) {
                // Fall back to using CODE from ENV if automation fails
                echo "\n⚠ OAuth automation failed: {$e->getMessage()}\n";
                echo "Falling back to CODE from ENV\n";
                $this->code = $_ENV['CODE'];
                $this->session = $_ENV['SESSION'] ?? '';
            }
        } else {
            // Use manual OAuth code from ENV
            $this->code = $_ENV['CODE'];
            $this->session = $_ENV['SESSION'] ?? '';
        }

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
        try {
            $this->api->requestToken();

            $accessToken = $this->api->getAccessToken();
            $refreshToken = $this->api->getRefreshToken();
            $expiresIn = $this->api->getExpiresIn();

            $this->assertNotEmpty($accessToken);
            $this->assertNotEmpty($refreshToken);
            $this->assertGreaterThan(0, $expiresIn);
            $this->assertLessThanOrEqual(1800, $expiresIn); // 30 minutes max
        } catch (\MichaelDrennen\SchwabAPI\Exceptions\RequestException $e) {
            if (str_contains($e->getMessage(), 'Bad authorization') ||
                str_contains($e->getMessage(), 'unsupported_token_type') ||
                str_contains($e->getResponseBody(), 'Bad authorization')) {
                $this->markTestSkipped('Authorization code expired. Get a fresh code from Schwab OAuth flow.');
            }
            throw $e;
        }
    }

    /**
     * @test
     * @group accounts
     */
    public function testAccountNumbersShouldReturnArray(): void {
        try {
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
        } catch (\MichaelDrennen\SchwabAPI\Exceptions\RequestException $e) {
            if (str_contains($e->getMessage(), 'Bad authorization') ||
                str_contains($e->getMessage(), 'unsupported_token_type') ||
                str_contains($e->getResponseBody(), 'Bad authorization')) {
                $this->markTestSkipped('Authorization code expired. Get a fresh code from Schwab OAuth flow.');
            }
            throw $e;
        }
    }

    /**
     * @test
     * @group accounts
     */
    public function testAccountsShouldReturnData(): void {
        try {
            $this->api->requestToken();

            $accounts = $this->api->accounts();

            $this->assertIsArray($accounts);
            $this->assertNotEmpty($accounts);

            // Each account should have securitiesAccount
            foreach ($accounts as $account) {
                $this->assertArrayHasKey('securitiesAccount', $account);
            }
        } catch (\MichaelDrennen\SchwabAPI\Exceptions\RequestException $e) {
            if (str_contains($e->getMessage(), 'Bad authorization') ||
                str_contains($e->getMessage(), 'unsupported_token_type') ||
                str_contains($e->getResponseBody(), 'Bad authorization')) {
                $this->markTestSkipped('Authorization code expired. Get a fresh code from Schwab OAuth flow.');
            }
            throw $e;
        }
    }

    /**
     * @test
     * @group accounts
     */
    public function testAccountsWithPositionsShouldReturnPositions(): void {
        try {
            $this->api->requestToken();

            $accounts = $this->api->accounts(positions: true);

            $this->assertIsArray($accounts);
        } catch (\MichaelDrennen\SchwabAPI\Exceptions\RequestException $e) {
            if (str_contains($e->getMessage(), 'Bad authorization') ||
                str_contains($e->getMessage(), 'unsupported_token_type') ||
                str_contains($e->getResponseBody(), 'Bad authorization')) {
                $this->markTestSkipped('Authorization code expired. Get a fresh code from Schwab OAuth flow.');
            }
            throw $e;
        }
    }

    /**
     * @test
     * @group preferences
     */
    public function testUserPreferenceShouldReturnData(): void {
        try {
            $this->api->requestToken();

            $preferences = $this->api->userPreference();

            $this->assertIsArray($preferences);
        } catch (\MichaelDrennen\SchwabAPI\Exceptions\RequestException $e) {
            if (str_contains($e->getMessage(), 'Bad authorization') ||
                str_contains($e->getMessage(), 'unsupported_token_type') ||
                str_contains($e->getResponseBody(), 'Bad authorization')) {
                $this->markTestSkipped('Authorization code expired. Get a fresh code from Schwab OAuth flow.');
            }
            throw $e;
        }
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
        try {
            $this->api->requestToken();

            $carbonDate = $this->api->getNextOpenDateForMarket('equity');

            $this->assertInstanceOf(\Carbon\Carbon::class, $carbonDate);
        } catch (\MichaelDrennen\SchwabAPI\Exceptions\RequestException $e) {
            if (str_contains($e->getMessage(), 'Bad authorization') ||
                str_contains($e->getMessage(), 'unsupported_token_type') ||
                str_contains($e->getResponseBody(), 'Bad authorization')) {
                $this->markTestSkipped('Authorization code expired. Get a fresh code from Schwab OAuth flow.');
            }
            throw $e;
        }
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
