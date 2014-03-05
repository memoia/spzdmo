<?php

use Phinx\Migration\AbstractMigration;

class LoadCitiesAndUsers extends AbstractMigration
{
    private function prepare($sql) {
        return $this->adapter->getConnection()->prepare($sql);
    }

    private function source_file($name) {
        $path = 'data/'.$name;
        $fd = fopen($path, 'r');
        if ($fd !== null) {
            return $fd;
        }
        throw new \Exception($path);
    }

    private function load_file($fd, $stmt) {
        $prefix = function($v) {
            return ":$v";
        };
        $fields = array_map($prefix, fgetcsv($fd));
        while (!feof($fd)) {
            $data = fgetcsv($fd);
            if (!is_array($data)) {
                continue;
            }
            $stmt->execute(array_combine($fields, $data));
        }
        return $fd;
    }

    private function load_cities($fd) {
        $stmt = $this->prepare('INSERT INTO cities VALUES (:id, :name, :state, :status, :latitude, :longitude)');
        $this->load_file($fd, $stmt);
    }

    private function load_users($fd) {
        $stmt = $this->prepare('INSERT INTO users VALUES (:id, :first_name, :last_name)');
        $this->load_file($fd, $stmt);
    }

    public function up()
    {
        $this->load_users($this->source_file('users.csv'));
        $this->load_cities($this->source_file('cities.csv'));
    }
}
