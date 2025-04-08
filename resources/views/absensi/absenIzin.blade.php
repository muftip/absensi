@extends('layouts.main')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="card">
        <div class="card-body">
            <div class="d-flex mb-3">
                <div class="card-title fw-semibold flex-grow-1">Absen Izin</div>
            </div>
            @isset($libur)
                <h3 class="text-danger text-center">{{ $libur }}</h3>
            @endisset
            <div class="d-flex justify-content-center">
                <div class="mb-3"><?php
                echo strftime('%A,');
                echo date(' d-M-Y'); ?>
                </div>
            </div>
            <table class="table mt-4 table-hover"id="myTable">
                <?php $no = 1; ?>
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Id</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Status</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">#</th>
                    </tr>
                </thead>
                <tbody>
                    @isset($alpha)
                        @foreach ($alpha as $row)
                            <tr class="align-middle">
                                <td></td>
                                <td>{{ $row["id"]}}</td>
                                <td>{{ $row["nama"] }}</td>
                                <td class="text-center">Alpha</td>
                                <td><input type="text" class="form-control bg-light" /></td>
                                <td>-</td>
                            </tr>
                        @endforeach
                    @endisset
                    @isset($izin)
                        @foreach ($izin as $row)
                            <tr class="align-middle">
                                <td></td>
                                <td>{{ $row["id"] }}</td>
                                <td>{{ $row["nama"] }}</td>
                                <td class="text-center">Izin</td>
                                <td><input type="text" class="form-control bg-light" value="{{ $row['keterangan'] }}"
                                        disabled /></td>
                                <td>-</td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('extra_scripts')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                "order": [
                    [3, 'asc']
                ],
                "createdRow": function(row, data, dataIndex) {
                    $(row).children().eq(0).html(dataIndex + 1);
                }
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Add event listener for 'Enter' key press on input fields
            $('#myTable').on('keypress', 'input', function(e) {
                if (e.which == 13) { // Enter key pressed
                    const inputField = $(this);
                    const keterangan = inputField.val();
                    const parent = inputField.closest('tr');
                    const id_karyawan = parent.find('td').eq(1).text();
                    const statusAbsen = parent.find('td').eq(3);
                    const statusKirim = parent.find('td').eq(5);
                    statusKirim.html("<div class='spinner-border spinner-border-sm'></div>");
                    $.ajax({
                        type: "post",
                        url: "{{ route('absensi.simpan-data-izin') }}",
                        data: {
                            "id_karyawan": id_karyawan,
                            "keterangan": keterangan
                        },
                        success: function(data) {
                            inputField.prop('disabled', true);
                            statusAbsen.text("Izin");
                            statusKirim.html("<i class='ti ti-check text-success'></i>");
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status == 423) {
                                alert("Absensi sudah dikunci, silahkan pakai menu revisi pada akun director!");
                            } else {
                                alert(error);
                            }
                            statusKirim.html("<i class='ti ti-x text-danger'></i>");
                        }
                    });
                }
            });
        });
    </script>
@endsection
