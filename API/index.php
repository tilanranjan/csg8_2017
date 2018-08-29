<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'src/autoload.php';

$app = new \Slim\App([
    "settings"  => [
        "determineRouteBeforeAppMiddleware" => true,
        "displayErrorDetails" => false,
        "mode"=> "production",
        "debug"=>false
    ]
]);

//Generalized error handler:
//It will return the serialized failure response using the FailureResponse class to the client
$c = $app->getContainer();
$c['phpErrorHandler'] = function ($c) {
    return function ($request, $response, $error) use ($c) {
        $failureResponse = new FailureResponse();
        $failureResponse->set_error_code(0)->set_message($error->getMessage());

        return $c['response']
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write(Serializer::serialize($failureResponse));
    };
};

include_once 'src/routes/auth.php';
include_once 'src/routes/books.php';
include_once 'src/routes/image.php';

$app->run();
?>
