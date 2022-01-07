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
    protected string $hook = '';

    /**
     * Timestamp
     *
     * @var int
     */
    protected int $timestamp = 0;

    /**
     * Schedule
     *
     * @var string
     */
    protected string $schedule = '';

    /**
     * Interval
     *
     * @var int
     */
    protected int $interval = 0;

    /**
     * Arguments
     *
     * @var array
     */
    protected array $args = [];

    /**
     * Key
     *
     * @var string
     */
    protected string $key = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Event constructor
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
     * @param object|string $key
     * @param array $args
     * @param int $timestamp
     * @return static|null
     */
    public static function init(object|string $key, array $args = [], int $timestamp = 0): ?Event
    {
        $event = new static();
        if (is_string($key)) {
            $event->load_from_params($key, $args, $timestamp);
        } elseif (is_object($key)) {
            $event->load_from_event($key);
        }
        if ($event->get_hook() === '') {
            return null;
        }

        return $event;
    }

    /**
     * Clear all cron events (and optionally matching args)
     *
     * @see wp_clear_scheduled_hook()
     * @see wp_unschedule_hook()
     * @param string $hook
     * @param array $args
     * @return bool
     */
    public static function clear(string $hook, array $args = []): bool
    {
        if (count($args) > 0) {
            $result = wp_clear_scheduled_hook($hook, $args);
        } else {
            $result = wp_unschedule_hook($hook);
        }
        if ($result === false) {
            return false;
        }

        return true;
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
                    $data = [
                        'hook' => $hook,
                        'timestamp' => $timestamp,
                        'schedule' => $wp_event['schedule'],
                        'args' => $wp_event['args'],
                        'key' => $key,
                    ];
                    if (isset($wp_event['interval'])) {
                        $data['interval'] = $wp_event['interval'];
                    }
                    $events[] = new static($data);
                }
            }
        }

        return $events;
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load from params
     *
     * @see wp_get_scheduled_event()
     * @param string $hook
     * @param array $args
     * @param int $timestamp
     */
    protected function load_from_params(string $hook, array $args = [], int $timestamp = 0): void
    {
        if (!$wp_event = wp_get_scheduled_event($hook, $args, $timestamp)) {
            return;
        }
        $this->load_from_event($wp_event);
    }

    /**
     * Load instance from event object
     *
     * @param object $event
     */
    protected function load_from_event(object $event): void
    {
        $this->hook = $event->hook;
        $this->timestamp = $event->timestamp;
        $this->schedule = $event->schedule;
        $this->args = $event->args;
        $this->key = md5(serialize($event->args));
        if (property_exists($event, 'interval')) {
            $this->interval = $event->interval;
        }
    }

    /**
     * Reload instance from database
     */
    protected function reload(): void
    {
        if (!$this->hook) {
            return;
        }
        $this->load_from_params($this->hook, $this->args, $this->timestamp);
    }

    /************************************************************************************/
    // Action methods

    /**
     * Schedule cron event (if not already scheduled)
     *
     * @see wp_next_scheduled()
     * @see wp_schedule_event()
     * @see wp_schedule_single_event()
     * @return bool
     */
    public function schedule(): bool
    {
        if (wp_next_scheduled($this->hook, $this->args) !== false) {
            return false;
        }

        return $this->force_schedule();
    }
    /**
     * Force schedule cron event
     *
     * @see wp_schedule_event()
     * @see wp_schedule_single_event()
     * @return bool
     */
    public function force_schedule(): bool
    {
        if ($this->schedule === '') {
            $result = wp_schedule_single_event(
                $this->timestamp, $this->hook, $this->args
            );
        } else {
            $result = wp_schedule_event(
                $this->timestamp, $this->schedule, $this->hook, $this->args
            );
        }
        $this->reload();

        return $result;
    }

    /**
     * Reschedule cron event
     *
     * @see wp_reschedule_event()
     * @return bool
     */
    public function reschedule(): bool
    {
        $result = wp_reschedule_event(
            $this->timestamp, $this->schedule, $this->hook, $this->args
        );
        $this->reload();

        return $result;
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