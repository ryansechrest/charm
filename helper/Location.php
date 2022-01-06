<?php

namespace Charm\Helper;

/**
 * Class Location
 *
 * @author Ryan Sechrest
 * @package Charm\Helper
 */
class Location
{
    /**
     * URL to WordPress user account login
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
     * URL to WordPress user account registration
     *  e.g. https://wpcharm.com/wp-login.php?action=register
     *
     * @return string
     */
    public static function register_url(): string
    {
        return wp_registration_url();
    }

    /**
     * URL to WordPress site
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
     * Path to WordPress site
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
     * URL to WordPress theme
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
     * Path to WordPress theme
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