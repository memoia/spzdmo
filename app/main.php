<?php
namespace Memoia\SpartzFun;
require dirname(dirname(__FILE__)).'/vendor/autoload.php';

$app = new \Slim\Slim(array(
    'debug' => true,
    'log.enabled' => true,
    'log.level' => \Slim\Log::DEBUG,
));

$app->response->headers->set('Content-Type', 'application/json');


class ApiV1 {

    function hello($name) {
        return $this->render("Hello, $name");
    }

    private function render($data) {
        echo json_encode($data);
    }

}

$app->group('/v1', function () use ($app) {
    $app->get('/hello/:name', '\Memoia\SpartzFun\ApiV1:hello');
});

$app->run();
