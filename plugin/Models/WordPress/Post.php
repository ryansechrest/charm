<?php

namespace Charm\Models\WordPress;

use Charm\Support\Result;
use WP_Post;
use WP_Query;

/**
 * Represents a post of any post type in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Post
{
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

        return !$post->id ? null : $post;
    }

    /**
     * Get posts
     *
     * @param array $params
     * @return static[]
     */
    public static function get(array $params): array
    {
        $wpQuery = self::query($params);

        if ($wpQuery->found_posts === 0) {
            return [];
        }

        return array_map(function (WP_Post $wpPost) {
            return static::init($wpPost);
        }, $wpQuery->posts);
    }

    /**
     * Query posts with WP_Query
     *
     * @param array $params
     * @return WP_Query
     */
    public static function query(array $params): WP_Query
    {
        return new WP_Query($params);
    }

    /**************************************************************************/

    /**
     * Load instance from ID
     *
     * @param int $id
     * @see get_post()
     */
    protected function loadFromId(int $id): void
    {
        if ($id === 0) {
            return;
        }

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
    protected function loadFromPath(string $path): void
    {
        if (!$wpPost = get_page_by_path(
            $path, OBJECT, $this->postType)
        ) {
            return;
        }

        $this->loadFromPost($wpPost);
    }

    /**
     * Load instance from global WP_Post
     *
     * @see get_post()
     */
    protected function loadFromGlobalPost(): void
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
     */
    protected function loadFromPost(WP_Post $wpPost): void
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
     * Reload instance from database
     */
    protected function reload(): void
    {
        if ($this->id === null) {
            return;
        }

        $this->loadFromId($this->id);
    }

    /*------------------------------------------------------------------------*/

    /**
     * Cast post to array to be used by WordPress functions
     *
     * Remove keys from array if the value is null,
     * since that indicates no value has been set.
     *
     * @param array $includeData
     * @return array
     */
    protected function toWpPostArray(array $includeData = []): array
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
            'menu_order' => $this->menuOrder,
            'post_type' => $this->postType,
            'post_mime_type' => $this->postMimeType,
        ];

        $data = array_merge($includeData, $data);

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
        if ($this->id !== null) {
            return Result::error(
                'existing_post_id',
                __('Post already exists.', 'charm')
            );
        }

        $result = wp_insert_post($this->toWpPostArray());

        if (is_wp_error($result)) {
            return Result::wpError($result);
        }

        if (!is_int($result) || $result === 0) {
            return Result::error(
                'wp_insert_post_failed',
                __('wp_insert_post() did not return an ID.', 'charm')
            );
        }

        $this->id = $result;
        $this->reload();

        return Result::success();
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
        if ($this->id === null) {
            return Result::error(
                'missing_post_id',
                __('Cannot update post with blank ID.', 'charm')
            );
        }

        $includeData = ['ID' => $this->id];

        $result = wp_update_post($this->toWpPostArray($includeData));

        if (is_wp_error($result)) {
            return Result::error($result);
        }

        if (!is_int($result) || $result === 0) {
            return Result::error(
                'wp_update_post_failed',
                __('wp_update_post() did not return an ID.', 'charm')
            );
        }

        $this->reload();

        return Result::success();
    }

    /**
     * Move post to trash
     *
     * @return Result
     * @see wp_trash_post()
     */
    public function trash(): Result
    {
        if ($this->id === null) {
            return Result::error(
                'missing_post_id',
                __('Cannot trash post with blank ID.', 'charm')
            );
        }

        $result = wp_trash_post($this->id);

        if (!$result instanceof WP_Post) {
            return Result::error(
                'wp_trash_post_failed',
                __('wp_trash_post() did not return a post.', 'charm')
            );
        }

        $this->reload();

        return Result::success();
    }

    /**
     * Restore post from trash
     *
     * @return Result
     * @see wp_untrash_post()
     */
    public function restore(): Result
    {
        if ($this->id === null) {
            return Result::error(
                'missing_post_id',
                __('Cannot restore post with blank ID.', 'charm')
            );
        }

        $result = wp_untrash_post($this->id);

        if (!$result instanceof WP_Post) {
            return Result::error(
                'wp_untrash_post_failed',
                __('wp_untrash_post() did not return a post.', 'charm')
            );
        }

        $this->reload();

        return Result::success();
    }

    /**
     * Delete post permanently
     *
     * @return Result
     * @see wp_delete_post()
     */
    public function delete(): Result
    {
        if ($this->id === null) {
            return Result::error(
                'missing_post_id',
                __('Cannot delete post with blank ID.', 'charm')
            );
        }

        $result = wp_delete_post($this->id, true);

        if (!$result instanceof WP_Post) {
            return Result::error(
                'wp_delete_post_failed',
                __('wp_delete_post() did not return a post.', 'charm')
            );
        }

        $this->id = null;

        return Result::success();
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
     * @return static
     */
    public function setPostAuthor(int $postAuthor): static
    {
        $this->postAuthor = $postAuthor;

        return $this;
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
     * @return static
     */
    public function setPostDate(string $postDate): static
    {
        $this->postDate = $postDate;

        return $this;
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
     * @return static
     */
    public function setPostDateGmt(string $postDateGmt): static
    {
        $this->postDateGmt = $postDateGmt;

        return $this;
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
     * @return static
     */
    public function setPostContent(string $postContent): static
    {
        $this->postContent = $postContent;

        return $this;
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
     * @return static
     */
    public function setPostTitle(string $postTitle): static
    {
        $this->postTitle = $postTitle;

        return $this;
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
     * @return static
     */
    public function setPostExcerpt(string $postExcerpt): static
    {
        $this->postExcerpt = $postExcerpt;

        return $this;
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
     * @return static
     */
    public function setPostStatus(string $postStatus): static
    {
        $this->postStatus = $postStatus;

        return $this;
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
     * @return static
     */
    public function setCommentStatus(string $commentStatus): static
    {
        $this->commentStatus = $commentStatus;

        return $this;
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
     * @return static
     */
    public function setPingStatus(string $pingStatus): static
    {
        $this->pingStatus = $pingStatus;

        return $this;
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
     * @return static
     */
    public function setPostPassword(string $postPassword): static
    {
        $this->postPassword = $postPassword;

        return $this;
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
     * @return static
     */
    public function setPostName(string $postName): static
    {
        $this->postName = $postName;

        return $this;
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
     * @return static
     */
    public function setToPing(string $toPing): static
    {
        $this->toPing = $toPing;

        return $this;
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
     * @return static
     */
    public function setPinged(string $pinged): static
    {
        $this->pinged = $pinged;

        return $this;
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
     * @return static
     */
    public function setPostModified(string $postModified): static
    {
        $this->postModified = $postModified;

        return $this;
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
     * @return static
     */
    public function setPostModifiedGmt(string $postModifiedGmt): static
    {
        $this->postModifiedGmt = $postModifiedGmt;

        return $this;
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
     * @return static
     */
    public function setPostContentFiltered(string $postContentFiltered): static
    {
        $this->postContentFiltered = $postContentFiltered;

        return $this;
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
     * @return static
     */
    public function setPostParent(int $postParent): static
    {
        $this->postParent = $postParent;

        return $this;
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
     * @return static
     */
    public function setMenuOrder(int $menuOrder): static
    {
        $this->menuOrder = $menuOrder;

        return $this;
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
     * @return static
     */
    public function setPostType(string $postType): static
    {
        $this->postType = $postType;

        return $this;
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
     * @return static
     */
    public function setPostMimeType(string $postMimeType): static
    {
        $this->postMimeType = $postMimeType;

        return $this;
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
}