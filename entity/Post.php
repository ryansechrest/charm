<?php

namespace Charm\Entity;

/**
 * Class Post
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Post extends BasePost
{
    /************************************************************************************/
    // Object access methods

    /**
     * Get post type
     *
     * @return string
     */
    public static function post_type(): string
    {
        return 'post';
    }
}