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



    <div class="col-md-10 table-responsive">

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th colspan="3">
                </th>
                <th colspan="8">
                    <form class="form-inline" onkeypress="return event.keyCode != 13;">
                        <div class="form-group">
                            <label for="">Employee name:</label>
                            <input class="form-control route_name" placeholder="Search" name="route_name" type="text"
                                   id="route_name">
                        </div>
                        <input class="btn btn-primary" id="menu-search-btn" readonly="readonly" type="button" value="Search">
                    </form>
                </th>

            </tr>
            <tr>
                <th>S/L</th>
                <th>Leave</th>
                <th>Employee Name</th>
                <th>Remaining Leave</th>
                <th>action</th>

            </tr>
            </thead>
            <tbody>


            @foreach($availableLeaveList as $k=> $leave)


                <tr>
                    <td>{{++$k}}</td>
                    <td class="text-capitalize">
                        {{ $leave->leave->name or 'Not Avaliable' }}
                    </td>
                    <td>{{ $leave->employee->name or 'Not Available'}}</td>
                    <td>{{ $leave->remaining or 'no' }}</td>


                    <td>

                        <a class="btn btn-success btn-xs mrg" data-original-title="" data-toggle="tooltip"
                           href="{{route('leave-attached-to-employee-edit',$leave->id)}}">
                            <i class="fa fa-edit"></i>
                            Edit
                        </a>
                    </td>

                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="pagination">
            {!!   str_replace('/?','?',$availableLeaveList->render() ) !!}
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