<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Hours extends AbstractSchema {

    protected ?string $category = null;
    protected ?string $date = null;
    protected ?string $description = null;
    protected ?string $exchange = null;
    protected ?bool $isOpen = null;
    protected ?string $marketType = null;
    protected ?string $product = null;
    protected ?string $productName = null;
    /**
     * @var array<string, Interval[]>
     */
    protected array $sessionHours = [];

    public function __construct(
        ?string $category = null,
        ?string $date = null,
        ?string $description = null,
        ?string $exchange = null,
        ?bool $isOpen = null,
        ?string $marketType = null,
        ?string $product = null,
        ?string $productName = null,
        array $sessionHours = []
    ) {
        $this->category     = $category;
        $this->date         = $date;
        $this->description  = $description;
        $this->exchange     = $exchange;
        $this->isOpen       = $isOpen;
        $this->marketType   = $marketType;
        $this->product      = $product;
        $this->productName  = $productName;
        $this->sessionHours = $sessionHours;
    }

    public static function fromArray( array $data ): static {
        $sessionHours = [];
        if ( isset($data['sessionHours']) && is_array($data['sessionHours']) ) {
            foreach ( $data['sessionHours'] as $sessionName => $intervals ) {
                if ( is_array($intervals) ) {
                    $sessionHours[$sessionName] = array_map(
                        fn($i) => is_array($i) ? Interval::fromArray($i) : $i,
                        $intervals
                    );
                }
            }
        }

        return new static(
            category: $data['category'] ?? null,
            date: $data['date'] ?? null,
            description: $data['description'] ?? null,
            exchange: $data['exchange'] ?? null,
            isOpen: isset($data['isOpen']) ? (bool)$data['isOpen'] : null,
            marketType: $data['marketType'] ?? null,
            product: $data['product'] ?? null,
            productName: $data['productName'] ?? null,
            sessionHours: $sessionHours
        );
    }

    public static function fromCollection( array $items ): array {
        $result = [];
        foreach ( $items as $key => $value ) {
            if ( !is_array($value) ) {
                continue;
            }
            if ( isset($value['date']) || isset($value['marketType']) || isset($value['product']) || isset($value['sessionHours']) || isset($value['isOpen']) ) {
                $result[$key] = static::fromArray($value);
            } else {
                foreach ( $value as $subKey => $subValue ) {
                    if ( is_array($subValue) ) {
                        $result[$key][$subKey] = static::fromArray($subValue);
                    }
                }
            }
        }
        return $result;
    }

    public function getCategory(): ?string { return $this->category; }
    public function getDate(): ?string { return $this->date; }
    public function getDescription(): ?string { return $this->description; }
    public function getExchange(): ?string { return $this->exchange; }
    public function isOpen(): ?bool { return $this->isOpen; }
    public function getMarketType(): ?string { return $this->marketType; }
    public function getProduct(): ?string { return $this->product; }
    public function getProductName(): ?string { return $this->productName; }
    public function getSessionHours(): array { return $this->sessionHours; }
}
