<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('auth_id') || session('auth_role') !== 'admin') {
            return redirect()->route('login')
                ->with('error', 'Silakan login sebagai Admin terlebih dahulu.');
        }

        return $next($request);
    }
}
