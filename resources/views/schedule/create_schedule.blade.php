<form id="form_schedule" class="form-horizontal" role="form" method="POST"
    action="{{ url('/schedule/create_schedule') }}">
    {!! csrf_field() !!}
    <div class="modal-content">
        <div class="modal-header border-0">
            <h5 class="modal-title">
                <span class="fw-mediumbold">
                    Create </span>
                <span class="fw-light">
                    Exam Schedule
                </span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body ">
            <p class="small">Create a new schedule exam using this form, make sure you fill them all</p>
            <div class="row top divCat">

                <div class="col-sm-6">
                    <div class="form-group form-group-default">
                        <label>Type</label>
                        <select name="type" class="form-control type" id="type">
                            @foreach ($type as $item)
                                <option value="{{ $item->id }}"
                                    {{ isset($schedule) && $schedule->id_type == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" id="add" class="btn btn-primary delete">Add</button>
                    <button type="button" class="btn btn-danger " data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
</form>
<script type="text/javascript">
    $('.delete').hide();
    $('#type').on('change', function() {

        $.ajax({
            url: `{{ url('/schedule/getexam/`+ $(this).val() +`') }}`,
            type: 'get',
            dataType: 'json',
            success: function(data) {
                console.log(data);
                if (data == "") {
                    $('.delete').hide();
                    if ($('.divExam')) {
                        $('.divExam').hide('slow', function() {
                            $(this).remove();
                        });
                    }
                    if ($('.alert-warning')) {
                        $('.alert-warning').hide('slow', function() {
                            $(this).remove();
                        });
                    }
                    $('.divCat').after(`
                        <div class="alert alert-block alert-warning fade in text-center mt20">
                            <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> Warning!</h4>
                            <p>
                                the exams in this scheme are not available
                            </p>
                        </div>
                        `);
                } else {
                    $('.delete').show();
                    if ($('.divExam')) {
                        $('.divExam').hide('slow', function() {
                            $(this).remove();
                        });
                    }
                    if ($('.alert-warning')) {
                        $('.alert-warning').hide('slow', function() {
                            $(this).remove();
                        });
                    }
                    $('.divCat').after(`
                            <div class="form-group form-group-default divExam">
                                    Exam <span class="symbol required"></span>
                                </label>
                                <div class="col-sm-12">
                                    <select name="exam" class="exam form-control" id="exam" required>
                                        <option value="" disabled selected> - Exam - </option>
                                    </select>
                                </div>
                            </div>
                    `);
                    $.each(data, function(index, val) {
                        // console.log(val)
                        $('select[name=exam]').append('<option value="' + val.id + '">' +
                            val.name + '</option>');
                    })
                }
            }
        })

        jQuery('#form_schedule').validate({
            ignore: "",
            rules: {
                type: {
                    required: true
                },
                exam: {
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
    });
</script>
