<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class TransferItem extends AbstractSchema {

    protected null|Instrument|array $instrument = null;
    protected ?float $amount = null;
    protected ?float $cost = null;
    protected ?float $price = null;
    protected ?string $feeType = null;
    protected ?string $positionEffect = null;

    public function __construct(
        null|Instrument|array $instrument = null,
        ?float $amount = null,
        ?float $cost = null,
        ?float $price = null,
        ?string $feeType = null,
        ?string $positionEffect = null
    ) {
        $this->instrument     = $instrument;
        $this->amount         = $amount;
        $this->cost           = $cost;
        $this->price          = $price;
        $this->feeType        = $feeType;
        $this->positionEffect = $positionEffect;
    }

    public static function fromArray( array $data ): static {
        $instrument = null;
        if ( isset($data['instrument']) ) {
            $instrument = is_array($data['instrument'])
                ? Instrument::fromArray($data['instrument'])
                : $data['instrument'];
        }

        return new static(
            instrument: $instrument,
            amount: isset($data['amount']) ? (float)$data['amount'] : null,
            cost: isset($data['cost']) ? (float)$data['cost'] : null,
            price: isset($data['price']) ? (float)$data['price'] : null,
            feeType: $data['feeType'] ?? null,
            positionEffect: $data['positionEffect'] ?? null
        );
    }

    public function getInstrument(): null|Instrument|array { return $this->instrument; }
    public function getAmount(): ?float { return $this->amount; }
    public function getCost(): ?float { return $this->cost; }
    public function getPrice(): ?float { return $this->price; }
    public function getFeeType(): ?string { return $this->feeType; }
    public function getPositionEffect(): ?string { return $this->positionEffect; }
}
