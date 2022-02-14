<?php

namespace Charm\Admin;

/**
 * Class Section
 *
 * @author Ryan Sechrest
 * @package Charm\Admin
 */
class Section
{
    /************************************************************************************/
    // Default constructor and load method

    /**
     * Setting constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (count($data) === 0) {
            return;
        }
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {

    }
}