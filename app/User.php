<?php

namespace Charm\App;

use Charm\App\DataType\DateTime;
use Charm\WordPress\User as WpUser;

/**
 * Class User
 *
 * @author Ryan Sechrest
 * @package Charm\App
 */
class User extends WpUser
{
    /**
     * DateTime class
     *
     * @var string
     */
    const DATETIME = 'Charm\App\DataType\DateTime::init';

    /************************************************************************************/
    // Object properties

    /**
     * User registered object
     *
     * @var DateTime|null
     */
    protected $user_registered_obj = null;

    /************************************************************************************/
    // Object access methods

    /**
     * Get registration date
     *
     * @return DateTime
     */
    public function user_registered()
    {
        return $this->user_registered_obj = call_user_func(
            static::DATETIME, $this->user_registered
        );
    }
}