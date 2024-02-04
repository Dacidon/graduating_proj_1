<?php
include '../vendor/autoload.php';

use \App\Controller\User;
use \App\Controller\Blog;
use Core\Router;

$route = new Router(); 
// $route->addRoute('/user/login', User::class, 'login');
// $route->addRoute('/user/register', User::class, 'register');
// $route->addRoute('/blog/messages', Blog::class, 'getMessages');

$controllerName = $route->getController();
$actionName = $route->getAction();

$controller = new $controllerName;

$controller->$actionName();



