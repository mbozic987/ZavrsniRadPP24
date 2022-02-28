<?php

class App 
{
    public static function start()
    {
        $route = Request::getRoute();

        $part = explode('/',$route);

        $class = '';
        if(!isset($part[1]) || $part[1]===''){
            $class = 'Index';
        }else{
            $class = ucfirst($part[1]);
        }

        $class .= 'Controller';

        $method = '';
        if(!isset($part[2]) || $part[2]===''){
            $method = 'Index';
        }else{
            $method = $part[2];
        }

        if(class_exists($class) && method_exists($class,$method)){
            $instance = new $class();
            $instance->$method();
        }else{
            $view = new View();
            $view->render('error404',[
                'missingcontent' => $class . '->' . $method
            ]);
        }
    }   

    public static function config($key)
    {
        $config = include BP_APP . 'config.php';
        return $config[$key];
    }

    public static function authorized()
    {
        if(isset($_SESSION) && isset($_SESSION['authorized'])){
            return true;
        }

        return false;
    }
}