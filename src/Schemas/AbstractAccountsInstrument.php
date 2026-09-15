<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

abstract class AbstractAccountsInstrument extends AbstractSchema {

    protected ?string $assetType = null;
    protected ?string $cusip = null;
    protected ?string $symbol = null;
    protected ?string $description = null;
    protected ?string $instrumentId = null;
    protected ?float $netChange = null;

    public function __construct(
        ?string $assetType = null,
        ?string $cusip = null,
        ?string $symbol = null,
        ?string $description = null,
        ?string $instrumentId = null,
        ?float $netChange = null
    ) {
        $this->assetType    = $assetType;
        $this->cusip        = $cusip;
        $this->symbol       = $symbol;
        $this->description  = $description;
        $this->instrumentId = $instrumentId;
        $this->netChange    = $netChange;
    }

    public static function fromArray( array $data ): static {
        return new static(
            assetType: $data['assetType'] ?? null,
            cusip: $data['cusip'] ?? null,
            symbol: $data['symbol'] ?? null,
            description: $data['description'] ?? null,
            instrumentId: isset($data['instrumentId']) ? (string)$data['instrumentId'] : null,
            netChange: isset($data['netChange']) ? (float)$data['netChange'] : null
        );
    }

    public function getAssetType(): ?string { return $this->assetType; }
    public function getCusip(): ?string { return $this->cusip; }
    public function getSymbol(): ?string { return $this->symbol; }
    public function getDescription(): ?string { return $this->description; }
    public function getInstrumentId(): ?string { return $this->instrumentId; }
    public function getNetChange(): ?float { return $this->netChange; }
}
