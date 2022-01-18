<?php

namespace Charm\WordPress;

use WP_Network;
use WP_Network_Query;

/**
 * Class Network (from wp_sites)
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class Network
{
    /************************************************************************************/
    // Properties

    /**
     * ID
     *
     * @var int
     */
    protected int $id = 0;

    /**
     * Site name
     *
     * @var string
     */
    protected string $site_name = '';

    /**
     * Domain
     *
     * @var string
     */
    protected string $domain = '';

    /**
     * Path
     *
     * @var string
     */
    protected string $path = '';

    /**
     * Cookie domain
     *
     * @var string
     */
    protected string $cookie_domain = '';

    /*----------------------------------------------------------------------------------*/

    /**
     * WordPress site
     *
     * @var WP_Network|null
     */
    private ?WP_Network $wp_network = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Network constructor
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        if (count($data) > 0) {
            $this->load($data);
        }
    }

    /**
     * Load instance with data
     *
     * @param array $data
     */
    public function load(array $data): void
    {
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }
        if (isset($data['site_name'])) {
            $this->site_name = $data['site_name'];
        }
        if (isset($data['domain'])) {
            $this->domain = $data['domain'];
        }
        if (isset($data['path'])) {
            $this->path = $data['path'];
        }
        if (isset($data['cookie_domain'])) {
            $this->cookie_domain = $data['cookie_domain'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize network
     *
     * @see WP_Network
     * @param int|string|WP_Network|null $key
     * @return static|null
     */
    public static function init(int|string|WP_Network $key = null): ?Network
    {
        $network = new static();
        if (is_int($key) || ctype_digit($key)) {
            $network->load_from_id($key);
        } elseif (is_string($key)) {
            $network->load_from_domain($key);
        } elseif (is_object($key) && get_class($key) === 'WP_Network') {
            $network->load_from_network($key);
        } else {
            $network->load_from_current_network();
        }
        if ($network->get_id() === 0) {
            return null;
        }

        return $network;
    }

    /**
     * Get networks
     *
     * @see WP_Network_Query
     * @param array $params
     * @return static[]
     */
    public static function get(array $params = []): array
    {
        $query = new WP_Network_Query($params);

        return array_map(function(WP_Network $site) {
            return static::init($site);
        }, $query->get_networks());
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load instance from ID
     *
     * @see get_network()
     * @param int $id
     */
    protected function load_from_id(int $id): void
    {
        if (!$network = get_network($id)) {
            return;
        }
        $this->load_from_network($network);
    }

    /**
     * Load instance from domain
     *
     * @param string $domain
     */
    protected function load_from_domain(string $domain): void
    {
        $networks = static::get(['domain' => $domain]);
        if (!isset($networks[0])) {
            return;
        }
        $this->load_from_network($networks[0]->wp_network());
    }

    /**
     * Load instance from current WP_Network object
     *
     * @see get_network()
     */
    protected function load_from_current_network(): void
    {
        if (!$network = get_network()) {
            return;
        }
        $this->load_from_network($network);
    }

    /**
     * Load instance from WP_Network object
     *
     * @see WP_Network
     * @param WP_Network $network
     */
    protected function load_from_network(WP_Network $network): void
    {
        $this->id = (int) $network->id;
        $this->site_name = $network->site_name;
        $this->domain = $network->domain;
        $this->path = $network->path;
        $this->cookie_domain = $network->cookie_domain;
        $this->wp_network = $network;
    }

    /**
     * Reload instance from database
     */
    protected function reload(): void
    {
        if (!$this->id) {
            return;
        }
        $this->load_from_id($this->id);
    }

    /************************************************************************************/
    // Action methods

    // There are no WordPress core functions to create, update, or delete networks.

    /************************************************************************************/
    // Cast methods

    /**
     * Cast instance to array
     *
     * @return array
     */
    public function to_array(): array
    {
        $data = [];
        if ($this->id !== 0) {
            $data['id'] = $this->id;
        }
        if ($this->site_name !== '') {
            $data['site_name'] = $this->site_name;
        }
        if ($this->domain !== '') {
            $data['domain'] = $this->domain;
        }
        if ($this->path !== '') {
            $data['path'] = $this->path;
        }
        if ($this->cookie_domain !== '') {
            $data['cookie_domain'] = $this->cookie_domain;
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
    // Object access methods

    /**
     * Get (or set) WordPress network
     *
     * @param WP_Network|null $network
     * @return WP_Network
     */
    protected function wp_network(WP_Network $network = null): WP_Network
    {
        if ($network !== null) {
            $this->wp_network = $network;
        }

        return $this->wp_network;
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get ID
     *
     * @return int
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * Set ID
     *
     * @param int $id
     */
    public function set_id(int $id): void
    {
        $this->id = $id;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get site name
     *
     * @return string
     */
    public function get_site_name(): string
    {
        return $this->site_name;
    }

    /**
     * Set site name
     *
     * @param string $site_name
     */
    public function set_site_name(string $site_name): void
    {
        $this->site_name = $site_name;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get domain
     *
     * @return string
     */
    public function get_domain(): string
    {
        return $this->domain;
    }

    /**
     * Set domain
     *
     * @param string $domain
     */
    public function set_domain(string $domain): void
    {
        $this->domain = $domain;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get path
     *
     * @return string
     */
    public function get_path(): string
    {
        return $this->path;
    }

    /**
     * Set path
     *
     * @param string $path
     */
    public function set_path(string $path): void
    {
        $this->path = $path;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get cookie domain
     *
     * @return string
     */
    public function get_cookie_domain(): string
    {
        return $this->cookie_domain;
    }

    /**
     * Set cookie domain
     *
     * @param string $cookie_domain
     */
    public function set_cookie_domain(string $cookie_domain): void
    {
        $this->cookie_domain = $cookie_domain;
    }
}