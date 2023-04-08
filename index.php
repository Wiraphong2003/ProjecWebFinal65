<?php
header('Access-Control-Allow-Origin: *');
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/webAPI_ProjectWebFinal');

require __DIR__ . '/dbcon.php';
require __DIR__ . '/api/login.php';
require __DIR__ . '/api/customer.php';
require __DIR__ . '/api/food.php';

$app->get('/', function (Request $request, Response $response, $args) {
   $response->getBody()->write("Hello world! 2345");
   return $response;
});

$app->run();
