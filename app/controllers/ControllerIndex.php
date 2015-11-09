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


		$form = new FormModel();
		if($this->request->getIsAjax()) {
			$form->load($this->request->getPost());
			if($form->validate() && $form->save()) {
				$this->sendJson(['status' => true]);
			} else {
				$this->sendJson(['status' => false, 'errors' => $form->getErrors()]);
			}
		}
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