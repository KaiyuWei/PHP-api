<?php

namespace App\Helpers;

class Router {
    private $routes = [];

    public function get($uri, $action) {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action) {
        $this->addRoute('POST', $uri, $action);
    }

    public function put($uri, $action) {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete($uri, $action) {
        $this->addRoute('DELETE', $uri, $action);
    }

    private function addRoute($method, $uri, $action) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function dispatchRequest() {

        foreach ($this->routes as $route) {
            $this->triggerControllerMethod($route);
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    private function triggerControllerMethod(array $route) {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if ($route['method'] === $requestMethod && $route['uri'] === $requestUri) {
            list($controller, $method) = $route['action'];
            (new $controller)->$method();
        }
    }

}