@extends('layouts.master')
@section('title', $viewType)


@section('style')

    {!!Html :: style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css')!!} <!--3.3.7-->

@endsection

@section('content')
    <div class="col-md-12" style="padding: 0;">

        <div class="col-md-6 col-md-offset-3" style=" padding-left: 20px; /*background-color:  red*/ ">

        </div>


        <div class="col-md-12">
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
                    <ul>

                        <li>{{ session()->get('success') }}</li>

                    </ul>
                </div>
            @endif
            </div>

                <div class="col-md-6" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 50px;">

                    <span class="fa fa-backward " aria-hidden="true"></span>
                    <span aria-hidden="true">
                            <a href="{{ route('weighbridge-list') }}"> Weighbridge Create Form</a>
                        </span>



                    <form method="POST" class="form-horizontal" action="{{route('weighbridge-save')}}">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label for="menu_name">Scale Name:</label>
                            <input type="text" class="form-control" value="{{old('scale_name')}}" placeholder="Type Scale Name"
                                   id="scale_name" name="scale_name">


                        </div>

                        <div class="form-group">
                            {{--<label for="menu_name">Port Name:</label>--}}
                            {{--<input type="text" class="form-control" value="{{old('port_id')}}" placeholder="Select Port Name"--}}
                                   {{--id="port_id" name="port_id">--}}
                            {!! Form::label('port_id','Port Name: ') !!}
                            {!! Form::select('port_id',$portList,null,['class'=>'form-control','placeholder'=>"Type Scale Name"]) !!}


                        </div>


                        <div class="clearfix">&nbsp;</div>
                        <button type="submit" class="btn btn-info btn-sm center-block">Save</button>
                    </form>

                    {{--{!! Form::model($theWeighbridge,['route'=>['weighbridge-update',$theWeighbridge->id]]) !!}--}}
                    {{--<div class="form-group">--}}
                        {{--{!! Form::label('scale_name') !!}--}}
                        {{--{!! Form::text('scale_name',null,['class'=>'form-control','placeholder'=>"Type Scale Name"]) !!}--}}
                    {{--</div>--}}

                    {{--<div class="form-group">--}}
                        {{--{!! Form::label('port_id','Port Name: ') !!}--}}
                        {{--{!! Form::select('port_id',$portList,null,['class'=>'form-control','placeholder'=>"Type Scale Name"]) !!}--}}
                    {{--</div>--}}
                    {{----}}
                    {{--{!! Form::close() !!}--}}
                </div>





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

    </script>

@endsection

