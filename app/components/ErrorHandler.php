<?php
/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 11.02.2016
 * Time: 10:07
 */

namespace app\components;


use app\Application;
use app\exceptions\CurlException;
use app\exceptions\DbException;
use app\exceptions\ErrorException;
use app\exceptions\ErrorParseXml;
use app\exceptions\MethodException;
use app\exceptions\NoExistProperty;
use app\exceptions\NotExistMethod;
use app\exceptions\NotFoundException;
use app\exceptions\NotFoundPayment;
use app\exceptions\NotTrustedIp;
use app\exceptions\ParamsException;
use app\exceptions\SignException;
use app\exceptions\SmsException;
use app\models\ErrorsContainer;
use Doctrine\DBAL\DBALException;
use Monolog\Logger;

class ErrorHandler extends ComponentBase
{

    public $debug = DEBUG;
    protected $otherError = false;


    public function registerHandlers(){
        ini_set('display_errors', false);
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);
        register_shutdown_function([$this, 'handleFatalError']);
    }

    /**
     * @param $e \Exception
     */
    public function handleException($e) {

        $this->otherError = true;
        $code = 500;
        $codeAppStatus = 999;
        $level = Logger::CRITICAL;
        $response = Application::app()->response;
        $customMessage = null;

        if($e instanceof NotFoundException){
            $level = Logger::INFO;
            $code = 404;
        } elseif($e instanceof ParamsException){
            $level = Logger::WARNING;
            $customMessage = 'No Exist Property';
            $code = 400;
        } elseif($e instanceof DBALException){
            $level = Logger::WARNING;
            $customMessage = 'Error db';
            $code = 500;
        } elseif($e instanceof NotExistMethod){
            $level = Logger::CRITICAL;
            $code = 500;
        } elseif($e instanceof DbException){
            $level = Logger::WARNING;
        } elseif($e instanceof NotTrustedIp){
            $level = Logger::WARNING;
        }

        Application::app()->logger->addRecord($level, $e->getMessage(), [
            'exception' => $e,
            'post' => $_POST,
            'get' => $_GET,
            'raw' => Application::app()->request->inputData(),
            'server_env' => $_SERVER
        ]);

        $message = $this->debug ? $e->getMessage() : (!empty($customMessage) ? $customMessage : '');
        $response->sendError($message, $code, $codeAppStatus);

    }


    public function handleError($errno, $errstr, $errfile, $errline)
    {
        $this->otherError = true;
        $error = '';
        $codeAppStatus = 999;
        if (!(error_reporting() & $errno)) {
            // Этот код ошибки не включен в error_reporting
            return;
        }

        switch ($errno) {
            case E_USER_ERROR:
                $error .= "My ERROR< [$errno] $errstr \n\r";
                $error .= "  Фатальная ошибка в строке $errline файла $errfile";
                $error .= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")";
                $error .= "Завершение работы...<br />\n";
                break;

            case E_USER_WARNING:
                $error .= "My WARNING [$errno]. Error: $errstr File: $errfile Line: $errline ";
                break;

            case E_WARNING:
                $error .= "WARNING [$errno]. Error: $errstr File: $errfile Line: $errline ";
                break;

            case E_USER_NOTICE:
                $error .= "My NOTICE [$errno]. Error: $errstr File: $errfile Line: $errline ";
                break;

            default:
                $error .= "Неизвестная ошибка: [$errno]. Error: $errstr File: $errfile Line: $errline ";
                break;
        }


        $response = Application::app()->response;
        Application::app()->logger->addCritical($error, [
            'post' => $_POST,
            'get' => $_GET,
            'raw' => Application::app()->request->inputData(),
            'server_env' => $_SERVER
        ]);

        $message = $this->debug ? $error : 'Error, please try later';

        if(strripos ($errstr, 'simplexml_load_string') !== false){
            $codeAppStatus = 997;
            $message = ErrorsContainer::getErrorDetail($codeAppStatus);
        }

        Application::app()->response->format = Response::FORMAT_XML;
        $response->sendError($message, 500, ErrorsContainer::translate($codeAppStatus));

        /* Не запускаем внутренний обработчик ошибок PHP */
        return true;
    }


    public function handleFatalError()
    {

        $error = error_get_last();

        if(empty($error)){
            return;
        }
        if($this->otherError){
            return;
        }

        if(self::isFatalError($error)){
            Application::app()->logger->addCritical(implode(', ', $error), [
                'post'       => $_POST,
                'get'        => $_GET,
                'raw'        => Application::app()->request->inputData(),
                'server_env' => $_SERVER
            ]);
            $response = Application::app()->response;
            Application::app()->response->format = Response::FORMAT_XML;
            $response->sendError('Error app', 500, ErrorsContainer::translate(999));
            exit(1);
        }

    }


    public static function isFatalError($error)
    {
        return isset($error['type']) && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_CORE_WARNING, E_COMPILE_ERROR, E_COMPILE_WARNING]);
    }

}