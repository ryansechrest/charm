<?php

namespace Charm\Support;

use Charm\Enums\Result\Message;
use Charm\Enums\ResultStatus;
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
     * @var ResultStatus
     */
    private ResultStatus $status = ResultStatus::Success;

    /**
     * Main object ID associated with the operation.
     *
     * For example, if the operation was to create, update, or delete a post,
     * this would contain the post ID.
     *
     * @var int
     */
    private int $id = 0;

    /**
     * Result code associated with the operation.
     *
     * For example, if the operation was to create a post, the result code
     * might be: `post_create_success`, `post_create_failed`, etc.
     *
     * @var string
     */
    private string $code = '';

    /**
     * Result message that describes the code in more detail.
     *
     * For example, if the operation was to create a post, the message might
     * say something like: `Post successfully created` or `Post could not be
     * created because it already exists`.
     *
     * @var string
     */
    private string $message = '';

    /**
     * Return value of the operation.
     *
     * For example, if the operation was to create a post, the return value
     * might be the post ID, but if the operation was to delete a post, it
     * would contain a `WP_Post` instance of the deleted post.
     *
     * @var ?mixed
     */
    private mixed $value = null;

    /**
     * Data associated with the operation.
     *
     * For example, if the operation was to update a post, the data might be
     * the method arguments passed to update the post. If the operation had no
     * method arguments, data might refer to the instance cast to an array.
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

        if (isset($data['value'])) {
            $this->value = $data['value'];
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
     * Initialize a result for a successful operation.
     *
     * @param string $code post_create_success
     * @param string $message Post was successfully created.
     * @return self
     */
    public static function success(string $code, string $message): self
    {
        return new self([
            'status' => ResultStatus::Success,
            'code' => $code,
            'message' => __($message, 'charm'),
        ]);
    }

    /**
     * Initialize a result for a successful operation with additional info.
     *
     * @param string $code post_create_info
     * @param string $message Post was not created because it already exists.
     * @return self
     */
    public static function info(string $code, string $message): self
    {
        return new self([
            'status' => ResultStatus::Info,
            'code' => $code,
            'message' => __($message, 'charm'),
        ]);
    }

    /**
     * Initialize a result for a failed operation.
     *
     * @param string $code post_create_failed
     * @param string $message Post was not created due to a WordPress error.
     * @return self
     */
    public static function error(string $code, string $message): self
    {
        return new self([
            'status' => ResultStatus::Error,
            'code' => $code,
            'message' => __($message, 'charm'),
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
        return in_array($this->status, [ResultStatus::Success, ResultStatus::Warning]);
    }

    /**
     * Check whether the operation failed.
     *
     * @return bool
     */
    public function hasFailed(): bool
    {
        return $this->status === ResultStatus::Error;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the main object ID associated with the operation.
     *
     * @return int Post ID, Term ID, User ID, etc.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the result code associated with the operation.
     *
     * @return string `post_already_exists`, `post_not_found`, etc.
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get the result message that describes the code in more detail.
     *
     * @return string `Post already exists; cannot create a post with an ID.`
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get the return value of the operation.
     *
     * If the data is an array or object, a `$key` can be passed to return the
     * value for that key. Furthermore, if the key is not found, the specified
     * default can be returned instead.
     *
     * @param string $key id
     * @param mixed $default 0
     *
     * @return mixed
     */
    public function getReturn(string $key = '', mixed $default = null): mixed
    {
        if ($key !== '' && is_array($this->value)) {
            return $this->value[$key] ?? $default;
        }

        if ($key !== '' && is_object($this->value)) {
            return $this->value->$key ?? $default;
        }

        return $this->value;
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
     * Get the relevant `WP_Error` instance.
     *
     * @return ?WP_Error
     */
    public function getWpError(): ?WP_Error
    {
        return $this->wpError;
    }

    // -------------------------------------------------------------------------

    /**
     * Print the instance with a status and message.
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
     * Add a return value to the result.
     *
     * @param mixed $value
     * @return self
     */
    public function withReturn(mixed $value): self
    {
        $this->value = $value;

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

    /**
     * Add a `WP_Error` to the result.
     *
     * @param WP_Error $wpError
     * @return self
     */
    public function withWpError(WP_Error $wpError): self
    {
        $this->wpError = $wpError;

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