<?php

class DeviceController extends AuthorizationController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 
                        'device' . DIRECTORY_SEPARATOR;

    private $message;
    private $device;

    public function __construct()
    {
        parent::__construct();
        $this->device = new stdClass();
        $this->device->device_id=0;
        $this->device->firstname='';
        $this->device->lastname='';
        $this->device->company='';
        $this->device->phonenum='';
        $this->device->email='';
        $this->device->manufacturer='';
        $this->device->model='';
        $this->device->serialnum='';
    }

    public function index()
    {
       $this->view->render($this->viewDir . 'index',[
           'entity' => Device::read()
       ]);
    } 

    public function details($device_id=0)
    {
        if($device_id===0){
            $this->view->render($this->viewDir . 'details',[
                'entity'=>$this->device,
                'message'=>'',
                'action'=>'Add new device   >>>'
            ]);
        }else{
            $this->view->render($this->viewDir . 'details',[
                'entity'=>Device::readOne($device_id),
                'message'=>'',
                'action'=>'Edit existing device   >>>'
            ]);
        }
    }

    public function action()
    {
        $this->prepareData();

        if($_POST['device_id']==0){
            if($this->firstnameControll()
            && $this->lastnameControll()
            && $this->companyControll()
            && $this->phonenumControll()
            && $this->emailControll()
            && $this->manufacturerControll()
            && $this->modelControll()
            && $this->serialnumControll()){
                unset($_POST['device_id']);
                Device::create($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'entity'=>$this->device,
                    'message'=>$this->message,
                    'action'=>'Add new device   >>>'
                ]);
                return;
            }
        }else{
            if($this->firstnameControll()
            && $this->lastnameControll()
            && $this->companyControll()
            && $this->phonenumControll()
            && $this->emailControll()
            && $this->manufacturerControll()
            && $this->modelControll()
            && $this->serialnumControll()){
                Device::update($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'entity'=>$this->device,
                    'message'=>$this->message,
                    'action'=>'Edit existing device   >>>'
                ]);
                return;
            }
        }
        header('location:' . App::config('url') . 'device/index');
    }

    public function delete($device_id)
    {
        Device::delete($device_id);
        header('location:' . App::config('url') . 'device/index');
    }

    private function prepareData()
    {
        $this->device=(object)$_POST;
    }

    //input controlls
    private function firstnameControll()
    {
        if(strlen(trim($this->device->firstname))===0){
            $this->message='You must enter first name!';
            return false;
        }
        if(strlen($this->device->firstname)>50){
            $this->message='First name can not be longer then 50 characters!';
            return false;
        }
        return true;
    }

    private function lastnameControll()
    {
        if(strlen(trim($this->device->lastname))===0){
            $this->message='You must enter last name!';
            return false;
        }
        if(strlen($this->device->lastname)>50){
            $this->message='Last name can not be longer then 50 characters!';
            return false;
        }
        return true;
    }

    private function companyControll()
    {
        if(strlen($this->device->lastname)>50){
            $this->message='Company name can not be longer then 50 characters!';
            return false;
        }
        return true;
    }

    private function phonenumControll()
    {
        if(strlen(trim($this->device->phonenum))===0){
            $this->message='You must enter phone number!';
            return false;
        }
        if(strlen($this->device->phonenum)>20){
            $this->message='Phone number can not be longer then 20 characters!';
            return false;
        }
        return true;
    }

    private function emailControll()
    {
        if(strlen($this->device->email)>50){
            $this->message='E-mail adress can not be longer then 50 characters!';
            return false;
        }
        return true;
    }

    private function manufacturerControll()
    {
        if(strlen(trim($this->device->manufacturer))===0){
            $this->message='You must enter manufacturer of your device!';
            return false;
        }
        if(strlen($this->device->manufacturer)>50){
            $this->message='Manufacturer name can not be longer then 30 characters!';
            return false;
        }
        return true;
    }

    private function modelControll()
    {
        if(strlen(trim($this->device->model))===0){
            $this->message='You must enter model of your device!';
            return false;
        }
        if(strlen($this->device->model)>30){
            $this->message='Model can not be longer then 30 characters!';
            return false;
        }
        return true;
    }

    private function serialnumControll()
    {
        if(strlen($this->device->serialnum)>20){
            $this->message='Serial number can not be longer then 50 characters!';
            return false;
        }
        return true;
    }
}
