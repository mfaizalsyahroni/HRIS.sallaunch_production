<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('verified_worker')) {
            return redirect()->route('news.verifyForm')
                ->with('error', 'Silakan verifikasi akses terlebih dahulu.');
        }

        return $next($request);
    }
}
