<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Bond extends AbstractSchema {

    protected ?string $cusip = null;
    protected ?string $symbol = null;
    protected ?string $description = null;
    protected ?string $exchange = null;
    protected ?string $assetType = null;
    protected ?string $bondFactor = null;
    protected ?float $bondMultiplier = null;
    protected ?float $bondPrice = null;

    public function __construct(
        ?string $cusip = null,
        ?string $symbol = null,
        ?string $description = null,
        ?string $exchange = null,
        ?string $assetType = null,
        ?string $bondFactor = null,
        ?float $bondMultiplier = null,
        ?float $bondPrice = null
    ) {
        $this->cusip          = $cusip;
        $this->symbol         = $symbol;
        $this->description    = $description;
        $this->exchange       = $exchange;
        $this->assetType      = $assetType;
        $this->bondFactor     = $bondFactor;
        $this->bondMultiplier = $bondMultiplier;
        $this->bondPrice      = $bondPrice;
    }

    public static function fromArray( array $data ): static {
        return new static(
            cusip: $data['cusip'] ?? null,
            symbol: $data['symbol'] ?? null,
            description: $data['description'] ?? null,
            exchange: $data['exchange'] ?? null,
            assetType: $data['assetType'] ?? null,
            bondFactor: $data['bondFactor'] ?? null,
            bondMultiplier: isset($data['bondMultiplier']) ? (float)$data['bondMultiplier'] : null,
            bondPrice: isset($data['bondPrice']) ? (float)$data['bondPrice'] : null
        );
    }

    public function getCusip(): ?string { return $this->cusip; }
    public function getSymbol(): ?string { return $this->symbol; }
    public function getDescription(): ?string { return $this->description; }
    public function getExchange(): ?string { return $this->exchange; }
    public function getAssetType(): ?string { return $this->assetType; }
    public function getBondFactor(): ?string { return $this->bondFactor; }
    public function getBondMultiplier(): ?float { return $this->bondMultiplier; }
    public function getBondPrice(): ?float { return $this->bondPrice; }
}
