<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 20.02.2016
 * Time: 15:31
 */

namespace app\commands;


use app\controllers\ControllerBase;

class ControllerBaseConsole extends ControllerBase
{

    protected function render($data = null) {
        if(empty($data)){
            $data = '';
        }
        $response = $this->getResponse($data);
        $this->notify(self::EVENT_BEFORE_RENDER);
        return $response;
    }


}