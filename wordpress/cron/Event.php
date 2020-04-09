<?php

namespace Charm\WordPress\Cron;

/**
 * Class Event
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Cron
 */
class Event
{
    /************************************************************************************/
    // Properties

    /**
     * Hook
     *
     * @var string
     */
    protected $hook = '';

    /**
     * Timestamp
     *
     * @var int
     */
    protected $timestamp = 0;

    /**
     * Schedule
     *
     * @var string
     */
    protected $schedule = '';

    /**
     * Interval
     *
     * @var int
     */
    protected $interval = 0;

    /**
     * Arguments
     *
     * @var array
     */
    protected $args = [];

    /**
     * Key
     *
     * @var string
     */
    protected $key = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * CronSchedule constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (count($data) === 0) {
            return;
        }
        $this->load($data);
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['hook'])) {
            $this->hook = $data['hook'];
        }
        if (isset($data['timestamp'])) {
            $this->timestamp = $data['timestamp'];
        }
        if (isset($data['schedule'])) {
            $this->schedule = $data['schedule'];
        }
        if (isset($data['interval'])) {
            $this->interval = $data['interval'];
        }
        if (isset($data['args'])) {
            $this->args = $data['args'];
        }
        if (isset($data['key'])) {
            $this->key = $data['key'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize cron event
     *
     * @see wp_get_scheduled_event()
     * @param string $hook
     * @param array $args
     * @param int $timestamp
     * @return static|null
     */
    public static function init(string $hook, array $args = [], $timestamp = null)
    {
        $wp_event = wp_get_scheduled_event($hook, $args, $timestamp);
        if ($wp_event === false) {
            return null;
        }
        $data = [
            'hook' => $wp_event->hook,
            'timestamp' => $wp_event->timestamp,
            'schedule' => $wp_event->schedule,
            'args' => $wp_event->args,
            'key' => md5(serialize($wp_event->args)),
        ];
        if (property_exists($wp_event, 'interval')) {
            $data['interval'] = $wp_event->interval;
        }

        return new static($data);
    }

    /**
     * Get cron events
     *
     * @see _get_cron_array()
     * @return static[]
     */
    public static function get(): array
    {
        $events = [];
        $timestamps = _get_cron_array();
        foreach ($timestamps as $timestamp => $wp_crons) {
            foreach ($wp_crons as $hook => $wp_events) {
                foreach ($wp_events as $key => $wp_event) {
                    $event = new static([
                        'hook' => $hook,
                        'timestamp' => $timestamp,
                        'schedule' => $wp_event['schedule'],
                        'interval' => $wp_event['interval'],
                        'args' => $wp_event['args'],
                        'key' => $key,
                    ]);
                    $events[] = $event;
                }
            }
        }

        return $events;
    }

    /************************************************************************************/
    // Action methods

    /**
     * Schedule cron event
     *
     * @see wp_schedule_event()
     * @see wp_schedule_single_event()
     * @return bool
     */
    public function schedule(): bool
    {
        if ($this->schedule === '') {
            return wp_schedule_single_event(
                $this->timestamp, $this->hook, $this->args
            );
        }

        return wp_schedule_event(
            $this->timestamp, $this->schedule, $this->hook, $this->args
        );
    }

    /**
     * Reschedule cron event
     *
     * @see wp_reschedule_event()
     * @return bool
     */
    public function reschedule(): bool
    {
        return wp_reschedule_event(
            $this->timestamp, $this->schedule, $this->hook, $this->args
        );
    }

    /**
     * Unschedule cron event
     *
     * @see wp_unschedule_event()
     * @return bool
     */
    public function unschedule(): bool
    {
        return wp_unschedule_event(
            $this->timestamp, $this->hook, $this->args
        );
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get hook
     *
     * @return string
     */
    public function get_hook(): string
    {
        return $this->hook;
    }

    /**
     * Set hook
     *
     * @param string $hook
     */
    public function set_hook(string $hook): void
    {
        $this->hook = $hook;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get timestamp
     *
     * @return int
     */
    public function get_timestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * Set timestamp
     *
     * @param int $timestamp
     */
    public function set_timestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get schedule
     *
     * @return string
     */
    public function get_schedule(): string
    {
        return $this->schedule;
    }

    /**
     * Set schedule
     *
     * @param string $schedule
     */
    public function set_schedule(string $schedule): void
    {
        $this->schedule = $schedule;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get interval
     *
     * @return int
     */
    public function get_interval(): int
    {
        return $this->interval;
    }

    /**
     * Set interval
     *
     * @param int $interval
     */
    public function set_interval(int $interval): void
    {
        $this->interval = $interval;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get args
     *
     * @return array
     */
    public function get_args(): array
    {
        return $this->args;
    }

    /**
     * Set args
     *
     * @param array $args
     */
    public function set_args(array $args): void
    {
        $this->args = $args;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get key
     *
     * @return string
     */
    public function get_key(): string
    {
        return $this->key;
    }

    /**
     * Set key
     * 
     * @param string $key
     */
    public function set_key(string $key): void
    {
        $this->key = $key;
    }
}