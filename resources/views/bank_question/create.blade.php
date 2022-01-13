<form id="form_bank_question" class="form-horizontal" role="form" method="POST"
    action="{{ isset($bank_question) ? url('bank_question/' . $id) : url('bank_question') }}">
    {!! csrf_field() !!}
    {!! isset($bank_question) ? method_field('PUT') : '' !!}
    <div class="modal-content">
        <div class="modal-header border-0">
            <h5 class="modal-title">
                <span class="fw-mediumbold">
                    {{ isset($bank_question) ? 'Edit' : 'Create' }}</span>
                <span class="fw-light">
                    User
                </span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p class="small">Create a new or edit bank question using this form, make sure you fill them all</p>
            <div class="row top">
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Name</label>
                        <input id="name" type="text" class="form-control" placeholder="fill name" name="name"
                            value="{{ isset($bank_question) ? $bank_question->name : '' }}">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Category</label>
                        <select name="category" class="form-control" id="category">
                            @foreach ($category as $item)
                            <option value="{{ $item->id }}"
                                {{ isset($bank_question) && $bank_question->id_category == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                            @endforeach
                        </select>
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
    jQuery('#form_bank_question').validate({
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
            category: {
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
                        bank_question.fnReloadAjax();
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
