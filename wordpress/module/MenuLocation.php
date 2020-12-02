<?php

namespace Charm\WordPress\Module;

/**
 * Class MenuLocation
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Module
 */
class MenuLocation
{
    /************************************************************************************/
    // Properties

    /**
     * Location
     *
     * Menu location identifier, like a slug.
     *
     * @var string
     */
    protected $location = '';

    /**
     * Description
     *
     * Menu location descriptive text.
     *
     * @var string
     */
    protected $description = '';

    /************************************************************************************/
    // Default constructor and load method

    /**
     * MenuLocation constructor
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
        if (isset($data['location'])) {
            $this->location = $data['location'];
        }
        if (isset($data['description'])) {
            $this->description = $data['description'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Get menu locations
     *
     * @see get_nav_menu_locations()
     * @return static[]
     */
    public static function get(): array
    {
        $menu_locations = [];
        foreach (get_nav_menu_locations() as $location => $term_id) {
            $menu_locations[] = new static(['location' => $location]);
        }

        return $menu_locations;
    }

    /************************************************************************************/
    // Action methods

    /**
     * Register menu
     *
     * @see register_nav_menu()
     */
    public function register(): void
    {
        if ($this->location === '') {
            return;
        }
        register_nav_menu($this->location, $this->description);
    }

    /**
     * Unregister menu
     *
     * @see unregister_nav_menu()
     */
    public function unregister(): void
    {
        if ($this->location === '') {
            return;
        }
        unregister_nav_menu($this->location);
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
        if ($this->location !== '') {
            $data['location'] = $this->location;
        }
        if ($this->description !== '') {
            $data['description'] = $this->description;
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
     * Get location
     *
     * @return string
     */
    public function get_location(): string
    {
        return $this->location;
    }

    /**
     * Set location
     *
     * @param string $location
     */
    public function set_location(string $location): void
    {
        $this->location = $location;
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
}