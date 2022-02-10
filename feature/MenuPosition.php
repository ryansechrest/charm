<?php

namespace Charm\Feature;

/**
 * Trait MenuPosition
 *
 * @author Ryan Sechrest
 * @package Charm\Feature
 */
trait MenuPosition
{
    /************************************************************************************/
    // Properties

    /**
     * Menu positions
     */
    protected array $menu_positions = [
        'site' => [
            'dashboard' => 2,
            'posts' => 5,
            'media' => 10,
            'links' => 15,
            'pages' => 20,
            'comments' => 25,
            'appearance' => 60,
            'plugins' => 65,
            'users' => 70,
            'tools' => 75,
            'settings' => 80,
            'separator' => [
                1 => 4,
                2 => 59,
                3 => 99,
            ],
        ],
        'network' => [
            'dashboard' => 2,
            'sites' => 5,
            'users' => 10,
            'themes' => 15,
            'plugins' => 20,
            'settings' => 25,
            'updates' => 30,
            'separator' => [
                1 => 4,
                2 => 99,
            ],
        ],
    ];

    /************************************************************************************/
    // Get and set methods

    /**
     * Get menu positions
     *
     * @return array
     */
    public function get_menu_positions(): array
    {
        return $this->menu_positions;
    }
}