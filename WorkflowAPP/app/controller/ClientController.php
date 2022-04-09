<?php

class ClientController extends AdminController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 
                        'client' . DIRECTORY_SEPARATOR;

    private $message;
    private $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new stdClass();
        $this->client->client_id=0;
        $this->client->firstname='';
        $this->client->lastname='';
        $this->client->company='';
        $this->client->phonenum='';
        $this->client->email='';
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

        $clientTotal = Client::clientTotal($cond);
        $pageTotal = ceil($clientTotal / App::config('rpp'));

        if($page>$pageTotal){
            $page = $pageTotal;
        }

       $this->view->render($this->viewDir . 'index',[
           'client' => Client::read($page,$cond),
           'cond' => $cond,
           'page' => $page,
           'pageTotal' => $pageTotal
       ]);
    } 

    public function details($client_id=0)
    {
        if($client_id===0){
            $this->view->render($this->viewDir . 'details',[
                'client'=>$this->client,
                'message'=>'',
                'action'=>'Add new client   >>>'
            ]);
        }else{
            $this->view->render($this->viewDir . 'details',[
                'client'=>Client::readOne($client_id),
                'message'=>'',
                'action'=>'Edit existing client   >>>'
            ]);
        }
    }

    public function action()
    {
        $this->prepareData();

        if($_POST['client_id']==0){
            if($this->firstnameControll()
            && $this->lastnameControll()
            && $this->companyControll()
            && $this->phonenumControll()
            && $this->emailControll()){
                unset($_POST['client_id']);
                Client::create($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'client'=>$this->client,
                    'message'=>$this->message,
                    'action'=>'Add new client   >>>'
                ]);
                return;
            }
        }else{
            if($this->firstnameControll()
            && $this->lastnameControll()
            && $this->companyControll()
            && $this->phonenumControll()
            && $this->emailControll()){
                Client::update($_POST);
            }else{
                $this->view->render($this->viewDir . 'details',[
                    'client'=>$this->client,
                    'message'=>$this->message,
                    'action'=>'Edit existing client   >>>'
                ]);
                return;
            }
        }
        header('location:' . App::config('url') . 'client/index');
    }

    public function delete($client_id)
    {
        Client::delete($client_id);
        header('location:' . App::config('url') . 'client/index');
    }

    public function searchClient($cond)
    {
        header('Content-type: application/json');
        echo json_encode(Client::clientSearch($cond));
    }

    private function prepareData()
    {
        $this->client=(object)$_POST;
    }

    //input controlls
    private function firstnameControll()
    {
        if(strlen(trim($this->client->firstname))===0){
            $this->message='You must enter first name!';
            return false;
        }
        if(strlen($this->client->firstname)>50){
            $this->message='First name can not be longer then 50 characters!';
            return false;
        }
        return true;
    }

    private function lastnameControll()
    {
        if(strlen(trim($this->client->lastname))===0){
            $this->message='You must enter last name!';
            return false;
        }
        if(strlen($this->client->lastname)>50){
            $this->message='Last name can not be longer then 50 characters!';
            return false;
        }
        return true;
    }

    private function companyControll()
    {
        if(strlen($this->client->lastname)>50){
            $this->message='Company name can not be longer then 50 characters!';
            return false;
        }
        return true;
    }

    private function phonenumControll()
    {
        if(strlen(trim($this->client->phonenum))===0){
            $this->message='You must enter phone number!';
            return false;
        }
        if(strlen($this->client->phonenum)>20){
            $this->message='Phone number can not be longer then 20 characters!';
            return false;
        }
        return true;
    }

    private function emailControll()
    {
        if(strlen($this->client->email)>50){
            $this->message='E-mail adress can not be longer then 50 characters!';
            return false;
        }
        return true;
    }
}
