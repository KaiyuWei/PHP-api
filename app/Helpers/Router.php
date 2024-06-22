<?php

namespace App\Helpers;

class Router {
    private $routes = [];

    public function get($uri, $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put($uri, $action): void
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete($uri, $action): void
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    private function addRoute($method, $uri, $action): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'action' => $action
        ];
    }

    public function dispatchRequest(): void
    {
        foreach ($this->routes as $route) {
            $this->triggerControllerMethod($route);
        }

        http_response_code(404);
        echo '404 Not Found';
    }

    private function triggerControllerMethod(array $route): void
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $isMethodMatched = $route['method'] === $requestMethod;
        $isRoutingMatched = $this->isRouteMatched($route['uri'], $requestUri);

        if ($isMethodMatched && $isRoutingMatched) {
            list($controller, $method) = $route['action'];
            (new $controller)->$method();
            exit();
        }
    }

    private function isRouteMatched($route, $uri): bool
    {
        if ($route === $uri) return true;
        return $this->isDymanicRoutingMatched($route, $uri);
    }

    private function isDymanicRoutingMatched($route, $uri): bool
    {
        $route = trim($route, '/');
        $uri = trim($uri, '/');

        $routeParts = explode('/', $route);
        $uriParts = explode('/', $uri);

        // The last part of the route should be {id}
        if (end($routeParts) !== '{id}') {
            return false;
        }
        // The last part of the URI should be a digit
        if (!ctype_digit(end($uriParts))) {
            return false;
        }

        // Remove the last part (id) from both arrays
        array_pop($routeParts);
        array_pop($uriParts);

        // Compare the remaining parts
        return $routeParts === $uriParts;
    }

}