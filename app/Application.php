<?php
use components\Request;
use components\SeviceLocator;

Class Application {
	
	protected $request;
	protected static $config;
    protected static $app;


	/**
	 * Run application
	 */
	public function run(Request $request) {
		try {
            $this->request = $request;
			echo $this->route();
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}


	public function __construct($config) {
        self::$config = $config;
	}


	/**
	 * Run method in controller
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function route() {
		$controller_name = '\controllers\Controller' . ucfirst($this->request->getController());
		$action_name = 'Action'.ucfirst($this->request->getAction());
		$controller = new  $controller_name;
		if(method_exists($controller, $action_name)){
			$controller->request = $this->request;
			return $controller->$action_name();
		} else {
			throw new Exception('Method ' . $action_name . ' not exist.');
		}
	}

    /**
     * @return SeviceLocator
     */
	public static function app() {
        if(empty(self::$app)){
            self::$app = new SeviceLocator(self::$config);
        }
        return self::$app;
	}
}
