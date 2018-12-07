<?php

namespace App\Http\Middleware;

use App\Core\Token\TokenRepository;
use App\Events\TokenIsInvalidated;
use Closure;
use Illuminate\Http\JsonResponse;

class Authorize
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        /** @var TokenRepository $tokenRepository */
        $tokenRepository = app(TokenRepository::class);
        $token = $tokenRepository->findTokenByValue($request->header('x-token', ''));

        if ($token->isExpired()) {
            //Fire the token is invalidated event
            event(new TokenIsInvalidated($token));

            return JsonResponse::create(
                [
                    'status' => 'forbidden',
                    'message' => 'the provided token is invalid'
                ],
                403
            )
                ->header('WWW-authorize', 'x-token')
                ->send();
        }

        return $next($request);
    }
}
