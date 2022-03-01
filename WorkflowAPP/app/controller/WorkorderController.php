<?php

class WorkorderController extends AuthorizationController
{
    private$viewDir = 'private' . DIRECTORY_SEPARATOR . 
                        'workorder' . DIRECTORY_SEPARATOR;

    public function index()
    {
        $this->view->render($this->viewDir . 'index');
    }
}