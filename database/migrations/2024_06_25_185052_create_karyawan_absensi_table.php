<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karyawan_absensi', function (Blueprint $table) {
            $table->string('id_karyawan', 5);
            $table->foreign('id_karyawan')->references('id')->on('karyawan')->cascadeOnUpdate()->cascadeOnDelete();
            $table->unsignedInteger('id_absensi'); // Ubah tipe data ke unsignedInteger
            $table->foreign('id_absensi')->references('id')->on('absensi')->cascadeOnUpdate()->cascadeOnDelete(); // Tambahkan onUpdate
            $table->time('waktu_masuk');
            $table->time('waktu_keluar')->nullable();
            $table->primary(array('id_absensi', 'id_karyawan'));
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan_absensi');
    }
};
