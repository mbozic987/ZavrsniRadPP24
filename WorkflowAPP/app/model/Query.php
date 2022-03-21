<?php

class Query
{
    public static function check($query)
    {
        $conn = DB::getInstance();
        $exp = $conn->prepare('

            select a.query_id, b.status_name 
            from workorder a 
            inner join repair_status b on a.repair_status = b.repair_status_id
            where query_id=:query_id;

        ');
        $exp->execute(['query_id' => $query]);
        $check = $exp->fetch();
        if($check==null){
            return null;
        }else{
            $check = (array)$check;
            return $check['status_name'];
        }   
    }
}