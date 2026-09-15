<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class QuoteResponse extends AbstractSchema {

    /**
     * @var array<string, QuoteResponseObject>
     */
    protected array $quotes = [];

    public function __construct( array $quotes = [] ) {
        $this->quotes = $quotes;
    }

    public static function fromArray( array $data ): static {
        $quotes = [];
        foreach ( $data as $symbol => $item ) {
            if ( is_array($item) ) {
                $quotes[$symbol] = QuoteResponseObject::fromArray($item);
            }
        }
        return new static( quotes: $quotes );
    }

    /**
     * @return array<string, QuoteResponseObject>
     */
    public function getQuotes(): array {
        return $this->quotes;
    }

    public function getQuote( string $symbol ): ?QuoteResponseObject {
        return $this->quotes[$symbol] ?? $this->quotes[strtoupper($symbol)] ?? null;
    }
}
