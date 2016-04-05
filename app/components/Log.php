<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 10.02.2016
 * Time: 20:07
 */

namespace app\components;

use app\helpers\File;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;
use Vube\Monolog\Formatter\SplunkLineFormatter;

class Log extends ComponentBase
{

    public $dirMode = 0775;

    protected $path;
    protected $logFile;
    protected $log;


    public function __construct()
    {
        $this->path = MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'log' ;
        if (!is_dir($this->path)) {
            File::createDirectory($this->path, $this->dirMode, true);
        }
        $this->logFile = $this->path . DIRECTORY_SEPARATOR . 'app.log';

        $this->log = new Logger('app');

        $webProcessor = new WebProcessor();
        $format = "[%datetime%] %channel%.%level_name%: %message% %extra.ip% %extra.http_method% %context% %extra%\n";
        $formatter = new LineFormatter($format, null, true);
        $logRotate = new RotatingFileHandler($this->logFile, 45, Logger::INFO, true, 0777);

        $logRotate->setFormatter($formatter);
        $this->log->pushHandler($logRotate);
        $this->log->pushProcessor($webProcessor);
    }


    public function addRecord($level, $message, array $context = array())
    {
        $this->log->addRecord($level, $message, $context);
    }


    public function addDebug($message, array $context = array())
    {
        $this->log->addDebug($message, $context);
    }


    public function addWarning($message, array $context = array())
    {
        $this->log->addWarning($message, $context);
    }


    public function addError($message, array $context = array())
    {
        $this->log->addError($message, $context);
    }


    public function addCritical($message, array $context = array())
    {
        $this->log->addCritical($message, $context);
    }


    public function addAlert($message, array $context = array())
    {
        $this->log->addAlert($message, $context);
    }


    public function addEmergency($message, array $context = array())
    {
        $this->log->addEmergency($message, $context);
    }


    public function addInfo($message, array $context = array())
    {
        return $this->log->addInfo($message, $context);
    }


    public function addNotice($message, array $context = array())
    {
        return $this->log->addNotice($message, $context);
    }


}