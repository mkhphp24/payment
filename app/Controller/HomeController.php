<?php

namespace App\Controller;

use App\Entity\GetPayment;
use App\Entity\IbanProvider;
use App\Entity\ObjPayment;
use App\Services\Encryption;
use App\Services\PaymentIbanService;
use App\validation\ValidationData;
use Klein\Request;
use Klein\Response;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeController
{


    public function index(Request $request)
    {
        $loader = new FilesystemLoader('templates');
        $twig = new Environment($loader, []);
        return $twig->render('home.html.twig', ['titile' => 'home', 'errorMesage' => '']);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function step1(Request $request, Response $response)
    {


        if (is_null($request->cookies()->get('step'))) $response->cookie('step', '1');
        $this->checkStep($request, $response, '1');


        $dataCustomer = $this->setDataForm($request->paramsPost()->all(), $request, $response);
        $loader = new FilesystemLoader('templates');
        $twig = new Environment($loader, []);
        $error_message = array();

        if ($request->method() === 'POST') {

            $datavalidate = new ValidationData($request->paramsPost()->all());
            $validationData = $datavalidate->validateStep1();
            $error_message = $datavalidate->setTwigError($validationData['Error']);

            if (empty($validationData['Error'])) {

                $this->setNextStep($request, $response, '2', $dataCustomer);
                $response->redirect('/step2');
            }
        }

        return $twig->render('step1.html.twig', ['titile' => 'step1', 'dataForm' => $dataCustomer, 'errorMesage' => $error_message, 'step' => '1']);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function step2(Request $request, Response $response)
    {
        $this->checkStep($request, $response, '2');
        $dataCustomer = $this->setDataForm($request->paramsPost()->all(), $request, $response);
        $loader = new FilesystemLoader('templates');
        $twig = new Environment($loader, []);
        $error_message = array();

        if ($request->method() === 'POST') {

            $datavalidate = new ValidationData($request->paramsPost()->all());
            $validationData = $datavalidate->validateStep2();
            $error_message = $datavalidate->setTwigError($validationData['Error']);
            if (empty($validationData['Error'])) {
                $this->setNextStep($request, $response, '3', $dataCustomer);
                $response->redirect('/step3');
            }
        }

        return $twig->render('step2.html.twig', ['titile' => 'step2', 'dataForm' => $dataCustomer, 'errorMesage' => $error_message, 'step' => '2']);

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function step3(Request $request, Response $response)
    {

        $this->checkStep($request, $response, '3');
        $dataCustomer = $this->setDataForm($request->paramsPost()->all(), $request, $response);
        $loader = new FilesystemLoader('templates');
        $twig = new Environment($loader, []);
        $error_message = array();

        if ($request->method() === 'POST') {

            $datavalidate = new ValidationData($request->paramsPost()->all());
            $validationData = $datavalidate->validateStep3();
            $error_message = $datavalidate->setTwigError($validationData['Error']);

            if (empty($validationData['Error'])) {

                $objPayment = $this->insertRequest($dataCustomer,'iban',$request->ip() );
                $paymentIbanService = new PaymentIbanService();
                $paymentDataId = $paymentIbanService->sendrequest('{"customerId": ' . $objPayment->getId() . ',"iban": "' . $objPayment->getIban() . '","owner": "' . $objPayment->getAccountOwner() . '"}');

                if ($paymentDataId['status'] === 200) {
                    $paymentIbanService->setPaymentDataId($paymentDataId['body'],$paymentDataId['status'], $objPayment->getGetwayId());
                    $dataCustomer['id'] = $objPayment->getId();
                    $dataCustomer['payment_data_id'] = $paymentDataId['body'];
                    $this->setNextStep($request, $response, '4', $dataCustomer);
                    $response->redirect('/success');
                } else
                    {
                    $paymentIbanService->setPaymentDataId('',$paymentDataId['status'], $objPayment->getGetwayId());
                    $this->setNextStep($request, $response, '5', $dataCustomer);
                    $response->redirect('/error-rsp');
                }


            }
        }

        return $twig->render('step3.html.twig', ['titile' => 'step3', 'dataForm' => $dataCustomer, 'errorMesage' => $error_message, 'step' => '3']);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function success(Request $request, Response $response)
    {

        $encryption = new Encryption();
        $PaymentCookies = json_decode($encryption->decrypt($request->cookies()->get('payment')), true);
        $loader = new FilesystemLoader('templates');
        $twig = new Environment($loader, []);

        return $twig->render('success.html.twig', ['titile' => 'success', 'message' => $PaymentCookies['payment_data_id']]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function error(Request $request, Response $response,$message)
    {
        $loader = new FilesystemLoader('templates');
        $twig = new Environment($loader, []);

        return $twig->render('error.html.twig', ['titile' => 'error', 'message' => $message]);
    }


    /**
     * @param array $PostRequest
     * @param Request $request
     * @param Response $response
     * @return array
     * @throws \Exception
     */
    private function setDataForm(array $PostRequest, Request $request, Response $response):array
    {
        $encryption = new Encryption();
        $PaymentCookies = json_decode($encryption->decrypt($request->cookies()->get('payment')), true);
        $PaymentArray = [
            'firstname' => (isset($PostRequest['firstname'])) ? $PostRequest['firstname'] : $PaymentCookies['firstname'],
            'lastname' => (isset($PostRequest['lastname'])) ? $PostRequest['lastname'] : $PaymentCookies['lastname'],
            'telephone' => (isset($PostRequest['telephone'])) ? $PostRequest['telephone'] : $PaymentCookies['telephone'],
            'address' => (isset($PostRequest['address'])) ? $PostRequest['address'] : $PaymentCookies['address'],
            'street' => (isset($PostRequest['street'])) ? $PostRequest['street'] : $PaymentCookies['street'],
            'house_number' => (isset($PostRequest['house_number'])) ? $PostRequest['house_number'] : $PaymentCookies['house_number'],
            'zip' => (isset($PostRequest['zip'])) ? $PostRequest['zip'] : $PaymentCookies['zip'],
            'city' => (isset($PostRequest['city'])) ? $PostRequest['city'] : $PaymentCookies['city'],
            'account_owner' => (isset($PostRequest['account_owner'])) ? $PostRequest['account_owner'] : $PaymentCookies['account_owner'],
            'iban' => (isset($PostRequest['iban'])) ? $PostRequest['iban'] : $PaymentCookies['iban']];

        return $PaymentArray;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param string $stepPage
     * @return string|mixed|string
     */
    private function checkStep(Request $request, Response $response, string $stepPage)
    {

        $stepCookies = $request->cookies()->get('step');
        if (is_null($stepCookies)) {
            $response->redirect('/step1');
            return '1';
        }
        if ($stepCookies < $stepPage ) {
            $response->redirect('/step' . $stepCookies);
            return $stepCookies;
        }
        if ($stepCookies === '4') {
            $response->redirect('/error');
            return '4';
        }

        return $stepPage;

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param string $stepPage
     * @param array $dataForm
     */
    private function setNextStep(Request $request, Response $response, string $stepPage, array $dataForm)
    {
        $encryption = new Encryption();
        $response->cookie('payment', $encryption->encrypt(json_encode($dataForm, true)));
        $stepCookies = $request->cookies()->get('step');

        if ($stepCookies < $stepPage) $response->cookie('step', $stepPage);

    }

    /**
     * @param array $dataCustomer
     * @param string $type
     * @param string $ip
     * @return ObjPayment
     */
    private function insertRequest( array $dataCustomer, string $type, string $ip):ObjPayment
    {
        $paymentIbanService = new PaymentIbanService();
        $user_id = $paymentIbanService->doInsertUser( $dataCustomer, $type , $ip );
        $payment = new GetPayment( new IbanProvider( $user_id) );

        return $payment->exceute();

    }


}
