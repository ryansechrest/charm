<?php

namespace Charm\Support;

use Charm\Enums\Result\Message;
use Charm\Enums\Result\Status;
use WP_Error;

/**
 * Represents a result after performing an operation.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Result
{
    /**
     * Status of the operation.
     *
     * @var Status
     */
    private Status $status = Status::Success;

    /**
     * Code associated with the operation.
     *
     * @var string
     */
    private string $code = '';

    /**
     * Message associated with the operation.
     *
     * @var string
     */
    private string $message = '';

    /**
     * Source of what triggered the operation.
     *
     * @var string
     */
    private string $source = '';

    /**
     * Associated data with the operation.
     *
     * @var ?mixed
     */
    private mixed $data = null;

    /**
     * Relevant `WP_Error` instance.
     *
     * @var ?WP_Error
     */
    private ?WP_Error $wpError = null;

    // -------------------------------------------------------------------------

    /**
     * Related results triggered by the main operation.
     *
     * @var array
     */
    private array $relatedResults = [];

    // *************************************************************************

    /**
     * Result constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->load($data);
    }

    /**
     * Load the instance with data.
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['status'])) {
            $this->status = $data['status'];
        }

        if (isset($data['code'])) {
            $this->code = $data['code'];
        }

        if (isset($data['message'])) {
            $this->message = $data['message'];
        }

        if (isset($data['source'])) {
            $this->source = $data['source'];
        }

        if (isset($data['data'])) {
            $this->data = $data['data'];
        }

        if (isset($data['wpError'])) {
            $this->wpError = $data['wpError'];
        }
    }

    // *************************************************************************

    /**
     * Initialize a result that indicates a successful operation.
     *
     * @param Message $message
     * @return self
     */
    public static function success(Message $message): self
    {
        return new self([
            'status' => Status::Success,
            'code' => $message->code(),
            'message' => $message->message(),
            'source' => 'Charm',
        ]);
    }

    /**
     * Initialize a result that produced a warning.
     *
     * @param Message $message
     * @return self
     */
    public static function warning(Message $message): self
    {
        return new self([
            'status' => Status::Warning,
            'code' => $message->code(),
            'message' => $message->message(),
            'source' => 'Charm',
        ]);
    }

    /**
     * Initialize a result that indicates a failed operation.
     *
     * @param Message $message
     * @return self
     */
    public static function error(Message $message): self
    {
        return new self([
            'status' => Status::Error,
            'code' => $message->code(),
            'message' => $message->message(),
            'source' => 'Charm',
        ]);
    }

    /**
     * Initialize the result from a `WP_Error` instance.
     *
     * @param WP_Error $wpError
     * @return self
     */
    public static function wpError(WP_Error $wpError): self
    {
        return new self([
            'status' => false,
            'code' => $wpError->get_error_code(),
            'message' => $wpError->get_error_message(),
            'source' => 'WordPress',
            'wpError' => $wpError,
        ]);
    }

    // *************************************************************************

    /**
     * Check whether the operation succeeded.
     *
     * @return bool
     */
    public function hasSucceeded(): bool
    {
        return in_array($this->status, [Status::Success, Status::Warning]);
    }

    /**
     * Check whether the operation failed.
     *
     * @return bool
     */
    public function hasFailed(): bool
    {
        return $this->status === Status::Error;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the code of the operation.
     *
     * @return string foobar_failed
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get the message of the operation.
     *
     * @return string Foobar could not be executed.
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get the source of the operation.
     *
     * @return string Charm
     */
    public function getSource(): string
    {
        return $this->message;
    }

    /**
     * Get the data associated with the operation.
     *
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Get the `WP_Error` instance.
     *
     * @return ?WP_Error
     */
    public function getWpError(): ?WP_Error
    {
        return $this->wpError;
    }

    // -------------------------------------------------------------------------

    /**
     * Print instance with a status and message.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->status->label() . ': ' . $this->getMessage();
    }

    // *************************************************************************

    /**
     * Add data to the result.
     *
     * @param mixed $data
     * @return self
     */
    public function withData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Add a single, related result.
     *
     * @param Result $result
     * @return void
     */
    public function addResult(Result $result): void
    {
        $this->relatedResults[] = $result;
    }

    /**
     * Add multiple, related results.
     *
     * @param Result[] $results
     * @return void
     */
    public function addResults(array $results): void
    {
        foreach ($results as $result) {
            $this->addResult($result);
        }
    }

    /**
     * Get main and related results.
     *
     * @return Result[]
     */
    public function getResults(): array
    {
        return array_merge([$this], $this->relatedResults);
    }

    /**
     * Get main and related results that failed.
     *
     * @return Result[]
     */
    public function getFailedResults(): array
    {
        return array_values(
            array_filter(
                $this->getResults(),
                fn(Result $result) => $result->hasFailed()
            )
        );
    }

    /**
     * Get main and related results that succeeded.
     *
     * @return Result[]
     */
    public function getSuccessfulResults(): array
    {
        return array_values(
            array_filter(
                $this->getResults(),
                fn(Result $result) => $result->hasSucceeded()
            )
        );
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether any main or related result failed.
     *
     * @return bool
     */
    public function hasFailedResults(): bool
    {
        return count($this->getFailedResults()) > 0;
    }
}