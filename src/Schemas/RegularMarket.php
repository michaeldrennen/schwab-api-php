<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class RegularMarket extends AbstractSchema {

    protected ?float $regularMarketLastPrice = null;
    protected ?float $regularMarketLastSize = null;
    protected ?float $regularMarketNetChange = null;
    protected ?float $regularMarketPercentChange = null;
    protected ?int $regularMarketTradeTime = null;

    public function __construct(
        ?float $regularMarketLastPrice = null,
        ?float $regularMarketLastSize = null,
        ?float $regularMarketNetChange = null,
        ?float $regularMarketPercentChange = null,
        ?int $regularMarketTradeTime = null
    ) {
        $this->regularMarketLastPrice     = $regularMarketLastPrice;
        $this->regularMarketLastSize      = $regularMarketLastSize;
        $this->regularMarketNetChange     = $regularMarketNetChange;
        $this->regularMarketPercentChange = $regularMarketPercentChange;
        $this->regularMarketTradeTime     = $regularMarketTradeTime;
    }

    public static function fromArray( array $data ): static {
        return new static(
            regularMarketLastPrice: isset($data['regularMarketLastPrice']) ? (float)$data['regularMarketLastPrice'] : null,
            regularMarketLastSize: isset($data['regularMarketLastSize']) ? (float)$data['regularMarketLastSize'] : null,
            regularMarketNetChange: isset($data['regularMarketNetChange']) ? (float)$data['regularMarketNetChange'] : null,
            regularMarketPercentChange: isset($data['regularMarketPercentChange']) ? (float)$data['regularMarketPercentChange'] : null,
            regularMarketTradeTime: isset($data['regularMarketTradeTime']) ? (int)$data['regularMarketTradeTime'] : null
        );
    }

    public function getRegularMarketLastPrice(): ?float { return $this->regularMarketLastPrice; }
    public function getRegularMarketLastSize(): ?float { return $this->regularMarketLastSize; }
    public function getRegularMarketNetChange(): ?float { return $this->regularMarketNetChange; }
    public function getRegularMarketPercentChange(): ?float { return $this->regularMarketPercentChange; }
    public function getRegularMarketTradeTime(): ?int { return $this->regularMarketTradeTime; }
}
