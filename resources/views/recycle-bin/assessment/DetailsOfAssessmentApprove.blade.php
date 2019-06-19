@extends('layouts.master')
@section('title', 'Assessment Approve')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')
    {{--{!!Html :: script('js/customizedAngular/assessment.js')!!}--}}
    {!!Html :: script('js/customizedAngular/AssessmentApprove.js')!!}
    <script>
        var manifestNo = {!! json_encode($manifestNo) !!};
    </script>
@endsection
@section('content')
    <div class="col-md-12 ng-cloak text-center" ng-app="AssessmentApproveApp" ng-controller="AssessmentApproveCtrl">
        <table class="table table-bordered">
            <caption><h4 class="text-center">Manifest Details: {{$manifestNo}}</h4></caption>
            <thead>
            <tr>
                <th>S/L</th>
                <th>Manifest Date</th>
                <th>Description of Goods</th>
                <th>Quantity</th>
                <th>No Of Packages</th>
                <th>C&F Value</th>
                <th>Name & Address of Expoter</th>
                <th>Name & Address of Importer</th>
                <th>L.C No. & Date</th>
                <th>B/E No. and Date</th>
                <th>Indian B/E No. and Date</th>
            </tr>
            </thead>
            <tbody> @php($i=0)
            @foreach($manifestDetails as $key => $manifestDetail)
                <tr>
                    <td> {{ ++$i }}</td>
                    <td>{{ $manifestDetail->manifest_date }}</td>
                    <td>{{ $manifestDetail->cargo_name }}</td>
                    <td>Gr. Wt-{{ $manifestDetail->gweight}} <br> Nt. Wt- {{$manifestDetail->nweight }}</td>
                    <td>{{ $manifestDetail->package_no . " ". $manifestDetail->package_type }}</td>
                    <td>{{ $manifestDetail->cnf_value }}</td>
                    <td>{{ $manifestDetail->exporter_name_addr }}</td>
                    <td>{{ $manifestDetail->NAME . " ". $manifestDetail->ADD1 }}</td>
                    <td>{{ $manifestDetail->lc_no . " ". $manifestDetail->lc_date }}</td>
                    <td>{{ $manifestDetail->be_no . " ". $manifestDetail->be_date}}</td>
                    <td>{{ $manifestDetail->ind_be_no . " ". $manifestDetail->ind_be_date}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <table class="table table-bordered">
            <caption><h4 class="text-center">Indian Truck Details</h4></caption>
            <thead>
            <tr>
                <th>Serial No.</th>
                <th>Manifest No.</th>
                <th>Truck No.</th>
                <th>Driver Name</th>
                <th>Net Weight</th>
                <th>Receive Package</th>
                <th>Receive Date</th>
                <th>Labor Unload</th>
                <th>Equipment Name</th>
                <th>Equipment Load</th>
                <!-- <th>Actions</th> -->
            </tr>
            </thead>
            <tbody>  @php($i=0) @php($sumNetweight=0)
            @foreach($indianTruckData as $key => $indianTruck)
                <tr>
                    <td> {{ ++$i }}</td>
                    <td>{{ $indianTruck->manifest }}</td>
                    <td>{{ $indianTruck->truck_no }}</td>
                    <td>{{ $indianTruck->driver_name }}</td>
                    <td>{{ $indianTruck->nweight }}</td> @php($sumNetweight += $indianTruck->nweight )
                    <td>{{ $indianTruck->receive_package }}</td>
                    <td>{{ $indianTruck->receive_datetime }}</td>
                    <td>{{ $indianTruck->labor_unload }}</td>
                    <td>{{ $indianTruck->equip_name }}</td>
                    <td>{{ $indianTruck->equip_unload }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <table class="table table-bordered">
            <caption><h4 class="text-center">BD Truck Details</h4></caption>
            <thead>
            <tr>
                <th>Serial No.</th>
                <th>Manifest No.</th>
                <th>Truck No.</th>
                <th>Driver Name</th>
                <th>Gross Weight</th>
                <th>Package</th>
                <th>Delivery Date</th>
                <th>Approve Date</th>
                <th>Labor Load</th>
                <th>Equipment Name</th>
                <th>Equipment Load</th>
                <!-- <th>Actions</th> -->
            </tr>
            </thead>
            <tbody> @php($i=0) @php($totalGrossWeight=0)
            @foreach($bdTruckData as $key => $bdTruck)
                <tr>
                    <td> {{ ++$i }}</td>
                    <td>{{ $bdTruck->manifest }}</td>
                    <td>{{ $bdTruck->truck_no }}</td>
                    <td>{{ $bdTruck->driver_name }}</td>
                    <td>{{ $bdTruck->gweight }}</td> @php($totalGrossWeight += $bdTruck->gweight )
                    <td>{{ $bdTruck->package }}</td>
                    <td>{{ $bdTruck->delivery_dt }}</td>
                    <td>{{ $bdTruck->approve_dt }}</td>
                    <td>{{ $bdTruck->labor_load }}</td>
                    <td>{{ $bdTruck->equip_name }}</td>
                    <td>{{ $bdTruck->equip_load }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>


        <div class="col-md-12">

            <table  border="0" class="table text-center-td-th" id="assessment">
                <caption><h4 class="text-center">Assessment Details</h4></caption>

                <tr>
                    <td class="2">
                        Manifest/Export Application No & Data:
                    </td>

                    <td class="2">
                        {{--<u> @{{ m.manifest }}</u> <br> @{{ m.manifest_date }}--}}
                    </td>



                    <td class="2">
                        Consignee:
                    </td>

                    <td class="2">
                        {{--@{{ m.Importer }}--}}
                    </td>
                </tr>

                <tr>
                    <td class="2">
                        Bill Of Entry No & Date Custom's Release Order:
                    </td>

                    <td class="2"> <span ng-show="m.be_date">C-</span>
                        {{--<u> @{{ m.c_no }}</u> <br> @{{ m.be_date }}--}}
                    </td>



                    <td class="2">
                        Shed:
                    </td>

                    <td class="2">
                        {{--@{{ m.posted_yard_shed }}--}}
                    </td>
                </tr>


            </table>


        </div>
        {{----------------------------------------------------First part is closed--}}

        {{--Assesment Sheet------------------START--}}
        <div class="col-md-12 text-center" ng-init="manifestSearch(ManifestNo)" ng-app="assessmentApp" ng-controller="assessmentCtrl">

            {{--<div class="col-md-5 col-md-offset-3" style="">
                <form name="form" class="form-inline" novalidate ng-submit="manifestSearch(searchText)">

                    <div class="form-group">
                        <label for="searchText"> </label>
                        <input type="text" ng-pattern='/^[0-9]{1,10}[/]{1}([0-9]{1,2}|[(A|a)]{1})$/' required="required"  ng-model="searchText" name="searchText"   class="form-control input-sm" id="searchText" placeholder="Enter Manifest No.">
                        <br>
                        <span class="error" ng-show='form.searchText.$error.pattern'>
                                Input like: 256/12 Or 256/A
                    </span>


                    </div>

                    <span ng-if="dataLoading" style="color:green; text-align:center; font-size:12px">
                            <img src="img/dataLoader.gif" width="250" height="15" />
                            <br /> Please wait!
                        </span>

                </form>
                <br>

            </div>--}}

            <div class="col-md-12">

                <table  border="0" class="table text-center-td-th" id="assessment">


                    <tr>
                        <td class="2">
                            <b> Manifest/Export Application No & Data:</b>
                        </td>

                        <td class="2">
                            <u> @{{ m.manifest }}</u> <br> @{{ m.manifest_date }}
                        </td>



                        <td class="2">
                            <b>Consignee:</b>
                        </td>

                        <td class="2">
                            @{{ m.Importer }}
                        </td>
                    </tr>

                    <tr>
                        <td class="2">
                            <b>Bill Of Entry No & Date Custom's Release Order:</b>
                        </td>

                        <td class="2"> <span ng-show="m.be_date">C-</span>
                            <u> @{{ m.c_no }}</u> <br> @{{ m.be_date }}
                        </td>



                        <td class="2">
                            <b> Shed:</b>
                        </td>

                        <td class="2">
                            @{{ m.posted_yard_shed }}
                        </td>
                    </tr>


                </table>


            </div>


            <div class="col-md-12">

                <div class="col-md-3">
                    <h5 class="text-left"><b> 1. WareHouse Rent:</b></h5>
                </div>


                <div class="col-md-6">


                    <table class="table">
                        <thead>
                        <tr>
                            <th>M. Ton(Weight)</th>
                            <th>Period</th>
                            <th>Rate</th>
                            <th>Amount</th>
                        </tr>
                        </thead>


                        <tbody>
                        <tr>
                            <td>@{{ WarehouseReceiveWeight /1000}}</td>
                            <td>@{{ WarehouseNetDay}} Day</td>
                            <td>45.32</td>
                            <td>@{{ WarehouseTotalCharge| number : 2  }}</td>

                        </tr>
                        </tbody>

                    </table>
                </div>
            </div>



            <div class="col-md-12">

                <div class="col-md-3">
                    <h5 class="text-left"><b> 2. Handling Charge:</b></h5>
                </div>


                <div class="col-md-6">


                    <table class="table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>M. Ton(Weight)</th>
                            <th>Rate</th>
                            <th>Amount</th>
                        </tr>
                        </thead>


                        <tbody>
                        <tr>
                            <th>Unload</th>

                            <td>@{{ WeightForHandling /1000}}</td>
                            <td>86.22</td>
                            <td>@{{ TotalForUnloadHandling| number : 2  }}</td>

                        </tr>

                        <tr>
                            <th>Load</th>

                            <td>@{{ WeightForHandling /1000}}</td>
                            <td>86.22</td>
                            <td>@{{ TotalForLoadHandling| number : 2  }}</td>

                        </tr>
                        </tbody>

                    </table>
                </div>

            </div>

            <div class="col-md-12">
                <div class="col-md-3">

                    <h5 class="text-left"><b> 3. Other Dues:</b></h5>
                </div>

                <div class="col-md-6">


                    <table class="table">
                        <thead>
                        <tr>

                            <th>Name</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Rate</th>
                            <th>Amount</th>
                        </tr>
                        </thead>


                        <tbody>
                        <tr>
                            <td rowspan="2" style="vertical-align: middle">Truck Entrance</td>
                            <td>1. Foreign Truck</td>
                            <td>@{{ m.Foreign_Truck }}</td>
                            <td>53.25</td>
                            <td>@{{ foreignTruckAmount }}</td>

                        </tr>
                        <tr>
                            <td>2. Local Truck</td>

                            <td>@{{ m.Local_Truck }}</td>
                            <td>53.25</td>
                            <td>@{{ localTruckAmount }}</td>

                        </tr>
                        </tbody>
                    </table>


                    <table class="table">
                        <thead>
                        <tr>
                            <th colspan="4">Haltage Charge</th>
                        </tr>
                        <tr>
                            <th>Truck No.</th>
                            <th>Day</th>
                            <th>Rate</th>
                            <th>Amount</th>
                        </tr>
                        </thead>


                        <tbody>

                        <tr ng-repeat="item in HaltageData">

                            <td>@{{ item.truck_no }}</td>
                            <td>@{{item.NetDay| ceil  }}</td>
                            <td>71.86</td>
                            <td><span>@{{ (item.NetDay| ceil) *71.86}}</span> </td>

                        </tr>


                        {{--   <tr>
                               <td>Holtage Charge</td>
                               <td>@{{HaltageDay| number : 2  }}</td>
                               <td>10</td>
                               <td>@{{ holtageAmount }}</td>

                           </tr>--}}
                        </tbody>
                    </table>





                </div>
            </div>



            <div class="col-md-6 col-md-offset-3">


                <table class="table">

                    <tbody>
                    <tr>

                        <td class="text-right"><b>Total:</b> <span>@{{ TotalAmount | ceil}}</span> </td>

                    </tr>
                    <tr>

                        <td class="text-right"><b>In Word:</b>  <span class="text-capitalize" id="totalInWord"> </span></td>

                    </tr>



                    </tbody>

                </table>

            </div>





        </div>

        {{--Assesment Sheet------------------END--}}


        {{--Approve panel Starts --}}

        <div class="col-md-4 col-md-offset-3" style="background-color: #dbd3ff; border-radius: 20px;">


            <h4 style="text-align: center;">Approve Panel</h4>
            <div class="alert alert-success" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
            <div class="alert alert-danger" ng-hide="!savingError">@{{ savingError }}</div>
            <div class="col-md-12 col-md-offset-2">
                <table>
                    <tr>
                        <th>Comment:</th>
                        <td>
                            <input class="form-control" type="text" name="approve_comment" id="approve_comment" ng-model="approve_comment">
                            <span class="error" ng-show="verify_comm_required">Approve Comment is Required</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr >
                        <td>
                            <button type="button" class="btn btn-danger center-block" ng-click="rejectmanifest()">Reject</button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary center-block" ng-click="approve()">Approve</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
@endsection