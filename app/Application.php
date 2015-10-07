<?php
Class Application {
	
	protected $request;
	
	public function run() {
		try {
			echo $this->route();
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function __construct(Request $request) {
		$this->request = $request;
	}
	
	public function route() {
		$controller_name = 'Controller' . ucfirst($this->request->getController());
		$action_name = 'Action'.ucfirst($this->request->getAction());
		$controller = new  $controller_name;
		if(method_exists($controller, $action_name)){
			$controller->request = $this->request;
			return $controller->$action_name();
		} else {
			throw new Exception('Method ' . $action_name . ' not exist.');
		}
	}
}
