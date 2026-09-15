<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Account extends AbstractSchema {

    protected ?SecuritiesAccount $securitiesAccount = null;

    public function __construct( ?SecuritiesAccount $securitiesAccount = null ) {
        $this->securitiesAccount = $securitiesAccount;
    }

    public static function fromArray( array $data ): static {
        $securitiesAccount = null;
        if ( isset($data['securitiesAccount']) && is_array($data['securitiesAccount']) ) {
            $securitiesAccount = SecuritiesAccount::fromArray($data['securitiesAccount']);
        }

        return new static(
            securitiesAccount: $securitiesAccount
        );
    }

    public function getSecuritiesAccount(): ?SecuritiesAccount {
        return $this->securitiesAccount;
    }

    public function setSecuritiesAccount( ?SecuritiesAccount $securitiesAccount ): self {
        $this->securitiesAccount = $securitiesAccount;
        return $this;
    }
}
