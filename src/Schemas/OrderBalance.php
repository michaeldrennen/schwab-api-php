<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class OrderBalance extends AbstractSchema {

    protected ?float $orderValue = null;
    protected ?float $projectedAvailableFund = null;
    protected ?float $projectedBuyingPower = null;
    protected ?float $projectedCommission = null;

    public function __construct(
        ?float $orderValue = null,
        ?float $projectedAvailableFund = null,
        ?float $projectedBuyingPower = null,
        ?float $projectedCommission = null
    ) {
        $this->orderValue             = $orderValue;
        $this->projectedAvailableFund = $projectedAvailableFund;
        $this->projectedBuyingPower   = $projectedBuyingPower;
        $this->projectedCommission    = $projectedCommission;
    }

    public static function fromArray( array $data ): static {
        return new static(
            orderValue: isset($data['orderValue']) ? (float)$data['orderValue'] : null,
            projectedAvailableFund: isset($data['projectedAvailableFund']) ? (float)$data['projectedAvailableFund'] : null,
            projectedBuyingPower: isset($data['projectedBuyingPower']) ? (float)$data['projectedBuyingPower'] : null,
            projectedCommission: isset($data['projectedCommission']) ? (float)$data['projectedCommission'] : null
        );
    }

    public function getOrderValue(): ?float { return $this->orderValue; }
    public function getProjectedAvailableFund(): ?float { return $this->projectedAvailableFund; }
    public function getProjectedBuyingPower(): ?float { return $this->projectedBuyingPower; }
    public function getProjectedCommission(): ?float { return $this->projectedCommission; }
}
