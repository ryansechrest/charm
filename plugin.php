<?php

namespace Charm;

if (!defined('ABSPATH')) {
    die('Whatcha doin? Ƹ̵̡Ӝ̵̨̄Ʒ');
}

/**
 * Plugin Name: Charm <code>Ƹ̵̡Ӝ̵̨̄Ʒ</code>
 * Plugin URI: https://wpcharm.com/
 * Description: An object-oriented WordPress framework for developers to build entities and relationships.
 * Version: 1.0.0
 * Requires at least: 5.3.2
 * Requires PHP: 7.3
 * Author: Ryan Sechrest
 * Author URI: https://ryansechrest.com/
 * Text Domain: charm
 * Network: true
 */

require_once WPMU_PLUGIN_DIR . '/charm/autoloader.php';

/****************************************************************************************/

Charm::init([

    /**
     * View cron events and schedules in WordPress admin
     *
     * Navigate to: Tools > Cron Viewer
     *
     * true  | Enable cron viewer
     * false | Disable cron viewer (default)
     */
    'cron_viewer' => false,

]);
