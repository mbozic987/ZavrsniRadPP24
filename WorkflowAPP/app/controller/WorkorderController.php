<?php

class WorkorderController extends AuthorizationController
{
    private$viewDir = 'private' . DIRECTORY_SEPARATOR . 
                        'workorder' . DIRECTORY_SEPARATOR;

    private $message;
    private $workorder;

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
        $this->workorder->repairman_id=0;
        $this->workorder->repairman_firstname='';
        $this->workorder->frontdesk_id=0;
        $this->workorder->frontdesk_firstname='';
        $this->workorder->malfunction='';
        $this->workorder->recive_date='';
        $this->workorder->status_name='';
        $this->workorder->work_done='';
        $this->workorder->query_id='';
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
            $this->view->render($this->viewDir . 'details',[
                'entity'=>$this->workorder,
                'message'=>'',
                'action'=>'Add new work order   >>>'
            ]);
        }else{
            $this->view->render($this->viewDir . 'details',[
                'entity'=>Workorder::readOne($workorder_id),
                'message'=>'',
                'action'=>'Edit existing work order   >>>'
            ]);
        }
    }

    public function action()
    {
        $this->prepareData();

        if($_POST['workorder_id']==0){
            if($this->controlls){
                Workorder::create($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'entity'=>$this->workorder,
                    'message'=>$this->message,
                    'action'=>'Add new work order   >>>'
                ]);
                return;
            }
        }else{
            if($this->controlls){
                Workorder::update($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'entity'=>$this->workorder,
                    'message'=>$this->message,
                    'action'=>'Edit work order   >>>'
                ]);
                return;
            }
        }
        header('location:' . App::config('url') . 'workorder/index');
    }

    private function prepareData()
    {
        $this->workorder=(object)$_POST;
    }

    public function delete($workorder_id)
    {
        Workorder::delete($workorder_id);
        $this->index();
    }
}