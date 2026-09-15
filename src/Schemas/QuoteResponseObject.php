<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class QuoteResponseObject extends AbstractSchema {

    protected ?string $assetMainType = null;
    protected ?string $assetSubType = null;
    protected ?int $ssid = null;
    protected ?string $symbol = null;
    protected ?bool $realtime = null;
    protected ?string $quoteType = null;
    protected ?ExtendedMarket $extended = null;
    protected ?Fundamental $fundamental = null;
    protected null|QuoteEquity|QuoteOption|array $quote = null;
    protected null|ReferenceEquity|ReferenceOption|array $reference = null;
    protected ?RegularMarket $regular = null;

    public function __construct(
        ?string $assetMainType = null,
        ?string $assetSubType = null,
        ?int $ssid = null,
        ?string $symbol = null,
        ?bool $realtime = null,
        ?string $quoteType = null,
        ?ExtendedMarket $extended = null,
        ?Fundamental $fundamental = null,
        null|QuoteEquity|QuoteOption|array $quote = null,
        null|ReferenceEquity|ReferenceOption|array $reference = null,
        ?RegularMarket $regular = null
    ) {
        $this->assetMainType = $assetMainType;
        $this->assetSubType  = $assetSubType;
        $this->ssid          = $ssid;
        $this->symbol        = $symbol;
        $this->realtime      = $realtime;
        $this->quoteType     = $quoteType;
        $this->extended      = $extended;
        $this->fundamental   = $fundamental;
        $this->quote         = $quote;
        $this->reference     = $reference;
        $this->regular       = $regular;
    }

    public static function fromArray( array $data ): static {
        $assetMainType = $data['assetMainType'] ?? null;

        $extended = isset($data['extended']) && is_array($data['extended'])
            ? ExtendedMarket::fromArray($data['extended'])
            : null;

        $fundamental = isset($data['fundamental']) && is_array($data['fundamental'])
            ? Fundamental::fromArray($data['fundamental'])
            : null;

        $regular = isset($data['regular']) && is_array($data['regular'])
            ? RegularMarket::fromArray($data['regular'])
            : null;

        $quote = null;
        if ( isset($data['quote']) && is_array($data['quote']) ) {
            if ( $assetMainType === 'OPTION' ) {
                $quote = QuoteOption::fromArray($data['quote']);
            } elseif ( $assetMainType === 'EQUITY' ) {
                $quote = QuoteEquity::fromArray($data['quote']);
            } else {
                $quote = QuoteEquity::fromArray($data['quote']);
            }
        }

        $reference = null;
        if ( isset($data['reference']) && is_array($data['reference']) ) {
            if ( $assetMainType === 'OPTION' ) {
                $reference = ReferenceOption::fromArray($data['reference']);
            } elseif ( $assetMainType === 'EQUITY' ) {
                $reference = ReferenceEquity::fromArray($data['reference']);
            } else {
                $reference = ReferenceEquity::fromArray($data['reference']);
            }
        }

        return new static(
            assetMainType: $assetMainType,
            assetSubType: $data['assetSubType'] ?? null,
            ssid: isset($data['ssid']) ? (int)$data['ssid'] : null,
            symbol: $data['symbol'] ?? null,
            realtime: isset($data['realtime']) ? (bool)$data['realtime'] : null,
            quoteType: $data['quoteType'] ?? null,
            extended: $extended,
            fundamental: $fundamental,
            quote: $quote,
            reference: $reference,
            regular: $regular
        );
    }

    public function getAssetMainType(): ?string { return $this->assetMainType; }
    public function getAssetSubType(): ?string { return $this->assetSubType; }
    public function getSsid(): ?int { return $this->ssid; }
    public function getSymbol(): ?string { return $this->symbol; }
    public function isRealtime(): ?bool { return $this->realtime; }
    public function getQuoteType(): ?string { return $this->quoteType; }
    public function getExtended(): ?ExtendedMarket { return $this->extended; }
    public function getFundamental(): ?Fundamental { return $this->fundamental; }
    public function getQuote(): null|QuoteEquity|QuoteOption|array { return $this->quote; }
    public function getReference(): null|ReferenceEquity|ReferenceOption|array { return $this->reference; }
    public function getRegular(): ?RegularMarket { return $this->regular; }
}
