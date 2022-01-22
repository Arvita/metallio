@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Exam</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="{{ url('home') }}">
                            <i class="flaticon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Quiz Setting</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Result</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div>SAINTEK</div>
                                <a href="{{ url('result/saintek_pdf') }}" class="btn btn-primary btn-round ml-auto" target="_blank">
                                    <i class="far fa-file-pdf"></i>  Export
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="result_saintek" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div>SOSHUM</div>
                                <a href="{{ url('result/soshum_pdf') }}" class="btn btn-primary btn-round ml-auto" target="_blank">
                                    <i class="far fa-file-pdf"></i>
                                    Export
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="result_soshum" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Score</th>
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
        var result_saintek;
        var ajaxModal = $('#ajax-modal');
        var ajaxModalPopup = $('#ajax-modal-popup');
        var ajaxModalElement = $('#ajax-modal-element');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            result_saintek = $('#result_saintek').dataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": '{{ url('result/data_saintek') }}',
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
                        "mData": "score",
                        "nama": "score",
                        "sWidth": "",
                        "sClass": "",
                        "bSortable": true,
                        "bSearchable": true
                    }
                ],
            });

            $('div.dataTables_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    result_saintek.fnFilter(this.value);
                } else {
                    if (this.value.length == 0) result_saintek.fnFilter('');
                }
            });
        });

        $(document).ready(function() {
            result_soshum = $('#data_soshum').dataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": '{{ url('result/data_soshum') }}',
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
                        "mData": "score",
                        "nama": "score",
                        "sWidth": "",
                        "sClass": "",
                        "bSortable": true,
                        "bSearchable": true
                    }
                ],
            });

            $('div.dataTables_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    result_saintek.fnFilter(this.value);
                } else {
                    if (this.value.length == 0) result_saintek.fnFilter('');
                }
            });
        });
    </script>
@endpush
