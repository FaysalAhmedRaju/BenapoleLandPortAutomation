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
                    <a href="{!! route('weighbridge-create-form') !!}"><i class="fa fa-plus"></i> Create New</a>
                </th>
                <th colspan="7">

                </th>

            </tr>
            <tr>
                <th>S/L</th>
                {{--<th>Id</th>--}}
                <th>Scale Name</th>
                <th>Port Name</th>
                {{--<th>Route Type</th>--}}
                {{--<th>Parent</th>--}}
                {{--<th>Accessable</th>--}}
                {{--<th>Displayable</th>--}}
                {{--<th>Status</th>--}}
                <th class="text-center th_width_80">action</th>

            </tr>
            </thead>
            <tbody>


            @foreach($weights as $k=> $weight)


                <tr>
                    <td>{{++$k}}</td>
                    {{--<td>{{$menu->id}}</td>--}}


                    <td>
                        {{ $weight->scale_name? $weight->scale_name : 'Not Avaliable' }}
                    </td>

                    <td>{{ $weight->port->port_name or 'Not Available'}}</td>


                    <td style="text-align: center">

                        <a class="btn btn-success btn-xs mrg" data-original-title="" data-toggle="tooltip"
                           href="{{ route('weighbridge-edit-form',[$weight->id]) }}">
                            <i class="fa fa-edit"></i>
                            Edit
                        </a>

                        <a class="btn btn-danger delete_btn btn-xs mrg" data-original-title=""
                           onclick="return confirm('Are you sure?')" href="{{ route('weighbridge-delete',[$weight->id]) }}">
                            <i class="fa fa-trash-o"></i>
                            Delete
                        </a>

                    </td>

                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="pagination">
            {!!   str_replace('/?','?',$weights->render() ) !!}
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