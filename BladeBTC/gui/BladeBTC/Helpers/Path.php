<?php

namespace BladeBTC\GUI\Helpers;


class Path
{
    /**
     * Return HTTPS or HTTP
     *
     * @return string
     */
    private static function getHttpProtocol()
    {
        return (isset($_SERVER['HTTPS']) &&
            ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
            ? $protocol = 'https://' : $protocol = 'http://';
    }


    /**
     * Return full website URL.
     *
     * @return string
     */
    public static function root()
    {
        return self::getHttpProtocol() . $_SERVER['HTTP_HOST'] . '/gui';
    }

    /**
     * Return module folder URL.
     *
     * @return string
     */
    public static function module()
    {
        return self::getHttpProtocol() . $_SERVER['HTTP_HOST'] . "/gui/views";
    }

    /**
     * Return css path
     *
     * @return string
     */
    public static function css()
    {
        return self::root() . "/dist/css";
    }

    /**
     * Return js path
     *
     * @return string
     */
    public static function js()
    {
        return self::root() . "/dist/js";
    }

    /**
     * Return components path
     *
     * @return string
     */
    public static function comp()
    {
        return self::root() . "/dist/components";
    }

    /**
     * Return images path
     *
     * @return string
     */
    public static function img()
    {
        return self::root() . "/dist/img";
    }

    /**
     * Return profile images path
     *
     * @return string
     */
    public static function profileImg()
    {
        return self::root() . "/dist/img/profiles";
    }

    /**
     * Return product images path
     *
     * @return string
     */
    public static function productImg()
    {
        return self::root() . "/dist/img/products";
    }

    /**
     * Return product images path
     *
     * @return string
     */
    public static function downloads()
    {
        return self::root() . "/downloads";
    }


    /**
     * Return temp download path
     *
     * @return string
     */
    public static function temp()
    {
        return self::root() . "/downloads/temp";
    }


    /**
     * Return PDF path
     *
     * @return string
     */
    public static function pdf()
    {
        return self::root() . "/app/PDF";
    }
}