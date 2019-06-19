@extends('layouts.master')
@section('title', $viewType)


@section('style')

    {!!Html :: style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css')!!} <!--3.3.7-->

@endsection

@section('content')
    <div class="col-md-12" style="">

        <div class="col-md-4" style="background-color: #f8f9f9; border-radius: 5px;">

            @if (count($errors) > 0)
                <div class="alert alert-danger successOrErrorMsgDiv">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session()->has('success'))
                <div class="alert alert-success successOrErrorMsgDiv">
                    <ul>

                        <li>{{ session()->get('success') }}</li>

                    </ul>
                </div>
            @endif


            <a href="{!! route('leave-attached-to-employee-list') !!}"><i class="fa fa-backward"></i> Back To List</a>

            <form class="form-horizontal">
                <div class="form-group">
                    <label for="email">Leave Name:</label>
                    <select class="form-control" title="" name="leave_id" id="leave_id">
                        @if($leaveList)
                            <option value="0">Select Leave</option>
                            @foreach($leaveList as $k=>$leave)
                                <option {{$leaveId == $leave->id?'selected':''}} value="{{$leave->id}}">{{$leave->name}}</option>
                            @endforeach
                        @endif
                    </select>

                </div>

                <div class="clearfix">&nbsp;</div>

                <button type="button" id="get-list-btn" class="btn btn-info btn-sm center-block">Get Employee List
                </button>

            </form>
        </div>


        <div class="col-md-12 table-responsive" id="emplist-div" style="">

        </div>


    </div>

@endsection

@section('script')
    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $(".successOrErrorMsgDiv").delay(3500).slideUp(4000);
            $('#parent_id').select2();
        })


        function getEmpList() {

            var leaveId = $('#leave_id').val();
            console.log(leaveId == '0');
            if (leaveId == '0') {
                $.growl.warning({title: "Warning!", message: 'Please Select Leave First'});
                return false;
            }


            $.ajax({
                url: '{{ route("leave-get-employee-list-for-attachment") }}',
                data: {
                    'leave_id': leaveId
                },
                type: "GET",
                success: function (data) {
                    // alert(data);
                    $data = $(data); // the HTML content your controller has produced

                    $('#emplist-div').html($data);
                },
                error: function (data) {
                    console.log(data);
                    if (data.status = 401) {
                        $.growl.error({message: data.statusText + " !"});
                    }
                    $.growl.error({message: data.statusText + " !"});
                }
            });
        }


        $('#get-list-btn').click(function () {
            getEmpList();
        })


        $('#emplist-div').on('click', '#save-list-btn', function () {
            var leaveId = $('#leave_id').val();

            var empIds = [];
            $('#emplist-div .attach-leave-checkbox').each(function () {
                if (this.checked) {

                    empIds.push($(this).val());
                    //  console.log($(this).val());
                }
            });

            console.log(empIds);

           // return;

            $.ajax({
                url: '{{ route("leave-attach-employee-to-leave") }}',
                data: {
                    'leave_id': leaveId,
                    'emp_ids':empIds
                },
                type: "GET",
                success: function (data) {

                    console.log(data);
                    $.growl.notice({message: data.message });

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


    </script>

@endsection

