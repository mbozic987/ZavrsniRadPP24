<?php

if($_SERVER['SERVER_ADDR']==='127.0.0.1'){
    $url = 'http://workflow.xyz/';
    $dev = true;
    $db = [
        'server' => 'localhost',
        'db' => 'workflow',
        'user' => 'edunova',
        'password' => 'edunova'
    ];
}else{
    $url = 'https://polaznik23.edunova.hr/';
    $dev = false;
    $db = [
        'server' => 'localhost',
        'db' => 'harpije_workflow',
        'user' => 'harpije_polaznik23',
        'password' => 'z[{gZJ!#i-]s'
    ];
}

return [
    'dev' => $dev,
    'url' => $url,
    'rpp' => 20,
    'titleApp' => 'Workflow',
    'db' => $db
];