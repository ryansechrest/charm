<?php

namespace Charm\Models\Base;

use Charm\Contracts\HasProxyPost;
use Charm\Contracts\IsPersistable;
use Charm\Models\Metas\PostMeta;
use Charm\Models\Proxy;
use Charm\Support\Result;
use Charm\Traits\WithDeferredCalls;
use Charm\Traits\WithMeta;
use Charm\Traits\WithPersistenceState;
use WP_Post;
use WP_Query;

/**
 * Represents a base post in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class Post implements HasProxyPost, IsPersistable
{
    use WithDeferredCalls;
    use WithMeta;
    use WithPersistenceState;

    // -------------------------------------------------------------------------

    /**
     * Proxy post
     *
     * @var ?Proxy\Post
     */
    protected ?Proxy\Post $proxyPost = null;

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
        $data['postType'] = static::postType();
        $this->proxyPost = new Proxy\Post($data);
    }

    // -------------------------------------------------------------------------

    /**
     * Get proxy post instance
     *
     * @return ?Proxy\Post
     */
    public function proxyPost(): ?Proxy\Post
    {
        return $this->proxyPost;
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
        $proxyPost = match (true) {
            is_numeric($key) => Proxy\Post::fromId((int) $key),
            is_string($key) => Proxy\Post::fromPath($key, static::postType()),
            $key instanceof WP_Post => Proxy\Post::fromWpPost($key),
            default => Proxy\Post::fromGlobalWpPost(),
        };

        if ($proxyPost === null) {
            return null;
        }

        if ($proxyPost->getPostType() !== static::postType()) {
            return null;
        }

        $post = new static();
        $post->proxyPost = $proxyPost;

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
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_query/
     *
     * @param array $args
     * @return static[]
     */
    public static function get(array $args = ['post_status' => 'any']): array
    {
        $args['post_type'] = static::postType();
        $proxyPosts = Proxy\Post::get($args);
        $posts = [];

        foreach ($proxyPosts as $proxyPost) {
            $post = new static();
            $post->proxyPost = $proxyPost;
            $posts[] = $post;
        }

        return $posts;
    }

    /**
     * Query posts with WP_Query
     *
     * See possible arguments:
     * https://developer.wordpress.org/reference/classes/wp_query/
     *
     * @param array $args
     * @return WP_Query
     */
    public static function query(array $args): WP_Query
    {
        return Proxy\Post::query($args);
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
        $result = $this->proxyPost()->create();

        if ($result->hasFailed()) {
            return $result;
        }

        $result->addResults($this->runDeferred());

        return $result;
    }

    /**
     * Update existing post
     *
     * @return Result
     */
    public function update(): Result
    {
        $result = $this->proxyPost()->update();

        if ($result->hasFailed()) {
            return $result;
        }

        $result->addResults($this->runDeferred());

        return $result;
    }

    /**
     * Move post to trash
     *
     * @return Result
     */
    public function trash(): Result
    {
        return $this->proxyPost()->trash();
    }

    /**
     * Restore post from trash
     *
     * @return Result
     */
    public function restore(): Result
    {
        return $this->proxyPost()->restore();
    }

    /**
     * Delete post permanently
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->proxyPost()->delete();
    }

    // *************************************************************************

    /**
     * Get ID
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->proxyPost()->getId();
    }

    // -------------------------------------------------------------------------

    /**
     * Whether post exists in database
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->proxyPost()->exists();
    }
}