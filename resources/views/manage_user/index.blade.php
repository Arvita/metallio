@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Manage User</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="{{ url('dashboard') }}">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Setting</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Management User</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <form action="{{ url('manage_user/import') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <input type="file" name="file" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
                                        <button class="btn btn-primary" type="submit" id="button-addon2">Import</button>
                                    </div>
                                </form>
                                <button data-url="{{ route('manage_user.create') }}"
                                    class="ajax_modal btn btn-primary btn-round ml-auto">
                                    <i class="fa fa-plus"></i>
                                    Add New
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="manage_user" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Category</th>
                                            <th>Update</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="ajax-modal" class="modal bs-example-modal-static" tabindex="-1" data-backdrop="static" data-keyboard="false"
        data-width="50%" style="display: none;"></div>
    <div id="ajax-modal-element" class="modal bs-example-modal-static" tabindex="-1" data-backdrop="static"
        data-keyboard="false" data-width="70%" style="display: none;"></div>
    <div id="ajax-modal-popup" class="modal bs-example-modal-static" tabindex="-1" data-backdrop="static"
        data-keyboard="false" data-width="520" style="display: none;"></div>
@endsection
@push('content-css')
@endpush
@push('content-js')
    <script type="text/javascript">
        var manage_user;
        var ajaxModal = $('#ajax-modal');
        var ajaxModalPopup = $('#ajax-modal-popup');
        var ajaxModalElement = $('#ajax-modal-element');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            manage_user = $('#manage_user').dataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": '{{ url('manage_user/data') }}',
                    "type": "POST"
                },
                "aoColumns": [{
                        "mData": function(data, type, dataToSet, meta, row) {
                            return (meta.row + meta.settings._iDisplayStart + 1);
                        },
                        "sWidth": "",
                        "sClass": "text-center",
                        "bSortable": false,
                        "bSearchable": false
                    },
                    {
                        "mData": "name",
                        "nama": "name",
                        "sWidth": "",
                        "sClass": "",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "email",
                        "name": "email",
                        "sWidth": "",
                        "sClass": "",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "role",
                        "name": "role",
                        "sWidth": "",
                        "sClass": "text-center",
                        "bSortable": false,
                        "bSearchable": false
                    },
                    {
                        "mData": "category",
                        "name": "category",
                        "sWidth": "",
                        "sClass": "text-center",
                        "bSortable": false,
                        "bSearchable": false
                    },
                    {
                        "mData": "updated_at",
                        "nama": "updated_at",
                        "sWidth": "",
                        "sClass": "",
                        "bSortable": true,
                        "bSearchable": false
                    },
                    {
                        "mData": "detail",
                        "sWidth": "",
                        "sClass": "text-center",
                        "bSortable": false,
                        "bSearchable": false
                    }
                ],
                "aoColumnDefs": [{
                        "aTargets": [3],
                        "mData": null,
                        "mRender": function(data, type, row) {
                            switch (row.role) {
                                case 0:
                                    return '<span class="badge badge-primary">Admin</span>';
                                    break;
                                case 1:
                                    return '<span class="badge badge-success">User</span>';
                                    break;
                            }
                        }
                    }, {
                        "aTargets": [4],
                        "mData": null,
                        "mRender": function(data, type, row) {
                            switch (row.category) {
                                case 0:
                                    return '<span class="badge badge-primary">Admin</span>';
                                    break;
                                case 1:
                                    return '<span class="badge badge-warning">TPS</span>';
                                    break;
                                case 2:
                                    return '<span class="badge badge-success">Bahasa Inggris</span>';
                                    break;
                                case 3:
                                    return '<span class="badge badge-info">TKA</span>';
                                    break;
                            }
                        }
                    }

                ],
            });
            
            $('div.dataTables_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    manage_user.fnFilter(this.value);
                } else {
                    if (this.value.length == 0) manage_user.fnFilter('');
                }
            });
        });
    </script>
@endpush
