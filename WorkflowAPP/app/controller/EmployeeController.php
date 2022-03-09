<?php

class EmployeeController extends AuthorizationController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 
                         'employee' . DIRECTORY_SEPARATOR;

    private $message;
    private $employee;

    public function __construct()
    {
        parent::__construct();
        $this->employee = new stdClass();
        $this->employee->firstname='';
        $this->employee->lastname='';
        $this->employee->phonenum='';
        $this->employee->email='';
        $this->employee->userpassword='';
        $this->employee->passwordcheck='';
        $this->employee->employee_role='Repairman';
    }

    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'employee' => Employee::read()
        ]);
    }

    public function new()
    {
        $this->view->render($this->viewDir . 'new',[
            'message'=>'',
            'employee'=>$this->employee
        ]);
    }

    public function addNew()
    {
        $this->prepareData();

        if($this->firstnameControll()
        && $this->lastnameControll()
        && $this->phonenumControll()
        && $this->emailControll()
        && $this->passwordControll()){
            Employee::create($_POST);
            $this->index();
        }else{
            $this->view->render($this->viewDir . 'new',[
                'message'=>$this->message,
                'employee'=>$this->employee
            ]);
        }
    }

    public function edit($employee_id)
    {
        $this->employee = Employee::readOne($employee_id);
        $this->view->render($this->viewDir . 'edit',[
            'message'=>'',
            'employee'=>$this->employee
        ]);
    }

    public function editOne()
    {
        $this->prepareData();

        if($this->firstnameControll()
        && $this->lastnameControll()
        && $this->phonenumControll()
        && $this->emailControll()
        && $this->passwordControll()){
            Employee::update($_POST);
            $this->index();
        }else{
            $this->view->render($this->viewDir . 'edit',[
                'message'=>$this->message,
                'employee'=>$this->employee
            ]);
        }
    }

    private function prepareData()
    {
        $this->employee=(object)$_POST;
    }

    private function firstnameControll()
    {
        if(strlen(trim($this->employee->firstname))===0){
            $this->message='You must enter first name!';
            return false;
        }
        if(strlen($this->employee->firstname)>50){
            $this->message='First name can not be longer then 50 characters!';
            return false;
        }
        return true;
    }

    private function lastnameControll()
    {
        if(strlen(trim($this->employee->lastname))===0){
            $this->message='You must enter last name!';
            return false;
        }
        if(strlen($this->employee->lastname)>50){
            $this->message='Last name can not be longer then 50 characters!';
            return false;
        }
        return true;
    }

    private function phonenumControll()
    {
        if(strlen(trim($this->employee->phonenum))===0){
            $this->message='You must enter phone number!';
            return false;
        }
        if(strlen($this->employee->phonenum)>20){
            $this->message='Phone number can not be longer then 20 characters!';
            return false;
        }
        return true;
    }

    private function emailControll()
    {
        if(strlen(trim($this->employee->email))===0){
            $this->message='E-mail adress is required!';
            return false;
        }
        if(strlen($this->employee->email)>50){
            $this->message='E-mail adress can not be longer then 50 characters!';
            return false;
        }
        return true;
    }

    private function passwordControll()
    {
        if(strlen(trim($this->employee->userpassword))===0
        && strlen(trim($this->employee->passwordcheck))===0){
            $this->message='Please enter and re-enter password!';
            return false;
        }
        if(trim($this->employee->userpassword)!=trim($this->employee->passwordcheck)){
            $this->message='Your passwords do not match!';
            return false;
        }
        unset($_POST['passwordcheck']);
        unset($this->employee->passwordcheck);
        $_POST['userpassword']=password_hash($this->employee->userpassword,PASSWORD_BCRYPT);
        return true;
    }

    public function delete($employee_id)
    {
        Employee::delete($employee_id);
        $this->index();
    }
}