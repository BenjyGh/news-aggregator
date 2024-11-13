<?php

use App\Factories\ErrorFactory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api/routes.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api([
            //
        ]);

        $middleware->alias([
            //
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Because we are developing API only backend, we disable rendering errors as HTML
        $exceptions->shouldRenderJsonWhen(fn() => true);

        // Delegate error handling to ErrorFactory class
        $exceptions->render(function (Throwable $e, Request $request) {
            $errorHandler = new ErrorFactory($e, $request);
            return $errorHandler->handle();
        });

    })->create();
