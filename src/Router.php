<?php

namespace Core;

class Router 
{

    private $controllerName;
    private $actionName;
    private $processed = false;
    private $routes;

    private function process() {

        if (!$this->processed) {
            $urlParts = parse_url($_SERVER['REQUEST_URI']);
            $path = $urlParts['path'];

            if (isset($this->routes[$path])) {
                $this->controllerName = $this->routes[$path][0];
                $this->actionName = $this->routes[$path][1];
            } else {
                $urlParts = explode('/', $path);
                $this->controllerName = 'App\\Controller\\' . ucfirst(strtolower($urlParts[1]));
                $this->actionName = strtolower($urlParts[2] ?? 'Index');
            }

            $this->processed = true;
        }
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