<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->string('id', 5)->primary();
            $table->string('id_jabatan', 2);
            $table->foreign('id_jabatan')->references('id')->on('jabatan')->cascadeOnUpdate();
            $table->string('nama', 50);
            $table->string('email', 50)->unique();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string("tempat_lahir", 20);
            $table->date("tanggal_lahir");
            $table->string("alamat", 100);
            $table->string('foto', 50)->nullable();
            $table->enum('agama', ['Islam', 'Katolik', 'Hindu', 'Kristen', 'Buddha', 'Konghucu']);
            $table->string('no_telp', 13)->unique();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
