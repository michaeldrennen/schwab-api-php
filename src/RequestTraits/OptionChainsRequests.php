<?php

namespace MichaelDrennen\SchwabAPI\RequestTraits;

use Carbon\Carbon;


trait OptionChainsRequests {

    use RequestTrait;

    const CONTRACT_TYPES = [ 'CALL', 'PUT', 'ALL' ];

    const STRATEGIES = [
        'SINGLE',
        'ANALYTICAL',
        'COVERED',
        'VERTICAL',
        'CALENDAR',
        'STRANGLE',
        'STRADDLE',
        'BUTTERFLY',
        'CONDOR',
        'DIAGONAL',
        'COLLAR',
        'ROLL',
    ];

    const RANGES = [ 'ITM', 'NTM', 'OTM' ];

    const MONTHS = [ 'ALL', 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC' ];

    const ENTITLEMENTS = [ 'NP', 'PN', 'PP' ];


    /**
     * Get option chain for an optionable symbol.
     *
     * @param string              $symbol
     * @param string              $contractType
     * @param int|NULL            $strikeCount
     * @param bool|NULL           $includeUnderlyingQuote
     * @param string              $strategy
     * @param float|NULL          $interval
     * @param float|NULL          $strike
     * @param string|NULL         $range
     * @param \Carbon\Carbon|NULL $fromDate
     * @param \Carbon\Carbon|NULL $toDate
     * @param float|NULL          $volatility
     * @param float|NULL          $underlyingPrice
     * @param float|NULL          $interestRate
     * @param int|NULL            $daysToExpiration
     * @param string|NULL         $expMonth
     * @param string|NULL         $optionType
     * @param string|NULL         $entitlement
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function chains( string  $symbol,
                            string  $contractType = 'ALL',
                            ?int    $strikeCount = NULL,
                            ?bool   $includeUnderlyingQuote = NULL,
                            string  $strategy = 'SINGLE',
                            ?float  $interval = NULL,
                            ?float  $strike = NULL,
                            ?string $range = NULL,
                            ?Carbon $fromDate = NULL,
                            ?Carbon $toDate = NULL,
                            ?float  $volatility = NULL,
                            ?float  $underlyingPrice = NULL,
                            ?float  $interestRate = NULL,
                            ?int    $daysToExpiration = NULL,
                            ?string $expMonth = NULL,
                            ?string $optionType = NULL,
                            ?string $entitlement = NULL ): array {
        $suffix                      = '/marketdata/v1/chains';
        $queryParameters             = [];
        $queryParameters[ 'symbol' ] = $symbol;

        if ( $contractType ):
            $contractType = strtoupper( $contractType );
            if ( !in_array( $contractType, self::CONTRACT_TYPES ) ):
                throw new \Exception( "Invalid contract type '{$contractType}'." );
            endif;
            $queryParameters[ 'contractType' ] = $contractType;
        endif;

        if ( $strikeCount !== NULL ):
            $queryParameters[ 'strikeCount' ] = $strikeCount;
        endif;

        if ( $includeUnderlyingQuote !== NULL ):
            $queryParameters[ 'includeUnderlyingQuote' ] = $includeUnderlyingQuote ? 'TRUE' : 'FALSE';
        endif;

        if ( $strategy ):
            $strategy = strtoupper( $strategy );
            if ( !in_array( $strategy, self::STRATEGIES ) ):
                throw new \Exception( "Invalid strategy type '{$strategy}'." );
            endif;
            $queryParameters[ 'strategy' ] = $strategy;
        endif;

        if ( $interval !== NULL ):
            $queryParameters[ 'interval' ] = $interval;
        endif;

        if ( $strike !== NULL ):
            $queryParameters[ 'strike' ] = $strike;
        endif;

        if ( $range ):
            $range = strtoupper( $range );
            if ( !in_array( $range, self::RANGES ) ):
                throw new \Exception( "Invalid range type '{$range}'." );
            endif;
            $queryParameters[ 'range' ] = $range;
        endif;

        if ( $fromDate ):
            $queryParameters[ 'fromDate' ] = $fromDate->toDateString();
        endif;

        if ( $toDate ):
            $queryParameters[ 'toDate' ] = $toDate->toDateString();
        endif;

        if ( $volatility !== NULL ):
            $queryParameters[ 'volatility' ] = $volatility;
        endif;

        if ( $underlyingPrice !== NULL ):
            $queryParameters[ 'underlyingPrice' ] = $underlyingPrice;
        endif;

        if ( $interestRate !== NULL ):
            $queryParameters[ 'interestRate' ] = $interestRate;
        endif;

        if ( $daysToExpiration !== NULL ):
            $queryParameters[ 'daysToExpiration' ] = $daysToExpiration;
        endif;

        if ( $expMonth ):
            $expMonth = strtoupper( $expMonth );
            if ( !in_array( $expMonth, self::MONTHS ) ):
                throw new \Exception( "Invalid expMonth type '{$expMonth}'." );
            endif;
            $queryParameters[ 'expMonth' ] = $expMonth;
        endif;

        if ( $optionType ):
            $queryParameters[ 'optionType' ] = $optionType;
        endif;

        if ( $entitlement ):
            $entitlement = strtoupper( $entitlement );
            if ( !in_array( $entitlement, self::ENTITLEMENTS ) ):
                throw new \Exception( "Invalid entitlement type '{$entitlement}'." );
            endif;
            $queryParameters[ 'entitlement' ] = $entitlement;
        endif;

        if ( $queryParameters ):
            $suffix .= '?' . http_build_query( $queryParameters );
        endif;

        $response = $this->_request( $suffix );
        return $this->json( $response );
    }


}