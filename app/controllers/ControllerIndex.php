<?php
namespace controllers;

use models\FormModel;
use components\Storage;

Class ControllerIndex extends ControllerBase{

	public $request;
	
	public function actionIndex() {

        $redis = \Application::app()->redis;

		//$redis->set('df', 45);
		echo $redis->get('df');
		die();

		return $this->render('index', [
			'form' => $form
		]);
	}	
	
	
	public function actionList() {
		$storage = new Storage();
		return $this->render('list', [
			'data' => $storage->get()
		]);
	}
	
}