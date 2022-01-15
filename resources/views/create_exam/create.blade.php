<form id="form_create_exam" class="form-horizontal" role="form" method="POST"
    action="{{ isset($create_exam) ? url('create_exam/' . $id) : url('create_exam') }}">
    {!! csrf_field() !!}
    {!! isset($create_exam) ? method_field('PUT') : '' !!}
    <div class="modal-content">
        <div class="modal-header border-0">
            <h5 class="modal-title">
                <span class="fw-mediumbold">
                    {{ isset($create_exam) ? 'Edit' : 'Create' }}</span>
                <span class="fw-light">
                    Exam
                </span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p class="small">Create a new or edit exam using this form, make sure you fill them all</p>
            <div class="row top">
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Name</label>
                        <input id="name" type="text" class="form-control" placeholder="fill name" name="name"
                            value="{{ isset($create_exam) ? $create_exam->name : '' }}">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group form-group-default">
                        <label>Duration</label>
                        <input id="duration" type="text" class="form-control" placeholder="fill duration" name="duration"
                            value="{{ isset($create_exam) ? $create_exam->duration : '' }}"> Minutes
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" id="add" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
</form>
</div>
<script type="text/javascript">
    jQuery('#form_create_exam').validate({
        errorElement: "span", // contain the error msg in a span tag
        errorClass: 'help-block',
        errorPlacement: function(error, element) { // render error placement for each input type
            if (element.attr("type") == "radio" || element.attr("type") ==
                "checkbox") { // for chosen elements, need to insert the error after the chosen container
                error.insertAfter($(element).closest('.form-group').children('div').children().last());
            } else if (element.attr("name") == "dd" || element.attr("name") == "mm" || element.attr(
                    "name") == "yyyy") {
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
            name: {
                required: true
            }
        },

        highlight: function(element) {
            $(element).closest('.help-block').removeClass('valid');
            $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find(
                '.symbol').removeClass('ok').addClass('required');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        success: function(label, element) {
            label.addClass('help-block valid');
            $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find(
                '.symbol').removeClass('required').addClass('ok');
        },

        submitHandler: function(form) {
            blockTab(ajaxModal);
            jQuery(form).ajaxSubmit({
                dataType: 'json',
                success: function(data) {
                    var msg;
                    $('.alert.alert-success, .alert.alert-danger').remove();
                    unblockTab(ajaxModal);
                    if (data.stat) {
                        msg =
                            '<div class="alert alert-success"><i class="fa fa-check-circle"></i><strong> Success !</strong> ' +
                            data.msg + '</div>';
                        $('.top').before(msg);
                        create_exam.fnReloadAjax();
                        window.setTimeout(function() {
                            ajaxModal.modal('hide');
                        }, 1500);
                    } else {
                        msg =
                            '<div class="alert alert-danger"><i class="fa fa-check-circle"></i><strong> Failed !</strong> ' +
                            data.msg + '</div>';
                        $('.top').before(msg);
                    }
                }
            });
        }
    });
</script>
