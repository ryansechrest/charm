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

    // -------------------------------------------------------------------------

    /**
     * WP_User instance
     *
     * @var ?WP_User
     */
    protected ?WP_User $wpUser = null;

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

    // -------------------------------------------------------------------------

    /**
     * Get WP_User instance
     *
     * @return ?WP_User
     */
    public function core(): ?WP_User
    {
        return $this->wpUser;
    }

    // *************************************************************************

    /**
     * Initialize user from ID
     *
     * From: wp_user -> ID
     *
     * @param int $id
     * @return ?static
     */
    public static function fromId(int $id): ?static
    {
        $user = new static();
        $user->loadFromId($id);

        return $user->id ? $user : null;
    }

    /**
     * Initialize user from username
     *
     * From: wp_user -> user_login
     *
     * @param string $username john.doe
     * @return ?static
     */
    public static function fromUsername(string $username): ?static
    {
        $user = new static();
        $user->loadFromUsername($username);

        return $user->id ? $user : null;
    }

    /**
     * Initialize user from slug
     *
     * From: wp_user -> user_nicename
     *
     * @param string $slug john-doe
     * @return ?static
     */
    public static function fromSlug(string $slug): ?static
    {
        $user = new static();
        $user->loadFromSlug($slug);

        return $user->id ? $user : null;
    }

    /**
     * Initialize user from email
     *
     * From: wp_user -> user_email
     *
     * @param string $email john.doe@example.org
     * @return ?static
     */
    public static function fromEmail(string $email): ?static
    {
        $user = new static();
        $user->loadFromEmail($email);

        return $user->id ? $user : null;
    }

    /**
     * Initialize user from global WP_User
     *
     * @return ?static
     * @see wp_get_current_user()
     */
    public static function fromGlobalWpUser(): ?static
    {
        $user = new static();
        $user->loadFromGlobalWpUser();

        return $user->id ? $user : null;
    }

    /**
     * Initialize user from WP_User
     *
     * @param WP_User $wpUser
     * @return static
     */
    public static function fromWpUser(WP_User $wpUser): static
    {
        $user = new static();
        $user->loadFromWpUser($wpUser);

        return $user;
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
    public static function get(array $args): array
    {
        $wpUserQuery = static::query($args);

        if ($wpUserQuery->get_total() === 0) {
            return [];
        }

        return array_map(function (WP_User $wpUser) {
            return static::fromWpUser($wpUser);
        }, $wpUserQuery->get_results());
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
        return new WP_User_Query(query: $args);
    }

    // -------------------------------------------------------------------------

    /**
     * Create new user
     *
     * @param array $data
     * @return Result
     * @see wp_insert_user()
     * @see is_wp_error()
     */
    public static function createUser(array $data): Result
    {
        $result = wp_insert_user(userdata: $data);

        if (is_wp_error($result)) {
            return Result::wpError(wpError: $result)
                ->withData(func_get_args());
        }

        if (!is_int($result)) {
            return Result::error(
                code: 'wp_insert_user_failed',
                message: __('wp_insert_user() did not return an ID.', 'charm')
            )->withData(func_get_args());
        }

        return Result::success()->withData($result);
    }

    /**
     * Update existing user
     *
     * @param array $data
     * @return Result
     * @see wp_update_user()
     * @see is_wp_error()
     */
    public static function updateUser(array $data): Result
    {
        $result = wp_update_user(userdata: $data);

        if (is_wp_error($result)) {
            return Result::wpError(wpError: $result)
                ->withData($data);
        }

        if (!is_int($result)) {
            return Result::error(
                code: 'wp_update_user_failed',
                message: __('wp_update_user() did not return an ID.', 'charm')
            )->withData($data);
        }

        return Result::success();
    }

    /**
     * Delete user
     *
     * @param int $id
     * @return Result
     * @see wp_delete_user()
     */
    public static function deleteUser(int $id): Result
    {
        $result = wp_delete_user(id: $id);

        if ($result !== true) {
            return Result::error(
                code: 'wp_delete_user_failed',
                message: __('wp_delete_user() did not return true.', 'charm')
            )->withData(func_get_args());
        }

        return Result::success();
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
                code: 'user_id_exists',
                message: __('User already exists.', 'charm')
            )->withData($this);
        }

        $result = static::createUser(
            data: $this->toWpUserArray(
                includeData: ['user_login' => $this->userLogin]
            )
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $this->id = $result->getData();
        $this->reload();

        return $result;
    }

    /**
     * Update existing user
     *
     * @return Result
     */
    public function update(): Result
    {
        if ($this->id === null) {
            return Result::error(
                code: 'user_id_missing',
                message: __('Cannot update user with blank ID.', 'charm')
            )->withData($this);
        }

        $result = static::updateUser(
            data: $this->toWpUserArray(includeData: ['ID' => $this->id])
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $this->reload();

        return $result;
    }

    /**
     * Delete user
     *
     * @return Result
     */
    public function delete(): Result
    {
        if ($this->id === null) {
            return Result::error(
                code: 'user_id_missing',
                message: __('Cannot delete user with blank ID.', 'charm')
            )->withData($this);
        }

        $result =  static::deleteUser(id: $this->id);

        if ($result->hasFailed()) {
            return $result;
        }

        $this->id = null;

        return $result;
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
     * @return string john.doe
     */
    public function getUserLogin(): string
    {
        return $this->userLogin ?? '';
    }

    /**
     * Set user login
     *
     * @param string $userLogin john.doe
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
     * @return string Hashed password
     */
    public function getUserPass(): string
    {
        return $this->userPass ?? '';
    }

    /**
     * Set user pass
     *
     * @param string $userPass Cleartext password
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
     * @return string john-doe
     */
    public function getUserNicename(): string
    {
        return $this->userNicename ?? '';
    }

    /**
     * Set user nicename
     *
     * @param string $userNicename john-doe
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
     * @return string john.doe@example.org
     */
    public function getUserEmail(): string
    {
        return $this->userEmail ?? '';
    }

    /**
     * Set user email
     *
     * @param string $userEmail john.doe@example.org
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
     * @return string https://example.org
     */
    public function getUserUrl(): string
    {
        return $this->userUrl ?? '';
    }

    /**
     * Set user URL
     *
     * @param string $userUrl https://example.org
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
     * @return string 0000-00-00 00:00:00
     */
    public function getUserRegistered(): string
    {
        return $this->userRegistered ?? '';
    }

    /**
     * Set user registered
     *
     * @param string $userRegistered 0000-00-00 00:00:00
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
     * @return string Random string
     */
    public function getUserActivationKey(): string
    {
        return $this->userActivationKey ?? '';
    }

    /**
     * Set user activation key
     *
     * @param string $userActivationKey Random string
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
     * @return string John Doe
     */
    public function getDisplayName(): string
    {
        return $this->displayName ?? '';
    }

    /**
     * Set display name
     *
     * @param string $displayName John Doe
     * @return static
     */
    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Whether user exists in database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->getId() > 0;
    }

    // *************************************************************************

    /**
     * Get WP_User by field
     *
     * @param string $field
     * @param int|string $value
     * @return ?WP_User
     */
    protected static function getWpUserBy(string $field, int|string $value): ?WP_User
    {
        $object = WP_User::get_data_by($field, $value);

        if ($object === false) {
            return null;
        }

        $user = new WP_User();
        $user->init($object);

        return $user;
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

        if (!$wpUser = static::getWpUserBy(field: 'id', value: $id)) {
            return;
        }

        $this->loadFromWpUser($wpUser);
    }

    /**
     * Load instance from username
     *
     * @param string $username john.doe
     */
    protected function loadFromUsername(string $username): void
    {
        if (!$wpUser = static::getWpUserBy(field: 'login', value: $username)) {
            return;
        }

        $this->loadFromWpUser($wpUser);
    }

    /**
     * Load instance from slug
     *
     * @param string $slug john-doe
     */
    protected function loadFromSlug(string $slug): void
    {
        if (!$wpUser = static::getWpUserBy(field: 'slug', value: $slug)) {
            return;
        }

        $this->loadFromWpUser($wpUser);
    }

    /**
     * Load instance from email
     *
     * @param string $email john.doe@example.org
     */
    protected function loadFromEmail(string $email): void
    {
        if (!$wpUser = static::getWpUserBy(field: 'email', value: $email)) {
            return;
        }

        $this->loadFromWpUser($wpUser);
    }

    /**
     * Load instance from global WP_User
     *
     * @see wp_get_current_user()
     */
    protected function loadFromGlobalWpUser(): void
    {
        if (!$wpUser = wp_get_current_user()) {
            return;
        }

        if ($wpUser->ID === 0) {
            return;
        }

        $this->loadFromWpUser($wpUser);
    }

    /**
     * Load instance from WP_User
     *
     * @param WP_User $wpUser
     */
    protected function loadFromWpUser(WP_User $wpUser): void
    {
        $this->wpUser = $wpUser;

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
     * Cast user to array to be used by WordPress functions
     *
     * Remove keys from array if the value is null,
     * since that indicates no value has been set.
     *
     * @param array $includeData ['ID' => 1]
     * @return array ['ID' => 1, 'user_nicename' => 'charm']
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