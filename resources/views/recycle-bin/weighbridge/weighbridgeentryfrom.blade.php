@extends('layouts.master')
@section('title', 'Weight Bridge Entry')
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

        $('#wbrdge_time1').datepicker({
            dateFormat: 'yy-mm-dd',
        })
    </script>
    {!!Html :: script('js/customizedAngular/weightBridgeEntry.js')!!}
@endsection
@section('content')
        <div class="col-md-12 ng-cloak" ng-app="weightBridgeEntryApp" ng-controller="weightBridgeEntryController">

            <div class="col-md-8 col-md-offset-4">
                <form class="form-inline" ng-submit="searchManifest()">
                    <div class="form-group">
                        <input type="text" name="ManifestNo" ng-model="ManifestNo" id="ManifestNo" {{--ng-change="searchManifest()"--}} class="form-control" placeholder="Enter Manifest No">
                    </div>
                 {{--<button type="button" class="btn btn-primary" ng-hide="!ManifestNo" ng-click="searchManifest()"><span class="fa fa-search"></span> Search Manifest</button>--}} 
                </form>
                <div class="clearfix"></div>
                <div class="col-md-4 col-md-offset-4" >
                    <a href="{{ url('todaysWeightBridgeEntryPDF') }}" target="_blank"><button type="button" class="btn btn-primary"><span class="fa fa-search"></span>Today's Weight Bridge Entry</button></a>
                </div>
            </div>


            <div class="clearfix"></div>
            <br>


            <div class="col-md-8 col-md-offset-2" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">Weight Bridge Entry</h4>
                <div class="alert alert-success" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                <div class="alert alert-danger" ng-hide="!savingError">@{{ savingError }}</div>
                <div class="clearfix"></div>
                <div class="col-md-7">
                    <table class="table" style="text-align: left;">
                        <tr>
                            <th>Truck No:</th>
                            <td>@{{ truck_type + " " + truck_no }}</td>
                        </tr>
                        <tr>
                            <th>Cargo Name:</th>
                            <td>@{{ goodsData[0].cargo_name }}</td>
                        </tr>
                       {{--<tr>
                            <th>Cargo Description:</th>
                            <td>@{{ goodsData[0].cargo_description }}</td>
                        </tr>--}}
                    </table>
                </div>
                {{-- <div class="label" ng-show="label" style="color:black;">
                            <h4 ng-model="truck_no">You Have Selected Truck No: <span class="label label-success"> @{{ truck_no }} </span></h4>
                            <h4>Cargo Name: @{{ goodsData[0].cargo_name }}</h4>
                            <h4>Cargo Description: @{{ goodsData[0].cargo_description }}</h4>
                    </div> --}}
                {{-- <form name="weightBridgeEntry" class="form-horizontal"  method="POST">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label for="gweight_wbridge">Gross Weight Weighbridge</label>
                                <div class="input-group">
                                    <input type="number" name="gweight_wbridge" id="gweight_wbridge" class="form-control" ng-model="gweight_wbridge" ng-disabled="show" required>

                                </div>
                                <span class="help-block" style="color:red;" ng-show="weightBridgeEntry.gweight_wbridge.$error.required">Gross Weight Weighbridge is Required</span>
                            </div>


                            <div class="col-sm-4">
                                <label for="tweight_wbridge">Total Weight Weighbridge</label>
                                <div class="input-group">
                                    <input type="number" name="tweight_wbridge" id="tweight_wbridge" class="form-control" ng-model="tweight_wbridge" ng-disabled="show" required>


                                </div>
                                <span class="help-block" style="color:red;" ng-show="weightBridgeEntry.tweight_wbridge.$error.required">Total Weight Weighbridge is Required</span>
                            </div>


                            <div class="col-sm-4">
                                <label for="wbrdge_time1">Weighbridge Time</label>

                                <div class="input-group" >
                                    <input type="text" class="form-control  datePicker"  ng-model="wbrdge_time1" name="wbrdge_time1" id="wbrdge_time1" ng-disabled="show" required>

                                </div>
                                <span class="help-block" style="color:red;" ng-show="weightBridgeEntry.wbrdge_time1.$error.required" >Weighbridge Time is Required</span>
                            </div>

                        </div>

                        <div class="col-md-2 col-md-offset-5">
                            <button type="button" class="btn btn-primary btn-block" ng-click="save()" ng-disabled="!weightBridgeEntry.$valid">Save</button>

                        </div>

                </form> --}}
                <div class="clearfix"></div>


                <table>
                    <tr>
                        <th>Gross Weight:</th>
                        <td>
                            <input type="number" class="form-control" name="gweight_wbridge" id="gweight_wbridge" ng-model="gweight_wbridge" ng-disabled="show || showGrossWeight" ng-change="getNetweight()">
                            <span class="error" ng-show="gweight_wbridge_required">Gross Weight Weighbridge is Required</span>
                        </td>
                        <th style="padding-left: 15px;">Net Weight:</th>
                        <td>
                            <input type="number" class="form-control" name="tweight_wbridge" id="tweight_wbridge" ng-model="tweight_wbridge" ng-disabled="show || showNetWeight">
                            <span class="error" ng-show="tweight_wbridge_required">Total Weight Weighbridge is Required</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Tare Weight:</th>
                        <td>
                            <input type="number" class="form-control" ng-model="tr_weight" name="tr_weight" id="tr_weight" ng-disabled="show || showTr_weight" ng-change="getNetweight()">
                            <span class="error" ng-show="tr_weight_required">Tar Weight is Required</span>
                        </td>
                        <th style="padding-left: 15px;">Date:</th>
                        <td>
                            <input type="text" class="datePicker form-control"  ng-model="wbrdge_time1" name="wbrdge_time1" id="wbrdge_time1" ng-disabled="show || showDate">
                            <span class="error" ng-show="wbrdge_time1_required" >Date is Required</span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-center">
                            <button type="button" class="btn btn-primary center-block" ng-click="save()" ng-disabled="show"{{--ng-disabled="!weightBridgeEntry.$valid"--}}>Save</button>
                        </td>
                    </tr>
                </table>
                <br>
            </div>

            
            <div class="clearfix"></div>
                <div class="col-md-12 table-responsive" style="padding: 10px;" >
                    <div class="alert alert-danger" id="ManifestError" ng-hide="!ManifestError">@{{ ManifestError }}</div>
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
                            <th>Net Weight</th>
                            <th>Weighbridge Entry Date</th>
                            <th>Tare Weight</th>
                            <th>Weightbridge Exit Date</th>
                            
                           {{-- <th>Action</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                        <tr {{--ng-class="{'selectedClass' : selectedStyle == truck.id}"--}}
                        ng-style="{'background-color':(truck.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="truck in allTrucksData.data | orderBy:'truck.id' | itemsPerPage:5" ng-click="update(truck)">
                            <td>@{{$index+1}}</td>
                            <td>@{{truck.manifest}}</td>
                            <td>@{{truck.truck_type +" "+ truck.truck_no}}</td>
                            {{--<td>@{{truck.goods_id}}</td>--}}
                            <td>@{{truck.driver_name}}</td>
                            <td>@{{truck.gweight_wbridge}}</td>
                            <td>@{{truck.tweight_wbridge}}</td>
                            <td>@{{truck.wbrdge_time1}}</td>
                            <td>@{{truck.tr_weight}}</td>
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