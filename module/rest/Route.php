<?php

namespace Charm\Module\Rest;

use Charm\WordPress\Module\RestRoute as WpRestRoute;

/**
 * Class RestRoute
 *
 * @author Ryan Sechrest
 * @package Charm\Module
 */
class Route extends WpRestRoute
{
    /************************************************************************************/
    // Properties

    /**
     * Endpoints
     *
     * @var Endpoint[]
     */
    protected $endpoints = [];

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        parent::load($data);
        if (isset($data['endpoints'])) {
            $this->endpoints = $data['endpoints'];
        }
    }

    /************************************************************************************/
    // Action methods

    /**
     * Register REST route
     */
    public function register(): void
    {
        $this->args = array_map(function(Endpoint $endpoint) {
            $ep = $endpoint->to_array();
            $ep['args'] = $ep['params'];
            unset($ep['params']);
            return $ep;
        }, $this->endpoints);
        parent::register();
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
        $data = parent::to_array();
        if (count($this->endpoints) > 0) {
            $data['endpoints'] = array_map(function(Endpoint $endpoint) {
                return $endpoint->to_array();
            }, $this->endpoints);
        }

        return $data;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get endpoints
     *
     * @return array
     */
    public function get_endpoints(): array
    {
        return $this->endpoints;
    }

    /**
     * Add endpoint
     *
     * @param Endpoint $endpoint
     */
    public function add_endpoint(Endpoint $endpoint): void
    {
        $this->endpoints[] = $endpoint;
    }

    /**
     * Set endpoints
     *
     * @param Endpoint[] $endpoints
     */
    public function set_methods(array $endpoints): void
    {
        $this->endpoints = $endpoints;
    }
}