<?php

namespace Charm\Entity;

/**
 * Class BlogPost
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class BlogPost extends Post
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