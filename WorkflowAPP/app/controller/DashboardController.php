<?php

class DashboardController extends AuthorizationController 
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR;

    public function index()
    {
        if($_SESSION['authorized']->employee_role==='Admin'){
            $this->view->render($this->viewDir . 'adminDashboard');
        }else{
            $this->view->render($this->viewDir . 'repairmanDashboard');

        }
    }
}