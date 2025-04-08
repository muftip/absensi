<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\SoftDeletes;

class KaryawanAbsensi extends Model
{
    use HasFactory, HasCompositeKey, SoftDeletes;
    protected $table = 'karyawan_absensi';
    protected $primaryKey = ['id_karyawan', 'id_absensi'];
    public $incrementing = false;
    protected $fillable = [
        "id_karyawan",
        "id_absensi",
        "waktu_masuk",
        "waktu_keluar"
    ];
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan')->select('id', 'nama');
    }
}
