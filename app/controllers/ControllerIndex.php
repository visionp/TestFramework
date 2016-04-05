<?php
namespace app\controllers;

use app\models\FormModel;
use app\components\Storage;
use app\models\StadiumModel;

Class ControllerIndex extends ControllerBase
{

	public $request;
	
	public function actionIndex()
	{

		/*
        //$redis = \Application::app()->redis;
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        $redis->auth('root');
        $key = 'linus torvalds';
        $redis->hset($key, 'age', 44);
        $redis->hset($key, 're', 'ssdf');
        $t = $redis->hMGet($key, ['age', 're']);

        //$t = $redis->hGetAll($key);

        var_dump($t);


        die();
		*/

        $this->view->registerJs('alert(5);', false);
        $model = new StadiumModel();
		return $this->render('index', [
			'model' => $model
		]);
	}	
	
	
	public function actionList()
	{
		$storage = new Storage();
		return $this->render('list', [
			'data' => $storage->get()
		]);
	}
	
}