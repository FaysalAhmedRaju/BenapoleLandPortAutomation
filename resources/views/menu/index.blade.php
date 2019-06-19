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
                    <a href="{!! route('menu-create-form') !!}"><i class="fa fa-plus"></i> Create New</a>
                </th>
                <th colspan="7">

                </th>

            </tr>
            <tr>
                <th>S/L</th>
                <th>Id</th>
                <th>Menu Name</th>
                <th>Route Name</th>
                <th>Route Type</th>
                <th>Parent</th>
                <th>Accessable</th>
                <th>Displayable</th>
                <th>Status</th>
                <th class="text-center th_width_80">action</th>

            </tr>
            </thead>
            <tbody>


            @foreach($menus as $k=> $menu)


                <tr>
                    <td>{{++$k}}</td>
                    <td>{{$menu->id}}</td>


                    <td>
                        {{ $menu->menu_name? $menu->menu_name : 'Not Avaliable' }}
                    </td>

                    <td>{{ $menu->route_name or 'Not Available'}}</td>
                    <td class="text-capitalize">{{ $menu->route_type or 'Not Available'}}</td>
                    <td>
                        @if($menu->menu)
                            {{$menu->menu->id.'-'.$menu->menu->menu_name }}
                        @else
                            No
                        @endif
                    </td>
                    <td>{{ $menu->is_common_access?'yes':'no' }}</td>
                    <td>{{ $menu->is_displayable?'yes':'no' }}</td>
                    <td>
                        @if(isset($menu->status) && $menu->status == true)
                            {{ 'Active' }}
                        @else
                            {{ 'Inactive' }}
                        @endif
                    </td>

                    <td>

                        <a class="btn btn-success btn-xs mrg" data-original-title="" data-toggle="tooltip"
                           href="{{ route('menu-edit-form',[$menu->id]) }}">
                            <i class="fa fa-edit"></i>
                            Edit
                        </a>

                        <a class="btn btn-danger delete_btn btn-xs mrg" data-original-title=""
                           onclick="return confirm('Are you sure?')" href="{{ route('menu-delete',[$menu->id]) }}">
                            <i class="fa fa-trash-o"></i>
                            Delete
                        </a>
                    </td>

                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="pagination">
            {!!   str_replace('/?','?',$menus->render() ) !!}
        </div>

    </div>


    <div class="col-md-12">

        <div>
            <form class="form-inline" onkeypress="return event.keyCode != 13;">
                <div class="form-group">
                    <label for="">Route name:</label>
                    <input class="form-control route_name" placeholder="Search" name="route_name" type="text"
                           id="route_name">
                </div>
                <input class="btn btn-primary" id="menu-search-btn" readonly="readonly" type="button" value="Search">
            </form>
        </div>

        <div id="menu-search-div">

        </div>
    </div>



@endsection

@section('script')
    {!! Html::script('/js/select2.min.js') !!}
    {!! Html::script('js/jquery.growl.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $('#menu-search-btn').click(function () {
                var routeName = $('#route_name').val();
                console.log(routeName == '');

                if (routeName == '') {
                    $.growl.warning({title: "Warning!", message: 'Please Input Value First'});
                    return false;
                }

                $.ajax({
                    url: '{{ route("menu-search") }}',
                    data: {
                        'route_name': routeName
                    },
                    type: "GET", // not POST, laravel won't allow it
                    success: function (data) {
//                        alert(data);
                        $data = $(data); // the HTML content your controller has produced

                        $('#menu-search-div').html($data);
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