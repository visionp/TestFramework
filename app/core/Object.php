<?php
namespace app\core;
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 11.02.2016
 * Time: 16:06
 */
abstract class Object
{
    public function init()
    {
    }

    public static function className()
    {
        return static::class;
    }

}