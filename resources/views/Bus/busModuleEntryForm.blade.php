@extends('layouts.master')
@section('title', 'Truck Entry/Exit')
@section('style')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}
@endsection
@section('script')
    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}
    {!! Html::script('js/customizedAngular/busModuleEntryForm.js') !!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="BusModuleEntryFormApp" ng-controller="BusModuleEntryFormCtrl">
        <div class="col-md-12 {{--col-md-offset-1--}}">
            <div class="col-md-4 {{--col-md-offset-9--}}" {{--style="background-color: green" --}} style="/*background-color: red;*/ text-align: left; padding: 0px;">
                <form action="{{ route('export-bus-month-wise-export-bus-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        {{--<table>--}}
                        {{--<tr>--}}
                        {{--<th>From Date:</th>--}}
                        {{--<td>--}}
                        <input style="width: 100px" type="text" placeholder="From Date"
                               class="form-control datePicker" name="from_date_v" id="from_date_v">
                        {{--</td>--}}
                        {{--<th style="padding-left: 40px;">To Date:</th>--}}
                        {{--<td>--}}
                        <input style="width: 100px" type="text" class="form-control datePicker"
                               placeholder="To Date"
                               name="to_date_v" id="to_date_v">
                        {{--</td>--}}
                        {{--<td style="padding-left: 10px;">--}}
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary center-block">
                                Month Wise Report
                            </button>
                        </div>
                        {{--</td>--}}
                        {{--</tr>--}}
                        {{--</table>--}}
                    </div>
                </form>
            </div>

            <div class="col-md-2 {{--col-md-offset-1--}}" style="/*background-color: red;*/ text-align: right; padding: 0px;" >
                <a href="{{ route('export-bus-get-todays-bus-entry-report') }}" target="_blank">
                    <button type="button" class="btn btn-primary">
                        <span class="fa fa-search"></span>Today's Entry
                    </button>
                </a>
            </div>

            <div class="col-md-3 {{--col-md-offset-0--}}"   style="/*background-color: yellow;*/ text-align: right; padding: 0px;"  >

                <form action="{{ route('export-bus-get-date-wise-bus-entry-report') }}" target="_blank" method="POST" class="form-inline">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text" class="form-control datePicker" name="from_date_b" id="from_date_b" placeholder="Select Date" ng-model="from_date_b">

                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary" ng-disabled="!from_date_b">Date Wise Report</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-3 {{--col-md-offset-1--}}">

                <form action="{{ route('export-bus-yearly-bus-entry-report-api') }}" target="_blank" method="get">
                    <table>
                        <tr >

                            <td style=" border-right: 200px; border-left: 200px; width: 150px">
                                <select class="form-control" name="year">
                                    @foreach($year as $item)
                                        <option value="{{$item->year}}">{{$item->year}}-{{$item->year+1}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td style="">
                                <button type="submit" class="btn btn-primary center-block">Yearly Report</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

        </div>



        <div class="breakDiv" style="border: 10px;  margin-top: 50px; margin-bottom: 50px">
            {{--   <p>dfdfd</p>--}}
        </div>






        <div class="col-md-9 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 20px;">
            {{--<tr>--}}
            {{--<td colspan="6">&nbsp;</td>--}}
            {{--</tr>--}}
            {{--&nbsp;--}}
            <h4 class="text-center ok">Bus Entry Form</h4>
            <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
            <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
            <div class="alert alert-success" id="savingSuccessCombo" ng-hide="!savingSuccessCombo" ng-show="savigSuccessCombo">@{{ savingSuccess_combo }}</div>
            <div class="alert alert-danger" id="savingErrorCombo" ng-hide="!savingErrorCombo" ng-show="ErrorCombo">@{{ savingError_combo }}</div>
            <div class="col-md-12">
                <form name="ExTruckEntryExitForm" id="ExTruckEntryExitForm" novalidate>
                    <table>
                        <tr>

                            <th  style="padding-left: 15px;">Bus Type<span class="mandatory">*</span>:</th>
                            <td>
                                <select class="form-control" style="width: 200px" name="bus_type_name" id="bus_type_name" ng-model="bus_type_name" ng-options="type.bus_id as type.type_name for type in allBusTypeData" required>
                                    <option value="" selected="selected">Type Name</option>
                                </select>
                                <span class="error" ng-show="ExTruckEntryExitForm.bus_type_name.$invalid && submitted">Bus Type is required</span>
                            </td>


                            <th style="padding-left: 35px;">Bus No<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="text" class="form-control" name="bus_no" id="bus_no" ng-model="bus_no" placeholder="Enter Bus No." required>
                                <span class="error" ng-show="ExTruckEntryExitForm.bus_no.$invalid && submitted">Bus No is required</span>
                            </td>







                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>

                        <tr>
                            <th style="padding-left: 15px;">Entry Date<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="text" class="form-control datePicker" name="entry_datetime_bus" id="entry_datetime_bus" ng-model="entry_datetime_bus" placeholder="Choose Datetime" required>
                                <span class="error" ng-show="ExTruckEntryExitForm.entry_datetime_bus.$invalid && submitted">Entry Datetime is required</span>
                            </td>


                            <th  style="padding-left: 15px;">Entrance Fee<span class="mandatory">*</span>:</th>
                            <td>
                                <select class="form-control" style="width: 200px" name="rate_of_charges" id="rate_of_charges" ng-model="rate_of_charges" ng-options="fees.rate_of_charges as fees.name_of_charge for fees in entrance_fee" ng-change="entranceFeefun()" required>
                                    <option value="" selected="selected">Charge Name</option>
                                </select>
                                <span class="error" ng-show="ExTruckEntryExitForm.rate_of_charges.$invalid && submitted">Entrance Fee is required</span>
                                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<level><b style="color: green">@{{ message }}@{{ charge_fee }}</b></level>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>

                            <th style="padding-left: 15px;">Haltage Day:</th>
                            <td>
                                <label class="radio-inline">
                                    <input type="radio" ng-change="haltage_day_select()"  ng-model="truck_type"  name="truck_type" id="truck_type"   value="1" required>Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" ng-change="haltage_day_select()" ng-model="truck_type" name="truck_type" id="truck_type" ng-init="truck_type = 0" ng-checked="true"  value="0" required >No
                                </label>
                                <span class="error" ng-show="ExTruckEntryExitForm.truck_type.$invalid && submitted">Truck Type is required</span>
                                <span ng-show="show_haltage_day">
									<input  type="text"   class="form-control"  ng-model="haltage_day" name="haltage_day" id="haltage_day" placeholder="Haltage Day" >
									</span>
                            </td>


                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center">
                                <button type="button" class="btn btn-primary center-block" ng-click="Save()" ng-if="!updateBtn">Save</button>
                                <button type="button" ng-click="update()"  class="btn btn-primary center-block" ng-if="updateBtn">Update</button>
                            </td>
                        </tr>
                    </table>
                </form>
                <br>
            </div>
        </div>
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered text-center">
                <caption><h4 class="text-center ok">Bus Details</h4><label class="form-inline">Search : <input class="form-control" ng-model="searchText" placeholder="Search"></label> <label class="form-inline" ><input type="text" style="width: 210px;" class="form-control datePicker" name="from_date_bus" id="from_date_bus" placeholder="Select Date For Bus Details" ng-model="from_date_bus" ng-change="searchDateWiseAllBuses(from_date_bus)" ></label></caption>
                <thead>
                <tr>
                    <th>S/L</th>
                    <th>Bus No</th>
                    <th>Entry Date</th>
                    <th>Holtage Time(Day)</th>
                    <th>Entrance Fee</th>
                    <th>Memo</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="exTruck in allExTrucks | orderBy: 'exTruck.id':true | filter:searchText |   itemsPerPage:itemPerpage">
                    <td>@{{ $index + serial }}</td>
                    <td>@{{ exTruck.type_name }}-@{{ exTruck.truck_bus_no }}</td>
                    <td>@{{ exTruck.entry_datetime }}</td>
                    <td>@{{ exTruck.haltage_day }}</td>
                    <td>@{{ exTruck.entrance_fee }}</td>
                    <td>
                        <a href="/export/bus/report/get-bus-entry-money-receipt-report/@{{exTruck.id}}" class="btn btn-success" target="_blank">Money Receipt</a>
                    </td>
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" ng-click="edit(exTruck)">Update</button>
                        <button type="button" class="btn btn-danger btn-sm" data-target="#deleteManifestConfirm" data-toggle="modal" ng-click="delete(exTruck)">Delete</button>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="10" class="text-center">
                        <dir-pagination-controls max-size="10"
                                                 on-page-change="getPageCount(newPageNumber)"
                                                 direction-links="true"
                                                 boundary-links="true">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        {{------------------Delete Model-------------------}}
        <div class="modal fade" id="deleteManifestConfirm" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 class="modal-title text-center">Are you sure to delete Bus No: <b>@{{ de_truck_no }}?</b></h4>
                        <a href="" class="btn btn-primary center-block pull-right" ng-click="deleteTruck()">Yes</a>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                    </div>
                    <div class="modal-footer">
                        <span ng-show="deleteFailMsg">Something wrong!</span>
                        <div id="deleteSuccess" class="alert alert-success text-center" ng-show="deleteSuccessMsg">
                            Successfully deleted!
                        </div>
                        <button type="button" class="btn btn-warning center-block" data-dismiss="modal">Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{--------------Delete Model End------------------}}

    </div>
    <script type="text/javascript">
        $('#exit_datetime').datetimepicker({
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'
        });

        $( function() {
            $( "#entry_datetime" ).datepicker(
                {

                    dateFormat: 'yy-mm-dd',
                }
            );

        } );
    </script>
@endsection