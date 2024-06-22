<?php

namespace App\Helpers;

class DynamicRouteParser
{
    /**
     * @param $route
     * @param $uri
     * @return bool
     *
     * check if a request uri matches the route patter <path>/{id}
     */
    public static function isDymanicRouteMatched($route, $uri): bool
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

    /**
     * This function parses uris of pattern '/api/text..text/123', and get the digit at the end.
     */
    public static function parseAndGetDigitsAtTheEndOfUri($uri): int|false
    {
        $matches = [];
        if (preg_match('/^\/api\/[\w\/-]+\/(\d+)$/', $uri, $matches)) {
            // Extract the {id} parameter caught by regex
            return $matches[1];
        }

        return false;
    }
}