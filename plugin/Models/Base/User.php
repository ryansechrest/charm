<?php

namespace Charm\Models\Base;

use Charm\Contracts\IsPersistable;
use Charm\Contracts\Proxy\HasProxyUser;
use Charm\Models\Metas\UserMeta;
use Charm\Models\Proxy;
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
abstract class User implements HasProxyUser, IsPersistable
{
    use WithDeferredCalls;
    use WithMeta;
    use WithPersistenceState;

    // -------------------------------------------------------------------------

    /**
     * Proxy user.
     *
     * @var ?Proxy\User
     */
    protected ?Proxy\User $proxyUser = null;

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
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->proxyUser = new Proxy\User($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get the proxy user instance.
     *
     * @return ?Proxy\User
     */
    public function proxyUser(): ?Proxy\User
    {
        return $this->proxyUser;
    }

    // *************************************************************************

    /**
     * Initialize the user.
     *
     * int                -> User ID
     * null               -> Global User
     * string (not email) -> Username / User Login
     * string (email)     -> Email Address
     * WP_User            -> WP_User instance
     *
     * @param int|null|string|WP_User $key
     * @return ?static
     */
    public static function init(
        int|null|string|WP_User $key = null
    ): ?static
    {
        $proxyUser = match (true) {
            is_numeric($key) => Proxy\User::fromId((int) $key),
            is_string($key) && !is_email($key) => Proxy\User::fromUsername($key),
            is_string($key) && is_email($key) => Proxy\User::fromEmail($key),
            $key instanceof WP_User => Proxy\User::fromWpUser($key),
            default => Proxy\User::fromGlobalWpUser(),
        };

        if ($proxyUser === null) {
            return null;
        }

        $user = new static();
        $user->proxyUser = $proxyUser;

        return $user;
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
        $proxyUsers = Proxy\User::get($args);
        $users = [];

        foreach ($proxyUsers as $proxyUser) {
            $user = new static();
            $user->proxyUser = $proxyUser;
            $users[] = $user;
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
        return Proxy\User::query($args);
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
        $result = $this->proxyUser()->create();

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
        $result = $this->proxyUser()->update();

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
        return $this->proxyUser()->delete();
    }

    // *************************************************************************

    /**
     * Get the user ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->proxyUser()->getId();
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the user exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->proxyUser()->exists();
    }
}