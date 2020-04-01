<?php

namespace Charm\DataType;

use DateTime as DT;
use DateTimeZone as DTZ;
use Exception;

/**
 * Class DateTime
 *
 * @author Ryan Sechrest
 * @package Charm\DataType
 */
class DateTime extends DT
{
    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize DateTime with timezone
     *
     * @param string $datetime
     * @param string $timezone
     * @return DateTime
     */
    public static function init(string $datetime, string $timezone = 'UTC'): DateTime
    {
        try {
            $datetime = new DateTime($datetime, new DTZ($timezone));
        } catch (Exception $e) {
            // Quiet please.
        }

        return $datetime;
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