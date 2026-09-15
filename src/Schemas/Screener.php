<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Screener extends AbstractSchema {

    protected ?int $total = null;
    /**
     * @var array
     */
    protected array $screeners = [];
    /**
     * @var array
     */
    protected array $values = [];

    public function __construct( ?int $total = null, array $screeners = [], array $values = [] ) {
        $this->total     = $total;
        $this->screeners = $screeners;
        $this->values    = $values;
    }

    public static function fromArray( array $data ): static {
        $screeners = $data['screeners'] ?? [];
        $values    = $data['values'] ?? [];

        return new static(
            total: isset($data['total']) ? (int)$data['total'] : count($screeners),
            screeners: $screeners,
            values: $values
        );
    }

    public function getTotal(): ?int { return $this->total; }
    public function getScreeners(): array { return $this->screeners; }
    public function getValues(): array { return $this->values; }
}
