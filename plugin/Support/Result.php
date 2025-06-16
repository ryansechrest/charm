<?php

namespace Charm\Support;

use Charm\Enums\ResultStatus;

/**
 * Represents a result after performing an operation.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Result
{
    /**
     * Result status of the operation.
     *
     * If the operation is to create a post, the result status might be
     * `Success` when the post was successfully created, `Info` when it wasn't
     * created because it already exists, and `Error` when the post couldn't
     * be created.
     *
     * @var ResultStatus
     */
    private ResultStatus $status = ResultStatus::Success;

    /**
     * Result code of the operation.
     *
     * If the operation is to create a post, the code might be
     * `post_create_success` when the post is successfully created,
     * `post_already_exists` when it wasn't created because it already exists,
     * and `post_created_failed` when the post couldn't be created.
     *
     * @var string
     */
    private string $code = '';

    /**
     * Result message of the operation.
     *
     * If the operation is to create a post, the message might be `Post
     * successfully created`, `Post was not created because it already
     * exists`, or `Post could not be created` depending on the result status.
     *
     * @var string
     */
    private string $message = '';

    /**
     * Object ID of the object being manipulated.
     *
     * If the operation is to create a post, and it was successful, this value
     * would be the post ID.
     *
     * @var int
     */
    private int $objectId = 0;

    /**
     * Object snapshot of all its props and values in an array.
     *
     * If the operation is to create a post, it would the post cast to an array,
     * meaning it would contain the post ID, post title, post content, etc.
     *
     * @var array
     */
    private array $objectSnapshot = [];

    /**
     * Return value from the function that executed the operation.
     *
     * If the operation is to create a post, it would contain the raw return
     * value from the `wp_insert_post()` function.
     *
     * @var ?mixed
     */
    private mixed $functionReturn = null;

    /**
     * Argument values passed to the function that executed the operation.
     *
     * If the operation is to create a post, it would contain an array of fields
     * that were passed to create said post, such as the post title, post
     * content, post status, etc.
     *
     * @var array
     */
    private array $functionArgs = [];

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
     * Print the instance with a status and message.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->status->label() . ': ' . $this->getMessage();
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

        if (isset($data['objectId'])) {
            $this->objectId = (int) $data['objectId'];
        }

        if (isset($data['objectSnapshot'])) {
            $this->objectSnapshot = $data['objectSnapshot'];
        }

        if (isset($data['functionReturn'])) {
            $this->functionReturn = $data['functionReturn'];
        }

        if (isset($data['functionArgs'])) {
            $this->functionArgs = $data['functionArgs'];
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
        return in_array($this->status, [ResultStatus::Success, ResultStatus::Info]);
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
     * Get the result code of the operation.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Get the result message of the operation.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the object ID of the object being manipulated.
     *
     * @return int
     */
    public function getObjectId(): int
    {
        return $this->objectId;
    }

    /**
     * Set the object ID of the object being manipulated.
     *
     * @param int $id
     * @return self
     */
    public function setObjectId(int $id): self
    {
        $this->objectId = $id;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the object snapshot of all its props and values in an array.
     *
     * @return array
     */
    public function getObjectSnapshot(): array
    {
        return $this->objectSnapshot;
    }

    /**
     * Set the object snapshot of all its props and values in an array.
     *
     * @param array $snapshot
     * @return self
     */
    public function setObjectSnapshot(array $snapshot): self
    {
        $this->objectSnapshot = $snapshot;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the return value from the function that executed the operation.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getFunctionReturn(string $key = '', mixed $default = null): mixed
    {
        if ($key === '') {
            return $this->functionReturn;
        }

        if (is_array($this->functionReturn)) {
            return $this->functionReturn[$key] ?? $default;
        }

        if (is_object($this->functionReturn)) {
            return $this->functionReturn->$key ?? $default;
        }

        return $default;
    }

    /**
     * Set the return value from the function that executed the operation.
     *
     * @param mixed $value
     * @return self
     */
    public function setFunctionReturn(mixed $value): self
    {
        $this->functionReturn = $value;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the argument values passed to the function that executed the operation.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getFunctionArgs(string $key = '', mixed $default = null): mixed
    {
        if ($key === '') {
            return $this->functionArgs;
        }

        return $this->functionArgs[$key] ?? $default;
    }

    /**
     * Set the argument values passed to the function that executed the operation.
     *
     * @param array $args
     * @return self
     */
    public function setFunctionArgs(array $args): self
    {
        $this->functionArgs = $args;

        return $this;
    }

    // *************************************************************************

    /**
     * Add a single, related result.
     *
     * @param Result $result
     * @return self
     */
    public function addResult(Result $result): self
    {
        $this->relatedResults[] = $result;

        return $this;
    }

    /**
     * Add multiple, related results.
     *
     * @param Result[] $results
     * @return self
     */
    public function addResults(array $results): self
    {
        foreach ($results as $result) {
            $this->addResult($result);
        }

        return $this;
    }

    // -------------------------------------------------------------------------

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