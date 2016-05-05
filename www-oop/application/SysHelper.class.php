<?php

namespace Devlabs\App;

/**
 * Class SysHelper
 * @package Devlabs\App
 */
class SysHelper
{
    /**
     * Get the previous value of a POST item
     *
     * @param $item
     * @return string
     */
    public static function formPrevValue($item)
    {
        if (!empty($_POST[$item])) {
            return htmlspecialchars($_POST[$item]);
        }

        return '';
    }

    /**
     * Check if form with a given form_name has been submitted via POST
     *
     * @param $form_name
     * @return bool
     */
    public static function isFormSubmitted($form_name)
    {
        return isset($_POST['form_name']) && $_POST['form_name'] === $form_name;
    }

    /**
     * Convert a timestamp to a string in format YYYY-MM-DD hh:mm:ss
     *
     * @param $timestamp
     * @return bool|string
     */
    public static function datetimeToString($timestamp)
    {
        return date('Y-m-d H:i:s', $timestamp);
    }

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

    /**
     * Return a start date
     *
     * @param $var_date_from
     * @param $value_default
     * @return mixed
     */
    public static function setDateStart(&$var_date_from, $value_default)
    {
        return (isset($var_date_from) && !empty($var_date_from))
            ? $var_date_from
            : $value_default;
    }

    /**
     * Return an end date
     *
     * @param $var_date_to
     * @param $sec_offset
     * @return bool|string
     */
    public static function setDateEnd(&$var_date_to, $sec_offset)
    {
        return (isset($var_date_to) && !empty($var_date_to))
            ? date("Y-m-d", strtotime($var_date_to) + $sec_offset)
            : date("Y-m-d", time() + 1209600);
    }
}