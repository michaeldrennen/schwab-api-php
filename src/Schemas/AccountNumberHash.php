<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class AccountNumberHash extends AbstractSchema {

    protected ?string $accountNumber = null;
    protected ?string $hashValue = null;

    public function __construct( ?string $accountNumber = null, ?string $hashValue = null ) {
        $this->accountNumber = $accountNumber;
        $this->hashValue     = $hashValue;
    }

    public static function fromArray( array $data ): static {
        return new static(
            accountNumber: $data['accountNumber'] ?? null,
            hashValue: $data['hashValue'] ?? null
        );
    }

    public function getAccountNumber(): ?string {
        return $this->accountNumber;
    }

    public function setAccountNumber( ?string $accountNumber ): self {
        $this->accountNumber = $accountNumber;
        return $this;
    }

    public function getHashValue(): ?string {
        return $this->hashValue;
    }

    public function setHashValue( ?string $hashValue ): self {
        $this->hashValue = $hashValue;
        return $this;
    }
}
