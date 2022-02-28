<?php

class TestController extends Controller
{
    public function testdb()
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('select * from client');
        $exp->execute();
        echo '<pre>';
        print_r($exp->fetchAll());
        echo '</pre>';
    }
}