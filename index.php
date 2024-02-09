<?php

error_reporting(E_ALL ^ E_DEPRECATED);
require 'vendor/autoload.php';

use Slim\App;


$app = new App();

// Default Service
$app->get('/', function ($request, $response) {
    Redirect('/web/checkout.html', false);
});

function Redirect($url, $permanent = false)
{
    header('Location: ' . $url, true, $permanent ? 301 : 302);
    die();
}


// Include our PayPal APIs Routes
require 'src/paypalapis.php';
require 'src/paypalservices.php';
$app->run();
?>