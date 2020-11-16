<?php

namespace App\Entity;


class ObjPayment implements AddressInterface,PersonalInterface
{

    protected $id;
    protected $firstname;
    protected $lastname;
    protected $telephone;
    protected $address;
    protected $street;
    protected $house_number;
    protected $zip;
    protected $city;
    protected $iban;
    protected $account_owner;
    protected $paymentDataId;
    protected $userId;
    protected $addressId;
    protected $getwayId;


    public function __construct(array $payment)
    {

        $this->id = $payment['id'];
        $this->firstname = $payment['firstname'];
        $this->lastname = $payment['lastname'];
        $this->telephone = $payment['telephone'];
        $this->street = $payment['street'];
        $this->house_number = $payment['house_number'];
        $this->zip = $payment['zip'];
        $this->city = $payment['city'];
        $this->iban = $payment['iban'];
        $this->account_owner = $payment['account_owner'];
        $this->paymentDataId=   $payment['payment_data_id'];
        $this->userId=   $payment['user_id'];
        $this->addressId=   $payment['address_id'];
        $this->getwayId=   $payment['getway_id'];



    }

    public static function fromState(array $state): self
    {

        return new self([
                'id'=> $state['id'],
                'firstname'=>$state['firstname'] ,
                'lastname'=>$state['lastname'] ,
                'telephone'=>$state['telephone'] ,
                'street'=>$state['street'] ,
                'house_number'=>$state['house_number'] ,
                'zip'=>$state['zip'] ,
                'city'=>$state['city'] ,
                'account_owner'=>$state['account_owner'] ,
                'iban'=>$state['iban'] ,
                'payment_data_id'=>$state['payment_data_id'],
                'user_id'=>$state['user_id'],
                'address_id'=>$state['address_id'],
                'getway_id'=>$state['getway_id']
            ]
        );
    }


    public function getId(): string
    {
       return $this->id;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getHouseNumber(): string
    {
        return $this->house_number;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function getCity(): int
    {
        return $this->city;
    }

    public function getIban():string
    {
        return $this->iban;
    }

    public function getAccountOwner():string
    {
        return $this->account_owner;
    }

    public function getPaymentDataId():string
    {
        return $this->paymentDataId;
    }

    public function getUserId():int
    {
        return $this->userId;
    }

    public function getAddressId():int
    {
        return $this->addressId;
    }

    public function getGetwayId():int
    {
        return $this->getwayId;
    }

}
