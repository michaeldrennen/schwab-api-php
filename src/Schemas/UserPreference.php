<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class UserPreference extends AbstractSchema {

    /**
     * @var UserPreferenceAccount[]
     */
    protected array $accounts = [];
    /**
     * @var StreamerInfo[]
     */
    protected array $streamerInfo = [];
    /**
     * @var Offer[]
     */
    protected array $offers = [];

    public function __construct(
        array $accounts = [],
        array $streamerInfo = [],
        array $offers = []
    ) {
        $this->accounts     = $accounts;
        $this->streamerInfo = $streamerInfo;
        $this->offers       = $offers;
    }

    public static function fromArray( array $data ): static {
        $accounts = [];
        if ( isset($data['accounts']) && is_array($data['accounts']) ) {
            $accounts = array_map(
                fn($a) => is_array($a) ? UserPreferenceAccount::fromArray($a) : $a,
                $data['accounts']
            );
        }

        $streamerInfo = [];
        if ( isset($data['streamerInfo']) && is_array($data['streamerInfo']) ) {
            $streamerInfo = array_map(
                fn($s) => is_array($s) ? StreamerInfo::fromArray($s) : $s,
                $data['streamerInfo']
            );
        }

        $offers = [];
        if ( isset($data['offers']) && is_array($data['offers']) ) {
            $offers = array_map(
                fn($o) => is_array($o) ? Offer::fromArray($o) : $o,
                $data['offers']
            );
        }

        return new static(
            accounts: $accounts,
            streamerInfo: $streamerInfo,
            offers: $offers
        );
    }

    public function getAccounts(): array { return $this->accounts; }
    public function getStreamerInfo(): array { return $this->streamerInfo; }
    public function getOffers(): array { return $this->offers; }
}
