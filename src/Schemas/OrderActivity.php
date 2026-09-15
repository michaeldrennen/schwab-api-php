<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class OrderActivity extends AbstractSchema {

    protected ?string $activityType = null;
    protected ?string $executionType = null;
    protected ?float $quantity = null;
    protected ?float $orderRemainingQuantity = null;
    /**
     * @var ExecutionLeg[]
     */
    protected array $executionLegs = [];

    public function __construct(
        ?string $activityType = null,
        ?string $executionType = null,
        ?float $quantity = null,
        ?float $orderRemainingQuantity = null,
        array $executionLegs = []
    ) {
        $this->activityType           = $activityType;
        $this->executionType          = $executionType;
        $this->quantity               = $quantity;
        $this->orderRemainingQuantity = $orderRemainingQuantity;
        $this->executionLegs          = $executionLegs;
    }

    public static function fromArray( array $data ): static {
        $executionLegs = [];
        if ( isset($data['executionLegs']) && is_array($data['executionLegs']) ) {
            $executionLegs = array_map(
                fn($l) => is_array($l) ? ExecutionLeg::fromArray($l) : $l,
                $data['executionLegs']
            );
        }

        return new static(
            activityType: $data['activityType'] ?? null,
            executionType: $data['executionType'] ?? null,
            quantity: isset($data['quantity']) ? (float)$data['quantity'] : null,
            orderRemainingQuantity: isset($data['orderRemainingQuantity']) ? (float)$data['orderRemainingQuantity'] : null,
            executionLegs: $executionLegs
        );
    }

    public function getActivityType(): ?string { return $this->activityType; }
    public function getExecutionType(): ?string { return $this->executionType; }
    public function getQuantity(): ?float { return $this->quantity; }
    public function getOrderRemainingQuantity(): ?float { return $this->orderRemainingQuantity; }
    public function getExecutionLegs(): array { return $this->executionLegs; }
}
