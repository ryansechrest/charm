<?php

namespace Charm\App;

use Charm\App\Core\Entity;
use Charm\App\DataType\DateTime;
use Charm\WordPress\Post as WpPost;
use WP_Post;

/**
 * Class Post
 *
 * @author Ryan Sechrest
 * @package Charm\App
 */
class Post extends Entity
{
    /**
     * WordPress post
     *
     * @var WpPost
     */
    protected $wp_post = null;

    /**
     * Post user
     *
     * @var User
     */
    protected $user = null;

    /**
     * Post parent
     *
     * @var Post
     */
    protected $parent = null;

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize post
     *
     * @param int|null|string|WP_Post $key
     * @return null|Post
     */
    public static function init($key = null)
    {
        $data = [];
        $data['wp_post'] = WpPost::init($key);
        if ($data['wp_post'] === null) {
            return null;
        }
        if ($data['wp_post']->get_post_type() !== 'post') {
            return null;
        }
        if ($user_id = $data['wp_post']->get_post_author()) {
            $data['user'] = User::init($user_id);
        }
        if ($parent_id = $data['wp_post']->get_post_parent()) {
            $data['parent'] = Post::init($parent_id);
        }

        return new Post($data);
    }

    /**
     * Get posts
     *
     * @todo Implement Post::get()
     * @param array $params
     * @return Post[]
     */
    public static function get(array $params): array
    {
        return [];
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save post
     *
     * @todo Implement Post->save()
     * @return bool
     */
    public function save(): bool
    {
        return false;
    }

    /**
     * Create post
     *
     * @todo Implement Post->create()
     * @return bool
     */
    public function create(): bool
    {
        return false;
    }

    /**
     * Update post
     *
     * @todo Implement Post->update()
     * @return bool
     */
    public function update(): bool
    {
        return false;
    }

    /**
     * Move post to trash
     *
     * @todo Implement Post->trash()
     * @return bool
     */
    public function trash(): bool
    {
        return false;
    }

    /**
     * Restore post from trash
     *
     * @todo Implement Post->restore()
     * @return bool
     */
    public function restore(): bool
    {
        return false;
    }

    /**
     * Delete post
     *
     * @todo Implement Post->delete()
     * @return bool
     */
    public function delete(): bool
    {
        return false;
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get WordPress post
     *
     * @return WpPost
     */
    public function wp_post(): WpPost
    {
        return $this->wp_post;
    }

    /**
     * Get post user
     *
     * @return User
     */
    public function user(): User
    {
        return $this->user;
    }

    /**
     * Get post parent
     *
     * @return null|Post
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * Get post meta
     *
     * @param string $key
     * @return Meta|Meta[]
     */
    public function meta(string $key)
    {
        $wp_meta = $this->wp_post->meta($key);
        if (!is_array($wp_meta)) {
            return new Meta(['wp_meta' => $wp_meta]);
        }
        $metas = [];
        foreach ($wp_meta as $meta) {
            $metas[] = new Meta(['wp_meta' => $meta]);
        }

        return $metas;
    }

    /**
     * Get created ate
     *
     * @return DateTime
     */
    public function created_date(): DateTime
    {
        return $this->wp_post->get_post_date();
    }

    /**
     * Get created date (GMT)
     *
     * @return DateTime
     */
    public function created_date_gmt(): DateTime
    {
        return $this->wp_post->get_post_date_gmt();
    }

    /**
     * Get updated date
     *
     * @return DateTime
     */
    public function updated_date(): DateTime
    {
        return $this->wp_post->get_post_modified();
    }

    /**
     * Get updated date (GMT)
     *
     * @return DateTime
     */
    public function updated_date_gmt(): DateTime
    {
        return $this->wp_post->get_post_modified_gmt();
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get ID
     *
     * @return int
     */
    public function get_id(): int
    {
        return $this->wp_post->get_id();
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get GUID
     *
     * @return string
     */
    public function get_guid(): string
    {
        return $this->wp_post->get_guid();
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get title
     *
     * @return string
     */
    public function get_title(): string
    {
        return $this->wp_post->get_post_title();
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function set_title(string $title): void
    {
        $this->wp_post->set_post_title($title);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get slug
     *
     * @return string
     */
    public function get_slug(): string
    {
        return $this->wp_post->get_post_name();
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function set_slug(string $slug): void
    {
        $this->wp_post->set_post_name($slug);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get content
     *
     * @return string
     */
    public function get_content(): string
    {
        return $this->wp_post->get_post_content();
    }

    /**
     * Set content
     *
     * @param string $content
     */
    public function set_content(string $content): void
    {
        $this->wp_post->set_post_content($content);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get filtered content
     *
     * @return string
     */
    public function get_filtered_content(): string
    {
        return $this->wp_post->get_post_content_filtered();
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get excerpt
     *
     * @return string
     */
    public function get_excerpt(): string
    {
        return $this->wp_post->get_post_excerpt();
    }

    /**
     * Set excerpt
     *
     * @param string $excerpt
     */
    public function set_excerpt(string $excerpt): void
    {
        $this->wp_post->set_post_excerpt($excerpt);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get password
     *
     * @return string
     */
    public function get_password(): string
    {
        return $this->wp_post->get_post_password();
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function set_password(string $password): void
    {
        $this->wp_post->set_post_password($password);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get status
     *
     * @return string
     */
    public function get_status(): string
    {
        return $this->wp_post->get_post_status();
    }

    /**
     * Set status
     *
     * @param string $status
     */
    public function set_status(string $status): void
    {
        $this->wp_post->set_post_status($status);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get comment status
     *
     * @return string
     */
    public function get_comment_status(): string
    {
        return $this->wp_post->get_comment_status();
    }

    /**
     * Set comment status
     *
     * @param string $comment_status
     */
    public function set_comment_status(string $comment_status): void
    {
        $this->wp_post->set_comment_status($comment_status);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get comment count
     *
     * @return int
     */
    public function get_comment_count(): int
    {
        return $this->wp_post->get_comment_count();
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get ping status
     *
     * @return string
     */
    public function get_ping_status(): string
    {
        return $this->wp_post->get_ping_status();
    }

    /**
     * Set ping status
     *
     * @param string $ping_status
     */
    public function set_ping_status(string $ping_status): void
    {
        $this->wp_post->set_ping_status($ping_status);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get URLs to ping
     *
     * @return string
     */
    public function get_to_ping(): string
    {
        return $this->wp_post->get_to_ping();
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get URLs already pinged
     *
     * @return string
     */
    public function get_pinged(): string
    {
        return $this->wp_post->get_pinged();
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get menu order
     *
     * @return int
     */
    public function get_menu_order(): int
    {
        return $this->wp_post->get_menu_order();
    }

    /**
     * Set menu order
     *
     * @param int $menu_order
     */
    public function set_menu_order(int $menu_order): void
    {
        $this->wp_post->set_menu_order($menu_order);
    }
}