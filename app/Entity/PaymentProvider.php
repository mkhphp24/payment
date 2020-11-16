<?php

namespace App\Entity;
interface PaymentProvider
{
    /**
     * @return Payment[]
     */
    public function getPaymentDataId() : ObjPayment;
}
