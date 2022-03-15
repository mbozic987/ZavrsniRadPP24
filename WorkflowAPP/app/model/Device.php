<?php

class Device
{
    //CRUD

    //R - Read
    public static function read()
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

            select a.device_id, b.firstname, b.lastname, b.company, b.phonenum, b.email,
            

        ');
        $exp->execute();
        return $exp->fetchAll();
    }

    //RO - Read One
    public static function readOne($device_id)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        select * from device where client_id=:client_id;

        ');
        $exp->execute(['device_id'=>$device_id]);
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