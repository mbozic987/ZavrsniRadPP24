<?php

class Device
{
    //CRUD

    //R - Read
    public static function read()
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

            select a.device_id, b.firstname, b.lastname, b.company, 
            b.phonenum, b.email, a.manufacturer, a.model, a.serialnum,
            count (c.workorder_id) as workorder
            from device a 
            inner join client b on a.client = b.client_id 
            left join workorder c on a.device_id = c.device
            group by 
            a.device_id, b.firstname, b.lastname, b.company, 
            b.phonenum, b.email, a.manufacturer, a.model, a.serialnum
            order by 3, 2;

        ');
        $exp->execute();
        return $exp->fetchAll();
    }

    //RO - Read One
    public static function readOne($device_id)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

            select a.device_id, b.firstname, b.lastname, b.company, 
            b.phonenum, b.email, a.manufacturer, a.model, a.serialnum
            from device a inner join client b on 
            a.client = b.client_id
            where a.device_id=:device_id; 

        ');
        $exp->execute(['device_id'=>$device_id]);
        return $exp->fetch();
    }

    //C - Create
    public static function create($parameters)
    {
        $conn = DB::getInstance();
        $conn->beginTransaction();
        $exp = $conn->prepare('

            insert into client (firstname,lastname,company,phonenum,email)
            values (:firstname,:lastname,:company,:phonenum,:email);

        ');
        $exp->execute([
            'firstname'=>$parameters['firstname'],
            'lastname'=>$parameters['lastname'],
            'company'=>$parameters['company'],
            'phonenum'=>$parameters['phonenum'],
            'email'=>$parameters['email']
        ]);

        $lastId = $conn -> lastInsertId();

        $exp = $conn -> prepare(['
            insert into device (client, manufacturer, model, serialnum)
            values (:client, :manufacturer, :model, :serialnum);
        ']);
        $exp->execute([
            'client'=>$lastId,
            'manufacturer'=>$parameters['manufacturer'],
            'model'=>$parameters['model'],
            'serialnum'=>$parameters['serialnum']
        ]);

        $conn->commit();
    }
    

    //U - Update
    public static function update($parameters)
    {
        $conn = DB::getInstance();
        $conn->beginTransaction();
        $exp = $conn->prepare('

            select client from device where device_id=:device_id;

        ');
        $exp->execute([
            'device_id'=>$parameters['device_id']
        ]);

        $clientId = $exp->fetchColumn();

        $exp = $conn->prepare('

            update client set
            firstname=:firstname,
            lastname=:lastname,
            company=:company,
            phonenum=:phonenum,
            email=:email
            where client_id=:client_id

        ');
        $exp->execute([
            'client_id'=>$clientId,
            'firstname'=>$parameters['firstname'],
            'lastname'=>$parameters['lastname'],
            'company'=>$parameters['company'],
            'phonenum'=>$parameters['phonenum'],
            'email'=>$parameters['email']
        ]);

        $exp = $conn->prepare('
        
            update device set 
            manufacturer=:manufacturer,
            model=:model,
            serialnum=:serialnum
            where device_id=:device_id
        
        ');
        $exp->execute([
            'device_id'=>$parameters['device_id'],
            'manufacturer'=>$parameters['manufacturer'],
            'model'=>$parameters['model'],
            'serialnum'=>$parameters['serialnum']
        ]);

        $conn->commit();
    }

    //D - Delete
    public static function delete($device_id)
    {
        $conn = DB::getInstance();
        $conn->beginTransaction();
        $exp = $conn->prepare('

            select client from device where device_id=:device_id;

        ');
        $exp->execute(['
            device_id'=>$device_id
        ]);

        $client_id = $exp->fetchColumn();

        $exp = $conn->prepare('
        
            delete from device where device_id=:device_id;
        
        ');
        $exp->execute([
            'device_id'=>$device_id
        ]);

        $exp = $conn->prepare('
        
            delete from client where client_id=:client_id;
        
        ');
        $exp->execute([
            'client_id'=>$client_id
        ]);

        $conn->commit();
    }
}