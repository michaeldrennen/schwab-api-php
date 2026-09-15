<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class QuoteMutualFund extends AbstractSchema {

    protected ?float $fiftyTwoWeekHigh = null;
    protected ?float $fiftyTwoWeekLow = null;
    protected ?float $closePrice = null;
    protected ?float $netChange = null;
    protected ?float $netPercentChange = null;
    protected ?float $nAV = null;
    protected ?string $securityStatus = null;
    protected ?int $tradeTime = null;

    public static function fromArray( array $data ): static {
        $instance = new static();
        $instance->fiftyTwoWeekHigh = isset($data['52WeekHigh']) ? (float)$data['52WeekHigh'] : (isset($data['fiftyTwoWeekHigh']) ? (float)$data['fiftyTwoWeekHigh'] : null);
        $instance->fiftyTwoWeekLow  = isset($data['52WeekLow']) ? (float)$data['52WeekLow'] : (isset($data['fiftyTwoWeekLow']) ? (float)$data['fiftyTwoWeekLow'] : null);
        $instance->closePrice       = isset($data['closePrice']) ? (float)$data['closePrice'] : null;
        $instance->netChange        = isset($data['netChange']) ? (float)$data['netChange'] : null;
        $instance->netPercentChange = isset($data['netPercentChange']) ? (float)$data['netPercentChange'] : null;
        $instance->nAV              = isset($data['nAV']) ? (float)$data['nAV'] : null;
        $instance->securityStatus   = $data['securityStatus'] ?? null;
        $instance->tradeTime        = isset($data['tradeTime']) ? (int)$data['tradeTime'] : null;
        return $instance;
    }

    public function getFiftyTwoWeekHigh(): ?float { return $this->fiftyTwoWeekHigh; }
    public function getFiftyTwoWeekLow(): ?float { return $this->fiftyTwoWeekLow; }
    public function getClosePrice(): ?float { return $this->closePrice; }
    public function getNetChange(): ?float { return $this->netChange; }
    public function getNetPercentChange(): ?float { return $this->netPercentChange; }
    public function getNAV(): ?float { return $this->nAV; }
    public function getSecurityStatus(): ?string { return $this->securityStatus; }
    public function getTradeTime(): ?int { return $this->tradeTime; }
}
