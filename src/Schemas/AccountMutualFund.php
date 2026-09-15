<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class AccountMutualFund extends AbstractAccountsInstrument {

    public function __construct(
        ?string $assetType = 'MUTUAL_FUND',
        ?string $cusip = null,
        ?string $symbol = null,
        ?string $description = null,
        ?string $instrumentId = null,
        ?float $netChange = null
    ) {
        parent::__construct(
            assetType: $assetType ?? 'MUTUAL_FUND',
            cusip: $cusip,
            symbol: $symbol,
            description: $description,
            instrumentId: $instrumentId,
            netChange: $netChange
        );
    }
}
