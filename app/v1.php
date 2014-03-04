<?php
namespace Memoia\SpartzFun\V1;

class Api {

    function hello($name) {
        return $this->render("Hello, $name");
    }

    private function render($data) {
        echo json_encode($data);
    }

}
