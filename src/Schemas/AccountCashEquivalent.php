<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class AccountCashEquivalent extends AbstractAccountsInstrument {

    public function __construct(
        ?string $assetType = 'CASH_EQUIVALENT',
        ?string $cusip = null,
        ?string $symbol = null,
        ?string $description = null,
        ?string $instrumentId = null,
        ?float $netChange = null
    ) {
        parent::__construct(
            assetType: $assetType ?? 'CASH_EQUIVALENT',
            cusip: $cusip,
            symbol: $symbol,
            description: $description,
            instrumentId: $instrumentId,
            netChange: $netChange
        );
    }
}
