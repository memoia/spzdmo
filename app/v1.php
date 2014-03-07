<?php
namespace Memoia\SpzDmo\V1;

use Memoia\SpzDmo\Util;
use Memoia\SpzDmo\Exceptions;

class Api
{
    public function cities($state)
    {
        return $this->render(
            \ORM::for_table('cities')
                ->where('state', $state)
                ->find_array()
        );
    }

    public function citiesNear($state, $cityName)
    {
        $radius = \Slim\Slim::getInstance()->request->params('radius');
        $radius = !is_null($radius) ? $radius : 0;
        if (!is_numeric($radius)) {
            throw new Exceptions\ValidationError('"radius" must be numeric');
        }

        $city = \ORM::for_table('cities')
                    ->where('state', $state)
                    ->where('name', $cityName)
                    ->where('status', 'verified')
                    ->find_one();
        if ($city === false) {
            throw new Exceptions\ValidationError('Requested city is unknown');
        }
        $bounds = Util\bounding_box($radius, $city->latitude, $city->longitude);
        $in_box = \ORM::for_table('cities')
                      ->where_lte('latitude', $bounds['max_lat'])
                      ->where_gte('latitude', $bounds['min_lat'])
                      ->where_lte('longitude', $bounds['max_lon'])
                      ->where_gte('longitude', $bounds['min_lon'])
                      ->find_array();

        $this->render($in_box);

        // TODO maybe further filter $in_box result like shown in
        // the reference as sql...
    }

    public function visitCity($userId) {
        $data = \Slim\Slim::getInstance()->request->getBody();
        $rec = json_decode($data);
        return $this->render($rec);
        //throw new Exceptions\ValidationError('Request data must be valid JSON and follow structure: { "city" : <CITY>, "state" : <STATE> }')
    }

    public function citiesVisitedBy($userId) {
        return $this->render($userId);
    }

    protected function render($data)
    {
        $app = \Slim\Slim::getInstance();
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($data);
    }
}
