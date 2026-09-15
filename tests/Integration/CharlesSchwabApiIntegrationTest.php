<?php

namespace MichaelDrennen\SchwabAPI\Tests\Integration;

use Carbon\Carbon;
use GuzzleHttp\Client;
use MichaelDrennen\SchwabAPI\SchwabAPI;
use MichaelDrennen\SchwabAPI\Schemas\Account;
use MichaelDrennen\SchwabAPI\Schemas\AccountNumberHash;
use MichaelDrennen\SchwabAPI\Schemas\Candle;
use MichaelDrennen\SchwabAPI\Schemas\CandleList;
use MichaelDrennen\SchwabAPI\Schemas\ExpirationChain;
use MichaelDrennen\SchwabAPI\Schemas\Hours;
use MichaelDrennen\SchwabAPI\Schemas\Instrument;
use MichaelDrennen\SchwabAPI\Schemas\InstrumentResponse;
use MichaelDrennen\SchwabAPI\Schemas\OptionChain;
use MichaelDrennen\SchwabAPI\Schemas\Order;
use MichaelDrennen\SchwabAPI\Schemas\Position;
use MichaelDrennen\SchwabAPI\Schemas\QuoteResponse;
use MichaelDrennen\SchwabAPI\Schemas\QuoteResponseObject;
use MichaelDrennen\SchwabAPI\Schemas\Screener;
use MichaelDrennen\SchwabAPI\Schemas\SecuritiesAccount;
use MichaelDrennen\SchwabAPI\Schemas\Transaction;
use MichaelDrennen\SchwabAPI\Schemas\UserPreference;
use MichaelDrennen\SchwabAPI\Tests\Helpers\OAuthAutomation;
use PHPUnit\Framework\TestCase;

/**
 * Integration tests for the Schwab API
 * These tests require valid credentials and will make real API calls
 *
 * @group integration
 */
class CharlesSchwabApiIntegrationTest extends TestCase {

    protected string $code = '';
    protected string $session = '';
    protected ?string $refreshToken = null;
    protected SchwabAPI $api;

    protected function setUp(): void {
        $envFile = dirname(__DIR__, 2) . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines !== false) {
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '' || str_starts_with($line, '#')) {
                        continue;
                    }
                    if (str_contains($line, '=')) {
                        [$key, $val] = explode('=', $line, 2);
                        $key = trim($key);
                        $val = trim($val);
                        if ((str_starts_with($val, '"') && str_ends_with($val, '"')) ||
                            (str_starts_with($val, "'") && str_ends_with($val, "'"))) {
                            $val = substr($val, 1, -1);
                        }
                        if (!isset($_ENV[$key]) || empty($_ENV[$key])) {
                            $_ENV[$key] = $val;
                            putenv("{$key}={$val}");
                        }
                    }
                }
            }
        }

        $apiKey         = $_ENV['SCHWAB_API_KEY'] ?? '';
        $apiSecret      = $_ENV['SCHWAB_API_SECRET'] ?? '';
        $apiCallbackUri = $_ENV['SCHWAB_CALLBACK_URI'] ?? $_ENV['SCHWAB_TOKEN_CALLBACK_URL'] ?? '';
        $chromePath     = $_ENV['CHROME_PATH'] ?? '';
        $username       = $_ENV['SCHWAB_USERNAME'] ?? '';
        $password       = $_ENV['SCHWAB_PASSWORD'] ?? '';
        $this->refreshToken = !empty($_ENV['SCHWAB_REFRESH_TOKEN']) ? $_ENV['SCHWAB_REFRESH_TOKEN'] : null;

        if (empty($apiKey) || empty($apiSecret) || empty($apiCallbackUri)) {
            $this->markTestSkipped('Schwab API credentials are not set in the environment.');
        }

        // If refresh token is available, use it directly.
        // Otherwise, attempt to get a fresh OAuth code using automation if credentials are provided.
        if (empty($this->refreshToken) && !empty($username) && !empty($password) && !empty($chromePath) && file_exists($chromePath)) {
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
                // Fall back to using CODE or REFRESH_TOKEN from ENV if automation fails
                echo "\n⚠ OAuth automation failed: {$e->getMessage()}\n";
                $this->code = $_ENV['CODE'] ?? '';
                $this->session = $_ENV['SESSION'] ?? '';
            }
        } else {
            // Use manual OAuth code or refresh token from ENV
            $code = $_ENV['CODE'] ?? '';
            $this->code = ($code === 'your_authorization_code_here') ? '' : $code;
            $this->session = $_ENV['SESSION'] ?? '';
        }

        // Check if we have either an authorization code or a refresh token
        if (empty($this->code) && empty($this->refreshToken)) {
            $this->markTestSkipped('Neither OAuth code nor SCHWAB_REFRESH_TOKEN are available for integration testing.');
        }

        $this->api = new SchwabAPI(
            apiKey: $apiKey,
            apiSecret: $apiSecret,
            apiCallbackUrl: $apiCallbackUri,
            authenticationCode: $this->code ?: null,
            accessToken: null,
            refreshToken: $this->refreshToken,
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

            // Hydrate into AccountNumberHash schema collection
            $accountNumberHashes = AccountNumberHash::fromCollection($accountNumbers);
            $this->assertNotEmpty($accountNumberHashes);
            foreach ($accountNumberHashes as $accountNumberHash) {
                $this->assertInstanceOf(AccountNumberHash::class, $accountNumberHash);
                $this->assertNotEmpty($accountNumberHash->getAccountNumber());
                $this->assertNotEmpty($accountNumberHash->getHashValue());
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

            // Hydrate into Account schema collection
            $accountObjects = Account::fromCollection($accounts);
            $this->assertNotEmpty($accountObjects);
            foreach ($accountObjects as $accountObject) {
                $this->assertInstanceOf(Account::class, $accountObject);
                $securitiesAccount = $accountObject->getSecuritiesAccount();
                $this->assertInstanceOf(SecuritiesAccount::class, $securitiesAccount);
                $this->assertNotEmpty($securitiesAccount->getAccountNumber());
                $this->assertNotEmpty($securitiesAccount->getType());
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
            $this->assertNotEmpty($accounts);

            // Hydrate into Account schema collection with nested positions
            $accountObjects = Account::fromCollection($accounts);
            $this->assertNotEmpty($accountObjects);
            foreach ($accountObjects as $accountObject) {
                $this->assertInstanceOf(Account::class, $accountObject);
                $securitiesAccount = $accountObject->getSecuritiesAccount();
                $this->assertInstanceOf(SecuritiesAccount::class, $securitiesAccount);
                $this->assertNotEmpty($securitiesAccount->getAccountNumber());

                if (!empty($securitiesAccount->getPositions())) {
                    foreach ($securitiesAccount->getPositions() as $position) {
                        $this->assertInstanceOf(Position::class, $position);
                        if ($position->getInstrument() !== null) {
                            $this->assertInstanceOf(Instrument::class, $position->getInstrument());
                        }
                    }
                }
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
    public function testSingleAccountShouldReturnDataAndHydrateAccountSchema(): void {
        try {
            $this->api->requestToken();

            $accountNumbers = $this->api->accountNumbers();
            $this->assertNotEmpty($accountNumbers);

            $hashValue = $accountNumbers[0]['hashValue'];
            $accountData = $this->api->account($hashValue, ['positions']);

            $this->assertIsArray($accountData);
            $this->assertArrayHasKey('securitiesAccount', $accountData);

            // Hydrate single account into Account schema
            $account = Account::fromArray($accountData);
            $this->assertInstanceOf(Account::class, $account);

            $securitiesAccount = $account->getSecuritiesAccount();
            $this->assertInstanceOf(SecuritiesAccount::class, $securitiesAccount);
            $this->assertNotEmpty($securitiesAccount->getAccountNumber());
            $this->assertNotEmpty($securitiesAccount->getType());

            if (!empty($securitiesAccount->getPositions())) {
                foreach ($securitiesAccount->getPositions() as $position) {
                    $this->assertInstanceOf(Position::class, $position);
                }
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
     * @group preferences
     */
    public function testUserPreferenceShouldReturnData(): void {
        try {
            $this->api->requestToken();

            $preferences = $this->api->userPreference();

            $this->assertIsArray($preferences);
            $this->assertNotEmpty($preferences);

            $userPref = UserPreference::fromArray($preferences[0] ?? $preferences);
            $this->assertInstanceOf(UserPreference::class, $userPref);
            $this->assertIsArray($userPref->getAccounts());
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
     * @group quotes
     * @group marketdata
     */
    public function testQuotesShouldReturnDataAndHydrateQuoteResponse(): void {
        try {
            $this->api->requestToken();

            $quotesData = $this->api->quotes(['AAPL', 'MSFT']);

            $this->assertIsArray($quotesData);
            $this->assertArrayHasKey('AAPL', $quotesData);
            $this->assertArrayHasKey('MSFT', $quotesData);

            $quoteResponse = QuoteResponse::fromArray($quotesData);
            $this->assertInstanceOf(QuoteResponse::class, $quoteResponse);
            $this->assertNotEmpty($quoteResponse->getQuotes());

            $aaplQuote = $quoteResponse->getQuote('AAPL');
            $this->assertInstanceOf(QuoteResponseObject::class, $aaplQuote);
            $this->assertEquals('AAPL', $aaplQuote->getSymbol());
            $this->assertNotNull($aaplQuote->getQuote());
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
     * @group quotes
     * @group marketdata
     */
    public function testQuotesBySymbolShouldReturnDataAndHydrateQuoteResponse(): void {
        try {
            $this->api->requestToken();

            $quoteData = $this->api->quotesBySymbol('AAPL');

            $this->assertIsArray($quoteData);
            $this->assertArrayHasKey('AAPL', $quoteData);

            $quoteResponse = QuoteResponse::fromArray($quoteData);
            $this->assertInstanceOf(QuoteResponse::class, $quoteResponse);

            $aaplQuote = $quoteResponse->getQuote('AAPL');
            $this->assertInstanceOf(QuoteResponseObject::class, $aaplQuote);
            $this->assertEquals('AAPL', $aaplQuote->getSymbol());
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
     * @group optionchains
     * @group marketdata
     */
    public function testOptionChainsShouldReturnDataAndHydrateOptionChain(): void {
        try {
            $this->api->requestToken();

            $chainData = $this->api->chains('AAPL', strikeCount: 2);

            $this->assertIsArray($chainData);
            $this->assertArrayHasKey('symbol', $chainData);

            $optionChain = OptionChain::fromArray($chainData);
            $this->assertInstanceOf(OptionChain::class, $optionChain);
            $this->assertEquals('AAPL', $optionChain->getSymbol());
            $this->assertNotNull($optionChain->getStatus());
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
     * @group optionchains
     * @group marketdata
     */
    public function testOptionExpirationChainShouldReturnDataAndHydrateExpirationChain(): void {
        try {
            $this->api->requestToken();

            $expirationChainData = $this->api->expirationChain('AAPL');

            $this->assertIsArray($expirationChainData);
            $this->assertArrayHasKey('expirationList', $expirationChainData);

            $expirationChain = ExpirationChain::fromArray($expirationChainData);
            $this->assertInstanceOf(ExpirationChain::class, $expirationChain);
            $this->assertNotEmpty($expirationChain->getExpirationList());
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
     * @group pricehistory
     * @group marketdata
     */
    public function testPriceHistoryShouldReturnDataAndHydrateCandleList(): void {
        try {
            $this->api->requestToken();

            $priceHistoryData = $this->api->priceHistory(
                symbol: 'AAPL',
                periodType: 'day',
                period: 5,
                frequencyType: 'minute',
                frequency: 15
            );

            $this->assertIsArray($priceHistoryData);
            $this->assertArrayHasKey('candles', $priceHistoryData);

            $candleList = CandleList::fromArray($priceHistoryData);
            $this->assertInstanceOf(CandleList::class, $candleList);
            $this->assertEquals('AAPL', $candleList->getSymbol());
            $this->assertNotEmpty($candleList->getCandles());

            $firstCandle = $candleList->getCandles()[0];
            $this->assertInstanceOf(Candle::class, $firstCandle);
            $this->assertNotNull($firstCandle->getClose());
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
     * @group movers
     * @group marketdata
     */
    public function testMoversShouldReturnDataAndHydrateScreener(): void {
        try {
            $this->api->requestToken();

            $moversData = $this->api->movers('$DJI');

            $this->assertIsArray($moversData);

            $screener = Screener::fromArray($moversData);
            $this->assertInstanceOf(Screener::class, $screener);
            $this->assertIsArray($screener->getScreeners());
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
     * @group markethours
     * @group marketdata
     */
    public function testMarketsShouldReturnDataAndHydrateHours(): void {
        try {
            $this->api->requestToken();

            $marketsData = $this->api->markets(['equity', 'option']);

            $this->assertIsArray($marketsData);
            $this->assertArrayHasKey('equity', $marketsData);

            $hoursMap = Hours::fromCollection($marketsData);
            $this->assertNotEmpty($hoursMap);
            $this->assertArrayHasKey('equity', $hoursMap);
            $this->assertArrayHasKey('EQ', $hoursMap['equity']);
            $this->assertInstanceOf(Hours::class, $hoursMap['equity']['EQ']);
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
     * @group markethours
     * @group marketdata
     */
    public function testMarketsByIdShouldReturnDataAndHydrateHours(): void {
        try {
            $this->api->requestToken();

            $equityHoursData = $this->api->marketsById('equity');

            $this->assertIsArray($equityHoursData);
            $this->assertArrayHasKey('equity', $equityHoursData);

            $hoursMap = Hours::fromCollection($equityHoursData);
            $this->assertNotEmpty($hoursMap);
            $this->assertArrayHasKey('equity', $hoursMap);
            $this->assertArrayHasKey('EQ', $hoursMap['equity']);
            $this->assertInstanceOf(Hours::class, $hoursMap['equity']['EQ']);
            $this->assertNotNull($hoursMap['equity']['EQ']->getDate());
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
     * @group instruments
     * @group marketdata
     */
    public function testInstrumentsShouldReturnDataAndHydrateInstrumentResponse(): void {
        try {
            $this->api->requestToken();

            $instrumentsData = $this->api->instruments('AAPL', 'symbol-search');

            $this->assertIsArray($instrumentsData);
            $this->assertArrayHasKey('instruments', $instrumentsData);

            $instrumentResponse = InstrumentResponse::fromArray($instrumentsData);
            $this->assertInstanceOf(InstrumentResponse::class, $instrumentResponse);
            $this->assertNotEmpty($instrumentResponse->getInstruments());

            $firstInstrument = $instrumentResponse->getInstruments()[0];
            $this->assertInstanceOf(Instrument::class, $firstInstrument);
            $this->assertEquals('AAPL', $firstInstrument->getSymbol());
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
     * @group instruments
     * @group marketdata
     */
    public function testGetInstrumentFromTickerShouldReturnData(): void {
        try {
            $this->api->requestToken();

            $instrumentData = $this->api->getInstrumentFromTicker('AAPL');

            $this->assertIsArray($instrumentData);
            $this->assertArrayHasKey('instruments', $instrumentData);

            $instrumentResponse = InstrumentResponse::fromArray($instrumentData);
            $this->assertInstanceOf(InstrumentResponse::class, $instrumentResponse);
            $this->assertNotEmpty($instrumentResponse->getInstruments());
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
     * @group instruments
     * @group marketdata
     */
    public function testInstrumentByCusipShouldReturnDataAndHydrateInstrumentResponse(): void {
        try {
            $this->api->requestToken();

            $cusip = $this->api->getCusipFromTicker('AAPL');
            $this->assertNotEmpty($cusip);

            $instrumentData = $this->api->instrumentByCusip($cusip);

            $this->assertIsArray($instrumentData);
            $this->assertArrayHasKey('instruments', $instrumentData);

            $instrumentResponse = InstrumentResponse::fromArray($instrumentData);
            $this->assertInstanceOf(InstrumentResponse::class, $instrumentResponse);
            $this->assertNotEmpty($instrumentResponse->getInstruments());
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
     * @group orders
     * @group accounts
     */
    public function testOrdersShouldReturnDataAndHydrateOrderSchema(): void {
        try {
            $this->api->requestToken();

            $ordersData = $this->api->orders(
                fromTime: Carbon::now()->subDays(60),
                toTime: Carbon::now()
            );

            $this->assertIsArray($ordersData);

            $orders = Order::fromCollection($ordersData);
            $this->assertIsArray($orders);
            foreach ($orders as $order) {
                $this->assertInstanceOf(Order::class, $order);
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
     * @group orders
     * @group accounts
     */
    public function testOrdersForAccountShouldReturnDataAndHydrateOrderSchema(): void {
        try {
            $this->api->requestToken();

            $accountNumbers = $this->api->accountNumbers();
            $this->assertNotEmpty($accountNumbers);

            $hashValue = $accountNumbers[0]['hashValue'];
            $ordersData = $this->api->ordersForAccount(
                hashValueOfAccountNumber: $hashValue,
                fromTime: Carbon::now()->subDays(60),
                toTime: Carbon::now()
            );

            $this->assertIsArray($ordersData);

            $orders = Order::fromCollection($ordersData);
            $this->assertIsArray($orders);
            foreach ($orders as $order) {
                $this->assertInstanceOf(Order::class, $order);
                if ($order->getOrderId() !== null) {
                    $singleOrderData = $this->api->orderForAccount($hashValue, $order->getOrderId());
                    $this->assertIsArray($singleOrderData);
                    $singleOrder = Order::fromArray($singleOrderData);
                    $this->assertInstanceOf(Order::class, $singleOrder);
                    $this->assertEquals($order->getOrderId(), $singleOrder->getOrderId());
                }
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
     * @group transactions
     * @group accounts
     */
    public function testTransactionsShouldReturnDataAndHydrateTransactionSchema(): void {
        try {
            $this->api->requestToken();

            $accountNumbers = $this->api->accountNumbers();
            $this->assertNotEmpty($accountNumbers);

            $hashValue = $accountNumbers[0]['hashValue'];
            $transactionsData = $this->api->transactions(
                hashValueOfAccountNumber: $hashValue,
                startDate: Carbon::now()->subDays(60),
                endDate: Carbon::now()
            );

            $this->assertIsArray($transactionsData);

            $transactions = Transaction::fromCollection($transactionsData);
            $this->assertIsArray($transactions);
            foreach ($transactions as $transaction) {
                $this->assertInstanceOf(Transaction::class, $transaction);
                if ($transaction->getActivityId() !== null) {
                    $singleTxData = $this->api->transaction($hashValue, (string)$transaction->getActivityId());
                    $this->assertIsArray($singleTxData);
                    $singleTx = Transaction::fromArray($singleTxData);
                    $this->assertInstanceOf(Transaction::class, $singleTx);
                    $this->assertEquals($transaction->getActivityId(), $singleTx->getActivityId());
                }
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
     * @group markets
     */
    public function testInvalidMarketHoursSymbolShouldThrowException(): void {
        $this->expectException(\Exception::class);

        $this->api->requestToken();
        $this->api->markets(['invalid_market_symbol']);
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
