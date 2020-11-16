<?php

namespace App\Entity;
use App\Model\Payment;

class StorageAdapter
{

    /**
     * @param int $id
     *
     * @return array|null
     */
    public function find(int $user_id)
    {

        $PaymentMModel=new Payment();
        if (isset($user_id)) {
            return $PaymentMModel->findid($user_id);
        }

        return null;
    }

}
