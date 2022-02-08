<?php

namespace Charm\WordPress\Admin;

/**
 * Class SettingsError
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Admin
 */
class SettingsError
{
    /************************************************************************************/
    // Properties

    /**
     * Setting (Required)
     *
     * Slug title of the setting to which this error applies.
     *
     * @var string
     */
    protected string $setting = '';

    /**
     * Code (Required)
     *
     * Slug to identify the error. Used as part of 'id' attribute in HTML output.
     *
     * @var string
     */
    protected string $code = '';

    /**
     * Message (Required)
     *
     * The formatted message text to display to the user (will be shown inside styled
     * <div> and <p> tags).
     *
     * @var string
     */
    protected string $message = '';

    /**
     * Type
     *
     * Message type, controls HTML class. Possible values include 'error', 'success',
     * 'warning', 'info'. Default value: 'error'
     *
     * @var string
     */
    protected string $type = 'error';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * SettingsError constructor
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
        if (isset($data['setting'])) {
            $this->setting = $data['setting'];
        }
        if (isset($data['code'])) {
            $this->code = $data['code'];
        }
        if (isset($data['message'])) {
            $this->message = $data['message'];
        }
        if (isset($data['type'])) {
            $this->type = $data['type'];
        }
    }

    /************************************************************************************/
    // Action methods

    /**
     * Register settings error
     */
    public function register(): void
    {
        add_settings_error(
            $this->setting,
            $this->code,
            $this->message,
            $this->type
        );
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
        if ($this->setting !== '') {
            $data['setting'] = $this->setting;
        }
        if ($this->code !== '') {
            $data['code'] = $this->code;
        }
        if ($this->message !== '') {
            $data['message'] = $this->message;
        }
        if ($this->type !== '') {
            $data['type'] = $this->type;
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
     * Get setting
     *
     * @return string
     */
    public function get_setting(): string
    {
        return $this->setting;
    }

    /**
     * Set setting
     *
     * @param string $setting
     */
    public function set_setting(string $setting): void
    {
        $this->setting = $setting;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get code
     *
     * @return string
     */
    public function get_code(): string
    {
        return $this->code;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function set_code(string $code): void
    {
        $this->code = $code;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get message
     *
     * @return string
     */
    public function get_message(): string
    {
        return $this->message;
    }

    /**
     * Set message
     *
     * @param string $message
     */
    public function set_message(string $message): void
    {
        $this->message = $message;
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
}