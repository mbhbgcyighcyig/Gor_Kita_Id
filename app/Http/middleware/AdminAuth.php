<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/admin/login')->with('error', 'Silakan login sebagai admin.');
        }

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect('/admin/login')->with('error', 'Akses ditolak! Hanya untuk admin.');
        }

        return $next($request);
    }
}