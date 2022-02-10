<?php

namespace Charm\Feature;

/**
 * Trait ParentSlug
 *
 * @author Ryan Sechrest
 * @package Charm\Feature
 */
trait ParentSlug
{
    /************************************************************************************/
    // Properties

    /**
     * Parent slugs
     */
    protected array $parent_slugs = [
        'site' => [
            'dashboard' => 'index.php',
            'posts' => 'edit.php',
            'media' => 'upload.php',
            'pages' => 'edit.php?post_type=page',
            'comments' => 'edit-comments.php',
            'appearance' => 'themes.php',
            'plugins' => 'plugins.php',
            'users' => 'users.php',
            'tools' => 'tools.php',
            'settings' => 'options-general.php',
        ],
        'network' => [
            'dashboard' => 'index.php',
            'sites' => 'sites.php',
            'users' => 'users.php',
            'themes' => 'themes.php',
            'plugins' => 'plugins.php',
            'settings' => 'settings.php',
        ],
    ];

    /************************************************************************************/
    // Get and set methods

    /**
     * Get parent slugs
     *
     * @return array
     */
    public function get_parent_slugs(): array
    {
        return $this->parent_slugs;
    }
}