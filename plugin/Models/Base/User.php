<?php

namespace Charm\Models\Base;

use Charm\Contracts\HasWpUser;
use Charm\Contracts\IsPersistable;
use Charm\Models\UserMeta;
use Charm\Models\WordPress;
use Charm\Support\Result;
use Charm\Traits\WithPersistenceState;
use Charm\Traits\WithMeta;
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
     * @param int|null|string|WP_User $key
     * @return ?static
     */
    public static function init(
        int|null|string|WP_User $key = null
    ): ?static
    {
        $wpUser = match (true) {
            is_numeric($key) => WordPress\User::fromId((int) $key),
            is_string($key) && !is_email($key) => WordPress\User::fromLogin($key),
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
     * @param array $params
     * @return static[]
     */
    public static function get(array $params): array
    {
        $wpUsers = WordPress\User::get($params);

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
     * @param array $params
     * @return WP_User_Query
     */
    public static function query(array $params): WP_User_Query
    {
        return WordPress\User::query($params);
    }

    // *************************************************************************

    /**
     * Save user
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->wp()->save();
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

        $results = $this->persistMetas($this->getId());
        $result->addResults($results);

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

        $results = $this->persistMetas($this->getId());
        $result->addResults($results);

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
}