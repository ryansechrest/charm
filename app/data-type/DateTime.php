<?php

namespace Charm\App\DataType;

use DateTime as DT;
use DateTimeZone;
use Exception;

/**
 * Class DateTime
 *
 * @author Ryan Sechrest
 * @package Charm\App\DataType
 */
class DateTime extends DT
{
    /**
     * Initialize DateTime in local timezone
     *
     * @throws Exception
     * @param string $datetime
     * @param string $timezone
     * @return DateTime
     */
    public static function init(string $datetime, string $timezone): DateTime
    {
        return new DateTime($datetime, new DateTimeZone($timezone));
    }

    /**
     * Initialize DateTime in UTC timezone
     *
     * @throws Exception
     * @param string $datetime
     * @return DateTime
     */
    public static function init_utc(string $datetime): DateTime
    {
        return new DateTime($datetime, new DateTimeZone('UTC'));
    }

    /************************************************************************************/
    // Format methods

    /**
     * Format for WordPress database
     *
     * @return string
     */
    public function format_db(): string
    {
        return $this->format('Y-m-d H:i:s');
    }
}