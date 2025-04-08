<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Karyawan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;
    protected $table = "karyawan";
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        "id",
        "id_jabatan",
        "nama", // Pastikan kolom nama ada di tabel karyawan
        "email",
        "jenis_kelamin",
        "tempat_lahir",
        "tanggal_lahir",
        "alamat",
        "foto",
        "agama",
        "no_telp"
    ];

    public function jabatan(){
        return $this->belongsTo(Jabatan::class, 'id_jabatan');
    }

    public function karyawan_absensi(){
        return $this->hasMany(KaryawanAbsensi::class, 'id_karyawan', 'id');
    }

    public function karyawan_izin(){
        return $this->hasMany(KaryawanIzin::class, 'id_karyawan', 'id');
    }

    public function delete() {
        foreach($this->karyawan_absensi as $absensi){
            $absensi->delete();
        }
        foreach($this->karyawan_izin as $izin){
            $izin->delete();
        }
        parent::delete();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->setDescriptionForEvent(function(string $eventName) {
                return "{$this->nama} melakukan {$eventName} data {$this->getKey()}";
            });
    }
}
