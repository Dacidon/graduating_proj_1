<?php
namespace Core;

use App\Controller\User;
use App\Controller\Blog;
use Core\View;

class Application {
    private $router;
    private $controller;
    private $actionName;

    public function __construct() {
        $this->router = new Router();
    }

    public function start() {
        try {
            $this->addRoutes();
            $this->initController();
            $this->initAction();
    
            $view = new View();
            $this->controller->setView($view);

            $content = $this->controller->{$this->actionName}();

            echo $content;
        } catch (RedirectException $e) {
            header('Location: ' . $e->getUrl());
        }
        
    }

    private function addRoutes() {
        // $this->router->addRoute('/user/login', User::class, 'login');
        // $this->router->addRoute('/user/register', User::class, 'register');
        $this->router->addRoute('/blog', Blog::class, 'getMessages');
    }

    private function initController() {
        $controllerName = $this->router->getController();
        if(!class_exists($controllerName)) {
            throw new \Exception('Controller ' . $controllerName . ' not found');
        }

        $this->controller = new $controllerName();
    }

    private function initAction() {
        $actionName = $this->router->getAction();
        if(!method_exists($this->controller, $actionName)) {
            throw new \Exception('Action ' . $actionName . ' not found in ' . get_class($this->controller));
        }

        $this->actionName = $actionName;
    }
}