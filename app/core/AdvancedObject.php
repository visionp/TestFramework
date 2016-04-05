<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 12.02.2016
 * Time: 9:56
 */

namespace app\core;


use app\interfaces\Observable;


abstract class AdvancedObject extends Object implements Observable
{

    protected $_observers = [];


    public function attach($callableFunction, $typeEvent)
    {
        if(is_callable($callableFunction)){
            $this->_observers[$typeEvent][] = $callableFunction;
        }

    }


    public function detach($callableFunction, $typeEvent)
    {
        if($this->issetHandlers($typeEvent)){
            foreach($this->_observers[$typeEvent] as $key => $handler){
                if($callableFunction === $handler){
                    unset($this->_observers[$typeEvent][$key]);
                }
            }
        }
    }


    public function notify($typeEvent)
    {
        if($this->issetHandlers($typeEvent)){
            foreach($this->_observers[$typeEvent] as $handler){
                if(is_callable($handler)){
                    call_user_func($handler, $this);
                }
            }
        }
    }


    protected function issetHandlers($typeEvent)
    {
        return isset($this->_observers[$typeEvent]);
    }

}