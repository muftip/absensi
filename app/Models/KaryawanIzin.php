<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Illuminate\Database\Eloquent\SoftDeletes;

class KaryawanIzin extends Model
{
    use HasFactory, HasCompositeKey, SoftDeletes;
    protected $table = 'karyawan_izin';
    protected $primaryKey = ['id_karyawan', 'id_absensi'];
    public $incrementing = false;
    protected $fillable = [
        "id_karyawan",
        "id_absensi",
        "izin",
        "keterangan"
    ];
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan')->select('id', 'nama');
    }
}
