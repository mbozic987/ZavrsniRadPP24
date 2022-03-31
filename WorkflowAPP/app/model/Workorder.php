<?php

class Workorder
{
    public static function workorderTotal($cond)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('
        
            select a.workorder_id, b.device_id, b.manufacturer, b.model, b.serialnum,
            c.client_id, c.firstname, c.lastname, c.company, c.phonenum, c.email,
            d.employee_id as repairman_id, d.firstname as repairman_firstname,
            e.employee_id as frontdesk_id, e.firstname as frontdesk_firstname,
            a.malfunction, a.receive_date, f.status_name, a.work_done,
            a.query_id, a.repair_date
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
            a.malfunction, a.receive_date, f.status_name, a.work_done,
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
            d.employee_id, d.firstname, e.employee_id, e.firstname,
            a.malfunction, a.receive_date, f.status_name, a.work_done,
            a.query_id, a.repair_date
            order by 21, 19
            limit :from, :rpp;

        
        ');
        $cond = '%' . $cond . '%';
        $exp->bindValue('from',$from,PDO::PARAM_INT);
        $exp->bindValue('rpp',$rpp,PDO::PARAM_INT);
        $exp->bindParam('cond',$cond);
        $exp->execute();
        return $exp->fetchAll();
    }
}