<?php
include '../vendor/autoload.php';

use \App\Controller\User;
use \App\Controller\Blog;

$urlParts = parse_url($_SERVER['REQUEST_URI']);

switch($urlParts['path']) {
    case '/user/login':
        $controller = new User();
        $controller->loginAction();
        break;

    case '/user/register':
        $controller = new User();
        $controller->registerAction();
        break;

    case '/blog/messages':
        $controller = new Blog();
        $controller->getMessagesAction();
        break;

    default:
        echo '404 PAGE NOT FOUND';
        header("HTTP/1.0 404 Not Found");
        break;
}

