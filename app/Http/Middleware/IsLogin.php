<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
        {
            // Nếu chưa đăng nhập thì đá về login
            if (!Auth::check()) {
                return redirect()->route('admin.login');
            }

            // Nếu đã đăng nhập nhưng không phải admin
            if (Auth::user()->role < 1) {
                Auth::logout(); // Đăng xuất luôn để tránh vòng lặp
                return redirect()->route('admin.login')->with('error', 'Bạn không có quyền truy cập!');
            }

            return $next($request);
        }
}
