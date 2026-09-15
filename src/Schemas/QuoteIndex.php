<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class QuoteIndex extends AbstractSchema {

    protected ?float $fiftyTwoWeekHigh = null;
    protected ?float $fiftyTwoWeekLow = null;
    protected ?float $closePrice = null;
    protected ?float $highPrice = null;
    protected ?float $lastPrice = null;
    protected ?float $lowPrice = null;
    protected ?float $netChange = null;
    protected ?float $netPercentChange = null;
    protected ?float $openPrice = null;
    protected ?int $tradeTime = null;
    protected ?string $securityStatus = null;
    protected ?float $totalVolume = null;

    public static function fromArray( array $data ): static {
        $instance = new static();
        $instance->fiftyTwoWeekHigh = isset($data['52WeekHigh']) ? (float)$data['52WeekHigh'] : (isset($data['fiftyTwoWeekHigh']) ? (float)$data['fiftyTwoWeekHigh'] : null);
        $instance->fiftyTwoWeekLow  = isset($data['52WeekLow']) ? (float)$data['52WeekLow'] : (isset($data['fiftyTwoWeekLow']) ? (float)$data['fiftyTwoWeekLow'] : null);
        $instance->closePrice       = isset($data['closePrice']) ? (float)$data['closePrice'] : null;
        $instance->highPrice        = isset($data['highPrice']) ? (float)$data['highPrice'] : null;
        $instance->lastPrice        = isset($data['lastPrice']) ? (float)$data['lastPrice'] : null;
        $instance->lowPrice         = isset($data['lowPrice']) ? (float)$data['lowPrice'] : null;
        $instance->netChange        = isset($data['netChange']) ? (float)$data['netChange'] : null;
        $instance->netPercentChange = isset($data['netPercentChange']) ? (float)$data['netPercentChange'] : null;
        $instance->openPrice        = isset($data['openPrice']) ? (float)$data['openPrice'] : null;
        $instance->tradeTime        = isset($data['tradeTime']) ? (int)$data['tradeTime'] : null;
        $instance->securityStatus   = $data['securityStatus'] ?? null;
        $instance->totalVolume      = isset($data['totalVolume']) ? (float)$data['totalVolume'] : null;
        return $instance;
    }

    public function getFiftyTwoWeekHigh(): ?float { return $this->fiftyTwoWeekHigh; }
    public function getFiftyTwoWeekLow(): ?float { return $this->fiftyTwoWeekLow; }
    public function getClosePrice(): ?float { return $this->closePrice; }
    public function getHighPrice(): ?float { return $this->highPrice; }
    public function getLastPrice(): ?float { return $this->lastPrice; }
    public function getLowPrice(): ?float { return $this->lowPrice; }
    public function getNetChange(): ?float { return $this->netChange; }
    public function getNetPercentChange(): ?float { return $this->netPercentChange; }
    public function getOpenPrice(): ?float { return $this->openPrice; }
    public function getTradeTime(): ?int { return $this->tradeTime; }
    public function getSecurityStatus(): ?string { return $this->securityStatus; }
    public function getTotalVolume(): ?float { return $this->totalVolume; }
}
