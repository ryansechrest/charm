<?php

namespace Charm\Models\Base;

use Charm\Contracts\HasWpPost;
use Charm\Models\WordPress;
use Charm\Support\Result;
use WP_Post;
use WP_Query;

/**
 * Represents a base post in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
abstract class Post implements HasWpPost
{
    /**
     * Post type
     */
    protected const POST_TYPE = '';

    /*------------------------------------------------------------------------*/

    /**
     * WordPress post
     *
     * @var ?WordPress\Post
     */
    protected ?WordPress\Post $wpPost = null;

    /**************************************************************************/

    /**
     * Post constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->wpPost = new WordPress\Post($data);
    }

    /**
     * Get WordPress post instance
     *
     * @return ?WordPress\Post
     */
    public function wp(): ?WordPress\Post
    {
        return $this->wpPost;
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
        if (!$wpPost = WordPress\Post::init($key)) {
            return null;
        }

        if ($wpPost->getPostType() !== static::POST_TYPE) {
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

    /**************************************************************************/

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
        return $this->wp()->create();
    }

    /**
     * Update existing post
     *
     * @return Result
     */
    public function update(): Result
    {
        return $this->wp()->update();
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
}