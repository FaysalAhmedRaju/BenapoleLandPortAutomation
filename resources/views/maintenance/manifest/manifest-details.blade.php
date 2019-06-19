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


    <div class="col-md-12" id="manifest-details">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th colspan="3">
                        <a href="{!! route('maintenance-manifest-list-view') !!}"><i
                                    class="fa fa-arrow-circle-o-left"></i>
                            Manifes List
                        </a>
                    </th>
                    <th class="error left" colspan="2">
                        Manifest Details
                        No. {{$theManifest->manifest}}
                    </th>
                    <td>
                        <a class="btn btn-success delete_btn btn-xs"
                           href="{!! route('maintenance-manifest-edit-form',[$theManifest->id]) !!}">
                            <i class="fa fa-trash-o"></i>
                            Edit Manifest Data
                        </a>
                    </td>

                </tr>
                </thead>

                <tbody>
                <tr>
                    <th>G. Weight</th>
                    <td>{{$theManifest->gweight}}</td>
                    <th>Net Weight</th>
                    <td>{{$theManifest->nweight}}</td>
                    <th>Package No</th>
                    <td>{{$theManifest->package_no}}</td>

                </tr>

                <tr>
                    <th>Posted Yard Shed</th>
                    <td>
                        @if($theManifest->shedYard)
                            {{$theManifest->shedYard->shed_yard}}
                        @endif
                    </td>
                    <th>Manifest Date</th>
                    <td>{{$theManifest->manifest_date}}</td>
                    <th>Transshipment Flag</th>
                    <td>{{$theManifest->transshipment_flag}}</td>

                </tr>
                </tbody>
                <tbody>
                <tr>

                    <th colspan="5" class="text-left error">Delivery Data</th>
                    <td>
                        <a class="btn btn-success delete_btn btn-xs"
                           href="{{route('maintenance-warehouse-delivery-delivery-request-edit-form',[$theManifest->id,1])}}">
                            <i class="fa fa-trash-o"></i>
                            Edit Delivery Data
                        </a>
                    </td>
                </tr>
                {{--$theManifest->deliveryRequisitions--}}

                @if($theManifest->deliveryRequisitions)
                    @foreach($theManifest->deliveryRequisitions as $k=>$requisitionData)
                <tr>
                    <th>Delivery Date</th>
                    <td>{{$requisitionData->approximate_delivery_date}}</td>
                    <th>Appx. Labour</th>
                    <td>{{$requisitionData->approximate_labour_load}}</td>
                    <th>Appx. Equ</th>
                    <td>{{$requisitionData->approximate_equipment_load}}</td>

                    {{--<td>--}}
                        {{--<a class="btn btn-success delete_btn btn-xs"--}}
                           {{--href="{{route('maintenance-warehouse-delivery-delivery-request-edit-form',[$theManifest->id])}}">--}}
                            {{--<i class="fa fa-trash-o"></i>--}}
                            {{--Edit Delivery Data--}}
                        {{--</a>--}}
                    {{--</td>--}}
                </tr>
                    @endforeach
                @endif

                </tbody>

            </table>
        </div>


        @if(count($theManifest->assessmentDetails)>0)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th colspan="5" class="text-left error">
                            Assessment Data ({{$theManifest->assessmentDetails->sum('tcharge')}})
                        </th>
                        <td>
                            <a class="btn btn-success delete_btn btn-xs"
                               href="{{route('maintenance-assessment-edit-form',[$theManifest->id,1])}}">
                                <i class="fa fa-trash-o"></i>
                                Edit Assessment Data
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>Sub Head</th>
                        <th>Unit</th>
                        <th>Other Unit</th>
                        <th>Charge</th>
                        <th>Total</th>
                        <th>Partial Status</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($theManifest->assessmentDetails as $k=>$assess)

                        <tr>
                            <td>
                                @if($assess->subHead)
                                    {{$assess->subHead->acc_sub_head}}
                                @else
                                    No Sub Head Found
                                @endif
                            </td>
                            <td>{{$assess->unit}}</td>
                            <td>{{$assess->other_unit}}</td>
                            <td>{{$assess->charge_per_unit}}</td>
                            <td>{{$assess->tcharge}}</td>
                            <td>{{$assess->partial_status}}</td>
                        </tr>

                    @endforeach


                    </tbody>

                </table>

            </div>

        @else
            <p>No Assessment Found!</p>
        @endif


    </div>


    <div class="col-md-12" id="truck-details">

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="error" colspan="10">
                        All Truck List For Manifest
                        No. @if(count($truckList)>0) {{$truckList[0]->manifest->manifest}} @endif
                    </th>

                </tr>
                <tr>
                    <th>S/L</th>
                    <th>Type-No</th>
                    <th>Entry At</th>
                    <th>Received At (shed/yard)</th>
                    <th>Received Weight</th>
                    <th class="text-center">action</th>

                </tr>
                </thead>
                @if(count($truckList)>0)
                    @foreach($truckList as $k=> $truck)
                        <tbody>
                        <tr>
                            <td>{{++$k}} ({{$truck->id}})</td>
                            <td>
                                {{ $truck->truck_type .'-'. $truck->truck_no}}
                            </td>
                            <td>{{ $truck->truckentry_datetime}}</td>
                            <td>
                                @if(count($truck->shedYardWeights)>0)

                                    @foreach($truck->shedYardWeights as $kSyw=>$shedYardWeight)

                                        @if($shedYardWeight->unload_receive_datetime)

                                            {{ $shedYardWeight->unload_receive_datetime}}
                                        @else
                                     (No Date Found )
                                        @endif

                                        @if($shedYardWeight->yardDetail && $shedYardWeight->yardDetail->yard_shed_name)

                                            <b> ({{ $shedYardWeight->yardDetail->yard_shed_name}})</b><br>
                                        @else
                                     (No ShedYard Found )
                                        @endif

                                    @endforeach

                                @else
                                    <p class="text-warning">Not Received Yet</p>
                                @endif
                            </td>
                            <td>
                                @if(count($truck->shedYardWeights)>0)
                                    @php($weightTotal=0)
                                    @foreach($truck->shedYardWeights as $kSyw=>$shedYardWeight)

                                        @php($weightTotal+=($shedYardWeight->unload_labor_weight + $shedYardWeight->unload_equip_weight))

                                    @endforeach
                                    {{$weightTotal}}

                                @else
                                    <p class="text-warning">Not Received Yet</p>
                                @endif
                            </td>

                            <td>
                                <a class="btn btn-info btn-xs mrg" data-original-title="" data-toggle="tooltip"
                                   href="{{ route('maintenance-truck-details',[$truck->id]) }}">
                                    <i class="fa fa-edit"></i>
                                    Details
                                </a>

                                <a class="btn btn-success btn-xs mrg" data-original-title=""
                                   data-toggle="tooltip"
                                   href="{{ route('maintenance-truck-edit-form',[$truck->id]) }}">
                                    <i class="fa fa-edit"></i>
                                    Edit
                                </a>


                                <a class="btn btn-danger delete_btn btn-xs mrg" data-original-title=""
                                   onclick="return confirm('Are you sure?')"
                                   href="{{route('maintenance-truck-delete',[$truck->id])}}">
                                    <i class="fa fa-trash-o"></i>
                                    Delete
                                </a>
                            </td>

                        </tr>
                        </tbody>
                    @endforeach
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
            <div class="pagination">
                {!!   str_replace('/?','?',$truckList->render() ) !!}
            </div>

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