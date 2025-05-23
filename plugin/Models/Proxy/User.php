<?php

namespace Charm\Models\Proxy;

use Charm\Contracts\IsArrayable;
use Charm\Contracts\IsPersistable;
use Charm\Contracts\WordPress\HasWpUser;
use Charm\Enums\Result\Message;
use Charm\Support\Filter;
use Charm\Support\Result;
use Charm\Traits\WithToArray;
use WP_User;
use WP_User_Query;

/**
 * Represents a proxy user in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class User implements HasWpUser, IsArrayable, IsPersistable
{
    use WithToArray;

    // *************************************************************************

    /**
     * User ID.
     *
     * @var ?int
     */
    protected ?int $id = null;

    /**
     * User login.
     *
     * @var ?string
     */
    protected ?string $userLogin = null;

    /**
     * User password.
     *
     * @var ?string
     */
    protected ?string $userPass = null;

    /**
     * User nicename (slug).
     *
     * @var ?string
     */
    protected ?string $userNicename = null;

    /**
     * User email address.
     *
     * @var ?string
     */
    protected ?string $userEmail = null;

    /**
     * User website URL.
     *
     * @var ?string
     */
    protected ?string $userUrl = null;

    /**
     * User registered date and time.
     *
     * @var ?string
     */
    protected ?string $userRegistered = null;

    /**
     * User activation key.
     *
     * @var ?string
     */
    protected ?string $userActivationKey = null;

    /**
     * User status.
     *
     * @var ?int
     */
    protected ?int $userStatus = null;

    /**
     * User display name.
     *
     * @var ?string
     */
    protected ?string $displayName = null;

    // -------------------------------------------------------------------------

    /**
     * `WP_Use`r instance.
     *
     * @var ?WP_User
     */
    protected ?WP_User $wpUser = null;

    // *************************************************************************

    /**
     * User constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
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
     * Access the `WP_User` instance.
     *
     * @return ?WP_User
     */
    public function wpUser(): ?WP_User
    {
        return $this->wpUser;
    }

    // *************************************************************************

    /**
     * Initialize the user from an ID.
     *
     * From: `wp_user` -> `ID`
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
     * Initialize the user from a username.
     *
     * From: `wp_user` -> `user_login`
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
     * Initialize the user from a slug.
     *
     * From: `wp_user` -> `user_nicename`
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
     * Initialize the user from an email address.
     *
     * From: `wp_user` -> `user_email`
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
     * Initialize the user from the global `WP_User` instance.
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
     * Initialize the user from a `WP_User` instance.
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
     * Get the users.
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

        return array_map(
            fn(WP_User $wpUser) => static::fromWpUser($wpUser),
            $wpUserQuery->get_results()
        );
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
        return new WP_User_Query(query: $args);
    }

    // -------------------------------------------------------------------------

    /**
     * Create a user in the database.
     *
     * @param array $data
     * @return Result
     * @see wp_insert_user()
     * @see is_wp_error()
     */
    public static function createUser(array $data): Result
    {
        // `int`    -> User ID    -> Success: User created
        // `object` -> `WP_Error` -> Fail: User not created
        $result = wp_insert_user(userdata: $data);

        if (is_wp_error($result)) {
            return Result::error(
                'user_create_failed',
                'User could not be created. `wp_insert_user()` returned a `WP_Error` object.'
            )->withReturn($result)->withData($data)->withWpError($result);
        }

        if (!is_int($result)) {
            return Result::error(
                'user_create_failed',
                'User could not be created. Expected `wp_insert_post()` to return a user ID, but received an unexpected result.'
            )->withReturn($result)->withData($data);
        }

        return Result::success(
            'user_create_success',
            'User successfully created.'
        )->withId($result)->withReturn($result)->withData($data);
    }

    /**
     * Update a user in the database.
     *
     * @param array $data
     * @return Result
     * @see wp_update_user()
     * @see is_wp_error()
     */
    public static function updateUser(array $data): Result
    {
        // `int`    -> User ID    -> Success: User created
        // `object` -> `WP_Error` -> Fail: User not created
        $result = wp_update_user(userdata: $data);

        if (is_wp_error($result)) {
            return Result::error(
                'user_update_failed',
                'User could not be updated. `wp_update_user()` returned a `WP_Error` object.'
            )->withReturn($result)->withData($data)->withWpError($result);
        }

        if (!is_int($result)) {
            return Result::error(
                'user_update_failed',
                'User could not be updated. Expected `wp_update_user()` to return a user ID, but received an unexpected result.'
            )->withReturn($result)->withData($data);
        }

        return Result::success(
            'user_update_success',
            'User successfully updated.'
        )->withId($result)->withReturn($result)->withData($data);
    }

    /**
     * Delete a user from the database.
     *
     * @param int $id
     * @return Result
     * @see wp_delete_user()
     */
    public static function deleteUser(int $id): Result
    {
        // `bool` -> `true`  -> Success: User deleted
        // `bool` -> `false` -> Fail: User not deleted
        $result = wp_delete_user(id: $id);

        if ($result === false) {
            return Result::error(
                'user_delete_failed',
                'User could not be deleted. `wp_delete_user()` returned `false`.'
            )->withId($id)->withReturn($result);
        }

        if ($result !== true) {
            return Result::error(
                'user_delete_failed',
                'User could not be deleted. Expected `wp_delete_user()` to return `true`, but received an unexpected result.'
            )->withId($id)->withReturn($result);
        }

        return Result::success(
            'user_delete_success',
            'User successfully deleted.'
        )->withId($id)->withReturn($result);
    }

    // *************************************************************************

    /**
     * Save the user in the database.
     *
     * @return Result
     */
    public function save(): Result
    {
        return $this->id === null ? $this->create() : $this->update();
    }

    /**
     * Create the user in the database.
     *
     * @return Result
     * @see wp_insert_user()
     * @see is_wp_error()
     */
    public function create(): Result
    {
        if ($this->id !== null) {
            return Result::error(
                'user_already_exists',
                'User was not created because they already exist.'
            )->withId($this->id)->withData($this->toArray());
        }

        $result = static::createUser(
            data: $this->toWpUserArray(except: ['ID'])
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $this->id = $result->getId();
        $this->reload();

        return $result;
    }

    /**
     * Update the user in the database.
     *
     * @return Result
     */
    public function update(): Result
    {
        if ($this->id === null) {
            return Result::error(
                'user_not_found',
                'User was not updated because they do not exist.'
            )->withData($this->toArray());
        }

        $result = static::updateUser(data: $this->toWpUserArray());

        if ($result->hasFailed()) {
            return $result;
        }

        $this->reload();

        return $result;
    }

    /**
     * Delete the user from the database.
     *
     * @return Result
     */
    public function delete(): Result
    {
        if ($this->id === null) {
            return Result::error(
                'user_not_found',
                'User was not deleted because they do not exist.'
            )->withData($this->toArray());
        }

        $result = static::deleteUser(id: $this->id);

        if ($result->hasFailed()) {
            return $result;
        }

        $this->id = null;

        return $result;
    }

    // *************************************************************************

    /**
     * Get the user ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id ?? 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the user login.
     *
     * @return string john.doe
     */
    public function getUserLogin(): string
    {
        return $this->userLogin ?? '';
    }

    /**
     * Set the user login.
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
     * Get the user password.
     *
     * @return string Hashed password
     */
    public function getUserPass(): string
    {
        return $this->userPass ?? '';
    }

    /**
     * Set the user password.
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
     * Get the user nicename (slug).
     *
     * @return string john-doe
     */
    public function getUserNicename(): string
    {
        return $this->userNicename ?? '';
    }

    /**
     * Set the user nicename (slug).
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
     * Get the user email address.
     *
     * @return string john.doe@example.org
     */
    public function getUserEmail(): string
    {
        return $this->userEmail ?? '';
    }

    /**
     * Set the user email address.
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
     * Get the user website URL.
     *
     * @return string https://example.org
     */
    public function getUserUrl(): string
    {
        return $this->userUrl ?? '';
    }

    /**
     * Set the user website URL.
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
     * Get the user registered date and time.
     *
     * @return string 0000-00-00 00:00:00
     */
    public function getUserRegistered(): string
    {
        return $this->userRegistered ?? '';
    }

    /**
     * Set the user registered date and time.
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
     * Get the user activation key.
     *
     * @return string Random string
     */
    public function getUserActivationKey(): string
    {
        return $this->userActivationKey ?? '';
    }

    /**
     * Set the user activation key.
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
     * Get the user status.
     *
     * @return int
     */
    public function getUserStatus(): int
    {
        return $this->userStatus ?? 0;
    }

    /**
     * Set the user status.
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
     * Get the display name.
     *
     * @return string John Doe
     */
    public function getDisplayName(): string
    {
        return $this->displayName ?? '';
    }

    /**
     * Set the display name.
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
     * Check whether the user exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->getId() > 0;
    }

    // *************************************************************************

    /**
     * Get a `WP_User` by the specified database column.
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
     * Load the instance from an ID.
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
     * Load the instance from a username.
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
     * Load the instance from a slug.
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
     * Load the instance from an email address.
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
     * Load the instance from the global `WP_User` instance.
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
     * Load the instance from a `WP_User` instance.
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
     * Reload the instance from the database.
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
     * Cast the user to an array to be used by WordPress functions.
     *
     * Remove keys from the array if the value is null, since that indicates
     *  that no value has been set.
     *
     * @param array $except ['ID']
     * @return array ['user_login' => 'charm', ...]
     */
    protected function toWpUserArray(array $except = []): array
    {
        $data = [
            'ID' => $this->id,
            'user_login' => $this->userLogin,
            'user_pass' => $this->userPass,
            'user_nicename' => $this->userNicename,
            'user_email' => $this->userEmail,
            'user_url' => $this->userUrl,
            'user_registered' => $this->userRegistered,
            'user_activation_key' => $this->userActivationKey,
            'user_status' => $this->userStatus,
            'display_name' => $this->displayName,
        ];

        return Filter::array($data)->except($except)->withoutNulls()->get();
    }
}