<?php

namespace Charm\WordPress\Meta;

use Charm\WordPress\Meta;

/**
 * Class PostMeta
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Meta
 */
class PostMeta extends Meta
{
    /**
     * PostMeta constructor
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
     * Initialize post meta(s)
     *
     * @param array $params
     * @return null|PostMeta|PostMeta[]
     */
    public static function init($params)
    {
        if (!isset($params['post_id'])) {
            return null;
        }
        $params['meta_type'] = 'post';
        $params['object_id'] = $params['post_id'];

        return parent::init($params);
    }

    /**
     * Get post metas
     *
     * @todo Implement PostMeta::get()
     * @param array $params
     * @return PostMeta[]
     */
    public static function get(array $params): array
    {
        return [];
    }
}