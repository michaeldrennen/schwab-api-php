<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class QuoteFuture extends AbstractSchema {

    protected ?float $askMICId = null;
    protected ?float $askPriceInDouble = null;
    protected ?float $askSizeInLong = null;
    protected ?int $askTimeInLong = null;
    protected ?float $bidMICId = null;
    protected ?float $bidPriceInDouble = null;
    protected ?float $bidSizeInLong = null;
    protected ?int $bidTimeInLong = null;
    protected ?float $changeInDouble = null;
    protected ?float $closePriceInDouble = null;
    protected ?float $futurePercentChange = null;
    protected ?float $highPriceInDouble = null;
    protected ?string $lastMICId = null;
    protected ?float $lastPriceInDouble = null;
    protected ?float $lastSizeInLong = null;
    protected ?float $lowPriceInDouble = null;
    protected ?float $mark = null;
    protected ?float $netChange = null;
    protected ?float $openInterest = null;
    protected ?float $openPriceInDouble = null;
    protected ?int $quoteTimeInLong = null;
    protected ?string $securityStatus = null;
    protected ?float $settleTimeInLong = null;
    protected ?float $totalVolume = null;
    protected ?int $tradeTimeInLong = null;

    public static function fromArray( array $data ): static {
        $instance = new static();
        $instance->askPriceInDouble   = isset($data['askPriceInDouble']) ? (float)$data['askPriceInDouble'] : null;
        $instance->askSizeInLong      = isset($data['askSizeInLong']) ? (float)$data['askSizeInLong'] : null;
        $instance->askTimeInLong      = isset($data['askTimeInLong']) ? (int)$data['askTimeInLong'] : null;
        $instance->bidPriceInDouble   = isset($data['bidPriceInDouble']) ? (float)$data['bidPriceInDouble'] : null;
        $instance->bidSizeInLong      = isset($data['bidSizeInLong']) ? (float)$data['bidSizeInLong'] : null;
        $instance->bidTimeInLong      = isset($data['bidTimeInLong']) ? (int)$data['bidTimeInLong'] : null;
        $instance->changeInDouble     = isset($data['changeInDouble']) ? (float)$data['changeInDouble'] : null;
        $instance->closePriceInDouble = isset($data['closePriceInDouble']) ? (float)$data['closePriceInDouble'] : null;
        $instance->futurePercentChange = isset($data['futurePercentChange']) ? (float)$data['futurePercentChange'] : null;
        $instance->highPriceInDouble  = isset($data['highPriceInDouble']) ? (float)$data['highPriceInDouble'] : null;
        $instance->lastMICId          = $data['lastMICId'] ?? null;
        $instance->lastPriceInDouble  = isset($data['lastPriceInDouble']) ? (float)$data['lastPriceInDouble'] : null;
        $instance->lastSizeInLong     = isset($data['lastSizeInLong']) ? (float)$data['lastSizeInLong'] : null;
        $instance->lowPriceInDouble   = isset($data['lowPriceInDouble']) ? (float)$data['lowPriceInDouble'] : null;
        $instance->mark               = isset($data['mark']) ? (float)$data['mark'] : null;
        $instance->netChange          = isset($data['netChange']) ? (float)$data['netChange'] : null;
        $instance->openInterest       = isset($data['openInterest']) ? (float)$data['openInterest'] : null;
        $instance->openPriceInDouble  = isset($data['openPriceInDouble']) ? (float)$data['openPriceInDouble'] : null;
        $instance->quoteTimeInLong    = isset($data['quoteTimeInLong']) ? (int)$data['quoteTimeInLong'] : null;
        $instance->securityStatus     = $data['securityStatus'] ?? null;
        $instance->settleTimeInLong   = isset($data['settleTimeInLong']) ? (float)$data['settleTimeInLong'] : null;
        $instance->totalVolume        = isset($data['totalVolume']) ? (float)$data['totalVolume'] : null;
        $instance->tradeTimeInLong    = isset($data['tradeTimeInLong']) ? (int)$data['tradeTimeInLong'] : null;
        return $instance;
    }

    public function getAskPriceInDouble(): ?float { return $this->askPriceInDouble; }
    public function getAskSizeInLong(): ?float { return $this->askSizeInLong; }
    public function getAskTimeInLong(): ?int { return $this->askTimeInLong; }
    public function getBidPriceInDouble(): ?float { return $this->bidPriceInDouble; }
    public function getBidSizeInLong(): ?float { return $this->bidSizeInLong; }
    public function getBidTimeInLong(): ?int { return $this->bidTimeInLong; }
    public function getChangeInDouble(): ?float { return $this->changeInDouble; }
    public function getClosePriceInDouble(): ?float { return $this->closePriceInDouble; }
    public function getFuturePercentChange(): ?float { return $this->futurePercentChange; }
    public function getHighPriceInDouble(): ?float { return $this->highPriceInDouble; }
    public function getLastMICId(): ?string { return $this->lastMICId; }
    public function getLastPriceInDouble(): ?float { return $this->lastPriceInDouble; }
    public function getLastSizeInLong(): ?float { return $this->lastSizeInLong; }
    public function getLowPriceInDouble(): ?float { return $this->lowPriceInDouble; }
    public function getMark(): ?float { return $this->mark; }
    public function getNetChange(): ?float { return $this->netChange; }
    public function getOpenInterest(): ?float { return $this->openInterest; }
    public function getOpenPriceInDouble(): ?float { return $this->openPriceInDouble; }
    public function getQuoteTimeInLong(): ?int { return $this->quoteTimeInLong; }
    public function getSecurityStatus(): ?string { return $this->securityStatus; }
    public function getSettleTimeInLong(): ?float { return $this->settleTimeInLong; }
    public function getTotalVolume(): ?float { return $this->totalVolume; }
    public function getTradeTimeInLong(): ?int { return $this->tradeTimeInLong; }
}
