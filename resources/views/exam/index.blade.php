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
                <div class="col-md-12">
                    @if($user->role==0)
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="exam" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Duration</th>
                                            <th>Complete</th>
                                            <th>Start</th>
                                            <th>Finsih</th>
                                            <th style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if (isset($schedule)&&$user->role==1)
                        <form action="{{ url('exam/exam') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $schedule->id }}" />
                            <input type="hidden" name="id_schedule" value="{{ $schedule->id_schedule }}" />
                            <input type="hidden" name="id_exam" value="{{ $schedule->id_exam }}" />
                            <input type="hidden" name="start" id="start">
                            <input type="hidden" name="finish" id="finish">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <button id="take_exam" type="submit" class=" btn btn-primary btn-round ml-auto">
                                            Take exam
                                        </button>
                                    </div>
                                </div>
                                <input id="complete" type="text" value="{{ $complete }} " hidden>
                                <div class="card-body">
                                    <table class="table table-striped table-hover">
                                        <tbody>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{ $schedule->name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Type</td>
                                                <td>{{ $schedule->type }}</td>
                                            </tr>
                                            <tr>
                                                <td>Duration</td>
                                                <td><input id="duration" type="text" value="{{ $schedule->duration }} "
                                                    hidden>{{ $schedule->duration }}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>@switch ($schedule->status)
                                                        @case(1)
                                                            <span class="badge badge-primary">Active</span>
                                                        @break
                                                        @case(0)
                                                            <span class="badge badge-danger">Not Active</span>
                                                        @break
                                                    @endswitch
                                                </td>
                                            </tr><tr>
                                                <td>Complete</td>
                                                <td>@switch ($complete)
                                                        @case(1)
                                                            <span class="badge badge-success">Complete</span>
                                                        @break
                                                        @case(0)
                                                            <span class="badge badge-danger">Not Complete</span>
                                                        @break
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Open</td>
                                                <td id="opens"><input id="open" type="text" value="{{ $schedule->open }} "
                                                        hidden>{{ $schedule->open }} </td>
                                            </tr>
                                            <tr>
                                                <td>Close</td>
                                                <td id="closes"><input id="close" type="text"
                                                        value="{{ $schedule->close }} " hidden>{{ $schedule->close }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="ajax-modal" class="modal bs-example-modal-static" tabindex="-1" data-backdrop="static" data-keyboard="false"
        data-width="50%" style="display: none;"></div>
    <div id="ajax-modal-element" class="modal bs-example-modal-static" tabindex="-1" data-backdrop="static"
        data-keyboard="false" data-width="70%" style="display: none;"></div>
    <div id="ajax-modal-popup" class="modal bs-example-modal-static" tabindex="-1" data-backdrop="static"
        data-keyboard="false" data-width="520" style="display: none;"></div>
@endsection
@push('content-css')
@endpush
@push('content-js')
    <script type="text/javascript">
        var exam;
        var ajaxModal = $('#ajax-modal');
        var ajaxModalPopup = $('#ajax-modal-popup');
        var ajaxModalElement = $('#ajax-modal-element');
        

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            exam = $('#exam').dataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": '{{ url('exam/data') }}',
                    "type": "POST"
                },
                "aoColumns": [{
                        "mData": function(data, type, dataToSet, meta, row) {
                            return (meta.row + meta.settings._iDisplayStart + 1);
                        },
                        "sWidth": "",
                        "sClass": "text-center",
                        "bSortable": false,
                        "bSearchable": false
                    },
                    {
                        "mData": "name",
                        "nama": "name",
                        "sWidth": "",
                        "sClass": "",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "type",
                        "name": "type",
                        "sWidth": "",
                        "sClass": "",
                        "bSortable": true,
                        "bSearchable": true
                    },
                    {
                        "mData": "duration",
                        "name": "duration",
                        "sWidth": "",
                        "sClass": "text-center",
                        "bSortable": false,
                        "bSearchable": false
                    },
                    {
                        "mData": "complete",
                        "name": "complete",
                        "sWidth": "",
                        "sClass": "text-center",
                        "bSortable": false,
                        "bSearchable": false
                    },
                    {
                        "mData": "start",
                        "name": "start",
                        "sWidth": "",
                        "sClass": "text-center",
                        "bSortable": false,
                        "bSearchable": false
                    },
                    {
                        "mData": "finish",
                        "name": "finish",
                        "sWidth": "",
                        "sClass": "text-center",
                        "bSortable": false,
                        "bSearchable": false
                    },
                    {
                        "mData": "detail",
                        "sWidth": "",
                        "sClass": "text-center",
                        "bSortable": false,
                        "bSearchable": false
                    }
                ],
                "aoColumnDefs": [{
                        "aTargets": [4],
                        "mData": null,
                        "mRender": function(data, type, row) {
                            switch (row.complete) {
                                case 1:
                                    return '<span class="badge badge-success">Complete</span>';
                                    break;
                                case 0:
                                    return '<span class="badge badge-danger">Not Complete</span>';
                                    break;
                            }
                        }
                    }, 
                    // 

                ],
            });
            
            $('div.dataTables_filter input').unbind().bind('keyup', function(e) {
                if (e.keyCode == 13) {
                    exam.fnFilter(this.value);
                } else {
                    if (this.value.length == 0) exam.fnFilter('');
                }
            });
        });
        var open = document.getElementById("open").value;
        var close = document.getElementById("close").value;
        var complete = document.getElementById("complete").value;
        var duration = document.getElementById("duration").value;
        document.getElementById("opens").innerHTML = open + "  (" + moment(open,
            'YYYY-MM-DD, h:mm A').fromNow() + ")";
        document.getElementById("closes").innerHTML = close + "  (" + moment(close,
            'YYYY-MM-DD, h:mm A').fromNow() + ")";
        document.getElementById("start").value = moment().format('YYYY-MM-DD, h:mm:ss A');
        document.getElementById("finish").value = moment().add(parseInt(duration), 'minutes').add(4, 'seconds').format(
            'YYYY-MM-DD, h:mm:ss A');

        var str = open;
        var res = str.split(", ");

        var close = close;
        var cls = close.split(", ");

        if (!(moment().isAfter(moment(res[0] + ', ' + res[1], 'YYYY-MM-DD, h:mm A')) && moment().isBefore(moment(cls[0] +
                ', ' + cls[1], 'YYYY-MM-DD, h:mm A')))||(complete==1)) {
            document.getElementById("take_exam").disabled = true;
        }

    </script>
@endpush
