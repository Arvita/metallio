<form id="deleteMenuForm" class="form-horizontal" role="form" method="POST" action="#">
    {!! csrf_field() !!}
    {!! isset($category) ? method_field('DELETE') : '' !!}
    {!! Form::hidden('id', $id) !!}

    <!-- <div class="swal-modal"> -->
    <div class="swal-content">
        <h3 class="swal-title">Confirmation</h3>
        <div class="swal-text top">
            <p>Are your sure you want to delete this category?</p>
            <p><strong><em>"{{ $category->name }} "</em></strong></p>
        </div>
    </div>
    <!-- </div> -->
    <div class="swal-footer">
        <button type="submit" id="deleteMenuButton" class="btn btn-success">Delete</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#deleteMenuButton').click(function(e) {
            e.preventDefault();
            blockTab(ajaxModalPopup);
            $.post("{{ url("/category/$id") }}",
                $("#deleteMenuForm").serialize(),
                function(data) {
                    var msg;
                    $('.alert.alert-success, .alert.alert-danger').remove();
                    unblockTab(ajaxModalPopup);
                    if (data.stat) {
                        msg =
                            '<div class="alert alert-success"><i class="fa fa-check-circle"></i><strong> Success !</strong> ' +
                            data.msg + '</div>';
                        $('.top').before(msg);

                        category.fnReloadAjax();
                        window.setTimeout(function() {
                            ajaxModalPopup.modal('hide');
                        }, 1500);
                    } else {
                        msg =
                            '<div class="alert alert-danger"><i class="fa fa-check-circle"></i><strong> Failed !</strong> ' +
                            data.msg + '</div>';
                        $('.top').before(msg);
                    }
                },
                "json");
        });
    });

</script>
