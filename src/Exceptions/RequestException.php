<?php

namespace MichaelDrennen\SchwabAPI\Exceptions;

use Throwable;

class RequestException extends \Exception {

    /**
     * @var string Ex: {"error":"unsupported_token_type","error_description":"400 Bad Request: \"{\"error_description\":\"Bad authorization code: String length must be a multiple of four. \",\"error\":\"invalid_request\"}\""}
     */
    protected string $rawResponseBody = '';


    /**
     * @var string|null Ex: unsupported_token_type
     */
    protected ?string $errorLabel = null;

    /**
     * @var int|null Ex: 400
     */
    protected ?int $errorCode = null;

    /**
     * @var string|null Ex: Bad Request
     */
    protected ?string $errorName = null;


    /**
     * @var string|null Ex: invalid_request
     */
    protected ?string $errorTag = null;

    /**
     * @var string|null Ex: Bad authorization code: String length must be a multiple of four.
     */
    protected ?string $errorDescription = null;


    /**
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     * @param string          $rawResponseBody Ex: {"error":"unsupported_token_type","error_description":"400 Bad Request: \"{\"error_description\":\"Bad authorization code: String length must be a multiple of four. \",\"error\":\"invalid_request\"}\""}
     */
    public function __construct( string     $message = "",
                                 int        $code = 0,
                                 ?Throwable $previous = NULL,
                                 string     $rawResponseBody = '' ) {
        parent::__construct( $message, $code, $previous );

        $this->rawResponseBody = $rawResponseBody;


        $this->_parseRawResponseBody();

    }

    protected function _parseRawResponseBody(): void {
        // Only parse if we have a response body
        if (empty($this->rawResponseBody)) {
            return;
        }

        $jsonResponse = json_decode($this->rawResponseBody, true);

        // Only parse if valid JSON
        if (!is_array($jsonResponse)) {
            return;
        }

        // Try to parse Schwab-specific error format
        if (isset($jsonResponse['error'])) {
            $this->errorLabel = $jsonResponse['error'];
        }

        if (isset($jsonResponse['error_description'])) {
            $errorDescription = $jsonResponse['error_description'];
            $errorDescriptionParts = preg_split('/:/', $errorDescription, 2);

            $matches = [];
            if (preg_match('/(\d{3}) (.*)/', $errorDescriptionParts[0], $matches)) {
                $this->errorCode = (int) $matches[1];
                $this->errorName = $matches[2];
            }

            if (isset($errorDescriptionParts[1])) {
                $jsonStringWithErrorData = trim($errorDescriptionParts[1], ' "');
                $errorData = json_decode($jsonStringWithErrorData, true);

                if (is_array($errorData)) {
                    if (isset($errorData['error_description'])) {
                        $this->errorDescription = trim($errorData['error_description']);
                    }
                    if (isset($errorData['error'])) {
                        $this->errorTag = trim($errorData['error']);
                    }
                }
            }
        }
    }

    /**
     * Get the raw response body
     *
     * @return string
     */
    public function getResponseBody(): string {
        return $this->rawResponseBody;
    }
}