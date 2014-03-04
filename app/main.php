<?php
namespace Memoia\SpartzFun;
require dirname(dirname(__FILE__)).'/vendor/autoload.php';
require 'autoload.php';


$app = new \Slim\Slim(array(
    'debug' => true,
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
));

$app->response->headers->set('Content-Type', 'application/json');

$app->group('/v1', function () use ($app) {
    $app->get('/hello/:name', '\Memoia\SpartzFun\V1\Api:hello');
});

$app->run();
