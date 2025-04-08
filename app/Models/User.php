<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, LogsActivity;

    protected $table = "users";
    protected $keyType = 'string';
    protected $primaryKey = 'id_karyawan';
    public $incrementing = false;

    protected $fillable = [
        'id_karyawan',
        'nama', // Pastikan kolom nama ada di tabel users
        'username',
        'password',
        'hak_akses',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->setDescriptionForEvent(function(string $eventName) {
                return "{$this->nama} melakukan {$eventName} data {$this->getKey()}";
            });
    }
}
