<?php

namespace App\Entity;
class GetPayment
{
    private $payment;

    /**
     * UseRepository constructor.
     */
    public function __construct(PaymentProvider $payment)
    {
        $this->payment=$payment;
    }

    /**
     * @param   string  $string
     *
     * @return array
     */
    public function exceute(): ObjPayment
    {
        return $this->payment->getPaymentDataId();
    }

}
