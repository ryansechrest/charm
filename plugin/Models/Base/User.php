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
    public static function init(int|null|string|WP_User $key = null): ?static
    {
        if (!$wpUser = WordPress\User::init($key)) {
            return null;
        }

        $user = new static();
        $user->wpUser = $wpUser;

        return $user;
    }

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
        return $this->wp()->create();
    }

    /**
     * Update existing user
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->wp()->update();
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