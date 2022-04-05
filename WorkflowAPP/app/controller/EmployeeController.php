<?php

class EmployeeController extends AdminController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 
                         'employee' . DIRECTORY_SEPARATOR;

    private $message;
    private $employee;

    public function __construct()
    {
        parent::__construct();
        $this->employee = new stdClass();
        $this->employee->employee_id=0;
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
        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page=(int)$_GET['page'];
        }
        if($page==0){
            $page = 1;
        }

        if(!isset($_GET['cond'])){
            $cond = '';
        }else{
            $cond=$_GET['cond'];
        }

        $employeeTotal = Employee::employeeTotal($cond);
        $pageTotal = ceil($employeeTotal / App::config('rpp'));

        if($page>$pageTotal){
            $page = $pageTotal;
        }

        $this->view->render($this->viewDir . 'index',[
            'employee' => Employee::read($page, $cond),
            'cond'=>$cond,
            'page'=>$page,
            'pageTotal'=>$pageTotal
        ]);
    }

    public function details($employee_id=0)
    {
        if($employee_id===0){
            $this->view->render($this->viewDir . 'details',[
                'employee'=>$this->employee,
                'message'=>'',
                'action'=>'Add new employee   >>>'
            ]);
        }else{
            $this->view->render($this->viewDir . 'details',[
                'employee'=>Employee::readOne($employee_id),
                'message'=>'',
                'action'=>'Edit existing employee   >>>'
            ]);
        }
    }

    public function action()
    {
        $this->prepareData();

        if($_POST['employee_id']==0){
            if($this->firstnameControll()
            && $this->lastnameControll()
            && $this->phonenumControll()
            && $this->emailControll()
            && $this->passwordControll()){
                unset($_POST['employee_id']);
                Employee::create($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'message'=>$this->message,
                    'employee'=>$this->employee,
                    'action'=>'Add new employee   >>>'
                ]);
                return;
            }
        }else{
            if($this->firstnameControll()
            && $this->lastnameControll()
            && $this->phonenumControll()
            && $this->emailControll()
            && $this->passwordControll()){
                Employee::update($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'message'=>$this->message,
                    'employee'=>$this->employee,
                    'action'=>'Edit new employee   >>>'
                ]);
                return;
            }
        }
        header('location:' . App::config('url') . 'employee/index');
        
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
        if($_POST['employee_id']==0){
            if(strlen(trim($this->employee->userpassword))===0
            || strlen(trim($this->employee->passwordcheck))===0){
                $this->message='Please enter and re-enter password!';
                return false;
            }
            if(trim($this->employee->userpassword)!=trim($this->employee->passwordcheck)){
                $this->message='Your passwords do not match!';
                return false;
            }
            unset($_POST['passwordcheck']);
            $_POST['userpassword']=password_hash($this->employee->userpassword,PASSWORD_BCRYPT);
            return true;
        }elseif($_POST['userpassword']==='' 
            && $_POST['passwordcheck']===''){
                $_POST['userpassword']=0;
                return true;
        }else{
            if(trim($this->employee->userpassword)!=trim($this->employee->passwordcheck)){
                $this->message='Your passwords do not match!';
                return false;
            }
            $_POST['userpassword']=password_hash($this->employee->userpassword,PASSWORD_BCRYPT);
            return true;
        }
    }

    public function delete($employee_id)
    {
        Employee::delete($employee_id);
        $this->index();
    }


    public function test($function){
        switch ($function) {
            case 'add1000':
                for($i=0;$i<1000;$i++){
                    Employee::create([
                        'firstname'=>'Pero',
                        'lastname'=>'PerPerić',
                        'phonenum'=>'09898080',
                        'email'=>'pero@pero.com',
                        'userpassword'=>'perica',
                        'employee_role'=>'Serviser'
                    ]);
                }
                break;
            case 'add':
                Employee::create([
                    'fistname'=>'Pero',
                    'lastname'=>'Perić',
                    'phonenum'=>'09898080',
                    'email'=>'pero@pero.com',
                    'userpassword'=>'perica',
                    'employee_role'=>'Serviser'
                ]);
                break;
            case 'edit':
                Employee::update([
                    'fistname'=>'Pero111',
                    'lastname'=>'Perić111',
                    'phonenum'=>'09898080111',
                    'email'=>'pero@pero.com111',
                    'userpassword'=>'perica111',
                    'employee_role'=>'Admin',
                    'employee_id'=>4
                ]);
                break;
            case 'delete':
                Employee::delete(4);
                break;
            case 'index':
                print_r(Employee::read());
                break;
            case 'read':
                print_r(Employee::readOne(1));
                break;
            default:
                # code...
                break;
        }
    }
}