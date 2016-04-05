<?php
namespace app\controllers;

use app\Application;
use app\core\AdvancedObject;
use app\core\Object;
use app\views\View;

Class ControllerBase extends AdvancedObject
{

	const EVENT_BEFORE_ACTION = 'beforeAction';
	const EVENT_BEFORE_RENDER = 'beforeRender';

	public $view;
	public $request;

    /**
     * ControllerBase constructor.
     */

    public function __construct()
    {
		$this->notify(self::EVENT_BEFORE_ACTION);
        $this->view = new View();
    }


	protected function renderXml(\stdClass $data)
	{
		$response = $this->getResponse($data);
		$response->format = \app\components\Response::FORMAT_XML;
		$this->notify(self::EVENT_BEFORE_RENDER);
		return $response;
	}


	protected function renderJson($data)
	{
		$response = $this->getResponse($data);
		$response->format = \app\components\Response::FORMAT_JSON;
		$this->notify(self::EVENT_BEFORE_RENDER);
		return $response;
	}


	protected function render($viewName, $options = []) {
		$this->view->name = $viewName;
		$this->view->params = $options;
		$data = $this->view->render();
		$response = $this->getResponse($data);
		$response->format = \app\components\Response::FORMAT_HTML;
		$this->notify(self::EVENT_BEFORE_RENDER);
		return $response;
	}


	protected function getResponse($data)
	{
		$response = Application::app()->response;
		$response->data = $data;
		return $response;
	}

}