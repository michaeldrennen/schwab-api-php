<?php

namespace MichaelDrennen\SchwabAPI\Tests\Unit;

use MichaelDrennen\SchwabAPI\Schemas\Account;
use MichaelDrennen\SchwabAPI\Schemas\AccountNumberHash;
use MichaelDrennen\SchwabAPI\Schemas\Candle;
use MichaelDrennen\SchwabAPI\Schemas\CandleList;
use MichaelDrennen\SchwabAPI\Schemas\CashBalance;
use MichaelDrennen\SchwabAPI\Schemas\CashInitialBalance;
use MichaelDrennen\SchwabAPI\Schemas\CommissionAndFee;
use MichaelDrennen\SchwabAPI\Schemas\Expiration;
use MichaelDrennen\SchwabAPI\Schemas\ExpirationChain;
use MichaelDrennen\SchwabAPI\Schemas\Hours;
use MichaelDrennen\SchwabAPI\Schemas\Instrument;
use MichaelDrennen\SchwabAPI\Schemas\InstrumentResponse;
use MichaelDrennen\SchwabAPI\Schemas\MarginBalance;
use MichaelDrennen\SchwabAPI\Schemas\MarginInitialBalance;
use MichaelDrennen\SchwabAPI\Schemas\OptionChain;
use MichaelDrennen\SchwabAPI\Schemas\OptionContract;
use MichaelDrennen\SchwabAPI\Schemas\Order;
use MichaelDrennen\SchwabAPI\Schemas\Position;
use MichaelDrennen\SchwabAPI\Schemas\PreviewOrder;
use MichaelDrennen\SchwabAPI\Schemas\QuoteResponse;
use MichaelDrennen\SchwabAPI\Schemas\QuoteResponseObject;
use MichaelDrennen\SchwabAPI\Schemas\Screener;
use MichaelDrennen\SchwabAPI\Schemas\SecuritiesAccount;
use MichaelDrennen\SchwabAPI\Schemas\Transaction;
use MichaelDrennen\SchwabAPI\Schemas\UserPreference;
use PHPUnit\Framework\TestCase;

class SchemasTest extends TestCase {

    /**
     * @test
     */
    public function testAccountNumberHash(): void {
        $data = ['accountNumber' => '12345678', 'hashValue' => 'HASH123'];
        $model = AccountNumberHash::fromArray($data);

        $this->assertEquals('12345678', $model->getAccountNumber());
        $this->assertEquals('HASH123', $model->getHashValue());
        $this->assertEquals($data, $model->toArray());
    }

    /**
     * @test
     */
    public function testAccountAndSecuritiesAccountWithPositions(): void {
        $data = [
            'securitiesAccount' => [
                'type' => 'MARGIN',
                'accountNumber' => '12345678',
                'roundTrips' => 2,
                'isDayTrader' => false,
                'isClosingOnlyRestricted' => false,
                'pfcbFlag' => false,
                'positions' => [
                    [
                        'shortQuantity' => 0.0,
                        'averagePrice' => 150.25,
                        'currentDayProfitLoss' => 25.5,
                        'currentDayProfitLossPercentage' => 1.5,
                        'longQuantity' => 10.0,
                        'settledLongQuantity' => 10.0,
                        'settledShortQuantity' => 0.0,
                        'agedQuantity' => 0.0,
                        'instrument' => [
                            'cusip' => '037833100',
                            'symbol' => 'AAPL',
                            'description' => 'Apple Inc',
                            'exchange' => 'NASDAQ',
                            'assetType' => 'EQUITY',
                            'type' => 'COMMON',
                        ],
                        'marketValue' => 1502.5,
                        'maintenanceRequirement' => 450.75,
                        'averageLongPrice' => 150.25,
                        'averageShortPrice' => 0.0,
                        'taxLotAverageLongPrice' => 150.25,
                        'taxLotAverageShortPrice' => 0.0,
                        'longOpenProfitLoss' => 100.0,
                        'shortOpenProfitLoss' => 0.0,
                        'previousSessionLongQuantity' => 10.0,
                        'previousSessionShortQuantity' => 0.0,
                        'currentDayCost' => 1502.5,
                    ]
                ],
                'currentBalances' => [
                    'availableFunds' => 5000.0,
                    'buyingPower' => 10000.0,
                    'cashBalance' => 3000.0,
                ]
            ]
        ];

        $account = Account::fromArray($data);
        $this->assertInstanceOf(SecuritiesAccount::class, $account->getSecuritiesAccount());
        $this->assertEquals('MARGIN', $account->getSecuritiesAccount()->getType());
        $this->assertEquals('12345678', $account->getSecuritiesAccount()->getAccountNumber());

        $positions = $account->getSecuritiesAccount()->getPositions();
        $this->assertCount(1, $positions);
        $this->assertInstanceOf(Position::class, $positions[0]);
        $this->assertEquals(10.0, $positions[0]->getLongQuantity());
        $this->assertInstanceOf(Instrument::class, $positions[0]->getInstrument());
        $this->assertEquals('AAPL', $positions[0]->getInstrument()->getSymbol());

        $balances = $account->getSecuritiesAccount()->getCurrentBalances();
        $this->assertInstanceOf(MarginBalance::class, $balances);
        $this->assertEquals(5000.0, $balances->getAvailableFunds());
    }

    /**
     * @test
     */
    public function testOrderAndPreviewOrder(): void {
        $data = [
            'orderId' => 999888,
            'validationResult' => [
                'warns' => [],
                'errors' => []
            ],
            'commissionAndFee' => [
                'commission' => [
                    'commissionLegs' => [
                        ['commissionValues' => [['value' => 0.0, 'type' => 'COMMISSION']]]
                    ]
                ],
                'fee' => [
                    'feeLegs' => [
                        ['feeValues' => [['value' => 0.02, 'type' => 'SEC_FEE']]]
                    ]
                ]
            ],
            'orderStrategy' => [
                'orderType' => 'LIMIT',
                'session' => 'NORMAL',
                'duration' => 'DAY',
                'price' => 150.0,
                'quantity' => 10.0,
                'status' => 'WORKING',
                'orderLegCollection' => [
                    [
                        'orderLegType' => 'EQUITY',
                        'legId' => 1,
                        'instruction' => 'BUY',
                        'quantity' => 10.0,
                        'instrument' => [
                            'symbol' => 'AAPL',
                            'assetType' => 'EQUITY'
                        ]
                    ]
                ]
            ],
            'orderBalance' => [
                'orderValue' => 1500.0,
                'projectedAvailableFund' => 3500.0,
                'projectedBuyingPower' => 7000.0,
                'projectedCommission' => 0.02
            ]
        ];

        $preview = PreviewOrder::fromArray($data);
        $this->assertEquals(999888, $preview->getOrderId());
        $this->assertInstanceOf(CommissionAndFee::class, $preview->getCommissionAndFee());
        $this->assertInstanceOf(Order::class, $preview->getOrderStrategy());
        $this->assertEquals('LIMIT', $preview->getOrderStrategy()->getOrderType());
        $this->assertEquals(150.0, $preview->getOrderStrategy()->getPrice());
        $this->assertCount(1, $preview->getOrderStrategy()->getOrderLegCollection());
        $this->assertEquals('BUY', $preview->getOrderStrategy()->getOrderLegCollection()[0]->getInstruction());
    }

    /**
     * @test
     */
    public function testTransaction(): void {
        $data = [
            'activityId' => 112233,
            'time' => '2026-09-14T10:00:00Z',
            'user' => [
                'login' => 'trader1',
                'firstName' => 'John',
                'lastName' => 'Doe'
            ],
            'description' => 'Bought 10 AAPL',
            'accountNumber' => '12345678',
            'type' => 'TRADE',
            'status' => 'VALID',
            'netAmount' => -1500.0,
            'transferItems' => [
                [
                    'amount' => 10.0,
                    'cost' => 1500.0,
                    'price' => 150.0,
                    'positionEffect' => 'OPENING',
                    'instrument' => [
                        'symbol' => 'AAPL',
                        'assetType' => 'EQUITY'
                    ]
                ]
            ]
        ];

        $tx = Transaction::fromArray($data);
        $this->assertEquals(112233, $tx->getActivityId());
        $this->assertEquals('TRADE', $tx->getType());
        $this->assertEquals('John', $tx->getUser()->getFirstName());
        $this->assertCount(1, $tx->getTransferItems());
        $this->assertEquals(150.0, $tx->getTransferItems()[0]->getPrice());
    }

    /**
     * @test
     */
    public function testUserPreference(): void {
        $data = [
            'accounts' => [
                [
                    'accountNumber' => '12345678',
                    'primaryAccount' => true,
                    'type' => 'MARGIN',
                    'nickName' => 'Primary Trading'
                ]
            ],
            'streamerInfo' => [
                [
                    'streamerSocketUrl' => 'wss://streamer.schwab.com/ws',
                    'schwabClientCorrelId' => 'correl123'
                ]
            ],
            'offers' => [
                [
                    'level2Permissions' => true,
                    'mktDataPermission' => 'NON_PROFESSIONAL'
                ]
            ]
        ];

        $pref = UserPreference::fromArray($data);
        $this->assertCount(1, $pref->getAccounts());
        $this->assertTrue($pref->getAccounts()[0]->isPrimaryAccount());
        $this->assertEquals('Primary Trading', $pref->getAccounts()[0]->getNickName());
        $this->assertCount(1, $pref->getStreamerInfo());
        $this->assertEquals('wss://streamer.schwab.com/ws', $pref->getStreamerInfo()[0]->getStreamerSocketUrl());
        $this->assertCount(1, $pref->getOffers());
        $this->assertTrue($pref->getOffers()[0]->isLevel2Permissions());
    }

    /**
     * @test
     */
    public function testQuoteResponse(): void {
        $data = [
            'AAPL' => [
                'assetMainType' => 'EQUITY',
                'symbol' => 'AAPL',
                'realtime' => true,
                'quote' => [
                    '52WeekHigh' => 200.0,
                    '52WeekLow' => 120.0,
                    'askPrice' => 150.1,
                    'bidPrice' => 150.0,
                    'lastPrice' => 150.05,
                    'netChange' => 1.25,
                    'totalVolume' => 5000000.0
                ],
                'reference' => [
                    'cusip' => '037833100',
                    'description' => 'Apple Inc',
                    'exchange' => 'NASDAQ'
                ],
                'regular' => [
                    'regularMarketLastPrice' => 150.05,
                    'regularMarketNetChange' => 1.25
                ]
            ]
        ];

        $response = QuoteResponse::fromArray($data);
        $this->assertCount(1, $response->getQuotes());
        $quoteObj = $response->getQuote('AAPL');
        $this->assertInstanceOf(QuoteResponseObject::class, $quoteObj);
        $this->assertEquals('AAPL', $quoteObj->getSymbol());
        $this->assertEquals(150.05, $quoteObj->getQuote()->getLastPrice());
        $this->assertEquals(200.0, $quoteObj->getQuote()->getFiftyTwoWeekHigh());
        $this->assertEquals('Apple Inc', $quoteObj->getReference()->getDescription());
        $this->assertEquals(150.05, $quoteObj->getRegular()->getRegularMarketLastPrice());
    }

    /**
     * @test
     */
    public function testOptionChain(): void {
        $data = [
            'symbol' => 'AAPL',
            'status' => 'SUCCESS',
            'strategy' => 'SINGLE',
            'numberOfContracts' => 1,
            'underlying' => [
                'symbol' => 'AAPL',
                'bid' => 150.0,
                'ask' => 150.1,
                'last' => 150.05,
                'close' => 148.8
            ],
            'callExpDateMap' => [
                '2026-10-16:30' => [
                    '150.0' => [
                        [
                            'putCall' => 'CALL',
                            'symbol' => 'AAPL  261016C00150000',
                            'description' => 'AAPL 10/16/2026 150.00 C',
                            'bid' => 5.2,
                            'ask' => 5.3,
                            'last' => 5.25,
                            'strikePrice' => 150.0,
                            'daysToExpiration' => 30
                        ]
                    ]
                ]
            ]
        ];

        $chain = OptionChain::fromArray($data);
        $this->assertEquals('AAPL', $chain->getSymbol());
        $this->assertEquals(150.05, $chain->getUnderlying()->getLast());
        $callMap = $chain->getCallExpDateMap();
        $this->assertArrayHasKey('2026-10-16:30', $callMap);
        $contract = $callMap['2026-10-16:30']['150.0'][0];
        $this->assertInstanceOf(OptionContract::class, $contract);
        $this->assertEquals('CALL', $contract->getPutCall());
        $this->assertEquals(5.25, $contract->getLast());
    }

    /**
     * @test
     */
    public function testExpirationChain(): void {
        $data = [
            'status' => 'SUCCESS',
            'expirationList' => [
                [
                    'expirationDate' => '2026-10-16',
                    'daysToExpiration' => 30,
                    'expirationType' => 'S',
                    'standard' => true
                ]
            ]
        ];

        $expChain = ExpirationChain::fromArray($data);
        $this->assertEquals('SUCCESS', $expChain->getStatus());
        $this->assertCount(1, $expChain->getExpirationList());
        $this->assertInstanceOf(Expiration::class, $expChain->getExpirationList()[0]);
        $this->assertEquals('2026-10-16', $expChain->getExpirationList()[0]->getExpirationDate());
        $this->assertTrue($expChain->getExpirationList()[0]->isStandard());
    }

    /**
     * @test
     */
    public function testCandleList(): void {
        $data = [
            'symbol' => 'AAPL',
            'empty' => false,
            'candles' => [
                [
                    'open' => 149.0,
                    'high' => 151.0,
                    'low' => 148.5,
                    'close' => 150.5,
                    'volume' => 1000000.0,
                    'datetime' => 1789430400000
                ]
            ]
        ];

        $candleList = CandleList::fromArray($data);
        $this->assertEquals('AAPL', $candleList->getSymbol());
        $this->assertFalse($candleList->isEmpty());
        $this->assertCount(1, $candleList->getCandles());
        $this->assertInstanceOf(Candle::class, $candleList->getCandles()[0]);
        $this->assertEquals(150.5, $candleList->getCandles()[0]->getClose());
    }

    /**
     * @test
     */
    public function testScreener(): void {
        $data = [
            'total' => 1,
            'screeners' => [
                ['symbol' => 'AAPL', 'change' => 2.5]
            ]
        ];

        $screener = Screener::fromArray($data);
        $this->assertEquals(1, $screener->getTotal());
        $this->assertCount(1, $screener->getScreeners());
    }

    /**
     * @test
     */
    public function testHours(): void {
        $data = [
            'category' => 'EQUITY',
            'date' => '2026-09-15',
            'description' => 'US Equity Markets',
            'exchange' => 'NYSE',
            'isOpen' => true,
            'marketType' => 'EQUITY',
            'sessionHours' => [
                'regularMarket' => [
                    ['start' => '2026-09-15T09:30:00-04:00', 'end' => '2026-09-15T16:00:00-04:00']
                ]
            ]
        ];

        $hours = Hours::fromArray($data);
        $this->assertTrue($hours->isOpen());
        $this->assertEquals('NYSE', $hours->getExchange());
        $this->assertArrayHasKey('regularMarket', $hours->getSessionHours());
        $this->assertEquals('2026-09-15T09:30:00-04:00', $hours->getSessionHours()['regularMarket'][0]->getStart());
    }

    /**
     * @test
     */
    public function testInstrumentResponse(): void {
        $data = [
            'instruments' => [
                [
                    'cusip' => '037833100',
                    'symbol' => 'AAPL',
                    'description' => 'Apple Inc',
                    'exchange' => 'NASDAQ',
                    'assetType' => 'EQUITY',
                    'type' => 'COMMON'
                ]
            ]
        ];

        $resp = InstrumentResponse::fromArray($data);
        $this->assertCount(1, $resp->getInstruments());
        $this->assertInstanceOf(Instrument::class, $resp->getInstruments()[0]);
        $this->assertEquals('AAPL', $resp->getInstruments()[0]->getSymbol());
    }
}
