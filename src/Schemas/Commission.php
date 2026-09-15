<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Commission extends AbstractSchema {

    /**
     * @var CommissionLeg[]
     */
    protected array $commissionLegs = [];

    public function __construct( array $commissionLegs = [] ) {
        $this->commissionLegs = $commissionLegs;
    }

    public static function fromArray( array $data ): static {
        $legs = [];
        if ( isset($data['commissionLegs']) && is_array($data['commissionLegs']) ) {
            $legs = array_map(
                fn($l) => is_array($l) ? CommissionLeg::fromArray($l) : $l,
                $data['commissionLegs']
            );
        }

        return new static(
            commissionLegs: $legs
        );
    }

    public function getCommissionLegs(): array {
        return $this->commissionLegs;
    }
}
