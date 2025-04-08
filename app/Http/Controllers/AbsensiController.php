<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Dompdf\Dompdf;
use App\Models\Absensi;
use App\Models\HariLibur;
use App\Models\KaryawanAbsensi;
use App\Models\KaryawanIzin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;

class AbsensiController extends Controller
{
    private function cek_otentik_absen(){
        date_default_timezone_set('Asia/Jakarta');
        $currentDate = now()->toDateString();
        $absen = Absensi::where('tanggal', $currentDate)->first();
        if (is_null($absen)){
            return true;
        } else {
            return false;
        }
    }

    private function otomatis_tutup_absensi(){
        date_default_timezone_set('Asia/Jakarta');
        $currentDate = now()->toDateString();
        $absen = Cache::get('id_absensi');
        if ($absen){
            $current_absen = Absensi::find($absen);
            if ($currentDate !== $current_absen->tanggal){
                return true;
            }
            return false;
        }
        return false;
    }

    public function riwayat(){
        $activities = Activity::all()->map(function($item){
            $nama = Karyawan::withTrashed()->find($item->causer_id)->nama;
            $eventMapping = [
                'deleted' => 'Menghapus',
                'updated' => 'Memperbarui',
                'created' => 'Membuat'
            ];
            $status = $eventMapping[$item->event] ?? $item->event;
            $subjectType = class_basename($item->subject_type);
            return [
                "created_at" => $item->created_at,
                "nama" => $nama,
                "status" => $status,
                "deskripsi" => $status ." ". $subjectType . " dengan id " . $item->subject_id
            ];
        });
        return view('absensi.riwayat', compact('activities'));
    }

    public function revisi()
    {
        return view('director.revisi');
    }

    public function update_revisi(Request $request)
    {
        $keterangan = $request->rowdata["keterangan"];
        $masuk = KaryawanAbsensi::find([
            $request->rowdata[0],
            $request->id_absensi
        ]);
        $izin = KaryawanIzin::find([
            $request->rowdata[0],
            $request->id_absensi
        ]);
        if (is_null($keterangan)) {
            if ($masuk) {
                $masuk->update([
                    "waktu_masuk" => $request->rowdata["waktu-masuk"],
                    "waktu_keluar" => $request->rowdata["waktu-keluar"]
                ]);
                return response()->json(["message" => "Data berhasil diupdate"]);
            } else if ($izin) {
                $izin->delete();
                $new_data = new KaryawanAbsensi([
                    "id_absensi" => $request->id_absensi,
                    "id_karyawan" => $request->rowdata[0],
                    "waktu_masuk" => $request->rowdata["waktu-masuk"],
                    "waktu_keluar" => $request->rowdata["waktu-keluar"]
                ]);
                $new_data->save();
                return response()->json(["message" => "Data berhasil diupdate"]);
            }
        } else {
            if ($masuk) {
                $masuk->delete();
                if (strtolower($keterangan) === "alpha") {
                    $new_data = new KaryawanIzin([
                        "id_absensi" => $request->id_absensi,
                        "id_karyawan" => $request->rowdata[0],
                        "izin" => 0,
                        "keterangan" => null
                    ]);
                    $new_data->save();
                    return response()->json(["message" => "Karyawan ditetapkan sebagai alpha"]);
                }
                $new_data = new KaryawanIzin([
                    "id_absensi" => $request->id_absensi,
                    "id_karyawan" => $request->rowdata[0],
                    "keterangan" => $keterangan
                ]);
                $new_data->save();
                return response()->json(["message" => "Data berhasil diupdate"]);
            } else if ($izin) {
                if (strtolower($keterangan) === "alpha") {
                    $izin->update([
                        "id_absensi" => $request->id_absensi,
                        "id_karyawan" => $request->rowdata[0],
                        "izin" => 0,
                        "keterangan" => null
                    ]);
                    return response()->json(["message" => "Karyawan ditetapkan sebagai alpha"]);
                }
                $izin->update([
                    "izin" => 1,
                    "keterangan" => $keterangan
                ]);
                return response()->json(["message" => "Data berhasil diupdate"]);
            }
        }

    }

    public function data_revisi()
    {
        $tanggal = request()->query()["tanggal"];
        $absensi = Absensi::where('tanggal', $tanggal)->first();
        $libur = HariLibur::whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->get(['id', 'keterangan'])->first();
        if (!is_null($libur)) {
            return response()->json(["message" => $libur->keterangan], 202);
        } else if (is_null($tanggal) || is_null($absensi)) {
            return response()->noContent();
        }
        $masuk = KaryawanAbsensi::with('karyawan')->where('id_absensi', $absensi->id)->get()->map(function ($item) {
            return [
                "id" => $item->karyawan->id,
                "nama" => $item->karyawan->nama,
                "waktu_masuk" => $item->waktu_masuk,
                "waktu_keluar" => $item->waktu_keluar
            ];
        });
        $data_izin = KaryawanIzin::with('karyawan')->where('id_absensi', $absensi->id)->get();
        $izin = $data_izin->map(function ($item) {
            if ($item->izin === 1) {
                return [
                    "id" => $item->karyawan->id,
                    "nama" => $item->karyawan->nama,
                    "keterangan" => $item->keterangan
                ];
            }
        })->filter()->values();
        $alpha = $data_izin->map(function ($item) {
            if ($item->izin === 0) {
                return [
                    "id" => $item->karyawan->id,
                    "nama" => $item->karyawan->nama,
                ];
            }
        })->filter()->values();
        if ($masuk->isEmpty() && $izin->isEmpty() && $alpha->isEmpty()) {
            return response()->noContent();
        }
        return response()->json([
            "id_absensi" => $absensi->id,
            "masuk" => $masuk,
            "izin" => $izin,
            "alpha" => $alpha
        ]);
    }

    public function edit_delete($id)
    {
        $id_absensi = Cache::get('id_absensi');
        $data_absensi = KaryawanAbsensi::find([
            $id,
            $id_absensi
        ]);
        if ($data_absensi) {
            $data_absensi->forceDelete();
            $alpha = new KaryawanIzin([
                "id_absensi" => $id_absensi,
                "id_karyawan" => $id,
                "izin" => 0
            ]);
            $alpha->save();
            return response()->json(["message" => "Data masuk berhasil dihapus"]);
        }
        $data_izin = KaryawanIzin::find([
            $id,
            $id_absensi
        ]);
        if ($data_izin) {
            $data_izin->izin = 0;
            $data_izin->keterangan = null;
            $data_izin->save();
            return response()->json(["message" => "Data izin berhasil dihapus"]);
        }
        return response()->json(["message" => "Data tidak ditemukan"]);
    }

    public function edit_update(Request $request)
    {
        $id_absensi = Cache::get('id_absensi');
        $id_karyawan = $request[0];
        $nama = $request[1];
        $masuk = $request["waktu-masuk"];
        $keluar = $request["waktu-keluar"];
        $keterangan = $request["keterangan"];
        if (is_null($keterangan)) {
            $karyawan_izin = KaryawanIzin::find([
                $id_karyawan,
                $id_absensi
            ]);
            if ($karyawan_izin) {
                $karyawan_izin->forceDelete();
            }
            $karyawan = KaryawanAbsensi::find([
                $id_karyawan,
                $id_absensi
            ]);
            if ($karyawan){
                $karyawan->waktu_masuk = $masuk;
                $karyawan->waktu_keluar = $keluar;
                $karyawan->save();
                return response()->json(['message' => 'Berhasil update data masuk karyawan']);
            }
            $new_masuk = new KaryawanAbsensi([
                "id_karyawan" => $id_karyawan,
                "id_absensi" => $id_absensi,
                "waktu_masuk" => $masuk,
                "waktu_keluar" => $keluar
            ]);
            $new_masuk->save();
            return response()->json(['message' => 'Berhasil membuat data masuk karyawan']);
        }
        $izin = KaryawanIzin::find([
            $id_karyawan,
            $id_absensi
        ]);
        if ($izin){
            $izin->keterangan = $keterangan;
            $izin->save();
            return response()->json(['message' => 'Berhasil update data izin karyawan']);
        }
        $absen = KaryawanAbsensi::find([
            $id_karyawan,
            $id_absensi
        ]);
        if ($absen){
            $absen->forceDelete();
        }
        $new_izin = new KaryawanIzin([
            "id_karyawan" => $id_karyawan,
            "id_absensi" => $id_absensi,
            "izin" => 1,
            "keterangan" => $keterangan
        ]);
        $new_izin->save();
        return response()->json(['message' => 'Berhasil membuat izin karyawan']);
    }

    public function masuk()
    {
        if ($this->otomatis_tutup_absensi()){
            Cache::forget('id_absensi');
        }
        $id_absensi = Cache::get('id_absensi');
        if (!is_null($keterangan = $this->cek_libur())) {
            return view('absensi.absenMasuk')->with('libur', $keterangan);
        } else if (is_null($id_absensi)) {
            $alpha = Karyawan::all()->map(function ($item){
                return [
                    "id" => $item->id,
                    "nama" => $item->nama
                ];
            });
           return view('absensi.absenMasuk', compact('alpha'));
        }
        $masuk = KaryawanAbsensi::where('id_absensi', $id_absensi)->get();
        $alpha = KaryawanIzin::with('karyawan')->where('id_absensi', $id_absensi)->where('izin', 0)->get()->map(function($item){
            return [
                "id" => $item->id_karyawan,
                "nama" => $item->karyawan->nama
            ];
        });
        return view('absensi.absenMasuk', compact(['masuk', 'alpha']));
    }

    private function cek_libur()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currentDate = now()->toDateString();
        $libur = HariLibur::whereDate('tanggal_mulai', '<=', $currentDate)
            ->whereDate('tanggal_selesai', '>=', $currentDate)
            ->get(['id', 'keterangan'])->first();
        if (is_null($libur)) {
            return null;
        }
        return $libur->keterangan;
    }

    private function buat()
    {
        date_default_timezone_set('Asia/Jakarta');
        $currentDate = now()->toDateString(); // Get the current date in 'Y-m-d' format
        if ($this->cek_otentik_absen()){
            $absen = new Absensi([
                'tanggal' => $currentDate
            ]);
            $absen->save();
            Cache::put('id_absensi', $absen->id);
            $karyawan = Karyawan::all();
            foreach($karyawan as $item){
                $karyawan_alpha = new KaryawanIzin([
                    'id_absensi' => $absen->id,
                    'id_karyawan' => $item->id,
                    'izin' => 0
                ]);
                $karyawan_alpha->save();
            }
            return true;
        } else{
            return false;
        }
    }


    public function editAbsensi()
    {
        if ($this->otomatis_tutup_absensi()){
            Cache::forget('id_absensi');
        }
        $id_absensi = Cache::get('id_absensi');
        if ($keterangan = $this->cek_libur()) {
            return view('absensi.edit')->with('libur', $keterangan);
        } else if (is_null($id_absensi)) {
            return view('absensi.edit');
        }
        $masuk = KaryawanAbsensi::with('karyawan')->where('id_absensi', $id_absensi)->get();
        $izin = KaryawanIzin::with('karyawan')->where('id_absensi', $id_absensi)->where('izin', 1)->get();
        return view('absensi.edit', compact(['masuk', 'izin']));
    }

    public function simpan_masuk(Request $request)
    {
        $id_absensi = Cache::get('id_absensi');
        if (is_null($id_absensi)){
            if($this->buat()){
                $id_absensi = Cache::get('id_absensi');
            } else {
                return response()->json([],423);
            };
        }
        $karyawan_alpha = KaryawanIzin::find([
            $request->id,
            $id_absensi
        ]);
        $karyawan_alpha->forceDelete();
        $karyawanAbsensi = new KaryawanAbsensi([
            'id_absensi' => $id_absensi,
            'id_karyawan' => $request->id,
            'waktu_masuk' => $request->waktu
        ]);
        $karyawanAbsensi->save();
        return response()->json();
    }

    public function simpan_izin(Request $request)
    {
        $id_karyawan = $request->id_karyawan;
        $id_absensi = Cache::get('id_absensi');
        if (is_null($id_absensi)){
            if($this->buat()){
                $id_absensi = Cache::get('id_absensi');
            } else {
                return response()->json([],423);
            };
        }
        $keterangan = $request->keterangan;
        $karyawan_izin = KaryawanIzin::find([
            $id_karyawan,
            $id_absensi
        ]);
        $karyawan_izin->izin = 1;
        $karyawan_izin->keterangan = $keterangan;
        $karyawan_izin->save();
        return "Data berhasil disimpan";
    }

    public function simpan_keluar(Request $request)
    {
        $id_karyawan = $request->id_karyawan;
        $id_absensi = Cache::get('id_absensi');
        $karyawan_absensi = KaryawanAbsensi::find([
            $id_karyawan,
            $id_absensi
        ]);
        $karyawan_absensi->waktu_keluar = $request->waktu_keluar;
        $karyawan_absensi->save();
        return "Data berhasil disimpan";
    }

    public function keluar()
    {
        if ($this->otomatis_tutup_absensi()){
            Cache::forget('id_absensi');
        }
        $id_absensi = Cache::get('id_absensi');
        if ($keterangan = $this->cek_libur()) {
            return view('absensi.absenKeluar')->with('libur', $keterangan);
        } else if (is_null($id_absensi)) {
            return view('absensi.absenKeluar')->with('error', "Tidak ada data absensi untuk hari ini, apakah Anda ingin membuat satu?");
        }
        $data_masuk = KaryawanAbsensi::with('karyawan')->where('id_absensi', $id_absensi)->get();
        return view('absensi.absenKeluar', ['data_masuk' => $data_masuk]);
    }

    public function izin()
    {
        if ($this->otomatis_tutup_absensi()){
            Cache::forget('id_absensi');
        }
        $id_absensi = Cache::get('id_absensi');
        if ($keterangan = $this->cek_libur()) {
            return view('absensi.absenIzin')->with('libur', $keterangan);
        } else if (is_null($id_absensi)) {
            $alpha = Karyawan::get('id', 'nama');
            return view('absensi.absenIzin', compact('alpha'));
        }
        $nama_masuk = collect(KaryawanAbsensi::with('karyawan')->where('id_absensi', $id_absensi)->get('id_karyawan')
            ->map(function ($item) {
                return $item ? $item->karyawan->nama : null;
            }));
        $data_izin = KaryawanIzin::with('karyawan')->where('id_absensi', $id_absensi)->where('izin', 1)->get()->map(function($item){
            return [
                "id" => $item->id_karyawan,
                "nama" => $item->karyawan->nama,
                "keterangan" => $item->keterangan
            ];
        });
        $data_alpha = KaryawanIzin::with('karyawan')->where('id_absensi', $id_absensi)->where('izin', 0)->get()->map(function($item){
            return [
                "id" => $item->id_karyawan,
                "nama" => $item->karyawan->nama
            ];
        });
        return view('absensi.absenIzin', ['alpha' => $data_alpha, 'izin' => $data_izin]);
    }

    public function laporan()
    {
        $laporan = DB::select("SELECT 
            karyawan.id, karyawan.nama AS nama_karyawan,
            COALESCE(hadir.jumlah_hadir, 0) AS jumlah_hadir,
            COALESCE(izin.jumlah_izin, 0) AS jumlah_izin,
            COALESCE(alpha.jumlah_alpha, 0) AS jumlah_alpha,
            COALESCE(terlambat.total_telat, 0) AS total_telat,
            COALESCE(lembur.total_lembur, 0) AS total_lembur
        FROM 
            karyawan
        LEFT JOIN (
            SELECT 
                karyawan_absensi.id_karyawan, 
                COUNT(DISTINCT karyawan_absensi.id_absensi) AS jumlah_hadir
            FROM 
                karyawan_absensi
            LEFT JOIN 
                absensi ON karyawan_absensi.id_absensi = absensi.id 
            WHERE 
                MONTH(absensi.tanggal) = ? AND YEAR(absensi.tanggal) = ?
            GROUP BY 
                karyawan_absensi.id_karyawan
        ) AS hadir ON karyawan.id = hadir.id_karyawan
        LEFT JOIN (
            SELECT 
                karyawan_izin.id_karyawan, 
                COUNT(*) AS jumlah_izin
            FROM 
                karyawan_izin
            LEFT JOIN 
                absensi ON karyawan_izin.id_absensi = absensi.id
            WHERE 
                karyawan_izin.izin = 1 
                AND MONTH(absensi.tanggal) = ? 
                AND YEAR(absensi.tanggal) = ?
            GROUP BY 
                karyawan_izin.id_karyawan
        ) AS izin ON karyawan.id = izin.id_karyawan
        LEFT JOIN (
            SELECT 
                karyawan_izin.id_karyawan, 
                COUNT(*) AS jumlah_alpha
            FROM 
                karyawan_izin
            LEFT JOIN 
                absensi ON karyawan_izin.id_absensi = absensi.id
            WHERE 
                karyawan_izin.izin = 0 
                AND MONTH(absensi.tanggal) = ? 
                AND YEAR(absensi.tanggal) = ?
            GROUP BY 
                karyawan_izin.id_karyawan
        ) AS alpha ON karyawan.id = alpha.id_karyawan
        LEFT JOIN (
            SELECT 
                karyawan_absensi.id_karyawan, 
                SUM(GREATEST(HOUR(waktu_keluar) - 17, 0)) AS total_lembur
            FROM 
                karyawan_absensi
            LEFT JOIN 
                absensi ON karyawan_absensi.id_absensi = absensi.id 
            WHERE 
                HOUR(waktu_keluar) > 17
                AND MONTH(absensi.tanggal) = ? 
                AND YEAR(absensi.tanggal) = ?
            GROUP BY 
                karyawan_absensi.id_karyawan
        ) AS lembur ON karyawan.id = lembur.id_karyawan
        LEFT JOIN (
            SELECT 
                karyawan_absensi.id_karyawan, 
                COUNT(*) AS total_telat
            FROM 
                karyawan_absensi
            LEFT JOIN 
                absensi ON karyawan_absensi.id_absensi = absensi.id 
            WHERE 
                TIME(waktu_masuk) > '08:00:00'
                AND MONTH(absensi.tanggal) = ? 
                AND YEAR(absensi.tanggal) = ?
            GROUP BY 
                karyawan_absensi.id_karyawan
        ) AS terlambat ON karyawan.id = terlambat.id_karyawan
        WHERE karyawan.deleted_at IS NULL
        ORDER BY 
            karyawan.nama;", ['01', date('Y'), '01', date('Y'), '01', date('Y'), '01', date('Y'), '01', date('Y')]);

        return view('absensi.laporan', compact('laporan'));
    }


    public function laporanFilter(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $laporan = DB::select("SELECT 
        karyawan.id, karyawan.nama AS nama_karyawan,
        COALESCE(hadir.jumlah_hadir, 0) AS jumlah_hadir,
        COALESCE(izin.jumlah_izin, 0) AS jumlah_izin,
        COALESCE(alpha.jumlah_alpha, 0) AS jumlah_alpha,
        COALESCE(terlambat.total_telat, 0) AS total_telat,
        COALESCE(lembur.total_lembur, 0) AS total_lembur
    FROM 
        karyawan
    LEFT JOIN (
        SELECT 
            karyawan_absensi.id_karyawan, 
            COUNT(DISTINCT karyawan_absensi.id_absensi) AS jumlah_hadir
        FROM 
            karyawan_absensi
        LEFT JOIN 
            absensi ON karyawan_absensi.id_absensi = absensi.id 
        WHERE 
            MONTH(absensi.tanggal) = ? AND YEAR(absensi.tanggal) = ?
        GROUP BY 
            karyawan_absensi.id_karyawan
    ) AS hadir ON karyawan.id = hadir.id_karyawan
    LEFT JOIN (
        SELECT 
            karyawan_izin.id_karyawan, 
            COUNT(*) AS jumlah_izin
        FROM 
            karyawan_izin
        LEFT JOIN 
            absensi ON karyawan_izin.id_absensi = absensi.id
        WHERE 
            karyawan_izin.izin = 1 
            AND MONTH(absensi.tanggal) = ? 
            AND YEAR(absensi.tanggal) = ?
        GROUP BY 
            karyawan_izin.id_karyawan
    ) AS izin ON karyawan.id = izin.id_karyawan
    LEFT JOIN (
        SELECT 
            karyawan_izin.id_karyawan, 
            COUNT(*) AS jumlah_alpha
        FROM 
            karyawan_izin
        LEFT JOIN 
            absensi ON karyawan_izin.id_absensi = absensi.id
        WHERE 
            karyawan_izin.izin = 0 
            AND MONTH(absensi.tanggal) = ? 
            AND YEAR(absensi.tanggal) = ?
        GROUP BY 
            karyawan_izin.id_karyawan
    ) AS alpha ON karyawan.id = alpha.id_karyawan
    LEFT JOIN (
        SELECT 
            karyawan_absensi.id_karyawan, 
            SUM(GREATEST(HOUR(waktu_keluar) - 17, 0)) AS total_lembur
        FROM 
            karyawan_absensi
        LEFT JOIN 
            absensi ON karyawan_absensi.id_absensi = absensi.id 
        WHERE 
            HOUR(waktu_keluar) > 17
            AND MONTH(absensi.tanggal) = ? 
            AND YEAR(absensi.tanggal) = ?
        GROUP BY 
            karyawan_absensi.id_karyawan
    ) AS lembur ON karyawan.id = lembur.id_karyawan
    LEFT JOIN (
        SELECT 
            karyawan_absensi.id_karyawan, 
            COUNT(*) AS total_telat
        FROM 
            karyawan_absensi
        LEFT JOIN 
            absensi ON karyawan_absensi.id_absensi = absensi.id 
        WHERE 
            TIME(waktu_masuk) > '08:00:00'
            AND MONTH(absensi.tanggal) = ? 
            AND YEAR(absensi.tanggal) = ?
        GROUP BY 
            karyawan_absensi.id_karyawan
    ) AS terlambat ON karyawan.id = terlambat.id_karyawan
    WHERE karyawan.deleted_at IS NULL
    ORDER BY 
        karyawan.nama;", [$bulan, $tahun, $bulan, $tahun, $bulan, $tahun, $bulan, $tahun, $bulan, $tahun]);

        return response()->json($laporan);
    }

    public function logHarian_single(){
        $date = request()->query()["tanggal"];
        $absen = Absensi::where('tanggal', $date)->first();
        if (is_null($absen)){
            return "No data";
        }
        $dataMasuk = KaryawanAbsensi::with('karyawan')->where('id_absensi', $absen->id)->get();
        $masuk = $dataMasuk->filter(function($item){
            return !is_null($item->waktu_keluar);
        })->map(function($item){
            return [
                "nama" => $item->karyawan->nama,
                "waktu_masuk" => $item->waktu_masuk,
                "waktu_keluar" => $item->waktu_keluar
            ];
        })->values();
        $minggat = $dataMasuk->filter(function($item){
            return is_null($item->waktu_keluar);
        })->map(function($item){
            return [
                "nama" => $item->karyawan->nama,
                "waktu_masuk" => $item->waktu_masuk
            ];
        })->values();
        $izin = KaryawanIzin::with('karyawan')->where('id_absensi', $absen->id)->where('izin', 1)->get()->map(function ($item) {
            return [
                "nama" => $item->karyawan->nama,
                "keterangan" => $item->keterangan
            ];
        })->values();
        $alpha = KaryawanIzin::with('karyawan')->where('id_absensi', $absen->id)->where('izin', 0)->get()->map(function ($item) {
            return [
                "nama" => $item->karyawan->nama
            ];
        })->values();
        return response()->json([
            "masuk" => $masuk,
            "izin" => $izin,
            "alpha" => $alpha,
            "minggat" => $minggat
        ]);
    }

    public function logHarian()
    {
        $params = request()->query();
        $nama = $params['nama'] ?? null;
        $start_date = $params['start'] ?? null;
        $end_date = $params['end'] ?? null;
        $karyawan = Karyawan::with('jabatan')->get(['id', 'nama', 'id_jabatan']);
        if (is_null($nama) || is_null($start_date) || is_null($end_date)) {
            return view('absensi.logHarian', compact('karyawan'));
        }

        $karyawanData = Karyawan::where('nama', $nama)->first();
        if (!$karyawanData) {
            return view('absensi.logHarian', compact('karyawan'))->withErrors(['error' => 'Karyawan not found']);
        }
        $id_karyawan = $karyawanData->id;
        $kumpulan_id_absensi = Absensi::whereBetween('tanggal', [$start_date, $end_date])->get();

        $logMasuk = [];
        $logAlpha = [];
        $logIzin = [];
        $logMinggat = [];

        foreach ($kumpulan_id_absensi as $item) {
            $absensiIzin = KaryawanIzin::where('id_karyawan', $id_karyawan)->where('id_absensi', $item->id)->first();
            if ($absensiIzin) {
                $logIzin[] = [
                    'tanggal' => $item->tanggal,
                    'keterangan_izin' => $absensiIzin->keterangan,
                ];
                continue;
            }
            $absensiMasuk = KaryawanAbsensi::where('id_karyawan', $id_karyawan)->where('id_absensi', $item->id)->first();
            if ($absensiMasuk) {
                if(is_null($absensiMasuk->waktu_keluar)){
                    $logMinggat[] = [
                        'tanggal' => $item->tanggal,
                        'waktu_masuk' => $absensiMasuk->waktu_masuk
                    ];
                } else {
                    $logMasuk[] = [
                        'tanggal' => $item->tanggal,
                        'waktu_masuk' => $absensiMasuk->waktu_masuk,
                        'waktu_keluar' => $absensiMasuk->waktu_keluar,
                    ];
                }
                continue;
            }
            $logAlpha[] = [
                'tanggal' => $item->tanggal,
            ];
        }
        return response()->json([
            'logMasuk' => $logMasuk,
            'logIzin' => $logIzin,
            'logAlpha' => $logAlpha,
            'logMinggat' => $logMinggat
        ]);
    }

    public function generatePDF(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        // Convert month number to month name
        $monthNames = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        $monthName = $monthNames[$bulan];

        // Get current timestamp
        $currentTimestamp = Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i:s \W\I\B');

        // Fetch report data based on month and year
        $laporan = DB::select("SELECT 
        karyawan.id, karyawan.nama AS nama_karyawan,
        COALESCE(hadir.jumlah_hadir, 0) AS jumlah_hadir,
        COALESCE(izin.jumlah_izin, 0) AS jumlah_izin,
        COALESCE(alpha.jumlah_alpha, 0) AS jumlah_alpha,
        COALESCE(terlambat.total_telat, 0) AS total_telat,
        COALESCE(lembur.total_lembur, 0) AS total_lembur
    FROM 
        karyawan
    LEFT JOIN (
        SELECT 
            karyawan_absensi.id_karyawan, 
            COUNT(DISTINCT karyawan_absensi.id_absensi) AS jumlah_hadir
        FROM 
            karyawan_absensi
        LEFT JOIN 
            absensi ON karyawan_absensi.id_absensi = absensi.id 
        WHERE 
            MONTH(absensi.tanggal) = ? AND YEAR(absensi.tanggal) = ?
        GROUP BY 
            karyawan_absensi.id_karyawan
    ) AS hadir ON karyawan.id = hadir.id_karyawan
    LEFT JOIN (
        SELECT 
            karyawan_izin.id_karyawan, 
            COUNT(*) AS jumlah_izin
        FROM 
            karyawan_izin
        LEFT JOIN 
            absensi ON karyawan_izin.id_absensi = absensi.id
        WHERE 
            karyawan_izin.izin = 1 
            AND MONTH(absensi.tanggal) = ? 
            AND YEAR(absensi.tanggal) = ?
        GROUP BY 
            karyawan_izin.id_karyawan
    ) AS izin ON karyawan.id = izin.id_karyawan
    LEFT JOIN (
        SELECT 
            karyawan_izin.id_karyawan, 
            COUNT(*) AS jumlah_alpha
        FROM 
            karyawan_izin
        LEFT JOIN 
            absensi ON karyawan_izin.id_absensi = absensi.id
        WHERE 
            karyawan_izin.izin = 0 
            AND MONTH(absensi.tanggal) = ? 
            AND YEAR(absensi.tanggal) = ?
        GROUP BY 
            karyawan_izin.id_karyawan
    ) AS alpha ON karyawan.id = alpha.id_karyawan
    LEFT JOIN (
        SELECT 
            karyawan_absensi.id_karyawan, 
            SUM(GREATEST(HOUR(waktu_keluar) - 17, 0)) AS total_lembur
        FROM 
            karyawan_absensi
        LEFT JOIN 
            absensi ON karyawan_absensi.id_absensi = absensi.id 
        WHERE 
            HOUR(waktu_keluar) > 17
            AND MONTH(absensi.tanggal) = ? 
            AND YEAR(absensi.tanggal) = ?
        GROUP BY 
            karyawan_absensi.id_karyawan
    ) AS lembur ON karyawan.id = lembur.id_karyawan
    LEFT JOIN (
        SELECT 
            karyawan_absensi.id_karyawan, 
            COUNT(*) AS total_telat
        FROM 
            karyawan_absensi
        LEFT JOIN 
            absensi ON karyawan_absensi.id_absensi = absensi.id 
        WHERE 
            TIME(waktu_masuk) > '08:00:00'
            AND MONTH(absensi.tanggal) = ? 
            AND YEAR(absensi.tanggal) = ?
        GROUP BY 
            karyawan_absensi.id_karyawan
    ) AS terlambat ON karyawan.id = terlambat.id_karyawan
    WHERE karyawan.deleted_at IS NULL
    ORDER BY 
        karyawan.id;", [$bulan, $tahun, $bulan, $tahun, $bulan, $tahun, $bulan, $tahun, $bulan, $tahun]);

        $data = [
            'laporan' => $laporan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'monthName' => $monthName,
            'currentTimestamp' => $currentTimestamp,
            'header' => 'CV. ANUGRAH ABADI',
            'title' => 'Laporan Absensi Karyawan'
        ];

        // Load the view and pass data
        $view = view('absensi.halamanCetakLaporan', $data)->render();

        // Generate PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($view);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan_absensi.pdf');
    }

}

