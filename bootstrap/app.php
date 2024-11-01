<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
    then: function () {
      Route::middleware(['web', 'set_language'])
        ->group(base_path('routes/admin.php'));
      Route::middleware(['web', 'set_language'])
        ->group(base_path('routes/web.php'));
    },
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
      'admin' => \App\Http\Middleware\Admin::class,
      'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
      'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
      'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
      'set_language' => \App\Http\Middleware\SetLanguage::class
    ]);
  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, Request $request) {
      if ($request->isMethod('Delete') || ($request->isMethod('Get') && Route::is('admin.toggle-news-status'))) {
        return response([
          'status' => 'error',
          'message' => 'You do not have the required authorization.'
        ]);
      }
    });
  })->create();
