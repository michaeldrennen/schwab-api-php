<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class QuoteEquity extends AbstractSchema {

    protected ?float $fiftyTwoWeekHigh = null;
    protected ?float $fiftyTwoWeekLow = null;
    protected ?string $askMICId = null;
    protected ?float $askPrice = null;
    protected ?float $askSize = null;
    protected ?int $askTime = null;
    protected ?string $bidMICId = null;
    protected ?float $bidPrice = null;
    protected ?float $bidSize = null;
    protected ?int $bidTime = null;
    protected ?float $closePrice = null;
    protected ?float $highPrice = null;
    protected ?string $lastMICId = null;
    protected ?float $lastPrice = null;
    protected ?float $lastSize = null;
    protected ?float $lowPrice = null;
    protected ?float $mark = null;
    protected ?float $markChange = null;
    protected ?float $markPercentChange = null;
    protected ?float $netChange = null;
    protected ?float $netPercentChange = null;
    protected ?float $openPrice = null;
    protected ?float $postMarketChange = null;
    protected ?float $postMarketPercentChange = null;
    protected ?int $quoteTime = null;
    protected ?string $securityStatus = null;
    protected ?float $totalVolume = null;
    protected ?int $tradeTime = null;

    public static function fromArray( array $data ): static {
        $instance = new static();
        $instance->fiftyTwoWeekHigh         = isset($data['52WeekHigh']) ? (float)$data['52WeekHigh'] : (isset($data['fiftyTwoWeekHigh']) ? (float)$data['fiftyTwoWeekHigh'] : null);
        $instance->fiftyTwoWeekLow          = isset($data['52WeekLow']) ? (float)$data['52WeekLow'] : (isset($data['fiftyTwoWeekLow']) ? (float)$data['fiftyTwoWeekLow'] : null);
        $instance->askMICId                 = $data['askMICId'] ?? null;
        $instance->askPrice                 = isset($data['askPrice']) ? (float)$data['askPrice'] : null;
        $instance->askSize                  = isset($data['askSize']) ? (float)$data['askSize'] : null;
        $instance->askTime                  = isset($data['askTime']) ? (int)$data['askTime'] : null;
        $instance->bidMICId                 = $data['bidMICId'] ?? null;
        $instance->bidPrice                 = isset($data['bidPrice']) ? (float)$data['bidPrice'] : null;
        $instance->bidSize                  = isset($data['bidSize']) ? (float)$data['bidSize'] : null;
        $instance->bidTime                  = isset($data['bidTime']) ? (int)$data['bidTime'] : null;
        $instance->closePrice               = isset($data['closePrice']) ? (float)$data['closePrice'] : null;
        $instance->highPrice                = isset($data['highPrice']) ? (float)$data['highPrice'] : null;
        $instance->lastMICId                = $data['lastMICId'] ?? null;
        $instance->lastPrice                = isset($data['lastPrice']) ? (float)$data['lastPrice'] : null;
        $instance->lastSize                 = isset($data['lastSize']) ? (float)$data['lastSize'] : null;
        $instance->lowPrice                 = isset($data['lowPrice']) ? (float)$data['lowPrice'] : null;
        $instance->mark                     = isset($data['mark']) ? (float)$data['mark'] : null;
        $instance->markChange               = isset($data['markChange']) ? (float)$data['markChange'] : null;
        $instance->markPercentChange        = isset($data['markPercentChange']) ? (float)$data['markPercentChange'] : null;
        $instance->netChange                = isset($data['netChange']) ? (float)$data['netChange'] : null;
        $instance->netPercentChange         = isset($data['netPercentChange']) ? (float)$data['netPercentChange'] : null;
        $instance->openPrice                = isset($data['openPrice']) ? (float)$data['openPrice'] : null;
        $instance->postMarketChange         = isset($data['postMarketChange']) ? (float)$data['postMarketChange'] : null;
        $instance->postMarketPercentChange  = isset($data['postMarketPercentChange']) ? (float)$data['postMarketPercentChange'] : null;
        $instance->quoteTime                = isset($data['quoteTime']) ? (int)$data['quoteTime'] : null;
        $instance->securityStatus           = $data['securityStatus'] ?? null;
        $instance->totalVolume              = isset($data['totalVolume']) ? (float)$data['totalVolume'] : null;
        $instance->tradeTime                = isset($data['tradeTime']) ? (int)$data['tradeTime'] : null;
        return $instance;
    }

    public function getFiftyTwoWeekHigh(): ?float { return $this->fiftyTwoWeekHigh; }
    public function getFiftyTwoWeekLow(): ?float { return $this->fiftyTwoWeekLow; }
    public function getAskMICId(): ?string { return $this->askMICId; }
    public function getAskPrice(): ?float { return $this->askPrice; }
    public function getAskSize(): ?float { return $this->askSize; }
    public function getAskTime(): ?int { return $this->askTime; }
    public function getBidMICId(): ?string { return $this->bidMICId; }
    public function getBidPrice(): ?float { return $this->bidPrice; }
    public function getBidSize(): ?float { return $this->bidSize; }
    public function getBidTime(): ?int { return $this->bidTime; }
    public function getClosePrice(): ?float { return $this->closePrice; }
    public function getHighPrice(): ?float { return $this->highPrice; }
    public function getLastMICId(): ?string { return $this->lastMICId; }
    public function getLastPrice(): ?float { return $this->lastPrice; }
    public function getLastSize(): ?float { return $this->lastSize; }
    public function getLowPrice(): ?float { return $this->lowPrice; }
    public function getMark(): ?float { return $this->mark; }
    public function getMarkChange(): ?float { return $this->markChange; }
    public function getMarkPercentChange(): ?float { return $this->markPercentChange; }
    public function getNetChange(): ?float { return $this->netChange; }
    public function getNetPercentChange(): ?float { return $this->netPercentChange; }
    public function getOpenPrice(): ?float { return $this->openPrice; }
    public function getPostMarketChange(): ?float { return $this->postMarketChange; }
    public function getPostMarketPercentChange(): ?float { return $this->postMarketPercentChange; }
    public function getQuoteTime(): ?int { return $this->quoteTime; }
    public function getSecurityStatus(): ?string { return $this->securityStatus; }
    public function getTotalVolume(): ?float { return $this->totalVolume; }
    public function getTradeTime(): ?int { return $this->tradeTime; }
}
