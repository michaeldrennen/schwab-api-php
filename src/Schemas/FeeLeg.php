<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class FeeLeg extends AbstractSchema {

    /**
     * @var FeeValue[]
     */
    protected array $feeValues = [];

    public function __construct( array $feeValues = [] ) {
        $this->feeValues = $feeValues;
    }

    public static function fromArray( array $data ): static {
        $values = [];
        if ( isset($data['feeValues']) && is_array($data['feeValues']) ) {
            $values = array_map(
                fn($v) => is_array($v) ? FeeValue::fromArray($v) : $v,
                $data['feeValues']
            );
        }

        return new static(
            feeValues: $values
        );
    }

    public function getFeeValues(): array {
        return $this->feeValues;
    }
}
