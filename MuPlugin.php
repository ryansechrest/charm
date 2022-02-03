<?php

namespace Charm;

use Charm\Helper\File;

/**
 * Class MuPlugin
 *
 * @author Ryan Sechrest
 * @package Charm
 */
class MuPlugin
{

    /**
     * Autoload and initialize mu-plugin
     *
     * Assuming $name = 'ProjectName', this method will attempt to autoload all
     * mu-plugin files within a 'projectname' directory, and then attempt to call the
     * init() method within a class of the same name and namespace, e.g.
     * 'ProjectName\ProjectName::init()'
     */
    public static function run(string $name): void
    {
        $mu_plugin = strtolower($name);
        static::autoload($mu_plugin);
        static::init($name);
    }

    /**
     * Autoload all mu-plugin files
     *
     * @param string $mu_plugin
     */
    public static function autoload(string $mu_plugin = ''): void
    {
        if ($mu_plugin !== '') {
            $file_paths = MuPlugin::get_file_paths($mu_plugin);
            File::require_once($file_paths);
            return;
        }
        $traces = debug_backtrace();
        if (!isset($traces[0]['file'])) {
            return;
        }
        $path = str_replace('.php', '', $traces[0]['file']);
        $file_paths = File::get_file_paths($path);
        File::require_once($file_paths);
    }

    /**
     * Get path of all files within mu-plugin path
     *
     * @param string $mu_plugin
     * @return array
     */
    public static function get_file_paths(string $mu_plugin): array
    {
        return File::get_file_paths(WPMU_PLUGIN_DIR . '/' . $mu_plugin);
    }

    /**
     * Initialize plugin after WordPress loads
     *
     * @param string $class_name
     */
    public static function init(string $class_name)
    {
        add_action('init', [$class_name . '\\' . $class_name, 'init']);
    }
}