<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Jabatan extends Model
{
    use HasFactory, LogsActivity;
    protected $table = "jabatan";
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        "id",
        "nama", // Pastikan kolom nama ada di tabel jabatan
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
