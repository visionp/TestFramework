<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 20.02.2016
 * Time: 15:31
 */

namespace app\commands;


use app\controllers\ControllerBase;
use app\exceptions\BaseException;

class ControllerBaseConsole extends ControllerBase
{

    protected function render($viewName, $options = []) {
        throw new BaseException('Method ' . __METHOD__ . ' for console is deprecated');
    }

    protected function renderToConsole($data = null)
    {
        if(empty($data)){
            $data = '';
        }
        $response = $this->getResponse($data);
        $this->notify(self::EVENT_BEFORE_RENDER);
        return $response;
    }

    protected function echoToConsole($string)
    {
        fwrite(STDOUT, $string . "\n\r");
    }

}