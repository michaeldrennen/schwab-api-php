<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class StreamerInfo extends AbstractSchema {

    protected ?string $streamerSocketUrl = null;
    protected ?string $schwabClientCorrelId = null;
    protected ?string $schwabClientChannel = null;
    protected ?string $schwabClientFunctionId = null;

    public function __construct(
        ?string $streamerSocketUrl = null,
        ?string $schwabClientCorrelId = null,
        ?string $schwabClientChannel = null,
        ?string $schwabClientFunctionId = null
    ) {
        $this->streamerSocketUrl      = $streamerSocketUrl;
        $this->schwabClientCorrelId   = $schwabClientCorrelId;
        $this->schwabClientChannel    = $schwabClientChannel;
        $this->schwabClientFunctionId = $schwabClientFunctionId;
    }

    public static function fromArray( array $data ): static {
        return new static(
            streamerSocketUrl: $data['streamerSocketUrl'] ?? null,
            schwabClientCorrelId: $data['schwabClientCorrelId'] ?? null,
            schwabClientChannel: $data['schwabClientChannel'] ?? null,
            schwabClientFunctionId: $data['schwabClientFunctionId'] ?? null
        );
    }

    public function getStreamerSocketUrl(): ?string { return $this->streamerSocketUrl; }
    public function getSchwabClientCorrelId(): ?string { return $this->schwabClientCorrelId; }
    public function getSchwabClientChannel(): ?string { return $this->schwabClientChannel; }
    public function getSchwabClientFunctionId(): ?string { return $this->schwabClientFunctionId; }
}
