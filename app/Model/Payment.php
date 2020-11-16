<?php

namespace App\Model;

class Payment extends Db
{
    /**
     * @param int $user_id
     * @param int $address_id
     * @param int $getway_id
     * @param string $type
     * @param string $ip
     * @return string
     */
    public function insert(int $user_id,int $address_id,int $getway_id,string $type,string $ip)
    {
        $sql="INSERT INTO 
        payment (user_id,address_id,getway_id,type,date,ip) 
        VALUES ('".$user_id."'
        ,'".$address_id."'
        ,'".$getway_id."'
        ,'".$type."'
        ,'". date("Y/m/d h:i:sa")."'
        ,'".$ip."'
        )";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    /**
     * @param int $user_id
     * @return mixed
     */
    public function findid(int $user_id){

    $sql="select * from payment 
    inner join user on payment.user_id=user.id 
    inner join address on payment.address_id=address.id 
    inner join iban on payment.getway_id=iban.id
    where  payment.user_id=$user_id
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * @param $id
     */
    public function deleteId($id){
        $sql="Delete from payment where user_id='$id' ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }


}
