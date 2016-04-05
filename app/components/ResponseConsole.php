<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 20.02.2016
 * Time: 15:25
 */

namespace app\components;


class ResponseConsole extends Response
{

    public function send()
    {
        $this->getContent();
        fwrite(STDOUT, $this->content . "\n\r");
        die;
    }


    public function sendError($message, $codeHttp = null, $codeStatus = -1)
    {
        $this->data = $message;
        $this->send();
    }


    protected function getContent()
    {
        $this->content = (string) $this->data;
    }

}