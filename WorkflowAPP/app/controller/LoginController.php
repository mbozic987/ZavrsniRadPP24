<?php

class LoginController extends Controller
{
    public function index()
    {
        $this->loginView('Input your username and password','');
    }

    public function authorize()
    {
        if(!isset($_POST['username']) || !isset($_POST['password'])){
            $this->index();
            return;
        }

        if(strlen(trim($_POST['username']))===0){
            $this->loginView('You must enter your username','');
            return;
        }

        if(strlen(trim($_POST['password']))===0){
            $this->loginView('You must enter your password',$_POST['username']);
            return;
        }


    }

    public function loginView($message,$email)
    {
        $this->view->render('login',[
            'message' => $message,
            'username' => $username
        ]);
    }
}