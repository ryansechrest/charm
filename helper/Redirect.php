<?php

namespace Charm\Helper;

/**
 * Class Redirect
 *
 * @author Ryan Sechrest
 * @package Charm\Helper
 */
class Redirect
{
    /************************************************************************************/
    // Properties

    /**
     * URL
     *  Where to send user
     *
     * @var string
     */
    protected $url = '';

    /**
     * Status
     *  Which status code to send with redirect
     *
     * @var int
     */
    protected $status = 302;

    /**
     * Source
     *  Name of application performing redirect
     *
     * @var string
     */
    protected $source = 'WordPress';

    /**
     * Local
     *  Whether URL is a local or remote URL
     *
     * @var bool
     */
    protected $local = true;

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Redirect constructor
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
        if (isset($data['url'])) {
            $this->url = $data['url'];
        }
        if (isset($data['status'])) {
            $this->status = $data['status'];
        }
        if (isset($data['source'])) {
            $this->source = $data['source'];
        }
        if (isset($data['local'])) {
            $this->local = $data['local'];
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Redirect to homepage
     *
     * @return bool
     */
    public static function home(): bool
    {
        $params['url'] = '/';
        $params['local'] = true;

        return (new static($params))->go();
    }

    /**
     * Redirect to WordPress login
     *
     * @see wp_login_url()
     * @param string $url
     * @return bool
     */
    public static function login(string $url = ''): bool
    {
        $params['url'] = wp_login_url($url);
        $params['local'] = true;

        return (new static($params))->go();
    }

    /**
     * Redirect to local URL
     *
     * @param array $params
     * @return bool
     */
    public static function local(array $params = []): bool
    {
        $params['local'] = true;

        return (new static($params))->go();
    }

    /**
     * Redirect to remote URL
     *
     * @param array $params
     * @return bool
     */
    public static function remote(array $params): bool
    {
        $params['local'] = false;

        return (new static($params))->go();
    }

    /************************************************************************************/
    // Chainable action methods

    /**
     * Set location
     *
     * @param string $url
     * @return self
     */
    public function url(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Set status
     *
     * @param int $status
     * @return self
     */
    public function status(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Redirect to URL
     *
     * @return bool
     */
    public function go(): bool
    {
        if ($this->local) {
            return wp_safe_redirect($this->url, $this->status, $this->source);
        }

        return wp_redirect($this->url, $this->status, $this->source);
    }

    /************************************************************************************/
    // Get and set methods

    /**
     * Get URL
     *
     * @return string
     */
    public function get_url(): string
    {
        return $this->url;
    }

    /**
     * Set URL
     *
     * @param string $url
     */
    public function set_url(string $url): void
    {
        $this->url = $url;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get status
     *
     * @return int
     */
    public function get_status(): int
    {
        return $this->status;
    }

    /**
     * Set status
     *
     * @param int $status
     */
    public function set_status(int $status): void
    {
        $this->status = $status;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Get source
     *
     * @return string
     */
    public function get_source(): string
    {
        return $this->source;
    }

    /**
     * Set source
     *
     * @param string $source
     */
    public function set_source(string $source): void
    {
        $this->source = $source;
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Is local?
     *
     * @return bool
     */
    public function is_local(): bool
    {
        return $this->local;
    }

    /**
     * Set local
     *
     * @param bool $local
     */
    public function set_local(bool $local): void
    {
        $this->local = $local;
    }
}