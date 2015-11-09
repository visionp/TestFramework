<?php
namespace controllers;

Class ControllerBase {

	public $request;
	
	
	public function sendJson($data) {
		echo json_encode($data);
		die();
	}

	
	protected function render($viewName, $options = []) {
		$view = new View($viewName, $options);
		return $view->render();
	}
	
}