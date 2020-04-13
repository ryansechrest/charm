<?php

namespace Charm\Cron;

use Charm\DataType\DateTime;
use Charm\WordPress\Cron\Event as WpEvent;

/**
 * Class Event
 *
 * @author Ryan Sechrest
 * @package Charm\Cron
 */
class Event extends WpEvent
{
    /************************************************************************************/
    // Constants

    /**
     * Timezone
     *
     * Example: America/Chicago
     *
     * @var string
     */
    const TIMEZONE = '';

    /**
     * DateTime format
     *
     * Example: n/j/Y g:i:sa
     *
     * @var string
     */
    const FORMAT = '';

    /************************************************************************************/
    // Properties

    /**
     * Timestamp object
     *
     * @var DateTime
     */
    protected $timestamp_obj = null;

    /************************************************************************************/
    // Cast methods

    /**
     * Get events as HTML table
     *
     * @return string
     */
    public static function to_html(): string
    {
        $timezone = get_option('timezone_string');
        if (static::TIMEZONE !== '') {
            $timezone = static::TIMEZONE;
        }
        $format = get_option('date_format') . ' ' . get_option('time_format');
        if (static::FORMAT !== '') {
            $format = static::FORMAT;
        }
        $now = DateTime::init('now', $timezone);
        $output = '<p>' . sprintf(
            _x('It\'s %s in %s timezone. You can change your date/time format and timezone in the <a href="' . admin_url('options-general.php') . '">general settings</a>.', 'Tools: Cron Viewer', 'charm'),
            '<b>' . $now->format($format) . '</b> (' . $now->getTimestamp() . ')',
            '<b>' . $timezone . '</b>'
        ) . '</p>';
        $output .= '<table>';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<th>' . _x('Hook', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Run On', 'Tools: Cron Viewer', 'charm') .' ↓</th>';
        $output .= '<th>' . _x('Run In', 'Tools: Cron Viewer', 'charm') .' ↓</th>';
        $output .= '<th>' . _x('Timestamp', 'Tools: Cron Viewer', 'charm') .' ↓</th>';
        $output .= '<th>' . _x('Schedule', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Interval', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Args', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Key', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        foreach (Event::get() as $event) {
            $timestamp = $event->timestamp()->timezone($timezone);
            $difference = $timestamp->diff($now);
            $output .= '<tr>';
            $output .= '<td>' . $event->get_hook() . '</td>';
            $output .= '<td>' . $timestamp->format($format) . '</td>';
            $color = 'green';
            if ($difference->invert === 0) {
                $color = 'red';
            }
            $run_in_format = '%hh %im %ss';
            if ($difference->days > 0) {
                $run_in_format = '%dd ' . $run_in_format;
            }
            $output .= '<td class="' . $color . '">' . $difference->format($run_in_format) . '</td>';
            $output .= '<td>' . $event->get_timestamp() . '</td>';
            $output .= '<td>' . $event->get_schedule() . '</td>';
            $output .= '<td>' . $event->get_interval() . '</td>';
            $output .= '<td>';
            if (count($event->get_args()) !== 0) {
                $output .= '<pre>' . print_r($event->get_args(), true) . '</pre>';
            } else {
                $output .= '';
            }
            $output .= '</td>';
            $output .= '<td>' . $event->get_key() . '</td>';
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';

        return $output;
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get timestamp
     *
     * @return DateTime|null
     */
    public function timestamp()
    {
        if ($this->timestamp_obj) {
            return $this->timestamp_obj;
        }
        if (!$this->timestamp) {
            return null;
        }
        $timestamp = DateTime::init();
        $timestamp->setTimestamp($this->timestamp);

        return $this->timestamp_obj = $timestamp;
    }
}