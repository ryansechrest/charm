<?php

namespace Charm\Models\WordPress;

use Charm\Contracts\IsPersistable;
use Charm\Support\Result;
use WP_User;
use WP_User_Query;

/**
 * Represents a user in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class User implements IsPersistable
{
    /**
     * ID
     *
     * @var ?int
     */
    protected ?int $id = null;

    /**
     * User login
     *
     * @var ?string
     */
    protected ?string $userLogin = null;

    /**
     * User pass
     *
     * @var ?string
     */
    protected ?string $userPass = null;

    /**
     * User nice name
     *
     * @var ?string
     */
    protected ?string $userNicename = null;

    /**
     * User email
     *
     * @var ?string
     */
    protected ?string $userEmail = null;

    /**
     * User URL
     *
     * @var ?string
     */
    protected ?string $userUrl = null;

    /**
     * User registered
     *
     * @var ?string
     */
    protected ?string $userRegistered = null;

    /**
     * User activation key
     *
     * @var ?string
     */
    protected ?string $userActivationKey = null;

    /**
     * User status
     *
     * @var ?int
     */
    protected ?int $userStatus = null;

    /**
     * User display name
     *
     * @var ?string
     */
    protected ?string $displayName = null;

    // *************************************************************************

    /**
     * User constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }

        if (isset($data['userLogin'])) {
            $this->userLogin = $data['userLogin'];
        }

        if (isset($data['userPass'])) {
            $this->userPass = $data['userPass'];
        }

        if (isset($data['userNicename'])) {
            $this->userNicename = $data['userNicename'];
        }

        if (isset($data['userEmail'])) {
            $this->userEmail = $data['userEmail'];
        }

        if (isset($data['userUrl'])) {
            $this->userUrl = $data['userUrl'];
        }

        if (isset($data['userRegistered'])) {
            $this->userRegistered = $data['userRegistered'];
        }

        if (isset($data['userActivationKey'])) {
            $this->userActivationKey = $data['userActivationKey'];
        }

        if (isset($data['userStatus'])) {
            $this->userStatus = $data['userStatus'];
        }

        if (isset($data['displayName'])) {
            $this->displayName = $data['displayName'];
        }
    }

    // *************************************************************************

    /**
     * Initialize user
     *
     * @param int|null|string|WP_User $key
     * @return ?User
     */
    public static function init(int|null|string|WP_User $key = null): ?User
    {
        $user = new static();

        match (true) {
            is_numeric($key) => $user->loadFromId($key),
            is_string($key) && !is_email($key) => $user->loadFromLogin($key),
            is_string($key) && is_email($key) => $user->loadFromEmail($key),
            $key instanceof WP_User => $user->loadFromUser($key),
            default => $user->loadFromGlobalUser(),
        };

        return !$user->id ? null : $user;
    }

    /**
     * Get users
     *
     * @param array $params
     * @return static[]
     */
    public static function get(array $params): array
    {
        $wpUserQuery = static::query($params);

        if ($wpUserQuery->get_total() === 0) {
            return [];
        }

        return array_map(function (WP_User $wpUser) {
            return static::init($wpUser);
        }, $wpUserQuery->get_results());
    }

    /**
     * Query users with WP_User_Query
     *
     * @param array $params
     * @return WP_User_Query
     */
    public static function query(array $params): WP_User_Query
    {
        return new WP_User_Query($params);
    }

    // *************************************************************************

    /**
     * Save user
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->id === null ? $this->create() : $this->update();
    }

    /**
     * Create new user
     *
     * @return Result
     * @see wp_insert_user()
     * @see is_wp_error()
     */
    public function create(): Result
    {
        if ($this->id !== null) {
            return Result::error(
                'user_id_exists',
                __('User already exists.', 'charm')
            );
        }

        $includeData = ['user_login' => $this->userLogin];

        $result = wp_insert_user($this->toWpUserArray($includeData));

        if (is_wp_error($result)) {
            return Result::wpError($result);
        }

        if (!is_int($result)) {
            return Result::error(
                'wp_insert_user_failed',
                __('wp_insert_user() did not return an ID.', 'charm')
            );
        }

        $this->id = $result;
        $this->reload();

        return Result::success();
    }

    /**
     * Update existing user
     *
     * @return Result
     * @see wp_update_user()
     * @see is_wp_error()
     */
    public function update(): Result
    {
        if ($this->id === null) {
            return Result::error(
                'user_id_missing',
                __('Cannot update user with blank ID.', 'charm')
            );
        }

        $includeData = ['ID' => $this->id];

        $result = wp_update_user($this->toWpUserArray($includeData));

        if (is_wp_error($result)) {
            return Result::wpError($result);
        }

        if (!is_int($result)) {
            return Result::error(
                'wp_update_user_failed',
                __('wp_update_user() did not return an ID.', 'charm')
            );
        }

        $this->reload();

        return Result::success();
    }

    /**
     * Delete user
     *
     * @return Result
     * @see wp_delete_user()
     */
    public function delete(): Result
    {
        if ($this->id === null) {
            return Result::error(
                'user_id_missing',
                __('Cannot delete user with blank ID.', 'charm')
            );
        }

        $result = wp_delete_user($this->id);

        if ($result !== true) {
            return Result::error(
                'wp_delete_user_failed',
                __('wp_delete_user() did not return true.', 'charm')
            );
        }

        $this->id = null;

        return Result::success();
    }

    // *************************************************************************

    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id ?? 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Get user login
     *
     * @return string
     */
    public function getUserLogin(): string
    {
        return $this->userLogin ?? '';
    }

    /**
     * Set user login
     *
     * @param string $userLogin
     * @return static
     */
    public function setUserLogin(string $userLogin): static
    {
        $this->userLogin = $userLogin;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get user pass
     *
     * @return string
     */
    public function getUserPass(): string
    {
        return $this->userPass ?? '';
    }

    /**
     * Set user pass
     *
     * @param string $userPass
     * @return static
     */
    public function setUserPass(string $userPass): static
    {
        $this->userPass = $userPass;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get user nicename
     *
     * @return string
     */
    public function getUserNicename(): string
    {
        return $this->userNicename ?? '';
    }

    /**
     * Set user nicename
     *
     * @param string $userNicename
     * @return static
     */
    public function setUserNicename(string $userNicename): static
    {
        $this->userNicename = $userNicename;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get user email
     *
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->userEmail ?? '';
    }

    /**
     * Set user email
     *
     * @param string $userEmail
     * @return static
     */
    public function setUserEmail(string $userEmail): static
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get user URL
     *
     * @return string
     */
    public function getUserUrl(): string
    {
        return $this->userUrl ?? '';
    }

    /**
     * Set user URL
     *
     * @param string $userUrl
     * @return static
     */
    public function setUserUrl(string $userUrl): static
    {
        $this->userUrl = $userUrl;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get user registered
     *
     * @return string
     */
    public function getUserRegistered(): string
    {
        return $this->userRegistered ?? '';
    }

    /**
     * Set user registered
     *
     * @param string $userRegistered
     * @return static
     */
    public function setUserRegistered(string $userRegistered): static
    {
        $this->userRegistered = $userRegistered;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get user activation key
     *
     * @return string
     */
    public function getUserActivationKey(): string
    {
        return $this->userActivationKey ?? '';
    }

    /**
     * Set user activation key
     *
     * @param string $userActivationKey
     * @return static
     */
    public function setUserActivationKey(string $userActivationKey): static
    {
        $this->userActivationKey = $userActivationKey;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get user status
     *
     * @return int
     */
    public function getUserStatus(): int
    {
        return $this->userStatus ?? 0;
    }

    /**
     * Set user status
     *
     * @param int $userStatus
     * @return static
     */
    public function setUserStatus(int $userStatus): static
    {
        $this->userStatus = $userStatus;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get display name
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName ?? '';
    }

    /**
     * Set display name
     *
     * @param string $displayName
     * @return static
     */
    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    // *************************************************************************

    /**
     * Load instance from ID
     *
     * @param int $id
     */
    protected function loadFromId(int $id): void
    {
        if ($id === 0) {
            return;
        }

        if (!$wpUser = static::getWpUserBy('id', $id)) {
            return;
        }

        $this->loadFromUser($wpUser);
    }

    /**
     * Load instance from login
     *
     * @param string $login
     */
    protected function loadFromLogin(string $login): void
    {
        if (!$wpUser = static::getWpUserBy('login', $login)) {
            return;
        }

        $this->loadFromUser($wpUser);
    }

    /**
     * Load instance from email
     *
     * @param string $email
     */
    protected function loadFromEmail(string $email): void
    {
        if (!$wpUser = static::getWpUserBy('email', $email)) {
            return;
        }

        $this->loadFromUser($wpUser);
    }

    /**
     * Load instance from global WP_User
     *
     * @see wp_get_current_user()
     */
    protected function loadFromGlobalUser(): void
    {
        if (!$wpUser = wp_get_current_user()) {
            return;
        }

        if ($wpUser->ID === 0) {
            return;
        }

        $this->loadFromUser($wpUser);
    }

    /**
     * Load instance from WP_User object
     *
     * @param WP_User $wpUser
     */
    protected function loadFromUser(WP_User $wpUser): void
    {
        $this->id = (int) $wpUser->data->ID;
        $this->userLogin = $wpUser->data->user_login;
        $this->userPass = $wpUser->data->user_pass;
        $this->userNicename = $wpUser->data->user_nicename;
        $this->userEmail = $wpUser->data->user_email;
        $this->userUrl = $wpUser->data->user_url;
        $this->userRegistered = $wpUser->data->user_registered;
        $this->userActivationKey = $wpUser->data->user_activation_key;
        $this->userStatus = (int) $wpUser->data->user_status;
        $this->displayName = $wpUser->data->display_name;
    }

    // -------------------------------------------------------------------------

    /**
     * Reload instance from database
     */
    protected function reload(): void
    {
        if ($this->id === null) {
            return;
        }

        $this->loadFromId($this->id);
    }

    // -------------------------------------------------------------------------

    /**
     * Get WP_User by field
     *
     * @param string $field
     * @param int|string $value
     * @return ?WP_User
     */
    protected function getWpUserBy(string $field, int|string $value): ?WP_User
    {
        $object = WP_User::get_data_by($field, $value);

        if ($object === false) {
            return null;
        }

        $user = new WP_User();
        $user->init($object);

        return $user;
    }

    // -------------------------------------------------------------------------

    /**
     * Cast user to array to be used by WordPress functions
     *
     * Remove keys from array if the value is null,
     * since that indicates no value has been set.
     *
     * @param array $includeData
     * @return array
     */
    protected function toWpUserArray(array $includeData = []): array
    {
        $data = [
            'user_pass' => $this->userPass,
            'user_nicename' => $this->userNicename,
            'user_email' => $this->userEmail,
            'user_url' => $this->userUrl,
            'user_registered' => $this->userRegistered,
            'user_activation_key' => $this->userActivationKey,
            'user_status' => $this->userStatus,
            'display_name' => $this->displayName,
        ];

        $data = array_merge($includeData, $data);

        return array_filter($data, fn($value) => !is_null($value));
    }
}