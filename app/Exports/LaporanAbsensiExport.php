<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use DB;
use Carbon\Carbon;

class LaporanAbsensiExport implements FromView, WithStyles
{
    protected $bulan;
    protected $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
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
                karyawan.id;", [
            $this->bulan,
            $this->tahun,
            $this->bulan,
            $this->tahun,
            $this->bulan,
            $this->tahun,
            $this->bulan,
            $this->tahun,
            $this->bulan,
            $this->tahun
        ]);

        $currentTimestamp = Carbon::now()->format('d F Y, H:i:s');
        $monthName = Carbon::createFromFormat('m', $this->bulan)->locale('id')->translatedFormat('F');
        $tahun = $this->tahun;

        $header = "CV. ANUGRAH ABADI";
        $title = "LAPORAN ABSENSI KARYAWAN";

        return view('absensi.export_laporan_absensi', compact('laporan', 'currentTimestamp', 'monthName', 'tahun', 'header', 'title'));
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells for headers
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $sheet->mergeCells('A4:G4');

        // Set font styles for headers
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(20);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A3')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A4')->getFont()->setSize(12);

        // Set alignment for the merged cell A4:G4 to right
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Set styles for table header
        $sheet->getStyle('A5:G5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A5:G5')->getFill()->getStartColor()->setARGB('FFEFEFEF'); // Warna abu-abu lebih terang
        $sheet->getStyle('A5:G5')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK); // Teks hitam

        // Set column width
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Align text in columns
        $sheet->getStyle('B:B')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle('A5:G5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Align text globally, excluding A4:G4
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A5:G5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A6:G' . $sheet->getHighestRow())->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Add border to the entire table
        $sheet->getStyle('A5:G' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);

        return [
            'A1:G1' => ['font' => ['bold' => true, 'size' => 20]],
            'A2:G2' => ['font' => ['bold' => true, 'size' => 12]],
            'A3:G3' => ['font' => ['bold' => true, 'size' => 12]],
            'A4:G4' => ['font' => ['size' => 12], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT]],
            'A5:G5' => ['font' => ['bold' => true]],
            'A:G' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'B' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT]],
        ];
    }


}
