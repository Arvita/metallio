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

                        <form method="post" id="inputMultipleQuestion" enctype="multipart/formdata"
                            action="{{ url('/question_bank/question_submit/') }}">
                            <div class="card-header">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-sm-12 questiondiv mt20">
                                            <center>
                                                <label for="number" class="label-control"
                                                    style="color: #707070; font-weight: bold;"> Bagian Soal</label>
                                            </center>
                                            <div class="question mt20 mb20">
                                                <textarea name="question" id="question" cols="30" rows="10"></textarea>
                                            </div>
                                            <center>
                                                <label for="number" class="label-control"
                                                    style="color: #707070; font-weight: bold;"> - Bagian Jawaban - </label>
                                            </center>
                                            <button type="button" id="lockAndAddAnswer" onclick="getAnswer()" da
                                                class="btn btn-orange mt20">Lock <i class="fa fa-lock"></i></button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class=card-body>
                                <a href="{{ url('bank_question/question/' . $id) }}"
                                    class="btn btn-warning mt20">Cancel</a>
                                <button type="button" onclick="submitForm()" da class="btn btn-primary mt20">Save</button>
                            </div>
                        </form>
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
            <link rel="stylesheet"
                href="{{ asset('assets/js/plugin/jquery-duration-picker-master/duration-picker.css') }}">
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
                    $('.square-orange').iCheck({
                        checkboxClass: 'icheckbox_square-orange',
                        radioClass: 'iradio_square-orange',
                        increaseArea: '10%' // optional
                    });
                }

                function myFunc() {
                    CKEDITOR.replace("question", {
                        filebrowserUploadUrl: "{{ route('file.upload', ['_token' => csrf_token()]) }}",
                        filebrowserUploadMethod: 'form'
                    });
                    CKEDITOR.config.language = 'id';
                    CKEDITOR.config.uiColor = 'lightgrey';
                    CKEDITOR.config.height = 60;
                    CKEDITOR.config.toolbarCanCollapse = true;
                }


                function defineFirstAnswerAndHide() {
                    $('.btn-footer').hide();
                }

                function getAnswer() {
                    $('.alert').remove();
                    if ($('.alertquestion')) {
                        $('.alertquestion').remove();
                    }
                    if (CKEDITOR.instances['question'].getData()) {

                        if ($('.preview_question')) {
                            $('.preview_question').remove();
                            $('#editQuestion').remove();
                        }

                        if ($('#lockQuestion')) {
                            $('#lockQuestion').remove();
                        }

                        if ($('#lockAndAddAnswer')) {
                            $('#lockAndAddAnswer').remove();
                        }

                        if ($('#addAnswer')) {
                            $('#addAnswer').remove();
                        }

                        $('.question').hide('slow');
                        $('.question').after('<div class="preview_question mt20" style="word-wrap: break-word;">' + CKEDITOR
                            .instances['question'].getData() + '</div>');
                        $('.preview_question').after(
                            '<button type="button" id="editQuestion" onclick="edit_question()" da class="btn btn-orange mt20">Edit <i class="fa fa-pencil"></i></button>'
                        );
                        $('.questiondiv').append(
                            '<button type="button" id="addAnswer" onclick="answer()" da class="btn btn-orange mt20">Add Answer <i class="fa fa-plus"></i></button>'
                        );
                    } else {
                        if ($('.alertquestion')) {
                            $('.alertquestion').remove();
                        }
                        $('#question').before(
                            '<div class="alert alert-danger alertquestion mt20"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> Isi Pertanyaan !</div>'
                        );
                    }
                }

                function edit_question() {
                    $('.preview_question').remove();
                    $('#editQuestion').remove();
                    $('.question').show('slow');
                    $('.question').after(
                        '<button type="button" id="lockQuestion" onclick="getAnswer()" da class="btn btn-orange mt20">Lock <i class="fa fa-lock"></i></button>'
                    );
                }

                let arrAnswer = [];

                function answer() {

                    let i = Math.floor(Math.random() * 10000);
                    $('#addAnswer').before(`
    <div class="answer` + i + ` mt20">
    <label class="radio-inline">
    <input type="radio" class="square-orange" name="correct" value="` + i + `"> <label for="number" class="label-control" style="color: #707070; font-weight: bold;">- Jawaban Benar - </label>
    </label>
    <button type="button" id="removeAnswer" onclick="remove(` + i + `)" da value="` + i +
                        `" class="btn btn-dark btn-md remove` + i + `" style="float:right"><i class="fa fa-trash-o"></i></button>
    <textarea name="answer` + i + `" id="answer` + i + `" cols="30" rows="10" required></textarea>
    </div>
    `);

                    CKEDITOR.replace("answer" + i, {
                        filebrowserUploadUrl: "{{ route('file.upload', ['_token' => csrf_token()]) }}",
                        filebrowserUploadMethod: 'form'
                    });
                    CKEDITOR.config.language = 'id';
                    CKEDITOR.config.uiColor = 'lightgrey';
                    CKEDITOR.config.height = 60;
                    CKEDITOR.config.toolbarCanCollapse = true;
                    myStyle()
                    arrAnswer.push(i)


                }

                $('.square-blue').iCheck({
                    checkboxClass: 'icheckbox_square-orange',
                    radioClass: 'iradio_square-orange',
                    increaseArea: '10%' // optional
                });


                function remove(value) {
                    if (value > -1) {
                        arrAnswer.splice(arrAnswer.indexOf(value), 1);
                    }
                    $('.answer' + value).remove();

                }

                function submitForm() {
                    if ($('.alertquestion')) {
                        $('.alertquestion').remove();
                    }
                    if ($('.alertanswer')) {
                        $('.alertanswer').remove();
                    }
                    let correct = true;
                    if (CKEDITOR.instances['question'].getData()) {

                        $('.alert').remove();
                        if (arrAnswer.length >= 2) {
                            $.each(arrAnswer, function(index, val) {
                                /* iterate through array or object */
                                if (!CKEDITOR.instances['answer' + val].getData()) {
                                    correct = false;
                                    return $('#answer' + val).before(
                                        '<div class="alert alert-danger alertanswer mt20"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> Isi Jawaban !</div>'
                                    );
                                }


                            });

                            if ($('input[name=correct]:checked').val() && correct == true) {
                                $('#addAnswer').before(
                                    '<div class="alert alert-info alertquestion mt20"><i class="fa fa-check"></i><strong> Data Akan Disimpan!</strong></div>'
                                );
                                $('input[name="_answer"]').val(arrAnswer.toString());
                                try {
                                    setTimeout(function() {
                                        setTimeout(function() {
                                            $('#inputMultipleQuestion').submit();
                                        }, 500);
                                        $('#addAnswer').before(
                                            '<div class="alert alert-success alertquestion mt20"><i class="fa fa-check"></i><strong> Tersimpan!</strong></div>'
                                        );
                                    }, 1000);
                                } catch (e) {
                                    setTimeout(function() {
                                        setTimeout(function() {
                                            $('#inputMultipleQuestion').submit();
                                        }, 500);
                                        $('#addAnswer').before(
                                            '<div class="alert alert-danger alertquestion mt20"><i class="fa fa-times-circle"></i><strong> Kesalahan!</strong></div>'
                                        );
                                    }, 1000);
                                }

                            } else {
                                $('#addAnswer').before(
                                    '<div class="alert alert-danger alertquestion mt20"><i class="fa fa-times-circle"></i><strong> Silahkan pilih jawaban yang benar!</strong></div>'
                                );
                            }
                        } else {
                            $('#addAnswer').before(
                                '<div class="alert alert-danger alertquestion mt20"><i class="fa fa-times-circle"></i><strong> Isi jawaban minimal 2!</strong></div>'
                            );
                        }

                    } else {
                        $('#question').before(
                            '<div class="alert alert-danger alertquestion mt20"><button data-dismiss="alert" class="close">×</button><i class="fa fa-times-circle"></i> Isi Pertanyaan !</div>'
                        );
                    }
                }

                jQuery(document).ready(function($) {

                    myFunc()
                    defineFirstAnswerAndHide()
                    myStyle()

                });
            </script>
        @endpush
