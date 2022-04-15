<?php

class WorkorderController extends AuthorizationController
{
    private$viewDir = 'private' . DIRECTORY_SEPARATOR . 
                        'workorder' . DIRECTORY_SEPARATOR;

    private $message;
    private $workorder;
    private $clientLabel;
    private $deviceLabel;

    public function __construct()
    {
        parent::__construct();
        $this->workorder = new stdClass();
        $this->workorder->workorder_id=0;
        $this->workorder->device_id=0;
        $this->workorder->manufacturer='';
        $this->workorder->model='';
        $this->workorder->serialnum='';
        $this->workorder->client_id=0;
        $this->workorder->firstname='';
        $this->workorder->lastname='';
        $this->workorder->company='';
        $this->workorder->phonemun='';
        $this->workorder->email='';
        $this->workorder->repairman_id=1;
        $this->workorder->repairman_firstname='';
        $this->workorder->frontdesk_id=0;
        $this->workorder->frontdesk_firstname='';
        $this->workorder->malfunction='';
        $this->workorder->recive_date='';
        $this->workorder->repair_status=1;
        $this->workorder->status_name='';
        $this->workorder->work_done='';
        $this->workorder->query_id=substr(md5(microtime()), 0, 6);
        $this->workorder->repair_date='';
    }

    public function index()
    {
        if(!isset($_GET['page'])){
            $page = 1;
        }else{
            $page = (int)$_GET['page'];
        }
        if($page==0){
            $page = 1;
        }

        if(!isset($_GET['cond'])){
            $cond = '';
        }else{
            $cond = $_GET['cond'];
        }

        $workorderTotal = Workorder::workorderTotal($cond);
        $pageTotal = ceil($workorderTotal / App::config('rpp'));

        if($page>$pageTotal){
            $page = $pageTotal;
        }

        $this->view->render($this->viewDir . 'index',[
            'entity' => Workorder::read($page,$cond),
            'cond' => $cond,
            'page' => $page,
            'pageTotal' => $pageTotal
        ]);
    }

    public function details($workorder_id=0)
    {
        if($workorder_id===0){
            $clientLabel = '<strong>Client is not selected!</strong>';
            $deviceLabel = '<strong>Device is not selected!</strong>';
            $this->view->render($this->viewDir . 'details',[
                'entity'=>$this->workorder,
                'message'=>'',
                'clientLabel'=>$clientLabel,
                'deviceLabel'=>$deviceLabel,
                'action'=>'Add new work order   >>>',
                'css'=>'<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">',
                'javascript'=>'<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
                <script src="' . App::config('url') . 'public/js/workorderDetails.js"></script>'
            ]);
        }else{
            $this->workorder = Workorder::readOne($workorder_id);
            $clientLabel = $this->workorder->firstname . ' ' . $this->workorder->lastname . ' ' . $this->workorder->company;
            $deviceLabel = $this->workorder->manufacturer . ' ' . $this->workorder->model . ' ' . $this->workorder->serialnum;
            $this->view->render($this->viewDir . 'details',[
                'entity'=>$this->workorder,
                'message'=>'',
                'clientLabel'=>$clientLabel,
                'deviceLabel'=>$deviceLabel,
                'action'=>'Edit existing work order   >>>',
                'css'=>'<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">',
                'javascript'=>'<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
                <script src="' . App::config('url') . 'public/js/workorderDetails.js"></script>'
            ]);
        }
    }

    public function action()
    {
        $this->prepareData();

        if($_POST['workorder_id']==0){
            if($this->clientControll()
            && $this->deviceControll()
            && $this->malfunctionControll()){
                Workorder::create($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'entity'=>$this->workorder,
                    'message'=>$this->message,
                    'clientLabel'=>$this->clientLabel,
                    'deviceLabel'=>$this->deviceLabel,
                    'action'=>'Add new work order   >>>',
                    'css'=>'<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">',
                    'javascript'=>'<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
                    <script src="' . App::config('url') . 'public/js/workorderDetails.js"></script>'
                ]);
                return;
            }
        }else{
            if($this->clientControll()
            && $this->deviceControll()
            && $this->malfunctionControll()){
                Workorder::update($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'entity'=>$this->workorder,
                    'message'=>$this->message,
                    'clientLabel'=>$this->clientLabel,
                    'deviceLabel'=>$this->deviceLabel,
                    'action'=>'Edit work order   >>>',
                    'css'=>'<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">',
                    'javascript'=>'<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
                    <script src="' . App::config('url') . 'public/js/workorderDetails.js"></script>'
                ]);
                return;
            }
        }
        header('location:' . App::config('url') . 'workorder/index');
    }

    public function delete($workorder_id)
    {
        Workorder::delete($workorder_id);
        header('location:' . App::config('url') . 'workorder/index');
    }

    private function prepareData()
    {
        $this->workorder=(object)$_POST;
    }

    private function clientControll()
    {
        if(trim($this->workorder->client)==0){
            $this->message='You must enter client!';
            $this->clientLabel='Client is not selected!';
            if($_POST['device']==0){
                $this->deviceLabel='Device is not selected!';
            }else{
                $this->deviceLabel=$_POST['manufacturer'] . ' ' . $_POST['model'] . ' ' . $_POST['serialnum'];
            }
            return false;
        }
        return true;
    }

    private function deviceControll()
    {
        if(trim($this->workorder->device)==0){
            $this->message='You must enter device!';
            $this->clientLabel=$_POST['firstname'] . ' ' . $_POST['lastname'] . ' ' . $_POST['company'];
            $this->deviceLabel='Device is not selected!';
            return false;
        }
        return true;
    }

    private function malfunctionControll()
    {
        if(trim($this->workorder->malfunction)===0){
            $this->message='You must enter malfunction!';
            $this->clientLabel=$_POST['firstname'] . ' ' . $_POST['lastname'] . ' ' . $_POST['company'];
            $this->deviceLabel=$_POST['manufacturer'] . ' ' . $_POST['model'] . ' ' . $_POST['serialnum'];
            return false;
        }
        if(strlen($this->workorder->malfunction)>255){
            $this->message='Malfunction description can not be longer then 255 characters!';
            $this->clientLabel=$_POST['firstname'] . ' ' . $_POST['lastname'] . ' ' . $_POST['company'];
            $this->deviceLabel=$_POST['manufacturer'] . ' ' . $_POST['model'] . ' ' . $_POST['serialnum'];
            return false;
        }
        return true;
    }
}