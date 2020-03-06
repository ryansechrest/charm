<?php

namespace Charm\WordPress\Meta;

use Charm\WordPress\Meta;

/**
 * Class UserMeta
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Meta
 */
class UserMeta extends Meta
{
    /**
     * UserMeta constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['meta_type'] = 'user';
        parent::__construct($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize comment user(s)
     *
     * @param array $params
     * @return null|UserMeta|UserMeta[]
     */
    public static function init($params)
    {
        if (!isset($params['user_id'])) {
            return null;
        }
        $params['meta_type'] = 'user';
        $params['object_id'] = $params['user_id'];

        return parent::init($params);
    }

    /**
     * Get user metas
     *
     * @todo Implement UserMeta::get()
     * @param array $params
     * @return UserMeta[]
     */
    public static function get(array $params): array
    {
        return [];
    }
}