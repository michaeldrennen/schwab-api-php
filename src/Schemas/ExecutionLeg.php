<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class ExecutionLeg extends AbstractSchema {

    protected ?int $legId = null;
    protected ?float $price = null;
    protected ?float $quantity = null;
    protected ?float $mismarkedQuantity = null;
    protected ?int $instrumentId = null;
    protected ?string $time = null;

    public function __construct(
        ?int $legId = null,
        ?float $price = null,
        ?float $quantity = null,
        ?float $mismarkedQuantity = null,
        ?int $instrumentId = null,
        ?string $time = null
    ) {
        $this->legId             = $legId;
        $this->price             = $price;
        $this->quantity          = $quantity;
        $this->mismarkedQuantity = $mismarkedQuantity;
        $this->instrumentId      = $instrumentId;
        $this->time              = $time;
    }

    public static function fromArray( array $data ): static {
        return new static(
            legId: isset($data['legId']) ? (int)$data['legId'] : null,
            price: isset($data['price']) ? (float)$data['price'] : null,
            quantity: isset($data['quantity']) ? (float)$data['quantity'] : null,
            mismarkedQuantity: isset($data['mismarkedQuantity']) ? (float)$data['mismarkedQuantity'] : null,
            instrumentId: isset($data['instrumentId']) ? (int)$data['instrumentId'] : null,
            time: $data['time'] ?? null
        );
    }

    public function getLegId(): ?int { return $this->legId; }
    public function getPrice(): ?float { return $this->price; }
    public function getQuantity(): ?float { return $this->quantity; }
    public function getMismarkedQuantity(): ?float { return $this->mismarkedQuantity; }
    public function getInstrumentId(): ?int { return $this->instrumentId; }
    public function getTime(): ?string { return $this->time; }
}
