@extends('layouts.main')
@section('content')
    <span id="id_absensi" class="hide"></span>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css" />
    <style>
        #tanggal {
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
        }

        #search-btn {
            border-top-left-radius: 0px;
            border-bottom-left-radius: 0px;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="d-flex mb-4">
                <div class="card-title fw-semibold flex-grow-1">Revisi</div>
                @isset($buka)
                <button class="btn btn-danger">Buka Absensi</button>
                @endisset
            </div>
            <div class="d-flex mb-4 col-3">
                <input type="date" class="form-control" id="tanggal">
                <button class="btn btn-primary" id="search-btn" onclick="search()"><i class="ti ti-search"></i></button>
            </div>
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Waktu Masuk</th>
                        <th scope="col">Waktu Keluar</th>
                        <th scope="col">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('exclude_jquery')
@endsection
@section('extra_scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.3.1/css/select.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.1/js/dataTables.select.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
    <script src="../assets/js/dataTables.altEditor.free.js"></script>
    <script type="text/javascript">
        let table;
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var columnDefs = [{
                title: "Id",
                type: "readonly"
            }, {
                title: "Nama",
                type: "readonly",
                required: true,
                unique: true,
                name: "gender"
            }, {
                data: "waktu-masuk",
                title: "Waktu masuk",
                pattern: "^([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$",
                type: "text",
                editorOnChange: function(event, altEditor) {
                    if ($(event.currentTarget).val() === '' && $(altEditor.modal_selector).find(
                            "#waktu-keluar").val() === '') {
                        $(altEditor.modal_selector).find("#keterangan").removeAttr('disabled').attr(
                            'required', 'true');
                    } else {
                        $(altEditor.modal_selector).find("#keterangan").attr('disabled', '').removeAttr(
                            'required');
                    }
                }
            }, {
                data: "waktu-keluar",
                title: "Waktu keluar",
                pattern: "^([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$",
                type: "text",
                editorOnChange: function(event, altEditor) {
                    if ($(event.currentTarget).val() === '' && $(altEditor.modal_selector).find(
                            "#waktu-masuk").val() === '') {
                        $(altEditor.modal_selector).find("#keterangan").removeAttr('disabled').attr(
                            'required', 'true');
                    } else {
                        $(altEditor.modal_selector).find("#keterangan").attr('disabled', '').removeAttr(
                            'required');
                    }
                }
            }, {
                data: "keterangan",
                title: "Keterangan",
                type: "text",
                editorOnChange: function(event, altEditor) {
                    if ($(event.currentTarget).val() === '') {
                        $(altEditor.modal_selector).find("#waktu-masuk").removeAttr('disabled').attr(
                            'required', 'true');
                        $(altEditor.modal_selector).find("#waktu-keluar").removeAttr('disabled').attr(
                            'required', 'true');
                    } else {
                        $(altEditor.modal_selector).find("#waktu-masuk").attr('disabled', '')
                            .removeAttr('required');
                        $(altEditor.modal_selector).find("#waktu-keluar").attr('disabled', '')
                            .removeAttr('required');
                        $(event.currentTarget).removeAttr('disabled').attr('required', 'true');
                    }
                }
            }];
            table = $('#myTable').DataTable({
                "sPaginationType": "full_numbers",
                columns: columnDefs,
                dom: 'Bfrtip',
                select: 'single',
                responsive: true,
                altEditor: true,
                buttons: [{
                    extend: 'selected',
                    text: 'Edit',
                    name: 'edit'
                }],
                onEditRow: function(datatable, rowdata, success, error) {
                    $.ajax({
                        url: "{{ route('update-revisi') }}",
                        type: 'PUT',
                        data: {
                            "id_absensi": $('#id_absensi').text(),
                            rowdata
                        },
                        success: function(json) {
                            alert(json.message);
                            var rowIndex = table.row('.selected').index();
                            if (rowdata.keterangan.toLowerCase() === "alpha") {
                                rowdata["waktu-masuk"] = "-";
                                rowdata["waktu-keluar"] = "-";
                                rowdata["keterangan"] = "Alpha";
                            }
                            table.row(rowIndex).data(rowdata).draw(false);
                        },
                        error: function(xhr, status, error) {
                            alert(error);
                        }
                    });
                }
            });

        });

        function search() {
            const tanggal = $('#tanggal').val();
            $.ajax({
                type: "GET",
                url: "{{ route('data-revisi') }}",
                data: {
                    tanggal: tanggal
                },
                statusCode: {
                    202: function(data) {
                        alert("Libur : " + data.message);
                    },
                    204: function() {
                        alert("Data tidak ditemukan.");
                    }
                },
                success: function(data, status, xhr) {
                    if (xhr.status === 204 || xhr.status === 202) {
                        return;
                    }
                    $('#id_absensi').text(data.id_absensi);
                    table.clear();
                    data.masuk.forEach(function(item) {
                        const newRow = $("<tr>");
                        newRow.append(
                            $("<td>").text(item.id),
                            $("<td>").text(item.nama),
                            $("<td>").text(item.waktu_masuk),
                            $("<td>").text(item.waktu_keluar),
                            $("<td>")
                        );
                        table.row.add(newRow).draw();
                    });
                    data.izin.forEach(function(item) {
                        const newRow = $("<tr>");
                        newRow.append(
                            $("<td>").text(item.id),
                            $("<td>").text(item.nama),
                            $("<td>"),
                            $("<td>"),
                            $("<td>").text(item.keterangan)
                        );
                        table.row.add(newRow).draw();
                    });
                    data.alpha.forEach(function(item) {
                        const newRow = $("<tr>");
                        newRow.append(
                            $("<td>").text(item.id),
                            $("<td>").text(item.nama),
                            $("<td>").text("-"),
                            $("<td>").text("-"),
                            $("<td>").text("Alpha")
                        );
                        table.row.add(newRow).draw();
                    });
                },
                error: function(xhr, status, error) {

                }
            });
        }
    </script>
@endsection
