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
     * Fallback timezone.
     */
    protected const TIMEZONE = 'UTC';

    /**
     * Fallback date format.
     */
    protected const DATE_FORMAT = 'Y-m-d';

    /**
     * Fallback time format.
     */
    protected const TIME_FORMAT = 'H:i:s';

    // -------------------------------------------------------------------------

    /**
     * Date and time.
     *
     * @var DateTimeImmutable
     */
    protected DateTimeImmutable $dti;

    /**
     * Timezone.
     *
     * @var ?DateTimeZone
     */
    protected ?DateTimeZone $timezone = null;

    // *************************************************************************

    /**
     * Initialize the `DateTime` from the WordPress GMT date string.
     *
     * @param string $wpGmtDateTime
     * @return static
     */
    public static function init(string $wpGmtDateTime): static
    {
        $dateTime = new static();

        $dateTime->dti = new DateTimeImmutable(
            datetime: $wpGmtDateTime,
            timezone: new DateTimeZone('UTC')
        );

        return $dateTime;
    }

    /**
     * Initialize the `DateTime` from the current time.
     *
     * @return static
     */
    public static function now(): static
    {
        return static::init(wpGmtDateTime: 'now');
    }

    // *************************************************************************

    /**
     * Set the timezone as provided.
     *
     * @param string $timezone
     * @return static
     */
    public function as(string $timezone): static
    {
        $this->timezone = new DateTimeZone($timezone);

        return $this;
    }

    /**
     * Set the timezone as configured in WordPress.
     *
     * @return static
     */
    public function asLocal(): static
    {
        $this->as(timezone: $this->getWpTimezoneString());

        return $this;
    }

    // -------------------------------------------------------------------------

    /**
     * Format the UTC date and time using the WordPress or a custom format.
     *
     * @param string $format
     * @return string
     */
    public function format(string $format = ''): string
    {
        if ($format === '') {
            $format = $this->getWpDateFormat() . ' \a\t ' . $this->getWpTimeFormat();
        }

        $timezone = $this->timezone ?? new DateTimeZone(
            $this->getWpTimezoneString()
        );

        return $this->dti->setTimezone($timezone)->format($format);
    }

    /**
     * Format the UTC date and time using the WordPress database format.
     *
     * @return string
     */
    public function formatForDb(): string
    {
        return $this->dti->format(
            static::DATE_FORMAT . ' ' . static::TIME_FORMAT
        );
    }

    // -------------------------------------------------------------------------

    /**
     * Return the formatted date and time string using the WordPress format.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->format();
    }

    // *************************************************************************

    /**
     * Get the timezone string from WordPress.
     *
     * @return string
     * @see get_option()
     */
    protected function getWpTimezoneString(): string
    {
        return static::getWpOption(
            option: 'timezone_string', fallback: static::TIMEZONE
        );
    }

    /**
     * Get the date format from WordPress.
     *
     * @return string
     */
    protected function getWpDateFormat(): string
    {
        return static::getWpOption(
            option: 'date_format', fallback: static::DATE_FORMAT
        );
    }

    /**
     * Get the time format from WordPress.
     *
     * @return string
     */
    protected function getWpTimeFormat(): string
    {
        return static::getWpOption(
            option: 'time_format', fallback: static::TIME_FORMAT
        );
    }

    /**
     * Get an option from WordPress.
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
}