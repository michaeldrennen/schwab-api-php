<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class OptionDeliverables extends AbstractSchema {

    protected ?string $symbol = null;
    protected ?string $assetType = null;
    protected ?string $deliverableUnits = null;
    protected ?string $currencyType = null;

    public function __construct(
        ?string $symbol = null,
        ?string $assetType = null,
        ?string $deliverableUnits = null,
        ?string $currencyType = null
    ) {
        $this->symbol           = $symbol;
        $this->assetType        = $assetType;
        $this->deliverableUnits = $deliverableUnits;
        $this->currencyType     = $currencyType;
    }

    public static function fromArray( array $data ): static {
        return new static(
            symbol: $data['symbol'] ?? null,
            assetType: $data['assetType'] ?? null,
            deliverableUnits: $data['deliverableUnits'] ?? null,
            currencyType: $data['currencyType'] ?? null
        );
    }

    public function getSymbol(): ?string { return $this->symbol; }
    public function getAssetType(): ?string { return $this->assetType; }
    public function getDeliverableUnits(): ?string { return $this->deliverableUnits; }
    public function getCurrencyType(): ?string { return $this->currencyType; }
}
