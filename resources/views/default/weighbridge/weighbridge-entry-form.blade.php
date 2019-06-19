@extends('layouts.master')
@section('title', 'WeighBridge Entry/Exit')
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
    {!!Html :: script('js/customizedAngular/weighbridge/weightBridgeEntry.js')!!}
    {!!Html :: script('js/bootbox.min.js')!!}
@endsection
@section('content')
        <div class="col-md-12 ng-cloak" ng-app="weightBridgeEntryApp" ng-controller="weightBridgeEntryController" >
            {{-- <div class="col-md-10 col-md-offset-1" style="padding-bottom: 30px;">
                <button type="button"  class="btn btn-info" data-toggle="collapse" data-target="#report">Report</button>
                <div id="report" class="collapse">
                    <div class="col-md-4 col-md-offset-3" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">
                        <h4 class="text-center">Date Wise Weightbridge Entry</h4>
                        <form action="{{ url('getDateWiseWeightbridgeEntryReportPDF') }}" target="_blank" method="POST">
                            {{ csrf_field() }}
                            <div class="col-md-12">
                                <table>
                                <br>
                                    <tr>
                                        <th>Date:</th>
                                        <td>
                                            <input type="text" class="form-control datePicker" name="date" id="date">
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary center-block">Show</button>
                                        </td>
                                    </tr>
                                </table>
                                <br>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-12">
                    <br>
                       <div class="list-group text-center">
                            <a class="list-group-item" href="{{ url('truckEntryDoneButWeightbridgeEntryNotDoneReport') }}" target="_blank">Truck Entry Done, But Weightbridge Entry Not Done Report</a>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="col-md-12">
                <div class="col-md-5">
                    <form class="form-inline" ng-submit="searchManifestOrTruck(searchKey, searchField)" name="ManifestSearchForm">
                        <div class="form-group">
                            Search By:
                            <select ng-change="select()" class="form-control" name="searchKey"
                                    ng-model="searchKey">
                                <option value="manifestNo">Manifest No</option>
                                <option value="truckTypeNo">Truck Type-No</option>
                            </select>
                            <input type="text" name="searchField" ng-model="searchField" id="searchField" class="form-control" placeholder="@{{ placeHolder }}" ng-keydown="keyBoard($event)" style="width: 125px;">
                        </div>
                    </form>
                </div>
                <div class="col-md-3" style="padding: 0">
                    <form action="{{ route('weighbridge-get-date-wise-entry-report') }}" class="form-inline" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <input type="text"  class="form-control datePicker" ng-model="dateWiseReport"
                                   name="date" id="date" placeholder="Select Entry Date">
                            <div class="input-group-btn">
                                {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                                <button ng-disabled="!dateWiseReport" type="submit" class="btn btn-primary">
                                    {{-- <span class="fa fa-calendar-o"></span>--}} Enrty Report
                                </button>
                            </div>
                        </div>
                    </form>
                    {{--<a href="{{ url('todaysWeightBridgeEntryPDF') }}" target="_blank"><button type="button" class="btn btn-primary"><span class="fa fa-search"></span>Today's Entry</button></a>--}}
                    {{--<a href="{{ url('todaysWeightBridgeExitPDF') }}" target="_blank"><button type="button" class="btn btn-primary"><span class="fa fa-search"></span>Today's Exit</button></a>--}}


                </div>
                <div class="col-md-4">
                    <form action="{{ route('weighbridge-get-date-wise-exit-report') }}" class="form-inline" target="_blank" method="POST">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <input type="text"  class="form-control datePicker" ng-model="exitDate"
                                   name="date" id="exitDate" placeholder="Select Exit Date">
                            <div class="input-group-btn">
                                {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                                <button ng-disabled="!exitDate" type="submit" class="btn btn-primary">
                                    {{-- <span class="fa fa-calendar-o"></span>--}} Exit Report
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="clearfix"></div>
            <br>





            <div class="col-md-12">
            <p  style="text-align: center"><b>Today's Total Entry</b> : <span class="label label-success"> @{{ entry_truck }}</span> Trucks.&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;<b>Today's Total Exit</b> : <span class="label label-success">@{{ exit_truck }}</span> Trucks. </p>
            <p style="text-align: center;">Your are from
                @if (count( Auth::user()->weighbridges)>0)
                    @foreach(Auth::user()->weighbridges as $k=>$v)
                    <span class="label label-info">
                          {{$v->scale_name }}
                        </span>
                @endforeach
                @else
                    Not Found. Please contact Admin.
                @endif
                
            </p>
            <div>



            <div class="col-md-8 col-md-offset-2">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#weightBridgeEntry">Weightbridge Entry</a></li>
                    <li><a data-toggle="tab" href="#weightBridgeExit">Weightbridge Exit</a></li>
                </ul>
            </div>

            <div class="tab-content">
                <div class="col-md-8 col-md-offset-2 tab-pane active" id="weightBridgeEntry" style="background-color: #f8f9f9; border-radius: 20px;">
                    <h4 class="text-center ok">Weighbridge Entry</h4>
                    <div class="alert alert-success" id="savingSuccessEntry" ng-hide="!savingSuccessEntry">@{{ savingSuccessEntry }}</div>
                    <div class="alert alert-danger" id="savingErrorEntry" ng-hide="!savingErrorEntry">@{{ savingErrorEntry }}</div>
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
                        </table>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <form name="WeightBridgeIN" id="WeightBridgeIN" novalidate>
                        <table>
                            <tr>
                                <th>Gross Weight <span class="mandatory">*</span> : </th>
                                <td>
                                    <input type="number" class="form-control" name="gweight_wbridge" id="gweight_wbridge" ng-model="gweight_wbridge" ng-disabled="show" ng-change="getNetweightEntry()">
                                    <span class="error" ng-show="gweight_wbridge_required">Gross Weight Weighbridge is Required</span>
                                </td>
                                <th style="padding-left: 15px;">Entry Date:</th>
                                <td>
                                    <input type="text" class="datePicker form-control"  ng-model="wbrdge_time1" name="wbrdge_time1" id="wbrdge_time1" ng-disabled="show">
                                    <span class="error" ng-show="wbrdge_time1_required" >Date is Required</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>
                            <tr ng-show="whenTrWeightFound">
                                <th>Tare Weight:</th>
                                <td>
                                    <input type="number" class="form-control" ng-model="tr_weight_from_Entry" name="tr_weight_from_Entry" id="tr_weight_from_Entry" ng-disabled="show || showWhenTrWeightFound" ng-change="getNetweightEntry()">
                                    <span class="error" ng-show="tr_weight_required">Tar Weight is Required</span>
                                </td>
                                <th style="padding-left: 15px;">Net Weight:</th>
                                <td>
                                    <input type="number" class="form-control" name="tweight_wbridge" id="tweight_wbridge" ng-model="tweight_wbridge" ng-disabled="show || showWhenTrWeightFound">
                                    <span class="error" ng-show="tweight_wbridge_required">Total Weight Weighbridge is Required</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <button type="button" class="btn btn-primary center-block" ng-click="saveEntry()" ng-disabled="show" ng-if="ButtonIN">
                                    <span class="fa fa-file"></span> Save</button>

                                </td>
                            </tr>
                        </table>
                        </form>
                        <br>
                    </div>
                </div>
                <div class="col-md-8 col-md-offset-2 tab-pane" id="weightBridgeExit" style="background-color: #f8f9f9; border-radius: 20px;">
                    <h4 class="text-center ok">Weighbridge Exit</h4>
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
                                <td>@{{ gweight_wbridge_view }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-12">
                        <form  name="WeightBridgeExit" id="WeightBridgeExit" novalidate>
                        <table>
                            <tr>
                                <th>Tare Weight <span class="mandatory">*</span> :</th>
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
                                    <button type="button" class="btn btn-primary center-block" ng-click="saveExit()" ng-disabled="show" ng-if="ButtonExit"><span class="fa fa-file"></span> Save</button>
                                </td>
                            </tr>
                        </table>
                        </form>
                        <br>
                    </div>
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
                            <th>Weighbridge Exit Date</th>
                           {{-- <th>Action</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                        <tr {{--ng-class="{'selectedClass' : selectedStyle == truck.id}"--}}
                        ng-style="{'background-color':(truck.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="truck in allTrucksData | orderBy:'truck.id' | itemsPerPage:15" ng-click="update(truck)">
                            <td>@{{$index+1}}</td>
                            <td>@{{truck.manifest}}</td>
                            <td>@{{truck.truck_type +"-"+ truck.truck_no}}</td>
                            {{--<td>@{{truck.goods_id}}</td>--}}
                            <td>@{{truck.driver_name}}</td>
                            <td>@{{truck.gweight_wbridge | number : 2}}</td>
                            <td>@{{truck.wbrdge_time1}}</td>
                            <td>@{{truck.tr_weight | number : 2}}</td>
                            <td>@{{truck.tweight_wbridge | number : 2}}</td>
                            <td>@{{truck.wbrdge_time2}}</td>
                         {{--<td>
                                <a class="btn btn-primary" ng-click="update(truck)">Update</a>
                            </td> --}}
                        </tr>
                        </tbody>

                        <tfoot>
                        <tr>
                            <th colspan="4">Total: </th>
                            <td>
                                <span ng-if="total_gweight_wbridge>0">
                                    @{{ total_gweight_wbridge | number : 2}}
                                </span>
                            </td>
                            <td> </td>
                            <td>
                                <span ng-if="total_tr_weight>0">
                                    @{{ total_tr_weight | number : 2 }}
                                </span>
                            </td>
                            <td>
                                <span ng-if="total_tweight_wbridge>0">
                                    @{{ total_tweight_wbridge | number : 2 }}
                                </span>
                            </td>
                            <td> </td>


                        </tr>
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
                <script type="text/javascript">
                    $(function() {
                        $("#month_year, #month_entry_exit, #month_year_income_statement, #month_year_receipts_and_payment").on('focus blur click',function () {
                            $(".ui-datepicker-calendar").hide();

                        });

                        $('#month_year, #month_entry_exit, #month_year_income_statement, #month_year_receipts_and_payment').datepicker( {
                            changeMonth: true,
                            changeYear: true,
                            showButtonPanel: true,
                            dateFormat: 'MM yy',
                            onClose: function(dateText, inst) {
                                function isDonePressed(){
                                    return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                                }
                                if (isDonePressed()){

                                    var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                                    var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                                    $(this).datepicker('setDate', new Date(year, month, 1)).trigger('input');
                                    //console.log(a);

                                }
                            }
                        });
                    });
                </script>
        </div>
@endsection