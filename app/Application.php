<?php
use components\Request;
use components\SeviceLocator;

/**
 *
 * @property components\Request $request The request component.
 *
 */

Class Application {
	
	protected $request;
    protected static $app;
    protected static $config = [
        'components' => [
            'request' => [
                'class' => 'components\Request'
            ]
        ]
    ];

	/**
	 * Run application
	 */
	public function run() {
		try {
            $this->request = self::app()->request;
			echo $this->route();
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}


    /**
     * Application constructor.
     * @param $config
     */
	public function __construct($config) {
        self::$config = array_replace_recursive(self::$config, $config);
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
