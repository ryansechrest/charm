<?php

namespace Charm\WordPress\Cron;

/**
 * Class Schedule
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Cron
 */
class Schedule
{
    /************************************************************************************/
    // Properties

    /**
     * Name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Display name
     *
     * @var string
     */
    protected $display_name = '';

    /**
     * Interval (seconds)
     *
     * @var int
     */
    protected $interval = 0;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Schedule constructor
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
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['display_name'])) {
            $this->display_name = $data['display_name'];
        }
        if (isset($data['interval'])) {
            $this->interval = $data['interval'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize cron schedule
     *
     * @see wp_get_schedules()
     * @param string $name
     * @return static|null
     */
    public static function init(string $name)
    {
        $wp_schedules = wp_get_schedules();
        if (!isset($wp_schedules[$name])) {
            return null;
        }

        return new static([
            'name' => $name,
            'display_name' => $wp_schedules[$name]['display'],
            'interval' => $wp_schedules[$name]['interval'],
        ]);
    }

    /**
     * Get cron schedules
     *
     * @see wp_get_schedules()
     * @return static[]
     */
    public static function get(): array
    {
        $schedules = [];
        $wp_schedules = wp_get_schedules();
        foreach ($wp_schedules as $name => $wp_schedule) {
            $schedule = new static([
                'name' => $name,
                'display_name' => $wp_schedule['display'],
                'interval' => $wp_schedule['interval'],
            ]);
            $schedules[] = $schedule;
        }
        usort($schedules, function(Schedule $a, Schedule $b) {
            return $a->get_interval() > $b->get_interval();
        });

        return $schedules;
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save cron schedule
     *
     * @see add_filter()
     * @return bool
     */
    public function save(): bool
    {
        add_filter('cron_schedules', function($schedules) {
            $schedules[$this->name] = [
                'display' => esc_html__($this->display_name, 'charm'),
                'interval' => $this->interval,
            ];

            return $schedules;
        });

        return true;
    }

    /**
     * Delete cron schedule
     *
     * @see add_filter()
     * @return bool
     */
    public function delete(): bool
    {
        add_filter('cron_schedules', function($schedules) {
            unset($schedules[$this->name]);

            return $schedules;
        });

        return true;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get name
     *
     * @return string
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function set_name(string $name): void
    {
        $this->name = $name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get display name
     *
     * @return string
     */
    public function get_display_name(): string
    {
        return $this->display_name;
    }

    /**
     * Set display name
     *
     * @param string $display_name
     */
    public function set_display_name(string $display_name): void
    {
        $this->display_name = $display_name;
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


}