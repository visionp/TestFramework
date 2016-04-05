<?php 
namespace app\components;

use \app\helpers\Json;
use app\helpers\Xml;

Class Request  extends  ComponentBase
{
	
	public $defaultController = 'index';
	public $defaultAction = 'index';


	/**
	 * Get controller name
	 *
	 * @return mixed
	 */
	public function getController()
	{
		return $this->getParam(1, $this->defaultController);
	}


	/**
	 * Get action name
	 *
	 * @return mixed
	 */
	public function getAction()
	{
		return $this->getParam(2, $this->defaultAction);
	}


	/**
	 * Get post value by name or POST data
	 *
	 * @param null $attribute
	 * @return array | object
	 */
	public function post($attribute = null)
	{
		if($attribute) {
			return isset($_POST[$attribute]) ? $_POST[$attribute] : null;
		} else {
			return $_POST;
		}
	}


	public function inputData()
	{
		$content = file_get_contents('php://input');
		return $content;
	}


	public function getPostXml(){
		$data = null;
		$contentType = trim($_SERVER['CONTENT_TYPE']);
		$contentType = strtolower($contentType);
		if(!empty($contentType) && in_array($contentType, [Response::FORMAT_JSON, Response::FORMAT_XML])){
			$content = $this->inputData();

			$data = $content; //urldecode($content);
		}
		return $data;
	}


    /**
	 * Get post value by name or POST data
	 *
	 * @param null $attribute
	 * @return bool
	 */
	public function get($attribute = null)
	{
		if($attribute) {
			return isset($_GET[$attribute]) ? $_GET[$attribute] : false;
		} else {
			return $_GET;
		}
	}


	/**
	 * Check is ajax method
	 *
	 * @return bool
	 */
	public function getIsAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }


	/**
	 * Check is post method
	 *
	 * @return bool
	 */
	public function getIsPost()
	{
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}


    public function getPath()
    {
        $path = isset($_SERVER['PATH_INFO']) && !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : $_SERVER['REQUEST_URI'];
        $path = explode('?', $path);
        return array_shift($path);
    }

	/**
	 * get controller or action name
	 *
	 * @param $index
	 * @param $default
	 * @return mixed
	 */
	protected function getParam($index, $default)
	{
		$path = $this->getPath();
		$name = $default;
		if($path){
			$controller_pre = explode('/', $path);
			if(isset($controller_pre[$index]) && strlen($controller_pre[$index]) > 0){
				$name = $controller_pre[$index];
			}
		}
		return $name;
	}
	
	
}