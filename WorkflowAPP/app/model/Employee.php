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
        a.userpassword, a.employee_role
        order by 7 asc, 3;

        ');
        $exp->execute();
        return $exp->fetchAll();
    }

    //RO - Read One
    public static function readOne($employee_id)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        select * from employee where employee_id=:employee_id;

        ');
        $exp->execute(['employee_id'=>$employee_id]);
        return $exp->fetch();
    }

    //C - Create
    public static function create($parameters)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        insert into employee (firstname,lastname,phonenum,email,userpassword,employee_role)
        values (:firstname,:lastname,:phonenum,:email,:userpassword,:employee_role);

        ');
        $exp->execute($parameters);
    }
    

    //U - Update
    public static function update($parameters)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        update employee set 
            firstname=:firstname,
            lastname=:lastname,
            phonenum=:phonenum,
            email=:email,
            userpassword=:userpassword,
            employee_role=:employee_role
        where employee_id=:employee_id;

        ');
        $exp->execute($parameters);
    }

    //D - Delete
    public static function delete($employee_id)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        delete from employee where employee_id=:employee_id;

        ');
        $exp->execute(['employee_id'=>$employee_id]);
    }
}