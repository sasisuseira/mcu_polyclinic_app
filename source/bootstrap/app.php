<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\{Exceptions, Middleware};
use Tymon\JWTAuth\Http\Middleware\{Authenticate, RefreshToken};
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withBroadcasting(
        __DIR__.'/../routes/channels.php',
        ['middleware' => ['web', \App\Http\Middleware\AuthenticateBroadcastUser::class]],
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            
        ]); 
        $middleware->alias([
            'jwt.cookie' => \App\Http\Middleware\JWTFromCookieMiddleware::class,
            'jwt.auth' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
            'jwt.refresh' => \Tymon\JWTAuth\Http\Middleware\RefreshToken::class,
            'permission_cache' => \App\Http\Middleware\CheckPermission::class,
        ]);
        $middleware->encryptCookies(except: [
            'token_device',
            'user_id',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (HttpException $exception, Request $request) {
            if ($exception->getStatusCode() == 403) {
                return response()->view("error.403_error", [], 403);
            }
        });
    })->create();
