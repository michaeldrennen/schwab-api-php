<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class CommissionLeg extends AbstractSchema {

    /**
     * @var CommissionValue[]
     */
    protected array $commissionValues = [];

    public function __construct( array $commissionValues = [] ) {
        $this->commissionValues = $commissionValues;
    }

    public static function fromArray( array $data ): static {
        $values = [];
        if ( isset($data['commissionValues']) && is_array($data['commissionValues']) ) {
            $values = array_map(
                fn($v) => is_array($v) ? CommissionValue::fromArray($v) : $v,
                $data['commissionValues']
            );
        }

        return new static(
            commissionValues: $values
        );
    }

    public function getCommissionValues(): array {
        return $this->commissionValues;
    }
}
