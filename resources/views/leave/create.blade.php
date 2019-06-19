@extends('layouts.master')
@section('title', $viewType)


@section('style')

    {!!Html :: style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css')!!} <!--3.3.7-->

@endsection

@section('content')
    <div class="col-md-12" style="padding: 0;">

        <div class="col-md-6 col-md-offset-3" style=" padding-left: 20px; /*background-color:  red*/ ">

        </div>


        <div class="col-md-8 col-md-offset-2"
             style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 50px;">

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


            <a href="{!! route('menu-list') !!}"><i class="fa fa-backward"></i> Back To List</a>
            <br>
            <h3 class="text-capitalize text-center">
                <b>Menu Create Form</b>
            </h3>

            <form method="POST" class="form-horizontal" action="{{route('menu-save')}}">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="menu_name">Menu Name:</label>
                    <input type="text" class="form-control" value="{{old('menu_name')}}" placeholder="Type Menu Name"
                           id="menu_name" name="menu_name">
                    <label for="module_name">Module Name:</label>
                    <input type="text" class="form-control" value="{{old('module_name')}}" id="module_name" name="module_name"
                           placeholder="Type Module Name">

                </div>

                <div class="form-group">

                    <label for="route_name">Route Name:</label>
                    <input type="text" placeholder="Type Route Name" value="{{old('route_name')}}" class="form-control"
                           id="route_name" name="route_name">

                    <label for="email"> Route Type:</label>
                    <select class="form-control" title="" name="route_type" id="">
                        <option {{old('route_type') == 'parent' ? 'selected':'' }} value="parent">Parent</option>
                        <option {{old('route_type') == 'view' ? 'selected':'' }} value="view">View</option>
                        <option {{old('route_type') == 'post' ? 'selected':'' }} value="post">Post Method</option>
                        <option {{old('route_type') == 'api' ? 'selected': '' }} value="api">Api</option>
                        <option {{old('route_type') == 'report' ? 'selected': '' }} value="report">Report</option>
                    </select>

                </div>


                <div class="form-group">
                    <label for="icon_name">Icon Name:</label>
                    <input type="text" class="form-control" value="{{old('icon_name')}}" id="icon_name" name="icon_name"
                           placeholder="Type Icon Name">

                    <label for="email">Parent Menu:</label>
                    <select class="form-control" title="" name="parent_id" id="parent_id">
                        @if($menu_list)
                            <option value="0">Select Menu as Parent</option>
                            @foreach($menu_list as $k=>$menu)
                                <option {{old('parent_id') == $menu->id?'selected':''}} value="{{$menu->id}}">{{$menu->menu_name}}</option>
                            @endforeach
                        @endif
                    </select>

                </div>


                <div class="form-group">
                    <label for="is_common_access">Access for all:</label>
                    <div class="radio">
                        <label><input value="1" type="radio" name="is_common_access">Yes</label>
                    </div>
                    <div class="radio">
                        <label><input value="0" type="radio" checked name="is_common_access">No</label>
                    </div>

                    <label for="is_displayable">Is displayable*:</label>
                    <div class="radio">
                        <label><input value="1" type="radio" name="is_displayable">Yes</label>
                    </div>
                    <div class="radio">
                        <label><input value="0" type="radio" checked name="is_displayable">No</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status*:</label>
                    <div class="radio">
                        <label><input value="1" type="radio" checked name="status">Yes</label>
                    </div>
                    <div class="radio">
                        <label><input value="0" type="radio"  name="status">No</label>
                    </div>
                </div>

                <div class="clearfix">&nbsp;</div>
                <button type="submit" class="btn btn-info btn-sm center-block">Save Menu</button>
            </form>
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

