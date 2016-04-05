<?php
const MAIN_DIRECTORY = __DIR__ . DIRECTORY_SEPARATOR . '..';
const BASE_URL = '';
const DEBUG = true;

$composer_autoload_path = MAIN_DIRECTORY . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

if(is_file($composer_autoload_path)){
	require($composer_autoload_path);
}

function loadClassF($class_name) {
	$file = MAIN_DIRECTORY . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name . '.php');
	if(is_file($file)){
		include_once ($file);
	}
}

spl_autoload_register('loadClassF');

$exceptionHandler = new \app\components\ErrorHandler();
$exceptionHandler->debug = DEBUG;
$exceptionHandler->registerHandlers();

require(MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Application.php');

$config = require(MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config.php');

$app = new \app\Application($config);

$app->run();