<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class SecuritiesAccount extends AbstractSchema {

    protected ?string $type = null;
    protected ?string $accountNumber = null;
    protected ?int $roundTrips = null;
    protected ?bool $isDayTrader = null;
    protected ?bool $isClosingOnlyRestricted = null;
    protected ?bool $pfcbFlag = null;
    /**
     * @var Position[]
     */
    protected array $positions = [];
    protected null|CashInitialBalance|MarginInitialBalance $initialBalances = null;
    protected null|CashBalance|MarginBalance $currentBalances = null;
    protected null|CashBalance|MarginBalance $projectedBalances = null;

    public function __construct(
        ?string $type = null,
        ?string $accountNumber = null,
        ?int $roundTrips = null,
        ?bool $isDayTrader = null,
        ?bool $isClosingOnlyRestricted = null,
        ?bool $pfcbFlag = null,
        array $positions = [],
        null|CashInitialBalance|MarginInitialBalance $initialBalances = null,
        null|CashBalance|MarginBalance $currentBalances = null,
        null|CashBalance|MarginBalance $projectedBalances = null
    ) {
        $this->type                    = $type;
        $this->accountNumber           = $accountNumber;
        $this->roundTrips              = $roundTrips;
        $this->isDayTrader             = $isDayTrader;
        $this->isClosingOnlyRestricted = $isClosingOnlyRestricted;
        $this->pfcbFlag                = $pfcbFlag;
        $this->positions               = $positions;
        $this->initialBalances         = $initialBalances;
        $this->currentBalances         = $currentBalances;
        $this->projectedBalances       = $projectedBalances;
    }

    public static function fromArray( array $data ): static {
        $isMargin = isset($data['type']) && strtoupper($data['type']) === 'MARGIN';

        $positions = [];
        if ( isset($data['positions']) && is_array($data['positions']) ) {
            $positions = array_map(
                fn($p) => is_array($p) ? Position::fromArray($p) : $p,
                $data['positions']
            );
        }

        $initialBalances = null;
        if ( isset($data['initialBalances']) && is_array($data['initialBalances']) ) {
            $initialBalances = $isMargin
                ? MarginInitialBalance::fromArray($data['initialBalances'])
                : CashInitialBalance::fromArray($data['initialBalances']);
        }

        $currentBalances = null;
        if ( isset($data['currentBalances']) && is_array($data['currentBalances']) ) {
            $currentBalances = $isMargin
                ? MarginBalance::fromArray($data['currentBalances'])
                : CashBalance::fromArray($data['currentBalances']);
        }

        $projectedBalances = null;
        if ( isset($data['projectedBalances']) && is_array($data['projectedBalances']) ) {
            $projectedBalances = $isMargin
                ? MarginBalance::fromArray($data['projectedBalances'])
                : CashBalance::fromArray($data['projectedBalances']);
        }

        return new static(
            type: $data['type'] ?? null,
            accountNumber: $data['accountNumber'] ?? null,
            roundTrips: isset($data['roundTrips']) ? (int)$data['roundTrips'] : null,
            isDayTrader: isset($data['isDayTrader']) ? (bool)$data['isDayTrader'] : null,
            isClosingOnlyRestricted: isset($data['isClosingOnlyRestricted']) ? (bool)$data['isClosingOnlyRestricted'] : null,
            pfcbFlag: isset($data['pfcbFlag']) ? (bool)$data['pfcbFlag'] : null,
            positions: $positions,
            initialBalances: $initialBalances,
            currentBalances: $currentBalances,
            projectedBalances: $projectedBalances
        );
    }

    public function getType(): ?string { return $this->type; }
    public function getAccountNumber(): ?string { return $this->accountNumber; }
    public function getRoundTrips(): ?int { return $this->roundTrips; }
    public function isDayTrader(): ?bool { return $this->isDayTrader; }
    public function isClosingOnlyRestricted(): ?bool { return $this->isClosingOnlyRestricted; }
    public function isPfcbFlag(): ?bool { return $this->pfcbFlag; }
    public function getPositions(): array { return $this->positions; }
    public function getInitialBalances(): null|CashInitialBalance|MarginInitialBalance { return $this->initialBalances; }
    public function getCurrentBalances(): null|CashBalance|MarginBalance { return $this->currentBalances; }
    public function getProjectedBalances(): null|CashBalance|MarginBalance { return $this->projectedBalances; }
}
