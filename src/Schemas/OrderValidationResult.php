<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

class OrderValidationResult extends AbstractSchema {

    /**
     * @var OrderValidationDetail[]
     */
    protected array $warns = [];
    /**
     * @var OrderValidationDetail[]
     */
    protected array $errors = [];

    public function __construct( array $warns = [], array $errors = [] ) {
        $this->warns  = $warns;
        $this->errors = $errors;
    }

    public static function fromArray( array $data ): static {
        $warns = [];
        if ( isset($data['warns']) && is_array($data['warns']) ) {
            $warns = array_map(
                fn($w) => is_array($w) ? OrderValidationDetail::fromArray($w) : $w,
                $data['warns']
            );
        }

        $errors = [];
        if ( isset($data['errors']) && is_array($data['errors']) ) {
            $errors = array_map(
                fn($e) => is_array($e) ? OrderValidationDetail::fromArray($e) : $e,
                $data['errors']
            );
        }

        return new static(
            warns: $warns,
            errors: $errors
        );
    }

    public function getWarns(): array { return $this->warns; }
    public function getErrors(): array { return $this->errors; }
}
