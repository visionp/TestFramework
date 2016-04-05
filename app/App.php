<?php
namespace app;
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 24.02.2016
 * Time: 11:22
 */
class App
{
    public static $conf;

    public static function getParams($attr)
    {
        return isset(static::$conf[$attr]) ? static::$conf[$attr] : [];
    }

}