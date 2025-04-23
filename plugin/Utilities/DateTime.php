<?php

namespace Charm\Utilities;

use DateTimeImmutable;
use DateTimeZone;

/**
 * Represents a date and time in WordPress.
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class DateTime
{
    /**
     * Fallback timezone
     */
    protected const TIMEZONE = 'UTC';

    /**
     * Fallback date format
     */
    protected const DATE_FORMAT = 'Y-m-d';

    /**
     * Fallback time format
     */
    protected const TIME_FORMAT = 'H:i:s';

    /*------------------------------------------------------------------------*/

    /**
     * Date and time
     *
     * @var DateTimeImmutable
     */
    protected DateTimeImmutable $dti;

    /**
     * Timezone
     *
     * @var ?DateTimeZone
     */
    protected ?DateTimeZone $timezone = null;

    /**************************************************************************/

    /**
     * Initialize DateTime from WordPress GMT date string
     *
     * @param string $wpGmtDateTime
     * @return static
     */
    public static function init(string $wpGmtDateTime): static
    {
        $dateTime = new static();

        $dateTime->dti = new DateTimeImmutable(
            $wpGmtDateTime, new DateTimeZone('UTC')
        );

        return $dateTime;
    }

    /**
     * Initialize DateTime from current time
     *
     * @return static
     */
    public static function now(): static
    {
        return static::init('now');
    }

    /**************************************************************************/

    /**
     * Get timezone string from WordPress
     *
     * @return string
     * @see get_option()
     */
    protected function getWpTimezoneString(): string
    {
        return static::getWpOption(
            'timezone_string', static::TIMEZONE
        );
    }

    /**
     * Get date format from WordPress
     *
     * @return string
     */
    protected function getWpDateFormat(): string
    {
        return static::getWpOption(
            'date_format', static::DATE_FORMAT
        );
    }

    /**
     * Get time format from WordPress
     *
     * @return string
     */
    protected function getWpTimeFormat(): string
    {
        return static::getWpOption(
            'time_format', static::TIME_FORMAT
        );
    }

    /**
     * Get option from WordPress
     *
     * @param string $option
     * @param string $fallback
     * @return string
     * @see get_option()
     */
    protected function getWpOption(string $option, string $fallback): string
    {
        $value = get_option($option);

        if (!is_string($value)) {
            return $fallback;
        }

        if ($value === '') {
            return $fallback;
        }

        return $value;
    }

    /**************************************************************************/

    /**
     * Set timezone as UTC
     *
     * @return static
     */
    public function asUtc(): static
    {
        $this->timezone = new DateTimeZone('UTC');

        return $this;
    }

    /**
     * Set timezone as configured in WordPress
     *
     * @return static
     */
    public function asLocal(): static
    {
        $this->timezone = new DateTimeZone($this->getWpTimezoneString());

        return $this;
    }

    /**
     * Set timezone as provided
     *
     * @param string $timezone
     * @return static
     */
    public function as(string $timezone): static
    {
        $this->timezone = new DateTimeZone($timezone);

        return $this;
    }

    /**************************************************************************/

    /**
     * Format UTC time using WordPress database format
     *
     * @return string
     */
    public function formatForDb(): string
    {
        return $this->dti->format(
            static::DATE_FORMAT . ' ' . static::TIME_FORMAT
        );
    }

    /**
     * Format UTC time using WordPress or custom format
     *
     * @param string $format
     * @return string
     */
    public function format(string $format = ''): string
    {
        if ($format === '') {
            $format = $this->getWpDateFormat() . ' ' . $this->getWpTimeFormat();
        }

        $timezone = $this->timezone ?? new DateTimeZone(
            $this->getWpTimezoneString()
        );

        return $this->dti->setTimezone($timezone)->format($format);
    }

    /**
     * Return local formatted string (default behavior)
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->format();
    }
}