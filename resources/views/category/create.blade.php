<form id="form_category" class="form-horizontal" role="form" method="POST"
    action="{{ isset($category) ? url('category/' . $id) : url('category') }}">
    {!! csrf_field() !!}
    {!! isset($category) ? method_field('PUT') : '' !!}
    <div class="modal-content">
        <div class="modal-header border-0">
            <h5 class="modal-title">
                <span class="fw-mediumbold">
                    {{ isset($category) ? 'Edit' : 'Create' }}</span>
                <span class="fw-light">
                    Category
                </span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p class="small">Create a new or edit category using this form, make sure you fill them all</p>
            <div class="row top">
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Name</label>
                        <input id="name" type="text" class="form-control" placeholder="fill name" name="name"
                            value="{{ isset($category) ? $category->name : '' }}">
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
    jQuery('#form_category').validate({
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
            email: {
                required: true
            },
            password: {
                minlength: 8,
                required: true
            },
            role: {
                required: true
            },
            category: {
                required: true
            },
            password_again: {
                required: true,
                minlength: 8,
                equalTo: "#password"
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
                        category.fnReloadAjax();
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
