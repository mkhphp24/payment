<?php

use Klein\Klein;
use Klein\Request;
use Klein\Response;
use App\Controller\HomeController;

require_once __DIR__ . '/vendor/autoload.php';
ini_set('display_errors', 1);
date_default_timezone_set("Europe/Berlin");
$klein = new Klein();

$klein->respond('GET', '/', function (Request $request,Response $response) {
    $stepCookies = $request->cookies()->get('step');
    if($stepCookies < 4) {
        $response->redirect('/step' . $stepCookies);
    } else { $response->redirect('/error' ); }

});


$klein->respond(['GET','POST'], '/step1', function (Request $request,Response $response) {
    $homeController=new HomeController();
    return  $homeController->step1($request,$response);
});

$klein->respond(['GET','POST'], '/step2', function (Request $request,Response $response) {
    $homeController=new HomeController();
    return  $homeController->step2($request,$response);
});

$klein->respond(['GET','POST'], '/step3', function (Request $request,Response $response) {
    $homeController=new HomeController();
    return  $homeController->step3($request,$response);
});

$klein->respond(['GET'], '/success', function (Request $request,Response $response) {
    $homeController=new HomeController();
    return  $homeController->success($request,$response);
});

$klein->respond(['GET'], '/error', function (Request $request,Response $response) {
    $homeController=new HomeController();
    return  $homeController->error($request,$response,'you already apply');
});


$klein->respond(['GET'], '/error-rsp', function (Request $request,Response $response) {
    $homeController=new HomeController();
    return  $homeController->error($request,$response,'api Error');
});


$klein->respond('GET', '/new', function (Request $request,Response $response) {
    setcookie("payment",false);
    setcookie("step",false);
    $response->redirect('/step1');

});



$klein->dispatch();

?>

