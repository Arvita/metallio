@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Detail Bank Question</h4>
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
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Detail Bank Question</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <table style="color: #707070;"
                                            class="table table-bordered table-hover standard-padding mt20">
                                            <tr>
                                                <th style="width: 25%;">Bank</th>
                                                <th>{{ $bank_question->name }}</th>
                                            </tr>
                                            <tr>
                                                <th style="width: 25%;">Category</th>
                                                <th>{{ $bank_question->category }}</th>
                                            </tr>
                                            <tr>
                                                <th style="width: 25%;">Total Question</th>
                                                <th>{{ $countQuestion }}</th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-sm-6">
                                <a href="{{ url('detail_bank_question/create/' . $bank_question->id) }}"
                                    class=" btn btn-primary btn-round ">
                                    <i class="fa fa-plus"></i>
                                    Add Question
                                </a>
                                <button
                                    data-url="{{ url('detail_bank_question/confirm/select_delete/' . $bank_question->id) }}"
                                    class="ajax_modal btn btn-primary btn-round ">
                                    <i class="fa fa-trash-o"></i>
                                    Delete All
                                </button>
                            </div>
                            <div class="col-sm-12 mt20">
                                <hr>
                                <table width="100%" id="list_question"
                                    class="table table-striped table-bordered table-hover table-full-width">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Detail</th>
                                            <th class="text-center">Action</th>
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
            <div id="ajax-modal" class="modal bs-example-modal-static" tabindex="-1" data-backdrop="static"
                data-keyboard="false" data-width="50%" style="display: none;"></div>
            <div id="ajax-modal-element" class="modal bs-example-modal-static" tabindex="-1" data-backdrop="static"
                data-keyboard="false" data-width="70%" style="display: none;"></div>
            <div id="ajax-modal-popup" class="modal bs-example-modal-static" tabindex="-1" data-backdrop="static"
                data-keyboard="false" data-width="520" style="display: none;"></div>
        @endsection
        @push('content-css')
            <link rel="stylesheet" href="{{ asset('assets/js/plugin/accordion/jquery-ui.min.css') }}">
            <style>
                .ui-state-active,
                .ui-widget-content .ui-state-active,
                .ui-widget-header .ui-state-active,
                a.ui-button:active,
                .ui-button:active,
                .ui-button.ui-state-active:hover {
                    border-top-color: #e6674a;
                    border-right-color: #e6674a;
                    border-bottom-color: #e6674a;
                    border-left-color: #e6674a;
                    background-image: initial;
                    background-color: #e35434;
                    color: rgb(232, 230, 227);
                }

                .fa-plus {
                    color: #ffff;
                }

                .addQuestion:hover {
                    background-color: #ffff;
                }

            </style>
        @endpush
        @push('content-js')
        <script src="{{ asset('assets/js/plugin/accordion/jquery-ui.min.js') }}"></script>
            <script type="text/javascript">
                var detail_bank_question;
                var ajaxModal = $('#ajax-modal');
                var ajaxModalPopup = $('#ajax-modal-popup');
                var ajaxModalElement = $('#ajax-modal-element');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                function getAnswer(type, id) {
                    if ($('#answer' + id)) {
                        $('#answer' + id).hide('400', function() {
                            $(this).remove();
                        });
                    }
                    if (!$('#answer' + id).length) {
                        if (type == '1' || type == '3' || type == '2') {
                            $.ajax({
                                url: `{{ url('detail_bank_question/getanswer/`+  id +`') }}`,
                                type: 'GET',
                                dataType: 'json',
                                success: function(data) {
                                    $('#question' + id).append('<ul id="answer' + id +
                                        '" class="list-group list-group-flush mt20"></ul>');
                                    $.each(data, function(index, val) {
                                        if (val.status == '1') {
                                            $('#answer' + id).append(
                                                '<li class="list-group-item"><i class="fa fa-check mb20" style="color:green; float: right;"></i>' +
                                                val.answer + '</li>');
                                        } else {
                                            $('#answer' + id).append('<li class="list-group-item">' + val
                                                .answer + '</li>');
                                        }
                                    });
                                }
                            })

                        }
                    }
                }

                jQuery(document).ready(function($) {
                    $('#accordion').accordion();
                    datatablesquestion = $('#list_question').dataTable({
                        "drawCallback": function() {
                            $('.accordion').accordion({
                                active: false,
                                collapsible: true,
                                heightStyle: 'panel',
                                clearStyle: true,
                                autoHeight: false,
                            });
                        },
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "{{ url('detail_bank_question/getallquestion/' . $bank_question->id) }}",
                            "type": "GET",
                            dataType: 'json',
                        },
                        "aoColumns": [

                            {
                                "mData": function(data, type, dataToSet, meta, row) {
                                    let typeQuestion = 'multiple choice';

                                    return `
                                        <h3>Soal Ke ` + (meta.row + meta.settings._iDisplayStart + 1) + ` - ` + data
                                        .category +
                                        ` <span style="float:right;">` + typeQuestion + `  </span></h3>
                                        <div id="question` + data.id + `">
                                        <table style="color: #707070; width: 50%; font-size:12px;" class="table table-sm table-hover mb20">
                                        <tr>
                                        <th style="width: 25%;">Detail Skema</th>
                                        <td>` + data.detailscheme + `</td>
                                        </tr>
                                        <tr>
                                        <th style="width: 25%;">No KUK</th>
                                        <td>` + data.no_kuk + `</td>
                                        </tr>
                                        <tr>
                                        <th style="width: 25%;">Elemen</th>
                                        <th>` + data.element + `</th>
                                        </tr>
                                        <tr>
                                        <th style="width: 25%;">Kategori</th>
                                        <td>` + data.category + `</td>
                                        </tr>
                                        </table>
                                        <div class="question mt20" style="word-break: break-word;">
                                        <p style="color: #707070; text-align:center;"><i>Soal</i></p>

                                        ` + data.question + `<button type="button" onclick="getAnswer(` + data.tipe +
                                        `,` + data.id + `);" class="btn-xs btn-orange">Lihat Jawaban</button>
                                        </div>
                                        <div>
                                        `;
                                },

                                "sWidth": "90%",
                                "sClass": "accordion",
                                "bSortable": false,
                                "bSearchable": false
                            },
                            {
                                "mData": "detail",
                                "bVisible": true,
                                "bSearchable": true
                            },
                            {
                                "mData": "question",
                                "bVisible": false,
                                "bSearchable": true
                            },
                            {
                                "mData": "category",
                                "bVisible": false,
                                "bSearchable": true
                            }

                        ],

                    });

                    $('div.dataTables_filter input').unbind().bind('keyup', function(e) {
                        $('.accordion').accordion({
                            active: false,
                            collapsible: true
                        });
                        if (e.keyCode == 13) {
                            datatablesquestion.fnFilter(this.value);
                        } else {
                            if (this.value.length == 0) datatablesquestion.fnFilter('');
                        }
                    });


                });
            </script>
        @endpush
