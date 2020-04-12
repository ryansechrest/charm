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

    public static function init($config)
    {
        $charm = new static($config);
        $charm->register();
    }

    /************************************************************************************/
    // Action methods

    public function register()
    {
        $this->register_tools();
    }

    public function register_tools()
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

    public function register_cron_viewer()
    {
        add_action('admin_menu', function() {
            add_management_page(
                'Cron Viewer',
                'Cron Viewer',
                'manage_options',
                'charm-cron-viewer',
                [$this, 'render_cron_viewer_page']
            );
        });
    }

    public function render_cron_viewer_page()
    {
        $output = '<div class="wrap">';
        $output .= '<h1>' . esc_html(get_admin_page_title()) . ' <small>by Charm</small></h1>';
        $output .= Cron::to_html();
        echo $output;
    }

    /************************************************************************************/
    // Check methods

    public function is_enabled($key)
    {
        if (!isset($this->config[$key])) {
            return false;
        }

        return $this->config[$key];
    }
}