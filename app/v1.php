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
        $rec = json_decode($data, true);
        if (!is_array($rec) || array_diff(array('city', 'state'), array_keys($rec))) {
            throw new Exceptions\ValidationError(
                  'Request data must be valid JSON and follow structure: '
                . '{ "city" : CITY, "state" : STATE }'
            );
        }
        $user = \ORM::for_table('users')
                    ->where('id', $userId)
                    ->find_one();
        if ($user === false) {
            throw new Exceptions\ValidationError('Invalid user');
        }
        $city = \ORM::for_table('cities')
                    ->where('state', $rec['state'])
                    ->where('name', $rec['city'])
                    ->find_one();
        if ($city === false) {
            throw new Exceptions\ValidationError('Requested city is unknown');
        }
        $visit = \ORM::for_table('visits')
                     ->where('users_id', $user->id)
                     ->where('cities_id', $city->id)
                     ->find_one();
        if ($visit === false) {
            $visit = \ORM::for_table('visits')->create();
            $visit->users_id = $user->id;
            $visit->cities_id = $city->id;
        }
        $visit->set_expr('modified', 'CURRENT_TIMESTAMP');
        $visit->save();
        return $this->render($visit->as_array());
    }

    public function citiesVisitedBy($userId) {
        return $this->render(
            \ORM::for_table('visits')
                ->inner_join('cities', array('visits.cities_id', '=', 'cities.id'))
                ->inner_join('users', array('visits.users_id', '=', 'users.id'))
                ->where('users.id', $userId)
                ->find_array()
        );
    }

    protected function render($data)
    {
        $app = \Slim\Slim::getInstance();
        $app->response->headers->set('Content-Type', 'application/json');
        echo json_encode($data);
    }
}
