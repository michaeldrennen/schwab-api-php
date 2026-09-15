<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Expiration extends AbstractSchema {

    protected ?string $expirationDate = null;
    protected ?int $daysToExpiration = null;
    protected ?string $expirationType = null;
    protected ?bool $standard = null;

    public function __construct(
        ?string $expirationDate = null,
        ?int $daysToExpiration = null,
        ?string $expirationType = null,
        ?bool $standard = null
    ) {
        $this->expirationDate   = $expirationDate;
        $this->daysToExpiration = $daysToExpiration;
        $this->expirationType   = $expirationType;
        $this->standard         = $standard;
    }

    public static function fromArray( array $data ): static {
        return new static(
            expirationDate: $data['expirationDate'] ?? null,
            daysToExpiration: isset($data['daysToExpiration']) ? (int)$data['daysToExpiration'] : null,
            expirationType: $data['expirationType'] ?? null,
            standard: isset($data['standard']) ? (bool)$data['standard'] : (isset($data['isStandard']) ? (bool)$data['isStandard'] : null)
        );
    }

    public function getExpirationDate(): ?string { return $this->expirationDate; }
    public function getDaysToExpiration(): ?int { return $this->daysToExpiration; }
    public function getExpirationType(): ?string { return $this->expirationType; }
    public function isStandard(): ?bool { return $this->standard; }
}
