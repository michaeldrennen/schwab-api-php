<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Underlying extends AbstractSchema {

    protected ?float $ask = null;
    protected ?int $askSize = null;
    protected ?float $bid = null;
    protected ?int $bidSize = null;
    protected ?float $change = null;
    protected ?float $close = null;
    protected ?bool $delayed = null;
    protected ?string $description = null;
    protected ?string $exchangeName = null;
    protected ?float $fiftyTwoWeekHigh = null;
    protected ?float $fiftyTwoWeekLow = null;
    protected ?float $highPrice = null;
    protected ?float $last = null;
    protected ?float $lowPrice = null;
    protected ?float $mark = null;
    protected ?float $markChange = null;
    protected ?float $markPercentChange = null;
    protected ?float $openPrice = null;
    protected ?float $percentChange = null;
    protected ?int $quoteTime = null;
    protected ?string $symbol = null;
    protected ?int $totalVolume = null;
    protected ?int $tradeTime = null;

    public static function fromArray( array $data ): static {
        $instance = new static();
        $instance->ask               = isset($data['ask']) ? (float)$data['ask'] : null;
        $instance->askSize           = isset($data['askSize']) ? (int)$data['askSize'] : null;
        $instance->bid               = isset($data['bid']) ? (float)$data['bid'] : null;
        $instance->bidSize           = isset($data['bidSize']) ? (int)$data['bidSize'] : null;
        $instance->change            = isset($data['change']) ? (float)$data['change'] : null;
        $instance->close             = isset($data['close']) ? (float)$data['close'] : null;
        $instance->delayed           = isset($data['delayed']) ? (bool)$data['delayed'] : null;
        $instance->description       = $data['description'] ?? null;
        $instance->exchangeName       = $data['exchangeName'] ?? null;
        $instance->fiftyTwoWeekHigh   = isset($data['fiftyTwoWeekHigh']) ? (float)$data['fiftyTwoWeekHigh'] : (isset($data['52WeekHigh']) ? (float)$data['52WeekHigh'] : null);
        $instance->fiftyTwoWeekLow    = isset($data['fiftyTwoWeekLow']) ? (float)$data['fiftyTwoWeekLow'] : (isset($data['52WeekLow']) ? (float)$data['52WeekLow'] : null);
        $instance->highPrice          = isset($data['highPrice']) ? (float)$data['highPrice'] : null;
        $instance->last               = isset($data['last']) ? (float)$data['last'] : null;
        $instance->lowPrice           = isset($data['lowPrice']) ? (float)$data['lowPrice'] : null;
        $instance->mark               = isset($data['mark']) ? (float)$data['mark'] : null;
        $instance->markChange         = isset($data['markChange']) ? (float)$data['markChange'] : null;
        $instance->markPercentChange  = isset($data['markPercentChange']) ? (float)$data['markPercentChange'] : null;
        $instance->openPrice          = isset($data['openPrice']) ? (float)$data['openPrice'] : null;
        $instance->percentChange      = isset($data['percentChange']) ? (float)$data['percentChange'] : null;
        $instance->quoteTime          = isset($data['quoteTime']) ? (int)$data['quoteTime'] : null;
        $instance->symbol             = $data['symbol'] ?? null;
        $instance->totalVolume        = isset($data['totalVolume']) ? (int)$data['totalVolume'] : null;
        $instance->tradeTime          = isset($data['tradeTime']) ? (int)$data['tradeTime'] : null;
        return $instance;
    }

    public function getAsk(): ?float { return $this->ask; }
    public function getAskSize(): ?int { return $this->askSize; }
    public function getBid(): ?float { return $this->bid; }
    public function getBidSize(): ?int { return $this->bidSize; }
    public function getChange(): ?float { return $this->change; }
    public function getClose(): ?float { return $this->close; }
    public function isDelayed(): ?bool { return $this->delayed; }
    public function getDescription(): ?string { return $this->description; }
    public function getExchangeName(): ?string { return $this->exchangeName; }
    public function getFiftyTwoWeekHigh(): ?float { return $this->fiftyTwoWeekHigh; }
    public function getFiftyTwoWeekLow(): ?float { return $this->fiftyTwoWeekLow; }
    public function getHighPrice(): ?float { return $this->highPrice; }
    public function getLast(): ?float { return $this->last; }
    public function getLowPrice(): ?float { return $this->lowPrice; }
    public function getMark(): ?float { return $this->mark; }
    public function getMarkChange(): ?float { return $this->markChange; }
    public function getMarkPercentChange(): ?float { return $this->markPercentChange; }
    public function getOpenPrice(): ?float { return $this->openPrice; }
    public function getPercentChange(): ?float { return $this->percentChange; }
    public function getQuoteTime(): ?int { return $this->quoteTime; }
    public function getSymbol(): ?string { return $this->symbol; }
    public function getTotalVolume(): ?int { return $this->totalVolume; }
    public function getTradeTime(): ?int { return $this->tradeTime; }
}
