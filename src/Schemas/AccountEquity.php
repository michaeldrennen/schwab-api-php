<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class AccountEquity extends AbstractAccountsInstrument {

    public function __construct(
        ?string $assetType = 'EQUITY',
        ?string $cusip = null,
        ?string $symbol = null,
        ?string $description = null,
        ?string $instrumentId = null,
        ?float $netChange = null
    ) {
        parent::__construct(
            assetType: $assetType ?? 'EQUITY',
            cusip: $cusip,
            symbol: $symbol,
            description: $description,
            instrumentId: $instrumentId,
            netChange: $netChange
        );
    }
}
