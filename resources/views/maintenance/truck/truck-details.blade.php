@extends('layouts.master')

@section('title', $viewTitle)

@section('style')

    {!! Html::style('css/styles.css') !!}
    {!! Html::style('css/select2.min.css') !!}
    {!! Html::style('/css/jquery.growl.css') !!}

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
    </div>

    <div class="col-md-12">

        <div class="col-md-12 table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th colspan="3">
                        <a href="{!! route('maintenance-manifest-manifest-details',$theTruck->manifest->id) !!}">
                            <i class="fa fa-plus"></i> Back To Truck List</a>
                    </th>
                    <th colspan="7">
                        Truck For Manifest No. @if($theTruck) {{$theTruck->manifest->manifest}} @endif
                    </th>

                </tr>
                <tr>
                    <th>Vehicle Type</th>
                    <th>Type-No</th>
                    <th>Entry At</th>
                    <th>Received At</th>
                    <th class="text-center">action</th>

                </tr>
                </thead>
                @if($theTruck)
                    <tbody>
                    <tr>
                        <td>{{$theTruck->vehicle_type_flag}}</td>
                        <td>
                            {{ $theTruck->truck_type .'-'. $theTruck->truck_no}}
                        </td>
                        <td>{{ $theTruck->truckentry_datetime}}</td>
                        <td class="text-capitalize">
                            @if(count($theTruck->shedYardWeights)>0)
                                @foreach($theTruck->shedYardWeights as $k=>$v)
                                    {{ $v->yardDetail->yard_shed_name}}
                                @endforeach
                            @else
                                <p class="text-warning">Not Received Yet</p>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-success btn-xs mrg" data-original-title="" data-toggle="tooltip"
                               href="{{ route('maintenance-truck-edit-form',[$theTruck->id]) }}">
                                <i class="fa fa-edit"></i>
                                Edit
                            </a>


                            <a class="btn btn-danger delete_btn btn-xs mrg" data-original-title=""
                               onclick="return confirm('Are you sure?')"
                               href="{{ route('maintenance-truck-edit-form',[$theTruck->id]) }}">
                                <i class="fa fa-trash-o"></i>
                                Delete
                            </a>
                        </td>

                    </tr>
                    </tbody>
                @else
                    <tfoot>
                    <tr>
                        <td colspan="6">
                            <p style="color: red; text-align: center">No Data Found!</p>
                        </td>
                    </tr>
                    </tfoot>
                @endif
            </table>
        </div>


        {{--shed yard details--}}
        <div class="col-md-12 table-responsive">

            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th colspan="13">
                        <i class="fa fa-birthday-cake"></i> Shed Yard Details
                    </th>
                <tr>
                <tr>
                    <th>Shed/Yard</th>
                    <th>Received Lab Weight</th>
                    <th>Received Equ Weight</th>
                    <th>Received Datetime</th>
                    <th>Shifting Flag</th>
                    <th>Received Lab Package</th>
                    <th>Received Equ Package</th>
                    <th>Action</th>
                <tr>


                </thead>

                <tbody>
                @if($theTruck && !empty($theTruck) && count($theTruck->shedYardWeights)>0)
                    @foreach($theTruck->shedYardWeights as $k=>$shedYardData)
                        <tr>
                            <td>
                                @if($shedYardData->yardDetail)
                                    {{$shedYardData->yardDetail->yard_shed_name}}
                                @endif
                            </td>
                            <td>{{$shedYardData->unload_labor_weight}}</td>
                            <td>{{$shedYardData->unload_equip_weight}}</td>
                            <td>{{$shedYardData->unload_receive_datetime}}</td>
                            <td>{{$shedYardData->unload_shifting_flag}}</td>
                            <td>{{$shedYardData->unload_labor_package}}</td>
                            <td>{{$shedYardData->unload_equipment_package}}</td>


                            <td>
                                <a class="btn btn-success btn-xs mrg" data-original-title="" data-toggle="tooltip"
                                   href="{{ route('maintenance-shed-yard-weight-edit-form',[$shedYardData->id]) }}">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </a>


                                <a class="btn btn-danger delete_btn btn-xs mrg" data-original-title=""
                                   onclick="return confirm('Are you sure?')"
                                   href="{{route('maintenance-shed-yard-weight-delete',[$shedYardData->id])}}">
                                    <i class="fa fa-trash-o"></i>
                                    Delete
                                </a>
                            </td>

                        </tr>
                    @endforeach
                @endif
                </tbody>

            </table>


        </div>
    </div>



@endsection

@section('script')
    {!! Html::script('/js/select2.min.js') !!}
    {!! Html::script('js/jquery.growl.js') !!}

    <script type="text/javascript">
        $(document).ready(function () {
            $(".successOrErrorMsgDiv").delay(3500).slideUp(4000);

        });

    </script>



@endsection