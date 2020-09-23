<?php

namespace Charm\Entity;

/**
 * Class Page
 *
 * @author Ryan Sechrest
 * @package Charm\Entity
 */
class Page extends Post
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
        return 'page';
    }
}