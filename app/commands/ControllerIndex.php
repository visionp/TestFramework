<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 20.02.2016
 * Time: 15:33
 */

namespace app\commands;


class ControllerIndex extends ControllerBaseConsole
{

    public function actionIndex ()
    {
        $str = 'test';
        return $this->render($str);
    }


    public function actionTest()
    {
        $r = 45;
        return $this->render($r);
    }
}