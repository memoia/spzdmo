<?php
namespace Memoia\SpzDmo\V1;

class Api
{
    public function hello($name)
    {
        return $this->render("Hello, $name");
    }

    private function render($data)
    {
        $app = \Slim\Slim::getInstance();
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($data);
    }
}
