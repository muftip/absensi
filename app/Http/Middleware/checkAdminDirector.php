<?php

namespace App\Http\Middleware;

use Closure;

class checkAdminDirector
{
    public function handle($request, Closure $next)
    {
        if (session()->has('hak_akses') && in_array(session('hak_akses'), ['Admin', 'Director'])) {
            return $next($request);
        }
        return redirect('/error')->with('error', 'Anda tidak memiliki akses!');
    }
}
