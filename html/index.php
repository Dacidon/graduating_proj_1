<?php

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1);

use Core\Application;

include '../vendor/autoload.php';

include '../src/config.php';

$app = new Application();
$app->start();





