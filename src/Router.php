<?php

namespace Core;

use App\Controller\User;
use App\Controller\Blog;


class Router {

    private $controllerName;
    private $actionName;
    private $processed = false;
    private $routes;

    private function process() {
        $urlParts = parse_url($_SERVER['REQUEST_URI']);
        $path = $urlParts['path'];

        if (isset($this->routes[$path])) {
            $this->controllerName = $this->routes[$path][0];
            $this->actionName = $this->routes[$path][1];
        } else {
            $urlParts = explode('/', $path);
            $this->controllerName = 'App\\Controller' . ucfirst(strtolower($urlParts[1]));
            $this->actionName = strtolower($urlParts[2] ?? 'Index');

            if(class_exists(!$this->controllerName)) {
                throw new \Exception('Controller ' . $this->controllerName . ' not found');
            }
        }

        // switch($urlParts['path']) {
        //     case '/user/login':
        //         $controller = new User();
        //         $controller->login();
        //         break;
        
        //     case '/user/register':
        //         $controller = new User();
        //         $controller->register();
        //         break;
        
        //     case '/blog/messages':
        //         $controller = new Blog();
        //         $controller->getMessages();
        //         break;
        
        //     default:
        //         echo '404 PAGE NOT FOUND';
        //         header("HTTP/1.0 404 Not Found");
        //         break;
        // }
    }

    public function addRoute($path, $controllerName, $actionName) {
        $this->routes[$path] = [
            $controllerName,
            $actionName
        ];
    }

    public function getController(): string {
        if (!$this->processed) {
            $this->process();
        }
        return $this->controllerName;
    }

    public function getAction(): string {
        if (!$this->processed) {
            $this->process();
        }
        return $this->actionName;
    }
}