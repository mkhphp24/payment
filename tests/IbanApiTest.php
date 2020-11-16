<?php
use App\Services\PaymentIbanService;
use PHPUnit\Framework\TestCase;
use App\Model\User;


class IbanApiTest  extends TestCase
{

    protected function setUp()
    {

    }

    public function testPaymentibanApi()
    {
        $paymentIbanService=new PaymentIbanService();

        $result=$paymentIbanService->sendrequest('{"customerId": 1,"iban": "DE8234","owner": "Max Mustermann"}');

        $this->assertEquals(200,$result['status']);

        try{
            $paymentIbanService->sendrequest('{"customerId": 1,"iban": "","owner": "Max Mustermann"}');
        }catch (\Exception $e) {
            $this->assertEquals('Bad Request', $e->errorMessage());
        }


    }

    /**
     * @throws \App\Exception\InvalidDbException
     */
    public function testInsertAndUpdateAndDeleteUser(){


        $user=new User();

        $paymentIbanService=new PaymentIbanService();
        $testData=[
            'firstname'=>'test-' ,
            'lastname'=>'test-' ,
            'telephone'=>'09666' ,
            'street'=>'1' ,
            'house_number'=>'1' ,
            'zip'=>'1' ,
            'city'=>'1' ,
            'account_owner'=>'test' ,
            'iban'=>'1111'
        ];

        $user_id=$paymentIbanService->doInsertUser($testData,'iban','10.10.10.10');
        $this->assertTrue( is_array($user->selectId($user_id) )  );

        $resut=$paymentIbanService->setPaymentDataId('4b2e1c9fd1cd7592aa2054171f9ed38fdb8662669ed6ba4eec88c322d150796f7e5728f3c086323e43535b1d51e7e569','200',$user_id);
        $this->assertEquals('ok', $resut);

        $paymentIbanService->doDeletUser($user_id);
        $this->assertFalse( is_array($user->selectId($user_id) )  );
    }




}
