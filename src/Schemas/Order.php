<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Order extends AbstractSchema {

    protected ?string $session = null;
    protected ?string $duration = null;
    protected ?string $orderType = null;
    protected ?string $cancelTime = null;
    protected ?string $complexOrderStrategyType = null;
    protected ?float $quantity = null;
    protected ?float $filledQuantity = null;
    protected ?float $remainingQuantity = null;
    protected ?string $destinationLinkName = null;
    protected ?string $releaseTime = null;
    protected ?float $stopPrice = null;
    protected ?string $stopPriceLinkBasis = null;
    protected ?string $stopPriceLinkType = null;
    protected ?float $stopPriceOffset = null;
    protected ?string $stopType = null;
    protected ?string $priceLinkBasis = null;
    protected ?string $priceLinkType = null;
    protected ?float $price = null;
    protected ?string $taxLotMethod = null;
    /**
     * @var OrderLegCollection[]
     */
    protected array $orderLegCollection = [];
    protected ?float $activationPrice = null;
    protected ?string $specialInstruction = null;
    protected ?string $orderStrategyType = null;
    protected ?int $orderId = null;
    protected ?bool $cancelable = null;
    protected ?bool $editable = null;
    protected ?string $status = null;
    protected ?string $enteredTime = null;
    protected ?string $closeTime = null;
    protected ?string $tag = null;
    protected ?int $accountNumber = null;
    /**
     * @var OrderActivity[]
     */
    protected array $orderActivityCollection = [];
    /**
     * @var Order[]
     */
    protected array $replacingOrderCollection = [];
    /**
     * @var Order[]
     */
    protected array $childOrderStrategies = [];
    protected ?string $statusDescription = null;

    public function __construct(
        ?string $session = null,
        ?string $duration = null,
        ?string $orderType = null,
        ?string $cancelTime = null,
        ?string $complexOrderStrategyType = null,
        ?float $quantity = null,
        ?float $filledQuantity = null,
        ?float $remainingQuantity = null,
        ?string $destinationLinkName = null,
        ?string $releaseTime = null,
        ?float $stopPrice = null,
        ?string $stopPriceLinkBasis = null,
        ?string $stopPriceLinkType = null,
        ?float $stopPriceOffset = null,
        ?string $stopType = null,
        ?string $priceLinkBasis = null,
        ?string $priceLinkType = null,
        ?float $price = null,
        ?string $taxLotMethod = null,
        array $orderLegCollection = [],
        ?float $activationPrice = null,
        ?string $specialInstruction = null,
        ?string $orderStrategyType = null,
        ?int $orderId = null,
        ?bool $cancelable = null,
        ?bool $editable = null,
        ?string $status = null,
        ?string $enteredTime = null,
        ?string $closeTime = null,
        ?string $tag = null,
        ?int $accountNumber = null,
        array $orderActivityCollection = [],
        array $replacingOrderCollection = [],
        array $childOrderStrategies = [],
        ?string $statusDescription = null
    ) {
        $this->session                  = $session;
        $this->duration                 = $duration;
        $this->orderType                = $orderType;
        $this->cancelTime               = $cancelTime;
        $this->complexOrderStrategyType = $complexOrderStrategyType;
        $this->quantity                 = $quantity;
        $this->filledQuantity           = $filledQuantity;
        $this->remainingQuantity        = $remainingQuantity;
        $this->destinationLinkName      = $destinationLinkName;
        $this->releaseTime              = $releaseTime;
        $this->stopPrice                = $stopPrice;
        $this->stopPriceLinkBasis       = $stopPriceLinkBasis;
        $this->stopPriceLinkType        = $stopPriceLinkType;
        $this->stopPriceOffset          = $stopPriceOffset;
        $this->stopType                 = $stopType;
        $this->priceLinkBasis           = $priceLinkBasis;
        $this->priceLinkType            = $priceLinkType;
        $this->price                    = $price;
        $this->taxLotMethod             = $taxLotMethod;
        $this->orderLegCollection       = $orderLegCollection;
        $this->activationPrice          = $activationPrice;
        $this->specialInstruction       = $specialInstruction;
        $this->orderStrategyType        = $orderStrategyType;
        $this->orderId                  = $orderId;
        $this->cancelable               = $cancelable;
        $this->editable                 = $editable;
        $this->status                   = $status;
        $this->enteredTime              = $enteredTime;
        $this->closeTime                = $closeTime;
        $this->tag                      = $tag;
        $this->accountNumber            = $accountNumber;
        $this->orderActivityCollection  = $orderActivityCollection;
        $this->replacingOrderCollection = $replacingOrderCollection;
        $this->childOrderStrategies     = $childOrderStrategies;
        $this->statusDescription        = $statusDescription;
    }

    public static function fromArray( array $data ): static {
        $legs = [];
        if ( isset($data['orderLegCollection']) && is_array($data['orderLegCollection']) ) {
            $legs = array_map(
                fn($l) => is_array($l) ? OrderLegCollection::fromArray($l) : $l,
                $data['orderLegCollection']
            );
        }

        $activities = [];
        if ( isset($data['orderActivityCollection']) && is_array($data['orderActivityCollection']) ) {
            $activities = array_map(
                fn($a) => is_array($a) ? OrderActivity::fromArray($a) : $a,
                $data['orderActivityCollection']
            );
        }

        $replacing = [];
        if ( isset($data['replacingOrderCollection']) && is_array($data['replacingOrderCollection']) ) {
            $replacing = array_map(
                fn($r) => is_array($r) ? static::fromArray($r) : $r,
                $data['replacingOrderCollection']
            );
        }

        $children = [];
        if ( isset($data['childOrderStrategies']) && is_array($data['childOrderStrategies']) ) {
            $children = array_map(
                fn($c) => is_array($c) ? static::fromArray($c) : $c,
                $data['childOrderStrategies']
            );
        }

        return new static(
            session: $data['session'] ?? null,
            duration: $data['duration'] ?? null,
            orderType: $data['orderType'] ?? null,
            cancelTime: $data['cancelTime'] ?? null,
            complexOrderStrategyType: $data['complexOrderStrategyType'] ?? null,
            quantity: isset($data['quantity']) ? (float)$data['quantity'] : null,
            filledQuantity: isset($data['filledQuantity']) ? (float)$data['filledQuantity'] : null,
            remainingQuantity: isset($data['remainingQuantity']) ? (float)$data['remainingQuantity'] : null,
            destinationLinkName: $data['destinationLinkName'] ?? null,
            releaseTime: $data['releaseTime'] ?? null,
            stopPrice: isset($data['stopPrice']) ? (float)$data['stopPrice'] : null,
            stopPriceLinkBasis: $data['stopPriceLinkBasis'] ?? null,
            stopPriceLinkType: $data['stopPriceLinkType'] ?? null,
            stopPriceOffset: isset($data['stopPriceOffset']) ? (float)$data['stopPriceOffset'] : null,
            stopType: $data['stopType'] ?? null,
            priceLinkBasis: $data['priceLinkBasis'] ?? null,
            priceLinkType: $data['priceLinkType'] ?? null,
            price: isset($data['price']) ? (float)$data['price'] : null,
            taxLotMethod: $data['taxLotMethod'] ?? null,
            orderLegCollection: $legs,
            activationPrice: isset($data['activationPrice']) ? (float)$data['activationPrice'] : null,
            specialInstruction: $data['specialInstruction'] ?? null,
            orderStrategyType: $data['orderStrategyType'] ?? null,
            orderId: isset($data['orderId']) ? (int)$data['orderId'] : null,
            cancelable: isset($data['cancelable']) ? (bool)$data['cancelable'] : null,
            editable: isset($data['editable']) ? (bool)$data['editable'] : null,
            status: $data['status'] ?? null,
            enteredTime: $data['enteredTime'] ?? null,
            closeTime: $data['closeTime'] ?? null,
            tag: $data['tag'] ?? null,
            accountNumber: isset($data['accountNumber']) ? (int)$data['accountNumber'] : null,
            orderActivityCollection: $activities,
            replacingOrderCollection: $replacing,
            childOrderStrategies: $children,
            statusDescription: $data['statusDescription'] ?? null
        );
    }

    public function getSession(): ?string { return $this->session; }
    public function getDuration(): ?string { return $this->duration; }
    public function getOrderType(): ?string { return $this->orderType; }
    public function getCancelTime(): ?string { return $this->cancelTime; }
    public function getComplexOrderStrategyType(): ?string { return $this->complexOrderStrategyType; }
    public function getQuantity(): ?float { return $this->quantity; }
    public function getFilledQuantity(): ?float { return $this->filledQuantity; }
    public function getRemainingQuantity(): ?float { return $this->remainingQuantity; }
    public function getDestinationLinkName(): ?string { return $this->destinationLinkName; }
    public function getReleaseTime(): ?string { return $this->releaseTime; }
    public function getStopPrice(): ?float { return $this->stopPrice; }
    public function getStopPriceLinkBasis(): ?string { return $this->stopPriceLinkBasis; }
    public function getStopPriceLinkType(): ?string { return $this->stopPriceLinkType; }
    public function getStopPriceOffset(): ?float { return $this->stopPriceOffset; }
    public function getStopType(): ?string { return $this->stopType; }
    public function getPriceLinkBasis(): ?string { return $this->priceLinkBasis; }
    public function getPriceLinkType(): ?string { return $this->priceLinkType; }
    public function getPrice(): ?float { return $this->price; }
    public function getTaxLotMethod(): ?string { return $this->taxLotMethod; }
    public function getOrderLegCollection(): array { return $this->orderLegCollection; }
    public function getActivationPrice(): ?float { return $this->activationPrice; }
    public function getSpecialInstruction(): ?string { return $this->specialInstruction; }
    public function getOrderStrategyType(): ?string { return $this->orderStrategyType; }
    public function getOrderId(): ?int { return $this->orderId; }
    public function isCancelable(): ?bool { return $this->cancelable; }
    public function isEditable(): ?bool { return $this->editable; }
    public function getStatus(): ?string { return $this->status; }
    public function getEnteredTime(): ?string { return $this->enteredTime; }
    public function getCloseTime(): ?string { return $this->closeTime; }
    public function getTag(): ?string { return $this->tag; }
    public function getAccountNumber(): ?int { return $this->accountNumber; }
    public function getOrderActivityCollection(): array { return $this->orderActivityCollection; }
    public function getReplacingOrderCollection(): array { return $this->replacingOrderCollection; }
    public function getChildOrderStrategies(): array { return $this->childOrderStrategies; }
    public function getStatusDescription(): ?string { return $this->statusDescription; }
}
