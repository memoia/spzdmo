<?php
namespace Memoia\SpzDmo;

require dirname(__DIR__).'/vendor/autoload.php';
require 'db.php';
require 'util.php';
require 'exception.php';
require 'v1.php';

$app = new \Slim\Slim(array(
    'debug' => false,
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
));

$app->error(function (\Exception $err) use ($app) {
    if ($err instanceof Exceptions\ValidationError) {
        $app->halt(500, $err->getMessage());
    }
});

$app->group('/v1', function () use ($app) {
    $app->get('/hello/:name', '\Memoia\SpzDmo\V1\Api:hello');
    $app->get('/states/:state/cities.json', '\Memoia\SpzDmo\V1\Api:cities');
    $app->get('/states/:state/cities/:cityName.json', '\Memoia\SpzDmo\V1\Api:citiesNear');
});

$app->run();
