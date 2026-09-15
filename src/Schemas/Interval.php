<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Interval extends AbstractSchema {

    protected ?string $start = null;
    protected ?string $end = null;

    public function __construct( ?string $start = null, ?string $end = null ) {
        $this->start = $start;
        $this->end   = $end;
    }

    public static function fromArray( array $data ): static {
        return new static(
            start: $data['start'] ?? null,
            end: $data['end'] ?? null
        );
    }

    public function getStart(): ?string { return $this->start; }
    public function getEnd(): ?string { return $this->end; }
}
