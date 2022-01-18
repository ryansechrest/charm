<?php

namespace Charm\Integration;

/**
 * Class WPML
 *
 * Requires "WPML String Translation" plugin to be active.
 * Cannot be used in other mu-plugins; just theme and regular plugins.
 *
 * @author Ryan Sechrest
 * @package Charm\Integration
 */
class WPML
{
    /************************************************************************************/
    // Constants

    /**
     * Text domain
     *
     * @var string
     */
    const TEXT_DOMAIN = 'Charm';

    /************************************************************************************/
    // Action methods

    /**
     * Get single string
     *
     * @param string $name
     * @param string $value
     * @param string|null $lang
     * @return string
     */
    public static function get_single_string(string $name, string $value, string $lang = null): string
    {
        return apply_filters(
            'wpml_translate_single_string',
            $value,
            static::TEXT_DOMAIN,
            $name,
            $lang
        );
    }

    /**
     * Register single string
     *
     * @param string $name
     * @param string $value
     */
    public static function register_single_string(string $name, string $value): void
    {
        do_action(
            'wpml_register_single_string',
            static::TEXT_DOMAIN,
            $name,
            $value
        );
    }
}