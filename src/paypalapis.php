<?php
use Slim\Http\Request;
use Slim\Http\Response;

$app->post('/api/orders', function (Request $request, Response $response) {
    $paypalService = new PayPalServices();
    return $paypalService->createOrder($response);
});

$app->post('/api/orders/{orderID}/capture', function (Request $request, Response $response, $args) {
    $paypalService = new PayPalServices();
    $orderID = $args['orderID'];
    return $paypalService->captureOrder($response, $orderID);
});

$app->get('/api/orders/{orderID}', function (Request $request, Response $response, $args) {
    $paypalService = new PayPalServices();
    $orderID = $args['orderID'];
    return $paypalService->getOrderDetails($response, $orderID);
});
?>