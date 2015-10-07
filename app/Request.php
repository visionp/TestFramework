<?php 
Class Request {
	
	public $defaultController = 'index';
	public $defaultAction = 'index';
	
	
	public function getController() {
		return $this->getParam(1, $this->defaultController);
	}
	
	
	public function getAction () {
		return $this->getParam(2, $this->defaultAction);
	}
	
	
	public function getPost($attribute = null) {
		if($attribute) {
			return isset($_POST[$attribute]) ? $_POST[$attribute] : false;
		} else {
			return $_POST;
		}
	}
	
	
	public function getIsAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }


	public function getIsPost(){
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	
	
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