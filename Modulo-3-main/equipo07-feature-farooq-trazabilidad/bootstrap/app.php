<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',      
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias de tus middlewares personalizados
        $middleware->alias([
            'admin'         => \App\Http\Middleware\AdminMiddleware::class,
            'guest'         => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'role.admin'    => \App\Http\Middleware\RoleAdmin::class,
            'role.vendedor' => \App\Http\Middleware\RoleVendedor::class,
            'role.cliente'  => \App\Http\Middleware\RoleCliente::class,
        ]);

        // ⬅️ IMPORTANTE: excluir todas las rutas /api/* del CSRF
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
