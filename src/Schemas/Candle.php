<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Candle extends AbstractSchema {

    protected ?float $open = null;
    protected ?float $high = null;
    protected ?float $low = null;
    protected ?float $close = null;
    protected ?float $volume = null;
    protected ?int $datetime = null;

    public function __construct(
        ?float $open = null,
        ?float $high = null,
        ?float $low = null,
        ?float $close = null,
        ?float $volume = null,
        ?int $datetime = null
    ) {
        $this->open     = $open;
        $this->high     = $high;
        $this->low      = $low;
        $this->close    = $close;
        $this->volume   = $volume;
        $this->datetime = $datetime;
    }

    public static function fromArray( array $data ): static {
        return new static(
            open: isset($data['open']) ? (float)$data['open'] : null,
            high: isset($data['high']) ? (float)$data['high'] : null,
            low: isset($data['low']) ? (float)$data['low'] : null,
            close: isset($data['close']) ? (float)$data['close'] : null,
            volume: isset($data['volume']) ? (float)$data['volume'] : null,
            datetime: isset($data['datetime']) ? (int)$data['datetime'] : null
        );
    }

    public function getOpen(): ?float { return $this->open; }
    public function getHigh(): ?float { return $this->high; }
    public function getLow(): ?float { return $this->low; }
    public function getClose(): ?float { return $this->close; }
    public function getVolume(): ?float { return $this->volume; }
    public function getDatetime(): ?int { return $this->datetime; }
}
