<?php
namespace App\Api;

use App\Api\Skeletons\Endpoint;

class EndpointFactory
{
    /**
     * @param $name
     * @return Endpoint
     */
    public static function create($name)
    {
        $container = app();

        if ($container->offsetExists('endpoint.' . $name)) {
            return app('endpoint.' . $name);
        }

	    return app('endpoint.resources');
    }
}
