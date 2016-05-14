<?php

namespace Devlabs\App;

/**
 * Class DateHelper
 * @package Devlabs\App
 */
class DateHelper
{
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