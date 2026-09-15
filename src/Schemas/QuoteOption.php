<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class QuoteOption extends AbstractSchema {

    protected ?float $fiftyTwoWeekHigh = null;
    protected ?float $fiftyTwoWeekLow = null;
    protected ?float $askPrice = null;
    protected ?float $askSize = null;
    protected ?float $bidPrice = null;
    protected ?float $bidSize = null;
    protected ?float $closePrice = null;
    protected ?float $delta = null;
    protected ?float $gamma = null;
    protected ?float $highPrice = null;
    protected ?float $impliedYield = null;
    protected ?float $indAskPrice = null;
    protected ?float $indBidPrice = null;
    protected ?int $indQuoteTime = null;
    protected ?float $impliedVolatility = null;
    protected ?float $lastPrice = null;
    protected ?float $lastSize = null;
    protected ?float $lowPrice = null;
    protected ?float $mark = null;
    protected ?float $markChange = null;
    protected ?float $markPercentChange = null;
    protected ?float $moneyIntrinsicValue = null;
    protected ?float $netChange = null;
    protected ?float $netPercentChange = null;
    protected ?float $openInterest = null;
    protected ?float $openPrice = null;
    protected ?int $quoteTime = null;
    protected ?float $rho = null;
    protected ?string $securityStatus = null;
    protected ?float $theoreticalOptionValue = null;
    protected ?float $theta = null;
    protected ?float $timeValue = null;
    protected ?float $totalVolume = null;
    protected ?int $tradeTime = null;
    protected ?float $underlyingPrice = null;
    protected ?float $vega = null;
    protected ?float $volatility = null;

    public static function fromArray( array $data ): static {
        $instance = new static();
        $instance->fiftyTwoWeekHigh      = isset($data['52WeekHigh']) ? (float)$data['52WeekHigh'] : (isset($data['fiftyTwoWeekHigh']) ? (float)$data['fiftyTwoWeekHigh'] : null);
        $instance->fiftyTwoWeekLow       = isset($data['52WeekLow']) ? (float)$data['52WeekLow'] : (isset($data['fiftyTwoWeekLow']) ? (float)$data['fiftyTwoWeekLow'] : null);
        $instance->askPrice              = isset($data['askPrice']) ? (float)$data['askPrice'] : null;
        $instance->askSize               = isset($data['askSize']) ? (float)$data['askSize'] : null;
        $instance->bidPrice              = isset($data['bidPrice']) ? (float)$data['bidPrice'] : null;
        $instance->bidSize               = isset($data['bidSize']) ? (float)$data['bidSize'] : null;
        $instance->closePrice            = isset($data['closePrice']) ? (float)$data['closePrice'] : null;
        $instance->delta                 = isset($data['delta']) ? (float)$data['delta'] : null;
        $instance->gamma                 = isset($data['gamma']) ? (float)$data['gamma'] : null;
        $instance->highPrice             = isset($data['highPrice']) ? (float)$data['highPrice'] : null;
        $instance->impliedYield          = isset($data['impliedYield']) ? (float)$data['impliedYield'] : null;
        $instance->indAskPrice           = isset($data['indAskPrice']) ? (float)$data['indAskPrice'] : null;
        $instance->indBidPrice           = isset($data['indBidPrice']) ? (float)$data['indBidPrice'] : null;
        $instance->indQuoteTime          = isset($data['indQuoteTime']) ? (int)$data['indQuoteTime'] : null;
        $instance->impliedVolatility     = isset($data['impliedVolatility']) ? (float)$data['impliedVolatility'] : null;
        $instance->lastPrice             = isset($data['lastPrice']) ? (float)$data['lastPrice'] : null;
        $instance->lastSize              = isset($data['lastSize']) ? (float)$data['lastSize'] : null;
        $instance->lowPrice              = isset($data['lowPrice']) ? (float)$data['lowPrice'] : null;
        $instance->mark                  = isset($data['mark']) ? (float)$data['mark'] : null;
        $instance->markChange            = isset($data['markChange']) ? (float)$data['markChange'] : null;
        $instance->markPercentChange     = isset($data['markPercentChange']) ? (float)$data['markPercentChange'] : null;
        $instance->moneyIntrinsicValue   = isset($data['moneyIntrinsicValue']) ? (float)$data['moneyIntrinsicValue'] : null;
        $instance->netChange             = isset($data['netChange']) ? (float)$data['netChange'] : null;
        $instance->netPercentChange      = isset($data['netPercentChange']) ? (float)$data['netPercentChange'] : null;
        $instance->openInterest          = isset($data['openInterest']) ? (float)$data['openInterest'] : null;
        $instance->openPrice             = isset($data['openPrice']) ? (float)$data['openPrice'] : null;
        $instance->quoteTime             = isset($data['quoteTime']) ? (int)$data['quoteTime'] : null;
        $instance->rho                   = isset($data['rho']) ? (float)$data['rho'] : null;
        $instance->securityStatus        = $data['securityStatus'] ?? null;
        $instance->theoreticalOptionValue = isset($data['theoreticalOptionValue']) ? (float)$data['theoreticalOptionValue'] : null;
        $instance->theta                 = isset($data['theta']) ? (float)$data['theta'] : null;
        $instance->timeValue             = isset($data['timeValue']) ? (float)$data['timeValue'] : null;
        $instance->totalVolume           = isset($data['totalVolume']) ? (float)$data['totalVolume'] : null;
        $instance->tradeTime             = isset($data['tradeTime']) ? (int)$data['tradeTime'] : null;
        $instance->underlyingPrice       = isset($data['underlyingPrice']) ? (float)$data['underlyingPrice'] : null;
        $instance->vega                  = isset($data['vega']) ? (float)$data['vega'] : null;
        $instance->volatility            = isset($data['volatility']) ? (float)$data['volatility'] : null;
        return $instance;
    }

    public function getFiftyTwoWeekHigh(): ?float { return $this->fiftyTwoWeekHigh; }
    public function getFiftyTwoWeekLow(): ?float { return $this->fiftyTwoWeekLow; }
    public function getAskPrice(): ?float { return $this->askPrice; }
    public function getAskSize(): ?float { return $this->askSize; }
    public function getBidPrice(): ?float { return $this->bidPrice; }
    public function getBidSize(): ?float { return $this->bidSize; }
    public function getClosePrice(): ?float { return $this->closePrice; }
    public function getDelta(): ?float { return $this->delta; }
    public function getGamma(): ?float { return $this->gamma; }
    public function getHighPrice(): ?float { return $this->highPrice; }
    public function getImpliedYield(): ?float { return $this->impliedYield; }
    public function getIndAskPrice(): ?float { return $this->indAskPrice; }
    public function getIndBidPrice(): ?float { return $this->indBidPrice; }
    public function getIndQuoteTime(): ?int { return $this->indQuoteTime; }
    public function getImpliedVolatility(): ?float { return $this->impliedVolatility; }
    public function getLastPrice(): ?float { return $this->lastPrice; }
    public function getLastSize(): ?float { return $this->lastSize; }
    public function getLowPrice(): ?float { return $this->lowPrice; }
    public function getMark(): ?float { return $this->mark; }
    public function getMarkChange(): ?float { return $this->markChange; }
    public function getMarkPercentChange(): ?float { return $this->markPercentChange; }
    public function getMoneyIntrinsicValue(): ?float { return $this->moneyIntrinsicValue; }
    public function getNetChange(): ?float { return $this->netChange; }
    public function getNetPercentChange(): ?float { return $this->netPercentChange; }
    public function getOpenInterest(): ?float { return $this->openInterest; }
    public function getOpenPrice(): ?float { return $this->openPrice; }
    public function getQuoteTime(): ?int { return $this->quoteTime; }
    public function getRho(): ?float { return $this->rho; }
    public function getSecurityStatus(): ?string { return $this->securityStatus; }
    public function getTheoreticalOptionValue(): ?float { return $this->theoreticalOptionValue; }
    public function getTheta(): ?float { return $this->theta; }
    public function getTimeValue(): ?float { return $this->timeValue; }
    public function getTotalVolume(): ?float { return $this->totalVolume; }
    public function getTradeTime(): ?int { return $this->tradeTime; }
    public function getUnderlyingPrice(): ?float { return $this->underlyingPrice; }
    public function getVega(): ?float { return $this->vega; }
    public function getVolatility(): ?float { return $this->volatility; }
}
