<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuruAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('auth_id') || session('auth_role') !== 'guru') {
            return redirect()->route('login')
                ->with('error', 'Silakan login sebagai Guru terlebih dahulu.');
        }

        return $next($request);
    }
}
