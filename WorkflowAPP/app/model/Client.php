<?php

class Client
{
    //CRUD

    public static function clientTotal($cond)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('
        
            select count(a.client_id)
            from client a
            left join device b
            on a.client_id = b.client
            where concat(a.firstname, \' \', a.lastname, \' \', ifnull(a.company,\'\')) like :cond;

        ');
        $cond = '%' . $cond . '%';
        $exp->bindParam('cond',$cond);
        $exp->execute();
        return $exp->fetchColumn();
    }

    //R - Read
    public static function read($page, $cond)
    {
        $rpp = App::config('rpp');
        $from = $page * $rpp - $rpp;

        $conn = DB::getInstance();
        $exp = $conn->prepare('

            select a.client_id, a.firstname, a.lastname, a.company, a.phonenum, a.email, 
            count(b.device_id) as device
            from client a left join device b 
            on a.client_id = b.client 
            where concat(a.firstname, \' \', a.lastname, \' \', ifnull(a.company,\'\')) like :cond
            group by 
            a.client_id, a.firstname, a.lastname, a.company, a.phonenum, a.email
            order by 3 asc, 4
            limit :from, :rpp;

        ');
        $cond = '%' . $cond . '%';
        $exp->bindValue('from',$from,PDO::PARAM_INT);
        $exp->bindValue('rpp',$rpp,PDO::PARAM_INT);
        $exp->bindParam('cond',$cond);
        $exp->execute();
        return $exp->fetchAll();
    }

    //RO - Read One
    public static function readOne($client_id)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        select * from client where client_id=:client_id;

        ');
        $exp->execute(['client_id'=>$client_id]);
        return $exp->fetch();
    }

    //C - Create
    public static function create($parameters)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        insert into client (firstname,lastname,company,phonenum,email)
        values (:firstname,:lastname,:company,:phonenum,:email);

        ');
        $exp->execute($parameters);
    }
    

    //U - Update
    public static function update($parameters)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        update client set 
            firstname=:firstname,
            lastname=:lastname,
            company=:company,
            phonenum=:phonenum,
            email=:email
        where client_id=:client_id;

        ');
        $exp->execute($parameters);
    }

    //D - Delete
    public static function delete($client_id)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        delete from client where client_id=:client_id;

        ');
        $exp->execute(['client_id'=>$client_id]);
    }
}