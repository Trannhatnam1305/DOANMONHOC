<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Nếu đã đăng nhập VÀ có quyền Admin (role >= 1)
        if (Auth::check() && Auth::user()->role >= 1) {
            return $next($request); // Cho qua
        }

        // Nếu không phải Admin -> Đá về trang chủ hoặc trang login
        return redirect()->route('admin.login')->with('error', 'Bạn không được phép truy cập vùng này!');
    }
}
