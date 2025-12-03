<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareAliases = [
        // 'auth' => \App\Http\Middleware\Authenticate::class, // HAPUS/COMMENT INI
        // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // HAPUS/COMMENT INI
        // 'admin' => \App\Http\Middleware\AdminAuth::class, // COMMENT DULU
        // 'user' => \App\Http\Middleware\UserMiddleware::class, // COMMENT DULU
        
        // KOSONGKAN AJA KALO GAADA FILE NYA
    ];
}