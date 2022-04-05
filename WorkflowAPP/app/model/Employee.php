<?php

class Employee
{
    //CRUD

    public static function employeeTotal($cond)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

        select count(a.employee_id)
        from employee a 
        left join workorder b 
        on a.employee_id = b.employee_repairman or a.employee_id = b.employee_frontdesk 
        where concat(a.firstname, \' \', a.lastname, \' \', a.employee_role) like :cond

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

        select a.employee_id, a.firstname, a.lastname, a.phonenum, a.email, 
        a.userpassword, a.employee_role, count(b.workorder_id) as workorder
        from employee a left join workorder b 
        on a.employee_id = b.employee_repairman or a.employee_id = b.employee_frontdesk 
        where concat(a.firstname, \' \', a.lastname, \' \', a.employee_role) like :cond
        group by 
        a.employee_id, a.firstname, a.lastname, a.phonenum,
        a.email, a.userpassword, a.employee_role
        order by 7 asc, 3
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
        if($_POST['userpassword']===0){
            $exp->execute([
                'firstname'=>$parameters['firstname'],
                'lastname'=>$parameters['lastname'],
                'phonenum'=>$parameters['phonenum'],
                'email'=>$parameters['email'],
                'employee_role'=>$parameters['employee_role']
            ]);
        }
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