<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class ExtendedMarket extends AbstractSchema {

    protected ?float $askPrice = null;
    protected ?float $askSize = null;
    protected ?float $bidPrice = null;
    protected ?float $bidSize = null;
    protected ?float $lastPrice = null;
    protected ?float $lastSize = null;
    protected ?float $mark = null;
    protected ?int $quoteTime = null;
    protected ?float $totalVolume = null;
    protected ?int $tradeTime = null;

    public function __construct(
        ?float $askPrice = null,
        ?float $askSize = null,
        ?float $bidPrice = null,
        ?float $bidSize = null,
        ?float $lastPrice = null,
        ?float $lastSize = null,
        ?float $mark = null,
        ?int $quoteTime = null,
        ?float $totalVolume = null,
        ?int $tradeTime = null
    ) {
        $this->askPrice    = $askPrice;
        $this->askSize     = $askSize;
        $this->bidPrice    = $bidPrice;
        $this->bidSize     = $bidSize;
        $this->lastPrice   = $lastPrice;
        $this->lastSize    = $lastSize;
        $this->mark        = $mark;
        $this->quoteTime   = $quoteTime;
        $this->totalVolume = $totalVolume;
        $this->tradeTime   = $tradeTime;
    }

    public static function fromArray( array $data ): static {
        return new static(
            askPrice: isset($data['askPrice']) ? (float)$data['askPrice'] : null,
            askSize: isset($data['askSize']) ? (float)$data['askSize'] : null,
            bidPrice: isset($data['bidPrice']) ? (float)$data['bidPrice'] : null,
            bidSize: isset($data['bidSize']) ? (float)$data['bidSize'] : null,
            lastPrice: isset($data['lastPrice']) ? (float)$data['lastPrice'] : null,
            lastSize: isset($data['lastSize']) ? (float)$data['lastSize'] : null,
            mark: isset($data['mark']) ? (float)$data['mark'] : null,
            quoteTime: isset($data['quoteTime']) ? (int)$data['quoteTime'] : null,
            totalVolume: isset($data['totalVolume']) ? (float)$data['totalVolume'] : null,
            tradeTime: isset($data['tradeTime']) ? (int)$data['tradeTime'] : null
        );
    }

    public function getAskPrice(): ?float { return $this->askPrice; }
    public function getAskSize(): ?float { return $this->askSize; }
    public function getBidPrice(): ?float { return $this->bidPrice; }
    public function getBidSize(): ?float { return $this->bidSize; }
    public function getLastPrice(): ?float { return $this->lastPrice; }
    public function getLastSize(): ?float { return $this->lastSize; }
    public function getMark(): ?float { return $this->mark; }
    public function getQuoteTime(): ?int { return $this->quoteTime; }
    public function getTotalVolume(): ?float { return $this->totalVolume; }
    public function getTradeTime(): ?int { return $this->tradeTime; }
}
