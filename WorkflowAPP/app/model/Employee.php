<?php

class Employee 
{
    //CRUD

    //R - Read
    public static function read()
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        select a.employee_id, a.firstname, a.lastname, a.phonenum, a.email, 
        a.userpassword, a.employee_role, count(b.workorder_id) as workorder
        from employee a left join workorder b 
        on a.employee_id = b.employee_repairman or a.employee_id = b.employee_frontdesk 
        group by a.employee_id, a.firstname, a.lastname, a.phonenum, a.email,
        a.userpassword, a.employee_role;

        ');
        $exp->execute();
        return $exp->fetchAll();
    }

    //C - Create

    

    //U - Update

    //D - Delete
}