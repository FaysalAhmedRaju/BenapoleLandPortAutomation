@extends('layouts.master')
@section('title', $viewType)


@section('style')

    {!!Html :: style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css')!!} <!--3.3.7-->

@endsection

@section('content')
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading"><i class="fa fa-info-circle"></i> {{$availableLeave->employee->name}}
                <span class="pull-right"><a href="{{route('leave-attached-to-employee-list')}}">
                            <i class="fa fa-backward"></i> Back To List
                        </a>
                    </span>
            </div>

            <div class="panel-body">


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
                {{--@include('includes.flash')--}}
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif


                {!! Form::model($availableLeave,['route' => ['leave-attached-to-employee-update',$availableLeave->id],'method' => 'POST','class' => ' ','files' => true]) !!}

                <div class="form-group">
                    <div class="col-md-6">
                        {!! Form::label('leave_id','Leave Type',['class' => 'control-label']) !!}
                        <select class="form-control" title="" name="leave_id">
                            @if($leaveList)
                                <option value="0">Select Leave Type</option>
                                @foreach($leaveList as $k=>$leave)
                                    <option {{$leave->id == $availableLeave->leave_id?'selected':''}} value="{{$leave->id}}">{{$leave->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="controls col-md-6">
                        {!! Form::label('remaining','Leave Days',['class' => 'control-label']) !!}
                        {!! Form::text('remaining',null,['class'=>'form-control','placeholder'=>'Total Days','style'=>"margin-bottom: 10px"]) !!}
                    </div>

                </div>


                <div class="clearfix"></div>

                <div class="form-group">
                    <div class="controls col-md-8">
                        <input type="submit" name="send" value="Update" class="btn btn btn-primary"
                               id="button-id-signup"/>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>


@endsection

@section('script')
    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $(".successOrErrorMsgDiv").delay(3500).slideUp(4000);
            $('#leave_id').select2();
            $('#to').datepicker({dateFormat: 'yy-mm-dd'});
            $('#from').datepicker({dateFormat: 'yy-mm-dd'});
        })

    </script>

@endsection

