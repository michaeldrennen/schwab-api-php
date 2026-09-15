<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class CommissionValue extends AbstractSchema {

    protected ?float $value = null;
    protected ?string $type = null;

    public function __construct( ?float $value = null, ?string $type = null ) {
        $this->value = $value;
        $this->type  = $type;
    }

    public static function fromArray( array $data ): static {
        return new static(
            value: isset($data['value']) ? (float)$data['value'] : null,
            type: $data['type'] ?? null
        );
    }

    public function getValue(): ?float { return $this->value; }
    public function getType(): ?string { return $this->type; }
}
