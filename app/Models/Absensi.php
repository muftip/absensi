<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;
    protected $table = "absensi";
    protected $fillable = [
        "id_libur",
        "tanggal"
    ];
    public function libur()
    {
        return $this->belongsTo(HariLibur::class, 'id_libur');
    }
}
