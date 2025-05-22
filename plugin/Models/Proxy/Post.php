<?php

namespace Charm\Models\Proxy;

use Charm\Contracts\IsArrayable;
use Charm\Contracts\IsPersistable;
use Charm\Contracts\WordPress\HasWpPost;
use Charm\Enums\Result\Message;
use Charm\Support\Filter;
use Charm\Support\Result;
use Charm\Traits\WithToArray;
use WP_Post;
use WP_Query;

/**
 * Represents a proxy post of any post type in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Post implements HasWpPost, IsArrayable, IsPersistable
{
    use WithToArray;

    // *************************************************************************

    /**
     * Post ID.
     *
     * @var ?int
     */
    protected ?int $id = null;

    /**
     * Post author.
     *
     * @var ?int
     */
    protected ?int $postAuthor = null;

    /**
     * Post created date and time.
     *
     * @var ?string
     */
    protected ?string $postDate = null;

    /**
     * Post created date and time (GMT).
     *
     * @var ?string
     */
    protected ?string $postDateGmt = null;

    /**
     * Post content.
     *
     * @var ?string
     */
    protected ?string $postContent = null;

    /**
     * Post title.
     *
     * @var ?string
     */
    protected ?string $postTitle = null;

    /**
     * Post excerpt.
     *
     * @var ?string
     */
    protected ?string $postExcerpt = null;

    /**
     * Post status.
     *
     * @var ?string
     */
    protected ?string $postStatus = null;

    /**
     * Comment status.
     *
     * @var ?string
     */
    protected ?string $commentStatus = null;

    /**
     * Ping status.
     *
     * @var ?string
     */
    protected ?string $pingStatus = null;

    /**
     * Post password.
     *
     * @var ?string
     */
    protected ?string $postPassword = null;

    /**
     * Post name.
     *
     * @var ?string
     */
    protected ?string $postName = null;

    /**
     * URLs to ping.
     *
     * @var ?string
     */
    protected ?string $toPing = null;

    /**
     * URLs pinged.
     *
     * @var ?string
     */
    protected ?string $pinged = null;

    /**
     * Post modified date and time.
     *
     * @var ?string
     */
    protected ?string $postModified = null;

    /**
     * Post modified date and time (GMT).
     *
     * @var ?string
     */
    protected ?string $postModifiedGmt = null;

    /**
     * Filtered post content.
     *
     * @var ?string
     */
    protected ?string $postContentFiltered = null;

    /**
     * Post parent.
     *
     * @var ?int
     */
    protected ?int $postParent = null;

    /**
     * GUID.
     *
     * @var ?string
     */
    protected ?string $guid = null;

    /**
     * Menu order.
     *
     * @var ?int
     */
    protected ?int $menuOrder = null;

    /**
     * Post type.
     *
     * @var ?string
     */
    protected ?string $postType = null;

    /**
     * Post MIME type.
     *
     * @var ?string
     */
    protected ?string $postMimeType = null;

    /**
     * Comment count.
     *
     * @var ?int
     */
    protected ?int $commentCount = null;

    // -------------------------------------------------------------------------

    /**
     * `WP_Post` instance.
     *
     * @var ?WP_Post
     */
    protected ?WP_Post $wpPost = null;

    // *************************************************************************

    /**
     * Post constructor.
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

    // -------------------------------------------------------------------------

    /**
     * Access the `WP_Post` instance.
     *
     * @return ?WP_Post
     */
    public function wpPost(): ?WP_Post
    {
        return $this->wpPost;
    }

    // *************************************************************************

    /**
     * Initialize the post from an ID.
     *
     * From: `wp_posts` -> `ID`
     *
     * @param int $id
     * @return ?static
     */
    public static function fromId(int $id): ?static
    {
        $post = new static();
        $post->loadFromId($id);

        return $post->id ? $post : null;
    }

    /**
     * Initialize the post from a path.
     *
     * From: `wp_posts` -> `post_name`
     *
     * @param string $path hello-world
     * @param string $postType post
     * @return ?static
     */
    public static function fromPath(string $path, string $postType): ?static
    {
        $post = new static();
        $post->loadFromPath($path, $postType);

        return $post->id ? $post : null;
    }

    /**
     * Initialize the post from the global `WP_Post` instance.
     *
     * @return ?static
     */
    public static function fromGlobalWpPost(): ?static
    {
        $post = new static();
        $post->loadFromGlobalWpPost();

        return $post->id ? $post : null;
    }

    /**
     * Initialize the post from the specified `WP_Post` instance.
     *
     * @param WP_Post $wpPost
     * @return static
     */
    public static function fromWpPost(WP_Post $wpPost): static
    {
        $post = new static();
        $post->loadFromWpPost($wpPost);

        return $post;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the posts.
     *
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_query/
     *
     * @param array $args
     * @return static[]
     */
    public static function get(array $args): array
    {
        $wpQuery = self::query($args);

        if ($wpQuery->found_posts === 0) {
            return [];
        }

        return array_map(
            fn (WP_Post $wpPost) => static::fromWpPost($wpPost),
            $wpQuery->posts
        );
    }

    /**
     * Query the posts with `WP_Query`.
     *
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_query/
     *
     * @param array $args
     * @return WP_Query
     */
    public static function query(array $args): WP_Query
    {
        return new WP_Query(query: $args);
    }

    // -------------------------------------------------------------------------

    /**
     * Create a post in the database.
     *
     * @param array $data
     * @return Result
     * @see wp_insert_post()
     * @see is_wp_error()
     */
    public static function createPost(array $data): Result
    {
        // `int`    -> Post ID    -> Success: Post created
        // `int`    -> `0`        -> Fail: Post not created
        // `object` -> `WP_Error` -> Fail: Post not created
        $result = wp_insert_post(postarr: $data, wp_error: true);

        if (is_wp_error($result)) {
            return Result::wpError(wpError: $result)->withData($data);
        }

        if (!is_int($result) || $result === 0) {
            return Result::error(Message::PostCreateFailed)->withData($data);
        }

        return Result::success(Message::PostCreateSuccess)
            ->withId($result)
            ->withData($data);
    }

    /**
     * Update a post in the database.
     *
     * @param array $data
     * @return Result
     * @see wp_update_post()
     * @see is_wp_error()
     */
    public static function updatePost(array $data): Result
    {
        // `int`    -> Post ID    -> Success: Post updated
        // `int`    -> `0`        -> Fail: Post not updated
        // `object` -> `WP_Error` -> Fail: Post not updated
        $result = wp_update_post(postarr: $data, wp_error: true);

        if (is_wp_error($result)) {
            return Result::wpError(wpError: $result)->withData($data);
        }

        if (!is_int($result) || $result === 0) {
            return Result::error(Message::PostUpdateFailed)->withData($data);
        }

        return Result::success(Message::PostUpdateSuccess)
            ->withId($result)
            ->withData($data);
    }

    /**
     * Move a post to the trash.
     *
     * @param int $id
     * @return Result
     * @see wp_trash_post()
     */
    public static function trashPost(int $id): Result
    {
        // `object` -> `WP_Post` -> Success: Post trashed
        // `bool`   -> `false`   -> Fail: Post not trashed
        // `null`   -> `null`    -> Fail: Post not trashed
        $result = wp_trash_post(post_id: $id);

        if (!$result instanceof WP_Post) {
            return Result::error(Message::PostTrashFailed)->withId($id);
        }

        return Result::success(Message::PostTrashSuccess)
            ->withId($result->ID)
            ->withData(['postTitle' => $result->post_title]);
    }

    /**
     * Restore a post from the trash.
     *
     * @param int $id
     * @return Result
     * @see wp_untrash_post()
     */
    public static function restorePost(int $id): Result
    {
        // `object` -> `WP_Post` -> Success: Post restored
        // `bool`   -> `false`   -> Fail: Post not restored
        // `null`   -> `null`    -> Fail: Post not restored
        $result = wp_untrash_post(post_id: $id);

        if (!$result instanceof WP_Post) {
            return Result::error(Message::PostRestoreFailed)->withId($id);
        }

        return Result::success(Message::PostRestoreSuccess)
            ->withId($result->ID)
            ->withData(['postTitle' => $result->post_title]);
    }

    /**
     * Permanently delete a post from the database.
     *
     * @param int $id
     * @return Result
     * @see wp_delete_post()
     */
    public static function deletePost(int $id): Result
    {
        // `object` -> `WP_Post` -> Success: Post deleted
        // `bool`   -> `false`   -> Fail: Post not deleted
        // `null`   -> `null`    -> Fail: Post not deleted
        $result = wp_delete_post(post_id: $id, force_delete: true);

        if (!$result instanceof WP_Post) {
            return Result::error(Message::PostDeleteFailed)->withId($id);
        }

        return Result::success(Message::PostDeleteSuccess)
            ->withId($result->ID)
            ->withData(['postTitle' => $result->post_title]);
    }

    // *************************************************************************

    /**
     * Save the post in the database.
     *
     * @return Result
     */
    public function save(): Result
    {
        return !$this->id ? $this->create() : $this->update();
    }

    /**
     * Create the post in the database.
     *
     * @return Result
     */
    public function create(): Result
    {
        if ($this->id !== null) {
            return Result::error(Message::PostAlreadyExists)
                ->withData($this->toArray());
        }

        $result = static::createPost(
            data: $this->toWpPostArray(except: ['ID', 'guid', 'comment_count'])
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $this->id = $result->getId();
        $this->reload();

        return $result;
    }

    /**
     * Update the post in the database.
     *
     * @return Result
     * @see wp_update_post()
     * @see is_wp_error()
     */
    public function update(): Result
    {
        if ($this->id === null) {
            return Result::error(Message::PostNotFound)
                ->withData($this->toArray());
        }

        $result = static::updatePost(
            data: $this->toWpPostArray(except: ['guid', 'comment_count'])
        );

        if ($result->hasFailed()) {
            return $result;
        }

        $this->reload();

        return $result;
    }

    /**
     * Move the post to the trash.
     *
     * @return Result
     */
    public function trash(): Result
    {
        if ($this->id === null) {
            return Result::error(Message::PostNotFound)
                ->withData($this->toArray());
        }

        $result = static::trashPost(id: $this->id);

        if ($result->hasFailed()) {
            return $result;
        }

        $this->reload();

        return $result;
    }

    /**
     * Restore the post from the trash.
     *
     * @return Result
     */
    public function restore(): Result
    {
        if ($this->id === null) {
            return Result::error(Message::PostNotFound)
                ->withData($this->toArray());
        }

        $result = static::restorePost(id: $this->id);

        if ($result->hasFailed()) {
            return $result;
        }

        $this->reload();

        return $result;
    }

    /**
     * Permanently delete the post from the database.
     *
     * @return Result
     * @see wp_delete_post()
     */
    public function delete(): Result
    {
        if ($this->id === null) {
            return Result::error(Message::PostNotFound)
                ->withData($this->toArray());
        }

        $result = static::deletePost(id: $this->id);

        if ($result->hasFailed()) {
            return $result;
        }

        $this->id = null;

        return $result;
    }

    // *************************************************************************

    /**
     * Get the post ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id ?? 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post author.
     *
     * @return int
     */
    public function getPostAuthor(): int
    {
        return $this->postAuthor ?? 0;
    }

    /**
     * Set the post author.
     *
     * @param int $postAuthor
     * @return static
     */
    public function setPostAuthor(int $postAuthor): static
    {
        $this->postAuthor = $postAuthor;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post created date and time.
     *
     * @return string 0000-00-00 00:00:00
     */
    public function getPostDate(): string
    {
        return $this->postDate ?? '';
    }

    /**
     * Set the post created date and time.
     *
     * @param string $postDate 0000-00-00 00:00:00
     * @return static
     */
    public function setPostDate(string $postDate): static
    {
        $this->postDate = $postDate;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post created date and time (GMT).
     *
     * @return string 0000-00-00 00:00:00
     */
    public function getPostDateGmt(): string
    {
        return $this->postDateGmt ?? '';
    }

    /**
     * Set the post created date and time (GMT).
     *
     * @param string $postDateGmt 0000-00-00 00:00:00
     * @return static
     */
    public function setPostDateGmt(string $postDateGmt): static
    {
        $this->postDateGmt = $postDateGmt;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post content.
     *
     * @return string Welcome to WordPress.
     */
    public function getPostContent(): string
    {
        return $this->postContent ?? '';
    }

    /**
     * Set the post content.
     *
     * @param string $postContent Welcome to WordPress.
     * @return static
     */
    public function setPostContent(string $postContent): static
    {
        $this->postContent = $postContent;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post title.
     *
     * @return string Hello World
     */
    public function getPostTitle(): string
    {
        return $this->postTitle ?? '';
    }

    /**
     * Set the post title.
     *
     * @param string $postTitle Hello World
     * @return static
     */
    public function setPostTitle(string $postTitle): static
    {
        $this->postTitle = $postTitle;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post excerpt.
     *
     * @return string
     */
    public function getPostExcerpt(): string
    {
        return $this->postExcerpt ?? '';
    }

    /**
     * Set the post excerpt.
     *
     * @param string $postExcerpt
     * @return static
     */
    public function setPostExcerpt(string $postExcerpt): static
    {
        $this->postExcerpt = $postExcerpt;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post status.
     *
     * @return string publish
     */
    public function getPostStatus(): string
    {
        return $this->postStatus ?? '';
    }

    /**
     * Set the post status.
     *
     * @param string $postStatus publish
     * @return static
     */
    public function setPostStatus(string $postStatus): static
    {
        $this->postStatus = $postStatus;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the comment status.
     *
     * @return string open
     */
    public function getCommentStatus(): string
    {
        return $this->commentStatus ?? '';
    }

    /**
     * Set the comment status.
     *
     * @param string $commentStatus open
     * @return static
     */
    public function setCommentStatus(string $commentStatus): static
    {
        $this->commentStatus = $commentStatus;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the ping status.
     *
     * @return string open
     */
    public function getPingStatus(): string
    {
        return $this->pingStatus ?? '';
    }

    /**
     * Set the ping status.
     *
     * @param string $pingStatus open
     * @return static
     */
    public function setPingStatus(string $pingStatus): static
    {
        $this->pingStatus = $pingStatus;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post password.
     *
     * @return string foobar
     */
    public function getPostPassword(): string
    {
        return $this->postPassword ?? '';
    }

    /**
     * Set the post password.
     *
     * @param string $postPassword foobar
     * @return static
     */
    public function setPostPassword(string $postPassword): static
    {
        $this->postPassword = $postPassword;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post name.
     *
     * @return string hello-world
     */
    public function getPostName(): string
    {
        return $this->postName ?? '';
    }

    /**
     * Set the post name.
     *
     * @param string $postName hello-world
     * @return static
     */
    public function setPostName(string $postName): static
    {
        $this->postName = $postName;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the URLs to ping.
     *
     * @return string https://example.org
     */
    public function getToPing(): string
    {
        return $this->toPing ?? '';
    }

    /**
     * Set the URLs to ping.
     *
     * @param string $toPing https://example.org
     * @return static
     */
    public function setToPing(string $toPing): static
    {
        $this->toPing = $toPing;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the pinged URLs.
     *
     * @return string https://example.org
     */
    public function getPinged(): string
    {
        return $this->pinged ?? '';
    }

    /**
     * Set the pinged URLs.
     *
     * @param string $pinged https://example.org
     * @return static
     */
    public function setPinged(string $pinged): static
    {
        $this->pinged = $pinged;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post modified date and time.
     *
     * @return string 0000-00-00 00:00:00
     */
    public function getPostModified(): string
    {
        return $this->postModified ?? '';
    }

    /**
     * Set the post modified date and time.
     *
     * @param string $postModified 0000-00-00 00:00:00
     * @return static
     */
    public function setPostModified(string $postModified): static
    {
        $this->postModified = $postModified;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post modified date and time (GMT).
     *
     * @return string 0000-00-00 00:00:00
     */
    public function getPostModifiedGmt(): string
    {
        return $this->postModifiedGmt ?? '';
    }

    /**
     * Set the post modified date and time (GMT).
     *
     * @param string $postModifiedGmt 0000-00-00 00:00:00
     * @return static
     */
    public function setPostModifiedGmt(string $postModifiedGmt): static
    {
        $this->postModifiedGmt = $postModifiedGmt;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the filtered post content.
     *
     * @return string
     */
    public function getPostContentFiltered(): string
    {
        return $this->postContentFiltered ?? '';
    }

    /**
     * Set the filtered post content.
     *
     * @param string $postContentFiltered
     * @return static
     */
    public function setPostContentFiltered(string $postContentFiltered): static
    {
        $this->postContentFiltered = $postContentFiltered;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post parent.
     *
     * @return int
     */
    public function getPostParent(): int
    {
        return $this->postParent ?? 0;
    }

    /**
     * Set the post parent.
     *
     * @param int $postParent
     * @return static
     */
    public function setPostParent(int $postParent): static
    {
        $this->postParent = $postParent;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the GUID.
     *
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid ?? '';
    }

    // -------------------------------------------------------------------------

    /**
     * Get the menu order.
     *
     * @return int
     */
    public function getMenuOrder(): int
    {
        return $this->menuOrder ?? 0;
    }

    /**
     * Set the menu order.
     *
     * @param int $menuOrder
     * @return static
     */
    public function setMenuOrder(int $menuOrder): static
    {
        $this->menuOrder = $menuOrder;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post type.
     *
     * @return string post
     */
    public function getPostType(): string
    {
        return $this->postType ?? '';
    }

    /**
     * Set the post type.
     *
     * @param string $postType post
     * @return static
     */
    public function setPostType(string $postType): static
    {
        $this->postType = $postType;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the post MIME type.
     *
     * @return string
     */
    public function getPostMimeType(): string
    {
        return $this->postMimeType ?? '';
    }

    /**
     * Set the post MIME type.
     *
     * @param string $postMimeType
     * @return static
     */
    public function setPostMimeType(string $postMimeType): static
    {
        $this->postMimeType = $postMimeType;

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Get the comment count.
     *
     * @return int
     */
    public function getCommentCount(): int
    {
        return $this->commentCount ?? 0;
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the post exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->getId() > 0;
    }

    // *************************************************************************

    /**
     * Load the instance from a post ID.
     *
     * @param int $id
     * @see get_post()
     */
    protected function loadFromId(int $id): void
    {
        if ($id === 0) {
            return;
        }

        $wpPost = get_post($id);

        if (!$wpPost instanceof WP_Post) {
            return;
        }

        $this->loadFromWpPost($wpPost);
    }

    /**
     * Load the instance from a post path.
     *
     * @param string $path hello-world
     * @param string $postType post
     * @see get_page_by_path()
     */
    protected function loadFromPath(string $path, string $postType): void
    {
        $wpPost = get_page_by_path(
            page_path: $path, post_type: $postType
        );

        if (!$wpPost instanceof WP_Post) {
            return;
        }

        $this->loadFromWpPost($wpPost);
    }

    /**
     * Load the instance from the global `WP_Post` instance.
     *
     * @see get_post()
     */
    protected function loadFromGlobalWpPost(): void
    {
        $wpPost = get_post();

        if (!$wpPost instanceof WP_Post) {
            return;
        }

        $this->loadFromWpPost($wpPost);
    }

    /**
     * Load the instance from the specified `WP_Post` instance.
     *
     * @param WP_Post $wpPost
     */
    protected function loadFromWpPost(WP_Post $wpPost): void
    {
        $this->wpPost = $wpPost;

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
     * Cast the post to an array to be used by WordPress functions.
     *
     * Remove keys from the array if the value is null, since that indicates
     * that no value has been set.
     *
     * @param array $except ['ID']
     * @return array ['post_author' => 1, ...]
     */
    protected function toWpPostArray(array $except = []): array
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

        return Filter::array($data)->except($except)->withoutNulls()->get();
    }
}