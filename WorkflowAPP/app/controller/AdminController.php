<?php

abstract class AdminController extends AuthorizationController 
{
    public function __construct()
    {
        parent::__construct();
        if($_SESSION['authorized']->employee_role!=='Admin'){
            $this->view->render('login',[
                    'message' => 'You must login as administrator!!!',
                    'email' => ''
                ]);
            exit;
        }
    }       
}