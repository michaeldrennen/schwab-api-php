<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class Offer extends AbstractSchema {

    protected ?bool $level2Permissions = null;
    protected ?string $mktDataPermission = null;

    public function __construct( ?bool $level2Permissions = null, ?string $mktDataPermission = null ) {
        $this->level2Permissions = $level2Permissions;
        $this->mktDataPermission = $mktDataPermission;
    }

    public static function fromArray( array $data ): static {
        return new static(
            level2Permissions: isset($data['level2Permissions']) ? (bool)$data['level2Permissions'] : null,
            mktDataPermission: $data['mktDataPermission'] ?? null
        );
    }

    public function isLevel2Permissions(): ?bool { return $this->level2Permissions; }
    public function getMktDataPermission(): ?string { return $this->mktDataPermission; }
}
