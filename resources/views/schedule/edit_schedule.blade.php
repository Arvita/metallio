<form id="formUpdate" class="form-horizontal" role="form" method="POST" action="{{ url('schedule/update') }}">
    {!! csrf_field() !!}
    <div class="modal-content">
        <div class="modal-header border-0">
            <h5 class="modal-title">
                <span class="fw-mediumbold">
                    Edit</span>
                <span class="fw-light">
                    Schedule
                </span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p class="small">Create a new or edit schedule using this form, make sure you fill them all</p>
            <div class="row to">
                <div class="col-sm-12">
                    <input name="id" value="{{ $data->id }}" hidden>
                    <input name="id_exam" value="{{ $data->id_exam }}" hidden>
                    <div class="form-group form-group-default">
                        <label>Exam Name</label>
                        <input id="name" type="text" class="form-control" placeholder="fill name" name="name"
                            value="{{ $data->name }}" readonly>
                    </div>
                    <div class="form-group form-group-default">
                        <label>Type</label>
                        <input id="type" type="text" class="form-control" placeholder="fill type" name="type"
                            value="{{ $data->type }}" readonly>
                    </div>
                    <div class="form-group form-group-default">
                        <label>Duration</label>
                        <input id="duration" type="text" class="form-control" placeholder="fill duration"
                            name="duration" value="{{ $data->duration }}" readonly>
                    </div>
                    <div class="form-group form-group-default col-sm-12">
                        <label>
                            Opened
                        </label>
                        <div class="col-sm-12">
                            <div class="input-group ">
                                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years"
                                    class="start_date form-control date-picker-start" name="start_date"
                                    value="{{ isset($data->open) ? $data->open : '' }}" required id="start_date">
                                <span class="input-group-addon add-on" onclick="function run() {
                                    $('#start_date').focus();
                                }
                                run();
                                "><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="input-group input-append bootstrap-timepicker">
                                <input type="text" name="start_time" class="start_time form-control time-picker-start"
                                    value="{{ isset($data->open_time) ? $data->open_time : '' }}" required>
                                <span class="input-group-addon add-on" onclick="function run() {
                                    $('input[name=start_time]').focus();
                                }
                                run();
                                "><i class="fa fa-clock"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group form-group-default col-sm-12 finishExam">
                        <label>
                            Closed
                        </label>
                        <div class="col-sm-12">
                            <div class="input-group">
                                <input type="text" data-date-format="yyyy-mm-dd" data-date-viewmode="years"
                                    class="finish_date form-control date-picker-finish" name="finish_date"
                                    value="{{ isset($data->close) ? $data->close : '' }}" required>
                                <span class="input-group-addon" onclick="function run() {
                                    $('input[name=finish_date]').focus();
                                }
                                run();
                                "> <i class="fa fa-calendar"></i> </span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="input-group input-append bootstrap-timepicker">
                                <input type="text" name="finish_time"
                                    class="finish_time form-control time-picker-finish"
                                    value="{{ isset($data->close_time) ? $data->close_time : '' }}" required onclick="function run() {
                                    $('input[name=finish_time]').focus();
                                }
                                run();
                                ">
                                <span class="input-group-addon add-on"><i class="fa fa-clock"></i></span>
                            </div>
                        </div>

                    </div>
                    <div class="form-group form-group-default">
                        <label>
                            Status
                        </label>
                        <div class="make-switch" data-on="warning" data-off="danger">
                            <input type="checkbox" value="1" name="status" id="status" {!! isset($data->status) && $data->status == 0 ? '' : 'checked="checked" ' !!}>
                        </div>
                    </div>
                    <input type="text" name="open" class="open" hidden>
                    <input type="text" name="close" class="close" hidden>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" id="add" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
</form>
</div>
<script src="{{ asset('assets/js/plugin/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/js/plugin/bootstrap-datepicker/css/datepicker.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/plugin/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
<script src="{{ asset('assets/js/plugin/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/plugin/bootstrap-switch/static/js/bootstrap-switch.min.js') }}"></script>
<link rel="stylesheet"
    href="{{ asset('assets/js/plugin/bootstrap-switch/static/stylesheets/bootstrap-switch.css') }}">
<script type="text/javascript">
    $('.time-picker').timepicker();
    let time = true;

    var str = "{{ $data->open }}";
    var res = str.split(", ");

    var close = "{{ $data->close }}";
    var cls = close.split(", ");

    $('.start_date').val(res[0]);
    $('.start_time').val(res[1]);

    $('.finish_date').val(cls[0]);
    $('.finish_time').val(cls[1]);

    function getTime() {
        if (($('input[name="start_date"]').val() == '') || ($('input[name="start_time"]').val() == '')) {
            time = false;
            $('input[name="finish_date"]').attr('disabled', true);
            $('input[name="finish_time"]').attr('disabled', true);
            $('input[name="finish_date"]').val('');
            $('input[name="finish_time"]').val('');
        } else {
            $('input[name="finish_date"]').attr('disabled', false);
            $('input[name="finish_time"]').attr('disabled', false);
        }

        if ($('input[name="start_date"]').val() == '') {
            $('input[name="start_time"]').attr('disabled', true);
        } else {
            $('input[name="start_time"]').attr('disabled', false);
        }

        if ($('input[name="start_date"]').val() != '' && $('input[name="start_time"]').val() != '' && $(
                'input[name="finish_date"]').val() != '' && $('input[name="finish_time"]').val() != '') {

            let finish = '' + $('input[name="finish_date"]').val() + ', ' + $('input[name="finish_time"]').val();
            let start = '' + $('input[name="start_date"]').val() + ', ' + $('input[name="start_time"]').val();
            let duration = moment(finish, 'YYYY-MM-DD, h:mm A').diff(moment(start, 'YYYY-MM-DD, h:mm A'), 'hours');
            $('.open').val(start);
            $('.close').val(finish);
            if ($('.alert')) {
                $('.alert').hide('slow', function() {
                    $(this).remove();
                });
            }
            if (duration <= 0) {
                $('input[name="finish_time"]').val($('input[name="start_time"]').val());
                $('.modal-body').append(
                    '<div class="alert alert-danger mt20">Waktu dibuka minimal berdurasi 1 jam!</b></div>');
                $('#submit').hide();
            } else {
                time = true;
                $('#submit').show();
                $('.modal-body').append('<div class="alert alert-info mt20">Akan dibuka selama <b>' + duration +
                    ' jam.</b></div>');
            }
            $('.date-picker-finish').datepicker({
                autoclose: true,
                startDate: $('input[name="start_date"]').val()
            });

            $('.time-picker-finish').timepicker({
                minuteStep: 1,
                defaultTime: false
            });
        }

    }

    jQuery(document).ready(function($) {
        getTime();
        $('.date-picker-start').datepicker({
            autoclose: true,
            startDate: 'now'
        });
        $('.time-picker-start').timepicker({
            minuteStep: 1,
            defaultTime: false
        });

        $('input[name="start_time"]').on('change', function() {

            let examStartDate = moment($('input[name="start_date"]').val(), 'YYYY-MM-DD').format(
                'YYYY-MM-DD');
            $('.date-picker-finish').datepicker({
                autoclose: true,
                startDate: examStartDate
            });

            $('.time-picker-finish').timepicker({
                minuteStep: 1,
                defaultTime: false
            });
            getTime();

        });

        $('input[name="finish_time"]').on('change', function() {
            getTime()
        });

        $('input[name="start_date"]').on('change', function() {
            getTime()
        });
        $('input[name="finish_date"]').on('change', function() {
            getTime()
        });

        $('#formUpdate').validate({
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            errorPlacement: function(error, element) { // render error placement for each input type
                if (element.attr("type") == "radio" || element.attr("type") ==
                    "checkbox"
                ) { // for chosen elements, need to insert the error after the chosen container
                    error.insertAfter($(element).closest('.form-group').children('div').children()
                        .last());
                } else if (element.attr("name") == "dd" || element.attr("name") == "mm" || element
                    .attr("name") == "yyyy") {
                    error.insertAfter($(element).closest('.form-group').children('div'));
                } else {
                    error.insertAfter(element);
                }
            },
            ignore: "",
            rules: {
                name: {
                    minlength: 2,
                    required: true
                },
                duration: {
                    minlength: 1,
                    required: true
                },
                start_date: {
                    minlength: 2,
                    required: true
                },
                start_time: {
                    minlength: 2,
                    required: true
                },
                finish_date: {
                    minlength: 2,
                    required: true
                },
                finish_time: {
                    minlength: 2,
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
            },
            submitHandler: function(form) {

                blockTab(ajaxModal);
                jQuery(form).ajaxSubmit({
                    dataType: 'json',
                    success: function(data) {
                        var msg;
                        $('.alert.alert-success, .alert.alert-danger').remove();
                        unblockTab(ajaxModal);
                        if (data == 1) {
                            msg =
                                '<div class="alert alert-success"><i class="fa fa-check-circle"></i><strong> Berhasil !</strong> disimpan </div>';
                            $('.to').before(msg);
                            window.setTimeout(function() {
                                // ajaxModalConfirm.modal('hide');
                                // ajaxModalElement.modal('hide');
                                ajaxModal.modal('hide');
                                location.reload(true);
                            }, 1500);
                        } else {
                            msg =
                                '<div class="alert alert-danger"><i class="fa fa-check-circle"></i><strong> Kesalahan !</strong></div>';
                            $('.top').before(msg);
                        }
                    }
                });
            }
        });
    });
</script>
