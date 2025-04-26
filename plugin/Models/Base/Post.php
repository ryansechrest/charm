<?php

namespace Charm\Models\Base;

use Charm\Contracts\HasWpPost;
use Charm\Contracts\IsPersistable;
use Charm\Models\PostMeta;
use Charm\Models\WordPress;
use Charm\Support\Result;
use Charm\Traits\HasPersistenceState;
use Charm\Traits\Metas\HasMeta;
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
    use HasMeta;
    use HasPersistenceState;

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
     * @param int|null|string|WP_Post $key
     * @return ?static
     */
    public static function init(int|null|string|WP_Post $key = null): ?static
    {
        if (!$wpPost = WordPress\Post::init($key)) {
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
        return $this->wp()->save();
    }

    /**
     * Create new post
     *
     * @return Result
     */
    public function create(): Result
    {
        $result = $this->wp()->create();

        // Don't proceed with metas if create failed
        if ($result->hasFailed()) {
            return $result;
        }

        // Ensure all metas have newly created post ID
        $this->fillMetasWithObjectId($this->wp()->getId());

        // Persist metas in database and save results
        $result->addResults($this->persistMetas());

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

        // Don't proceed with metas if update failed
        if ($result->hasFailed()) {
            return $result;
        }

        // Persist metas in database and save results
        $result->addResults($this->persistMetas());

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
}