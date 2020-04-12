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
    public static function init(string $datetime = 'now', string $timezone = 'UTC'): DateTime
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

    /************************************************************************************/
    // Chainable set methods

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return DateTime
     */
    public function timezone(string $timezone)
    {
        return $this->setTimezone(new DTZ($timezone));
    }
}