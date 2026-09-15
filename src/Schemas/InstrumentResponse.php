<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class InstrumentResponse extends AbstractSchema {

    /**
     * @var Instrument[]|Bond[]|FundamentalInst[]
     */
    protected array $instruments = [];

    public function __construct( array $instruments = [] ) {
        $this->instruments = $instruments;
    }

    public static function fromArray( array $data ): static {
        $instruments = [];
        if ( isset($data['instruments']) && is_array($data['instruments']) ) {
            $instruments = array_map(
                function($inst) {
                    if ( !is_array($inst) ) {
                        return $inst;
                    }
                    if ( isset($inst['bondFactor']) || isset($inst['bondMultiplier']) ) {
                        return Bond::fromArray($inst);
                    }
                    if ( isset($inst['fundamental']) ) {
                        return FundamentalInst::fromArray($inst);
                    }
                    return Instrument::fromArray($inst);
                },
                $data['instruments']
            );
        }

        return new static( instruments: $instruments );
    }

    public function getInstruments(): array {
        return $this->instruments;
    }
}
