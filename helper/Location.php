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
     * URL of current page without args
     *  e.g. https://wpcharm.com/sample-page/
     *
     * @return string
     */
    public static function current_url(): string
    {
        $url = Location::current_url_with_args();
        if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '') {
            $url = str_replace('?' . $_SERVER['QUERY_STRING'], '', $url);
        }

        return $url;
    }

    /**
     * URL of current page with arguments
     *  e.g. https://wpcharm.com/sample-page/?foo=bar
     *
     * @see get_site_url()
     * @return string
     */
    public static function current_url_with_args(): string
    {
        if (!isset($_SERVER['REQUEST_URI'])) {
            return '';
        }

        return get_site_url() . $_SERVER['REQUEST_URI'];
    }

    /**
     * URL to WordPress user account login
     *  e.g. https://wpcharm.com/wp-login.php?redirect_to=<path>
     *
     * @see wp_login_url()
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
     * @see wp_registration_url()
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
     * @see get_site_url()
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
     * @see get_home_path()
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
     * @see get_template_directory_uri()
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
     * @see get_template_directory()
     * @param string $path
     * @return string
     */
    public static function theme_path(string $path = ''): string
    {
        return get_template_directory() . '/' . $path;
    }

    /**
     * URL to WordPress mu-plugins
     *  e.g. https://wpcharm.com/wp-content/mu-plugins/<path>
     *
     * @param string $path
     * @return string
     */
    public static function mu_plugin_url(string $path = ''): string
    {
        return WPMU_PLUGIN_URL . '/' . $path;
    }

    /**
     * Path to WordPress mu-plugins
     *  e.g. /var/www/domains/wpcharm.com/wp-content/mu-plugins/<path>
     *
     * @param string $path
     * @return string
     */
    public static function mu_plugin_path(string $path = ''): string
    {
        return WPMU_PLUGIN_DIR . '/' . $path;
    }
}