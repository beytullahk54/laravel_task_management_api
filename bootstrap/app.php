<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Event;
use App\Events\TaskAssigned;
use App\Listeners\SendTaskAssignedEmail;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class
        ]);
        
        $middleware->alias([
            'team.owner' => \App\Http\Middleware\TeamOwnerCheck::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json(
                [
                    'success' => false,
                    'message' => __('validations.unauthorized'),
                    'error' => $e->getMessage(),
                ], 403);
        });
    })
    ->withEvents(discover: [__DIR__.'/../app/Listeners'])
    ->create();
