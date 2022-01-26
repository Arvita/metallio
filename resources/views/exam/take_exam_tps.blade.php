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
                        <a href="#">Exam</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-sm-9">
                    <div class="panel panel-default mt20" style="min-height: 360px;">
                        <div class="panel-body" style="word-break: break-word;">  
                            <h2>TPS </h2>                           
                            <h3>Kategori: </h3>
                            <span id="divCategory" class="badge badge-warning">
                            </span>
                            <br>
                            <h4>Pertanyaan: </h4>
                            <div id="divQuestion">
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="panel panel-default mt20" style="min-height: 360px;">
                        <div class="panel-body">
                            <div class="col-sm-12">
                                @foreach ($id_question as $key => $val)
                                    @if (array_key_exists($val->id, $arr_answer))
                                        <button type="button" id="{{ $key + 1 }}" class="btn btn-success page"
                                            style="width: 45px; margin: 2px;" value="{{ $val->id }}">
                                            {{ $key + 1 }}
                                        </button>

                                    @else
                                        <button type="button" id="{{ $key + 1 }}" class="btn btn-default page"
                                            style="width: 45px; margin: 2px;" value="{{ $val->id }}">
                                            {{ $key + 1 }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-sm-12 mt20">
                                <div class="alert alert-info text-center">
                                    <i class="fa fa-info-circle"></i>
                                    <strong>Duration: </strong> <strong class="timee"></strong>
                                </div>
                                {{-- <h2 class="timee" style="text-align: center;"></h2> --}}
                            </div>
                            <div class="col-sm-12 form-group" style="padding-bottom: 10px">
                                <span class="badge badge-success">Telah terisi</span>

                            </div>
                            <div class="col-sm-12 form-group" style="padding-bottom: 10px">
                                <span class="badge badge-info">Sedang dikerjakan</span>
                            </div>
                            <div class="col-sm-12 form-group" style="padding-bottom: 10px">
                                <span class="badge badge-count">Belum diisi</span>
                            </div>
                            <div class="col-sm-12 form-group">
                                <div class="alert alert-warning text-center">
                                    <i class="fa fa-info-circle"></i>
                                    <strong>Untuk mengakhiri ujian menuju ke soal terakhir, kemudian tekan
                                        "Finish".</strong>
                                </div>
                            </div>
                            <div class="col-sm-12 text-center">
                                {{-- <button class="btn btn-orange previous btn-block" onclick="previous()">Previous</button>
							<button class="btn btn-orange next btn-block" onclick="next()">Next</button> --}}
                                <button data-style="expand-left" style="width: 49%"
                                    class="btn btn-orange ladda-button previous" onclick="previous()">
                                    <i class="fa fa-arrow-circle-left"></i>
                                    <span class="ladda-label"> Previous </span>
                                    <span class="ladda-spinner"></span>
                                </button>
                                <button data-style="expand-right" style="width: 49%"
                                    class="btn btn-orange ladda-button next" onclick="next()">
                                    <span class="ladda-label"> Next </span>
                                    <i class="fa fa-arrow-circle-right"></i>
                                    <span class="ladda-spinner"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- !! MODAL !! -->
    <a hidden data-toggle="modal" class="btn btn-primary" role="button" href="#myModal2" id="btn_modal2">
    </a>
    <div id="myModal1" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-header stack0">
            <h3 class="text-center">Konfirmasi</h3>
        </div>
        <div class="modal-body text-center">
            <h5>
                Apakah anda telah selesai mengerjakan ujian TPS? silahkan klik <strong>next</strong> untuk mengakhiri ujian TPS
                ini dan melanjutkan ke ujian TPA berikutnya.
            </h5>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-default">
                Cancel
            </button>
            <button type="button" data-dismiss="modal" class="btn btn-primary" onclick="completed()">
                Next
            </button>
        </div>
    </div>
    <div id="myModal2" class="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-header stack0">
            <h3 class="text-center">Konfirmasi</h3>
        </div>
        <div class="modal-body text-center">
            <h5>
                Waktu telah berakhir, jawaban otomatis tersimpan! anda akan melanjutkan ke ujian TPA berikutnya.
            </h5>
        </div>
        <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-primary" onclick="completed()">
                Next
            </button>
        </div>
    </div>
    
    <input id="duration_tpa" type="text" value="{{ $exam->duration_tpa }} " hidden>

@endsection

@push('content-css')
    <link href="{{ asset('assets/js/plugin/iCheck/skins/all.css?v=0.9.1') }}" rel="stylesheet">
    <link href="{{ asset('assets/js/plugin/dropzone/downloads/css/basic.css') }}" rel="stylesheet">
    <style>
        .ck-editor__editable {
            min-height: 100px !important;
        }

    </style>
@endpush

@push('content-js')
    <script src="{{ asset('assets/js/plugin/dropzone/downloads/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/ckeditor5/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/iCheck/jquery.icheck.min.js?v=0.9.1') }}"></script>
    <script>
        let question = null;

        let arrFile = [];

        
        let duration_tpa = document.getElementById("duration_tpa").value;
        window.onerror = function(e) {
            if (e == "Uncaught TypeError: Cannot read property 'bytesSent' of undefined") {
                return true;
            }
        }

        function myStyle() {
            $('.square-orange').iCheck({
                checkboxClass: 'icheckbox_square-orange',
                radioClass: 'iradio_square-orange',
                increaseArea: '10%' // optional
            });
        }

        let currentAnswer;

        function submit_answer(answer, id_question, id_button) {
            if (currentAnswer != answer) {
                currentAnswer = answer

                $.post("{{ url('exam/submit_answer') }}", {
                    _token: "{{ csrf_token() }}",
                    id_exam: "{{ $exam->id }}",
                    id_question: id_question,
                    id_answer: answer
                }, function(data, textStatus, xhr) {

                    if (data != 0) {
                        $('button.page#' + id_button).addClass('btn-success');
                    }
                });
            }
        }

        function getQuestion() {
            $('button#' + question).addClass('btn-info');
            $('a.finish').remove();
            if (question == $('button.page').length) {
                $('.next').remove();
                $('.previous').after(
                    `<a class="btn btn-primary finish" data-toggle="modal" role="button" href="#myModal1" style="width: 100px; margin-left: 3px;">Finish</a>`
                );
            } else {
                if ($('a.finish')) {
                    $('a.finish').remove();
                }

                if ($('button.next').length == 0) {
                    $('.previous').after(
                        `<button class="btn btn-orange next" onclick="next()" style="width: 100px; margin-left: 3px;">Next</button>`
                    );
                }
            }

            $.ajax({
                url: `{{ url('/exam/getquestion') }}`,
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: $('button#' + question).val(),
                    id_exam: "{{ $exam->id }}"
                },
                success: function(data) {
                    let currentId = question;
                    $('div#divQuestion').html(data.question.question);
                    $('div#divQuestion').append(`
                		<div class="divAnswer">
                		<h4>Jawaban: </h4>
                		<table id="tableAnswer">
                		</table>
                		</div>
                		`);
                    $('span#divCategory').html(data.question.category);

                    $.each(data.answer, function(index, val) {
                        $('#tableAnswer').append(`
                			<tr>
                			<td class="text-center" style="width: 50px;">
                			<label class="radio-inline">
                			<input type="radio" class="square-orange" name="multiple_answer" value="` + val.id + `">
                			</label>
                			</td>
                			<td>
                			<div class="multiple_answer" id="` + val.id + `">
                			` + val.answer + `
                			</div>
                			</td>
                			</tr>
                			`);
                    });

                    if (data.answer_assessment) {
                        $('input[name=multiple_answer][value=' + data.answer_assessment.id_answer + ']')
                            .iCheck('check');
                    }

                    $('div.no-question').text('Soal ' + question);
                    myStyle();

                    $('input[name=multiple_answer]').on('ifChanged', function() {
                        submit_answer($(this).val(), data.question.id, currentId);
                    });

                    $('td.multiple_answer').on('click', function() {
                        $('input[name=multiple_answer][value=' + $(this).attr('id') + ']').iCheck(
                            'check');
                    });
                }
            })
        }

        function next() {

            if (question < $('button.page').length) {
                $('button#' + question).removeClass('btn-info');
                question++;
                getQuestion();
            }

        }

        function previous() {
            if (question > 1) {
                $('button#' + question).removeClass('btn-info');
                question--;
                getQuestion();
            }

        }
        function completed() {

            $.post("{{ url('exam/start_tpa') }}", {
                _token: "{{ csrf_token() }}",
                id: "{{ $exam->id }}",
                finish : moment().add(parseInt(duration_tpa), 'minutes').add(4, 'seconds').format(
            'YYYY-MM-DD, h:mm:ss A'),
                start : moment().format('YYYY-MM-DD, h:mm:ss A'),
            }, function(data, textStatus, xhr) {
                console.log(data)
                if (data != 0) {
                    window.location.href = "{{ url('exam/exam_tpa/') }}/"+data.id ;

                } else {
                    alert('Ada kesalahan!');
                }
            });

        }

        jQuery(document).ready(function($) {

            $('body').addClass('navigation-small footer-fixed');

            if (question == null) {
                question = 1;
                getQuestion();
            }

            $('button.page').on('click', function() {
                $('button#' + question).removeClass('btn-info');
                question = $(this).attr('id');
                getQuestion();
            });



            var timeInterval = setInterval(function() {
                let now = moment().format('YYYY-MM-DD, h:mm:ss A');
                let finish = moment("{{ $exam->finish_tps }}", 'YYYY-MM-DD, h:mm:ss A').format(
                    'YYYY-MM-DD, h:mm:ss A');

                let ms = moment(finish, 'YYYY-MM-DD, h:mm:ss A').diff(moment(now, 'YYYY-MM-DD, h:mm:ss A'));
                let d = moment.duration(ms);
                let s = Math.floor(d.asHours()) + moment.utc(ms).format(":mm:ss")


                if (Math.floor(parseInt(s)) < 0) {
                    $('.timee').text("0:00:00");

                    removeInterval();

                    $('a#btn_modal2').trigger('click');

                } else {

                    $('.timee').text(s);

                }

            }, 1000);

            function removeInterval() {
                clearInterval(timeInterval);
            }


        });
    </script>
@endpush
