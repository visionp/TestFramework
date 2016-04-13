<?php
namespace app;

use app\components\SeviceLocator;
use app\core\AdvancedObject;
use app\exceptions\NotFoundException;


/**
 *
 * @property components\Request $request The request component.
 *
 */

Class Application extends AdvancedObject{

	protected $request;
	protected $errorHandler;
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
			],
			'errorHandler' => [
				'class' => '\app\components\ErrorHandler'
			]
        ]
    ];


	/**
	 * Run application
	 */
	public function run()
	{
		$response = $this->route();
		$response->send();
	}


    /**
     * Application constructor.
     * @param $config
     */
	public function __construct($config)
	{
		parent::__construct();
        static::$config = array_replace_recursive(static::$config, $config);
		App::$conf = static::$config;
	}


	public function init()
	{
		$this->errorHandler = $this->getErrorHandler();
		$this->errorHandler->debug = DEBUG;
		$this->errorHandler->registerHandlers();
	}


	/**
	 * Run method in controller
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function route()
	{
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
	public static function app()
	{
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


	/**
	 * Returns the error handler component.
	 * @return components\ErrorHandler
	 */
	public function getErrorHandler()
	{
		return static::app()->errorHandler;
	}


	public static function getParams($attr)
	{
		return isset(static::$config[$attr]) ? static::$config[$attr] : [];
	}

}
