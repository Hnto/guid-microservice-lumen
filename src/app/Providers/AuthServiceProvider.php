<?php

namespace App\Providers;

use App\Core\User\User;
use App\Core\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('user.repository', UserRepository::class);
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.
        $this->app['auth']->viaRequest('api', function (Request $request) {
            if (null !== $request->headers->has('x-api-key', null)) {
                /** @var User $user */
                $user = $this->app['user.repository']->authenticate(
                    $request->header('x-api-key', '')
                );

                if (null === $user) {
                    return null;
                }

                return $user->getUserModel();
            }

            return null;
        });
    }
}
