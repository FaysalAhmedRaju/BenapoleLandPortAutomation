@inject('user','App\User')
@inject('port','App\Models\Port')
@extends('layouts.master')
@section('title', 'Port Session!')

@section('script')

@endsection
@section('content')
    <div class="col-md-6 col-md-offset-3 ">
        @if(Session::get('PORT_ID'))
            <p class="success text-capitalize text-center">
                Your Current Port: <span style="color: green; font-weight: bolder"> {{Session::get('PORT_ALIAS')}}</span>
            </p>
        @else
            <p class="error text-center">No Port Found For You!</p>

        @endif
        @php( $portList = $port->portList())
        @php( $userPorts = $port->userPortList())

        @if($userPorts)

{{--            {!! Form::model($user::findOrFail(Auth::user()),['route'=>['user-update-port-session',$user::findOrFail(Auth::user()->id)]]) !!}--}}
            {!! Form::open(['route'=>['user-update-port-session']]) !!}
            <div class="form-group">
                <div class="col-md-6">
                    {!! Form::label('port_id','Assigned Port List: ') !!}
                    {!! Form::select('port_id',$userPorts,null,['class'=>'form-control']) !!}
                </div>
                <div class="clearfix"></div>
                <div class="col-md-6 text-center">
                    <br>
                    {!! Form::submit('Change Port',['class'=>'btn btn-info btn-sm']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        @else
            <p class="warning text-center"><i class="fa fa-warning"></i> Contact Admin For Assigning You A Port!</p>
        @endif
    </div>
@endsection