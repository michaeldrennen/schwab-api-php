<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class CashInitialBalance extends AbstractSchema {

    protected ?float $accruedInterest = null;
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
    protected ?float $cashDebitCallValue = null;
    protected ?float $pendingDeposits = null;
    protected ?float $accountValue = null;

    public function __construct(
        ?float $accruedInterest = null,
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
        ?float $cashDebitCallValue = null,
        ?float $pendingDeposits = null,
        ?float $accountValue = null
    ) {
        $this->accruedInterest            = $accruedInterest;
        $this->cashAvailableForTrading    = $cashAvailableForTrading;
        $this->cashAvailableForWithdrawal = $cashAvailableForWithdrawal;
        $this->cashBalance                = $cashBalance;
        $this->bondValue                  = $bondValue;
        $this->cashReceipts               = $cashReceipts;
        $this->liquidationValue           = $liquidationValue;
        $this->longOptionMarketValue      = $longOptionMarketValue;
        $this->longStockValue             = $longStockValue;
        $this->moneyMarketFund            = $moneyMarketFund;
        $this->mutualFundValue            = $mutualFundValue;
        $this->shortOptionMarketValue     = $shortOptionMarketValue;
        $this->shortStockValue            = $shortStockValue;
        $this->isInCall                   = $isInCall;
        $this->unsettledCash              = $unsettledCash;
        $this->cashDebitCallValue         = $cashDebitCallValue;
        $this->pendingDeposits            = $pendingDeposits;
        $this->accountValue               = $accountValue;
    }

    public static function fromArray( array $data ): static {
        return new static(
            accruedInterest: isset($data['accruedInterest']) ? (float)$data['accruedInterest'] : null,
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
            cashDebitCallValue: isset($data['cashDebitCallValue']) ? (float)$data['cashDebitCallValue'] : null,
            pendingDeposits: isset($data['pendingDeposits']) ? (float)$data['pendingDeposits'] : null,
            accountValue: isset($data['accountValue']) ? (float)$data['accountValue'] : null
        );
    }

    public function getAccruedInterest(): ?float { return $this->accruedInterest; }
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
    public function getCashDebitCallValue(): ?float { return $this->cashDebitCallValue; }
    public function getPendingDeposits(): ?float { return $this->pendingDeposits; }
    public function getAccountValue(): ?float { return $this->accountValue; }
}
