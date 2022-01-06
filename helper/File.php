<?php

namespace Charm\Helper;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class File
 *
 * @author Ryan Sechrest
 * @package Charm\Helper
 */
class File
{
    /**
     * Autoload all files in target path
     *
     * @param string $path
     */
    public static function autoload(string $path): void
    {
        $file_paths = File::get_file_paths($path);
        File::require_once($file_paths);
    }

    /**
     * Get path of all files within target path
     *
     * @param string $path
     * @return array
     */
    public static function get_file_paths(string $path): array
    {
        $rdi = new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS);
        $rii = new RecursiveIteratorIterator($rdi);
        $file_infos = array_filter(iterator_to_array($rii), function($file_info) {
            if (!file_exists($file_info->getPathname())) {
                return false;
            }
            return true;
        });

        return array_keys($file_infos);
    }

    /**
     * Include specified file paths
     *
     * @param array $file_paths
     */
    public static function include(array $file_paths): void
    {
        foreach ($file_paths as $file_path) {
            include $file_path;
        }
    }

    /**
     * Include specified file paths once
     *
     * @param array $file_paths
     */
    public static function include_once(array $file_paths): void
    {
        foreach ($file_paths as $file_path) {
            include_once $file_path;
        }
    }

    /**
     * Require specified file paths
     *
     * @param array $file_paths
     */
    public static function require(array $file_paths): void
    {
        foreach ($file_paths as $file_path) {
            require $file_path;
        }
    }

    /**
     * Require specified file paths once
     *
     * @param array $file_paths
     */
    public static function require_once(array $file_paths): void
    {
        foreach ($file_paths as $file_path) {
            require_once $file_path;
        }
    }
}