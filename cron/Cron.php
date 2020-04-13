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
     * Name
     *
     * @var string
     */
    protected $name = '';

    /**
     * Callback
     *
     * @var callable
     */
    protected $callback = null;

    /**
     * Repeat (date modifier)
     *
     * @var string
     */
    protected $repeat = '';

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
        if (isset($data['callback'])) {
            $this->callback = $data['callback'];
        }
        if (isset($data['repeat'])) {
            $this->repeat = $data['interval'];
        }
        if (!isset($data['schedule'])) {
            $data['schedule'] = '';
        }
        $this->load_schedule($data['schedule']);
        if (!isset($data['event'])) {
            $data['event'] = $this->name;
        }
        $this->load_event($data['event']);
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Load instance with schedule
     *
     * @param array|string $data
     */
    protected function load_schedule($data): void
    {
        if (is_string($data) && $data !== '') {
            $this->schedule = call_user_func(
                static::SCHEDULE . '::init', $data
            );
            return;
        }
        if (!is_array($data)) {
            return;
        }
        if (isset($data['name']) && !isset($data['display_name'])) {
            $data['display_name'] = ucwords(str_replace('_', ' ', $data['name']));
        }
        if (is_array($data)) {
            $schedule = static::SCHEDULE;
            $this->schedule = new $schedule($data);
        }
    }

    /**
     * Load instance with event
     *
     * @param array|string $data
     */
    protected function load_event($data): void
    {
        if (is_string($data)) {
            $data = ['hook' => $data];
        }
        if (!isset($data['hook'])) {
            $data['hook'] = $this->name;
        }
        if (!isset($data['timestamp'])) {
            $data['timestamp'] = time();
        }
        if ($this->schedule !== null) {
            $data['schedule'] = $this->schedule->get_name();
            $data['interval'] = $this->schedule->get_interval();
        }
        if (!isset($data['args'])) {
            $data['args'] = [];
        }
        if (!isset($data['key'])) {
            $data['key'] = md5(serialize($data['args']));
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
        $output .= call_user_func(static::EVENT . '::to_html');
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
     */
    public function save()
    {
        if ($this->name === '') {
            return;
        }
        add_action($this->name, $this->callback);
        if ($this->schedule()) {
            $this->schedule->save();
        }
        $this->event->schedule();
    }

    /**
     * Delete cron (with schedule and event)
     */
    public function delete()
    {

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
     * Get callback
     *
     * @return callable
     */
    public function get_callback(): callable
    {
        return $this->callback;
    }

    /**
     * Set callback
     *
     * @param callable $callback
     */
    public function set_callback(callable $callback): void
    {
        $this->callback = $callback;
    }
}