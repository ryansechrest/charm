<?php

namespace Charm\WordPress;

use Charm\App\DataType\DateTime;
use Charm\App\Feature\Conversion;
use Charm\WordPress\Meta\PostMeta;
use Exception;
use WP_Post;
use WP_Query;

/**
 * Class Post
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class Post implements Conversion
{
    /**
     * ID
     *
     * @var int
     */
    private $id = 0;

    /**
     * Post author
     *
     * @var int
     */
    private $post_author = 0;

    /**
     * Post date
     *
     * @var DateTime
     */
    private $post_date = null;

    /**
     * Post date (GMT)
     *
     * @var DateTime
     */
    private $post_date_gmt = null;

    /**
     * Post content
     *
     * @var string
     */
    private $post_content = '';

    /**
     * Post title
     *
     * @var string
     */
    private $post_title = '';

    /**
     * Post excerpt
     *
     * @var string
     */
    private $post_excerpt = '';

    /**
     * Post status
     *
     * @var string
     */
    private $post_status = '';

    /**
     * Comment status
     *
     * @var string
     */
    private $comment_status = '';

    /**
     * Ping status
     *
     * @var string
     */
    private $ping_status = '';

    /**
     * Post password
     *
     * @var string
     */
    private $post_password = '';

    /**
     * Post name
     *
     * @var string
     */
    private $post_name = '';

    /**
     * URLs to ping
     *
     * @var string
     */
    private $to_ping = '';

    /**
     * URLs pinged
     *
     * @var string
     */
    private $pinged = '';

    /**
     * Post modified
     *
     * @var DateTime
     */
    private $post_modified = null;

    /**
     * Post modified (GMT)
     *
     * @var DateTime
     */
    private $post_modified_gmt = null;

    /**
     * Filtered post content
     *
     * @var string
     */
    private $post_content_filtered = '';

    /**
     * Post parent
     *
     * @var int
     */
    private $post_parent = 0;

    /**
     * GUID
     *
     * @var string
     */
    private $guid = '';

    /**
     * Menu order
     *
     * @var int
     */
    private $menu_order = 0;

    /**
     * Post type
     *
     * @var string
     */
    private $post_type = '';

    /**
     * Post mime type
     *
     * @var string
     */
    private $post_mime_type = '';

    /**
     * Comment count
     *
     * @var int
     */
    private $comment_count = 0;

    /**
     * Permalink
     *
     * @var string
     */
    private $permalink = '';

    /**
     * Post metas
     *
     * @var PostMeta[]
     */
    private $metas = [];

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Post constructor
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
    public function load(array $data): void
    {
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }
        if (isset($data['post_author'])) {
            $this->post_author = (int) $data['post_author'];
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
            $this->post_parent = (int) $data['post_parent'];
        }
        if (isset($data['guid'])) {
            $this->guid = $data['guid'];
        }
        if (isset($data['menu_order'])) {
            $this->menu_order = (int) $data['menu_order'];
        }
        if (isset($data['post_type'])) {
            $this->post_type = $data['post_type'];
        }
        if (isset($data['post_mime_type'])) {
            $this->post_mime_type = $data['post_mime_type'];
        }
        if (isset($data['comment_count'])) {
            $this->comment_count = (int) $data['comment_count'];
        }
        if (isset($data['permalink'])) {
            $this->permalink = $data['permalink'];
        }
        if (isset($data['metas'])) {
            $this->metas = $data['metas'];
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
        /* @var Post $post */
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
    public static function get(array $params): array
    {
        $posts = [];
        $query = new WP_Query($params);
        if (!$query->found_posts) {
            return $posts;
        }

        return array_map(function(WP_Post $post) {
            /* @var Post $child */
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
    private function load_from_id(int $id): void
    {
        if (!$post = get_post($id)) {
            return;
        }
        $this->load_from_post($post);
    }

    /**
     * Load instance from path
     *
     * @see get_page_by_path()
     * @param string $path
     */
    private function load_from_path(string $path): void
    {
        if (!$post = get_page_by_path($path)) {
            return;
        }
        $this->load_from_post($post);
    }

    /**
     * Load instance from global WP_Post object
     *
     * @see get_post()
     */
    private function load_from_global_post(): void
    {
        if (!$post = get_post()) {
            return;
        }
        $this->load_from_post($post);
    }

    /**
     * Load instance from WP_Post object
     *
     * @see DateTime
     * @see get_option()
     * @see WP_Post
     * @param WP_Post $post
     */
    private function load_from_post(WP_Post $post): void
    {
        $timezone = get_option('timezone_string');
        try {
            $this->id = (int) $post->ID;
            $this->post_author = (int) $post->post_author;
            $this->post_date = DateTime::init($post->post_date, $timezone);
            $this->post_date_gmt = DateTime::init_utc($post->post_date_gmt);
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
            $this->post_modified = DateTime::init($post->post_modified, $timezone);
            $this->post_modified_gmt = DateTime::init_utc($post->post_modified_gmt);
            $this->post_content_filtered = $post->post_content_filtered;
            $this->post_parent = (int) $post->post_parent;
            $this->guid = $post->guid;
            $this->menu_order = (int) $post->menu_order;
            $this->post_type = $post->post_type;
            $this->post_mime_type = $post->post_mime_type;
            $this->comment_count = (int) $post->comment_count;
            $this->permalink = get_permalink($this->id);
        } catch (Exception $e) {
            // Quiet please.
        }
        $this->load_metas();
    }

    /**
     * Reload instance from database
     */
    private function reload(): void
    {
        if (!$this->id) {
            return;
        }
        $this->load_from_id($this->id);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Load post metas
     */
    private function load_metas(): void
    {
        $metas = PostMeta::init(['post_id' => $this->id]);
        if (!is_array($metas)) {
            return;
        }
        $this->metas = $metas;
    }

    /**
     * Save post metas
     */
    private function save_metas(): void
    {
        foreach ($this->metas as $meta) {
            if (!$meta->has_changed()) {
                continue;
            }
            if (!$meta->is_from_db()) {
                $meta->create();
            }
            $meta->update();
        }
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save post
     *
     * @param string $post_status
     * @return bool
     */
    public function save($post_status = ''): bool
    {
        if (!$this->id) {
            return $this->create($post_status);
        }

        return $this->update($post_status);
    }

    /**
     * Create new post
     *
     * @see wp_insert_post()
     * @param string $post_status
     * @return bool
     */
    public function create($post_status = ''): bool
    {
        if ($post_status) {
            $this->post_status = $post_status;
        }
        if (!$id = wp_insert_post($this->to_array())) {
            return false;
        }
        $this->id = $id;
        $this->save_metas();
        $this->reload();

        return true;
    }

    /**
     * Update existing post
     *
     * @see wp_update_post()
     * @param string $post_status
     * @return bool
     */
    public function update($post_status = ''): bool
    {
        if ($post_status) {
            $this->post_status = $post_status;
        }
        if (!$id = wp_update_post($this->to_array())) {
            return false;
        }
        $this->save_metas();
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
        $data['ID'] = $this->id;
        $data['post_author'] = $this->post_author;
        $data['post_date'] = $this->post_date->format_db();
        $data['post_date_gmt'] = $this->post_date_gmt->format_db();
        $data['post_content'] = $this->post_content;
        $data['post_title'] = $this->post_title;
        $data['post_excerpt'] = $this->post_excerpt;
        $data['post_status'] = $this->post_status;
        $data['comment_status'] = $this->comment_status;
        $data['ping_status'] = $this->ping_status;
        $data['post_password'] = $this->post_password;
        $data['post_name'] = $this->post_name;
        $data['to_ping'] = $this->to_ping;
        $data['pinged'] = $this->pinged;
        $data['post_modified'] = $this->post_modified->format_db();
        $data['post_modified_gmt'] = $this->post_modified_gmt->format_db();
        $data['post_content_filtered'] = $this->post_content_filtered;
        $data['post_parent'] = $this->post_parent;
        $data['guid'] = $this->guid;
        $data['menu_order'] = $this->menu_order;
        $data['post_type'] = $this->post_type;
        $data['post_mime_type'] = $this->post_mime_type;
        $data['comment_count'] = $this->comment_count;
        $data['permalink'] = $this->permalink;
        $data['metas'] = $this->metas;

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
    /**
     * Convert instance to stdClass
     *
     * @return object
     */
    public function to_object(): object
    {
        return (object) $this->to_array();
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get post meta
     *
     * @param string key
     * @return null|PostMeta
     */
    public function meta(string $key)
    {
        if (count($this->metas) === 0) {
            $this->load_metas();
        }
        if (!isset($this->metas[$key])) {
            return null;
        }

        return $this->metas[$key];
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
    public function set_id(int $id): void
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
    public function set_post_author(int $post_author): void
    {
        $this->post_author = $post_author;
    }

    /**
     * Get post date
     *
     * @return DateTime
     */
    public function get_post_date(): DateTime
    {
        return $this->post_date;
    }

    /**
     * Set post date
     *
     * @param DateTime $post_date
     */
    public function set_post_date(DateTime $post_date): void
    {
        $this->post_date = $post_date;
    }

    /**
     * Get post date (GMT)
     *
     * @return DateTime
     */
    public function get_post_date_gmt(): DateTime
    {
        return $this->post_date_gmt;
    }

    /**
     * Set post date (GMT)
     *
     * @param DateTime $post_date_gmt
     */
    public function set_post_date_gmt(DateTime $post_date_gmt): void
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
    public function set_post_content(string $post_content): void
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
    public function set_post_title(string $post_title): void
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
    public function set_post_excerpt(string $post_excerpt): void
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
    public function set_post_status(string $post_status): void
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
    public function set_comment_status(string $comment_status): void
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
    public function set_ping_status(string $ping_status): void
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
    public function set_post_password(string $post_password): void
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
    public function set_post_name(string $post_name): void
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
    public function set_to_ping(string $to_ping): void
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
    public function set_pinged(string $pinged): void
    {
        $this->pinged = $pinged;
    }

    /**
     * Get post modified
     *
     * @return DateTime
     */
    public function get_post_modified(): DateTime
    {
        return $this->post_modified;
    }

    /**
     * Set post modified
     *
     * @param DateTime $post_modified
     */
    public function set_post_modified(DateTime $post_modified): void
    {
        $this->post_modified = $post_modified;
    }

    /**
     * Get post modified (GMT)
     *
     * @return DateTime
     */
    public function get_post_modified_gmt(): DateTime
    {
        return $this->post_modified_gmt;
    }

    /**
     * Set post modified (GMT)
     *
     * @param DateTime $post_modified_gmt
     */
    public function set_post_modified_gmt(DateTime $post_modified_gmt): void
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
    public function set_post_content_filtered(string $post_content_filtered): void
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
    public function set_post_parent(int $post_parent): void
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
    public function set_guid(string $guid): void
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
    public function set_menu_order(int $menu_order): void
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
    public function set_post_type(string $post_type): void
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
    public function set_post_mime_type(string $post_mime_type): void
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
    public function set_comment_count(int $comment_count): void
    {
        $this->comment_count = $comment_count;
    }

    /**
     * Get permalink
     *
     * @return string
     */
    public function get_permalink(): string
    {
        return $this->permalink;
    }

    /**
     * Set permalink
     *
     * @param string $permalink
     */
    public function set_permalink(string $permalink): void
    {
        $this->permalink = $permalink;
    }
}