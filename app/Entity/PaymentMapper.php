<?php

namespace App\Entity;

use App\Entity\ObjPayment;
use InvalidArgumentException;

class PaymentMapper
{
    private  $adapter;

    public function __construct(StorageAdapter $storage)
    {
        $this->adapter = $storage;
    }

    /**
     * @param int $id
     * @return ObjProduct
     */
    public function findById(int $id): ObjPayment

    {
        $result = $this->adapter->find($id);

        if ($result === null) {
            throw new InvalidArgumentException("Payment #$id not found");
        }

        return $this->mapRowToPayment($result);
    }


    /**
     * @param array $row
     * @return ObjProduct
     */
    private function mapRowToPayment(array $row): ObjPayment
    {
        return ObjPayment::fromState($row);
    }
}
