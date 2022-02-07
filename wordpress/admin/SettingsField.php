<?php

namespace Charm\WordPress\Admin;

/**
 * Class SettingsField
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Admin
 */
class SettingsField
{
    /************************************************************************************/
    // Properties

    /**
     * ID (Required)
     *
     * Slug-name to identify the field. Used in the 'id' attribute of tags.
     *
     * @var string
     */
    protected string $id = '';

    /**
     * Title (Required)
     *
     * Formatted title of the field. Shown as the label for the field during output.
     *
     * @var string
     */
    protected string $title = '';

    /**
     * Callback (Required)
     *
     * Function that fills the field with the desired form inputs. The function should echo
     * its output.
     *
     * @var callable
     */
    protected $callback = null;

    /**
     * Page (Required)
     *
     * The slug-name of the settings page on which to show the section (general, reading,
     * writing, ...).
     *
     * @var string
     */
    protected string $page = '';

    /**
     * Section
     *
     * The slug-name of the section of the settings page in which to show the box.
     * Default value: 'default'
     *
     * @var string
     */
    protected string $section = 'default';

    /**
     * Label for
     *
     * When supplied, the setting title will be wrapped in a <label> element, its for
     * attribute populated with this value.
     *
     * @var string
     */
    protected string $label_for = '';

    /**
     * Class
     *
     * CSS Class to be added to the <tr> element when the field is output.
     *
     * @var string
     */
    protected string $class = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * SettingsField constructor
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
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['title'])) {
            $this->title = $data['title'];
        }
        if (isset($data['callback'])) {
            $this->callback = $data['callback'];
        }
        if (isset($data['page'])) {
            $this->page = $data['page'];
        }
        if (isset($data['section'])) {
            $this->section = $data['section'];
        }
        if (isset($data['label_for'])) {
            $this->label_for = $data['label_for'];
        }
        if (isset($data['class'])) {
            $this->class = $data['class'];
        }
    }

    /************************************************************************************/
    // Action methods

    /**
     * Register settings section
     */
    public function register()
    {
        add_action('admin_init', function() {
            add_settings_field(
                $this->id,
                $this->title,
                $this->callback,
                $this->page,
                $this->section,
                $this->to_array()
            );
        });
    }

    /**
     * Display settings field
     *
     * @see do_settings_fields()
     */
    public function display(): void
    {
        do_settings_fields($this->page, $this->section);
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
        if ($this->id !== '') {
            $data['id'] = $this->id;
        }
        if ($this->title !== '') {
            $data['title'] = $this->title;
        }
        if ($this->callback !== null) {
            $data['callback'] = $this->callback;
        }
        if ($this->page !== '') {
            $data['page'] = $this->page;
        }
        if ($this->label_for !== '') {
            $data['label_for'] = $this->label_for;
        }
        if ($this->class !== '') {
            $data['class'] = $this->class;
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
     * Get ID
     *
     * @return string
     */
    public function get_id(): string
    {
        return $this->id;
    }

    /**
     * Set ID
     *
     * @param string $id
     */
    public function set_name(string $id): void
    {
        $this->id = $id;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get title
     *
     * @return string
     */
    public function get_title(): string
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function set_title(string $title): void
    {
        $this->title = $title;
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

    /*----------------------------------------------------------------------------------*/

    /**
     * Get page
     *
     * @return string
     */
    public function get_page(): string
    {
        return $this->page;
    }

    /**
     * Set page
     *
     * @param string $page
     */
    public function set_page(string $page): void
    {
        $this->page = $page;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get section
     *
     * @return string
     */
    public function get_section(): string
    {
        return $this->section;
    }

    /**
     * Set section
     *
     * @param string $section
     */
    public function set_section(string $section): void
    {
        $this->section = $section;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get label for
     *
     * @return string
     */
    public function get_label_for(): string
    {
        return $this->label_for;
    }

    /**
     * Set label for
     *
     * @param string $label_for
     */
    public function set_label_for(string $label_for): void
    {
        $this->label_for = $label_for;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get class
     *
     * @return string
     */
    public function get_class(): string
    {
        return $this->class;
    }

    /**
     * Set class
     *
     * @param string $class
     */
    public function set_class(string $class): void
    {
        $this->class = $class;
    }
}