<?php

namespace Charm;

use Charm\Cron\Cron;

/**
 * Class Charm
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class Charm
{
    /************************************************************************************/
    // Properties

    /**
     * Configuration
     *
     * @var array
     */
    private $config = [];

    /************************************************************************************/
    // Default constructor and load method

    /**
     * Charm constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = [
            'cron_viewer' => true,
        ];
        $this->load($config);
    }

    /**
     * Load instance with config
     *
     * @param array $config
     */
    public function load(array $config): void
    {
        foreach ($config as $key => $value) {
            if (!isset($this->config[$key])) {
                continue;
            }
            $this->config[$key] = $value;
        }
    }

    /************************************************************************************/
    // Instantiation methods

    /**
     * Initialize Charm
     *
     * @param array $config
     */
    public static function init(array $config)
    {
        $charm = new static($config);
        $charm->register();
    }

    /************************************************************************************/
    // Action methods

    /**
     * Register everything
     */
    public function register(): void
    {
        $this->register_tools();
    }

    /**
     * Register tools
     */
    public function register_tools(): void
    {
        $tools = [
            'cron_viewer'
        ];
        foreach ($tools as $tool) {
            if (!$this->is_enabled($tool)) {
                continue;
            }
            $method = 'register_' . $tool;
            if (!method_exists($this, $method)) {
                continue;
            }
            $this->$method();
        }
    }

    /*----------------------------------------------------------------------------------*/

    /**
     * Register cron viewer
     */
    public function register_cron_viewer(): void
    {
        add_action('admin_menu', [$this, 'add_cron_viewer_page']);
    }

    /**
     * Add cron viewer page
     */
    public function add_cron_viewer_page(): void
    {
        add_management_page(
            'Cron Viewer',
            'Cron Viewer',
            'manage_options',
            'charm-cron-viewer',
            [$this, 'render_cron_viewer_page']
        );
    }

    /**
     * Render cron viewer page
     */
    public function render_cron_viewer_page(): void
    {
        $output = '<div class="wrap">';
        $output .= '<h1>' . esc_html(get_admin_page_title()) . ' <small>by <a href="https://wpcharm.com/" target="_blank">Charm</a></small></h1>';
        $output .= Cron::to_html();
        $output .= '</div>';
        echo $output;
    }

    /************************************************************************************/
    // Check methods

    /**
     * Check if feature is enabled
     *
     * @param string $key
     * @return bool
     */
    public function is_enabled(string $key): bool
    {
        if (!isset($this->config[$key])) {
            return false;
        }

        return $this->config[$key];
    }
}