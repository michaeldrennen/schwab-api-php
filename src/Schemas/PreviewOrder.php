<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class PreviewOrder extends AbstractSchema {

    protected ?int $orderId = null;
    protected ?OrderValidationResult $validationResult = null;
    protected ?CommissionAndFee $commissionAndFee = null;
    protected ?Order $orderStrategy = null;
    protected ?OrderBalance $orderBalance = null;

    public function __construct(
        ?int $orderId = null,
        ?OrderValidationResult $validationResult = null,
        ?CommissionAndFee $commissionAndFee = null,
        ?Order $orderStrategy = null,
        ?OrderBalance $orderBalance = null
    ) {
        $this->orderId          = $orderId;
        $this->validationResult = $validationResult;
        $this->commissionAndFee = $commissionAndFee;
        $this->orderStrategy    = $orderStrategy;
        $this->orderBalance     = $orderBalance;
    }

    public static function fromArray( array $data ): static {
        $validationResult = null;
        if ( isset($data['validationResult']) && is_array($data['validationResult']) ) {
            $validationResult = OrderValidationResult::fromArray($data['validationResult']);
        }

        $commissionAndFee = null;
        if ( isset($data['commissionAndFee']) && is_array($data['commissionAndFee']) ) {
            $commissionAndFee = CommissionAndFee::fromArray($data['commissionAndFee']);
        }

        $orderStrategy = null;
        if ( isset($data['orderStrategy']) && is_array($data['orderStrategy']) ) {
            $orderStrategy = Order::fromArray($data['orderStrategy']);
        }

        $orderBalance = null;
        if ( isset($data['orderBalance']) && is_array($data['orderBalance']) ) {
            $orderBalance = OrderBalance::fromArray($data['orderBalance']);
        }

        return new static(
            orderId: isset($data['orderId']) ? (int)$data['orderId'] : null,
            validationResult: $validationResult,
            commissionAndFee: $commissionAndFee,
            orderStrategy: $orderStrategy,
            orderBalance: $orderBalance
        );
    }

    public function getOrderId(): ?int { return $this->orderId; }
    public function getValidationResult(): ?OrderValidationResult { return $this->validationResult; }
    public function getCommissionAndFee(): ?CommissionAndFee { return $this->commissionAndFee; }
    public function getOrderStrategy(): ?Order { return $this->orderStrategy; }
    public function getOrderBalance(): ?OrderBalance { return $this->orderBalance; }
}
