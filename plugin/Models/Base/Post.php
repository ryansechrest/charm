<?php

namespace Charm\Models\Base;

use Charm\Contracts\HasWpPost;
use Charm\Contracts\IsPersistable;
use Charm\Models\PostMeta;
use Charm\Models\WordPress;
use Charm\Support\Result;
use Charm\Traits\WithDeferredPersistence;
use Charm\Traits\WithPersistenceState;
use Charm\Traits\WithMeta;
use WP_Post;
use WP_Query;

/**
 * Represents a base post in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class Post implements HasWpPost, IsPersistable
{
    use WithDeferredPersistence;
    use WithMeta;
    use WithPersistenceState;

    // -------------------------------------------------------------------------

    /**
     * WordPress post
     *
     * @var ?WordPress\Post
     */
    protected ?WordPress\Post $wpPost = null;

    // *************************************************************************

    /**
     * Force post type definition
     *
     * e.g. `post`, `page`, `attachment`, etc.
     *
     * @return string
     */
    abstract protected static function postType(): string;

    /**
     * Define default meta class
     *
     * @return string
     */
    protected static function metaClass(): string
    {
        return PostMeta::class;
    }

    // *************************************************************************

    /**
     * Post constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->wpPost = new WordPress\Post($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get WordPress post instance
     *
     * @return ?WordPress\Post
     */
    public function wp(): ?WordPress\Post
    {
        return $this->wpPost;
    }

    // *************************************************************************

    /**
     * Initialize post
     *
     * int     -> Post ID
     * null    -> Global Post
     * string  -> Post Slug / Path
     * WP_Post -> WP_Post instance
     *
     * @param int|null|string|WP_Post $key
     * @return ?static
     */
    public static function init(
        int|null|string|WP_Post $key = null
    ): ?static
    {
        $wpPost = match (true) {
            is_numeric($key) => WordPress\Post::fromId((int) $key),
            is_string($key) => WordPress\Post::fromPath($key, static::postType()),
            $key instanceof WP_Post => WordPress\Post::fromWpPost($key),
            default => WordPress\Post::fromGlobalWpPost(),
        };

        if ($wpPost === null) {
            return null;
        }

        if ($wpPost->getPostType() !== static::postType()) {
            return null;
        }

        $post = new static();
        $post->wpPost = $wpPost;

        return $post;
    }

    /**
     * Initialize post and preload metas
     *
     * @param int|string|WP_Post|null $key
     * @return ?static
     */
    public static function withMetas(
        int|null|string|WP_Post $key = null
    ): ?static
    {
        return static::init($key)?->preloadMetas();
    }

    // -------------------------------------------------------------------------

    /**
     * Get posts
     *
     * @param array $params
     * @return static[]
     */
    public static function get(array $params): array
    {
        $wpPosts = WordPress\Post::get($params);

        $posts = [];

        foreach ($wpPosts as $wpPost) {
            $post = new static();
            $post->wpPost = $wpPost;
            $posts[] = $post;
        }

        return $posts;
    }

    /**
     * Query posts with WP_Query
     *
     * @param array $params
     * @return WP_Query
     */
    public static function query(array $params): WP_Query
    {
        return WordPress\Post::query($params);
    }

    // *************************************************************************

    /**
     * Save post
     *
     * @return Result
     */
    public function save(): Result
    {
        return !$this->getId() ? $this->create() : $this->update();
    }

    /**
     * Create new post
     *
     * @return Result
     */
    public function create(): Result
    {
        $result = $this->wp()->create();

        if ($result->hasFailed()) {
            return $result;
        }

        $result->addResults($this->persistDeferred());

        return $result;
    }

    /**
     * Update existing post
     *
     * @return Result
     */
    public function update(): Result
    {
        $result = $this->wp()->update();

        if ($result->hasFailed()) {
            return $result;
        }

        $result->addResults($this->persistDeferred());

        return $result;
    }

    /**
     * Move post to trash
     *
     * @return Result
     */
    public function trash(): Result
    {
        return $this->wp()->trash();
    }

    /**
     * Restore post from trash
     *
     * @return Result
     */
    public function restore(): Result
    {
        return $this->wp()->restore();
    }

    /**
     * Delete post permanently
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->wp()->delete();
    }

    // *************************************************************************

    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->wp()->getId();
    }

    // -------------------------------------------------------------------------

    /**
     * Whether post exists in database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->wp()->exists();
    }
}