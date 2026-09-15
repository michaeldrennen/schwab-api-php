<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class CommissionAndFee extends AbstractSchema {

    protected ?Commission $commission = null;
    protected ?Fees $fee = null;
    protected ?Commission $trueCommission = null;

    public function __construct(
        ?Commission $commission = null,
        ?Fees $fee = null,
        ?Commission $trueCommission = null
    ) {
        $this->commission     = $commission;
        $this->fee            = $fee;
        $this->trueCommission = $trueCommission;
    }

    public static function fromArray( array $data ): static {
        $commission = isset($data['commission']) && is_array($data['commission'])
            ? Commission::fromArray($data['commission'])
            : null;

        $fee = isset($data['fee']) && is_array($data['fee'])
            ? Fees::fromArray($data['fee'])
            : null;

        $trueCommission = isset($data['trueCommission']) && is_array($data['trueCommission'])
            ? Commission::fromArray($data['trueCommission'])
            : null;

        return new static(
            commission: $commission,
            fee: $fee,
            trueCommission: $trueCommission
        );
    }

    public function getCommission(): ?Commission { return $this->commission; }
    public function getFee(): ?Fees { return $this->fee; }
    public function getTrueCommission(): ?Commission { return $this->trueCommission; }
}
