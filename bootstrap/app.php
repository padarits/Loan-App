<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('api', [
            //\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // This handles SPA authentication
            //'throttle:api',
            //\Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        /*$exceptions->render(function (AuthenticationException $e, Request $request) {
            $response['statusCode'] = 403;
            $response['message'] = 'Unauthorized';
            return response()->json(['error' => true, 'content' => 'YOUR MESSAGE'], 403);
        });*/
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            // return redirect($request->fullUrl());
        });
    })->create();
