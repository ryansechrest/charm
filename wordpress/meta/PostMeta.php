<?php

namespace Charm\WordPress\Meta;

use Charm\WordPress\Core\Meta;

/**
 * Class PostMeta
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Meta
 */
class PostMeta extends Meta
{
    /**
     * Default constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['meta_type'] = 'post';
        parent::__construct($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Get post meta
     *
     * @param int $post_id
     * @param string $meta_key
     * @param string $meta_type
     * @return null|PostMeta|PostMeta[]
     */
    public static function get($post_id, $meta_key = '', $meta_type = 'post')
    {
        return parent::get($meta_type, $post_id, $meta_key);
    }
}