<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class UserDetails extends AbstractSchema {

    protected ?string $cdDomainId = null;
    protected ?string $login = null;
    protected ?string $type = null;
    protected ?int $userId = null;
    protected ?string $systemUserName = null;
    protected ?string $firstName = null;
    protected ?string $lastName = null;
    protected ?string $brokerRepCode = null;

    public function __construct(
        ?string $cdDomainId = null,
        ?string $login = null,
        ?string $type = null,
        ?int $userId = null,
        ?string $systemUserName = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?string $brokerRepCode = null
    ) {
        $this->cdDomainId     = $cdDomainId;
        $this->login          = $login;
        $this->type           = $type;
        $this->userId         = $userId;
        $this->systemUserName = $systemUserName;
        $this->firstName      = $firstName;
        $this->lastName       = $lastName;
        $this->brokerRepCode  = $brokerRepCode;
    }

    public static function fromArray( array $data ): static {
        return new static(
            cdDomainId: $data['cdDomainId'] ?? null,
            login: $data['login'] ?? null,
            type: $data['type'] ?? null,
            userId: isset($data['userId']) ? (int)$data['userId'] : null,
            systemUserName: $data['systemUserName'] ?? null,
            firstName: $data['firstName'] ?? null,
            lastName: $data['lastName'] ?? null,
            brokerRepCode: $data['brokerRepCode'] ?? null
        );
    }

    public function getCdDomainId(): ?string { return $this->cdDomainId; }
    public function getLogin(): ?string { return $this->login; }
    public function getType(): ?string { return $this->type; }
    public function getUserId(): ?int { return $this->userId; }
    public function getSystemUserName(): ?string { return $this->systemUserName; }
    public function getFirstName(): ?string { return $this->firstName; }
    public function getLastName(): ?string { return $this->lastName; }
    public function getBrokerRepCode(): ?string { return $this->brokerRepCode; }
}
