<?php

class DashboardController extends AuthorizationController 
{
    private $viewDirAdmin = 'private' . DIRECTORY_SEPARATOR . 
                        'admin' . DIRECTORY_SEPARATOR;

    private $viewDirRep = 'private' . DIRECTORY_SEPARATOR . 
                        'repairman' . DIRECTORY_SEPARATOR;

    private $workorder;

    public function __construct()
    {
        parent::__construct();
        $this->workorder = new stdClass();
    }

    public function index()
    {
        if($_SESSION['authorized']->employee_role==='Admin'){
    
            $this->view->render($this->viewDirAdmin . 'dashboard',[
                'css'=>'<link rel="stylesheet" href="' . App::config('url') . 'public/css/graph.css">',
                'javascript'=>'<script src="https://code.highcharts.com/highcharts.js"></script>
                <script src="https://code.highcharts.com/modules/exporting.js"></script>
                <script src="https://code.highcharts.com/modules/export-data.js"></script>
                <script src="https://code.highcharts.com/modules/accessibility.js"></script>
                <script>
                    let wodata=' . json_encode(Workorder::statusCount(), JSON_NUMERIC_CHECK) . ';
                </script>
                <script src="' . App::config('url') . 'public/js/admindash.js"></script>'
            ]);
        }else{
            $this->view->render($this->viewDirRep . 'dashboard',[
                'eclaim' => Workorder::readNew(),
                'econt' => Workorder::readClaimed()

            ]);

        }
    }

    public function claim($workorder_id)
    {
        Workorder::claim($workorder_id);
        $this->workorder = Workorder::readOne($workorder_id);
        $this->view->render($this->viewDirRep . 'details',[
            'entity'=>$this->workorder,
            'message'=>''
        ]);
    }

    public function continue($workorder_id)
    {
        $this->workorder = Workorder::readOne($workorder_id);
        $this->view->render($this->viewDirRep . 'details',[
            'entity'=>$this->workorder,
            'message'=>''
        ]);
    }
}