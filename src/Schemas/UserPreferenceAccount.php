<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class UserPreferenceAccount extends AbstractSchema {

    protected ?string $accountNumber = null;
    protected ?bool $primaryAccount = null;
    protected ?string $type = null;
    protected ?string $nickName = null;
    protected ?string $accountColor = null;
    protected ?string $displayAcctId = null;
    protected ?bool $autoPositionEffect = null;

    public function __construct(
        ?string $accountNumber = null,
        ?bool $primaryAccount = null,
        ?string $type = null,
        ?string $nickName = null,
        ?string $accountColor = null,
        ?string $displayAcctId = null,
        ?bool $autoPositionEffect = null
    ) {
        $this->accountNumber      = $accountNumber;
        $this->primaryAccount     = $primaryAccount;
        $this->type               = $type;
        $this->nickName           = $nickName;
        $this->accountColor       = $accountColor;
        $this->displayAcctId      = $displayAcctId;
        $this->autoPositionEffect = $autoPositionEffect;
    }

    public static function fromArray( array $data ): static {
        return new static(
            accountNumber: $data['accountNumber'] ?? null,
            primaryAccount: isset($data['primaryAccount']) ? (bool)$data['primaryAccount'] : null,
            type: $data['type'] ?? null,
            nickName: $data['nickName'] ?? null,
            accountColor: $data['accountColor'] ?? null,
            displayAcctId: $data['displayAcctId'] ?? null,
            autoPositionEffect: isset($data['autoPositionEffect']) ? (bool)$data['autoPositionEffect'] : null
        );
    }

    public function getAccountNumber(): ?string { return $this->accountNumber; }
    public function isPrimaryAccount(): ?bool { return $this->primaryAccount; }
    public function getType(): ?string { return $this->type; }
    public function getNickName(): ?string { return $this->nickName; }
    public function getAccountColor(): ?string { return $this->accountColor; }
    public function getDisplayAcctId(): ?string { return $this->displayAcctId; }
    public function isAutoPositionEffect(): ?bool { return $this->autoPositionEffect; }
}
