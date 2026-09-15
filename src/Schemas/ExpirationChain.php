<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class ExpirationChain extends AbstractSchema {

    protected ?string $status = null;
    /**
     * @var Expiration[]
     */
    protected array $expirationList = [];

    public function __construct( ?string $status = null, array $expirationList = [] ) {
        $this->status         = $status;
        $this->expirationList = $expirationList;
    }

    public static function fromArray( array $data ): static {
        $list = [];
        if ( isset($data['expirationList']) && is_array($data['expirationList']) ) {
            $list = array_map(
                fn($e) => is_array($e) ? Expiration::fromArray($e) : $e,
                $data['expirationList']
            );
        }

        return new static(
            status: $data['status'] ?? null,
            expirationList: $list
        );
    }

    public function getStatus(): ?string { return $this->status; }
    public function getExpirationList(): array { return $this->expirationList; }
}
