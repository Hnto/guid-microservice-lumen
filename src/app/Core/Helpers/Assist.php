<?php
namespace App\Core\Helpers;

use Dingo\Api\Routing\Router;

class Assist
{
    /**
     * Get correct endpoint from path
     *
     * @return string
     */
    public static function extractResourceFromPath()
    {
        $routeInfo = app('request')->route();

        preg_match('/\/(.*)\/?/', $routeInfo[1]['uri'], $match);

        $uri = $match[1];

        if (strpos($uri, '/') !== false) {
            $uri = strstr($uri, '/', true);
        }

        return $uri;
    }

    /**
     * Get the registered resources
     *
     * @return array
     */
    public static function getResources()
    {
        /** @var Router $api */
        $api = app('Dingo\Api\Routing\Router');

        $resources = [];
        foreach ($api->getRoutes()[getenv('API_VERSION')]->getRoutes() as $route) {
            if (in_array($route->getPath(), ['resources', false, null, '', 'authenticate'])) {
                continue;
            }

            $resources[] = $route->getPath();
        }

        return $resources;
    }
}
