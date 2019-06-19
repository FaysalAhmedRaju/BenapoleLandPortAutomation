@extends('layouts.master')
@section('title', 'Truck Monitor')
@section('script')
    <script>
        $(function () {
            $("#dateP").datepicker(
                    {   
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                    }
                );
            });
    </script>
	{!!Html :: script('js/customizedAngular/truck/cargo-monitor.js')!!}
@endsection
@section('content')
	<div class="col-md-12 ng-cloak text-center" ng-app="dateWiseTruckMonitorApp" ng-controller="dateWiseTruckMonitorCtrl">
        <div>
            <div class="form-inline">
                <form name="vehicleform" id="vehicleform" novalidate >
                <select  ng-init="vehile_type_flage_pdf = '1'" {{--style="width: 150px"--}}  name="vehile_type_flage_pdf"  ng-model="vehile_type_flage_pdf"  class="form-control input-sm" >
                    <optgroup label="(1). Truck">
                        <option    value="1" selected >Truck</option>
                        <option   value="2">Chassis(Chassis on Truck)</option>
                        <option   value="3">Trucktor(Trucktor on Truck)</option>
                    </optgroup>
                    <optgroup label="(2). Self">
                        <option  value="11">Chassis(Self)</option>
                        <option  value="12">Trucktor(Self)</option>
                        <option   value="13">Bus</option>
                        <option   value="14">Three Wheller</option>
                        <option   value="15">Rickshaw</option>
                        <option value="16">Car(self)</option>
                        <option value="17">Pick Up(self)</option>
                    </optgroup>
                </select>
               {{--<label>Date:</label>--}}
                <input class="form-control datePicker" type="text" name="date" id="date" ng-model="date">
                <button class="btn btn-success" type="button" ng-click="getTruckDetails(vehicleform)">Get</button>
                </form>
            </div>
        </div>
		<div class="col-md-12">
            <span ng-if="dataLoading" style="color:green; text-align:center; font-size:20px">
                <img src="img/dataLoader.gif" width="250" height="15" />
                <br /> Please wait!
            </span>   
        </div>
        <div  class="col-md-12" ng-show="dataDiv">
            <br>
           <p>
              <b> Total Truck : @{{total_goods}}  &nbsp; &nbsp;   Total Trucktor : @{{total_trucktor}}
               &nbsp;&nbsp;  Total Chassis : @{{total_chassis_self}}  &nbsp;&nbsp;  Total Car : @{{total_car_self}}
                  &nbsp;&nbsp;  Total Pick Up : @{{total_pick_up_self}}</b>
           </p>
        </div>
        <div class="col-md-12 table-responsive" ng-show="dataDiv">
        	<table class="table table-bordered">
                <caption><h4 class="text-center ok">Truck Details</h4><label class="form-inline">Search:<input class="form-control" placeholder="Search" ng-model="searchText"></label></caption>
        		<thead>
        			<th>S/L</th>
        			<th>Manifest No</th>
                    <th>Truck Type-No</th>
                    <th>Vehicle Type</th>
                    <th>Driver Card No</th>
                    <th>Entry By</th>
                    <th>Entry Datetime</th>
                    <th>Update By</th>
        			<th>Update Datetime</th>
                    <th>Exit By</th>
                    <th>Exit At</th>
        		</thead>
        		<tbody>
        			<tr dir-paginate="truck in allTruck | orderBy: 'truck.truck_id' | filter:searchText | itemsPerPage:15" pagination-id="truck">
        				<td>@{{ $index+serial }}</td>
        				<td>@{{ truck.manifest }}</td>
                        <td>@{{ truck.truck_type }}-@{{ truck.truck_no }}</td>
                        <td>@{{ truck.vehicle_type_flag | vehicleTypeFilter }}</td>
        				<td>@{{ truck.driver_card }}</td>
                        <td>@{{ truck.created_by }}</td>
        				<td>@{{ truck.truckentry_datetime }}</td>
        				<td>@{{ truck.updated_by }}</td>
        				<td>@{{ truck.updated_at }}</td>
                        <td>@{{ truck.out_by }}</td>
                        <td>@{{ truck.out_date }}</td>
        			</tr>
        		</tbody>
        		<tfoot>
                    <tr>
                        <td colspan="11" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                on-page-change="getPageCount(newPageNumber)"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="truck">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                </tfoot>
        	</table>
        </div>
        <div class="col-md-12" ng-show="noDataDiv">
        	<h2 class="text-warning">No Truck Entry at @{{ date }}.</h2>
        </div>
	</div>
@endsection