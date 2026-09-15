<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class ReferenceEquity extends AbstractSchema {

    protected ?string $cusip = null;
    protected ?string $description = null;
    protected ?string $exchange = null;
    protected ?string $exchangeName = null;
    protected ?bool $isFsi = null;
    protected ?bool $isHtb = null;
    protected ?bool $isHardToBorrow = null;
    protected ?bool $isShortable = null;
    protected ?float $htbRate = null;

    public function __construct(
        ?string $cusip = null,
        ?string $description = null,
        ?string $exchange = null,
        ?string $exchangeName = null,
        ?bool $isFsi = null,
        ?bool $isHtb = null,
        ?bool $isHardToBorrow = null,
        ?bool $isShortable = null,
        ?float $htbRate = null
    ) {
        $this->cusip          = $cusip;
        $this->description    = $description;
        $this->exchange       = $exchange;
        $this->exchangeName   = $exchangeName;
        $this->isFsi          = $isFsi;
        $this->isHtb          = $isHtb;
        $this->isHardToBorrow = $isHardToBorrow;
        $this->isShortable    = $isShortable;
        $this->htbRate        = $htbRate;
    }

    public static function fromArray( array $data ): static {
        return new static(
            cusip: $data['cusip'] ?? null,
            description: $data['description'] ?? null,
            exchange: $data['exchange'] ?? null,
            exchangeName: $data['exchangeName'] ?? null,
            isFsi: isset($data['isFsi']) ? (bool)$data['isFsi'] : null,
            isHtb: isset($data['isHtb']) ? (bool)$data['isHtb'] : null,
            isHardToBorrow: isset($data['isHardToBorrow']) ? (bool)$data['isHardToBorrow'] : null,
            isShortable: isset($data['isShortable']) ? (bool)$data['isShortable'] : null,
            htbRate: isset($data['htbRate']) ? (float)$data['htbRate'] : null
        );
    }

    public function getCusip(): ?string { return $this->cusip; }
    public function getDescription(): ?string { return $this->description; }
    public function getExchange(): ?string { return $this->exchange; }
    public function getExchangeName(): ?string { return $this->exchangeName; }
    public function isFsi(): ?bool { return $this->isFsi; }
    public function isHtb(): ?bool { return $this->isHtb; }
    public function isHardToBorrow(): ?bool { return $this->isHardToBorrow; }
    public function isShortable(): ?bool { return $this->isShortable; }
    public function getHtbRate(): ?float { return $this->htbRate; }
}
