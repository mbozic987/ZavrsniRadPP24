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

        $parameter1=null;
        if(!isset($part[3]) || $part[3]===''){
            $parameter1 = null;
        }else{
            $parameter1 = $part[3];
        }

        $parameter2=null;
        if(!isset($part[4]) || $part[4]===''){
            $parameter2 = null;
        }else{
            $parameter2 = $part[4];
        }

        if(class_exists($class) && method_exists($class,$method)){
            $instance = new $class();
            if($parameter1==null){
                $instance->$method();
            }else{
                if($parameter2==null){
                    $instance->$method($parameter1);
                }else{
                    $instance->$method($parameter1,$parameter2);
                }  
            }
            
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

    public static function admin()
    {
        if(App::authorized() && $_SESSION['authorized']->employee_role==='Admin'){
            return true;
        }

        return false;
    }
}