<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EndpointsServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        foreach (config('custom.api.endpoints') as $endpoint) {
            $name = strtolower(preg_replace('/^.+\\\\/', '', $endpoint));
            $this->app->bind('endpoint.' . $name, function () use ($endpoint) {
                return $this->app->make($endpoint);
            });
        }
    }
}
