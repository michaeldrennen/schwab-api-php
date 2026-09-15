<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class OptionChain extends AbstractSchema {

    protected ?string $symbol = null;
    protected ?string $status = null;
    protected ?Underlying $underlying = null;
    protected ?string $strategy = null;
    protected ?float $interval = null;
    protected ?bool $isDelayed = null;
    protected ?bool $isIndex = null;
    protected ?float $interestRate = null;
    protected ?float $underlyingPrice = null;
    protected ?float $volatility = null;
    protected ?float $daysToExpiration = null;
    protected ?int $numberOfContracts = null;
    protected array $callExpDateMap = [];
    protected array $putExpDateMap = [];

    public static function fromArray( array $data ): static {
        $instance = new static();
        $instance->symbol           = $data['symbol'] ?? null;
        $instance->status           = $data['status'] ?? null;
        $instance->underlying       = isset($data['underlying']) && is_array($data['underlying'])
            ? Underlying::fromArray($data['underlying'])
            : null;
        $instance->strategy         = $data['strategy'] ?? null;
        $instance->interval         = isset($data['interval']) ? (float)$data['interval'] : null;
        $instance->isDelayed        = isset($data['isDelayed']) ? (bool)$data['isDelayed'] : null;
        $instance->isIndex          = isset($data['isIndex']) ? (bool)$data['isIndex'] : null;
        $instance->interestRate     = isset($data['interestRate']) ? (float)$data['interestRate'] : null;
        $instance->underlyingPrice  = isset($data['underlyingPrice']) ? (float)$data['underlyingPrice'] : null;
        $instance->volatility       = isset($data['volatility']) ? (float)$data['volatility'] : null;
        $instance->daysToExpiration = isset($data['daysToExpiration']) ? (float)$data['daysToExpiration'] : null;
        $instance->numberOfContracts = isset($data['numberOfContracts']) ? (int)$data['numberOfContracts'] : null;

        // Parse callExpDateMap: date => strike => OptionContract[]
        if ( isset($data['callExpDateMap']) && is_array($data['callExpDateMap']) ) {
            foreach ( $data['callExpDateMap'] as $expDate => $strikes ) {
                if ( is_array($strikes) ) {
                    foreach ( $strikes as $strike => $contracts ) {
                        if ( is_array($contracts) ) {
                            foreach ( $contracts as $contract ) {
                                $instance->callExpDateMap[$expDate][$strike][] = is_array($contract)
                                    ? OptionContract::fromArray($contract)
                                    : $contract;
                            }
                        }
                    }
                }
            }
        }

        // Parse putExpDateMap: date => strike => OptionContract[]
        if ( isset($data['putExpDateMap']) && is_array($data['putExpDateMap']) ) {
            foreach ( $data['putExpDateMap'] as $expDate => $strikes ) {
                if ( is_array($strikes) ) {
                    foreach ( $strikes as $strike => $contracts ) {
                        if ( is_array($contracts) ) {
                            foreach ( $contracts as $contract ) {
                                $instance->putExpDateMap[$expDate][$strike][] = is_array($contract)
                                    ? OptionContract::fromArray($contract)
                                    : $contract;
                            }
                        }
                    }
                }
            }
        }

        return $instance;
    }

    public function getSymbol(): ?string { return $this->symbol; }
    public function getStatus(): ?string { return $this->status; }
    public function getUnderlying(): ?Underlying { return $this->underlying; }
    public function getStrategy(): ?string { return $this->strategy; }
    public function getInterval(): ?float { return $this->interval; }
    public function isDelayed(): ?bool { return $this->isDelayed; }
    public function isIndex(): ?bool { return $this->isIndex; }
    public function getInterestRate(): ?float { return $this->interestRate; }
    public function getUnderlyingPrice(): ?float { return $this->underlyingPrice; }
    public function getVolatility(): ?float { return $this->volatility; }
    public function getDaysToExpiration(): ?float { return $this->daysToExpiration; }
    public function getNumberOfContracts(): ?int { return $this->numberOfContracts; }
    public function getCallExpDateMap(): array { return $this->callExpDateMap; }
    public function getPutExpDateMap(): array { return $this->putExpDateMap; }
}
