<?php
namespace app\core;
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 11.02.2016
 * Time: 16:06
 */
abstract class SimpleObject
{

    public function __construct()
    {
        $this->init();
    }


    /**
     * Method run after self::__construct()
     */
    public function init()
    {
    }


    /**
     * @return mixed
     */
    public static function className()
    {
        return static::class;
    }

}