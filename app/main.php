<?php
namespace Memoia\SpartzFun;
require dirname(dirname(__FILE__)).'/vendor/autoload.php';

$app = new \Slim\Slim(array(
    'debug' => true,
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
));

$app->group('/v1', function () use ($app) {

    $app->get('/hello/:name', function ($name) {
        echo "Hello, $name";
    });

});

$app->run();
