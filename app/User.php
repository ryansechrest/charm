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
     * Created date
     *
     * @var DateTime|null
     */
    protected $registration_date = null;

    /************************************************************************************/
    // Object access methods

    /**
     * Get registration date
     *
     * @return DateTime
     */
    public function registration_date()
    {
        return $this->registration_date = DateTime::init($this->post_date_gmt);
    }
}