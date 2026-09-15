<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class ReferenceOption extends AbstractSchema {

    protected ?string $contractType = null;
    protected ?string $cusip = null;
    protected ?int $daysToExpiration = null;
    protected ?string $description = null;
    protected ?string $exchange = null;
    protected ?string $exchangeName = null;
    protected ?string $exerciseType = null;
    protected ?string $expirationDay = null;
    protected ?string $expirationMonth = null;
    protected ?string $expirationType = null;
    protected ?string $expirationYear = null;
    protected ?bool $isFsi = null;
    protected ?int $lastTradingDay = null;
    protected ?float $multiplier = null;
    protected ?string $settlementType = null;
    protected ?float $strikePrice = null;
    protected ?string $underlying = null;

    public static function fromArray( array $data ): static {
        $instance = new static();
        $instance->contractType     = $data['contractType'] ?? null;
        $instance->cusip            = $data['cusip'] ?? null;
        $instance->daysToExpiration = isset($data['daysToExpiration']) ? (int)$data['daysToExpiration'] : null;
        $instance->description      = $data['description'] ?? null;
        $instance->exchange         = $data['exchange'] ?? null;
        $instance->exchangeName     = $data['exchangeName'] ?? null;
        $instance->exerciseType     = $data['exerciseType'] ?? null;
        $instance->expirationDay    = $data['expirationDay'] ?? null;
        $instance->expirationMonth  = $data['expirationMonth'] ?? null;
        $instance->expirationType   = $data['expirationType'] ?? null;
        $instance->expirationYear   = $data['expirationYear'] ?? null;
        $instance->isFsi            = isset($data['isFsi']) ? (bool)$data['isFsi'] : null;
        $instance->lastTradingDay   = isset($data['lastTradingDay']) ? (int)$data['lastTradingDay'] : null;
        $instance->multiplier       = isset($data['multiplier']) ? (float)$data['multiplier'] : null;
        $instance->settlementType   = $data['settlementType'] ?? null;
        $instance->strikePrice      = isset($data['strikePrice']) ? (float)$data['strikePrice'] : null;
        $instance->underlying       = $data['underlying'] ?? null;
        return $instance;
    }

    public function getContractType(): ?string { return $this->contractType; }
    public function getCusip(): ?string { return $this->cusip; }
    public function getDaysToExpiration(): ?int { return $this->daysToExpiration; }
    public function getDescription(): ?string { return $this->description; }
    public function getExchange(): ?string { return $this->exchange; }
    public function getExchangeName(): ?string { return $this->exchangeName; }
    public function getExerciseType(): ?string { return $this->exerciseType; }
    public function getExpirationDay(): ?string { return $this->expirationDay; }
    public function getExpirationMonth(): ?string { return $this->expirationMonth; }
    public function getExpirationType(): ?string { return $this->expirationType; }
    public function getExpirationYear(): ?string { return $this->expirationYear; }
    public function isFsi(): ?bool { return $this->isFsi; }
    public function getLastTradingDay(): ?int { return $this->lastTradingDay; }
    public function getMultiplier(): ?float { return $this->multiplier; }
    public function getSettlementType(): ?string { return $this->settlementType; }
    public function getStrikePrice(): ?float { return $this->strikePrice; }
    public function getUnderlying(): ?string { return $this->underlying; }
}
