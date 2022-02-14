<?php

class App 
{
    public static function start()
    {
        $route = Request::getRoute();
        //echo $route;

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
            echo $class . '->' . $method . '() does not exist!';
        }
    }   
}