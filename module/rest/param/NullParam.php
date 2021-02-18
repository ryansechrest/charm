<?php

namespace Charm\Module\Rest\Param;

/**
 * Class NullParam
 *
 * @author Ryan Sechrest
 * @package Charm\Module\Rest\Param
 */
class NullParam extends Param
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
        $this->type = 'null';
    }
}