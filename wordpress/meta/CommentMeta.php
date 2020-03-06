<?php

namespace Charm\WordPress\Meta;

use Charm\WordPress\Meta;

/**
 * Class CommentMeta
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Meta
 */
class CommentMeta extends Meta
{
    /**
     * CommentMeta constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $data['meta_type'] = 'comment';
        parent::__construct($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize comment meta(s)
     *
     * @param array $params
     * @return CommentMeta|CommentMeta[]|null
     */
    public static function init($params)
    {
        if (!isset($params['comment_id'])) {
            return null;
        }
        $params['meta_type'] = 'comment';
        $params['object_id'] = $params['comment_id'];

        return parent::init($params);
    }

    /**
     * Get comment metas
     *
     * @todo Implement CommentMeta::get()
     * @param array $params
     * @return CommentMeta[]
     */
    public static function get(array $params): array
    {
        return [];
    }
}