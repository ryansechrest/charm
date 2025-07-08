<?php

namespace Charm\Models\Base;

use Charm\Contracts\IsPersistable;
use Charm\Contracts\Core\HasCorePost;
use Charm\Models\Metas\PostMeta;
use Charm\Models\Core;
use Charm\Support\Result;
use Charm\Traits\WithDeferredCalls;
use Charm\Traits\WithMeta;
use Charm\Traits\WithPersistenceState;
use Charm\Traits\WithTerms;
use WP_Post;
use WP_Query;

/**
 * Represents a base post in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class Post implements HasCorePost, IsPersistable
{
    use WithDeferredCalls;
    use WithMeta;
    use WithPersistenceState;
    use WithTerms;

    // -------------------------------------------------------------------------

    /**
     * Core post.
     *
     * @var ?Core\Post
     */
    protected ?Core\Post $corePost = null;

    // *************************************************************************

    /**
     * Ensures that the post type gets defined.
     *
     * @return string `post`, `page`, `attachment`, etc.
     */
    abstract protected static function postType(): string;

    /**
     * Set the class to be used when instantiating a post meta.
     *
     * @return string
     */
    protected static function metaClass(): string
    {
        return PostMeta::class;
    }

    // *************************************************************************

    /**
     * Post constructor.
     *
     * @param Core\Post|array $corePostOrData
     */
    public function __construct(Core\Post|array $corePostOrData = [])
    {
        $this->corePost = match (true) {
            $corePostOrData instanceof Core\Post => $corePostOrData,
            is_array($corePostOrData) => new Core\Post([
                ...$corePostOrData,
                'postType' => static::postType()
            ]),
            default => null
        };
    }

    // -------------------------------------------------------------------------

    /**
     * Get the core post instance.
     *
     * @return ?Core\Post
     */
    public function corePost(): ?Core\Post
    {
        return $this->corePost;
    }

    // *************************************************************************

    /**
     * Initialize the post.
     *
     * $key `int` -> Post ID
     *      `null` -> Global post
     *      `string` -> Post slug / Path
     *      `WP_Post` -> `WP_Post` instance
     *
     * @param int|null|string|WP_Post $key
     * @return ?static
     */
    public static function init(
        int|null|string|WP_Post $key = null
    ): ?static
    {
        $corePost = match (true) {
            is_numeric($key) => Core\Post::fromId((int) $key),
            is_string($key) => Core\Post::fromPath($key, static::postType()),
            $key instanceof WP_Post => Core\Post::fromWpPost($key),
            default => Core\Post::fromGlobalWpPost(),
        };

        if ($corePost === null) {
            return null;
        }

        if ($corePost->getPostType() !== static::postType()) {
            return null;
        }

        return new static($corePost);
    }

    /**
     * Initialize the post and preload all of its metas.
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
     * Get the posts.
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
        $corePosts = Core\Post::get($args);
        $posts = [];

        foreach ($corePosts as $corePost) {
            $posts[] = new static($corePost);
        }

        return $posts;
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
        return Core\Post::query($args);
    }

    // *************************************************************************

    /**
     * Save the post in the database.
     *
     * @return Result
     */
    public function save(): Result
    {
        return !$this->getId() ? $this->create() : $this->update();
    }

    /**
     * Create the post in the database.
     *
     * @return Result
     */
    public function create(): Result
    {
        $result = $this->corePost()->create();

        if ($result->hasFailed()) {
            return $result;
        }

        $result->addResults($this->runDeferred());

        return $result;
    }

    /**
     * Update the post in the database.
     *
     * @return Result
     */
    public function update(): Result
    {
        $result = $this->corePost()->update();

        if ($result->hasFailed()) {
            return $result;
        }

        $result->addResults($this->runDeferred());

        return $result;
    }

    /**
     * Move the post to the trash.
     *
     * @return Result
     */
    public function trash(): Result
    {
        return $this->corePost()->trash();
    }

    /**
     * Restore the post from the trash.
     *
     * @return Result
     */
    public function restore(): Result
    {
        return $this->corePost()->restore();
    }

    /**
     * Delete the post permanently.
     *
     * @return Result
     */
    public function delete(): Result
    {
        return $this->corePost()->delete();
    }

    // *************************************************************************

    /**
     * Get the post ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->corePost()->getId();
    }

    // -------------------------------------------------------------------------

    /**
     * Check whether the post exists in the database.
     *
     * @return bool
     */
    public function exists(): bool
    {
        return $this->corePost()->exists();
    }
}