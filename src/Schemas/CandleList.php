<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class CandleList extends AbstractSchema {

    protected ?string $symbol = null;
    protected ?bool $empty = null;
    /**
     * @var Candle[]
     */
    protected array $candles = [];

    public function __construct( ?string $symbol = null, ?bool $empty = null, array $candles = [] ) {
        $this->symbol  = $symbol;
        $this->empty   = $empty;
        $this->candles = $candles;
    }

    public static function fromArray( array $data ): static {
        $candles = [];
        if ( isset($data['candles']) && is_array($data['candles']) ) {
            $candles = array_map(
                fn($c) => is_array($c) ? Candle::fromArray($c) : $c,
                $data['candles']
            );
        }

        return new static(
            symbol: $data['symbol'] ?? null,
            empty: isset($data['empty']) ? (bool)$data['empty'] : null,
            candles: $candles
        );
    }

    public function getSymbol(): ?string { return $this->symbol; }
    public function isEmpty(): ?bool { return $this->empty; }
    public function getCandles(): array { return $this->candles; }
}
