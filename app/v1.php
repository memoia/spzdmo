<?php
namespace Memoia\SpzDmo\V1;

class Api
{
    public function hello($name)
    {
        return $this->render("Hello, $name");
    }

    public function cities($state)
    {
        return $this->render(
            \ORM::for_table('cities')
                ->where('state', $state)
                ->find_array()
        );
    }

    private function render($data)
    {
        $app = \Slim\Slim::getInstance();
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($data);
    }
}
