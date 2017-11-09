<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 10.02.2016
 * Time: 12:54
 */

namespace app\helpers;


use app\core\SimpleObject;

class Json extends Object
{

    public static function decode($data)
    {
        return json_decode($data);
    }


    public static function encode($data)
    {
        return json_encode($data);
    }

}