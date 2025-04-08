@extends('layouts.main')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-baseline mb-3">
                <div class="card-title fw-semibold flex-grow-1">Absen Masuk</div>
                <div class="card-title fs-3">
                    <div id="timestamp"></div>
                </div>
            </div>
            @isset($libur)
                <h3 class="text-danger text-center">{{ $libur }}</h3>
            @endisset
            {{-- @isset($tutup)
                <div class="alert alert-danger mb-4">
                    {{ $tutup }}
                </div>
            @endisset --}}
            <div class="text-center mb-3"><?php
            echo strftime('%A,');
            echo date(' d-M-Y'); ?>
            </div>
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Waktu Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($masuk)
                        @foreach ($masuk as $item)
                            <tr>
                                <td>{{ $item->id_karyawan }}</td>
                                <td>{{ $item->karyawan->nama }}</td>
                                <td>{{ $item->waktu_masuk }}</td>
                            </tr>
                        @endforeach
                    @endisset
                    @isset($alpha)
                        @foreach ($alpha as $item)
                            <tr class="text-danger">
                                <td>{{ $item["id"] }}</td>
                                <td>{{ $item["nama"] }}</td>
                                <td><button class="btn btn-primary" onclick="add(this)">Masuk</button></td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
            {{-- <div class="d-flex align-items-end mt-4 flex-column">
                <button class="btn btn-primary py-2" style="width: 100px;" onclick="saveTable(this)"
                    @if (isset($libur) or isset($error)) disabled @endif>Kirim</button>
                <div id="keterangan_kirim"></div>
            </div> --}}
        </div>
    </div>
@endsection
@section('extra_scripts')
    <script>
        let currentRequest = null;
        let addedEmployees = [];
        $(function() {
            const table = $('#myTable').DataTable({
                "order": [
                    [0, 'desc']
                ]
            });
            setInterval(timestamp, 1000);

            function timestamp() {
                $.ajax({
                    url: '/timestamp.php',
                    success: function(data) {
                        $('#timestamp').html(data);
                    }
                });
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function add(element) {
            const timestamp = document.getElementById("timestamp").innerHTML;
            if (timestamp.trim() === "") {
                alert("Timestamp kosong. Tidak bisa mengatur Waktu Masuk!");
                return;
            }
            var parent = element.closest("tr");
            var id = $(parent).find("td:first").text();
            var button = $(parent).find("td:last");
            var btnContent = button.html();
            var waktu_masuk = $('#timestamp').text();
            button.html("<div class='spinner-border spinner-border-sm'></div>");
            $.ajax({
                url: '{{ route('absensi.simpan-data-masuk') }}',
                type: 'POST',
                data: {
                    'id': id,
                    'waktu': waktu_masuk
                },
                success: function() {
                    button.html(waktu_masuk);
                    parent.classList.remove("text-danger");
                },
                error: function(xhr, status, error) {
                    if (xhr.status == 423) {
                        alert("Absensi sudah dikunci, silahkan pakai menu revisi pada akun director!");
                    } else {
                        alert(error);
                    }
                    button.html(btnContent);
                }
            });
        }
    </script>
@endsection
