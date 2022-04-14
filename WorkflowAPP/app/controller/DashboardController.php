<?php

class DashboardController extends AuthorizationController 
{
    private $viewDirAdmin = 'private' . DIRECTORY_SEPARATOR . 
                        'admin' . DIRECTORY_SEPARATOR;

    private $viewDirRep = 'private' . DIRECTORY_SEPARATOR . 
                        'repairman' . DIRECTORY_SEPARATOR;

    public function index()
    {
        if($_SESSION['authorized']->employee_role==='Admin'){
            $this->view->render($this->viewDirAdmin . 'adminDashboard');
        }else{
            $this->view->render($this->viewDirRep . 'repairmanDashboard');

        }
    }
}