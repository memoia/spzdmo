<?php

// Force Slim to process all requests in the
// PHP Devserver environment.
//
$_SERVER["SCRIPT_NAME"] = null;
include 'main.php';
