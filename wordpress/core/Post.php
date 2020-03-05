<?php

namespace Charm\WordPress\Core;

use WP_Post,
    WP_Query;

/**
 * Class Post
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Core
 */
class Post
{
    /**
     * ID
     *
     * @var int
     */
    private $id;

    /**
     * Post author
     *
     * @var int
     */
    private $post_author;

    /**
     * Post date
     *
     * @var string
     */
    private $post_date;

    /**
     * Post date (GMT)
     *
     * @var string
     */
    private $post_date_gmt;

    /**
     * Post content
     *
     * @var string
     */
    private $post_content;

    /**
     * Post title
     *
     * @var string
     */
    private $post_title;

    /**
     * Post excerpt
     *
     * @var string
     */
    private $post_excerpt;

    /**
     * Post status
     *
     * @var string
     */
    private $post_status;

    /**
     * Comment status
     *
     * @var string
     */
    private $comment_status;

    /**
     * Ping status
     *
     * @var string
     */
    private $ping_status;

    /**
     * Post password
     *
     * @var string
     */
    private $post_password;

    /**
     * Post name
     *
     * @var string
     */
    private $post_name;

    /**
     * URLs to ping
     *
     * @var string
     */
    private $to_ping;

    /**
     * URLs pinged
     *
     * @var string
     */
    private $pinged;

    /**
     * Post modified
     *
     * @var string
     */
    private $post_modified;

    /**
     * Post modified (GMT)
     *
     * @var string
     */
    private $post_modified_gmt;

    /**
     * Filtered post content
     *
     * @var string
     */
    private $post_content_filtered;

    /**
     * Post parent
     *
     * @var int
     */
    private $post_parent;

    /**
     * GUID
     *
     * @var string
     */
    private $guid;

    /**
     * Menu order
     *
     * @var int
     */
    private $menu_order;

    /**
     * Post type
     *
     * @var string
     */
    private $post_type;

    /**
     * Post mime type
     *
     * @var string
     */
    private $post_mime_type;

    /**
     * Comment count
     *
     * @var int
     */
    private $comment_count;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Default constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (!is_array($data)) {
            return;
        }
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['post_author'])) {
            $this->post_author = $data['post_author'];
        }
        if (isset($data['post_date'])) {
            $this->post_date = $data['post_date'];
        }
        if (isset($data['post_date_gmt'])) {
            $this->post_date_gmt = $data['post_date_gmt'];
        }
        if (isset($data['post_content'])) {
            $this->post_content = $data['post_content'];
        }
        if (isset($data['post_title'])) {
            $this->post_title = $data['post_title'];
        }
        if (isset($data['post_excerpt'])) {
            $this->post_excerpt = $data['post_excerpt'];
        }
        if (isset($data['post_status'])) {
            $this->post_status = $data['post_status'];
        }
        if (isset($data['comment_status'])) {
            $this->comment_status = $data['comment_status'];
        }
        if (isset($data['ping_status'])) {
            $this->ping_status = $data['ping_status'];
        }
        if (isset($data['post_password'])) {
            $this->post_password = $data['post_password'];
        }
        if (isset($data['post_name'])) {
            $this->post_name = $data['post_name'];
        }
        if (isset($data['to_ping'])) {
            $this->to_ping = $data['to_ping'];
        }
        if (isset($data['pinged'])) {
            $this->pinged = $data['pinged'];
        }
        if (isset($data['post_modified'])) {
            $this->post_modified = $data['post_modified'];
        }
        if (isset($data['post_modified_gmt'])) {
            $this->post_modified_gmt = $data['post_modified_gmt'];
        }
        if (isset($data['post_content_filtered'])) {
            $this->post_content_filtered = $data['post_content_filtered'];
        }
        if (isset($data['post_parent'])) {
            $this->post_parent = $data['post_parent'];
        }
        if (isset($data['guid'])) {
            $this->guid = $data['guid'];
        }
        if (isset($data['menu_order'])) {
            $this->menu_order = $data['menu_order'];
        }
        if (isset($data['post_type'])) {
            $this->post_type = $data['post_type'];
        }
        if (isset($data['post_mime_type'])) {
            $this->post_mime_type = $data['post_mime_type'];
        }
        if (isset($data['comment_count'])) {
            $this->comment_count = $data['comment_count'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize post
     *
     * @see WP_Post
     * @param int|null|string|WP_Post $key
     * @return null|Post
     */
    public static function init($key = null)
    {
        $child = get_called_class();
        $post = new $child();
        if (is_int($key) || ctype_digit($key)) {
            $post->load_from_id($key);
        } elseif (is_string($key)) {
            $post->load_from_path($key);
        } elseif (is_object($key) && get_class($key) === 'WP_Post') {
            $post->load_from_post($key);
        } else {
            $post->load_from_global_post();
        }
        if ($post->get_id() === 0) {
            return null;
        }

        return $post;
    }

    /**
     * Get posts
     *
     * @see WP_Post
     * @see WP_Query
     * @param array $params
     * @return Post[]
     */
    public static function get(array $params)
    {
        $posts = [];
        $query = new WP_Query($params);
        if (!$query->found_posts) {
            return $posts;
        }

        return array_map(function(WP_Post $post) {
            $child = get_called_class();
            return $child::init($post);
        }, $query->posts);
    }

    /************************************************************************************/
    // Private load methods

    /**
     * Load instance from ID
     *
     * @see get_post()
     * @param int $id
     */
    private function load_from_id(int $id)
    {
        $this->load_from_post(get_post($id));
    }

    /**
     * Load instance from path
     *
     * @see get_page_by_path()
     * @param string $path
     */
    private function load_from_path(string $path)
    {
        $this->load_from_post(get_page_by_path($path));
    }

    /**
     * Load instance from WP_Post object
     *
     * @see WP_Post
     * @param WP_Post $post
     */
    private function load_from_post(WP_Post $post)
    {
        if (!is_object($post)) {
            return;
        }
        if (get_class($post) !== 'WP_Post') {
            return;
        }
        $this->id = $post->ID;
        $this->post_author = $post->post_author;
        $this->post_date = $post->post_date;
        $this->post_date_gmt = $post->post_date_gmt;
        $this->post_content = $post->post_content;
        $this->post_title = $post->post_title;
        $this->post_excerpt = $post->post_excerpt;
        $this->post_status = $post->post_status;
        $this->comment_status = $post->comment_status;
        $this->ping_status = $post->ping_status;
        $this->post_password = $post->post_password;
        $this->post_name = $post->post_name;
        $this->to_ping = $post->to_ping;
        $this->pinged = $post->pinged;
        $this->post_modified = $post->post_modified;
        $this->post_modified_gmt = $post->post_modified_gmt;
        $this->post_content_filtered = $post->post_content_filtered;
        $this->post_parent = $post->post_parent;
        $this->guid = $post->guid;
        $this->menu_order = $post->menu_order;
        $this->post_type = $post->post_type;
        $this->post_mime_type = $post->post_mime_type;
        $this->comment_count = $post->comment_count;
    }

    /**
     * Load instance from global WP_Post object
     *
     * @see get_post()
     */
    private function load_from_global_post()
    {
        $this->load_from_post(get_post());
    }

    /**
     * Reload instance from database
     */
    private function reload()
    {
        if (!$this->id) {
            return;
        }
        $this->load_from_id($this->id);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save post
     *
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->id) {
            return $this->create();
        }

        return $this->update();
    }

    /**
     * Create new post
     *
     * @see wp_insert_post()
     * @return bool
     */
    public function create(): bool
    {
        if (!$id = wp_insert_post($this->to_array())) {
            return false;
        }
        $this->id = $id;
        $this->reload();

        return true;
    }

    /**
     * Update existing post
     *
     * @see wp_update_post()
     * @return bool
     */
    public function update(): bool
    {
        if (!$id = wp_update_post($this->to_array())) {
            return false;
        }
        $this->reload();

        return true;
    }

    /**
     * Move post to trash
     *
     * @see wp_delete_post()
     * @return bool
     */
    public function trash(): bool
    {
        if (!wp_delete_post($this->id)) {
            return false;
        }

        return true;
    }

    /**
     * Restore post from trash
     *
     * @see wp_untrash_post()
     * @return bool
     */
    public function restore(): bool
    {
        if (!wp_untrash_post($this->id)) {
            return false;
        }

        return true;
    }

    /**
     * Delete post permanently
     *
     * @see wp_delete_post()
     * @return bool
     */
    public function delete(): bool
    {
        if (!wp_delete_post($this->id, true)) {
            return false;
        }

        return true;
    }

    /************************************************************************************/
    // Conversion methods

    /**
     * Convert instance to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = [];
        if ($this->id !== null) {
            $data['id'] = $this->id;
        }
        if ($this->post_author !== null) {
            $data['post_author'] = $this->post_author;
        }
        if ($this->post_date !== null) {
            $data['post_date'] = $this->post_date;
        }
        if ($this->post_date_gmt !== null) {
            $data['post_date_gmt'] = $this->post_date_gmt;
        }
        if ($this->post_content !== null) {
            $data['post_content'] = $this->post_content;
        }
        if ($this->post_title !== null) {
            $data['post_title'] = $this->post_title;
        }
        if ($this->post_excerpt !== null) {
            $data['post_excerpt'] = $this->post_excerpt;
        }
        if ($this->post_status !== null) {
            $data['post_status'] = $this->post_status;
        }
        if ($this->comment_status !== null) {
            $data['comment_status'] = $this->comment_status;
        }
        if ($this->ping_status !== null) {
            $data['ping_status'] = $this->ping_status;
        }
        if ($this->post_password !== null) {
            $data['post_password'] = $this->post_password;
        }
        if ($this->post_name !== null) {
            $data['post_name'] = $this->post_name;
        }
        if ($this->to_ping !== null) {
            $data['to_ping'] = $this->to_ping;
        }
        if ($this->pinged !== null) {
            $data['pinged'] = $this->pinged;
        }
        if ($this->post_modified !== null) {
            $data['post_modified'] = $this->post_modified;
        }
        if ($this->post_modified_gmt !== null) {
            $data['post_modified_gmt'] = $this->post_modified_gmt;
        }
        if ($this->post_content_filtered !== null) {
            $data['post_content_filtered'] = $this->post_content_filtered;
        }
        if ($this->post_parent !== null) {
            $data['post_parent'] = $this->post_parent;
        }
        if ($this->guid !== null) {
            $data['guid'] = $this->guid;
        }
        if ($this->menu_order !== null) {
            $data['menu_order'] = $this->menu_order;
        }
        if ($this->post_type !== null) {
            $data['post_type'] = $this->post_type;
        }
        if ($this->post_mime_type !== null) {
            $data['post_mime_type'] = $this->post_mime_type;
        }
        if ($this->comment_count !== null) {
            $data['comment_count'] = $this->comment_count;
        }

        return $data;
    }

    /**
     * Convert instance to JSON
     *
     * @return string
     */
    public function to_json(): string
    {
        return json_encode($this->to_array());
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
        return $this->id;
    }

    /**
     * Set ID
     *
     * @param int $id
     */
    public function set_id(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get post author
     *
     * @return int
     */
    public function get_post_author(): int
    {
        return $this->post_author;
    }

    /**
     * Set post author
     *
     * @param int $post_author
     */
    public function set_post_author(int $post_author)
    {
        $this->post_author = $post_author;
    }

    /**
     * Get post date
     *
     * @return string
     */
    public function get_post_date(): string
    {
        return $this->post_date;
    }

    /**
     * Set post date
     *
     * @param string $post_date
     */
    public function set_post_date(string $post_date)
    {
        $this->post_date = $post_date;
    }

    /**
     * Get post date (GMT)
     *
     * @return string
     */
    public function get_post_date_gmt(): string
    {
        return $this->post_date_gmt;
    }

    /**
     * Set post date (GMT)
     *
     * @param string $post_date_gmt
     */
    public function set_post_date_gmt(string $post_date_gmt)
    {
        $this->post_date_gmt = $post_date_gmt;
    }

    /**
     * Get post content
     *
     * @return string
     */
    public function get_post_content(): string
    {
        return $this->post_content;
    }

    /**
     * Set post content
     *
     * @param string $post_content
     */
    public function set_post_content(string $post_content)
    {
        $this->post_content = $post_content;
    }

    /**
     * Get post title
     *
     * @return string
     */
    public function get_post_title(): string
    {
        return $this->post_title;
    }

    /**
     * Set post title
     *
     * @param string $post_title
     */
    public function set_post_title(string $post_title)
    {
        $this->post_title = $post_title;
    }

    /**
     * Get post excerpt
     *
     * @return string
     */
    public function get_post_excerpt(): string
    {
        return $this->post_excerpt;
    }

    /**
     * Set post excerpt
     *
     * @param string $post_excerpt
     */
    public function set_post_excerpt(string $post_excerpt)
    {
        $this->post_excerpt = $post_excerpt;
    }

    /**
     * Get post status
     *
     * @return string
     */
    public function get_post_status(): string
    {
        return $this->post_status;
    }

    /**
     * Set post status
     *
     * @param string $post_status
     */
    public function set_post_status(string $post_status)
    {
        $this->post_status = $post_status;
    }

    /**
     * Get comment status
     *
     * @return string
     */
    public function get_comment_status(): string
    {
        return $this->comment_status;
    }

    /**
     * Set comment status
     *
     * @param string $comment_status
     */
    public function set_comment_status(string $comment_status)
    {
        $this->comment_status = $comment_status;
    }

    /**
     * Get ping status
     *
     * @return string
     */
    public function get_ping_status(): string
    {
        return $this->ping_status;
    }

    /**
     * Set ping status
     *
     * @param string $ping_status
     */
    public function set_ping_status(string $ping_status)
    {
        $this->ping_status = $ping_status;
    }

    /**
     * Get post password
     *
     * @return string
     */
    public function get_post_password(): string
    {
        return $this->post_password;
    }

    /**
     * Set post password
     *
     * @param string $post_password
     */
    public function set_post_password(string $post_password)
    {
        $this->post_password = $post_password;
    }

    /**
     * Get post name
     *
     * @return string
     */
    public function get_post_name(): string
    {
        return $this->post_name;
    }

    /**
     * Set post name
     *
     * @param string $post_name
     */
    public function set_post_name(string $post_name)
    {
        $this->post_name = $post_name;
    }

    /**
     * Get to ping
     *
     * @return string
     */
    public function get_to_ping(): string
    {
        return $this->to_ping;
    }

    /**
     * Set to ping
     *
     * @param string $to_ping
     */
    public function set_to_ping(string $to_ping)
    {
        $this->to_ping = $to_ping;
    }

    /**
     * Get pinged
     *
     * @return string
     */
    public function get_pinged(): string
    {
        return $this->pinged;
    }

    /**
     * Set pinged
     *
     * @param string $pinged
     */
    public function set_pinged(string $pinged)
    {
        $this->pinged = $pinged;
    }

    /**
     * Get post modified
     *
     * @return string
     */
    public function get_post_modified(): string
    {
        return $this->post_modified;
    }

    /**
     * Set post modified
     *
     * @param string $post_modified
     */
    public function set_post_modified(string $post_modified)
    {
        $this->post_modified = $post_modified;
    }

    /**
     * Get post modified (GMT)
     *
     * @return string
     */
    public function get_post_modified_gmt(): string
    {
        return $this->post_modified_gmt;
    }

    /**
     * Set post modified (GMT)
     *
     * @param string $post_modified_gmt
     */
    public function set_post_modified_gmt(string $post_modified_gmt)
    {
        $this->post_modified_gmt = $post_modified_gmt;
    }

    /**
     * Get post content filtered
     *
     * @return string
     */
    public function get_post_content_filtered(): string
    {
        return $this->post_content_filtered;
    }

    /**
     * Set post content filtered
     *
     * @param string $post_content_filtered
     */
    public function set_post_content_filtered(string $post_content_filtered)
    {
        $this->post_content_filtered = $post_content_filtered;
    }

    /**
     * Get post parent
     *
     * @return int
     */
    public function get_post_parent(): int
    {
        return $this->post_parent;
    }

    /**
     * Set post parent
     *
     * @param int $post_parent
     */
    public function set_post_parent(int $post_parent)
    {
        $this->post_parent = $post_parent;
    }

    /**
     * Get GUID
     *
     * @return string
     */
    public function get_guid(): string
    {
        return $this->guid;
    }

    /**
     * Set GUID
     *
     * @param string $guid
     */
    public function set_guid(string $guid)
    {
        $this->guid = $guid;
    }

    /**
     * Get menu order
     *
     * @return int
     */
    public function get_menu_order(): int
    {
        return $this->menu_order;
    }

    /**
     * Set menu order
     *
     * @param int $menu_order
     */
    public function set_menu_order(int $menu_order)
    {
        $this->menu_order = $menu_order;
    }

    /**
     * Get post type
     *
     * @return string
     */
    public function get_post_type(): string
    {
        return $this->post_type;
    }

    /**
     * Set post type
     *
     * @param string $post_type
     */
    public function set_post_type(string $post_type)
    {
        $this->post_type = $post_type;
    }

    /**
     * Get post mime type
     *
     * @return string
     */
    public function get_post_mime_type(): string
    {
        return $this->post_mime_type;
    }

    /**
     * Set post mime type
     *
     * @param string $post_mime_type
     */
    public function set_post_mime_type(string $post_mime_type)
    {
        $this->post_mime_type = $post_mime_type;
    }

    /**
     * Get comment count
     *
     * @return int
     */
    public function get_comment_count(): int
    {
        return $this->comment_count;
    }

    /**
     * Set comment count
     *
     * @param int $comment_count
     */
    public function set_comment_count(int $comment_count)
    {
        $this->comment_count = $comment_count;
    }
}