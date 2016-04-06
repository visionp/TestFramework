<?php
/**
 * Created by PhpStorm.
 * User: VisioN
 * Date: 09.11.2015
 * Time: 21:12
 */

namespace app\components;


class SeviceLocator extends  ComponentBase {

    public $config;
    protected $components = [];


    public function __construct($config)
    {
        $this->config = isset($config['components']) ? $config['components'] : [];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if(!isset($this->components[$name])) {
            $this->components[$name] = $this->createObj($name);
        }
        return $this->components[$name];
    }


    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    protected function createObj($name)
    {

        if(!isset($this->config[$name])) {
            throw new \Exception("Unknown component $name");
        }

        if(!isset($this->config[$name]['class'])) {
            throw new \Exception("Set name class $name");
        }

        $className = $this->config[$name]['class'];

        unset($this->config[$name]['class']);

        $obj = new $className();

        if(isset($this->config[$name])){
            foreach($this->config[$name]as $k => $v) {
                $this->setParam($obj, $k, $v);
            }
        }

        if(method_exists($obj, 'init')){
            $obj->init();
        }

        return $obj;
    }


    /**
     * @param $obj
     * @param $property
     * @param $value
     * @throws \Exception
     */
    protected function setParam(&$obj, $property, $value)
    {
        if(!property_exists($obj, $property)) {
            throw new \Exception("Unknown property " . get_class($obj) . "::" . $property);
        }else {
            $obj->$property = $value;
        }
    }

}