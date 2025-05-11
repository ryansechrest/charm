<?php

namespace Charm\Models\Base;

use Charm\Contracts\HasWpUser;
use Charm\Contracts\IsPersistable;
use Charm\Models\Meta\UserMeta;
use Charm\Models\WordPress;
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
abstract class User implements HasWpUser, IsPersistable
{
    use WithDeferredCalls;
    use WithMeta;
    use WithPersistenceState;

    // -------------------------------------------------------------------------

    /**
     * WordPress user
     *
     * @var ?WordPress\User
     */
    protected ?WordPress\User $wpUser = null;

    // *************************************************************************

    /**
     * Define default meta class
     *
     * @return string
     */
    protected static function metaClass(): string
    {
        return UserMeta::class;
    }

    // *************************************************************************

    /**
     * User constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->wpUser = new WordPress\User($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get WordPress user instance
     *
     * @return ?WordPress\User
     */
    public function wp(): ?WordPress\User
    {
        return $this->wpUser;
    }

    // *************************************************************************

    /**
     * Initialize user
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
        $wpUser = match (true) {
            is_numeric($key) => WordPress\User::fromId((int) $key),
            is_string($key) && !is_email($key) => WordPress\User::fromUsername($key),
            is_string($key) && is_email($key) => WordPress\User::fromEmail($key),
            $key instanceof WP_User => WordPress\User::fromWpUser($key),
            default => WordPress\User::fromGlobalWpUser(),
        };

        if ($wpUser === null) {
            return null;
        }

        $user = new static();
        $user->wpUser = $wpUser;

        return $user;
    }

    /**
     * Initialize user and preload metas
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
     * Get users
     *
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_user_query/
     *
     * @param array $args
     * @return static[]
     */
    public static function get(array $args = ['search' => '']): array
    {
        $wpUsers = WordPress\User::get($args);
        $users = [];

        foreach ($wpUsers as $wpUser) {
            $user = new static();
            $user->wpUser = $wpUser;
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Query users with WP_User_Query
     *
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_user_query/
     *
     * @param array $args
     * @return WP_User_Query
     */
    public static function query(array $args): WP_User_Query
    {
        return WordPress\User::query($args);
    }

    // *************************************************************************

    /**
     * Save user
     *
     * @return Result
     */
    public function save(): Result
    {
        return !$this->getId() ? $this->create() : $this->update();
    }

    /**
     * Create new user
     *
     * @return Result
     */
    public function create(): Result
    {
        $result = $this->wp()->create();

        if ($result->hasFailed()) {
            return $result;
        }

        $result->addResults($this->runDeferred());

        return $result;
    }

    /**
     * Update existing user
     *
     * @return Result
     */
    public function update(): Result
    {
        $result = $this->wp()->update();

        if ($result->hasFailed()) {
            return $result;
        }

        $result->addResults($this->runDeferred());

        return $result;
    }

    /**
     * Delete user
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->wp()->delete();
    }

    // *************************************************************************

    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->wp()->getId();
    }

    // -------------------------------------------------------------------------

    /**
     * Whether user exists in database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->wp()->exists();
    }
}