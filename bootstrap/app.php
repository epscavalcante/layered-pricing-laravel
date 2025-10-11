<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;
use Src\Domain\Exceptions\AlreadyExistsException;
use Src\Domain\Exceptions\NotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (NotFoundException $e) {
            abort(Response::HTTP_NOT_FOUND, $e->getMessage());
        });

        $exceptions->report(function (AlreadyExistsException $e) {
            abort(Response::HTTP_CONFLICT, $e->getMessage());
        });
    })->create();
