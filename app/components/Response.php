<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 10.02.2016
 * Time: 11:23
 */

namespace app\components;


use app\Application;
use app\helpers\Json;
use app\helpers\Xml;

class Response extends  ComponentBase
{

    const EVENT_BEFORE_SEND = 'beforeSend';

    const FORMAT_JSON = 'application/json';
    const FORMAT_XML = 'text/xml';
    const FORMAT_HTML = 'text/html';
    const GZIP = 'gzip';

    public $data;
    public $format = self::FORMAT_HTML;
    public $charset = 'utf8';

    public $content;
    public $xmlConverter = '\app\helpers\Xml';
    public $jsonConverter = '\app\helpers\Json';

    protected $headers = [];


    /**
     * Send response
     */
    public function send()
    {
        $header = $this->format . "; charset=" . $this->getCharset();
        $this->setHeaders('Content-Type', $header);
        $this->getContent();
        $this->notify(self::EVENT_BEFORE_SEND);
        if(in_array(self::GZIP, $this->headers)){
            $content = gzencode($this->content);
            $this->setHeaders('vary', 'accept-encoding');
            $this->setHeaders('content-length', strlen($content));
            echo $content;
        } else {
            echo $this->content;
        }
        exit(0);
    }


    public function sendError($message, $codeHttp = null)
    {
        $this->setHttpCode($codeHttp);

        echo "Create error send";die;
        $this->send();
    }


    protected function getContent()
    {
        switch ($this->format) {
            case self::FORMAT_JSON:
                $class = $this->jsonConverter;
                $content = $class::encode($this->data);
                break;
            case self::FORMAT_XML:
                $class= $this->xmlConverter;
                $content = $class::encode($this->data);
                break;
            default:
                $content = (string) $this->data;
        }
        $this->content = $content;
    }


    /**
     * @param $name
     * @param $value
     */
    public function setHeaders($name, $value)
    {
        $this->headers[] = $value;
        header($name . ": " . $value);
    }


    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }


    /**
     * @param $code
     */
    protected function setHttpCode($code)
    {
        if(!empty($code) && is_integer($code)){
            http_response_code($code);
        }
    }


    /**
     * @return string
     */
    protected function getCharset()
    {
        return $this->charset;
    }


}