<?php

namespace App\Model;

class Address extends Db
{
    /**
     * @param array $paymentCookie
     * @param int $user_id
     * @return string
     */
    public function insert(array $paymentCookie,int $user_id)
    {
        $sql="INSERT INTO 
        address (user_id,street,house_number,zip,city) 
        VALUES (
         '".$user_id."'
        ,'".$paymentCookie['street']."'
        ,'".$paymentCookie['house_number']."'
        ,'".$paymentCookie['zip']."'
        ,'".$paymentCookie['city']."'
        )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    /**
     * @param $id
     */
    public function deleteId($id){
        $sql="Delete from address where user_id='$id' ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

}
