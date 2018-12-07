<?php

return [
    'api' => [
        'endpoints' => [
            \App\Api\Endpoints\Resources::class,
            \App\Api\Endpoints\Authenticate::class,
            \App\Api\Endpoints\Guids::class
        ]
    ]
];
