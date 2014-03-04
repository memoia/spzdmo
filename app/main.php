<?php
namespace Memoia\SpzDmo;

require dirname(dirname(__FILE__)).'/vendor/autoload.php';
require 'autoload.php';


$app = new \Slim\Slim(array(
    'debug' => true,
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
));

$app->group('/v1', function () use ($app) {
    $app->get('/hello/:name', '\Memoia\SpzDmo\V1\Api:hello');
});

$app->run();
