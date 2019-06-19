@extends('layouts.master')

@section('title', $viewType)

@section('style')

    {!!Html :: style('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css')!!} <!--3.3.7-->

@endsection

@section('content')

    <div class="col-md-12">
        <div class="col-md-12">

            <div class="col-md-12">
                <span class="glyphicon glyphicon-user " aria-hidden="true"></span>
                <span aria-hidden="true">
                            <a href="{{ route('menu-list') }}"> Menu List</a>
                        </span>

                <ul class="breadcrumb text-right">
                    <li>
                        <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>
                        <a href="#"></a></li>
                    <li class="active">


                    </li>
                </ul>
            </div>
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


            <form method="POST" class="form-horizontal" action="{{route('menu-update',[$theMenu->id])}}">
                {!! csrf_field() !!}
                <div class="form-group">
                    <label for="menu_name">Menu Name:</label>
                    <input type="text" class="form-control" value="{{$theMenu->menu_name}}" placeholder="Type Menu Name"
                           id="menu_name" name="menu_name">

                    <label for="module_name">Module Name:</label>
                    <input type="text" class="form-control" value="{{$theMenu->module_name}}" id="module_name"
                           name="module_name"
                           placeholder="Type Module Name">


                </div>
                <div class="form-group">
                    <label for="route_name">Route Name:</label>
                    <input type="text" placeholder="Type Route Name" value="{{$theMenu->route_name}}"
                           class="form-control"
                           id="route_name" name="route_name">


                    <label for="email"> Route Type:</label>
                    <select class="form-control" title="" name="route_type" id="">
                        <option {{$theMenu->route_type == 'parent' ? 'selected':'' }} value="parent">Parent</option>
                        <option {{$theMenu->route_type == 'view' ? 'selected':'' }} value="view">View</option>
                        <option {{$theMenu->route_type == 'post' ? 'selected':'' }} value="post">Post Method</option>
                        <option {{$theMenu->route_type == 'api' ? 'selected': '' }} value="api">Api</option>
                        <option {{$theMenu->route_type == 'report' ? 'selected': '' }} value="report">Report</option>
                    </select>

                </div>


                <div class="form-group">
                    <label for="icon_name">Icon Name:</label>
                    <input type="text" class="form-control" value="{{$theMenu->icon_name}}" id="icon_name"
                           name="icon_name"
                           placeholder="Type Icon Name">

                    <label for="email">Parent Menu:</label>
                    <select class="form-control" title="" name="parent_id" id="parent_id">
                        @if($menu_list)
                            <option value="0">Select Menu as Parent</option>
                            @foreach($menu_list as $k=>$menu)
                                <option {{$theMenu->parent_id == $menu->id ?'selected':''}} value="{{$menu->id}}">{{$menu->menu_name}}</option>
                            @endforeach
                        @endif
                    </select>
                    <span id="loading" style="display: none"><b> Please wait...</b></span>
                    <div id="position_list"></div>

                    <label for="position">Position:</label>
                    <input type="number" class="form-control"  value="{{$theMenu->position}}"   name="position"
                           placeholder="Type Position">

                </div>

                <div class="form-group">
                    <label for="is_common_access">Access for all:</label>
                    <div class="radio">
                        <label>
                            <input type="radio" value="1"
                                   {{$theMenu->is_common_access==1? 'checked' : ''}} name="is_common_access">
                            Yes
                        </label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" value="0"
                                      {{$theMenu->is_common_access==0? 'checked' : ''}} name="is_common_access">No</label>
                    </div>

                    <label for="is_displayable">Is displayable*:</label>
                    <div class="radio">
                        <label><input type="radio" value="1"
                                      {{$theMenu->is_displayable==1? 'checked' : ''}} name="is_displayable">Yes</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" value="0"
                                      {{$theMenu->is_displayable==0? 'checked' : ''}} name="is_displayable">No</label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status*:</label>
                    <div class="radio">
                        <label><input type="radio" value="1"
                                      {{$theMenu->status==1? 'checked' : ''}} name="status">Yes</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" value="0"
                                      {{$theMenu->status==0? 'checked' : ''}} name="status">No</label>
                    </div>
                </div>

                <div class="clearfix">&nbsp;</div>
                <button type="submit" class="btn btn-info btn-sm center-block">Update Menu</button>
            </form>
        </div>

    </div>



@endsection
@section('script')

    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $('#parent_id').select2();





            getPositionIds();


            $('#parent_id').on('change', function () {
                getPositionIds();

            })

            function getPositionIds() {

                var parentId = $('#parent_id').val();
                $("#position_list").html('');
                $("#loading").css('display','block');

                $.ajax({
                    url: '{{ route("menu-get-position-list-by-parent") }}',
                    data: {
                        'parent_id': parentId
                    },
                    type: "GET",
                    success: function (data) {

                        if (data.length > 0) {
                            //  var split=data.split(',')
                            //console.log(split);
                            var value = '';
                            data.forEach(function (entry) {
                                console.log(entry);
                                value += '<span class="label label-success">' + entry + '</span>'
                            });

                            $("#position_list").html(value)


                        } else {
                            $.growl.notice({message: "Sorry, No Child Menu Found!"});
                        }


                    },
                    error: function (data) {
                        console.log(data);
                        if (data.status = 401) {
                            $.growl.error({message: data.statusText + " !"});
                        }
                        $.growl.error({message: data.statusText + " !"});

                    },complete:function () {
                        $("#loading").css('display','none');

                    }
                });

            }

        });

    </script>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection