@extends('layouts.master')
@section('title', 'User Edit Form')
@section('style')

@endsection

@section('content')
    <div class="col-md-12">

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
                {{ session()->get('success') }}
            </div>
        @endif


        <div class="col-md-12" style="background-color: aliceblue; border-radius: 20px; padding: 0">
            <h4 class="text-center ok">User Port Assign Form</h4>
            <div class="col-md-12" style="padding: 0">

                {!! Form::model($theUser,['route'=>['user-update',$theUser->id],'files' => true]) !!}
                {{-- <div class="form-group">

                    <div class="col-md-4">
                        {!! Form::label('photo','Current Photo: ',['class'=>'form-controll']) !!}
                        {{ Html::image('img/users/'.$theUser->photo,$theUser->name,
                        ['class'=>'img-rounded','height'=>120,'width'=>250]) }}
                    </div>

                    <div class="col-md-4">
                        {!! Form::label('nid_photo','Current NID Copy: ',['class'=>'form-controll']) !!}
                        {{ Html::image('img/users/nid/'.$theUser->nid_photo,$theUser->name,
                        ['class'=>'img-rounded','height'=>120,'width'=>250]) }}
                    </div>
                </div> --}}


                <div class="clearfix"></div>
                {{-- <div class="form-group">

                    <div class="col-md-4">
                        {!! Form::label('photo','Photo: ',['class'=>'form-controll']) !!}
                        {!! Form::file('photo',['class'=>'form-control']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('nid_photo','NID Copy: ',['class'=>'form-controll']) !!}
                        {!! Form::file('nid_photo',['class'=>'form-control']) !!}
                    </div>


                </div> --}}

                <div class="clearfix"></div>


                {{-- <div class="form-group">
                    <div class="col-md-4">
                        {!! Form::label('role_id','Role: ',['class'=>'form-controll']) !!}
                        {!! Form::select('role_id',$roleList,null,['class'=>'form-control']) !!}
                        <br>
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('password','Password: ',['class'=>'form-controll']) !!}
                        {!! Form::password('password',['class'=>'form-control','placeholder'=>'Change Password']) !!}
                        <br>
                    </div>
                </div> --}}

                <div class="form-group">
                    <div class="col-md-4">
                        {!! Form::label('name','Name: ',['class'=>'form-controll']) !!}
                        {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Please Type Name','disabled' => 'disabled']) !!}
                    </div>

                    {{-- <div class="col-md-4">
                        {!! Form::label('father_name','Father Name: ',['class'=>'form-controll']) !!}
                        {!! Form::text('father_name',null,['class'=>'form-control','placeholder'=>'Please Type Father Name']) !!}
                    </div>

                    <div class="col-md-4">
                        {!! Form::label('mother_name','Mother Name: ',['class'=>'form-controll']) !!}
                        {!! Form::text('mother_name',null,['class'=>'form-control','placeholder'=>'Please Type Mother Name']) !!}
                    </div> --}}

                    <div class="col-md-4">
                        {!! Form::label('mobile','Mobile: ',['class'=>'form-controll']) !!}
                        {!! Form::text('mobile',null,['class'=>'form-control','placeholder'=>'Please Type Mobile no.','disabled' => 'disabled']) !!}
                    </div>

                   {{--  <div class="col-md-4">
                        {!! Form::label('phone','Phone: ',['class'=>'form-controll']) !!}
                        {!! Form::text('phone',null,['class'=>'form-control','placeholder'=>'Please Type Phone no']) !!}
                    </div> --}}

                    <div class="col-md-4">
                        {!! Form::label('email','email: ',['class'=>'form-controll']) !!}
                        {!! Form::email('email',null,['class'=>'form-control','placeholder'=>'Please Type Email','disabled' => 'disabled']) !!}
                    </div>

                    <div class="col-md-4">
                        {!! Form::label('user_status','Status: ',['class'=>'form-controll']) !!}
                        {!! Form::select('user_status',['0'=>'Pending','1'=>'Active','2'=>'Test User'],null,['class'=>'form-control','disabled' => 'disabled']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('user_type','User Status: ',['class'=>'form-controll']) !!}
                        {!! Form::select('user_type',['port'=>'Port','c&f'=>'C&F','custom'=>'Custom'],null,['class'=>'form-control','disabled' => 'disabled']) !!}
                    </div>

                    {{-- <div class="col-md-4">
                        {!! Form::label('designation','Designation: ',['class'=>'form-controll']) !!}
                        {!! Form::select('designation',$designationList,null,['class'=>'form-control']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('date_of_birth','DOB: ',['class'=>'form-controll']) !!}
                        {!! Form::text('date_of_birth',null,['class'=>'form-control','placeholder'=>'Please Type DOB']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('join_date','Join Date: ',['class'=>'form-controll']) !!}
                        {!! Form::text('join_date',null,['class'=>'form-control','placeholder'=>'Please Type DOJ']) !!}
                    </div>


                    <div class="col-md-4">
                        {!! Form::label('nid_no','NID No.: ',['class'=>'form-controll']) !!}
                        {!! Form::text('nid_no',null,['class'=>'form-control','placeholder'=>'Please Type NID No.']) !!}
                    </div> --}}


                    {{-- <div class="col-md-4">
                        {!! Form::label('present_address','Present Address: ',['class'=>'form-controll']) !!}
                        {!! Form::textarea('present_address',null,['class'=>'form-control','rows'=>3]) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::label('permanent_address','Permanent Address: ',['class'=>'form-controll']) !!}
                        {!! Form::textarea('permanent_address',null,['class'=>'form-control','rows'=>3]) !!}
                    </div> --}}


                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <br>
                    <div class="col-md-12" style="border: 1px dotted green ; box-shadow: 0px 0px 5px 0px black">
                        {!! Form::label('port_ids','Ports : ',['class'=>'form-controll']) !!}
                        <br>
                        @foreach($portList as $k=>$port)
                            @php($checked = in_array($port->id, $userPorts) ? true : false )

                            {!! Form::checkbox('port_ids[]',$port->id,$checked,['title'=>$port->id]) !!} {{$port->port_name}}
                        @endforeach

                    </div>

                 {{--   <div class="col-md-12" style="border: 1px dotted green ;box-shadow: 0px 0px 5px 0px black">
                        {!! Form::label('port_ids','Shed Yards : ',['class'=>'form-controll']) !!}
                        <br>
                        @foreach($shedYardList as $shedYard)
                            @php($checked = in_array($shedYard->id, $userShedYard) ? true : false )

                            {!! Form::checkbox('shed_yard_ids[]',$shedYard->id,$checked,['title'=>$shedYard->id]) !!} {{$shedYard->shed_yard}}
                        @endforeach

                    </div>--}}

                    {{-- <div class="col-md-12" style="border: 1px dotted green ;box-shadow: 0px 0px 5px 0px black">
                        {!! Form::label('weighbridge_ids','Weigh Scale : ',['class'=>'form-controll']) !!}
                        <br>
                        @foreach($weighbridgeList as $weighbridge)
                            @php($checked = in_array($weighbridge->id, $userWeighbridge) ? true : false )

                            {!! Form::checkbox('shed_yard_ids[]',$weighbridge->id,$checked,['title'=>$weighbridge->id]) !!} {{$weighbridge->scale_name}}
                        @endforeach

                    </div> --}}
                </div>

                <div class="clearfix"></div>

                <div class="form-group">
                    <br><br>
                    <div class="col-md-4 col-md-offset-4">
                        {!! Form::submit('Update User',['class'=>'btn btn-info btn-sm']) !!}

                    </div>

                    {!! Form::close() !!}


                </div>
            </div>
        </div>

    @endsection



    @section('script')

        <!-- Latest compiled and minified CSS -->
            <link rel="stylesheet" href="css/bootstrap-select.min.css">
            <!-- Latest compiled and minified JavaScript -->
            <script src="/js/bootstrap-select.min.js"></script>
            <script type="text/javascript">
                $(function () {

                    $('#join_date').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-100:+10",
                        dateFormat: 'yy-mm-dd'
                    });
                    $('#date_of_birth').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "-100:+10",
                        dateFormat: 'yy-mm-dd'
                    });
                });
            </script>
@endsection