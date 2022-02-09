<?php


//display all errors and warnings
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

session_start();

define('BP', __DIR__ . DIRECTORY_SEPARATOR);
define('BP_APP', __DIR__ . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);

$path = implode(
    PATH_SEPARATOR,
    [
        BP_APP . 'model',
        BP_APP . 'controller',
        BP_APP . 'core'
    ]
);

set_include_path($path);

spl_autoload_register(function($class){
    $path = explode(PATH_SEPARATOR,get_include_path());
    foreach($path as $p){
        $directory = $p . DIRECTORY_SEPARATOR . $class . '.php';
        if(file_exists($directory)){
            include_once $directory;
            break;
        }
    }
});

App::start();