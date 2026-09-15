<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Fundamental extends AbstractSchema {

    protected ?float $avg10DaysVolume = null;
    protected ?float $avg1YearVolume = null;
    protected ?string $declarationDate = null;
    protected ?float $divAmount = null;
    protected ?string $divExDate = null;
    protected ?string $divFreq = null;
    protected ?float $divPayAmount = null;
    protected ?string $divPayDate = null;
    protected ?float $divYield = null;
    protected ?float $eps = null;
    protected ?float $fundLeverageFactor = null;
    protected ?string $fundStrategy = null;
    protected ?string $nextDivExDate = null;
    protected ?string $nextDivPayDate = null;
    protected ?float $peRatio = null;

    public function __construct(
        ?float $avg10DaysVolume = null,
        ?float $avg1YearVolume = null,
        ?string $declarationDate = null,
        ?float $divAmount = null,
        ?string $divExDate = null,
        ?string $divFreq = null,
        ?float $divPayAmount = null,
        ?string $divPayDate = null,
        ?float $divYield = null,
        ?float $eps = null,
        ?float $fundLeverageFactor = null,
        ?string $fundStrategy = null,
        ?string $nextDivExDate = null,
        ?string $nextDivPayDate = null,
        ?float $peRatio = null
    ) {
        $this->avg10DaysVolume     = $avg10DaysVolume;
        $this->avg1YearVolume      = $avg1YearVolume;
        $this->declarationDate     = $declarationDate;
        $this->divAmount           = $divAmount;
        $this->divExDate           = $divExDate;
        $this->divFreq             = $divFreq;
        $this->divPayAmount        = $divPayAmount;
        $this->divPayDate          = $divPayDate;
        $this->divYield            = $divYield;
        $this->eps                 = $eps;
        $this->fundLeverageFactor  = $fundLeverageFactor;
        $this->fundStrategy        = $fundStrategy;
        $this->nextDivExDate       = $nextDivExDate;
        $this->nextDivPayDate      = $nextDivPayDate;
        $this->peRatio             = $peRatio;
    }

    public static function fromArray( array $data ): static {
        return new static(
            avg10DaysVolume: isset($data['avg10DaysVolume']) ? (float)$data['avg10DaysVolume'] : null,
            avg1YearVolume: isset($data['avg1YearVolume']) ? (float)$data['avg1YearVolume'] : null,
            declarationDate: $data['declarationDate'] ?? null,
            divAmount: isset($data['divAmount']) ? (float)$data['divAmount'] : null,
            divExDate: $data['divExDate'] ?? null,
            divFreq: $data['divFreq'] ?? null,
            divPayAmount: isset($data['divPayAmount']) ? (float)$data['divPayAmount'] : null,
            divPayDate: $data['divPayDate'] ?? null,
            divYield: isset($data['divYield']) ? (float)$data['divYield'] : null,
            eps: isset($data['eps']) ? (float)$data['eps'] : null,
            fundLeverageFactor: isset($data['fundLeverageFactor']) ? (float)$data['fundLeverageFactor'] : null,
            fundStrategy: $data['fundStrategy'] ?? null,
            nextDivExDate: $data['nextDivExDate'] ?? null,
            nextDivPayDate: $data['nextDivPayDate'] ?? null,
            peRatio: isset($data['peRatio']) ? (float)$data['peRatio'] : null
        );
    }

    public function getAvg10DaysVolume(): ?float { return $this->avg10DaysVolume; }
    public function getAvg1YearVolume(): ?float { return $this->avg1YearVolume; }
    public function getDeclarationDate(): ?string { return $this->declarationDate; }
    public function getDivAmount(): ?float { return $this->divAmount; }
    public function getDivExDate(): ?string { return $this->divExDate; }
    public function getDivFreq(): ?string { return $this->divFreq; }
    public function getDivPayAmount(): ?float { return $this->divPayAmount; }
    public function getDivPayDate(): ?string { return $this->divPayDate; }
    public function getDivYield(): ?float { return $this->divYield; }
    public function getEps(): ?float { return $this->eps; }
    public function getFundLeverageFactor(): ?float { return $this->fundLeverageFactor; }
    public function getFundStrategy(): ?string { return $this->fundStrategy; }
    public function getNextDivExDate(): ?string { return $this->nextDivExDate; }
    public function getNextDivPayDate(): ?string { return $this->nextDivPayDate; }
    public function getPeRatio(): ?float { return $this->peRatio; }
}
