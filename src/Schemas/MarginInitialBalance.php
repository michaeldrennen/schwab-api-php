<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class MarginInitialBalance extends AbstractSchema {

    protected ?float $accruedInterest = null;
    protected ?float $availableFundsNonMarginableTrade = null;
    protected ?float $bondValue = null;
    protected ?float $buyingPower = null;
    protected ?float $cashBalance = null;
    protected ?float $cashAvailableForTrading = null;
    protected ?float $cashReceipts = null;
    protected ?float $dayTradingBuyingPower = null;
    protected ?float $dayTradingBuyingPowerCall = null;
    protected ?float $dayTradingEquityCall = null;
    protected ?float $equity = null;
    protected ?float $equityPercentage = null;
    protected ?float $liquidationValue = null;
    protected ?float $longMarginValue = null;
    protected ?float $longOptionMarketValue = null;
    protected ?float $longStockValue = null;
    protected ?float $maintenanceCall = null;
    protected ?float $maintenanceRequirement = null;
    protected ?float $margin = null;
    protected ?float $marginEquity = null;
    protected ?float $moneyMarketFund = null;
    protected ?float $mutualFundValue = null;
    protected ?float $regTCall = null;
    protected ?float $shortMarginValue = null;
    protected ?float $shortOptionMarketValue = null;
    protected ?float $shortStockValue = null;
    protected ?float $totalCash = null;
    protected ?bool $isInCall = null;
    protected ?float $unsettledCash = null;
    protected ?float $pendingDeposits = null;
    protected ?float $marginBalance = null;
    protected ?float $shortBalance = null;
    protected ?float $accountValue = null;

    public function __construct(
        ?float $accruedInterest = null,
        ?float $availableFundsNonMarginableTrade = null,
        ?float $bondValue = null,
        ?float $buyingPower = null,
        ?float $cashBalance = null,
        ?float $cashAvailableForTrading = null,
        ?float $cashReceipts = null,
        ?float $dayTradingBuyingPower = null,
        ?float $dayTradingBuyingPowerCall = null,
        ?float $dayTradingEquityCall = null,
        ?float $equity = null,
        ?float $equityPercentage = null,
        ?float $liquidationValue = null,
        ?float $longMarginValue = null,
        ?float $longOptionMarketValue = null,
        ?float $longStockValue = null,
        ?float $maintenanceCall = null,
        ?float $maintenanceRequirement = null,
        ?float $margin = null,
        ?float $marginEquity = null,
        ?float $moneyMarketFund = null,
        ?float $mutualFundValue = null,
        ?float $regTCall = null,
        ?float $shortMarginValue = null,
        ?float $shortOptionMarketValue = null,
        ?float $shortStockValue = null,
        ?float $totalCash = null,
        ?bool $isInCall = null,
        ?float $unsettledCash = null,
        ?float $pendingDeposits = null,
        ?float $marginBalance = null,
        ?float $shortBalance = null,
        ?float $accountValue = null
    ) {
        $this->accruedInterest                  = $accruedInterest;
        $this->availableFundsNonMarginableTrade = $availableFundsNonMarginableTrade;
        $this->bondValue                        = $bondValue;
        $this->buyingPower                      = $buyingPower;
        $this->cashBalance                      = $cashBalance;
        $this->cashAvailableForTrading          = $cashAvailableForTrading;
        $this->cashReceipts                     = $cashReceipts;
        $this->dayTradingBuyingPower            = $dayTradingBuyingPower;
        $this->dayTradingBuyingPowerCall        = $dayTradingBuyingPowerCall;
        $this->dayTradingEquityCall             = $dayTradingEquityCall;
        $this->equity                           = $equity;
        $this->equityPercentage                 = $equityPercentage;
        $this->liquidationValue                 = $liquidationValue;
        $this->longMarginValue                  = $longMarginValue;
        $this->longOptionMarketValue            = $longOptionMarketValue;
        $this->longStockValue                   = $longStockValue;
        $this->maintenanceCall                  = $maintenanceCall;
        $this->maintenanceRequirement           = $maintenanceRequirement;
        $this->margin                           = $margin;
        $this->marginEquity                     = $marginEquity;
        $this->moneyMarketFund                  = $moneyMarketFund;
        $this->mutualFundValue                  = $mutualFundValue;
        $this->regTCall                         = $regTCall;
        $this->shortMarginValue                 = $shortMarginValue;
        $this->shortOptionMarketValue           = $shortOptionMarketValue;
        $this->shortStockValue                  = $shortStockValue;
        $this->totalCash                        = $totalCash;
        $this->isInCall                         = $isInCall;
        $this->unsettledCash                    = $unsettledCash;
        $this->pendingDeposits                  = $pendingDeposits;
        $this->marginBalance                    = $marginBalance;
        $this->shortBalance                     = $shortBalance;
        $this->accountValue                     = $accountValue;
    }

    public static function fromArray( array $data ): static {
        return new static(
            accruedInterest: isset($data['accruedInterest']) ? (float)$data['accruedInterest'] : null,
            availableFundsNonMarginableTrade: isset($data['availableFundsNonMarginableTrade']) ? (float)$data['availableFundsNonMarginableTrade'] : null,
            bondValue: isset($data['bondValue']) ? (float)$data['bondValue'] : null,
            buyingPower: isset($data['buyingPower']) ? (float)$data['buyingPower'] : null,
            cashBalance: isset($data['cashBalance']) ? (float)$data['cashBalance'] : null,
            cashAvailableForTrading: isset($data['cashAvailableForTrading']) ? (float)$data['cashAvailableForTrading'] : null,
            cashReceipts: isset($data['cashReceipts']) ? (float)$data['cashReceipts'] : null,
            dayTradingBuyingPower: isset($data['dayTradingBuyingPower']) ? (float)$data['dayTradingBuyingPower'] : null,
            dayTradingBuyingPowerCall: isset($data['dayTradingBuyingPowerCall']) ? (float)$data['dayTradingBuyingPowerCall'] : null,
            dayTradingEquityCall: isset($data['dayTradingEquityCall']) ? (float)$data['dayTradingEquityCall'] : null,
            equity: isset($data['equity']) ? (float)$data['equity'] : null,
            equityPercentage: isset($data['equityPercentage']) ? (float)$data['equityPercentage'] : null,
            liquidationValue: isset($data['liquidationValue']) ? (float)$data['liquidationValue'] : null,
            longMarginValue: isset($data['longMarginValue']) ? (float)$data['longMarginValue'] : null,
            longOptionMarketValue: isset($data['longOptionMarketValue']) ? (float)$data['longOptionMarketValue'] : null,
            longStockValue: isset($data['longStockValue']) ? (float)$data['longStockValue'] : null,
            maintenanceCall: isset($data['maintenanceCall']) ? (float)$data['maintenanceCall'] : null,
            maintenanceRequirement: isset($data['maintenanceRequirement']) ? (float)$data['maintenanceRequirement'] : null,
            margin: isset($data['margin']) ? (float)$data['margin'] : null,
            marginEquity: isset($data['marginEquity']) ? (float)$data['marginEquity'] : null,
            moneyMarketFund: isset($data['moneyMarketFund']) ? (float)$data['moneyMarketFund'] : null,
            mutualFundValue: isset($data['mutualFundValue']) ? (float)$data['mutualFundValue'] : null,
            regTCall: isset($data['regTCall']) ? (float)$data['regTCall'] : null,
            shortMarginValue: isset($data['shortMarginValue']) ? (float)$data['shortMarginValue'] : null,
            shortOptionMarketValue: isset($data['shortOptionMarketValue']) ? (float)$data['shortOptionMarketValue'] : null,
            shortStockValue: isset($data['shortStockValue']) ? (float)$data['shortStockValue'] : null,
            totalCash: isset($data['totalCash']) ? (float)$data['totalCash'] : null,
            isInCall: isset($data['isInCall']) ? (bool)$data['isInCall'] : null,
            unsettledCash: isset($data['unsettledCash']) ? (float)$data['unsettledCash'] : null,
            pendingDeposits: isset($data['pendingDeposits']) ? (float)$data['pendingDeposits'] : null,
            marginBalance: isset($data['marginBalance']) ? (float)$data['marginBalance'] : null,
            shortBalance: isset($data['shortBalance']) ? (float)$data['shortBalance'] : null,
            accountValue: isset($data['accountValue']) ? (float)$data['accountValue'] : null
        );
    }

    public function getAccruedInterest(): ?float { return $this->accruedInterest; }
    public function getAvailableFundsNonMarginableTrade(): ?float { return $this->availableFundsNonMarginableTrade; }
    public function getBondValue(): ?float { return $this->bondValue; }
    public function getBuyingPower(): ?float { return $this->buyingPower; }
    public function getCashBalance(): ?float { return $this->cashBalance; }
    public function getCashAvailableForTrading(): ?float { return $this->cashAvailableForTrading; }
    public function getCashReceipts(): ?float { return $this->cashReceipts; }
    public function getDayTradingBuyingPower(): ?float { return $this->dayTradingBuyingPower; }
    public function getDayTradingBuyingPowerCall(): ?float { return $this->dayTradingBuyingPowerCall; }
    public function getDayTradingEquityCall(): ?float { return $this->dayTradingEquityCall; }
    public function getEquity(): ?float { return $this->equity; }
    public function getEquityPercentage(): ?float { return $this->equityPercentage; }
    public function getLiquidationValue(): ?float { return $this->liquidationValue; }
    public function getLongMarginValue(): ?float { return $this->longMarginValue; }
    public function getLongOptionMarketValue(): ?float { return $this->longOptionMarketValue; }
    public function getLongStockValue(): ?float { return $this->longStockValue; }
    public function getMaintenanceCall(): ?float { return $this->maintenanceCall; }
    public function getMaintenanceRequirement(): ?float { return $this->maintenanceRequirement; }
    public function getMargin(): ?float { return $this->margin; }
    public function getMarginEquity(): ?float { return $this->marginEquity; }
    public function getMoneyMarketFund(): ?float { return $this->moneyMarketFund; }
    public function getMutualFundValue(): ?float { return $this->mutualFundValue; }
    public function getRegTCall(): ?float { return $this->regTCall; }
    public function getShortMarginValue(): ?float { return $this->shortMarginValue; }
    public function getShortOptionMarketValue(): ?float { return $this->shortOptionMarketValue; }
    public function getShortStockValue(): ?float { return $this->shortStockValue; }
    public function getTotalCash(): ?float { return $this->totalCash; }
    public function getIsInCall(): ?bool { return $this->isInCall; }
    public function getUnsettledCash(): ?float { return $this->unsettledCash; }
    public function getPendingDeposits(): ?float { return $this->pendingDeposits; }
    public function getMarginBalance(): ?float { return $this->marginBalance; }
    public function getShortBalance(): ?float { return $this->shortBalance; }
    public function getAccountValue(): ?float { return $this->accountValue; }
}
