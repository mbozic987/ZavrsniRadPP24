<?php

class Workorder
{
    public static function workorderTotal($cond)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('
        
            select count(a.workorder_id)
            from workorder a
            inner join device b on a.device = b.device_id
            inner join client c on b.client = c.client_id
            inner join employee d on a.employee_repairman = d.employee_id
            inner join employee e on a.employee_frontdesk = e.employee_id
            inner join repair_status f on a.repair_status = f.repair_status_id
            where concat(b.manufacturer, \' \', b.model, \' \', c.firstname, \' \',
            c.lastname, \' \', ifnull(c.company, \' \'), d.firstname, \' \',
            d.lastname, \' \', f.status_name) like :cond;

        ');
        $cond = '%' . $cond . '%';
        $exp->bindParam('cond',$cond);
        $exp->execute();
        return $exp->fetchColumn();
    }

    public static function read($page, $cond)
    {

        $rpp = App::config('rpp');
        $from = $page * $rpp - $rpp;

        $conn = DB::getInstance();
        $exp = $conn->prepare('
        
            select a.workorder_id, b.device_id, b.manufacturer, b.model, b.serialnum,
            c.client_id, c.firstname, c.lastname, c.company, c.phonenum, c.email,
            d.employee_id as repairman_id, d.firstname as repairman_firstname,
            e.employee_id as frontdesk_id, e.firstname as frontdesk_firstname,
            a.malfunction, a.receive_date, f.repair_status_id, f.status_name, a.work_done,
            a.query_id, a.repair_date
            from workorder a
            inner join device b on a.device = b.device_id
            inner join client c on b.client = c.client_id
            inner join employee d on a.employee_repairman = d.employee_id
            inner join employee e on a.employee_frontdesk = e.employee_id
            inner join repair_status f on a.repair_status = f.repair_status_id
            where concat(b.manufacturer, \' \', b.model, \' \', c.firstname, \' \',
            c.lastname, \' \', ifnull(c.company, \' \'), d.firstname, \' \',
            d.lastname, \' \', f.status_name) like :cond
            group by
            a.workorder_id, b.device_id, b.manufacturer, b.model, b.serialnum,
            c.client_id, c.firstname, c.lastname, c.company, c.phonenum, c.email,
            d.employee_id, d.firstname,e.employee_id, e.firstname,
            a.malfunction, a.receive_date, f.repair_status_id, f.status_name, a.work_done,
            a.query_id, a.repair_date
            order by 1
            limit :from, :rpp;

        
        ');
        $cond = '%' . $cond . '%';
        $exp->bindValue('from',$from,PDO::PARAM_INT);
        $exp->bindValue('rpp',$rpp,PDO::PARAM_INT);
        $exp->bindParam('cond',$cond);
        $exp->execute();
        return $exp->fetchAll();
    }

    public static function readOne($workorder_id)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('
        
            select a.workorder_id, b.device_id, b.manufacturer, b.model, b.serialnum,
            c.client_id, c.firstname, c.lastname, c.company, c.phonenum, c.email,
            d.employee_id as repairman_id, d.firstname as repairman_firstname,
            e.employee_id as frontdesk_id, e.firstname as frontdesk_firstname,
            a.malfunction, a.receive_date, f.repair_status_id, f.status_name, a.work_done,
            a.query_id, a.repair_date
            from workorder a
            inner join device b on a.device = b.device_id
            inner join client c on b.client = c.client_id
            inner join employee d on a.employee_repairman = d.employee_id
            inner join employee e on a.employee_frontdesk = e.employee_id
            inner join repair_status f on a.repair_status = f.repair_status_id
            where workorder_id=:workorder_id;
        
        ');
        $exp->execute(['workorder_id'=>$workorder_id]);
        return $exp->fetch();
    }

    public static function create($parameters)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('
        
            insert into workorder (employee_frontdesk, employee_repairman, device, 
            malfunction, repair_status, query_id)
            values (:employee_frontdesk, :employee_repairman, :device,
            :malfunction, :repair_status, :query_id);
        
        ');
        $exp->execute([
            'employee_frontdesk'=>$parameters['frontdesk_id'],
            'employee_repairman'=>$parameters['frontdesk_id'],
            'device'=>$parameters['device'],
            'malfunction'=>$parameters['malfunction'],
            'repair_status'=>$parameters['repair_status'],
            'query_id'=>$parameters['query_id']
        ]);

    }

    public static function update($parameters)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('
        
            update workorder set 
            employee_frontdesk=:employee_frontdesk,
            device=:device,
            malfunction=:malfunction
        
        ');
        $exp->execute([
            'employee_frontdesk'=>$parameters['employee_frontdesk'],
            'device'=>$parameters['device'],
            'malfunction'=>$parameters['malfunction']
        ]);
    }

    public static function delete($workorder_id)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('
        
            delete from workorder where workorder_id=:workorder_id;
        
        ');
        $exp->execute(['workorder_id'=>$workorder_id]);
    }
}