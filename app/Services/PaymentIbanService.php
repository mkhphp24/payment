<?php

namespace App\Services;

use App\Model\Address;
use App\Model\Iban;
use App\Model\Payment;
use App\Model\User;
use GuzzleHttp\Client;
use App\Exception\InvalidApiException;
use App\Exception\InvalidDbException;

class PaymentIbanService
{
    private $client;
    const PATH = 'https://37f32cl571.execute-api.eu-central-1.amazonaws.com/default/wunderfleet-recruiting-backend-dev-save-payment-data';

    /**
     * PaymentApiService constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param $jsonRequest
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendrequest($jsonRequest)
    {
        try {
            $responseguzzel = $this->client->request('POST', self::PATH, ['body' => $jsonRequest]);
            $result = json_decode($responseguzzel->getBody(), true);
            return ['body' => $result['paymentDataId'], 'status' => $responseguzzel->getStatusCode()];
        } catch (\Exception $e) {

            throw new InvalidApiException();

        }
    }

    /**
     * @param array $paymentCookie
     * @param string $type
     * @param string $ip
     * @return string
     */
    public function doInsertUser( array $paymentCookie, string $type, $ip )
    {
        try {
        $user = new User();
        $address = new Address();
        $iban = new Iban();
        $Payment = new Payment();
        $user_id = $user->insert($paymentCookie);
        $adrress_id = $address->insert($paymentCookie, $user_id);
        $iban_id = $iban->insert($paymentCookie, $user_id);
        $Payment_id = $Payment->insert($user_id, $adrress_id, $iban_id, $type, $ip);
        return $user_id;
        } catch (\Exception $e) {

            throw new InvalidDbException();
        }

    }

    /**
     * @param string $paymentDataId
     * @param int $user_id
     * @return string
     * @throws InvalidDbException
     */
    public function setPaymentDataId(string $paymentDataId,int $status ,int $user_id)
    {
        try {
        $iban = new Iban();
        $iban->updatePaymentDataId($paymentDataId, $status ,$user_id);
        return 'ok';
        } catch (\Exception $e) {

            throw new InvalidDbException();
        }
    }

    /**
     * @param $id
     * @throws InvalidDbException
     */
    public function doDeletUser($id){

        try {
        $user = new User();
        $address = new Address();
        $iban = new Iban();
        $Payment = new Payment();
        $user->deleteId($id);
        $address->deleteId($id);
        $iban->deleteId($id);
        $Payment->deleteId($id);

        } catch (\Exception $e) {

            throw new InvalidDbException();
        }
    }

}

