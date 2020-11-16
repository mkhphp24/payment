<?php

namespace App\Model;

class User extends Db
{
    /**
     * @param array $paymentCookie
     * @return string
     */
    public function insert(array $paymentCookie)
    {
        $sql="INSERT INTO 
        user (firstname,lastname,telephone) 
        VALUES ('".$paymentCookie['firstname']."'
        ,'".$paymentCookie['lastname']."'
        ,'".$paymentCookie['telephone']."'
        )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function selectId(int $id){
        $sql="select  * from user where id='$id' ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * @param $id
     */
    public function deleteId($id){
        $sql="Delete from user where id='$id' ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

}
