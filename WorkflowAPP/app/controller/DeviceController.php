<?php

class DeviceController extends AdminController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 
                        'device' . DIRECTORY_SEPARATOR;

    private $message;
    private $device;
    private $clientLabel;

    public function __construct()
    {
        parent::__construct();
        $this->device = new stdClass();
        $this->device->device_id=0;
        $this->device->client=0;
        $this->device->firstname='';
        $this->device->lastname='';
        $this->device->company='';
        $this->device->manufacturer='';
        $this->device->model='';
        $this->device->serialnum='';
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
            $cond = $_GET['cond'];
        }

        $deviceTotal = Device::deviceTotal($cond);
        $pageTotal = ceil($deviceTotal / App::config('rpp'));

        if($page>$pageTotal){
            $page = $pageTotal;
        }

       $this->view->render($this->viewDir . 'index',[
           'entity' => Device::read($page,$cond),
           'cond' => $cond,
           'page' => $page,
           'pageTotal' => $pageTotal
       ]);
    } 

    public function details($device_id=0)
    {   
        if($device_id===0){
            $clientLabel = 'Client is not selected!';
            $this->view->render($this->viewDir . 'details',[
                'entity'=>$this->device,
                'message'=>'',
                'clientLabel'=>$clientLabel,
                'action'=>'Add new device   >>>',
                'css'=>'<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">',
                'javascript'=>'<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
                <script src="' . App::config('url') . 'public/js/deviceDetails.js"></script>'
            ]);
        }else{
            $this->device = Device::readOne($device_id);
            $clientLabel = $this->device->firstname . ' ' . $this->device->lastname . ' ' . $this->device->company;
            $this->view->render($this->viewDir . 'details',[
                'entity'=>$this->device,
                'message'=>'',
                'clientLabel'=>$clientLabel,
                'action'=>'Edit existing device   >>>',
                'css'=>'<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">',
                'javascript'=>'<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
                <script src="' . App::config('url') . 'public/js/deviceDetails.js"></script>'
            ]);
        }
    }

    public function action()
    {
        $this->prepareData();

        if($_POST['device_id']==0){
            if($this->clientControll()
            && $this->manufacturerControll()
            && $this->modelControll()
            && $this->serialnumControll()){
                unset($_POST['device_id']);
                Device::create($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'entity'=>$this->device,
                    'message'=>$this->message,
                    'clientLabel'=>$this->clientLabel,
                    'action'=>'Add new device   >>>'
                ]);
                return;
            }
        }else{
            if($this->clientControll()
            && $this->manufacturerControll()
            && $this->modelControll()
            && $this->serialnumControll()){
                Device::update($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'entity'=>$this->device,
                    'message'=>$this->message,
                    'clientLabel'=>$this->clientLabel,
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
    
    private function clientControll()
    {
        if($this->device->client==0){
            $this->message='You must choose a client!';
            $this->clientLabel=$_POST['firstname'] . ' ' . $_POST['lastname'] . ' ' . $_POST['company'];
            return false;
        }
        return true;
    }

    private function manufacturerControll()
    {
        if(strlen(trim($this->device->manufacturer))===0){
            $this->message='You must enter manufacturer of your device!';
            $this->clientLabel=$_POST['firstname'] . ' ' . $_POST['lastname'] . ' ' . $_POST['company'];
            return false;
        }
        if(strlen($this->device->manufacturer)>50){
            $this->message='Manufacturer name can not be longer then 30 characters!';
            $this->clientLabel=$_POST['firstname'] . ' ' . $_POST['lastname'] . ' ' . $_POST['company'];
            return false;
        }
        return true;
    }

    private function modelControll()
    {
        if(strlen(trim($this->device->model))===0){
            $this->message='You must enter model of your device!';
            $this->clientLabel=$_POST['firstname'] . ' ' . $_POST['lastname'] . ' ' . $_POST['company'];
            return false;
        }
        if(strlen($this->device->model)>30){
            $this->message='Model can not be longer then 30 characters!';
            $this->clientLabel=$_POST['firstname'] . ' ' . $_POST['lastname'] . ' ' . $_POST['company'];
            return false;
        }
        return true;
    }

    private function serialnumControll()
    {
        if(strlen($this->device->serialnum)>20){
            $this->message='Serial number can not be longer then 50 characters!';
            $this->clientLabel=$_POST['firstname'] . ' ' . $_POST['lastname'] . ' ' . $_POST['company'];
            return false;
        }
        return true;
    }

    public function test($function){
        switch ($function) {
            case 'add1000':
                for($i=0;$i<1000;$i++){
                    Device::create([
                        'firstname'=>'Pero',
                        'lastname'=>'Perić',
                        'company'=>'Peronato',
                        'phonenum'=>'09898080',
                        'email'=>'pero@pero.com',
                        'manufacturer'=>'Kercher',
                        'model'=>'Peraja 3000',
                        'serialnum'=>'9876543210'
                    ]);
                }
                break;
            case 'add':
                Device::create([
                    'firstname'=>'Pero',
                    'lastname'=>'Perić',
                    'company'=>'Peronato',
                    'phonenum'=>'09898080',
                    'email'=>'pero@pero.com',
                    'manufacturer'=>'Kercher',
                    'model'=>'Peraja 3000',
                    'serialnum'=>'9876543210'
                ]);
                break;
            case 'edit':
                Device::update([
                    'firstname'=>'Pero123',
                    'lastname'=>'Perić123',
                    'company'=>'Peronato123',
                    'phonenum'=>'09898080',
                    'email'=>'pero@pero.com',
                    'manufacturer'=>'Kercher',
                    'model'=>'Peraja 3000',
                    'serialnum'=>'9876543210',
                    'device_id'=>4
                ]);
                break;
            case 'delete':
                Device::delete(4);
                break;
            case 'index':
                print_r(Device::read());
                break;
            case 'read':
                print_r(Device::readOne(1));
                break;
            default:
                # code...
                break;
        }
    }
}
