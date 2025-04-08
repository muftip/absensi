<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\HariLiburController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\jabatanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\checkHakAkses;
use App\Http\Middleware\checkDirector;
use App\Http\Middleware\checkAdmin;
use App\Http\Middleware\checkGeneralManager;
use App\Http\Middleware\antiLoginLagi;
use App\Http\Middleware\checkAdminDirector;
use App\Exports\LaporanAbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;

// ✅ Public Route: Register (tanpa login)
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// ✅ Route untuk halaman login
Route::middleware([antiLoginLagi::class])->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login-page');
    Route::post('/', [LoginController::class, 'login'])->name('login');
});

// ✅ Route yang membutuhkan login
Route::middleware([checkHakAkses::class])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/error', fn() => view('user.error'));

    // Laporan dan cetak
    Route::get('/laporan', [AbsensiController::class, 'laporan'])->name('laporan');
    Route::post('/laporan/filter', [AbsensiController::class, 'laporanFilter'])->name('laporan.filter');
    Route::get('/print-pdf', [AbsensiController::class, 'generatePDF'])->name('print.pdf');
    Route::get('log-harian', [AbsensiController::class, 'logharian'])->name("logharian");
    Route::get('log-harian/single', [AbsensiController::class, 'logharian_single'])->name("logharian.single");
    Route::get('cetak', [AbsensiController::class, 'cetak'])->name('cetak');

    // Export ke Excel
    Route::get('/laporan/export-excel/{bulan}/{tahun}', function ($bulan, $tahun) {
        return Excel::download(new LaporanAbsensiExport($bulan, $tahun), 'laporan-absensi.xlsx');
    })->name('laporan.export-excel');

    // ✅ Route untuk Admin + Director
    Route::middleware([CheckAdminDirector::class])->group(function () {
        Route::resource('users', RegisterController::class);
     
        Route::resource('karyawan', KaryawanController::class);
        Route::resource('jabatan', jabatanController::class);
        Route::resource('hari-libur', HariLiburController::class);

        Route::get('absensi/masuk', [AbsensiController::class, 'masuk'])->name('absensi.masuk');
        Route::get('absensi/izin', [AbsensiController::class, 'izin'])->name('absensi.izin');
        Route::get('absensi/keluar', [AbsensiController::class, 'keluar'])->name('absensi.keluar');

        Route::post('save/masuk', [AbsensiController::class, 'simpan_masuk'])->name('absensi.simpan-data-masuk');
        Route::post('save/izin', [AbsensiController::class, 'simpan_izin'])->name('absensi.simpan-data-izin');
        Route::post('save/keluar', [AbsensiController::class, 'simpan_keluar'])->name('absensi.simpan-data-keluar');

        Route::get('/riwayat', [ActivityLogController::class, 'index'])->name('activity.log');
    });

    // ✅ Route khusus Admin
    Route::middleware([CheckAdmin::class])->group(function () {
        Route::get('absensi/ubah', [AbsensiController::class, 'editAbsensi'])->name('absensi.edit');
        Route::put('edit-absensi', [AbsensiController::class, 'edit_update'])->name('edit.update');
        Route::delete('delete-absensi/{id_karyawan}', [AbsensiController::class, 'edit_delete'])->name('edit.delete');
    });

    // ✅ Route khusus Director
    Route::middleware([CheckDirector::class])->group(function () {
        Route::get('revisi', [AbsensiController::class, 'revisi'])->name('revisi');
        Route::get('data-revisi', [AbsensiController::class, 'data_revisi'])->name('data-revisi');
        Route::put('data-revisi', [AbsensiController::class, 'update_revisi'])->name('update-revisi');
        Route::get('riwayat', [AbsensiController::class, 'riwayat'])->name('riwayat');
    });

    // ✅ Route untuk General Manager
    Route::middleware([CheckGeneralManager::class])->group(function () {
        // Tambahkan route jika ada fitur untuk GM
    });
});