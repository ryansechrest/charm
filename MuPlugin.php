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
}