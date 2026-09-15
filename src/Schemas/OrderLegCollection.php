<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class OrderLegCollection extends AbstractSchema {

    protected ?string $orderLegType = null;
    protected ?int $legId = null;
    protected null|AbstractAccountsInstrument|Instrument|array $instrument = null;
    protected ?string $instruction = null;
    protected ?string $positionEffect = null;
    protected ?float $quantity = null;
    protected ?string $quantityType = null;
    protected ?string $divCapGains = null;
    protected ?string $toSymbol = null;

    public function __construct(
        ?string $orderLegType = null,
        ?int $legId = null,
        null|AbstractAccountsInstrument|Instrument|array $instrument = null,
        ?string $instruction = null,
        ?string $positionEffect = null,
        ?float $quantity = null,
        ?string $quantityType = null,
        ?string $divCapGains = null,
        ?string $toSymbol = null
    ) {
        $this->orderLegType   = $orderLegType;
        $this->legId          = $legId;
        $this->instrument     = $instrument;
        $this->instruction    = $instruction;
        $this->positionEffect = $positionEffect;
        $this->quantity       = $quantity;
        $this->quantityType   = $quantityType;
        $this->divCapGains    = $divCapGains;
        $this->toSymbol       = $toSymbol;
    }

    public static function fromArray( array $data ): static {
        $instrument = null;
        if ( isset($data['instrument']) ) {
            $instrument = is_array($data['instrument'])
                ? Instrument::fromArray($data['instrument'])
                : $data['instrument'];
        }

        return new static(
            orderLegType: $data['orderLegType'] ?? null,
            legId: isset($data['legId']) ? (int)$data['legId'] : null,
            instrument: $instrument,
            instruction: $data['instruction'] ?? null,
            positionEffect: $data['positionEffect'] ?? null,
            quantity: isset($data['quantity']) ? (float)$data['quantity'] : null,
            quantityType: $data['quantityType'] ?? null,
            divCapGains: $data['divCapGains'] ?? null,
            toSymbol: $data['toSymbol'] ?? null
        );
    }

    public function getOrderLegType(): ?string { return $this->orderLegType; }
    public function getLegId(): ?int { return $this->legId; }
    public function getInstrument(): null|AbstractAccountsInstrument|Instrument|array { return $this->instrument; }
    public function getInstruction(): ?string { return $this->instruction; }
    public function getPositionEffect(): ?string { return $this->positionEffect; }
    public function getQuantity(): ?float { return $this->quantity; }
    public function getQuantityType(): ?string { return $this->quantityType; }
    public function getDivCapGains(): ?string { return $this->divCapGains; }
    public function getToSymbol(): ?string { return $this->toSymbol; }
}
