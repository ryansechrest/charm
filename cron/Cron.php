<?php

namespace Charm\Cron;

/**
 * Class Cron
 *
 * @author Ryan Sechrest
 * @package Charm\Cron
 */
class Cron
{
    /**
     * Event class
     *
     * @var string
     */
    const EVENT = 'Charm\Cron\Event';

    /**
     * Schedule class
     *
     * @var string
     */
    const SCHEDULE = 'Charm\Cron\Schedule';

    /************************************************************************************/
    // Properties

    /**
     * Name of event
     *
     * @var string
     */
    protected $name = '';

    /**
     * Function or method to call
     *
     * @var callable
     */
    protected $action = null;

    /**
     * Pass args to function or method
     *
     * @var array
     */
    protected $args = [];

    /**
     * Repeat run in specified interval
     *
     * @var array
     */
    protected $repeat = [];

    /*----------------------------------------------------------------------------------*/

    /**
     * Cron event
     *
     * @var Event
     */
    private $event = null;

    /**
     * Cron schedule
     *
     * @var Schedule
     */
    private $schedule = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Cron constructor
     *
     * @param array $data
     */
    public function __construct(array $data)
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
        if (isset($data['action'])) {
            $this->action = $data['action'];
        }
        if (isset($data['args'])) {
            $this->args = $data['args'];
        }
        if (isset($data['repeat'])) {
            $this->repeat = $data['repeat'];
        }
        if (count($this->repeat) > 0) {
            $this->load_schedule();
        }
        if ($this->name !== '') {
            $this->load_event();
        }
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Load instance with schedule
     */
    protected function load_schedule(): void
    {
        $data = $this->prepare_schedule();
        $schedule = call_user_func(
            static::SCHEDULE . '::init', $data['name']
        );
        if ($schedule !== null) {
            $this->schedule = $schedule;
            return;
        }
        $schedule = static::SCHEDULE;
        $this->schedule = new $schedule($data);
    }

    /**
     * Prepare schedule
     *
     * @return array
     */
    protected function prepare_schedule(): array
    {
        $schedule = [
            'name' => 'charm_repeat_',
            'display_name' => 'Every ',
            'interval' => 0,
        ];
        $components = [];
        $repeat = [
            'd' => 86400,
            'h' => 3600,
            'm' => 60,
            's' => 1
        ];
        foreach ($repeat as $key => $value) {
            if (!isset($this->repeat[$key])) {
                unset($repeat[$key]);
                continue;
            }
            if ($this->repeat[$key] <= 0) {
                unset($repeat[$key]);
                continue;
            }
            $components[] = $this->repeat[$key] . $key;
            $schedule['interval'] += $this->repeat[$key] * $value;
        }
        $schedule['name'] .= implode('_', $components);
        $schedule['display_name'] .= implode(' ', $components);

        return $schedule;
    }

    /**
     * Load instance with event
     */
    protected function load_event(): void
    {
        $data = [
            'hook' => $this->name,
            'timestamp' => time(),
            'args' => $this->args,
            'key' => md5(serialize($this->args))
        ];
        if ($this->schedule !== null) {
            $data['schedule'] = $this->schedule->get_name();
            $data['interval'] = $this->schedule->get_interval();
        }
        $event = static::EVENT;
        $this->event = new $event($data);
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize cron
     *
     * @param string $name
     * @return static|null
     */
    public static function init(string $name)
    {
        $data = [
            'name' => $name,
            'event' => Event::init($name),
        ];
        if ($data['event'] !== null && $data['event']->get_schedule()) {
            $data['schedule'] = call_user_func(
                static::SCHEDULE . '::init', $data['event']->get_schedule()
            );
        }

        return new static($data);
    }

    /**
     * Get all schedules and events as HTML table
     *
     * @return string
     */
    public static function to_html(): string
    {
        $output = <<<STYLE
<style type="text/css">
   .charm {
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
   }
   .charm .green {
        color: #28a745;
   }
   .charm .red {
        color: #dc3545;
   }
   .charm pre {
        margin: 0;
   }
   .charm table {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-collapse: collapse;
        margin-bottom: 1rem;
        width: 100%;
        max-width: 100%;
   }
   .charm table caption {
        color: #6c757d;
        text-align: left;
        padding: .75rem 0;
        caption-side: bottom;
   }
   .charm table thead th {
        border-bottom: 2px solid #dee2e6;
        text-align: left;
        padding: .75rem;
   }
   .charm table tbody tr:hover {
        background-color: rgba(0,0,0,.075);
   }
   .charm table th, .charm table td {
        border: 1px solid #dee2e6;
        padding: .75rem;
   }
</style>
STYLE;
        $output .= '<div class="charm">';
        $output .= '<p>' . _x('You can change your date/time format and timezone in the <a href="' . admin_url('options-general.php') . '">general settings</a>.', 'Tools: Cron Viewer', 'charm') . '</p>';
        $output .= '<h2>' . _x('Events', 'Tools: Cron Viewer', 'charm') . '</h2>';
        $output .= call_user_func(static::EVENT . '::to_html');
        $output .= '<h2>' . _x('Schedules', 'Tools: Cron Viewer', 'charm') . '</h2>';
        $output .= call_user_func(static::SCHEDULE . '::to_html');
        $output .= '</div>';

        return $output;
    }

    /************************************************************************************/
    // Action methods

    /**
     * Save cron (with schedule and event)
     *
     * @see add_action()
     * @return bool
     */
    public function save(): bool
    {
        add_action($this->name, $this->action);
        if ($this->schedule()) {
            $this->schedule->add();
        }

        return $this->event->schedule();
    }

    /**
     * Delete cron (with schedule and event)
     *
     * @see wp_clear_scheduled_hook()
     * * @return bool
     */
    public function delete(): bool
    {
        remove_action($this->name, $this->action);

        return call_user_func(
            static::EVENT, '::clear', $this->name, $this->args
        );
    }

    /************************************************************************************/
    // Object access methods

    /**
     * Get (or set) Charm event
     *
     * @param Event $event
     * @return Event
     */
    protected function event(Event $event = null)
    {
        if ($event !== null) {
            $this->event = $event;
        }

        return $this->event;
    }

    /**
     * Get (or set) Charm schedule
     *
     * @param Schedule $schedule
     * @return Schedule
     */
    protected function schedule(Schedule $schedule = null)
    {
        if ($schedule !== null) {
            $this->schedule = $schedule;
        }

        return $this->schedule;
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
     * Get action
     *
     * @return callable
     */
    public function get_action(): callable
    {
        return $this->action;
    }

    /**
     * Set action
     *
     * @param callable $action
     */
    public function set_action(callable $action): void
    {
        $this->action = $action;
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
     * Get repeat
     *
     * @return array
     */
    public function get_repeat(): array
    {
        return $this->repeat;
    }

    /**
     * Set repeat
     *
     * @param array $repeat
     */
    public function set_repeat(array $repeat): void
    {
        $this->repeat = $repeat;
    }
}