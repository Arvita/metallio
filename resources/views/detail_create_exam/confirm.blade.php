@if ($type == 'deleteall')
    <div class="swal-content">
        <h3 class="swal-title">Confirmation</h3>
        <div class="swal-text top">
            <p>Are your sure you want to delete all question?</p>
            <div class="pertanyaan"></div>
        </div>
    </div>
    <div class="swal-footer">
        <button id="deleteMenuButton" onclick="deleteAll()" class="btn btn-success">Delete</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
@endif

@if ($type == 'deletequestion')
    <div class="swal-content">
        <h3 class="swal-title">Confirmation</h3>
        <div class="swal-text top">
            <p>Are your sure you want to delete this question?</p>
            <div class="pertanyaan"></div>
        </div>
    </div>
    <div class="swal-footer">
        <button id="deleteMenuButton" onclick="deleteOne()" class="btn btn-success">Delete</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
    <script>
        function getquestion() {
            $.ajax({
                url: `{{ url('/detail_create_exam/getdetailquestionbank/' . $id) }}`,
                dataType: 'json',
                success: function(data) {
                    $('.pertanyaan').html(
                        `<small class="text-center"><i>Question : </i></small><div class="mt20"></div>` +
                        data.question);
                }
            })
        }
        getquestion();
    </script>
@endif

<script type="text/javascript">
  var detail_create_exam;
    function deleteAll() {
        $.post("{{ url('/detail_create_exam/delete/') }}", {
            _token: "{{ csrf_token() }}",
            type: 'deleteall',
            id: "{{ $id }}"
        }, function(data, textStatus, xhr) {
            var msg;
            $('.alert.alert-success, .alert.alert-danger').remove();
            unblockTab(ajaxModalPopup);
            if (data.stat) {
                msg =
                    '<div class="alert alert-success"><i class="fa fa-check-circle"></i><strong> Success </strong> ' +
                    data.msg + '</div>';
                $('.top').before(msg);
                
                window.setTimeout(function() {
                    ajaxModalPopup.modal('hide');
                    window.location.reload(true);
                }, 1500);
            } else {
                msg =
                    '<div class="alert alert-danger"><i class="fa fa-check-circle"></i><strong> Wrong !</strong> ' +
                    data.msg + '</div>';
                $('.top').before(msg);
            }
        });
    }

    function deleteOne() {
        $.post("{{ url('/detail_create_exam/delete/') }}", {
            _token: "{{ csrf_token() }}",
            type: 'delete',
            id: "{{ $id }}"
        }, function(data, textStatus, xhr) {
            var msg;
            $('.alert.alert-success, .alert.alert-danger').remove();
            unblockTab(ajaxModalPopup);
            if (data.stat) {
                msg =
                    '<div class="alert alert-success"><i class="fa fa-check-circle"></i><strong> Success </strong> ' +
                    data.msg + '</div>';
                $('.top').before(msg);
                window.setTimeout(function() {
                    ajaxModalPopup.modal('hide');
                    window.location.reload(true);
                }, 1500);
            } else {
                msg =
                    '<div class="alert alert-danger"><i class="fa fa-check-circle"></i><strong> Wrong !</strong> ' +
                    data.msg + '</div>';
                $('.top').before(msg);
            }
        });
    }
</script>
