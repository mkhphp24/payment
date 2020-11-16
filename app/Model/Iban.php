<?php

namespace App\Model;

class Iban extends Db
{
    /**
     * @param array $paymentCookie
     * @param int $user_id
     * @return string
     */
    public function insert(array $paymentCookie,int $user_id)
    {
        $sql="INSERT INTO 
        iban (user_id,account_owner,iban) 
        VALUES ('".$user_id."'
        ,'".$paymentCookie['account_owner']."'
        ,'".$paymentCookie['iban']."'
        )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    /**
     * @param string $paymentDataId
     * @param int $status
     * @param int $getway_id
     */
    public function updatePaymentDataId(string $paymentDataId,int $status ,int $getway_id){

        $sql="update  iban set  payment_data_id='".$paymentDataId."' ,status_response='".$status."' where id=$getway_id ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    /**
     * @param $id
     */
    public function deleteId($id){
        $sql="Delete from iban where user_id='$id' ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

}
