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
     * @var Status `Success`, `Warning`, `Error`, etc.
     */
    private Status $status = Status::Success;

    /**
     * ID associated with the operation.
     *
     * @var int Post ID, Term ID, User ID, etc.
     */
    private int $id = 0;

    /**
     * Code associated with the operation.
     *
     * @var string `post_already_exists`, `post_not_found`, etc.
     */
    private string $code = '';

    /**
     * Message associated with the operation.
     *
     * @var string `Post already exists; cannot create a post with an ID.`
     */
    private string $message = '';

    /**
     * Source of what triggered the operation.
     *
     * @var string `Charm` or `WordPress`
     */
    private string $source = '';

    /**
     * Data associated with the operation.
     *
     * Could be the arguments passed to the method, the return value of the
     * operation, or a copy of the current instance state.
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
     * Get the ID associated with the operation.
     *
     * @return int Post ID, Term ID, User ID, etc.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the code associated with the operation.
     *
     * @return string `post_already_exists`, `post_not_found`, etc.
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get the message associated with the operation.
     *
     * @return string `Post already exists; cannot create a post with an ID.`
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get the source of what triggered the operation.
     *
     * @return string `Charm` or `WordPress`
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Get the data associated with the operation.
     *
     * If the data is an array or object, a `$key` can be passed to return the
     * value for that key. Furthermore, if the key is not found, the specified
     * default can be returned instead.
     *
     * @param string $key id
     * @param mixed $default 0
     * @return mixed
     */
    public function getData(string $key = '', mixed $default = null): mixed
    {
        if ($key !== '' && is_array($this->data)) {
            return $this->data[$key] ?? $default;
        }

        if ($key !== '' && is_object($this->data)) {
            return $this->data->$key ?? $default;
        }

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
     * Add an ID to the result.
     *
     * @param int $id
     * @return self
     */
    public function withId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

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