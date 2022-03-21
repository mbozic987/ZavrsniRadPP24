<?php

class QueryController extends Controller
{

    public function index()
    {
        $this->queryView('');
    }

    public function check()
    {
        if(!isset($_POST['query'])){
            $this->index();
            return;
        }

        if(strlen(trim($_POST['query']))==0){
            $this->queryView('You must enter your code');
            return;
        }

        if(strlen(trim($_POST['query']))!=6){
            $this->queryView('Your code must be 6 digits long');
            return;
        }

        $check = Query::check($_POST['query']);

        if($check==null){
            $this->queryView('Your code is incorrect!!!');
            return;
        }else{
            $this->queryView('Status of your device: ' . $check);
        }
        
    }

    public function queryView($message)
    {
        $this->view->render('index',[
            'message' => $message
        ]);
    }
}