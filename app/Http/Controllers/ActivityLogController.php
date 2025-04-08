<?php

namespace App\Http\Controllers;
use App\Models\User;

use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = Activity::all()->map(function ($activity) {
            $user = User::find($activity->causer_id);
            if ($user) {
                $activity->causer_info = "{$user->nama}";
            } else {
                $activity->causer_info = 'Pengguna tidak ditemukan';
            }
            return $activity;
        });

        return view('absensi.riwayat', compact('activities'));
    }
}
