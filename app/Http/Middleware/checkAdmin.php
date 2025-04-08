<?php

namespace App\Http\Middleware;

use Closure;

class checkAdmin
{
    public function handle($request, Closure $next)
    {
        if (session()->has('hak_akses') && session('hak_akses') == 'Admin') {
            return $next($request);
        }

        return redirect('/error')->with('error', 'Anda tidak memiliki akses!');
    }
}
