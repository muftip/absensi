@extends('layouts.main')
@section('exclude_jquery', '')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css" />
    <style>
        .status-kirim:hover {
            color: rgb(93, 135, 255);
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="d-flex mb-3">
                <div class="card-title fw-semibold flex-grow-1">Edit Absensi</div>
            </div>
            @isset($libur)
                <h3 class="text-danger text-center">{{ $libur }}</h3>
            @endisset
            @isset($tutup)
                <div class="alert alert-danger mb-4">
                    {{ $tutup }}
                </div>
            @endisset
            <table cellpadding="0" cellspacing="0" border="0" class="dataTable table table-striped" id="myTable">
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
                    @isset($masuk)
                        @foreach ($masuk as $item)
                            <tr>
                                <td>{{ $item->id_karyawan }}</td>
                                <td>{{ $item->karyawan->nama }}</td>
                                <td>{{ $item->waktu_masuk }}</td>
                                <td>{{ $item->waktu_keluar }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endisset
                    @isset($izin)
                        @foreach ($izin as $item)
                            <tr>
                                <td>{{ $item->id_karyawan }}</td>
                                <td>{{ $item->karyawan->nama }}</td>
                                <td>-</td>
                                <td>-</td>
                                <td>{{ $item->keterangan }}</td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('extra_scripts')
    <style>
        .modal-header {
            padding: 15px;
            align-items: center;
            border-bottom: 1px solid #e5e5e5;
            display: block;
        }

        button.close {
            -webkit-appearance: none;
            padding: 0;
            cursor: pointer;
            background: transparent;
            border: 0;
            display: block;
            font-size: 22px;
        }

        .close {
            float: right;
            font-size: 21px;
            font-weight: bold;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            filter: alpha(opacity=20);
            opacity: .2;
        }
    </style>
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
    <script>
        $(document).ready(function() {
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
                name: "nama"
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

            var myTable = $('#myTable').DataTable({
                "sPaginationType": "full_numbers",
                // ajax: {
                //     url : '',
                //     // our data is an array of objects, in the root node instead of /data node, so we need 'dataSrc' parameter
                //     dataSrc : ''
                // },
                columns: columnDefs,
                dom: 'Bfrtip', // Needs button container
                select: 'single',
                responsive: true,
                altEditor: true, // Enable altEditor
                buttons: [{
                        extend: 'selected', // Bind to Selected row
                        text: 'Edit',
                        name: 'edit' // do not change name
                    },
                    {
                        extend: 'selected', // Bind to Selected row
                        text: 'Delete',
                        name: 'delete' // do not change name
                    }
                ],
                onDeleteRow: function(datatable, rowdata, success, error) {
                    $.ajax({
                        url: "/delete-absensi/" + rowdata[0][0],
                        type: 'DELETE',
                        success: function(json) {
                            alert(json.message);
                            myTable.row('.selected').remove().draw(false);
                        },
                        error: function(xhr, status, error) {
                            alert(error);
                        }
                    });
                },
                onEditRow: function(datatable, rowdata, success, error) {
                    $.ajax({
                        url: "{{ route('edit.update') }}",
                        type: 'PUT',
                        data: rowdata,
                        success: function(json) {
                            alert(json.message);
                            var rowIndex = myTable.row('.selected').index();
                            myTable.row(rowIndex).data(rowdata).draw(false);
                        },
                        error: function(xhr, status, error) {
                            alert(error);
                        }
                    });
                }
            });
        });
    </script>
@endsection
