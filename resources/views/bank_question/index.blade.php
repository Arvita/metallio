@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Bank Question</h4>
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
                        <a href="#">Bank Question</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <button data-url="{{ route('bank_question.create') }}"
                                    class="ajax_modal btn btn-primary btn-round ml-auto">
                                    <i class="fa fa-plus"></i>
                                    Add New
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="bank_question" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Update</th>
                                            <th>Question</th>
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
        var bank_question;
        var ajaxModal = $('#ajax-modal');
        var ajaxModalPopup = $('#ajax-modal-popup');
        var ajaxModalElement = $('#ajax-modal-element');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            bank_question = $('#bank_question').dataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": '{{ url('bank_question/data') }}',
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
                        "mData": "action",
                        "name": "action",
                        "sWidth": "",
                        "sClass": "text-center",
                        "bSortable": false,
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
            });
            
            $('div.dataTables_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    bank_question.fnFilter(this.value);
                } else {
                    if (this.value.length == 0) bank_question.fnFilter('');
                }
            });
        });
    </script>
@endpush
