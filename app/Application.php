<?php
namespace app;

use app\components\SeviceLocator;
use app\exceptions\CurlException;
use app\exceptions\ErrorException;
use app\exceptions\ErrorParseXml;
use app\exceptions\NotFoundException;
use app\exceptions\SignException;

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
                'class' => 'app\components\Request'
            ],
            'response' => [
                'class' => 'app\components\Response'
            ],
			'logger' => [
				'class' => 'app\components\Log'
			]
        ]
    ];


	/**
	 * Run application
	 */
	public function run() {
		$response = $this->route();
		$response->send();
	}


    /**
     * Application constructor.
     * @param $config
     */
	public function __construct($config) {
        static::$config = array_replace_recursive(static::$config, $config);
		App::$conf = static::$config;
	}


	/**
	 * Run method in controller
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function route() {
		$this->request = $this->getRequest();

		$controller_name = 'app\controllers\Controller' . ucfirst($this->request->getController());
		$action_name = 'Action'.ucfirst($this->request->getAction());

		if(!class_exists($controller_name)){
			throw new NotFoundException('Not found route: ' . $this->request->getPath());
		}

		$controller = new  $controller_name;

		if(method_exists($controller, $action_name)){
			$response = $controller->$action_name();
			return $response ? $response : static::app()->response;
		} else {
			throw new NotFoundException('Not found route: ' . $this->request->getPath());
		}
	}


    /**
     * @return SeviceLocator
     */
	public static function app() {
        if(empty(static::$app)){
			static::$app = new SeviceLocator(static::$config);
        }
        return static::$app;
	}


    /**
     * Returns the request component.
     * @return components\Request
     */
    public function getRequest()
    {
        return static::app()->request;
    }


    /**
     * Returns the request component.
     * @return components\Response
     */
    public function getResponse()
    {
        return static::app()->response;
    }


	public static function getParams($attr)
	{
		return isset(static::$config[$attr]) ? static::$config[$attr] : [];
	}
}
