<?php

namespace Charm\WordPress\Module;

/**
 * Class RestRoute
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress\Module
 */
class RestRoute
{
    /************************************************************************************/
    // Properties

    /**
     * Namespace
     *
     * The first URL segment after core prefix. Should be unique to your package/plugin.
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * Route
     *
     * The base URL for route you are adding.
     *
     * @var string
     */
    protected $route = '';

    /**
     * Args
     *
     * Either an array of options for the endpoint, or an array of arrays for
     * multiple methods.
     *
     * @var array
     */
    protected $args = [];

    /**
     * Override
     *
     * If the route already exists, should we override it? True overrides, false merges
     * (with newer overriding if duplicate keys exist).
     *
     * @var bool
     */
    protected $override = false;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * RestRoute constructor
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
        if (isset($data['namespace'])) {
            $this->namespace = $data['namespace'];
        }
        if (isset($data['route'])) {
            $this->route = $data['route'];
        }
        if (isset($data['args'])) {
            $this->args = $data['args'];
        }
        if (isset($data['override'])) {
            $this->override = $data['override'];
        }
    }

    /************************************************************************************/
    // Action methods

    /**
     * Register REST route
     *
     * @uses register_rest_route()
     */
    public function register(): void
    {
        add_action('rest_api_init', function() {
            register_rest_route(
                $this->namespace, $this->route, $this->args, $this->override
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
        if ($this->namespace !== '') {
            $data['namespace'] = $this->namespace;
        }
        if ($this->route !== '') {
            $data['route'] = $this->route;
        }
        if (count($this->args) > 0) {
            $data['args'] = $this->args;
        }
        if ($this->override !== null) {
            $data['override'] = $this->override;
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
     * Get namespace
     *
     * @return string
     */
    public function get_namespace(): string
    {
        return $this->namespace;
    }

    /**
     * Set namespace
     *
     * @param string $namespace
     */
    public function set_namespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get route
     *
     * @return string
     */
    public function get_route(): string
    {
        return $this->route;
    }

    /**
     * Set route
     *
     * @param string $route
     */
    public function set_route(string $route): void
    {
        $this->route = $route;
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
     * Is override?
     *
     * @return bool
     */
    public function is_override(): bool
    {
        return $this->override;
    }

    /**
     * Get override
     *
     * @param bool $override
     */
    public function set_override(bool $override): void
    {
        $this->override = $override;
    }
}