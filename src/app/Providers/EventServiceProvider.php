<?php

namespace App\Providers;

use App\Events\TokenIsInvalidated;
use App\Listeners\DeleteTokenListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        TokenIsInvalidated::class => [
            DeleteTokenListener::class
        ],
    ];
}
