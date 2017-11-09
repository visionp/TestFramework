<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 20.02.2016
 * Time: 15:22
 */

namespace app\components;


class RequestConsole extends Request
{

    /**
     * @return mixed
     */
    public function getPath()
    {
        $path = isset($_SERVER['argv'][1])? $_SERVER['argv'][1] : '';
        $path = explode('?', $path);
        return array_shift($path);
    }


    /**
     * @param $index
     * @param $default
     * @return mixed
     */
    protected function getParam($index, $default)
    {
        $path = $this->getPath();

        $name = $default;
        if($path){
            $controller_pre = explode('/', '/' . $path);
            if(isset($controller_pre[$index]) && strlen($controller_pre[$index]) > 0){
                $name = $controller_pre[$index];
            }
        }
        return $name;
    }


    public function post($attribute = null)
    {
        $this->error();
    }


    public function inputData()
    {
        $this->error();
    }


    public function getPostXml(){
        $this->error();
    }


    public function get($attribute = null)
    {
        $this->error();
    }


    public function getIsAjax()
    {
        $this->error();
    }


    public function getIsPost()
    {
        $this->error();
    }


    protected function error($m = 'Not data. Console mode.')
    {
        return null;
    }


}