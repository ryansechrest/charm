<?php

namespace Charm\Models\WordPress;

/**
 * Represents a page in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Page extends BasePost
{
    /**
     * Post type for a page in WordPress
     */
    protected const POST_TYPE = 'page';
}