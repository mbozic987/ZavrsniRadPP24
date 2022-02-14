<?php

class View
{
    private $template;

    public function __construct($template='template')
    {
        $this->template=$template;
    }

    public function render($phtmlPage,$parameter=[])
    {
        ob_start();
        extract($parameter);
        include_once BP_APP . 'view' . DIRECTORY_SEPARATOR . $phtmlPage . '.phtml';
        $content = ob_get_clean();
        include_once BP_APP . 'view' . DIRECTORY_SEPARATOR . $this->template . '.phtml';
    }
}