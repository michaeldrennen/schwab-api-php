<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class MarginBalance extends AbstractSchema {

    protected ?float $availableFunds = null;
    protected ?float $availableFundsWithNonMarginableSecurities = null;
    protected ?float $buyingPower = null;
    protected ?float $dayTradingBuyingPower = null;
    protected ?float $dayTradingBuyingPowerCall = null;
    protected ?float $maintenanceCall = null;
    protected ?float $regTCall = null;
    protected ?float $shortBalance = null;
    protected ?float $marginBalance = null;
    protected ?float $accountValue = null;
    protected ?float $marginBuyingPower = null;
    protected ?float $stockBuyingPower = null;
    protected ?float $optionBuyingPower = null;
    protected ?float $cashAvailableForTrading = null;
    protected ?float $cashAvailableForWithdrawal = null;
    protected ?float $cashBalance = null;
    protected ?float $bondValue = null;
    protected ?float $cashReceipts = null;
    protected ?float $liquidationValue = null;
    protected ?float $longOptionMarketValue = null;
    protected ?float $longStockValue = null;
    protected ?float $moneyMarketFund = null;
    protected ?float $mutualFundValue = null;
    protected ?float $shortOptionMarketValue = null;
    protected ?float $shortStockValue = null;
    protected ?bool $isInCall = null;
    protected ?float $unsettledCash = null;
    protected ?float $pendingDeposits = null;
    protected ?float $margin = null;
    protected ?float $shortOptionBalance = null;
    protected ?float $shortStockBalance = null;
    protected ?float $longMarginValue = null;
    protected ?float $shortMarginValue = null;
    protected ?float $shortMarketValue = null;
    protected ?float $longMarketValue = null;

    public function __construct(
        ?float $availableFunds = null,
        ?float $availableFundsWithNonMarginableSecurities = null,
        ?float $buyingPower = null,
        ?float $dayTradingBuyingPower = null,
        ?float $dayTradingBuyingPowerCall = null,
        ?float $maintenanceCall = null,
        ?float $regTCall = null,
        ?float $shortBalance = null,
        ?float $marginBalance = null,
        ?float $accountValue = null,
        ?float $marginBuyingPower = null,
        ?float $stockBuyingPower = null,
        ?float $optionBuyingPower = null,
        ?float $cashAvailableForTrading = null,
        ?float $cashAvailableForWithdrawal = null,
        ?float $cashBalance = null,
        ?float $bondValue = null,
        ?float $cashReceipts = null,
        ?float $liquidationValue = null,
        ?float $longOptionMarketValue = null,
        ?float $longStockValue = null,
        ?float $moneyMarketFund = null,
        ?float $mutualFundValue = null,
        ?float $shortOptionMarketValue = null,
        ?float $shortStockValue = null,
        ?bool $isInCall = null,
        ?float $unsettledCash = null,
        ?float $pendingDeposits = null,
        ?float $margin = null,
        ?float $shortOptionBalance = null,
        ?float $shortStockBalance = null,
        ?float $longMarginValue = null,
        ?float $shortMarginValue = null,
        ?float $shortMarketValue = null,
        ?float $longMarketValue = null
    ) {
        $this->availableFunds                             = $availableFunds;
        $this->availableFundsWithNonMarginableSecurities = $availableFundsWithNonMarginableSecurities;
        $this->buyingPower                                = $buyingPower;
        $this->dayTradingBuyingPower                      = $dayTradingBuyingPower;
        $this->dayTradingBuyingPowerCall                  = $dayTradingBuyingPowerCall;
        $this->maintenanceCall                            = $maintenanceCall;
        $this->regTCall                                   = $regTCall;
        $this->shortBalance                               = $shortBalance;
        $this->marginBalance                              = $marginBalance;
        $this->accountValue                               = $accountValue;
        $this->marginBuyingPower                          = $marginBuyingPower;
        $this->stockBuyingPower                           = $stockBuyingPower;
        $this->optionBuyingPower                          = $optionBuyingPower;
        $this->cashAvailableForTrading                    = $cashAvailableForTrading;
        $this->cashAvailableForWithdrawal                 = $cashAvailableForWithdrawal;
        $this->cashBalance                                = $cashBalance;
        $this->bondValue                                  = $bondValue;
        $this->cashReceipts                               = $cashReceipts;
        $this->liquidationValue                           = $liquidationValue;
        $this->longOptionMarketValue                      = $longOptionMarketValue;
        $this->longStockValue                             = $longStockValue;
        $this->moneyMarketFund                            = $moneyMarketFund;
        $this->mutualFundValue                            = $mutualFundValue;
        $this->shortOptionMarketValue                     = $shortOptionMarketValue;
        $this->shortStockValue                            = $shortStockValue;
        $this->isInCall                                   = $isInCall;
        $this->unsettledCash                              = $unsettledCash;
        $this->pendingDeposits                            = $pendingDeposits;
        $this->margin                                     = $margin;
        $this->shortOptionBalance                         = $shortOptionBalance;
        $this->shortStockBalance                          = $shortStockBalance;
        $this->longMarginValue                            = $longMarginValue;
        $this->shortMarginValue                           = $shortMarginValue;
        $this->shortMarketValue                           = $shortMarketValue;
        $this->longMarketValue                            = $longMarketValue;
    }

    public static function fromArray( array $data ): static {
        return new static(
            availableFunds: isset($data['availableFunds']) ? (float)$data['availableFunds'] : null,
            availableFundsWithNonMarginableSecurities: isset($data['availableFundsWithNonMarginableSecurities']) ? (float)$data['availableFundsWithNonMarginableSecurities'] : null,
            buyingPower: isset($data['buyingPower']) ? (float)$data['buyingPower'] : null,
            dayTradingBuyingPower: isset($data['dayTradingBuyingPower']) ? (float)$data['dayTradingBuyingPower'] : null,
            dayTradingBuyingPowerCall: isset($data['dayTradingBuyingPowerCall']) ? (float)$data['dayTradingBuyingPowerCall'] : null,
            maintenanceCall: isset($data['maintenanceCall']) ? (float)$data['maintenanceCall'] : null,
            regTCall: isset($data['regTCall']) ? (float)$data['regTCall'] : null,
            shortBalance: isset($data['shortBalance']) ? (float)$data['shortBalance'] : null,
            marginBalance: isset($data['marginBalance']) ? (float)$data['marginBalance'] : null,
            accountValue: isset($data['accountValue']) ? (float)$data['accountValue'] : null,
            marginBuyingPower: isset($data['marginBuyingPower']) ? (float)$data['marginBuyingPower'] : null,
            stockBuyingPower: isset($data['stockBuyingPower']) ? (float)$data['stockBuyingPower'] : null,
            optionBuyingPower: isset($data['optionBuyingPower']) ? (float)$data['optionBuyingPower'] : null,
            cashAvailableForTrading: isset($data['cashAvailableForTrading']) ? (float)$data['cashAvailableForTrading'] : null,
            cashAvailableForWithdrawal: isset($data['cashAvailableForWithdrawal']) ? (float)$data['cashAvailableForWithdrawal'] : null,
            cashBalance: isset($data['cashBalance']) ? (float)$data['cashBalance'] : null,
            bondValue: isset($data['bondValue']) ? (float)$data['bondValue'] : null,
            cashReceipts: isset($data['cashReceipts']) ? (float)$data['cashReceipts'] : null,
            liquidationValue: isset($data['liquidationValue']) ? (float)$data['liquidationValue'] : null,
            longOptionMarketValue: isset($data['longOptionMarketValue']) ? (float)$data['longOptionMarketValue'] : null,
            longStockValue: isset($data['longStockValue']) ? (float)$data['longStockValue'] : null,
            moneyMarketFund: isset($data['moneyMarketFund']) ? (float)$data['moneyMarketFund'] : null,
            mutualFundValue: isset($data['mutualFundValue']) ? (float)$data['mutualFundValue'] : null,
            shortOptionMarketValue: isset($data['shortOptionMarketValue']) ? (float)$data['shortOptionMarketValue'] : null,
            shortStockValue: isset($data['shortStockValue']) ? (float)$data['shortStockValue'] : null,
            isInCall: isset($data['isInCall']) ? (bool)$data['isInCall'] : null,
            unsettledCash: isset($data['unsettledCash']) ? (float)$data['unsettledCash'] : null,
            pendingDeposits: isset($data['pendingDeposits']) ? (float)$data['pendingDeposits'] : null,
            margin: isset($data['margin']) ? (float)$data['margin'] : null,
            shortOptionBalance: isset($data['shortOptionBalance']) ? (float)$data['shortOptionBalance'] : null,
            shortStockBalance: isset($data['shortStockBalance']) ? (float)$data['shortStockBalance'] : null,
            longMarginValue: isset($data['longMarginValue']) ? (float)$data['longMarginValue'] : null,
            shortMarginValue: isset($data['shortMarginValue']) ? (float)$data['shortMarginValue'] : null,
            shortMarketValue: isset($data['shortMarketValue']) ? (float)$data['shortMarketValue'] : null,
            longMarketValue: isset($data['longMarketValue']) ? (float)$data['longMarketValue'] : null
        );
    }

    public function getAvailableFunds(): ?float { return $this->availableFunds; }
    public function getAvailableFundsWithNonMarginableSecurities(): ?float { return $this->availableFundsWithNonMarginableSecurities; }
    public function getBuyingPower(): ?float { return $this->buyingPower; }
    public function getDayTradingBuyingPower(): ?float { return $this->dayTradingBuyingPower; }
    public function getDayTradingBuyingPowerCall(): ?float { return $this->dayTradingBuyingPowerCall; }
    public function getMaintenanceCall(): ?float { return $this->maintenanceCall; }
    public function getRegTCall(): ?float { return $this->regTCall; }
    public function getShortBalance(): ?float { return $this->shortBalance; }
    public function getMarginBalance(): ?float { return $this->marginBalance; }
    public function getAccountValue(): ?float { return $this->accountValue; }
    public function getMarginBuyingPower(): ?float { return $this->marginBuyingPower; }
    public function getStockBuyingPower(): ?float { return $this->stockBuyingPower; }
    public function getOptionBuyingPower(): ?float { return $this->optionBuyingPower; }
    public function getCashAvailableForTrading(): ?float { return $this->cashAvailableForTrading; }
    public function getCashAvailableForWithdrawal(): ?float { return $this->cashAvailableForWithdrawal; }
    public function getCashBalance(): ?float { return $this->cashBalance; }
    public function getBondValue(): ?float { return $this->bondValue; }
    public function getCashReceipts(): ?float { return $this->cashReceipts; }
    public function getLiquidationValue(): ?float { return $this->liquidationValue; }
    public function getLongOptionMarketValue(): ?float { return $this->longOptionMarketValue; }
    public function getLongStockValue(): ?float { return $this->longStockValue; }
    public function getMoneyMarketFund(): ?float { return $this->moneyMarketFund; }
    public function getMutualFundValue(): ?float { return $this->mutualFundValue; }
    public function getShortOptionMarketValue(): ?float { return $this->shortOptionMarketValue; }
    public function getShortStockValue(): ?float { return $this->shortStockValue; }
    public function getIsInCall(): ?bool { return $this->isInCall; }
    public function getUnsettledCash(): ?float { return $this->unsettledCash; }
    public function getPendingDeposits(): ?float { return $this->pendingDeposits; }
    public function getMargin(): ?float { return $this->margin; }
    public function getShortOptionBalance(): ?float { return $this->shortOptionBalance; }
    public function getShortStockBalance(): ?float { return $this->shortStockBalance; }
    public function getLongMarginValue(): ?float { return $this->longMarginValue; }
    public function getShortMarginValue(): ?float { return $this->shortMarginValue; }
    public function getShortMarketValue(): ?float { return $this->shortMarketValue; }
    public function getLongMarketValue(): ?float { return $this->longMarketValue; }
}
