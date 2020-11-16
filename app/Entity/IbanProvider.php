<?php

namespace App\Entity;
use App\Entity\PaymentMapper;
use App\Entity\StorageAdapter;

class IbanProvider implements PaymentProvider
{

    private $user_id;

    /**
     * IbanProvider constructor.
     * @param $user_id
     */
    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }


    /**
     * @return Payment[]
     */
    public function getPaymentDataId(): ObjPayment
    {
        try {
            return $this->IbanMapper(  $this->user_id ) ;
        } catch (\Exception $e) {
            die( $e->getMessage() );

        }
    }

    /**
     * @param array $csvLines
     * @return array Product::class
     */
    private function IbanMapper(int $user_id) :ObjPayment {


        $storage = new StorageAdapter();
        $mapper = new PaymentMapper($storage);
        return $mapper->findById($user_id);

    }

}
