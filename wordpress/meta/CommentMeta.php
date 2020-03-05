<?php

namespace Charm\WordPress\Meta;

use Charm\WordPress\Core\Meta;

/**
 * Class CommentMeta
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Meta
 */
class CommentMeta extends Meta
{
    /**
     * Default constructor
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $data['meta_type'] = 'comment';
        parent::__construct($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Get comment meta
     *
     * @param int $comment_id
     * @param string $meta_key
     * @param string $meta_type
     * @return CommentMeta|CommentMeta[]|null
     */
    public static function get($comment_id, $meta_key = '', $meta_type = 'comment')
    {
        return parent::get($meta_type, $comment_id, $meta_key);
    }
}