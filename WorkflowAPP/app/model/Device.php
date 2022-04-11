<?php

class Device
{
    //CRUD

    public static function deviceTotal($cond)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('
        
            select count(a.device_id)
            from device a 
            inner join client b on a.client = b.client_id 
            left join workorder c on a.device_id = c.device
            where concat(b.firstname, \' \', b.lastname, \' \', ifnull(b.company,\'\'),
            a.manufacturer, \' \', a.model) like :cond;

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

            select a.device_id, b.firstname, b.lastname, b.company, 
            b.phonenum, b.email, a.manufacturer, a.model, a.serialnum,
            count(c.workorder_id) as workorder
            from device a 
            inner join client b on a.client = b.client_id 
            left join workorder c on a.device_id = c.device
            where concat(b.firstname, \' \', b.lastname, \' \', ifnull(b.company,\'\'),
            a.manufacturer, \' \', a.model) like :cond
            group by 
            a.device_id, b.firstname, b.lastname, b.company, 
            b.phonenum, b.email, a.manufacturer, a.model, a.serialnum
            order by 3, 2
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
    public static function readOne($device_id)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        select a.device_id, b.client_id as client, b.firstname, b.lastname, b.company, 
        a.manufacturer, a.model, a.serialnum
        from device a 
        inner join client b on a.client = b.client_id
        where device_id=:device_id

        ');
        $exp->execute(['device_id'=>$device_id]);
        return $exp->fetch();
    }

    //C - Create
    public static function create($parameters)
    {
        $conn = DB::getInstance();
        $exp = $conn -> prepare('

            insert into device (client, manufacturer, model, serialnum)
            values (:client, :manufacturer, :model, :serialnum);
        ');
        $exp->execute([
            'client'=>$parameters['client'],
            'manufacturer'=>$parameters['manufacturer'],
            'model'=>$parameters['model'],
            'serialnum'=>$parameters['serialnum']
        ]);
    }
    

    //U - Update
    public static function update($parameters)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('
        
            update device set 
            client=:client,
            manufacturer=:manufacturer,
            model=:model,
            serialnum=:serialnum
            where device_id=:device_id
        
        ');
        $exp->execute([
            'device_id'=>$parameters['device_id'],
            'client'=>$parameters['client'],
            'manufacturer'=>$parameters['manufacturer'],
            'model'=>$parameters['model'],
            'serialnum'=>$parameters['serialnum']
        ]);
    }

    //D - Delete
    public static function delete($device_id)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('
        
            delete from device where device_id=:device_id;
        
        ');
        $exp->execute([
            'device_id'=>$device_id
        ]);
    }
}