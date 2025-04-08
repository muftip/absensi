@extends('layouts.main')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex mb-3">
                <div class="card-title fw-semibold flex-grow-1">Laporan Absensi</div>
                <a href="#" id="print-pdf-btn" class="btn btn-danger me-5">Unduh Laporan ke PDF</a>
                <a href="#" id="export-excel-btn" class="btn btn-success">Export Laporan Ke Excel</a>
            </div>
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <div class="col-lg-4 col-sm-3">
                    <div class="input-group d-flex">
                        <select class="form-select" id="bulan" name="bulan">
                            <option value="01">Januari</option>
                            <option value="02">Februari</option>
                            <option value="03">Maret</option>
                            <option value="04">April</option>
                            <option value="05">Mei</option>
                            <option value="06">Juni</option>
                            <option value="07">Juli</option>
                            <option value="08">Agustus</option>
                            <option value="09">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                        <input type="number" class="form-control" id="tahun" value="{{ date('Y') }}" />
                        <button class="btn btn-primary" id="filter-btn">Cari</button>
                    </div>
                </div>
            </div>
            <table class="display" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Jumlah Hadir</th>
                        <th scope="col">Jumlah Izin</th>
                        <th scope="col">Jumlah Alpha</th>
                        <th scope="col">Jumlah Terlambat</th>
                        <th scope="col">Total Jam Lembur</th>
                    </tr>
                </thead>
                <tbody id="laporan-table-body">
                    @foreach ($laporan as $key => $g)
                        <tr>
                            <td>{{ $g->id }}</td>
                            <td class='nama'>{{ $g->nama_karyawan }}</td>
                            <td>{{ $g->jumlah_hadir }}</td>
                            <td>{{ $g->jumlah_izin }}</td>
                            <td>{{ $g->jumlah_alpha }}</td>
                            <td>{{ $g->total_telat }}</td>
                            <td>{{ $g->total_lembur }} jam</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#myTable').DataTable({
                "createdRow": function(row, data, dataIndex) {
                    $('td', row).eq(1).addClass('nama'); // Menambahkan kelas 'nama' ke kolom Nama
                }
            });

            $('#filter-btn').click(function() {
                var bulan = $('#bulan').val();
                var tahun = $('#tahun').val();

                $.ajax({
                    url: '{{ route('laporan.filter') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        bulan: bulan,
                        tahun: tahun
                    },
                    success: function(response) {
                        table.clear().draw(); // Menghapus data tabel yang ada
                        response.forEach(function(laporan) {
                            table.row.add([
                                laporan.id,
                                laporan.nama_karyawan,
                                laporan.jumlah_hadir,
                                laporan.jumlah_izin,
                                laporan.jumlah_alpha,
                                laporan.total_telat,
                                laporan.total_lembur + ' jam'
                            ]).draw(false);
                        });
                    }
                });
            });

            $('#print-pdf-btn').click(function() {
                var bulan = $('#bulan').val();
                var tahun = $('#tahun').val();
                var url = '{{ route('print.pdf') }}' + '?bulan=' + bulan + '&tahun=' + tahun;
                window.location.href = url;
            });
        });
    </script>
@endsection

@section('extra_scripts')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>

    <script>
        document.getElementById('filter-btn').addEventListener('click', function() {
            const bulan = document.getElementById('bulan').value;
            const tahun = document.getElementById('tahun').value;
            const url = `/laporan/export-excel/${bulan}/${tahun}`;
            document.getElementById('export-excel-btn').href = url;
        });
    </script>

    <style>
        #myTable thead th {
            text-align: center;
        }

        #myTable tbody td {
            text-align: center;
        }

        #myTable tbody .nama {
            text-align: left;
        }
    </style>
@endsection
