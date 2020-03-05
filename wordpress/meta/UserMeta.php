<?php

namespace Charm\WordPress\Meta;

use Charm\WordPress\Core\Meta;

/**
 * Class UserMeta
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Meta
 */
class UserMeta extends Meta
{
    /**
     * Default constructor
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $data['meta_type'] = 'user';
        parent::__construct($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Get user meta
     *
     * @param int $user_id
     * @param string $meta_key
     * @param string $meta_type
     * @return null|UserMeta|UserMeta[]
     */
    public static function get($user_id, $meta_key = '', $meta_type = 'user')
    {
        return parent::get($meta_type, $user_id, $meta_key);
    }
}