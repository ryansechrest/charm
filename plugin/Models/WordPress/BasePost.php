<?php

namespace Charm\Models\WordPress;

use Charm\Support\Result;
use WP_Post;
use WP_Query;

/**
 * Represents a generic post in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class BasePost
{
    /**
     * Model's post type
     */
    protected const POST_TYPE = '';

    /*------------------------------------------------------------------------*/

    /**
     * ID
     *
     * @var ?int
     */
    protected ?int $id = null;

    /**
     * Post author
     *
     * @var ?int
     */
    protected ?int $postAuthor = null;

    /**
     * Post date
     *
     * @var ?string
     */
    protected ?string $postDate = null;

    /**
     * Post date (GMT)
     *
     * @var ?string
     */
    protected ?string $postDateGmt = null;

    /**
     * Post content
     *
     * @var ?string
     */
    protected ?string $postContent = null;

    /**
     * Post title
     *
     * @var ?string
     */
    protected ?string $postTitle = null;

    /**
     * Post excerpt
     *
     * @var ?string
     */
    protected ?string $postExcerpt = null;

    /**
     * Post status
     *
     * @var ?string
     */
    protected ?string $postStatus = null;

    /**
     * Comment status
     *
     * @var ?string
     */
    protected ?string $commentStatus = null;

    /**
     * Ping status
     *
     * @var ?string
     */
    protected ?string $pingStatus = null;

    /**
     * Post password
     *
     * @var ?string
     */
    protected ?string $postPassword = null;

    /**
     * Post name
     *
     * @var ?string
     */
    protected ?string $postName = null;

    /**
     * URLs to ping
     *
     * @var ?string
     */
    protected ?string $toPing = null;

    /**
     * URLs pinged
     *
     * @var ?string
     */
    protected ?string $pinged = null;

    /**
     * Post modified
     *
     * @var ?string
     */
    protected ?string $postModified = null;

    /**
     * Post modified (GMT)
     *
     * @var ?string
     */
    protected ?string $postModifiedGmt = null;

    /**
     * Filtered post content
     *
     * @var ?string
     */
    protected ?string $postContentFiltered = null;

    /**
     * Post parent
     *
     * @var ?int
     */
    protected ?int $postParent = null;

    /**
     * GUID
     *
     * @var ?string
     */
    protected ?string $guid = null;

    /**
     * Menu order
     *
     * @var ?int
     */
    protected ?int $menuOrder = null;

    /**
     * Post type
     *
     * @var ?string
     */
    protected ?string $postType = null;

    /**
     * Post MIME type
     *
     * @var ?string
     */
    protected ?string $postMimeType = null;

    /**
     * Comment count
     *
     * @var ?int
     */
    protected ?int $commentCount = null;

    /**************************************************************************/

    /**
     * Post constructor
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

        if (isset($data['postAuthor'])) {
            $this->postAuthor = (int) $data['postAuthor'];
        }

        if (isset($data['postDate'])) {
            $this->postDate = $data['postDate'];
        }

        if (isset($data['postDateGmt'])) {
            $this->postDateGmt = $data['postDateGmt'];
        }

        if (isset($data['postContent'])) {
            $this->postContent = $data['postContent'];
        }

        if (isset($data['postTitle'])) {
            $this->postTitle = $data['postTitle'];
        }

        if (isset($data['postExcerpt'])) {
            $this->postExcerpt = $data['postExcerpt'];
        }

        if (isset($data['postStatus'])) {
            $this->postStatus = $data['postStatus'];
        }

        if (isset($data['commentStatus'])) {
            $this->commentStatus = $data['commentStatus'];
        }

        if (isset($data['pingStatus'])) {
            $this->pingStatus = $data['pingStatus'];
        }

        if (isset($data['postPassword'])) {
            $this->postPassword = $data['postPassword'];
        }

        if (isset($data['postName'])) {
            $this->postName = $data['postName'];
        }

        if (isset($data['toPing'])) {
            $this->toPing = $data['toPing'];
        }

        if (isset($data['pinged'])) {
            $this->pinged = $data['pinged'];
        }

        if (isset($data['postModified'])) {
            $this->postModified = $data['postModified'];
        }

        if (isset($data['postModifiedGmt'])) {
            $this->postModifiedGmt = $data['postModifiedGmt'];
        }

        if (isset($data['postContentFiltered'])) {
            $this->postContentFiltered = $data['postContentFiltered'];
        }

        if (isset($data['postParent'])) {
            $this->postParent = (int) $data['postParent'];
        }

        if (isset($data['guid'])) {
            $this->guid = $data['guid'];
        }

        if (isset($data['menuOrder'])) {
            $this->menuOrder = (int) $data['menuOrder'];
        }

        if (isset($data['postType'])) {
            $this->postType = $data['postType'];
        }

        if (isset($data['postMimeType'])) {
            $this->postMimeType = $data['postMimeType'];
        }

        if (isset($data['commentCount'])) {
            $this->commentCount = (int) $data['commentCount'];
        }
    }

    /**************************************************************************/

    /**
     * Initialize post
     *
     * @param int|null|string|WP_Post $key
     * @return ?static
     */
    public static function init(int|null|string|WP_Post $key = null): ?static
    {
        $post = new static();

        match (true) {
            is_numeric($key) => $post->loadFromId((int) $key),
            is_string($key) => $post->loadFromPath($key),
            $key instanceof WP_Post => $post->loadFromPost($key),
            default => $post->loadFromGlobalPost(),
        };

        return !$post->getId() ? null : $post;
    }

    /**
     * Get posts
     *
     * @param array $params
     * @return static[]
     * @see WP_Post
     * @see WP_Query
     */
    public static function get(array $params): array
    {
        $query = self::query($params);

        return count($query->posts) > 0 ? $query->posts : [];
    }

    /**
     * Query posts using WP_Query
     *
     * Note that this method mutates $query->posts by replacing the array of
     * WP_Post instances with our own class.
     *
     * @param array $params
     * @return WP_Query
     * @see WP_Post
     * @see WP_Query
     */
    public static function query(array $params): WP_Query
    {
        $params['post_type'] = static::postType();

        $query = new WP_Query($params);

        if ($query->found_posts) {
            $query->posts = array_map(function (WP_Post $wpPost) {
                return static::init($wpPost);
            }, $query->posts);
        }

        return $query;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post type from model
     *
     * @return string
     */
    protected static function postType(): string
    {
        if (static::POST_TYPE === '') {
            throw new \RuntimeException(
                static::class . ' must define POST_TYPE.'
            );
        }

        return static::POST_TYPE;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Load instance from ID
     *
     * @param int $id
     * @see get_post()
     */
    private function loadFromId(int $id): void
    {
        if (!$wpPost = get_post($id)) {
            return;
        };

        $this->loadFromPost($wpPost);
    }

    /**
     * Load instance from path
     *
     * @param string $path
     * @see get_page_by_path()
     */
    private function loadFromPath(string $path): void
    {
        if (!$wpPost = get_page_by_path($path, OBJECT, $this->postType)) {
            return;
        }

        $this->loadFromPost($wpPost);
    }

    /**
     * Load instance from global WP_Post
     *
     * @see get_post()
     */
    private function loadFromGlobalPost(): void
    {
        if (!$wpPost = get_post()) {
            return;
        }

        $this->loadFromPost($wpPost);
    }

    /**
     * Load instance from WP_Post
     *
     * @param WP_Post $wpPost
     * @see WP_Post
     */
    private function loadFromPost(WP_Post $wpPost): void
    {
        $this->id = (int) $wpPost->ID;
        $this->postAuthor = (int) $wpPost->post_author;
        $this->postDate = $wpPost->post_date;
        $this->postDateGmt = $wpPost->post_date_gmt;
        $this->postContent = $wpPost->post_content;
        $this->postTitle = $wpPost->post_title;
        $this->postExcerpt = $wpPost->post_excerpt;
        $this->postStatus = $wpPost->post_status;
        $this->commentStatus = $wpPost->comment_status;
        $this->pingStatus = $wpPost->ping_status;
        $this->postPassword = $wpPost->post_password;
        $this->postName = $wpPost->post_name;
        $this->toPing = $wpPost->to_ping;
        $this->pinged = $wpPost->pinged;
        $this->postModified = $wpPost->post_modified;
        $this->postModifiedGmt = $wpPost->post_modified_gmt;
        $this->postContentFiltered = $wpPost->post_content_filtered;
        $this->postParent = (int) $wpPost->post_parent;
        $this->guid = $wpPost->guid;
        $this->menuOrder = (int) $wpPost->menu_order;
        $this->postType = $wpPost->post_type;
        $this->postMimeType = $wpPost->post_mime_type;
        $this->commentCount = (int) $wpPost->comment_count;
    }

    /**
     * Cast post to array to be used by WordPress functions
     *
     * Remove keys from array if the value is null,
     * since that indicates no value has been set.
     *
     * @return array
     */
    private function toWpPostArray(): array
    {
        $data = [
            'ID' => $this->id,
            'post_author' => $this->postAuthor,
            'post_date' => $this->postDate,
            'post_date_gmt' => $this->postDateGmt,
            'post_content' => $this->postContent,
            'post_title' => $this->postTitle,
            'post_excerpt' => $this->postExcerpt,
            'post_status' => $this->postStatus,
            'comment_status' => $this->commentStatus,
            'ping_status' => $this->pingStatus,
            'post_password' => $this->postPassword,
            'post_name' => $this->postName,
            'to_ping' => $this->toPing,
            'pinged' => $this->pinged,
            'post_modified' => $this->postModified,
            'post_modified_gmt' => $this->postModifiedGmt,
            'post_content_filtered' => $this->postContentFiltered,
            'post_parent' => $this->postParent,
            'guid' => $this->guid,
            'menu_order' => $this->menuOrder,
            'post_type' => $this->postType,
            'post_mime_type' => $this->postMimeType,
            'comment_count' => $this->commentCount,
        ];

        return array_filter($data, fn($value) => !is_null($value));
    }

    /**************************************************************************/

    /**
     * Save post
     *
     * @return Result
     */
    public function save(): Result
    {
        return !$this->id ? $this->create() : $this->update();
    }

    /**
     * Create new post
     *
     * @return Result
     * @see wp_insert_post()
     * @see is_wp_error()
     */
    public function create(): Result
    {
        $result = wp_insert_post($this->toWpPostArray());

        // WordPress successfully created the post
        if (is_int($result) && $result > 0) {
            $this->id = $result;
            return Result::success();
        }

        // WordPress failed to create the post
        if (is_wp_error($result)) {
            return Result::wpError($result);
        }

        // WordPress failed to create the post
        if ($result === 0) {
            Result::error(
                'wp_insert_post_failed',
                __('wp_insert_post() returned 0.', 'charm')
            );
        }

        // WordPress returned something unexpected
        return Result::error(
            'wp_insert_post_failed',
            __('wp_insert_post() returned something unexpected.', 'charm')
        );
    }

    /**
     * Update existing post
     *
     * @return Result
     * @see wp_update_post()
     * @see is_wp_error()
     */
    public function update(): Result
    {
        $result = wp_update_post($this->toWpPostArray());

        // WordPress successfully updated the post
        if (is_int($result) && $result > 0) {
            return Result::success();
        }

        // WordPress failed to update the post
        if (is_wp_error($result)) {
            return Result::error($result);
        }

        // WordPress failed to update the post
        if ($result === 0) {
            Result::error(
                'wp_update_post_failed',
                __('wp_update_post() returned 0.', 'charm')
            );
        }

        // WordPress returned something unexpected
        return Result::error(
            'wp_update_post_failed',
            __('wp_update_post() returned something unexpected.', 'charm')
        );
    }

    /**
     * Move post to trash
     *
     * @return Result
     * @see wp_trash_post()
     */
    public function trash(): Result
    {
        // Charm can't trash a post without an ID
        if ($this->id === null) {
            return Result::error(
                'missing_post_id',
                __('Cannot trash post with blank ID.', 'charm')
            );
        }

        $result = wp_trash_post($this->id);

        // WordPress successfully trashed the post
        if ($result instanceof WP_Post) {
            return Result::success();
        }

        // WordPress failed to trash the post
        if ($result === false) {
            Result::error(
                'wp_trash_post_failed',
                __('wp_trash_post() returned false.', 'charm')
            );
        }

        // WordPress failed to trash the post
        if ($result === null) {
            Result::error(
                'wp_trash_post_failed',
                __('wp_trash_post() returned null.', 'charm')
            );
        }

        // WordPress returned something unexpected
        return Result::error(
            'wp_trash_post_failed',
            __('wp_trash_post() returned something unexpected.', 'charm')
        );
    }

    /**
     * Restore post from trash
     *
     * @return Result
     * @see wp_untrash_post()
     */
    public function restore(): Result
    {
        // Charm can't restore a post without an ID
        if ($this->id === null) {
            return Result::error(
                'missing_post_id',
                __('Cannot restore post with blank ID.', 'charm')
            );
        }

        $result = wp_untrash_post($this->id);

        // WordPress successfully restored the post
        if ($result instanceof WP_Post) {
            return Result::success();
        }

        // WordPress failed to restore the post
        if ($result === false) {
            Result::error(
                'wp_untrash_post_failed',
                __('wp_untrash_post() returned false.', 'charm')
            );
        }

        // WordPress failed to restore the post
        if ($result === null) {
            Result::error(
                'wp_untrash_post_failed',
                __('wp_untrash_post() returned null.', 'charm')
            );
        }

        // WordPress returned something unexpected
        return Result::error(
            'wp_untrash_post_failed',
            __('wp_untrash_post() returned something unexpected.', 'charm')
        );
    }

    /**
     * Delete post permanently
     *
     * @return Result
     * @see wp_delete_post()
     */
    public function delete(): Result
    {
        // Charm can't delete a post without an ID
        if ($this->id === null) {
            return Result::error(
                'missing_post_id',
                __('Cannot delete post with blank ID.', 'charm')
            );
        }

        $result = wp_delete_post($this->id, true);

        // WordPress successfully deleted the post
        if ($result instanceof WP_Post) {
            return Result::success();
        }

        // WordPress failed to delete the post
        if ($result === false) {
            Result::error(
                'wp_delete_post_failed',
                __('wp_delete_post() returned false.', 'charm')
            );
        }

        // WordPress failed to delete the post
        if ($result === null) {
            Result::error(
                'wp_delete_post_failed',
                __('wp_delete_post() returned null.', 'charm')
            );
        }

        // WordPress returned something unexpected
        return Result::error(
            'wp_delete_post_failed',
            __('wp_delete_post() returned something unexpected.', 'charm')
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
     * Get post author
     *
     * @return int
     */
    public function getPostAuthor(): int
    {
        return $this->postAuthor ?? 0;
    }

    /**
     * Set post author
     *
     * @param int $postAuthor
     */
    public function setPostAuthor(int $postAuthor): void
    {
        $this->postAuthor = $postAuthor;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post date
     *
     * @return string
     */
    public function getPostDate(): string
    {
        return $this->postDate ?? '';
    }

    /**
     * Set post date
     *
     * @param string $postDate
     */
    public function setPostDate(string $postDate): void
    {
        $this->postDate = $postDate;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post date (GMT)
     *
     * @return string
     */
    public function getPostDateGmt(): string
    {
        return $this->postDateGmt ?? '';
    }

    /**
     * Set post date (GMT)
     *
     * @param string $postDateGmt
     */
    public function setPostDateGmt(string $postDateGmt): void
    {
        $this->postDateGmt = $postDateGmt;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post content
     *
     * @return string
     */
    public function getPostContent(): string
    {
        return $this->postContent ?? '';
    }

    /**
     * Set post content
     *
     * @param string $postContent
     */
    public function setPostContent(string $postContent): void
    {
        $this->postContent = $postContent;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post title
     *
     * @return string
     */
    public function getPostTitle(): string
    {
        return $this->postTitle ?? '';
    }

    /**
     * Set post title
     *
     * @param string $postTitle
     */
    public function setPostTitle(string $postTitle): void
    {
        $this->postTitle = $postTitle;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post excerpt
     *
     * @return string
     */
    public function getPostExcerpt(): string
    {
        return $this->postExcerpt ?? '';
    }

    /**
     * Set post excerpt
     *
     * @param string $postExcerpt
     */
    public function setPostExcerpt(string $postExcerpt): void
    {
        $this->postExcerpt = $postExcerpt;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post status
     *
     * @return string
     */
    public function getPostStatus(): string
    {
        return $this->postStatus ?? '';
    }

    /**
     * Set post status
     *
     * @param string $postStatus
     */
    public function setPostStatus(string $postStatus): void
    {
        $this->postStatus = $postStatus;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get comment status
     *
     * @return string
     */
    public function getCommentStatus(): string
    {
        return $this->commentStatus ?? '';
    }

    /**
     * Set comment status
     *
     * @param string $commentStatus
     */
    public function setCommentStatus(string $commentStatus): void
    {
        $this->commentStatus = $commentStatus;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get ping status
     *
     * @return string
     */
    public function getPingStatus(): string
    {
        return $this->pingStatus ?? '';
    }

    /**
     * Set ping status
     *
     * @param string $pingStatus
     */
    public function setPingStatus(string $pingStatus): void
    {
        $this->pingStatus = $pingStatus;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post password
     *
     * @return string
     */
    public function getPostPassword(): string
    {
        return $this->postPassword ?? '';
    }

    /**
     * Set post password
     *
     * @param string $postPassword
     */
    public function setPostPassword(string $postPassword): void
    {
        $this->postPassword = $postPassword;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post name
     *
     * @return string
     */
    public function getPostName(): string
    {
        return $this->postName ?? '';
    }

    /**
     * Set post name
     *
     * @param string $postName
     */
    public function setPostName(string $postName): void
    {
        $this->postName = $postName;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get URLs to ping
     *
     * @return string
     */
    public function getToPing(): string
    {
        return $this->toPing ?? '';
    }

    /**
     * Set URLs to ping
     *
     * @param string $toPing
     */
    public function setToPing(string $toPing): void
    {
        $this->toPing = $toPing;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get pinged URLs
     *
     * @return string
     */
    public function getPinged(): string
    {
        return $this->pinged ?? '';
    }

    /**
     * Set pinged URLs
     *
     * @param string $pinged
     */
    public function setPinged(string $pinged): void
    {
        $this->pinged = $pinged;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post modified
     *
     * @return string
     */
    public function getPostModified(): string
    {
        return $this->postModified ?? '';
    }

    /**
     * Set post modified
     *
     * @param string $postModified
     */
    public function setPostModified(string $postModified): void
    {
        $this->postModified = $postModified;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post modified (GMT)
     *
     * @return string
     */
    public function getPostModifiedGmt(): string
    {
        return $this->postModifiedGmt ?? '';
    }

    /**
     * Set post modified (GMT)
     *
     * @param string $postModifiedGmt
     */
    public function setPostModifiedGmt(string $postModifiedGmt): void
    {
        $this->postModifiedGmt = $postModifiedGmt;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get filtered post content
     *
     * @return string
     */
    public function getPostContentFiltered(): string
    {
        return $this->postContentFiltered ?? '';
    }

    /**
     * Set filtered post content
     *
     * @param string $postContentFiltered
     */
    public function setPostContentFiltered(string $postContentFiltered): void
    {
        $this->postContentFiltered = $postContentFiltered;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post parent
     *
     * @return int
     */
    public function getPostParent(): int
    {
        return $this->postParent ?? 0;
    }

    /**
     * Set post parent
     *
     * @param int $postParent
     */
    public function setPostParent(int $postParent): void
    {
        $this->postParent = $postParent;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get GUID
     *
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid ?? '';
    }

    /**
     * Set GUID
     *
     * @param string $guid
     */
    public function setGuid(string $guid): void
    {
        $this->guid = $guid;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get menu order
     *
     * @return int
     */
    public function getMenuOrder(): int
    {
        return $this->menuOrder ?? 0;
    }

    /**
     * Set menu order
     *
     * @param int $menuOrder
     */
    public function setMenuOrder(int $menuOrder): void
    {
        $this->menuOrder = $menuOrder;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post type
     *
     * @return string
     */
    public function getPostType(): string
    {
        return $this->postType ?? '';
    }

    /**
     * Set post type
     *
     * @param string $postType
     */
    public function setPostType(string $postType): void
    {
        $this->postType = $postType;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get post MIME type
     *
     * @return string
     */
    public function getPostMimeType(): string
    {
        return $this->postMimeType ?? '';
    }

    /**
     * Set post MIME type
     *
     * @param string $postMimeType
     */
    public function setPostMimeType(string $postMimeType): void
    {
        $this->postMimeType = $postMimeType;
    }

    /*------------------------------------------------------------------------*/

    /**
     * Get comment count
     *
     * @return int
     */
    public function getCommentCount(): int
    {
        return $this->commentCount ?? 0;
    }

    /**
     * Set comment count
     *
     * @param int $commentCount
     */
    public function setCommentCount(int $commentCount): void
    {
        $this->commentCount = $commentCount;
    }
}