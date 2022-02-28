<?php

class Operator
{
    public static function authorize($email,$password)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('
            select * from employee where email=:email;
        ');
        $exp->execute(['email' => $email]);
        $operator = $exp->fetch();
        if($operator==null){
            return null;
        }
        if(!password_verify($password,$operator->userpassword)){
            return null;
        }
        unset($operator->userpassword);
        return $operator;
    }
}