<?php

namespace Charm\Helper;

/**
 * Class Debug
 *
 * @author Ryan Sechrest
 * @package Charm\Helper
 */
class Debug
{
    /**
     * Dump value on screen using var_dump
     *
     * @param mixed $value
     */
    public static function dump(mixed $value): void
    {
        var_dump($value);
    }

    /**
     * Print value on screen using print_r
     *
     * @param string $label
     * @param mixed $value
     */
    public static function print(mixed $value, string $label = ''): void
    {
        if ($label !== '') {
            $label .= ': ';
        }
        echo '<pre>' . $label . print_r($value, true) . '</pre>';
    }
}