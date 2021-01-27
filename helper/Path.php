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
     *  e.g. https://domain.com/wp-content/theme/<name>/<path>
     *
     * @param string $path
     * @return string
     */
    public static function theme(string $path = ''): string
    {
        return get_template_directory_uri() . '/' . $path;
    }
}