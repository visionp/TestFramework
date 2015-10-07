<?php
const MAIN_DIRECTORY = __DIR__;
const BASE_URL = '';

function __autoload($class_name) {
	$file = MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . $class_name . '.php';
	if(is_file($file)){
		include_once $file;
	} else {
		throw new Exception('Not found file ' . $file);
	}    
}

$request = new Request();
$app = new Application($request);
$app->run();