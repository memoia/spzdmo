<?php
namespace Memoia\SpzDmo;

require dirname(__DIR__).'/vendor/autoload.php';
require 'db.php';
require 'autoload.php';
spl_autoload_register('\Memoia\SpzDmo\api_version_loader');


$app = new \Slim\Slim(array(
    'debug' => true,
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
));

$app->group('/v1', function () use ($app) {
    $app->get('/hello/:name', '\Memoia\SpzDmo\V1\Api:hello');
    $app->get('/states/:state/cities.json', '\Memoia\SpzDmo\V1\Api:cities');
});

$app->run();
