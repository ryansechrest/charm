<?php

namespace Charm\Models\WordPress;

/**
 * Represents a post in WordPress.
 *
 * @author Ryan Sechrest <ryan@sechrest.dev>
 * @package Charm
 */
class Post extends BasePost
{
    /**
     * Post type for a post in WordPress
     */
    protected const POST_TYPE = 'post';
}