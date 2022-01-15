<div class="modal-content">
    <div class="modal-header border-0">
        <h5 class="modal-title">
            <span class="fw-light">
                Detail
            </span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    <p class="small">List Bank</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="list_bank" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer border-0">
    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        let id = '{{ $id }}';
        detail_create_exam = $('#list_bank').dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": '{{ url('detail_create_exam/list_question') }}',
                "type": "POST",
                "data": {
                    _token: "{{ csrf_token() }}",
                    id: id
                }
            },
            "aoColumns": [{
                    "mData": function(data, type, dataToSet, meta, row) {
                        return (meta.row + meta.settings._iDisplayStart + 1);
                    },
                    "sWidth": "10%",
                    "sClass": "text-center",
                    "bSortable": false,
                    "bSearchable": false
                },
                {
                    "mData": "name",
                    "name": "name",
                    "sWidth": "30%",
                    "sClass": "text-center",
                    "bSortable": false,
                    "bSearchable": false
                },
                {
                    "mData": "category",
                    "total": "category",
                    "sWidth": "15%",
                    "sClass": "text-center",
                    "bSortable": false,
                    "bSearchable": false
                },
                {
                    "mData": "action",
                    "action": "update_adt",
                    "sWidth": "15%",
                    "sClass": "text-center",
                    "bSortable": false,
                    "bSearchable": false
                },

            ],
        });
        $('div.dataTables_filter input').unbind().bind('keyup', function(e) {
            if (e.keyCode == 13) {
                detail_create_exam.fnFilter(this.value);
            } else {
                if (this.value.length == 0) detail_create_exam.fnFilter('');
            }
        });
    });
</script>
