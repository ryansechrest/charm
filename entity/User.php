<?php

namespace Charm\Entity;

use Charm\DataType\DateTime;
use Charm\Feature\Meta as MetaFeature;
use Charm\Module\Role as Role;
use Charm\WordPress\User as WpUser;

/**
 * Class User
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class User extends WpUser
{
    use MetaFeature;

    /************************************************************************************/
    // Constants

    /**
     * Meta class
     *
     * @var string
     */
    const META = 'Charm\Entity\UserMeta';

    /**
     * DateTime class
     *
     * @var string
     */
    const DATETIME = 'Charm\DataType\DateTime';

    /**
     * Role class
     *
     * @var string
     */
    const ROLE = 'Charm\Module\Role';

    /************************************************************************************/
    // Properties

    /**
     * Registration date object
     *
     * @var DateTime
     */
    protected $registration_date_obj = null;

    /**
     * Role object
     *
     * @var Role|null
     */
    protected $role_obj = null;

    /************************************************************************************/
    // Object access methods

    /**
     * Get user ID
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * Get registration date
     *
     * @return DateTime
     */
    public function registration_date(): DateTime
    {
        if ($this->registration_date_obj) {
            return $this->registration_date_obj;
        }

        return $this->registration_date_obj = call_user_func(
            static::DATETIME . '::init', $this->user_registered
        );
    }

    /**
     * Get role
     *
     * @return Role|null
     */
    public function role(): ?Role
    {
        if ($this->role_obj) {
            return $this->role_obj;
        }
        $wp_capabilities = $this->meta('wp_capabilities')->cast()->array();
        if (!is_array($wp_capabilities)) {
            return null;
        }
        $roles = array_keys($wp_capabilities);
        if (!isset($roles[0])) {
            return null;
        }

        return $this->role_obj = call_user_func(
            static::ROLE . '::init', $roles[0]
        );
    }

    /************************************************************************************/
    // Action methods

    /**
     * Create user with metas
     *
     * @return bool
     */
    public function create(): bool
    {
        if (!parent::create()) {
            return false;
        }
        $this->save_metas();

        return true;
    }

    /**
     * Update user with metas
     */
    public function update(): bool
    {
        if (!parent::update()) {
            return false;
        }
        $this->save_metas();

        return true;
    }

    /**
     * Can user read post?
     *
     * @param Post $post
     * @return bool
     */
    public function can_read(Post $post): bool
    {
        return $this->can_do('read', $post);
    }

    /**
     * Can user edit post?
     *
     * @param Post $post
     * @return bool
     */
    public function can_edit(Post $post): bool
    {
        return $this->can_do('edit', $post);
    }

    /**
     * Can user delete post?
     *
     * @param Post $post
     * @return bool
     */
    public function can_delete(Post $post): bool
    {
        return $this->can_do('delete', $post);
    }

    /**
     * Can user do action?
     *
     * @param string $action
     * @param Post $post
     * @return bool
     */
    public function can_do(string $action, Post $post): bool
    {
        $capability = $action . '_' . $post->get_post_type();

        return $this->can($capability, $post->get_id());
    }

    /**
     * Can user perform capability on post?
     *
     * @param $capability
     * @param mixed ...$args
     * @return bool
     */
    public function can($capability, ...$args): bool
    {
        return $this->wp_user()->has_cap($capability, ...$args);
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast instance to array
     *
     * @return array
     */
    public function to_array(): array
    {
        return array_merge(
            parent::to_array(),
            ['metas' => array_map(function($meta) {
                if (!is_array($meta)) {
                    return $meta->get_meta_value();
                }
                return array_map(function($meta) {
                    return $meta->get_meta_value();
                }, $meta);
            }, $this->meta())]
        );
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get best name
     *
     * @return string
     */
    public function get_best_name(): string
    {
        if ($this->get_full_name() !== '') {
            return $this->get_full_name();
        }
        if ($this->get_nickname() !== '') {
            return $this->get_nickname();
        }
        if ($this->get_display_name() !== '') {
            return $this->get_display_name();
        }

        return $this->get_user_login();
    }

    /**
     * Get full name
     *   i.e. first and last name separated by space
     *
     * @return string
     */
    public function get_full_name(): string
    {
        $name = [];
        if ($this->get_first_name() !== '') {
            $name[] = $this->get_first_name();
        }
        if ($this->get_last_name() !== '') {
            $name[] = $this->get_last_name();
        }

        return implode(' ', $name);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get nickname
     *
     * @return string
     */
    public function get_nickname(): string
    {
        return $this->meta('nickname')->cast()->string();
    }

    /**
     * Set nickname
     *
     * @param string $nickname
     */
    public function set_nickname(string $nickname): void
    {
        $this->meta('nickname', $nickname);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get first name
     *
     * @return string
     */
    public function get_first_name(): string
    {
        return $this->meta('first_name')->cast()->string();
    }

    /**
     * Set first_name
     *
     * @param string $first_name
     */
    public function set_first_name(string $first_name): void
    {
        $this->meta('first_name', $first_name);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get last name
     *
     * @return string
     */
    public function get_last_name(): string
    {
        return $this->meta('last_name')->cast()->string();
    }

    /**
     * Set last name
     *
     * @param string $last_name
     */
    public function set_last_name(string $last_name): void
    {
        $this->meta('last_name', $last_name);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get description
     *
     * @return string
     */
    public function get_description(): string
    {
        return $this->meta('description')->cast()->string();
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function set_description(string $description): void
    {
        $this->meta('description', $description);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get rich editing
     *
     * @return bool
     */
    public function get_rich_editing(): bool
    {
        return $this->meta('rich_editing')->cast()->bool();
    }

    /**
     * Set rich editing
     *
     * @param string $rich_editing
     */
    public function set_rich_editing(string $rich_editing)
    {
        $this->meta('rich_editing', $rich_editing);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get syntax highlighting
     *
     * @return bool
     */
    public function get_syntax_highlighting(): bool
    {
        return $this->meta('syntax_highlighting')->cast()->bool();
    }

    /**
     * Set syntax highlighting
     *
     * @param string $syntax_highlighting
     */
    public function set_syntax_highlighting(string $syntax_highlighting)
    {
        $this->meta('syntax_highlighting', $syntax_highlighting);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get comment shortcuts
     *
     * @return bool
     */
    public function get_comment_shortcuts(): bool
    {
        return $this->meta('comment_shortcuts')->cast()->bool();
    }

    /**
     * Set comment shortcuts
     *
     * @param string $comment_shortcuts
     */
    public function set_comment_shortcuts(string $comment_shortcuts)
    {
        $this->meta('comment_shortcuts', $comment_shortcuts);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get admin color
     *
     * @return string
     */
    public function get_admin_color(): string
    {
        return $this->meta('admin_color')->cast()->string();
    }

    /**
     * Set admin color
     *
     * @param string $admin_color
     */
    public function set_admin_color(string $admin_color): void
    {
        $this->meta('admin_color', $admin_color);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get use SSL
     *
     * @return bool
     */
    public function get_use_ssl(): bool
    {
        return $this->meta('use_ssl')->cast()->bool();
    }

    /**
     * Set use SSL
     *
     * @param int $use_ssl
     */
    public function set_use_ssl(int $use_ssl): void
    {
        $this->meta('use_ssl', $use_ssl);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get show admin bar front
     *
     * @return bool
     */
    public function get_show_admin_bar_front(): bool
    {
        return $this->meta('show_admin_bar_front')->cast()->bool();
    }

    /**
     * Set show admin bar front
     *
     * @param string $show_admin_bar_front
     */
    public function set_show_admin_bar_front(string $show_admin_bar_front)
    {
        $this->meta('show_admin_bar_front', $show_admin_bar_front);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get locale
     *
     * @return string
     */
    public function get_locale(): string
    {
        return $this->meta('locale')->cast()->string();
    }

    /**
     * Set locale
     *
     * @param string $locale
     */
    public function set_locale(string $locale): void
    {
        $this->meta('locale', $locale);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get WP capabilities
     *
     * @return array
     */
    public function get_wp_capabilities(): array
    {
        return $this->meta('wp_capabilities')->cast()->array();
    }

    /**
     * Add WP capability
     *
     * @param string $wp_capability
     */
    public function add_wp_capability(string $wp_capability): void
    {
        $this->meta('wp_capabilities')->add($wp_capability);
    }

    /**
     * Set WP capabilities
     *
     * @param array $wp_capabilities
     */
    public function set_wp_capabilities(array $wp_capabilities): void
    {
        $this->meta('wp_capabilities', $wp_capabilities);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get WP user level
     *
     * @return int
     */
    public function get_wp_user_level(): int
    {
        return $this->meta('wp_user_level')->cast()->int();
    }

    /**
     * Set WP user level
     *
     * @param int $wp_user_level
     */
    public function set_wp_user_level(int $wp_user_level): void
    {
        $this->meta('wp_user_level', $wp_user_level);
    }
}