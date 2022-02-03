<?php

namespace Charm\Helper;

/**
 * Class Generate
 *
 * @author Ryan Sechrest
 * @package Charm\Helper
 */
class Generate
{
    /************************************************************************************/
    // Action methods

    /**
     * Generate random string of letters
     *
     * @param int $length
     * @return string
     */
    public static function string(int $length = 12): string
    {
        $string = '';
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $string .= $characters[$index];
        }

        return $string;
    }

    /**
     * Generate random password
     *
     * @see wp_generate_password()
     * @param int $length
     * @return string
     */
    public static function password(int $length = 12): string
    {
        return wp_generate_password($length, false, false);
    }

    /**
     * Generate random password with special chars
     *
     * @see wp_generate_password()
     * @param int $length
     * @return string
     */
    public static function special_password(int $length = 12): string
    {
        return wp_generate_password($length, true, false);
    }

    /**
     * Generate random password with extra special chars
     *
     * @see wp_generate_password()
     * @param int $length
     * @return string
     */
    public static function extra_special_password(int $length = 12): string
    {
        return wp_generate_password($length, true, true);
    }
}