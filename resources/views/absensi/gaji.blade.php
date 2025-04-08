@extends('layouts.main')
@section('content')
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <style>
        .toggle.ios,
        .toggle-on.ios,
        .toggle-off.ios {
            border-radius: 20px;
        }

        .toggle.ios .toggle-handle {
            border-radius: 20px;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="d-flex mb-3">
                <div class="card-title fw-semibold flex-grow-1">Gaji Karyawan</div>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="col-lg-4 col-sm-3">
                    <div class="input-group d-flex">
                        <select class="form-select" name="bulan" id="bulan">
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
                            <option value="Konghucu">Desember</option>
                        </select>
                        <input type="number" class="form-control" value="2024" id="tahun" />
                        <button class="btn btn-primary" id="cari">Cari</button>
                    </div>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                    <label class="form-check-label" for="flexSwitchCheckDefault">Beralih ke jam kerja</label>
                </div>
            </div>
            <table class="table table-striped" id="myTable">
                <?php $no = 1; ?>
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col">Gaji Pokok</th>
                        <th scope="col">Uang Makan</th>
                        <th scope="col">Uang Lembur</th>
                        <th scope="col">Denda</th>
                        <th scope="col">Total</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('extra_scripts')
    <script>
        $(document).ready(function() {
            const table = $('#myTable').DataTable();
            $('#cari').click(function() {
                const btnCari = $(this).html("<div class='spinner-border spinner-border-sm'></div>").attr(
                    'disabled', '');
                var bulan = $('#bulan').val();
                var tahun = $('#tahun').val();

                // You can make an AJAX call here to fetch data based on bulan and tahun
                $.ajax({
                    type: 'GET',
                    url: '{{ route('gaji') }}',
                    data: {
                        bulan: bulan,
                        tahun: tahun
                    },
                    success: function(response, status, xhr) {
                        btnCari.html("Cari").removeAttr('disabled');
                        table.clear();
                        if (xhr.status == 204) {
                            table.clear().draw();
                            return;
                        }
                        response.forEach(function(item) {
                            const totalMasuk = item.total_masuk + " hari";
                            const uangMakan = item.total_masuk * item.uang_makan;
                            const totalLembur = item.total_lembur + " jam";
                            const uangLembur = item.total_lembur * item.uang_lembur;
                            const totalTelat = item.total_telat + " hari";
                            const dendaTelat = item.denda_telat * item.total_telat;

                            const total = item.gaji_pokok + uangMakan + uangLembur -
                                dendaTelat;

                            const newRow = $("<tr>");
                            newRow.append(
                                $("<td>").text(item.id),
                                $("<td>").text(item.nama),
                                $("<td>").text(item.jabatan),
                                //$("<td>").text(item.gaji_pokok),
                                //$("<td>").html(`<span class="data">${totalMasuk}</span><span class="bayaran">${uangMakan}</span>`),
                                //$("<td>").html(`<span class="data">${totalLembur}</span><span class="bayaran">${uangLembur}</span>`),
                                // $("<td>").html(`<span class="data">${totalTelat}</span><span class="bayaran">${dendaTelat}</span>`),
                                //$("<td>").text(total)
                                $("<td>").html(formatRupiah(item.gaji_pokok)),
                                $("<td>").html(
                                    `<span class="data">${totalMasuk}</span><span class="bayaran">${formatRupiah(uangMakan)}</span>`
                                    ),
                                $("<td>").html(
                                    `<span class="data">${totalLembur}</span><span class="bayaran">${formatRupiah(uangLembur)}</span>`
                                    ),
                                // $("<td>").html(`<span class="data">${totalTelat}</span><span class="bayaran">${formatRupiah(dendaTelat)}</span>`),
                                $("<td>").html(formatDenda(dendaTelat, totalTelat)),
                                $("<td>").html(formatRupiah(total))
                            );
                            table.row.add(newRow).draw();
                        });
                        if ($('#flexSwitchCheckDefault').is(':checked')) {
                            $('span.data').show();
                            $('span.bayaran').hide();
                        } else {
                            $('span.data').hide();
                            $('span.bayaran').show();
                        }
                    },
                    error: function(xhr, status) {
                        btnCari.html("Cari").removeAttr('disabled');
                        console.error(xhr);
                    }
                });
                $('#flexSwitchCheckDefault').change(function() {
                    if ($(this).is(':checked')) {
                        $('span.data').show();
                        $('span.bayaran').hide();
                    } else {
                        $('span.data').hide();
                        $('span.bayaran').show();
                    }
                });
            });
        });

        function formatRupiah(angka) {
            const format = angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            return 'Rp. ' + format;
        }

        function formatDenda(denda, totalTelat) {
            const formattedDenda = formatRupiah(denda);
            return `<span class="data">${totalTelat}</span><span class="bayaran text-danger">- ${formattedDenda}</span>`;
        }
    </script>
@endsection
