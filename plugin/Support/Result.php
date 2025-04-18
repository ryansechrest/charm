<?php

namespace Charm\Support;

use WP_Error;

/**
 * Represents a result when performing an operation.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Result
{
    /**
     * Whether operation was successful
     *
     * @var bool
     */
    private bool $success = false;

    /**
     * Error code if operation failed
     *
     * @var string
     */
    private string $errorCode = '';

    /**
     * Error message if operation failed
     *
     * @var string
     */
    private string $errorMessage = '';

    /**
     * Relevant WP_Error instance
     *
     * @var ?WP_Error
     */
    private ?WP_Error $wpError = null;

    /**************************************************************************/

    /**
     * Result constructor
     *
     * @param bool $success
     * @param string $errorCode
     * @param string $errorMessage
     * @param ?WP_Error $wpError
     */
    private function __construct(
        bool $success,
        string $errorCode = '',
        string $errorMessage = '',
        WP_Error $wpError = null
    )
    {
        $this->success = $success;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
        $this->wpError = $wpError;
    }

    /**************************************************************************/

    /**
     * Initialize result that operation succeeded
     *
     * @return self
     */
    public static function success(): self
    {
        return new self(true);
    }

    /**
     * Initialize result that operation failed
     *
     * @param string $code
     * @param string $message
     * @return self
     */
    public static function error(string $code = '', string $message = ''): self
    {
        return new self(false, $code, $message);
    }

    /**
     * Initialize result from WP_Error
     *
     * @param WP_Error $wpError
     * @return self
     */
    public static function wpError(WP_Error $wpError): self
    {
        return new self(
            false,
            $wpError->get_error_code(),
            $wpError->get_error_message(),
            $wpError
        );
    }

    /**************************************************************************/

    /**
     * Whether operation succeeded
     *
     * @return bool
     */
    public function hasSucceeded(): bool
    {
        return $this->success;
    }

    /**
     * Whether operation failed
     *
     * @return bool
     */
    public function hasFailed(): bool
    {
        return !$this->success;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get error code
     *
     * @return string
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * Get error message
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * Get WP_Error instance
     *
     * @return ?WP_Error
     */
    public function getWpError(): ?WP_Error
    {
        return $this->wpError;
    }

    /**************************************************************************/

    /**
     * Ability to directly print result as message
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->hasSucceeded()
            ? 'Success'
            : 'Error: ' . $this->getErrorMessage();
    }
}