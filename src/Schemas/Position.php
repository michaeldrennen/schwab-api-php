<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Position extends AbstractSchema {

    protected ?float $shortQuantity = null;
    protected ?float $averagePrice = null;
    protected ?float $currentDayProfitLoss = null;
    protected ?float $currentDayProfitLossPercentage = null;
    protected ?float $longQuantity = null;
    protected ?float $settledLongQuantity = null;
    protected ?float $settledShortQuantity = null;
    protected ?float $agedQuantity = null;
    protected null|Instrument|array $instrument = null;
    protected ?float $marketValue = null;
    protected ?float $maintenanceRequirement = null;
    protected ?float $averageLongPrice = null;
    protected ?float $averageShortPrice = null;
    protected ?float $taxLotAverageLongPrice = null;
    protected ?float $taxLotAverageShortPrice = null;
    protected ?float $longOpenProfitLoss = null;
    protected ?float $shortOpenProfitLoss = null;
    protected ?float $previousSessionLongQuantity = null;
    protected ?float $previousSessionShortQuantity = null;
    protected ?float $currentDayCost = null;

    public function __construct(
        ?float $shortQuantity = null,
        ?float $averagePrice = null,
        ?float $currentDayProfitLoss = null,
        ?float $currentDayProfitLossPercentage = null,
        ?float $longQuantity = null,
        ?float $settledLongQuantity = null,
        ?float $settledShortQuantity = null,
        ?float $agedQuantity = null,
        null|Instrument|array $instrument = null,
        ?float $marketValue = null,
        ?float $maintenanceRequirement = null,
        ?float $averageLongPrice = null,
        ?float $averageShortPrice = null,
        ?float $taxLotAverageLongPrice = null,
        ?float $taxLotAverageShortPrice = null,
        ?float $longOpenProfitLoss = null,
        ?float $shortOpenProfitLoss = null,
        ?float $previousSessionLongQuantity = null,
        ?float $previousSessionShortQuantity = null,
        ?float $currentDayCost = null
    ) {
        $this->shortQuantity                  = $shortQuantity;
        $this->averagePrice                   = $averagePrice;
        $this->currentDayProfitLoss           = $currentDayProfitLoss;
        $this->currentDayProfitLossPercentage = $currentDayProfitLossPercentage;
        $this->longQuantity                   = $longQuantity;
        $this->settledLongQuantity            = $settledLongQuantity;
        $this->settledShortQuantity           = $settledShortQuantity;
        $this->agedQuantity                   = $agedQuantity;
        $this->instrument                     = $instrument;
        $this->marketValue                    = $marketValue;
        $this->maintenanceRequirement         = $maintenanceRequirement;
        $this->averageLongPrice               = $averageLongPrice;
        $this->averageShortPrice              = $averageShortPrice;
        $this->taxLotAverageLongPrice         = $taxLotAverageLongPrice;
        $this->taxLotAverageShortPrice        = $taxLotAverageShortPrice;
        $this->longOpenProfitLoss             = $longOpenProfitLoss;
        $this->shortOpenProfitLoss            = $shortOpenProfitLoss;
        $this->previousSessionLongQuantity    = $previousSessionLongQuantity;
        $this->previousSessionShortQuantity   = $previousSessionShortQuantity;
        $this->currentDayCost                 = $currentDayCost;
    }

    public static function fromArray( array $data ): static {
        $instrument = null;
        if ( isset($data['instrument']) ) {
            $instrument = is_array($data['instrument'])
                ? Instrument::fromArray($data['instrument'])
                : $data['instrument'];
        }

        return new static(
            shortQuantity: isset($data['shortQuantity']) ? (float)$data['shortQuantity'] : null,
            averagePrice: isset($data['averagePrice']) ? (float)$data['averagePrice'] : null,
            currentDayProfitLoss: isset($data['currentDayProfitLoss']) ? (float)$data['currentDayProfitLoss'] : null,
            currentDayProfitLossPercentage: isset($data['currentDayProfitLossPercentage']) ? (float)$data['currentDayProfitLossPercentage'] : null,
            longQuantity: isset($data['longQuantity']) ? (float)$data['longQuantity'] : null,
            settledLongQuantity: isset($data['settledLongQuantity']) ? (float)$data['settledLongQuantity'] : null,
            settledShortQuantity: isset($data['settledShortQuantity']) ? (float)$data['settledShortQuantity'] : null,
            agedQuantity: isset($data['agedQuantity']) ? (float)$data['agedQuantity'] : null,
            instrument: $instrument,
            marketValue: isset($data['marketValue']) ? (float)$data['marketValue'] : null,
            maintenanceRequirement: isset($data['maintenanceRequirement']) ? (float)$data['maintenanceRequirement'] : null,
            averageLongPrice: isset($data['averageLongPrice']) ? (float)$data['averageLongPrice'] : null,
            averageShortPrice: isset($data['averageShortPrice']) ? (float)$data['averageShortPrice'] : null,
            taxLotAverageLongPrice: isset($data['taxLotAverageLongPrice']) ? (float)$data['taxLotAverageLongPrice'] : null,
            taxLotAverageShortPrice: isset($data['taxLotAverageShortPrice']) ? (float)$data['taxLotAverageShortPrice'] : null,
            longOpenProfitLoss: isset($data['longOpenProfitLoss']) ? (float)$data['longOpenProfitLoss'] : null,
            shortOpenProfitLoss: isset($data['shortOpenProfitLoss']) ? (float)$data['shortOpenProfitLoss'] : null,
            previousSessionLongQuantity: isset($data['previousSessionLongQuantity']) ? (float)$data['previousSessionLongQuantity'] : null,
            previousSessionShortQuantity: isset($data['previousSessionShortQuantity']) ? (float)$data['previousSessionShortQuantity'] : null,
            currentDayCost: isset($data['currentDayCost']) ? (float)$data['currentDayCost'] : null
        );
    }

    public function getShortQuantity(): ?float { return $this->shortQuantity; }
    public function getAveragePrice(): ?float { return $this->averagePrice; }
    public function getCurrentDayProfitLoss(): ?float { return $this->currentDayProfitLoss; }
    public function getCurrentDayProfitLossPercentage(): ?float { return $this->currentDayProfitLossPercentage; }
    public function getLongQuantity(): ?float { return $this->longQuantity; }
    public function getSettledLongQuantity(): ?float { return $this->settledLongQuantity; }
    public function getSettledShortQuantity(): ?float { return $this->settledShortQuantity; }
    public function getAgedQuantity(): ?float { return $this->agedQuantity; }
    public function getInstrument(): null|Instrument|array { return $this->instrument; }
    public function getMarketValue(): ?float { return $this->marketValue; }
    public function getMaintenanceRequirement(): ?float { return $this->maintenanceRequirement; }
    public function getAverageLongPrice(): ?float { return $this->averageLongPrice; }
    public function getAverageShortPrice(): ?float { return $this->averageShortPrice; }
    public function getTaxLotAverageLongPrice(): ?float { return $this->taxLotAverageLongPrice; }
    public function getTaxLotAverageShortPrice(): ?float { return $this->taxLotAverageShortPrice; }
    public function getLongOpenProfitLoss(): ?float { return $this->longOpenProfitLoss; }
    public function getShortOpenProfitLoss(): ?float { return $this->shortOpenProfitLoss; }
    public function getPreviousSessionLongQuantity(): ?float { return $this->previousSessionLongQuantity; }
    public function getPreviousSessionShortQuantity(): ?float { return $this->previousSessionShortQuantity; }
    public function getCurrentDayCost(): ?float { return $this->currentDayCost; }
}
