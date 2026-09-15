<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class OptionContract extends AbstractSchema {

    protected ?string $putCall = null;
    protected ?string $symbol = null;
    protected ?string $description = null;
    protected ?string $exchangeName = null;
    protected ?float $bid = null;
    protected ?float $ask = null;
    protected ?float $last = null;
    protected ?float $mark = null;
    protected ?int $bidSize = null;
    protected ?int $askSize = null;
    protected ?int $lastSize = null;
    protected ?float $highPrice = null;
    protected ?float $lowPrice = null;
    protected ?float $openPrice = null;
    protected ?float $closePrice = null;
    protected ?int $totalVolume = null;
    protected ?int $tradeDate = null;
    protected ?int $tradeTimeInLong = null;
    protected ?int $quoteTimeInLong = null;
    protected ?float $netChange = null;
    protected ?float $volatility = null;
    protected ?float $delta = null;
    protected ?float $gamma = null;
    protected ?float $theta = null;
    protected ?float $vega = null;
    protected ?float $rho = null;
    protected ?int $openInterest = null;
    protected ?float $timeValue = null;
    protected ?float $theoreticalOptionValue = null;
    protected ?float $theoreticalVolatility = null;
    /**
     * @var OptionDeliverables[]
     */
    protected array $optionDeliverablesList = [];
    protected ?float $strikePrice = null;
    protected ?string $expirationDate = null;
    protected ?int $daysToExpiration = null;
    protected ?string $expirationType = null;
    protected ?int $lastTradingDay = null;
    protected ?float $multiplier = null;
    protected ?string $settlementType = null;
    protected ?string $deliverableNote = null;
    protected ?bool $isIndexOption = null;
    protected ?float $percentChange = null;
    protected ?float $markChange = null;
    protected ?float $markPercentChange = null;
    protected ?float $intrinsicValue = null;
    protected ?bool $inTheMoney = null;
    protected ?bool $mini = null;
    protected ?bool $nonStandard = null;

    public static function fromArray( array $data ): static {
        $instance = new static();
        $instance->putCall                = $data['putCall'] ?? null;
        $instance->symbol                 = $data['symbol'] ?? null;
        $instance->description            = $data['description'] ?? null;
        $instance->exchangeName           = $data['exchangeName'] ?? null;
        $instance->bid                    = isset($data['bid']) ? (float)$data['bid'] : null;
        $instance->ask                    = isset($data['ask']) ? (float)$data['ask'] : null;
        $instance->last                   = isset($data['last']) ? (float)$data['last'] : null;
        $instance->mark                   = isset($data['mark']) ? (float)$data['mark'] : null;
        $instance->bidSize                = isset($data['bidSize']) ? (int)$data['bidSize'] : null;
        $instance->askSize                = isset($data['askSize']) ? (int)$data['askSize'] : null;
        $instance->lastSize               = isset($data['lastSize']) ? (int)$data['lastSize'] : null;
        $instance->highPrice              = isset($data['highPrice']) ? (float)$data['highPrice'] : null;
        $instance->lowPrice               = isset($data['lowPrice']) ? (float)$data['lowPrice'] : null;
        $instance->openPrice              = isset($data['openPrice']) ? (float)$data['openPrice'] : null;
        $instance->closePrice             = isset($data['closePrice']) ? (float)$data['closePrice'] : null;
        $instance->totalVolume            = isset($data['totalVolume']) ? (int)$data['totalVolume'] : null;
        $instance->tradeDate              = isset($data['tradeDate']) ? (int)$data['tradeDate'] : null;
        $instance->tradeTimeInLong        = isset($data['tradeTimeInLong']) ? (int)$data['tradeTimeInLong'] : null;
        $instance->quoteTimeInLong        = isset($data['quoteTimeInLong']) ? (int)$data['quoteTimeInLong'] : null;
        $instance->netChange              = isset($data['netChange']) ? (float)$data['netChange'] : null;
        $instance->volatility             = isset($data['volatility']) ? (float)$data['volatility'] : null;
        $instance->delta                  = isset($data['delta']) ? (float)$data['delta'] : null;
        $instance->gamma                  = isset($data['gamma']) ? (float)$data['gamma'] : null;
        $instance->theta                  = isset($data['theta']) ? (float)$data['theta'] : null;
        $instance->vega                   = isset($data['vega']) ? (float)$data['vega'] : null;
        $instance->rho                    = isset($data['rho']) ? (float)$data['rho'] : null;
        $instance->openInterest           = isset($data['openInterest']) ? (int)$data['openInterest'] : null;
        $instance->timeValue              = isset($data['timeValue']) ? (float)$data['timeValue'] : null;
        $instance->theoreticalOptionValue = isset($data['theoreticalOptionValue']) ? (float)$data['theoreticalOptionValue'] : null;
        $instance->theoreticalVolatility  = isset($data['theoreticalVolatility']) ? (float)$data['theoreticalVolatility'] : null;
        
        if ( isset($data['optionDeliverablesList']) && is_array($data['optionDeliverablesList']) ) {
            $instance->optionDeliverablesList = array_map(
                fn($d) => is_array($d) ? OptionDeliverables::fromArray($d) : $d,
                $data['optionDeliverablesList']
            );
        }

        $instance->strikePrice            = isset($data['strikePrice']) ? (float)$data['strikePrice'] : null;
        $instance->expirationDate         = $data['expirationDate'] ?? null;
        $instance->daysToExpiration       = isset($data['daysToExpiration']) ? (int)$data['daysToExpiration'] : null;
        $instance->expirationType         = $data['expirationType'] ?? null;
        $instance->lastTradingDay         = isset($data['lastTradingDay']) ? (int)$data['lastTradingDay'] : null;
        $instance->multiplier             = isset($data['multiplier']) ? (float)$data['multiplier'] : null;
        $instance->settlementType         = $data['settlementType'] ?? null;
        $instance->deliverableNote        = $data['deliverableNote'] ?? null;
        $instance->isIndexOption          = isset($data['isIndexOption']) ? (bool)$data['isIndexOption'] : null;
        $instance->percentChange          = isset($data['percentChange']) ? (float)$data['percentChange'] : null;
        $instance->markChange             = isset($data['markChange']) ? (float)$data['markChange'] : null;
        $instance->markPercentChange      = isset($data['markPercentChange']) ? (float)$data['markPercentChange'] : null;
        $instance->intrinsicValue         = isset($data['intrinsicValue']) ? (float)$data['intrinsicValue'] : null;
        $instance->inTheMoney             = isset($data['inTheMoney']) ? (bool)$data['inTheMoney'] : null;
        $instance->mini                   = isset($data['mini']) ? (bool)$data['mini'] : null;
        $instance->nonStandard            = isset($data['nonStandard']) ? (bool)$data['nonStandard'] : null;

        return $instance;
    }

    public function getPutCall(): ?string { return $this->putCall; }
    public function getSymbol(): ?string { return $this->symbol; }
    public function getDescription(): ?string { return $this->description; }
    public function getExchangeName(): ?string { return $this->exchangeName; }
    public function getBid(): ?float { return $this->bid; }
    public function getAsk(): ?float { return $this->ask; }
    public function getLast(): ?float { return $this->last; }
    public function getMark(): ?float { return $this->mark; }
    public function getBidSize(): ?int { return $this->bidSize; }
    public function getAskSize(): ?int { return $this->askSize; }
    public function getLastSize(): ?int { return $this->lastSize; }
    public function getHighPrice(): ?float { return $this->highPrice; }
    public function getLowPrice(): ?float { return $this->lowPrice; }
    public function getOpenPrice(): ?float { return $this->openPrice; }
    public function getClosePrice(): ?float { return $this->closePrice; }
    public function getTotalVolume(): ?int { return $this->totalVolume; }
    public function getTradeDate(): ?int { return $this->tradeDate; }
    public function getTradeTimeInLong(): ?int { return $this->tradeTimeInLong; }
    public function getQuoteTimeInLong(): ?int { return $this->quoteTimeInLong; }
    public function getNetChange(): ?float { return $this->netChange; }
    public function getVolatility(): ?float { return $this->volatility; }
    public function getDelta(): ?float { return $this->delta; }
    public function getGamma(): ?float { return $this->gamma; }
    public function getTheta(): ?float { return $this->theta; }
    public function getVega(): ?float { return $this->vega; }
    public function getRho(): ?float { return $this->rho; }
    public function getOpenInterest(): ?int { return $this->openInterest; }
    public function getTimeValue(): ?float { return $this->timeValue; }
    public function getTheoreticalOptionValue(): ?float { return $this->theoreticalOptionValue; }
    public function getTheoreticalVolatility(): ?float { return $this->theoreticalVolatility; }
    public function getOptionDeliverablesList(): array { return $this->optionDeliverablesList; }
    public function getStrikePrice(): ?float { return $this->strikePrice; }
    public function getExpirationDate(): ?string { return $this->expirationDate; }
    public function getDaysToExpiration(): ?int { return $this->daysToExpiration; }
    public function getExpirationType(): ?string { return $this->expirationType; }
    public function getLastTradingDay(): ?int { return $this->lastTradingDay; }
    public function getMultiplier(): ?float { return $this->multiplier; }
    public function getSettlementType(): ?string { return $this->settlementType; }
    public function getDeliverableNote(): ?string { return $this->deliverableNote; }
    public function isIndexOption(): ?bool { return $this->isIndexOption; }
    public function getPercentChange(): ?float { return $this->percentChange; }
    public function getMarkChange(): ?float { return $this->markChange; }
    public function getMarkPercentChange(): ?float { return $this->markPercentChange; }
    public function getIntrinsicValue(): ?float { return $this->intrinsicValue; }
    public function isInTheMoney(): ?bool { return $this->inTheMoney; }
    public function isMini(): ?bool { return $this->mini; }
    public function isNonStandard(): ?bool { return $this->nonStandard; }
}
