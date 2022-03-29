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
            left join employee d on a.employee_repairman = d.employee_id
            inner join repair_status e on a.repair_status = e.repair_status_id
            where concat(b.manufacturer, \' \', b.model, \' \', c.firstname, \' \',
            c.lastname, \' \', ifnull(c.company, \' \'), d.firstname, \' \',
            d.lastname, \' \', e.status_name) like :cond;

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
        
            select a.workorder_id, d.firstname, 
        
        ');
    }
}