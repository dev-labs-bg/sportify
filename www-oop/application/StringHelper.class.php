<?php

namespace Devlabs\App;

/**
 * Class StringHelper
 * @package Devlabs\App
 */
class StringHelper
{
    /**
     * Generate a random string with a given length, using alphanumeric and special characters
     *
     * @param int $string_length
     * @return string
     */
    public static function randomStringSpecial($string_length = 20)
    {
        $chars_list = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&';
        $chars_count = strlen($chars_list);
        $string = '';

        for ($i = 0; $i < $string_length; $i++) {
            $string .= $chars_list[rand(0, $chars_count - 1)];
        }

        return $string;
    }

    /**
     * Generate a random string with a given length, using only alphanumeric characters
     *
     * @param int $string_length
     * @return string
     */
    public static function randomStringAlphanum($string_length = 20)
    {
        $chars_list = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chars_count = strlen($chars_list);
        $string = '';

        for ($i = 0; $i < $string_length; $i++) {
            $string .= $chars_list[rand(0, $chars_count - 1)];
        }

        return $string;
    }
}
