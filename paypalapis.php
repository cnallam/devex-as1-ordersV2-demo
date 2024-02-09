<?php
// paypalapis.php
// Assuming $app is a Slim application or similar that provides a routing mechanism
require 'vendor/autoload.php';
require_once 'paypalservices.php';

// Assuming there's a routing system in place like Slim or FastRoute
$app = new \Slim\App();

// $app = new \Slim\App(array(
//     'debug' => true
// ));


// Replace with your routing system accordingly

$paypalService = new PaypalServices();

// Create an order
$app->post('/api/orders', function () use ($paypalService) {
    $requestData = json_decode(file_get_contents('php://input'), true);
    $response = $paypalService->createOrder($requestData);
    echo json_encode($response);
});

// Capture an order
$app->post('/api/orders/:orderID/capture', function ($orderID) use ($paypalService) {
    $response = $paypalService->captureOrder($orderID);
    echo json_encode($response);
});

// Get order details
$app->get('/api/orders/:orderID', function ($orderID) use ($paypalService) {
    $response = $paypalService->getOrder($orderID);
    echo json_encode($response);
});
?>
