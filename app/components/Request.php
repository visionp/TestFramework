<?php 
namespace app\components;

Class Request  extends  ComponentBase {
	
	public $defaultController = 'index';
	public $defaultAction = 'index';


	/**
	 * Get controller name
	 *
	 * @return mixed
	 */
	public function getController() {
		return $this->getParam(1, $this->defaultController);
	}


	/**
	 * Get action name
	 *
	 * @return mixed
	 */
	public function getAction () {
		return $this->getParam(2, $this->defaultAction);
	}


	/**
	 * Get post value by name or POST data
	 *
	 * @param null $attribute
	 * @return bool
	 */
	public function getPost($attribute = null) {
		if($attribute) {
			return isset($_POST[$attribute]) ? $_POST[$attribute] : false;
		} else {
			return $_POST;
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
	public function getIsPost(){
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}


	/**
	 * get controller or action name
	 *
	 * @param $index
	 * @param $default
	 * @return mixed
	 */
	protected function getParam($index, $default) {

		$path = $_SERVER['PATH_INFO'];
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