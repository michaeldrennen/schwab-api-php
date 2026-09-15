<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class FundamentalInst extends AbstractSchema {

    protected ?string $cusip = null;
    protected ?string $symbol = null;
    protected ?string $description = null;
    protected ?string $exchange = null;
    protected ?string $assetType = null;
    protected ?Fundamental $fundamental = null;

    public function __construct(
        ?string $cusip = null,
        ?string $symbol = null,
        ?string $description = null,
        ?string $exchange = null,
        ?string $assetType = null,
        ?Fundamental $fundamental = null
    ) {
        $this->cusip       = $cusip;
        $this->symbol      = $symbol;
        $this->description = $description;
        $this->exchange    = $exchange;
        $this->assetType   = $assetType;
        $this->fundamental = $fundamental;
    }

    public static function fromArray( array $data ): static {
        $fundamental = isset($data['fundamental']) && is_array($data['fundamental'])
            ? Fundamental::fromArray($data['fundamental'])
            : null;

        return new static(
            cusip: $data['cusip'] ?? null,
            symbol: $data['symbol'] ?? null,
            description: $data['description'] ?? null,
            exchange: $data['exchange'] ?? null,
            assetType: $data['assetType'] ?? null,
            fundamental: $fundamental
        );
    }

    public function getCusip(): ?string { return $this->cusip; }
    public function getSymbol(): ?string { return $this->symbol; }
    public function getDescription(): ?string { return $this->description; }
    public function getExchange(): ?string { return $this->exchange; }
    public function getAssetType(): ?string { return $this->assetType; }
    public function getFundamental(): ?Fundamental { return $this->fundamental; }
}
