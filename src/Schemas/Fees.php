<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Fees extends AbstractSchema {

    /**
     * @var FeeLeg[]
     */
    protected array $feeLegs = [];

    public function __construct( array $feeLegs = [] ) {
        $this->feeLegs = $feeLegs;
    }

    public static function fromArray( array $data ): static {
        $legs = [];
        if ( isset($data['feeLegs']) && is_array($data['feeLegs']) ) {
            $legs = array_map(
                fn($l) => is_array($l) ? FeeLeg::fromArray($l) : $l,
                $data['feeLegs']
            );
        }

        return new static(
            feeLegs: $legs
        );
    }

    public function getFeeLegs(): array {
        return $this->feeLegs;
    }
}
