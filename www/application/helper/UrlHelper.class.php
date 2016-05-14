<?php

namespace Devlabs\App;

/**
 * Class UrlHelper
 * @package Devlabs\App
 */
class UrlHelper
{
    /**
     * Get base host url
     *
     * @return string
     */
    public static function getHomeUrl()
    {
        return 'http://' . $_SERVER['HTTP_HOST'];
    }

    /**
     * Get full site url
     *
     * @return string
     */
    public static function getSiteUrl()
    {
        return self::getHomeUrl() . $_SERVER['REQUEST_URI'];
    }

    /**
     * Get static dir url
     *
     * @return string
     */
    public static function getStaticUrl()
    {
        return self::getHomeUrl() . '/static/';
    }

    /**
     * Get css dir url
     *
     * @return string
     */
    public static function getCssUrl()
    {
        return self::getStaticUrl() . 'css/';
    }

    /**
     * Get js dir url
     *
     * @return string
     */
    public static function getJsUrl()
    {
        return self::getStaticUrl() . 'js/';
    }

    /**
     * Get img dir url
     *
     * @return string
     */
    public static function getImgUrl()
    {
        return self::getStaticUrl() . 'img/';
    }
}