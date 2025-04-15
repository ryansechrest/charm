<?php

namespace Charm\Models\WordPress;

use Charm\Support\Result;
use WP_User;

/**
 * Represents a user in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class User
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

    /**************************************************************************/

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
            $this->id = (int) $data['id'];
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

    /**************************************************************************/

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

        return !$user->getId() ? null : $user;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Load instance from ID
     *
     * @param int $id
     */
    protected function loadFromId(int $id): void
    {
        if (!$user = static::getBy('id', $id)) {
            return;
        }

        $this->loadFromUser($user);
    }

    /**
     * Load instance from login
     *
     * @param string $login
     */
    protected function loadFromLogin(string $login): void
    {
        if (!$user = static::getBy('login', $login)) {
            return;
        }

        $this->loadFromUser($user);
    }

    /**
     * Load instance from email
     *
     * @param string $email
     */
    protected function loadFromEmail(string $email): void
    {
        if (!$user = static::getBy('email', $email)) {
            return;
        }

        $this->loadFromUser($user);
    }

    /**
     * Load instance from global WP_User
     *
     * @see wp_get_current_user()
     */
    protected function loadFromGlobalUser(): void
    {
        if (!$user = wp_get_current_user()) {
            return;
        }

        if ($user->ID === 0) {
            return;
        }

        $this->loadFromUser($user);
    }

    /**
     * Load instance from WP_User object
     *
     * @param WP_User $wpUser
     * @see WP_User
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

    /**
     * Get WP_User by field
     *
     * @param string $field
     * @param int|string $value
     * @return ?WP_User
     */
    private function getBy(string $field, int|string $value): ?WP_User
    {
        $object = WP_User::get_data_by($field, $value);

        if ($object === false) {
            return null;
        }

        $user = new WP_User();
        $user->init($object);

        return $user;
    }

    /**
     * Cast user to array to be used by WordPress functions
     *
     * Remove keys from array if the value is null,
     * since that indicates no value has been set.
     *
     * @return array
     */
    private function toWpUserArray(): array
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

        return array_filter($data, fn($value) => !is_null($value));
    }

    /**************************************************************************/

    /**
     * Save user
     *
     * @return Result
     */
    public function save(): Result
    {
        return !$this->id ? $this->create() : $this->update();
    }

    /**
     * Create new user
     *
     * @return Result
     * @see wp_insert_user()
     */
    public function create(): Result
    {
        $result = wp_insert_user($this->toWpUserArray());

        // WordPress successfully created the user
        if (is_int($result)) {
            $this->id = $result;
            return Result::success();
        }

        // WordPress failed to create the user
        if (is_wp_error($result)) {
            return Result::wpError($result);
        }

        // WordPress returned something unexpected
        return Result::error(
            'wp_insert_user_failed',
            __('wp_insert_user() did not return integer or WP_Error.', 'charm')
        );
    }

    /**
     * Update existing user
     *
     * @return Result
     * @see wp_update_user()
     */
    public function update(): Result
    {
        $result = wp_update_user($this->toWpUserArray());

        // WordPress successfully updated the user
        if (is_int($result)) {
            return Result::success();
        }

        // WordPress failed to update the user
        if (is_wp_error($result)) {
            return Result::wpError($result);
        }

        // WordPress returned something unexpected
        return Result::error(
            'wp_update_user_failed',
            __('wp_update_user() did not return integer or WP_Error.', 'charm')
        );
    }

    /**
     * Delete user
     *
     * @return Result
     * @see wp_delete_user()
     */
    public function delete(): Result
    {
        $result = wp_delete_user($this->id);

        // WordPress successfully deleted the user
        if ($result === true) {
            return Result::success();
        }

        // WordPress failed to delete the user
        if ($result === false) {
            return Result::error(
                'wp_delete_user_failed',
                __('wp_delete_user() return false.', 'charm')
            );
        }

        // WordPress failed to delete the user
        return Result::error(
            'wp_delete_user_failed',
            __('wp_delete_user() returned something unexpected.', 'charm')
        );
    }

    /**************************************************************************/

    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id ?? 0;
    }

    /**
     * Set ID
     *
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /*------------------------------------------------------------------------*/

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
     */
    public function setUserLogin(string $userLogin): void
    {
        $this->userLogin = $userLogin;
    }

    /*------------------------------------------------------------------------*/

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
     */
    public function setUserPass(string $userPass): void
    {
        $this->userPass = $userPass;
    }

    /*------------------------------------------------------------------------*/

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
     */
    public function setUserNicename(string $userNicename): void
    {
        $this->userNicename = $userNicename;
    }

    /*------------------------------------------------------------------------*/

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
     */
    public function setUserEmail(string $userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    /*------------------------------------------------------------------------*/

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
     */
    public function setUserUrl(string $userUrl): void
    {
        $this->userUrl = $userUrl;
    }

    /*------------------------------------------------------------------------*/

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
     */
    public function setUserRegistered(string $userRegistered): void
    {
        $this->userRegistered = $userRegistered;
    }

    /*------------------------------------------------------------------------*/

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
     */
    public function setUserActivationKey(string $userActivationKey): void
    {
        $this->userActivationKey = $userActivationKey;
    }

    /*------------------------------------------------------------------------*/

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
     */
    public function setUserStatus(int $userStatus): void
    {
        $this->userStatus = $userStatus;
    }

    /*------------------------------------------------------------------------*/

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
     */
    public function setDisplayName(string $displayName): void
    {
        $this->displayName = $displayName;
    }
}