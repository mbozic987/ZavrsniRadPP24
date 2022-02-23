<?php

class LoginController extends Controller
{
    public function index()
    {
        $this->loginView('Input your email and password','');
    }

    public function authorize()
    {
        if(!isset($_POST['email']) || !isset($_POST['password'])){
            $this->index();
            return;
        }

        if(strlen(trim($_POST['email']))===0){
            $this->loginView('You must enter your email','');
            return;
        }

        if(strlen(trim($_POST['password']))===0){
            $this->loginView('You must enter your password',$_POST['email']);
            return;
        }


    }

    public function loginView($message,$email)
    {
        $this->view->render('login',[
            'message' => $message,
            'email' => $email
        ]);
    }
}