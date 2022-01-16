@extends('layouts.template')

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Detail Exam</h4>
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
                        <a href="#">Exam</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Detail Exam</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <table style="color: #707070;" class="table table-bordered table-hover standard-padding mt20">
                                <tr>
                                    <th style="width: 25%;">Nama Ujian</th>
                                    <th>{{ $exam->name }}</th>
                                </tr>
                                <tr>
                                    <th style="width: 25%;">Bank Question</th>
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
                        <div class="col-sm-12">
                            <hr>
                            <div class="row contentData">
                                <form action="" method="post" id="formCreateExam">
                                    <div class="form-group col-sm-12">
                                        <label class="control-label" style="float: left;">
                                            <span class="symbol">Total Questions</span>
                                        </label>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="number" class="form-control" aria-label=""
                                                    aria-describedby="basic-addon1" tabindex="1" id="totalQuestionExam"
                                                    name="totalQuestionExam" min="0">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-secondary btn-border" type="button"
                                                        id="categoryButton" onclick="getQuestion()">Create</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="inputDetailCategory"></div>


                                    </div>
                            </div>
                            </form>
                            <form action="{{ url('/create_exam/submit') }}" id="formGenerate" method="post">
                                @csrf
                                <input type="text" name="_id_bank" value="{{ $bank_question->id }}" hidden>
                                <input type="text" name="_id_exam" value="{{ $exam->id }}" hidden>
                                <div class="contentCategory">

                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="datatables mt20">

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
        <link href="{{ asset('assets/js/plugin/iCheck/skins/all.css?v=0.9.1') }}" rel="stylesheet">
        <link rel="stylesheet"
            href="{{ asset('assets/js/plugin/bootstrap-switch/static/stylesheets/bootstrap-switch.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/js/plugin/jquery-duration-picker-master/duration-picker.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/js/plugin/accordion/jquery-ui.min.css') }}">
        <style>
            .ui-state-active,
            .ui-widget-content .ui-state-active,
            .ui-widget-header .ui-state-active,
            a.ui-button:active,
            .ui-button:active,
            .ui-button.ui-state-active:hover {
                border-top-color: #2A2F5B;
                border-right-color: #2A2F5B;
                border-bottom-color: #2A2F5B;
                border-left-color: #2A2F5B;
                background-image: initial;
                background-color: #2A2F5B;
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
        <script src="{{ asset('assets/js/plugin/iCheck/jquery.icheck.min.js?v=0.9.1') }}"></script>
        <script src="{{ asset('assets/js/plugin/bootstrap-switch/static/js/bootstrap-switch.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugin/ckeditor4/ckeditor.js') }}"></script>
        <script src="{{ asset('assets/js/plugin/jquery-duration-picker-master/duration-picker.min.js') }}"></script>
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

            function myStyle() {
                $("select").select2({
                    allowClear: true
                });
            }

            let data = {{ $countQuestion }};
            let dataQuestion;

            function getQuestion() {
                if ($('#list_question').length) {
                    $('#list_question').hide('slow', function() {
                        $(this).remove();
                    });
                }

                if ($('.divPicker').length) {
                    $('.divPicker').hide('slow', function() {
                        $(this).remove();
                    });
                }
                if (parseInt($('#totalQuestionExam').val(), 10) > 0 && parseInt($('#totalQuestionExam').val(), 10) <= data) {

                    $('.inputDetailCategory').html(`
				<button onclick="submitdata()" class="btn-xs btn-primary btn-round  mt20 submit  col-sm-5">Save</i></button>
				<div class="notif col-sm-5"></div>
				`);
                    $('.contentCategory').append(`
                    <input type="text" name="total" value="` + parseInt($('#totalQuestionExam').val(), 10) + `" hidden>
				`);


                    $('.datatables').html(`<table width="100%" id="list_question"
                                    class="table table-striped table-bordered table-hover table-full-width">
				<thead>
				<tr>
				<th class="text-center">Detail</th>
                <th class="text-center">Score</th>
				</tr>
				</thead>
				<tbody>
				</tbody>
				</table>`);
                    $.ajax({
                        url: '{{ url('/detail_create_exam/generate') }}',
                        type: 'POST',
                        dataType: 'json',
                        data: $('#formGenerate').serialize(),
                        success: function(response) {
                            let response2 = [].concat.apply([], response);
                            dataQuestion = response2;

                            // ===============CREATE DATATABLES================

                            detail_bank_question = $('#list_question').DataTable({
                                "drawCallback": function() {
                                    $(".trackInput").on("change", function() {
                                        var $row = $(this).parents("tr");
                                        var rowData = detail_bank_question.row($row).data();
                                        rowData.score = $(this).val();
                                    });
                                    $('.accordion').accordion({
                                        active: false,
                                        collapsible: true,
                                        heightStyle: 'panel',
                                        clearStyle: true,
                                        autoHeight: false,
                                    });
                                },
                                data: response2,
                                columns: [

                                    {
                                        "mData": function(data, type, dataToSet, meta, row) {

                                            let typeQuestion = 'multiple choice';
                                            return `
                                        <h3>Question - ` + (meta.row + meta.settings._iDisplayStart + 1) +
                                                ` <span style="float:right;">` + typeQuestion + `  </span></h3>
                                        <div id="question` + data.id + `">
                                        <div class="question mt20" style="word-break: break-word;">
                                        <p style="color: #707070; text-align:center;"><i>Question</i></p>
                                        <div class="col-sm-12"> 
                                        ` + data.question + `</div><br><div class="col-sm-12"><button type="button" onclick="getAnswer('` +
                                                data.id + `')" class="btn-xs btn-primary btn-round">Look Answer</button></div><br>
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
                                        "targets": -1,
                                        "data": "score",
                                        "defaultContent": `<input class="form-control trackInput" type="text" value="0">`
                                    }

                                ],

                            });
                            
                            
                            $('div.dataTables_filter input').unbind().bind('keyup', function(e) {
                                if (e.keyCode == 13) {
                                    detail_bank_question.fnFilter(this.value);
                                } else {
                                    if (this.value.length == 0) detail_bank_question.fnFilter('');
                                }
                            });
                        }
                    });
                }
            }

            function getAnswer(id) {
                if ($('#answer' + id)) {
                    $('#answer' + id).hide('400', function() {
                        $(this).remove();
                    });
                }
                if (!$('#answer' + id).length) {
                    $.ajax({
                        url: `{{ url('detail_create_exam/getanswer/`+  id +`') }}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#question' + id).append('<ul id="answer' + id +
                                '" class="list-group mt20"></ul>');
                            $.each(data, function(index, val) {
                                if (val.status == '1') {
                                    $('#answer' + id).append(
                                        '<li class="list-group-item">' +
                                        val.answer +
                                        '<i class="fa fa-check mb20" style="color:green; padding-left:10px"></i></li>'
                                    );
                                } else {
                                    $('#answer' + id).append('<li class="list-group-item">' + val
                                        .answer + '</li>');
                                }
                            });
                        }
                    })
                }
            }


            function submitdata() {
                $('.notif').append(
                    `<div class="alert alert-info alertquestion mt20"><i class="fa fa-check"></i><strong> Data Akan Disimpan!</strong></div>`
                );
                setTimeout(function() {
                    $.ajax({
                        url: `{{ url('/detail_create_exam/submit') }}`,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            _token: "{{ csrf_token() }}",
                            data_question: dataQuestion,
                            id_exam: "{{ $exam->id }}",
                            id_bank: "{{ $bank_question->id }}"
                        },
                        success: function(data) {
                            if (data.stat) {
                                msg =
                                    '<div class="alert alert-success"><i class="fa fa-check-circle"></i><strong> Berhasil !</strong> ' +
                                    data.msg + '</div>';
                                $('.notif').append(msg);
                                window.setTimeout(function() {
                                    window.location.href =
                                        `{{ url('/detail_create_exam/' . $exam->id . '/detail') }}`;
                                }, 500);
                            } else {
                                msg =
                                    '<div class="alert alert-danger"><i class="fa fa-check-circle"></i><strong> Kesalahan !</strong> ' +
                                    data.msg + '</div>';
                                $('.notif').append(msg);
                            }
                        }

                    })
                }, 1000);

            }

            jQuery(document).ready(function($) {
                myStyle();
                // $('#list_question').hide();
                // tables();
                //============= type change =========

                $('#formCreateExam').on('submit', function(e) {
                    e.preventDefault();
                });
                $('#formGenerate').on('submit', function(e) {
                    if ($('.inputDetailCategory')) {
                        $('.inputDetailCategory').hide('400', function() {
                            $(this).remove();
                        });
                    }
                    e.preventDefault();
                });
                jQuery('#formCreateExam').validate({
                    ignore: "",
                    rules: {
                        exam_name: {
                            required: true
                        },
                        totalQuestionExam: {
                            required: true
                        }
                    },
                    highlight: function(element) {
                        $(element).closest('.help-block').removeClass('valid');
                        $(element).closest('.form-group').removeClass('has-success').addClass('has-error')
                            .find('.symbol').removeClass('ok').addClass('required');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('has-error');
                    },
                    success: function(label, element) {
                        label.addClass('help-block valid');
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success')
                            .find('.symbol').removeClass('required').addClass('ok');
                    }
                });
                jQuery('#formGenerate').validate({
                    ignore: "",
                    rules: {},
                    highlight: function(element) {
                        $(element).closest('.help-block').removeClass('valid');
                        $(element).closest('.form-group').removeClass('has-success').addClass('has-error')
                            .find('.symbol').removeClass('ok').addClass('required');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('has-error');
                    },
                    success: function(label, element) {
                        label.addClass('help-block valid');
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success')
                            .find('.symbol').removeClass('required').addClass('ok');
                    }
                });
            });
        </script>
    @endpush
