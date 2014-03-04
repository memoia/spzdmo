<?php
namespace Memoia\SpartzFun;

function api_version_loader($ns) {
    try {
        list($version, $class) = array_slice(explode('\\', $ns), -2);
    } catch (Exception $err) {
        return;
    }
    if ($class != 'Api') {
        return;
    }
    include dirname(__FILE__).DIRECTORY_SEPARATOR.strtolower($version).'.php';
}

spl_autoload_register('\Memoia\SpartzFun\api_version_loader');
