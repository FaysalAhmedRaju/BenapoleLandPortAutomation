@extends('layouts.master')

@section('title', $viewType)

@section('style')

    {!! Html::style('css/styles.css') !!}
    {!! Html::style('css/select2.min.css') !!}
    {!! Html::style('/css/jquery.growl.css') !!}

@endsection


@section('content')

    <div class="col-md-12">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session()->has('success'))
            <div class="alert alert-success">
                <ul>
                    <li>{{ session()->get('success') }}</li>
                </ul>
            </div>
        @endif
    </div>



    <div class="col-md-12 table-responsive">

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th colspan="3">
                    <a href="{!! route('leave-application-create-form') !!}"><i class="fa fa-plus"></i> Create New</a>
                </th>
                <th colspan="8">
                    @if($leaveAdmin)

                        {!! Form::open(['url'=>route('leave-application-search-by-employee'),'method'=>'post','class'=>"form-inline"]) !!}
                        <div class="form-group">
                            <select class="form-control" title="" name="employee_id" id="employee_id">
                                <option value="0">Select Employee</option>
                                @foreach($employeeList as $k=>$emp)
                                    <option title="" value="{{$emp->id}}">{{$emp->name or ''}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input class="btn btn-primary" id="menu-search-btn" readonly="readonly" type="submit"
                               value="Search">
                        {!! Form::close() !!}
                    @endif
                </th>

            </tr>
            <tr>
                <th>S/L</th>
                <th>Leave</th>
                <th>Employee Name</th>
                <th>From-To</th>
                <th>Total Days</th>
                <th>Applied Date</th>
                <th>Status</th>
                <th>Granted Date</th>
                <th>Reason</th>
                <th>Application Copy</th>
                <th class="text-center th_width_80">action</th>

            </tr>
            </thead>
            <tbody>


            @foreach($leaveApplicationList as $k=> $application)


                <tr class=" @if($application->status=='Rejected') warning @elseif($application->status=='Granted') success @endif ">
                    <td>{{++$k}}</td>
                    <td class="text-capitalize">
                        {{ $application->leave->name or 'Not Avaliable' }}
                    </td>

                    <td class="hid_empname_{{ $application->id}}"> {{ $application->employee->name or 'Not Available'}}</td>
                    <td>
                        <nobr>
                            {{date('d-m-Y',strtotime($application->from)) }} <br>
                            {{ date('d-m-Y',strtotime($application->to)) }}
                        </nobr>
                    </td>
                    <td class="text-capitalize">{{ $application->leave_days or 'Not Available'}}</td>
                    <td class="get_app_date_{{ $application->id}}">{{ $application->applied_on or 'no' }}</td>
                    <td>{{ $application->status}}</td>
                    <td>{{ $application->granted_on or 'Not Granted' }}</td>
                    <td>{{ $application->reason or 'no' }} </td>
                    <td>
                        @if($application->application_copy)
                            <img width="100" height="70" src="/img/leave-application/{{$application->application_copy}}"
                                 alt="application_copy">
                        @endif
                    </td>


                    <td>
                        <nobr>
                            @if(!$leaveAdmin)
                                @if($application->status=='Applied')

                                    <a class="btn btn-success btn-xs mrg" data-original-title="" data-toggle="tooltip"
                                       href="{{ route('leave-application-edit',[$application->id]) }}">
                                        <i class="fa fa-edit"></i>
                                        Edit
                                    </a>

                                    <a class="btn btn-danger btn-xs mrg" onclick="return confirm('Are you sure?')"
                                       href="{{route('leave-application-delete',$application->id)}}">
                                        <i class="fa fa-trash-o"></i>
                                        Delete
                                    </a>
                                @elseif($application->status=='Rejected')
                                    <a class="btn btn-success btn-xs mrg" data-original-title="" data-toggle="tooltip"
                                       href="{{ route('leave-application-edit',[$application->id]) }}">
                                        <i class="fa fa-edit"></i>
                                        Edit
                                    </a>
                                @endif
                            @endif

                            @if($leaveAdmin)

                                <a class="btn btn-success btn-xs mrg" data-original-title="" data-toggle="tooltip"
                                   href="{{ route('leave-application-edit',[$application->id]) }}">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </a>

                                <a class="btn btn-danger btn-xs mrg" onclick="return confirm('Are you sure?')"
                                   href="{{route('leave-application-delete',$application->id)}}">
                                    <i class="fa fa-trash-o"></i>
                                    Delete
                                </a>

                                @if($application->status=='Applied')

                                    <a data-toggle="modal" data-target="#rejectModal" data-emp="{{$application->id}}"
                                       class="reject-btn btn btn-warning btn-xs" href="#">
                                        <i class="fa fa-crosshairs"></i>
                                        Reject
                                    </a>
                                    <a class="grant-btn btn btn-info btn-xs mrg" href="#"
                                       data-toggle="modal" data-target="#grantModal" data-emp="{{$application->id}}">
                                        <i class="fa fa-check-circle"></i>
                                        Grant
                                    </a>

                                @elseif($application->status=='Rejected')
                                    <a class="grant-btn btn btn-info btn-xs mrg" href="#"
                                       data-toggle="modal" data-target="#grantModal" data-emp="{{$application->id}}">
                                        <i class="fa fa-check-circle"></i>
                                        Grant
                                    </a>

                                @endif
                            @endif
                        </nobr>
                    </td>

                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="pagination">
            {!!   str_replace('/?','?',$leaveApplicationList->render() ) !!}
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="grantModal" tabindex="-1" role="dialog" aria-labelledby="grantModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="grantModalLabel"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <b> By: </b> <span id="grant_emp_name"></span>
                            <b>Applied: </b> <span id="grant_app_date"></span>

                            <form action="">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <input type="hidden" value="" id="grant_app_id">
                                        <label for="">Remark:</label>
                                        <textarea class="form-control" name="grant-reason" id="grant-reason" cols="50"
                                                  rows="5"></textarea>
                                    </div>

                                    <div class="controls col-md-6">


                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="send-grant-btn" class="btn btn-info">Grant Application</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="rejectModalLabel"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <b> By: </b> <span id="emp_name"></span>
                            <b>Applied: </b> <span id="app_date"></span>

                            <form action="">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <input type="hidden" value="" id="reject_app_id">
                                        <label for="">Reject Reason:</label>
                                        <textarea class="form-control" name="reject-reason" id="reject-reason" cols="50"
                                                  rows="5"></textarea>
                                    </div>

                                    <div class="controls col-md-6">


                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id="send-reject-btn" class="btn btn-warning">Reject Application</button>
                    </div>
                </div>
            </div>
        </div>


    </div>


@endsection

@section('script')
    {!! Html::script('/js/select2.min.js') !!}
    {!! Html::script('js/jquery.growl.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $('#employee_id').select2();


            $('.grant-btn').click(function () {
                console.log($(this).attr('data-emp'))
                var appId = $(this).attr('data-emp');

                $('#grantModalLabel').html('You are going to Grant The Apllication!');
                $('#grant_emp_name').html($(".hid_empname_" + appId).html());
                $('#grant_app_date').html($(".get_app_date_" + appId).html());
                $('#grant_app_id').val(appId);
            });


            $('#send-grant-btn').click(function () {

                var rejectReason = $('#grant-reason').val();
                var appId = $('#grant_app_id').val();

                $.ajax({
                    url: '{{ route("leave-application-grant") }}',
                    data: {
                        'application_id': appId,
                        'reject_reason': rejectReason
                    },
                    type: "GET", // not POST, laravel won't allow it
                    success: function (data) {
                        console.log(data)
                        $.growl.notice({message: data.message});

                        setTimeout("$('#grantModal').modal('hide')", 2000);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);

                    },
                    error: function (data) {
                        console.log(data);
                        if (data.status = 401) {
                            $.growl.error({message: data.statusText + " !"});
                        }
                        $.growl.error({message: data.statusText + " !"});

                    }
                });
            });


            $('.reject-btn').click(function () {
                console.log($(this).attr('data-emp'))
                var appId = $(this).attr('data-emp');

                $('#rejectModalLabel').html('You are going to Reject The Apllication!');
                $('#emp_name').html($(".hid_empname_" + appId).html());
                $('#app_date').html($(".get_app_date_" + appId).html());
                $('#reject_app_id').val(appId);
            });

            $('#send-reject-btn').click(function () {

                var rejectReason = $('#reject-reason').val();
                var appId = $('#reject_app_id').val();

                $.ajax({
                    url: '{{ route("leave-application-reject") }}',
                    data: {
                        'application_id': appId,
                        'reject_reason': rejectReason
                    },
                    type: "GET", // not POST, laravel won't allow it
                    success: function (data) {
                        console.log(data)
                        $.growl.notice({message: data.message});

                        setTimeout("$('#rejectModal').modal('hide')", 2000);
                        setTimeout(function () {
                            location.reload();
                        }, 2000);

                    },
                    error: function (data) {
                        console.log(data);
                        if (data.status = 401) {
                            $.growl.error({message: data.statusText + " !"});
                        }
                        $.growl.error({message: data.statusText + " !"});

                    }
                });
            });
        });
    </script>


@endsection