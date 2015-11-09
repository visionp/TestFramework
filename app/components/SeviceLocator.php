<?php
/**
 * Created by PhpStorm.
 * User: VisioN
 * Date: 09.11.2015
 * Time: 21:12
 */

namespace components;


class SeviceLocator extends  ComponentBase {

    protected $components = [];
    protected $config;


    public function __construct($config) {
        $this->config = isset($config['components']) ? $config['components'] : [];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name) {
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
    protected function createObj($name) {
        if(!isset($this->config[$name])) {
            throw new \Exception("Unknown component $name");
        }

        if(!isset($this->config[$name]['class'])) {
            throw new \Exception("Set name class $name");
        }
        $className = $this->config[$name]['class'];
        $obj = new $className();

        if(isset($this->config[$name]['options'])){
            foreach($this->config[$name]['options'] as $k => $v) {
                $this->setParams($obj, $k, $v);
            }
        }
        return $obj;
    }


    /**
     * @param $obj
     * @param $property
     * @param $value
     * @throws \Exception
     */
    protected function setParams(&$obj, $property, $value) {
        if(!property_exists($obj, $property)) {
            throw new \Exception("Unknown property $property in " . get_class($obj));
        }else {
            $obj->$property = $value;
        }
    }

}