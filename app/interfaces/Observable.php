<?php
namespace app\interfaces;

/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 12.02.2016
 * Time: 9:59
 */
interface Observable
{
    public function attach($callableFunction, $typeEvent);

    public function detach($callableFunction, $typeEvent);

    public function notify($typeEvent);
}