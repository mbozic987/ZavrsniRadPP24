<?php

class EmployeeController extends AuthorizationController
{
    private$viewDir = 'private' . DIRECTORY_SEPARATOR . 
                        'employee' . DIRECTORY_SEPARATOR;

    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'employee' => Employee::read()
        ]);
    }

    public function new()
    {
        $this->view->render($this->viewDir . 'new');
    }

    public function addNew()
    {
        //controls
        unset($_POST['passwordcheck']);
        Employee::create($_POST);
        $this->index();

    }

    public function delete($employee_id)
    {
        Employee::delete($employee_id);
        $this->index();
    }
}