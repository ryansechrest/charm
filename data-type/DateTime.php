<?php

namespace Charm\DataType;

use DateInterval as DI;
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

    /**
     * Get duration between start and end
     *
     * @param int $start
     * @param int $end
     * @return string
     */
    public static function duration(int $start, int $end): string
    {
        $duration = [];
        try {
            $start_dt = new DateTime("@" . $start);
            $end_dt = new DateTime("@" . $end);
            $diff = $start_dt->diff($end_dt);

            if ($diff->format('%d') > 0) {
                $duration[] = $diff->format('%dd');
            }
            if ($diff->format('%h') > 0) {
                $duration[] = $diff->format('%hh');
            }
            if ($diff->format('%i') > 0) {
                $duration[] = $diff->format('%im');
            }
            if ($diff->format('%s') > 0) {
                $duration[] = $diff->format('%ss');
            }
        } catch (Exception $e) {
            // Quiet please.
        }

        return implode(' ', $duration);
    }

    /************************************************************************************/
    // Chainable calculation methods

    /**
     * Add time to date
     *
     * @param string $duration
     * @return DateTime
     */
    public function addTime(string $duration): DateTime
    {
        if (!$di = DI::createFromDateString($duration)) {
            return $this;
        }

        return $this->add($di);
    }

    /**
     * Subtract time from date
     *
     * @param string $duration
     * @return DateTime
     */
    public function subtractTime(string $duration): DateTime
    {
        if (!$di = DI::createFromDateString($duration)) {
            return $this;
        }

        return $this->sub($di);
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

    /**
     * Format to display time elapsed
     *
     * @return string
     */
    public function elapsed(): string
    {
        $format = 's';

        return $this->format($format);
    }

    /************************************************************************************/
    // Chainable set methods

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return DateTime
     */
    public function timezone(string $timezone): DateTime
    {
        return $this->setTimezone(new DTZ($timezone));
    }
}