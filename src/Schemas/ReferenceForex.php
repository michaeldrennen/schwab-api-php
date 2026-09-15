<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class ReferenceForex extends AbstractSchema {

    protected ?string $description = null;
    protected ?string $exchange = null;
    protected ?string $exchangeName = null;
    protected ?bool $isFsi = null;

    public function __construct(
        ?string $description = null,
        ?string $exchange = null,
        ?string $exchangeName = null,
        ?bool $isFsi = null
    ) {
        $this->description  = $description;
        $this->exchange     = $exchange;
        $this->exchangeName = $exchangeName;
        $this->isFsi        = $isFsi;
    }

    public static function fromArray( array $data ): static {
        return new static(
            description: $data['description'] ?? null,
            exchange: $data['exchange'] ?? null,
            exchangeName: $data['exchangeName'] ?? null,
            isFsi: isset($data['isFsi']) ? (bool)$data['isFsi'] : null
        );
    }

    public function getDescription(): ?string { return $this->description; }
    public function getExchange(): ?string { return $this->exchange; }
    public function getExchangeName(): ?string { return $this->exchangeName; }
    public function isFsi(): ?bool { return $this->isFsi; }
}
