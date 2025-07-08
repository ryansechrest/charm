<?php

namespace Charm\Models\Base;

use Charm\Contracts\IsPersistable;
use Charm\Contracts\Core\HasCoreUser;
use Charm\Models\Metas\UserMeta;
use Charm\Models\Core;
use Charm\Support\Result;
use Charm\Traits\WithDeferredCalls;
use Charm\Traits\WithMeta;
use Charm\Traits\WithPersistenceState;
use WP_User;
use WP_User_Query;

/**
 * Represents a base user in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class User implements HasCoreUser, IsPersistable
{
    use WithDeferredCalls;
    use WithMeta;
    use WithPersistenceState;

    // -------------------------------------------------------------------------

    /**
     * Core user.
     *
     * @var ?Core\User
     */
    protected ?Core\User $coreUser = null;

    // *************************************************************************

    /**
     * Set the class to be used when instantiating a user meta.
     *
     * @return string
     */
    protected static function metaClass(): string
    {
        return UserMeta::class;
    }

    // *************************************************************************

    /**
     * User constructor.
     *
     * @param Core\User|array $coreUserOrData
     */
    public function __construct(Core\User|array $coreUserOrData = [])
    {
        $this->coreUser = match (true) {
            $coreUserOrData instanceof Core\User => $coreUserOrData,
            is_array($coreUserOrData) => new Core\User($coreUserOrData),
            default => null
        };
    }

    // -------------------------------------------------------------------------

    /**
     * Get the core user instance.
     *
     * @return ?Core\User
     */
    public function coreUser(): ?Core\User
    {
        return $this->coreUser;
    }

    // *************************************************************************

    /**
     * Initialize the user.
     *
     * $key `int` -> User ID
     *      `null` -> Global user
     *      `string` (not email) -> Username / User login
     *      `string` (email) -> Email address
     *      `WP_User` -> `WP_User` instance
     *
     * @param int|null|string|WP_User $key
     * @return ?static
     */
    public static function init(
        int|null|string|WP_User $key = null
    ): ?static
    {
        $coreUser = match (true) {
            is_numeric($key) => Core\User::fromId((int) $key),
            is_string($key) && !is_email($key) => Core\User::fromUsername($key),
            is_string($key) && is_email($key) => Core\User::fromEmail($key),
            $key instanceof WP_User => Core\User::fromWpUser($key),
            default => Core\User::fromGlobalWpUser(),
        };

        if ($coreUser === null) {
            return null;
        }

        return new static($coreUser);
    }

    /**
     * Initialize the user and preload all of their metas.
     *
     * @param int|string|WP_User|null $key
     * @return ?static
     */
    public static function withMetas(
        int|null|string|WP_User $key = null
    ): ?static
    {
        return static::init($key)?->preloadMetas();
    }

    // -------------------------------------------------------------------------

    /**
     * Get the users.
     *
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_user_query/
     *
     * @param array $args
     * @return static[]
     */
    public static function get(array $args = ['search' => '']): array
    {
        $coreUsers = Core\User::get($args);
        $users = [];

        foreach ($coreUsers as $coreUser) {
            $users[] = new static($coreUser);
        }

        return $users;
    }

    /**
     * Query the users with `WP_User_Query`.
     *
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_user_query/
     *
     * @param array $args
     * @return WP_User_Query
     */
    public static function query(array $args): WP_User_Query
    {
        return Core\User::query($args);
    }

    // *************************************************************************

    /**
     * Save the user in the database.
     *
     * @return Result
     */
    public function save(): Result
    {
        return !$this->getId() ? $this->create() : $this->update();
    }

    /**
     * Create the user in the database and run all the deferred methods.
     *
     * @return Result
     */
    public function create(): Result
    {
        $result = $this->coreUser()->create();

        if ($result->hasFailed()) {
            return $result;
        }

        $result->addResults($this->runDeferred());

        return $result;
    }

    /**
     * Update the user in the database and run all the deferred methods.
     *
     * @return Result
     */
    public function update(): Result
    {
        $result = $this->coreUser()->update();

        if ($result->hasFailed()) {
            return $result;
        }

        $result->addResults($this->runDeferred());

        return $result;
    }

    /**
     * Delete the user from the database.
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->coreUser()->delete();
    }

    // *************************************************************************

    /**
     * Get the user ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->coreUser()->getId();
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the user exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->coreUser()->exists();
    }
}