<?php

namespace Charm\WordPress\Admin;

/**
 * Class SettingsSection
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Admin
 */
class SettingsSection
{
    /************************************************************************************/
    // Properties

    /**
     * ID
     *
     * Slug-name to identify the section. Used in the 'id' attribute of tags.
     *
     * @var string
     */
    protected string $id = '';

    /**
     * Title
     *
     * Formatted title of the section. Shown as the heading for the section.
     *
     * @var string
     */
    protected string $title = '';

    /**
     * Callback
     *
     * Function that echos out any content at the top of the section (between heading
     * and fields).
     *
     * @var callable
     */
    protected $callback = null;


    /**
     * Page
     *
     * The slug-name of the settings page on which to show the section. Built-in pages
     * include 'general', 'reading', 'writing', 'discussion', 'media', etc. Create your
     * own using add_options_page();
     *
     * @var string
     */
    protected string $page = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * SettingsSection constructor
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
}