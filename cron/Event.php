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
     * Date format
     *
     * Example: n/j/Y
     *
     * @var string
     */
    const DATE_FORMAT = '';

    /**
     * Time format
     *
     * Example: g:i:sa
     *
     * @var string
     */
    const TIME_FORMAT = '';

    /************************************************************************************/
    // Properties

    /**
     * Timezone
     *
     * @var string
     */
    protected $timezone = '';

    /**
     * Date format
     *
     * @var string
     */
    protected $date_format = '';

    /**
     * Time format
     *
     * @var string
     */
    protected $time_format = '';

    /**
     * Run on
     *
     * @var string
     */
    protected $run_on = '';

    /**
     * Run in
     *
     * @var string
     */
    protected $run_in = '';

    /**
     * Run every
     *
     * @var string
     */
    protected $run_every = '';

    /**
     * Actions
     *
     * @var array
     */
    protected $actions = [];

    /*----------------------------------------------------------------------------------*/

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
        $output = '<table>';
        $output .= '<thead>';
        $output .= '<tr>';
        $output .= '<th>' . _x('Hook', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Timestamp', 'Tools: Cron Viewer', 'charm') .' ↓</th>';
        $output .= '<th>' . _x('Run On', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Run In', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Run Every', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Schedule', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Actions', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '<th>' . _x('Args', 'Tools: Cron Viewer', 'charm') .'</th>';
        //$output .= '<th>' . _x('Key', 'Tools: Cron Viewer', 'charm') .'</th>';
        $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
        foreach (Event::get() as $event) {
            $output .= '<tr>';
            $output .= '<td>' . $event->get_hook() . '</td>';
            $output .= '<td>' . $event->get_timestamp() . '</td>';
            $output .= '<td>' . $event->get_run_on() . '</td>';
            $output .= '<td>' . $event->get_run_in() . '</td>';
            $output .= '<td>' . $event->get_run_every() . '</td>';
            $output .= '<td>' . $event->get_schedule() . '</td>';
            $output .= '<td>';
            if (count($event->get_actions()) !== 0) {
                $output .= '<pre>' . implode('<br />', $event->get_actions_for_display()) . '</pre>';
            } else {
                $output .= '<i>' . _x('Unknown', 'Tools: Cron Viewer', 'charm') . '</i>';
            }
            $output .= '</td>';
            $output .= '<td>';
            if (count($event->get_args()) !== 0) {
                $output .= '<pre>' . print_r($event->get_args(), true) . '</pre>';
            } else {
                $output .= '<i>' . _x('None', 'Tools: Cron Viewer', 'charm') . '</i>';
            }
            $output .= '</td>';
            //$output .= '<td>' . $event->get_key() . '</td>';
            $output .= '</tr>';
        }
        $output .= '</tbody>';
        $output .= '</table>';
        $output .= '<p>' . _x('<span class="green"><b>Green Time</b></span> → Event is on schedule. | <span class="red"><b>Red Time</b></span> → Event is late.', 'Tools: Cron Viewer', 'charm') . '</p>';

        return $output;
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get timestamp
     *
     * @return DateTime|null
     */
    public function timestamp(): ?DateTime
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

    /************************************************************************************/
    // Get methods

    /**
     * Get timezone from WordPress or constant
     *
     * @return string
     */
    public function get_timezone(): string
    {
        if ($this->timezone !== '') {
            return $this->timezone;
        }
        $this->timezone = get_option('timezone_string');
        if (static::TIMEZONE !== '') {
            $this->timezone = static::TIMEZONE;
        }

        return $this->timezone;
    }

    /**
     * Get date format from WordPress or constant
     *
     * @return string
     */
    public function get_date_format(): string
    {
        if ($this->date_format !== '') {
            return $this->date_format;
        }
        $this->date_format = get_option('date_format');
        if (static::DATE_FORMAT !== '') {
            $this->date_format = static::DATE_FORMAT;
        }

        return $this->date_format;
    }

    /**
     * Get time format from WordPress or constant
     *
     * @return string
     */
    public function get_time_format(): string
    {
        if ($this->time_format !== '') {
            return $this->time_format;
        }
        $this->time_format = get_option('time_format');
        if (static::TIME_FORMAT !== '') {
            $this->time_format = static::TIME_FORMAT;
        }

        return $this->time_format;
    }

    /**
     * Get date and time format combined
     *
     * @return string
     */
    public function get_date_time_format(): string
    {
        return $this->get_date_format() . ' ' . $this->get_time_format();
    }

    /**
     * Get run on
     *
     * @return string
     */
    public function get_run_on(): string
    {
        if ($this->run_on !== '') {
            return $this->run_on;
        }

        return $this->run_on = $this->timestamp()
            ->timezone($this->get_timezone())
            ->format($this->get_date_time_format());
    }

    /**
     * Get run in
     *
     * @return string
     */
    public function get_run_in(): string
    {
        if ($this->run_in !== '') {
            return $this->run_in;
        }
        $now = time();
        $duration = DateTime::duration($now, $this->get_timestamp());
        $sign = '';
        $color = 'green';
        if ($now > $this->get_timestamp()) {
            $sign = '-';
            $color = 'red';
        }

        return $this->run_in = '<span class="' . $color . '">' . $sign . $duration . '</span>';
    }

    /**
     * Get run every
     *
     * @return string
     */
    public function get_run_every(): string
    {
        if ($this->run_every !== '') {
            return $this->run_every;
        }

        return $this->run_every = DateTime::duration(0, $this->get_interval());
    }

    /**
     * Get actions
     *
     * @return array
     */
    public function get_actions(): array
    {
        global $wp_filter;

        if (count($this->actions) !== 0) {
            return $this->actions;
        }
        if (!isset($wp_filter[$this->hook])) {
            return $this->actions;
        }
        foreach ($wp_filter[$this->hook] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                if (!isset($callback['function'])) {
                    continue;
                }
                $this->actions[] = $callback['function'];
            }
        }

        return $this->actions;
    }

    /**
     * Get actions for display
     *
     * @return array
     */
    public function get_actions_for_display(): array
    {
        return array_map(function($action) {
            if (is_string($action)) {
                return $action . '()';
            }
            if (is_array($action)) {
                return get_class($action[0]) . '->' . $action[1] . '()';
            }

            return '';
        }, $this->get_actions());
    }
}