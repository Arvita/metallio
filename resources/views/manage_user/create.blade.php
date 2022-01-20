<form id="form_manage_user" class="form-horizontal" role="form" method="POST"
    action="{{ isset($manage_user) ? url('manage_user/' . $id) : url('manage_user') }}">
    {!! csrf_field() !!}
    {!! isset($manage_user) ? method_field('PUT') : '' !!}
    <div class="modal-content">
        <div class="modal-header border-0">
            <h5 class="modal-title">
                <span class="fw-mediumbold">
                    {{ isset($manage_user) ? 'Edit' : 'Create' }}</span>
                <span class="fw-light">
                    User
                </span>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <p class="small">Create a new or edit user using this form, make sure you fill them all</p>
            <div class="row top">
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Name</label>
                        <input id="name" type="text" class="form-control" placeholder="fill name" name="name"
                            value="{{ isset($manage_user) ? $manage_user->name : '' }}">
                    </div>
                </div>
                @if (isset($manage_user))
                    <div class="col-sm-12">
                        <div class="form-group form-group-default">
                            <label>Password</label>
                            <input id="passwordupdate" type="password" class="form-control"
                                placeholder="fill password" name="passwordupdate">
                            <span class="help-block"><i class="fa fa-info-circle"></i> Change password.</span>
                        </div>
                    </div>
                @else
                    <div class="col-md-6 pr-0">
                        <div class="form-group form-group-default">
                            <label>Password</label>
                            <input id="password" type="password" class="form-control" placeholder="fill password"
                                name="password">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-default">
                            <label>Confirmation Password</label>
                            <input id="confirmationpassword" type="password" class="form-control"
                                placeholder="fill confirmation" name="password_again">
                        </div>
                    </div>
                @endif
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Email</label>
                        <input id="email" type="email" class="form-control" placeholder="fill email" name="email"
                            value="{{ isset($manage_user) ? $manage_user->email : '' }}">
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Role</label>
                        <select class="form-control" id="role" name="role">
                            <option value="0"
                                {{ isset($manage_user) && $manage_user->role == 0 ? 'selected' : '' }}>Admin
                            </option>
                            <option value="1"
                                {{ isset($manage_user) && $manage_user->role == 1 ? 'selected' : '' }}>
                                User</option>
                        </select>

                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group form-group-default">
                        <label>Type</label>
                        <select name="type" class="form-control" id="type">
                            @foreach ($type as $item)
                            <option value="{{ $item->id }}"
                                {{ isset($manage_user) && $manage_user->id_type == $item->id ? 'selected' : '' }}>
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
    jQuery('#form_manage_user').validate({
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
            type: {
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
                        manage_user.fnReloadAjax();
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
