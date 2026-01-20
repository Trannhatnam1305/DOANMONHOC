<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // THÊM ĐOẠN NÀY VÀO:
        $middleware->redirectGuestsTo(function (Request $request) {
            // Nếu đường dẫn hiện tại có chữ 'admin', chuyển sang trang login admin
            if ($request->is('admin/*')) {
                return route('admin.login');
            }
            // Ngược lại thì về trang login user bình thường
            return route('login');
        });
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\CheckAdminRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();






