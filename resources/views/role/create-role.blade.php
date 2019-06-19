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
                            <a href="{{ route('role-list') }}"> Role List</a>
                        </span>

                {!! Form::open(['route' => 'role-save']) !!}

                <div class="form-group">
                    <label for="name"></label>

                    {!! Form::label('dashboard_route', 'Role Name:', [])!!}

                    {!! Form::text('name',null,['class'=>'form-control','placeholder'=>"Type Role Name"]) !!}

                    {!! Form::label('dashboard_route', 'Dashboard Route:', [])!!}

                    {!! Form::select('dashboard_route', $dashboardRouteList,null,['class'=>'form-control']) !!}


                </div>

                <div class="clearfix">&nbsp;</div>
                <button type="submit" class="btn btn-info btn-sm center-block">Save</button>
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
            $('#dashboard_route').select2();
        })

    </script>

@endsection

