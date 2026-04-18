<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestCheck
{
    /**
     * Kalau sudah login, langsung redirect ke dashboard.
     * Mencegah user yang sudah login balik ke halaman login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('refreshToken')) {
            return redirect()->route('tutorials.index');
        }

        return $next($request);
    }
}