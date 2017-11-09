<?php

namespace app;
use app\exceptions\NotFoundException;

/**
 * Created by PhpStorm.
 * User: Vision
 * Date: 20.02.2016
 * Time: 15:24
 */

class ApplicationConsole extends Application
{

    protected static $config = [
        'components' => [
            'request' => [
                'class' => 'app\components\RequestConsole'
            ],
            'response' => [
                'class' => 'app\components\ResponseConsole'
            ],
            'logger' => [
                'class' => 'app\components\Log'
            ],
            'errorHandler' => [
                'class' => '\app\components\ErrorHandler'
            ]
        ]
    ];


    /**
     * @return mixed
     * @throws NotFoundException
     */
    public function route()
    {

        $this->request = $this->getRequest();

        $controller_name = 'app\commands\Controller' . ucfirst($this->request->getController());
        $action_name = 'Action'.ucfirst($this->request->getAction());

        if(!class_exists($controller_name)){
            throw new NotFoundException('Not found route: ' . $this->request->getPath());
        }

        $controller = new  $controller_name ();

        if(method_exists($controller, $action_name)){
            $response = $controller->$action_name();
            return $response ? $response : self::app()->response;
        } else {
            throw new NotFoundException('Not found route: ' . $this->request->getPath());
        }
    }

}