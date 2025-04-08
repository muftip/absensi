<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Activitylog\Facades\CauserResolver;
use App\Models\User;

class checkHakAkses
{
    public function handle($request, Closure $next)
    {
        if (session()->has('hak_akses')) {
            $user = User::find(session('id_karyawan'));
            CauserResolver::setCauser($user);
            return $next($request);
        }

        return redirect('/')->with('error', 'Silahkan login terlebih dahulu!');
    }
}
