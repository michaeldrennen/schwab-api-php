<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class AccountOption extends AbstractAccountsInstrument {

    public function __construct(
        ?string $assetType = 'OPTION',
        ?string $cusip = null,
        ?string $symbol = null,
        ?string $description = null,
        ?string $instrumentId = null,
        ?float $netChange = null
    ) {
        parent::__construct(
            assetType: $assetType ?? 'OPTION',
            cusip: $cusip,
            symbol: $symbol,
            description: $description,
            instrumentId: $instrumentId,
            netChange: $netChange
        );
    }
}
