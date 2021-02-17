<?php

namespace Charm\Helper;

/**
 * Class Path
 *
 * @author Ryan Sechrest
 * @package Charm\Helper
 */
class Path
{
    /**
     * URL to theme
     *  e.g. https://wpcharm.com/wp-content/themes/charm/<path>
     *
     * @param string $path
     * @return string
     */
    public static function theme_url(string $path = ''): string
    {
        return get_template_directory_uri() . '/' . $path;
    }

    /**
     * Path to theme
     *  e.g. /var/www/domains/wpcharm.com/wp-content/themes/charm/<path>
     *
     * @param string $path
     * @return string
     */
    public static function theme_path(string $path = ''): string
    {
        return get_template_directory() . '/' . $path;
    }
}