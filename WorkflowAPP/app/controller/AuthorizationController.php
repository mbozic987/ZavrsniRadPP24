<?php

abstract class AuthorizationController extends Controller 
{
    public function __construct()
    {
        parent::__construct();
        if(!isset($_SESSION['authorized'])){
            $this->view->render('login',[
                    'message' => 'First you must login',
                    'email' => ''
                ]);
            exit;
        }
    }       
}