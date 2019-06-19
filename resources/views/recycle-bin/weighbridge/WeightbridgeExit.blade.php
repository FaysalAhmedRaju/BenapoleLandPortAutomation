@extends('layouts.master')
@section('title', 'WeightBridge Exit')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')
    <script type="text/javascript">
        {{-- $('.clockpicker').clockpicker(); --}}

        $('#wbrdge_time2').datepicker({
            dateFormat: 'yy-mm-dd',
        })
    </script>
    {!!Html :: script('js/bootbox.min.js')!!}
    {!!Html :: script('js/customizedAngular/weightBridgeExit.js')!!}
@endsection
@section('content')
        <div class="col-md-12 ng-cloak" ng-app="weightBridgeEntryApp" ng-controller="weightBridgeEntryController">

            <div class="col-md-8 col-md-offset-4">
                <form class="form-inline" ng-submit="searchManifestOrTruck(searchKey, searchField)" name="ManifestSearchForm">
                    <div class="form-group">
                        Search By:
                        <select ng-change="select()" class="form-control" name="searchKey"
                                ng-model="searchKey">
                            <option value="manifestNo">Manifest No</option>
                            <option value="truckNo">Truck No</option>
                        </select>
                        <input type="text" name="searchField" ng-model="searchField" id="searchField" class="form-control" placeholder="@{{ placeHolder }}" ng-keydown="keyBoard($event)">
                    </div>
                </form>
                <div class="clearfix"></div>
                <div class="col-md-4 col-md-offset-4" >
                    <a href="{{ url('todaysWeightBridgeExitPDF') }}" target="_blank"><button type="button" class="btn btn-primary"><span class="fa fa-search"></span>Today's Weight Bridge Exit</button></a>
                </div>
            </div>
            <div class="clearfix"></div>
            <br>


            <div class="col-md-8 col-md-offset-2" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">WeightBridge Exit</h4>
                <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
                <div class="clearfix"></div>
                <div class="col-md-7">
                    <table class="table" style="text-align: left;">
                        <tr ng-if="serachBytruck">
                            <th>Manifest:</th>
                            <td>@{{manifest}}</td>
                        </tr>
                        <tr ng-if="!serachBytruck">
                            <th>Truck No:</th>
                            <td>@{{ truck_type + " " + truck_no }}</td>
                        </tr>
                        <tr>
                            <th>Cargo Name:</th>
                            <td>@{{ goods }}</td>
                        </tr>
                       {{--<tr>
                            <th>Cargo Description:</th>
                            <td>@{{ goodsData[0].cargo_description }}</td>
                        </tr>--}}
                        <tr>
                            <th>Gross Weight:</th>
                            <td>@{{ gweight_wbridge }}</td>
                        </tr>
                    </table>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-12">
                    <form  name="WeightBridgeExit" id="WeightBridgeExit" novalidate>
                    <table>
                        <tr>
                            <th>Tare Weight:</th>
                            <td>
                                <input type="number" class="form-control" ng-model="tr_weight" name="tr_weight" id="tr_weight" ng-disabled="show" ng-change="getNetweight()">
                                <span class="error" ng-show="tr_weight_required">Tar Weight is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Exit Date:</th>
                            <td>
                                <input type="text" class="datePicker form-control"  ng-model="wbrdge_time2" name="wbrdge_time2" id="wbrdge_time2" ng-disabled="show">
                                <span class="error" ng-show="wbrdge_time2_required" >Date is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Net Weight:</th>
                            <td>
                                <input type="number" class="form-control" name="tweight_wbridge" id="tweight_wbridge" ng-model="tweight_wbridge" ng-disabled="show_tweight_wbridge">
                                <span class="error" ng-show="tweight_wbridge_required">Total Weight Weighbridge is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center">
                                <button type="button" class="btn btn-primary center-block" ng-click="save()" ng-disabled="show" ng-if="ButtonExit">Save</button>
                            </td>
                        </tr>
                    </table>
                    </form>
                    <br>
                </div>
            </div>

            
            <div class="clearfix"></div>
                <div class="col-md-12 table-responsive" style="padding: 10px;" >
                    <div class="alert alert-danger" id="Error" ng-hide="!Error">@{{ Error }}</div>
                    <table class="table table-bordered table-hover table-striped" ng-show="table">
                        <caption><h4 class="text-center ok">Truck Details</h4></caption>
                        <thead>
                        <tr>
                            <th>S/L</th>
                            <th>Manifest No.</th>
                            <th>Truck No.</th>
                            {{--<th>Goods ID</th>--}}
                            <th>Driver Name</th>
                            <th>Gross Weight</th>
                            <th>Weighbridge Entry Date</th>
                            <th>Tare Weight</th>
                            <th>Net Weight</th>
                            <th>Weightbridge Exit Date</th>
                           {{-- <th>Action</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                        <tr {{--ng-class="{'selectedClass' : selectedStyle == truck.id}"--}}
                        ng-style="{'background-color':(truck.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="truck in allTrucksData.data | orderBy:'truck.id' | itemsPerPage:5" ng-click="update(truck)">
                            <td>@{{$index+1}}</td>
                            <td>@{{truck.manifest}}</td>
                            <td>@{{truck.truck_type +"-"+ truck.truck_no}}</td>
                            {{--<td>@{{truck.goods_id}}</td>--}}
                            <td>@{{truck.driver_name}}</td>
                            <td>@{{truck.gweight_wbridge}}</td>
                            <td>@{{truck.wbrdge_time1}}</td>
                            <td>@{{truck.tr_weight}}</td>
                            <td>@{{truck.tweight_wbridge}}</td>
                            <td>@{{truck.wbrdge_time2}}</td>
                         {{--<td>
                                <a class="btn btn-primary" ng-click="update(truck)">Update</a>
                            </td> --}}
                        </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10" class="text-center">
                                    <dir-pagination-controls max-size="5"
                                                         direction-links="true"
                                                         boundary-links="true">
                                    </dir-pagination-controls>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
        </div>
@endsection