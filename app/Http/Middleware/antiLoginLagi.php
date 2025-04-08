<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class antiLoginLagi
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->session()->has('hak_akses')) {
            switch ($request->session()->get('hak_akses')) {
                case 'Admin':
                    return redirect()->route('laporan');
                case 'Director':
                    return redirect()->route('laporan');
                case 'General Manager':
                    return redirect()->route('laporan');
            }
        }

        return $next($request);
    }
}
