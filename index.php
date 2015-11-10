<?php
const MAIN_DIRECTORY = __DIR__;
const BASE_URL = '';

$composer_autoload_path = MAIN_DIRECTORY . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

if(is_file($composer_autoload_path)){
	require($composer_autoload_path);
}

function loadClassF($class_name) {
	$file = MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name . '.php');
	if(is_file($file)){
		include_once ($file);
	} else {
		throw new Exception("Error load class $class_name . Not found file " . $file);
	}    
}

spl_autoload_register('loadClassF');

require(MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Application.php');

$config = require(MAIN_DIRECTORY . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config.php');

$app = new Application($config);
$app->run();