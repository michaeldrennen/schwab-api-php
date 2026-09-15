<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Transaction extends AbstractSchema {

    protected ?int $activityId = null;
    protected ?string $time = null;
    protected ?UserDetails $user = null;
    protected ?string $description = null;
    protected ?string $accountNumber = null;
    protected ?string $type = null;
    protected ?string $status = null;
    protected ?string $subAccount = null;
    protected ?string $tradeDate = null;
    protected ?string $settlementDate = null;
    protected ?int $positionId = null;
    protected ?int $orderId = null;
    protected ?float $netAmount = null;
    protected ?string $activityType = null;
    /**
     * @var TransferItem[]
     */
    protected array $transferItems = [];

    public function __construct(
        ?int $activityId = null,
        ?string $time = null,
        ?UserDetails $user = null,
        ?string $description = null,
        ?string $accountNumber = null,
        ?string $type = null,
        ?string $status = null,
        ?string $subAccount = null,
        ?string $tradeDate = null,
        ?string $settlementDate = null,
        ?int $positionId = null,
        ?int $orderId = null,
        ?float $netAmount = null,
        ?string $activityType = null,
        array $transferItems = []
    ) {
        $this->activityId     = $activityId;
        $this->time           = $time;
        $this->user           = $user;
        $this->description    = $description;
        $this->accountNumber  = $accountNumber;
        $this->type           = $type;
        $this->status         = $status;
        $this->subAccount     = $subAccount;
        $this->tradeDate      = $tradeDate;
        $this->settlementDate = $settlementDate;
        $this->positionId     = $positionId;
        $this->orderId        = $orderId;
        $this->netAmount      = $netAmount;
        $this->activityType   = $activityType;
        $this->transferItems  = $transferItems;
    }

    public static function fromArray( array $data ): static {
        $user = null;
        if ( isset($data['user']) && is_array($data['user']) ) {
            $user = UserDetails::fromArray($data['user']);
        }

        $items = [];
        if ( isset($data['transferItems']) && is_array($data['transferItems']) ) {
            $items = array_map(
                fn($t) => is_array($t) ? TransferItem::fromArray($t) : $t,
                $data['transferItems']
            );
        }

        return new static(
            activityId: isset($data['activityId']) ? (int)$data['activityId'] : null,
            time: $data['time'] ?? null,
            user: $user,
            description: $data['description'] ?? null,
            accountNumber: $data['accountNumber'] ?? null,
            type: $data['type'] ?? null,
            status: $data['status'] ?? null,
            subAccount: $data['subAccount'] ?? null,
            tradeDate: $data['tradeDate'] ?? null,
            settlementDate: $data['settlementDate'] ?? null,
            positionId: isset($data['positionId']) ? (int)$data['positionId'] : null,
            orderId: isset($data['orderId']) ? (int)$data['orderId'] : null,
            netAmount: isset($data['netAmount']) ? (float)$data['netAmount'] : null,
            activityType: $data['activityType'] ?? null,
            transferItems: $items
        );
    }

    public function getActivityId(): ?int { return $this->activityId; }
    public function getTime(): ?string { return $this->time; }
    public function getUser(): ?UserDetails { return $this->user; }
    public function getDescription(): ?string { return $this->description; }
    public function getAccountNumber(): ?string { return $this->accountNumber; }
    public function getType(): ?string { return $this->type; }
    public function getStatus(): ?string { return $this->status; }
    public function getSubAccount(): ?string { return $this->subAccount; }
    public function getTradeDate(): ?string { return $this->tradeDate; }
    public function getSettlementDate(): ?string { return $this->settlementDate; }
    public function getPositionId(): ?int { return $this->positionId; }
    public function getOrderId(): ?int { return $this->orderId; }
    public function getNetAmount(): ?float { return $this->netAmount; }
    public function getActivityType(): ?string { return $this->activityType; }
    public function getTransferItems(): array { return $this->transferItems; }
}
