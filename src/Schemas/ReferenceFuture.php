<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class ReferenceFuture extends AbstractSchema {

    protected ?string $description = null;
    protected ?string $exchange = null;
    protected ?string $exchangeName = null;
    protected ?float $futureMultiplier = null;
    protected ?float $futurePriceFormat = null;
    protected ?string $futureSettlementPrice = null;
    protected ?float $futureTickSize = null;
    protected ?bool $isFsi = null;

    public static function fromArray( array $data ): static {
        $instance = new static();
        $instance->description           = $data['description'] ?? null;
        $instance->exchange              = $data['exchange'] ?? null;
        $instance->exchangeName          = $data['exchangeName'] ?? null;
        $instance->futureMultiplier      = isset($data['futureMultiplier']) ? (float)$data['futureMultiplier'] : null;
        $instance->futurePriceFormat     = isset($data['futurePriceFormat']) ? (float)$data['futurePriceFormat'] : null;
        $instance->futureSettlementPrice = $data['futureSettlementPrice'] ?? null;
        $instance->futureTickSize        = isset($data['futureTickSize']) ? (float)$data['futureTickSize'] : null;
        $instance->isFsi                 = isset($data['isFsi']) ? (bool)$data['isFsi'] : null;
        return $instance;
    }

    public function getDescription(): ?string { return $this->description; }
    public function getExchange(): ?string { return $this->exchange; }
    public function getExchangeName(): ?string { return $this->exchangeName; }
    public function getFutureMultiplier(): ?float { return $this->futureMultiplier; }
    public function getFuturePriceFormat(): ?float { return $this->futurePriceFormat; }
    public function getFutureSettlementPrice(): ?string { return $this->futureSettlementPrice; }
    public function getFutureTickSize(): ?float { return $this->futureTickSize; }
    public function isFsi(): ?bool { return $this->isFsi; }
}
