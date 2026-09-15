<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class AccountFixedIncome extends AbstractAccountsInstrument {

    public function __construct(
        ?string $assetType = 'FIXED_INCOME',
        ?string $cusip = null,
        ?string $symbol = null,
        ?string $description = null,
        ?string $instrumentId = null,
        ?float $netChange = null
    ) {
        parent::__construct(
            assetType: $assetType ?? 'FIXED_INCOME',
            cusip: $cusip,
            symbol: $symbol,
            description: $description,
            instrumentId: $instrumentId,
            netChange: $netChange
        );
    }
}
