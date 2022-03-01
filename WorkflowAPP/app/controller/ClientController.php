<?php

class ClientController extends AuthorizationController
{
    private$viewDir = 'private' . DIRECTORY_SEPARATOR . 
                        'client' . DIRECTORY_SEPARATOR;

    public function index()
    {
        $this->view->render($this->viewDir . 'index');
    }
}