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
     * URL to login
     *  e.g. https://wpcharm.com/wp-login.php?redirect_to=<path>
     *
     * @param string $path
     * @return string
     */
    public static function login_url(string $path = ''): string
    {
        return wp_login_url($path);
    }

    /**
     * URL to register
     *  e.g. https://wpcharm.com/wp-login.php?action=register
     *
     * @return string
     */
    public static function register_url(): string
    {
        return wp_registration_url();
    }

    /**
     * URL to site
     *  e.g. https://wpcharm.com/<path>
     *
     * @param string $path
     * @return string
     */
    public static function site_url(string $path = ''): string
    {
        return get_site_url() . '/' . $path;
    }

    /**
     * Path to site
     *  e.g. /var/www/domains/wpcharm.com/<path>
     *
     * @param string $path
     * @return string
     */
    public static function site_path(string $path = ''): string
    {
        return get_home_path()  . $path;
    }

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