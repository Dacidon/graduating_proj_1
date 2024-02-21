<?php
require '../vendor/autoload.php';
require '../base/config.php';
require '../base/eloquent.php';

ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL | E_NOTICE);

$route = new \Base\Route();
$route->add('/', \App\Controller\Login::class);
$route->add('/logout', \App\Controller\Login::class, 'logout');
$route->add('/admin/users', \App\Controller\Admin\Users::class);
$route->add('/admin/saveUser', \App\Controller\Admin\Users::class, 'saveUser');
$route->add('/admin/deleteUser', \App\Controller\Admin\Users::class, 'deleteUser');
$route->add('/admin/addUser', \App\Controller\Admin\Users::class, 'addUser');

$app = new \Base\Application($route);
$app->run();