@extends('layouts.master')
@section('title', 'Welcome Local Truck Gateout')
@section('style')
  <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')
    {!!Html :: script('js/customizedAngular/localTruckGate.js')!!}
@endsection
@section('content')
	<div class="col-md-12 ng-cloak text-center" ng-app="localTruckGateOutApp" ng-controller="localTruckGateOutCtrl">
		<div class="col-md-7 col-md-offset-2" style="border:1px solid green">
            <form name="form" class="form-inline" novalidate ng-submit="manifestOrTruckNoSearch(searchText)">
                <div class="form-group">
                	<br>
                    Search By:
                    <select ng-change="select()" class="form-control" name="selection" ng-model="selection">
                        <option value="">---Please Select---</option>
                        <option value="manifestNo">Manifest No</option>
                        <option value="truckNo">Truck No</option>    
                    </select>
                    <input type="text" required="required" ng-model="searchText" name="searchText" class="form-control input-sm" id="searchText" ng-disabled="serachField" placeholder="@{{ placeHolder }}" ng-change="clear()" ng-keydown="keyBoard($event)">
                </div>
            </form>
            <br>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-4 col-md-offset-8" >
            <a href="{{ route('gateout-todays-gateout-entry-report') }}" target="_blank" class="btn btn-success"><span class="fa fa-search"></span>Today's GateIn</a>
            <a href="{{ route('gateout-todays-gateout-exit-report') }}" target="_blank" class="btn btn-primary"><span class="fa fa-search"></span>Today's GateOut</a>
            <form action="{{ route('gateout-local-truck-gate-pass-sheet-report') }}" target="_blank" method="POST">
                {{ csrf_field() }}
                <input ng-show="ff" class="form-control" value="@{{ searchText }}" type="text" name="manifest"
                       id="manifest">
                <button type="submit" ng-disabled="!searchText" class="btn btn-primary center-block"> Gate Pass
                </button>
            </form>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12 table-responsive">
        	<div class="alert alert-danger" ng-hide="!notFoundError">@{{ notFoundError }}</div>
        	 <table class="table table-bordered" ng-show="table">
                <caption><h4 class="text-center">Manifest Details:  @{{ searchText }}</h4></caption>
                <thead>
                <tr>
                    <th>S/L</th>
                    <th>Truck No</th>
                    <th>Manifest No</th>
                    <th>Manifest Date</th>
                    <th>B/E No</th>
                    <th>B/E Date</th>
                    <th>Goods Name</th>
                    <th>Marks</th>
                    <th>Quantity</th> {{-- Local Truck gweight--}}
                    <th>Report</th>
                    <th>Gate In</th>
                    <th>Gate Out</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="manifestWithLocalTruck in allManifestWithLocalTruckData | orderBy:'manifestWithLocalTruck.id':false | itemsPerPage:5" pagination-id="localTruck">
                    <td>@{{$index+1}}</td>
                    <td>@{{manifestWithLocalTruck.truck_no}}</td>
                    <td>@{{manifestWithLocalTruck.manifest}}</td>
                    <td>@{{manifestWithLocalTruck.manifest_date}}</td>
                    <td>@{{manifestWithLocalTruck.be_no}}</td>
                    <td>@{{manifestWithLocalTruck.be_date}}</td>
                    <td>@{{manifestWithLocalTruck.cargo_name}}</td>
                    <td>@{{manifestWithLocalTruck.marks_no}}</td>
                    <td>@{{manifestWithLocalTruck.loading_unit}}</td> {{-- Local Truck loading_unit--}}
                    <td>
                        <a class="btn btn-default" href="/gateout/report/get-local-truck-details-report/@{{manifestWithLocalTruck.id}}" target="_blank">PDF</a>
                    </td>
                    <td>
                        <button type="button" class="btn btn-success" ng-if="!manifestWithLocalTruck.entry_dt" ng-click="modalInfo(manifestWithLocalTruck)" data-target="#EntryModal" data-toggle="modal">Gate In</button>
                        <b ng-if="manifestWithLocalTruck.entry_dt" style="color: green;">@{{ manifestWithLocalTruck.entry_dt }}</b>
                    </td>
                    <td>
                        <button type="button" ng-if="!manifestWithLocalTruck.exit_dt" class="btn btn-primary" ng-click="modalInfo(manifestWithLocalTruck)" data-target="#ExitModal" data-toggle="modal" ng-disabled="!manifestWithLocalTruck.entry_dt">Gate Out</button>
                        <b ng-if="manifestWithLocalTruck.exit_dt" style="color: red;">@{{ manifestWithLocalTruck.exit_dt }}</b>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="12" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="localTruck">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        {{---------------------------------Entry Model------------------------}}
        <div class="modal fade" style="left:0px; " id="EntryModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">Do you want to Gate In?</h4>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Truck No</th>
                                <th>Manifest No</th>
                                <th>Manifest Date</th>
                                <th>B/E No</th>
                                <th>B/E Date</th>
                                <th>Goods Name</th>
                                <th>Marks</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>@{{exit_truck_no}}</td>
                                <td>@{{exit_manifest}}</td>
                                <td>@{{exit_manifest_date}}</td>
                                <td>@{{exit_be_no}}</td>
                                <td>@{{exit_be_date}}</td>
                                <td>@{{exit_cargo_name}}</td>
                                <td>@{{exit_marks_no}}</td>
                                <td>@{{exit_loading_unit}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <span style="font-weight: bold;">Entry Comment:</span>
                        <input type="text" name="entry_comment" ng-model="entry_comment">
                        <button type="button" class="btn btn-primary" ng-click="getIn()" ng-disabled="whenEntrySuccessfull">Entry</button>
                    </div>
                    <div class="modal-footer">
                        <span class="error text-center" ng-show="entryError">Something wrong!</span>
                        <div class="alert alert-success text-center" ng-show="entrySuccessfull">
                            Entry Done Successfully !
                        </div>
                        <button type="button" class="btn btn-warning center-block" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ------------------------Entry Model----------------------------}}
       {{--------------------------------- Exit Model------------------------}}
        <div class="modal fade" style="left:0px; " id="ExitModal" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center">Do you want to Gate Out?</h4>
                        {{--<span class="text-center">Manifest No: <b>@{{ exit_manifest }}</b>&nbsp;</span>
                        <span class="text-center">&nbsp;&nbsp;Truck No: <b>@{{ exit_truck_no }}</b></span>--}}
                        {{--<a class=" btn btn-primary pull-right" href="getLocalTruckDetailsPDF/@{{exit_id}}" target="_blank">PDF</a>--}}
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Truck No</th>
                                <th>Manifest No</th>
                                <th>Manifest Date</th>
                                <th>B/E No</th>
                                <th>B/E Date</th>
                                <th>Goods Name</th>
                                <th>Marks</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>@{{exit_truck_no}}</td>
                                <td>@{{exit_manifest}}</td>
                                <td>@{{exit_manifest_date}}</td>
                                <td>@{{exit_be_no}}</td>
                                <td>@{{exit_be_date}}</td>
                                <td>@{{exit_cargo_name}}</td>
                                <td>@{{exit_marks_no}}</td>
                                <td>@{{exit_loading_unit}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <span style="font-weight: bold;">Exit Comment:</span>
                        <input type="text" name="exit_comment" ng-model="exit_comment">
                        <button type="button" class="btn btn-primary" ng-click="getOut()" ng-disabled="whenExitSuccessfull">Exit</button>
                    </div>
                    <div class="modal-footer">
                        <span class="error text-center" ng-show="exitError">Something wrong!</span>
                        <div class="alert alert-success text-center" ng-show="exitSuccessfull">
                            Exit Done Successfully !
                        </div>
                        <button type="button" class="btn btn-warning center-block" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ------------------------Exit Model----------------------------}}
    </div>


@endsection