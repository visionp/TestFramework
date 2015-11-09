<?php
namespace controllers;

Class ControllerIndex extends ControllerBase{

	public $request;
	
	public function actionIndex() {

		$redis = new \Redis();
		$redis->connect('127.0.0.1', 6379);
		$redis->auth('root');
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
	
	
	public function sendJson($data) {
		echo json_encode($data);
		die();
	}

	
	protected function render($viewName, $options = []) {
		$view = new View($viewName, $options);
		return $view->render();
	}
	
}