<?php

namespace App\Http\Middleware;

use Closure;

class checkGeneralManager
{
    public function handle($request, Closure $next)
    {
        if (session()->has('hak_akses') && session('hak_akses') == 'General Manager') {
            return $next($request);
        }

        return redirect('/error')->with('error', 'Anda tidak memiliki akses!');
    }
}
