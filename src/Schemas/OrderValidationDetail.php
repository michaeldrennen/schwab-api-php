<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class OrderValidationDetail extends AbstractSchema {

    protected ?string $validationRuleName = null;
    protected ?string $message = null;
    protected ?string $activityMessage = null;
    protected ?string $originalSeverity = null;
    protected ?string $overrideSeverity = null;

    public function __construct(
        ?string $validationRuleName = null,
        ?string $message = null,
        ?string $activityMessage = null,
        ?string $originalSeverity = null,
        ?string $overrideSeverity = null
    ) {
        $this->validationRuleName = $validationRuleName;
        $this->message            = $message;
        $this->activityMessage    = $activityMessage;
        $this->originalSeverity   = $originalSeverity;
        $this->overrideSeverity   = $overrideSeverity;
    }

    public static function fromArray( array $data ): static {
        return new static(
            validationRuleName: $data['validationRuleName'] ?? null,
            message: $data['message'] ?? null,
            activityMessage: $data['activityMessage'] ?? null,
            originalSeverity: $data['originalSeverity'] ?? null,
            overrideSeverity: $data['overrideSeverity'] ?? null
        );
    }

    public function getValidationRuleName(): ?string { return $this->validationRuleName; }
    public function getMessage(): ?string { return $this->message; }
    public function getActivityMessage(): ?string { return $this->activityMessage; }
    public function getOriginalSeverity(): ?string { return $this->originalSeverity; }
    public function getOverrideSeverity(): ?string { return $this->overrideSeverity; }
}
