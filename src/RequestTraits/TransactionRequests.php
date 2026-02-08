<?php

namespace MichaelDrennen\SchwabAPI\RequestTraits;

use Carbon\Carbon;

trait TransactionRequests {

    use RequestTrait;


    /**
     * Get all transactions for a specific account
     * Returns transaction information for a specific account based on the query parameters
     *
     * @param string              $hashValueOfAccountNumber The encrypted account hash value
     * @param \Carbon\Carbon      $startDate                Start date for transaction search (required)
     * @param \Carbon\Carbon      $endDate                  End date for transaction search (required)
     * @param string|null         $symbol                   Filter by symbol (optional)
     * @param string|null         $types                    Transaction types to filter by (optional)
     *
     * @return array List of transactions
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function transactions( string $hashValueOfAccountNumber,
                                  Carbon $startDate,
                                  Carbon $endDate,
                                  string $symbol = null,
                                  string $types = null ): array {
        $suffix = '/trader/v1/accounts/' . $hashValueOfAccountNumber . '/transactions';

        $queryParameters = [
            'startDate' => $startDate->toIso8601String(),
            'endDate'   => $endDate->toIso8601String(),
        ];

        if ( $symbol ) {
            $queryParameters[ 'symbol' ] = strtoupper( $symbol );
        }

        if ( $types ) {
            $queryParameters[ 'types' ] = $types;
        }

        $suffix .= '?' . http_build_query( $queryParameters );

        $response = $this->_request( $suffix );
        return $this->json( $response );
    }


    /**
     * Get a specific transaction for a specific account
     *
     * @param string $hashValueOfAccountNumber The encrypted account hash value
     * @param string $transactionId            The transaction ID
     *
     * @return array Transaction details
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function transaction( string $hashValueOfAccountNumber,
                                 string $transactionId ): array {
        $suffix = '/trader/v1/accounts/' . $hashValueOfAccountNumber . '/transactions/' . $transactionId;

        $response = $this->_request( $suffix );
        return $this->json( $response );
    }
}
