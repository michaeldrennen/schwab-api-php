<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class CashBalance extends AbstractSchema {

    protected ?float $cashAvailableForTrading = null;
    protected ?float $cashAvailableForWithdrawal = null;
    protected ?float $cashCall = null;
    protected ?float $longMarketValue = null;
    protected ?float $totalCash = null;
    protected ?float $cashDebitCallValue = null;
    protected ?float $unsettledCash = null;

    public function __construct(
        ?float $cashAvailableForTrading = null,
        ?float $cashAvailableForWithdrawal = null,
        ?float $cashCall = null,
        ?float $longMarketValue = null,
        ?float $totalCash = null,
        ?float $cashDebitCallValue = null,
        ?float $unsettledCash = null
    ) {
        $this->cashAvailableForTrading    = $cashAvailableForTrading;
        $this->cashAvailableForWithdrawal = $cashAvailableForWithdrawal;
        $this->cashCall                   = $cashCall;
        $this->longMarketValue            = $longMarketValue;
        $this->totalCash                  = $totalCash;
        $this->cashDebitCallValue         = $cashDebitCallValue;
        $this->unsettledCash              = $unsettledCash;
    }

    public static function fromArray( array $data ): static {
        return new static(
            cashAvailableForTrading: isset($data['cashAvailableForTrading']) ? (float)$data['cashAvailableForTrading'] : null,
            cashAvailableForWithdrawal: isset($data['cashAvailableForWithdrawal']) ? (float)$data['cashAvailableForWithdrawal'] : null,
            cashCall: isset($data['cashCall']) ? (float)$data['cashCall'] : null,
            longMarketValue: isset($data['longMarketValue']) ? (float)$data['longMarketValue'] : null,
            totalCash: isset($data['totalCash']) ? (float)$data['totalCash'] : null,
            cashDebitCallValue: isset($data['cashDebitCallValue']) ? (float)$data['cashDebitCallValue'] : null,
            unsettledCash: isset($data['unsettledCash']) ? (float)$data['unsettledCash'] : null
        );
    }

    public function getCashAvailableForTrading(): ?float { return $this->cashAvailableForTrading; }
    public function getCashAvailableForWithdrawal(): ?float { return $this->cashAvailableForWithdrawal; }
    public function getCashCall(): ?float { return $this->cashCall; }
    public function getLongMarketValue(): ?float { return $this->longMarketValue; }
    public function getTotalCash(): ?float { return $this->totalCash; }
    public function getCashDebitCallValue(): ?float { return $this->cashDebitCallValue; }
    public function getUnsettledCash(): ?float { return $this->unsettledCash; }
}
