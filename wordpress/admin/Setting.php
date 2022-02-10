<?php

namespace Charm\WordPress\Admin;

/**
 * Class Setting
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Admin
 */
class Setting
{
    /************************************************************************************/
    // Properties

    /**
     * Option group (Required)
     *
     * A settings group name. Should correspond to an allowed option key name. Default
     * allowed option key names include 'general', 'discussion', 'media', 'reading',
     * 'writing', and 'options'.
     *
     * @var string
     */
    protected string $option_group = '';

    /**
     * Option name (Required)
     *
     * The name of an option to sanitize and save.
     *
     * @var string
     */
    protected string $option_name = '';

    /**
     * Type
     *
     * The type of data associated with this setting. Valid values are 'string',
     * 'boolean', 'integer', 'number', 'array', and 'object'.
     *
     * @var string
     */
    protected string $type = '';

    /**
     * Description
     *
     * A description of the data attached to this setting.
     *
     * @var string
     */
    protected string $description = '';

    /**
     * Sanitize callback
     *
     * A callback function that sanitizes the option's value.
     *
     * @var callable
     */
    protected $sanitize_callback = null;

    /**
     * Show in rest
     *
     * Whether data associated with this setting should be included in the REST API.
     * When registering complex settings, this argument may optionally be an array with
     * a 'schema' key.
     *
     * @var bool|array
     */
    protected bool|array $show_in_rest = false;

    /**
     * Default
     *
     * Default value when calling get_option().
     *
     * @var mixed|null
     */
    protected mixed $default = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Setting constructor
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
        if (isset($data['option_group'])) {
            $this->option_group = $data['option_group'];
        }
        if (isset($data['option_name'])) {
            $this->option_name = $data['option_name'];
        }
        if (isset($data['type'])) {
            $this->type = $data['type'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
        if (isset($data['sanitize_callback'])) {
            $this->sanitize_callback = $data['sanitize_callback'];
        }
        if (isset($data['show_in_rest'])) {
            $this->show_in_rest = $data['show_in_rest'];
        }
        if (isset($data['default'])) {
            $this->default = $data['default'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize setting
     *
     * @param string $option_name
     * @return static|null
     */
    public static function init(string $option_name): ?Setting
    {
        $settings = static::get();
        if (!isset($settings[$option_name])) {
            return null;
        }

        return $settings[$option_name];
    }

    /**
     * Get registered settings
     *
     * @see get_registered_settings()
     * @return array
     */
    public static function get(): array
    {
        $settings = [];
        $wp_settings = get_registered_settings();
        foreach ($wp_settings as $option_name => $wp_setting) {
            $setting = new static();
            $wp_setting['option_name'] = $option_name;
            $wp_setting['option_group'] = $wp_setting['group'];
            $setting->load($wp_setting);
            $settings[$option_name] = $setting;
        }

        return $settings;
    }

    /************************************************************************************/
    // Action methods

    /**
     * Register setting
     *
     * @see register_setting()
     */
    public function register(): void
    {
        add_action('admin_init', function() {
            $args = $this->to_array();
            unset($args['option_group']);
            unset($args['option_name']);
            register_setting(
                $this->option_group,
                $this->option_name,
                $args
            );
        });
    }

    /**
     * Unregister setting
     *
     * @see unregister_setting()
     */
    public function unregister(): void
    {
        add_action('admin_init', function() {
            unregister_setting(
                $this->option_group,
                $this->option_name
            );
        });
    }

    /************************************************************************************/
    // Cast methods

    /**
     * Cast properties to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = [];
        if ($this->option_group !== '') {
            $data['option_group'] = $this->option_group;
        }
        if ($this->option_name !== '') {
            $data['option_name'] = $this->option_name;
        }
        if ($this->type !== '') {
            $data['type'] = $this->type;
        }
        if ($this->description !== '') {
            $data['description'] = $this->description;
        }
        if ($this->sanitize_callback !== null) {
            $data['sanitize_callback'] = $this->sanitize_callback;
        }
        $data['show_in_rest'] = $this->show_in_rest;
        if ($this->default !== null) {
            $data['default'] = $this->default;
        }

        return $data;
    }

    /**
     * Cast instance to JSON
     *
     * @return string
     */
    public function to_json(): string
    {
        return json_encode($this->to_array());
    }
    /**
     * Cast instance to object
     *
     * @return object
     */
    public function to_object(): object
    {
        return (object) $this->to_array();
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get option group
     *
     * @return string
     */
    public function get_option_group(): string
    {
        return $this->option_group;
    }

    /**
     * Set option group
     *
     * @param string $option_group
     */
    public function set_option_group(string $option_group): void
    {
        $this->option_group = $option_group;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get option name
     *
     * @return string
     */
    public function get_option_name(): string
    {
        return $this->option_name;
    }

    /**
     * Set option group
     *
     * @param string $option_name
     */
    public function set_option_name(string $option_name): void
    {
        $this->option_name = $option_name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get type
     *
     * @return string
     */
    public function get_type(): string
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function set_type(string $type): void
    {
        $this->type = $type;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get description
     *
     * @return string
     */
    public function get_description(): string
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function set_description(string $description): void
    {
        $this->description = $description;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get sanitize callback
     *
     * @return callable
     */
    public function get_sanitize_callback(): callable
    {
        return $this->sanitize_callback;
    }

    /**
     * Set sanitize callback
     *
     * @param callable $sanitize_callback
     */
    public function set_sanitize_callback(callable $sanitize_callback): void
    {
        $this->sanitize_callback = $sanitize_callback;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get show in rest
     *
     * @return array|bool
     */
    public function get_show_in_rest(): array|bool
    {
        return $this->show_in_rest;
    }

    /**
     * Set show in rest
     *
     * @param array|bool $show_in_rest
     */
    public function set_show_in_rest(array|bool $show_in_rest): void
    {
        $this->show_in_rest = $show_in_rest;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get default
     *
     * @return mixed
     */
    public function get_default(): mixed
    {
        return $this->default;
    }

    /**
     * Set default
     *
     * @param mixed $default
     */
    public function set_default(mixed $default): void
    {
        $this->default = $default;
    }
}