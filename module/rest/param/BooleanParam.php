<?php

namespace Charm\Module\Rest\Param;

/**
 * Class BooleanParam
 *
 * @author Ryan Sechrest
 * @package Charm\Module\Rest\Param
 */
class BooleanParam extends Param
{
    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        parent::load($data);
        $this->type = 'boolean';
    }
}