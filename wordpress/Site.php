<?php

namespace Charm\WordPress;

use WP_Site;
use WP_Site_Query;

/**
 * Class Site (from wp_blogs)
 *
 * @author Ryan Sechrest
 * @package Charm\WordPress
 */
class Site
{
    /************************************************************************************/
    // Properties

    /**
     * ID (org. blog_id)
     *
     * @var int
     */
    protected int $id = 0;

    /**
     * Network ID (org. site_id)
     *
     * @var int
     */
    protected int $network_id = 0;

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
     * Registered
     *
     * @var string
     */
    protected string $registered = '';

    /**
     * Last updated
     *
     * @var string
     */
    protected string $last_updated = '';

    /**
     * Is public?
     *
     * @var int|null
     */
    protected ?int $public = null;

    /**
     * Is archived?
     *
     * @var int|null
     */
    protected ?int $archived = null;

    /**
     * Is mature?
     *
     * @var int|null
     */
    protected ?int $mature = null;

    /**
     * Is spam?
     *
     * @var int|null
     */
    protected ?int $spam = null;

    /**
     * Is deleted?
     *
     * @var int|null
     */
    protected ?int $deleted = null;

    /**
     * Language ID
     *
     * @var int
     */
    protected int $lang_id = 0;

    /*----------------------------------------------------------------------------------*/

    /**
     * WordPress site
     *
     * @var WP_Site|null
     */
    private ?WP_Site $wp_site = null;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Site constructor
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
        if (isset($data['network_id'])) {
            $this->network_id = (int) $data['network_id'];
        }
        if (isset($data['domain'])) {
            $this->domain = $data['domain'];
        }
        if (isset($data['path'])) {
            $this->path = $data['path'];
        }
        if (isset($data['registered'])) {
            $this->registered = $data['registered'];
        }
        if (isset($data['last_updated'])) {
            $this->last_updated = $data['last_updated'];
        }
        if (isset($data['public'])) {
            $this->public = $data['public'];
        }
        if (isset($data['archived'])) {
            $this->archived = $data['archived'];
        }
        if (isset($data['mature'])) {
            $this->mature = $data['mature'];
        }
        if (isset($data['spam'])) {
            $this->spam = $data['spam'];
        }
        if (isset($data['deleted'])) {
            $this->deleted = $data['deleted'];
        }
        if (isset($data['lang_id'])) {
            $this->lang_id = (int) $data['lang_id'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize site
     *
     * @see WP_Site
     * @param int|string|WP_Site|null $key
     * @return static|null
     */
    public static function init(int|string|WP_Site $key = null): ?Site
    {
        $site = new static();
        if (is_int($key) || ctype_digit($key)) {
            $site->load_from_id($key);
        } elseif (is_string($key)) {
            $site->load_from_domain($key);
        } elseif (is_object($key) && get_class($key) === 'WP_Site') {
            $site->load_from_site($key);
        } else {
            $site->load_from_current_site();
        }
        if ($site->get_id() === 0) {
            return null;
        }

        return $site;
    }

    /**
     * Get sites
     *
     * @see WP_Site_Query
     * @param array $params
     * @return static[]
     */
    public static function get(array $params = []): array
    {
        $query = new WP_Site_Query($params);

        return array_map(function(WP_Site $site) {
            return static::init($site);
        }, $query->get_sites());
    }

    /**
     * Get sites by network ID
     *
     * @param int $network_id
     * @return array
     */
    public static function get_by_network_id(int $network_id): array
    {
        return static::get(['network_id' => $network_id]);
    }

    /************************************************************************************/
    // Protected load methods

    /**
     * Load instance from ID
     *
     * @see get_site()
     * @param int $id
     */
    protected function load_from_id(int $id): void
    {
        if (!$site = get_site($id)) {
            return;
        }
        $this->load_from_site($site);
    }

    /**
     * Load instance from domain
     *
     * @param string $domain
     */
    protected function load_from_domain(string $domain): void
    {
        $sites = static::get(['domain' => $domain]);
        if (!isset($sites[0])) {
            return;
        }
        $this->load_from_site($sites[0]->wp_site());
    }

    /**
     * Load instance from current WP_Site object
     *
     * @see get_site()
     */
    protected function load_from_current_site(): void
    {
        if (!$site = get_site()) {
            return;
        }
        $this->load_from_site($site);
    }

    /**
     * Load instance from WP_Site object
     *
     * @see WP_Site
     * @param WP_Site $site
     */
    protected function load_from_site(WP_Site $site): void
    {
        $this->id = (int) $site->blog_id;
        $this->network_id = (int) $site->site_id;
        $this->domain = $site->domain;
        $this->path = $site->path;
        $this->registered = $site->registered;
        $this->last_updated = $site->last_updated;
        $this->public = (int) $site->public;
        $this->archived = (int) $site->archived;
        $this->mature = (int) $site->mature;
        $this->spam = (int) $site->spam;
        $this->deleted = (int) $site->deleted;
        $this->lang_id = (int) $site->lang_id;
        $this->wp_site = $site;
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

    /**
     * Save site
     *
     * @return bool
     */
    public function save(): bool
    {
        if (!$this->id) {
            return $this->create();
        }

        return $this->update();
    }

    /**
     * Create new site
     *
     * @see wp_insert_site()
     * @return bool
     */
    public function create(): bool
    {
        if (!$id = wp_insert_site($this->to_array())) {
            return false;
        }
        $this->id = $id;
        $this->reload();

        return true;
    }

    /**
     * Update existing site
     *
     * @see wp_update_site()
     * @return bool
     */
    public function update(): bool
    {
        if (!$id = wp_update_site($this->id, $this->to_array())) {
            return false;
        }
        $this->reload();

        return true;
    }

    /**
     * Delete site permanently
     *
     * @see wp_delete_site()
     * @return bool
     */
    public function delete(): bool
    {
        if (get_class(wp_delete_site($this->id)) === 'WP_Site') {
            return true;
        }

        return false;
    }

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
        if ($this->network_id !== 0) {
            $data['network_id'] = $this->network_id;
        }
        if ($this->domain !== '') {
            $data['domain'] = $this->domain;
        }
        if ($this->path !== '') {
            $data['path'] = $this->path;
        }
        if ($this->registered !== '') {
            $data['registered'] = $this->registered;
        }
        if ($this->last_updated !== '') {
            $data['last_updated'] = $this->last_updated;
        }
        if ($this->public !== null) {
            $data['public'] = $this->public;
        }
        if ($this->archived !== null) {
            $data['archived'] = $this->archived;
        }
        if ($this->mature !== null) {
            $data['mature'] = $this->mature;
        }
        if ($this->spam !== null) {
            $data['spam'] = $this->spam;
        }
        if ($this->deleted !== null) {
            $data['deleted'] = $this->deleted;
        }
        if ($this->lang_id !== 0) {
            $data['lang_id'] = $this->lang_id;
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
     * Get (or set) WordPress site
     *
     * @param WP_Site|null $site
     * @return WP_Site
     */
    protected function wp_site(WP_Site $site = null): WP_Site
    {
        if ($site !== null) {
            $this->wp_site = $site;
        }

        return $this->wp_site;
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
     * Get network ID
     *
     * @return int
     */
    public function get_network_id(): int
    {
        return $this->id;
    }

    /**
     * Set network ID
     *
     * @param int $network_id
     */
    public function set_network_id(int $network_id): void
    {
        $this->network_id = $network_id;
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
     * Get registered
     *
     * @return string
     */
    public function get_registered(): string
    {
        return $this->registered;
    }

    /**
     * Set registered
     *
     * @param string $registered
     */
    public function set_registered(string $registered): void
    {
        $this->registered = $registered;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get last updated
     *
     * @return string
     */
    public function get_last_updated(): string
    {
        return $this->registered;
    }

    /**
     * Set last updated
     *
     * @param string $last_updated
     */
    public function set_last_updated(string $last_updated): void
    {
        $this->last_updated = $last_updated;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is public?
     *
     * @return int
     */
    public function is_public(): int
    {
        return $this->public;
    }

    /**
     * Set public
     *
     * @param int $public
     */
    public function set_public(int $public): void
    {
        $this->public = $public;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is archived?
     *
     * @return int
     */
    public function is_archived(): int
    {
        return $this->archived;
    }

    /**
     * Set archived
     *
     * @param int $archived
     */
    public function set_archived(int $archived): void
    {
        $this->archived = $archived;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is mature?
     *
     * @return int
     */
    public function is_mature(): int
    {
        return $this->mature;
    }

    /**
     * Set mature
     *
     * @param int $mature
     */
    public function set_mature(int $mature): void
    {
        $this->mature = $mature;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is spam?
     *
     * @return int
     */
    public function is_spam(): int
    {
        return $this->spam;
    }

    /**
     * Set spam
     *
     * @param int $spam
     */
    public function set_spam(int $spam): void
    {
        $this->spam = $spam;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is deleted?
     *
     * @return int
     */
    public function is_deleted(): int
    {
        return $this->deleted;
    }

    /**
     * Set deleted
     *
     * @param int $deleted
     */
    public function set_deleted(int $deleted): void
    {
        $this->deleted = $deleted;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get lang ID
     *
     * @return int
     */
    public function is_lang_id(): int
    {
        return $this->lang_id;
    }

    /**
     * Set lang_id
     *
     * @param int $lang_id
     */
    public function set_lang_id(int $lang_id): void
    {
        $this->lang_id = $lang_id;
    }
}