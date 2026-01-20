<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next) {
    if (Auth::check() && Auth::user()->role >= 1) {
        return $next($request);
    }
    return redirect()->route('admin.sanpham')->with('error', 'Bạn không có quyền này!');
}
}
