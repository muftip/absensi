@extends('layouts.main')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .ti.ti-circle-x:hover {
            color: rgb(250, 137, 107);
        }

        .ti.ti-circle-x {
            cursor: pointer;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-baseline mb-3">
                <div class="card-title fw-semibold flex-grow-1">Absen Keluar</div>
                <div class="card-title fs-3">
                    <div id="timestamp"></div>
                </div>
            </div>
            @isset($libur)
                <h3 class="text-danger text-center">{{ $libur }}</h3>
            @endisset
            @isset($tutup)
                <div class="alert alert-danger mb-4">
                    {{ $tutup }}
                </div>
            @endisset
            <div class="text-center mb-3"><?php
            echo strftime('%A,');
            echo date(' d-M-Y'); ?>
            </div>
            <table class="table table-hover mt-4" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Id</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Waktu Masuk</th>
                        <th scope="col">Waktu Keluar</th>
                        <th scope="col">#</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($data_masuk)
                        @foreach ($data_masuk as $row)
                            <tr>
                                <td></td>
                                <td>{{ $row->id_karyawan }}</td>
                                <td>{{ $row->karyawan->nama }}</td>
                                <td>{{ $row->waktu_masuk }}</td>
                                <td>
                                    @isset($row->waktu_keluar)
                                        {{ $row->waktu_keluar }}
                                    @else
                                        <div class="btn btn-danger fs-1 p-2 py-1" onclick="setJamKeluar(this)">Keluar</div>
                                    @endisset
                                </td>
                                <td>-</td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
            {{-- <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('absensi.tutup-absensi') }}" class="btn btn-danger" onclick="tutupAbsensi(this)">Tutup
                    absensi</a>
            </div> --}}
        </div>
    </div>
@endsection
@section('extra_scripts')
    <script>
        $(document).ready(function() {
            setInterval(timestamp, 1000);
            $('#myTable').DataTable({
                "order": [
                    [4, 'desc']
                ],
                "createdRow": function(row, data, dataIndex) {
                    $(row).children().eq(0).html(dataIndex + 1);
                }
            });
        });

        function timestamp() {
            $.ajax({
                url: '/timestamp.php',
                success: function(data) {
                    $('#timestamp').html(data);
                },
            });
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function updateRowNumbers() {
            const tbody = document.querySelector("table tbody");
            for (let i = 0; i < tbody.rows.length; i++) {
                tbody.rows[i].cells[0].textContent = i + 1;
            }
        }

        function setJamKeluar(element) {
            const timestamp = document.getElementById("timestamp").innerHTML;
            if (timestamp.trim() === "") {
                alert("Timestamp kosong. Tidak bisa mengatur Waktu Keluar!");
                return;
            }
            element.innerHTML = timestamp;
            element.className = '';
            const parent = element.closest('tr');
            const id_karyawan = parent.cells[1].textContent.trim();
            console.log(id_karyawan);
            const statusKirim = parent.cells[5];
            statusKirim.innerHTML = "<div class='spinner-border spinner-border-sm'></div>"
            $.ajax({
                type: "post",
                url: "{{ route('absensi.simpan-data-keluar') }}",
                data: {
                    "id_karyawan": id_karyawan,
                    "waktu_keluar": timestamp
                },
                success: function(data) {
                    statusKirim.innerHTML = "<i class='ti ti-check text-success'></i>";
                },
                error: function(xhr, status, error) {
                    statusKirim.innerHTML = "<i class='ti ti-x text-danger'>" + error + "</i>";
                }
            });
        }

        function tutupAbsensi(element) {
            element.innerHTML = "<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div>";
            element.setAttribute("disabled", '');
        }
    </script>
@endsection
