<?php

use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\DashboardStatsController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix('api')->group(function () {
                Route::get('/apps/{appKey}', [AppController::class, 'show']);
                Route::get('/dashboard/stats', [DashboardStatsController::class, 'index'])
                    ->middleware(['web', 'auth']);
            });
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
