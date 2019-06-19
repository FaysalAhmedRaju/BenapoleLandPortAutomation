@extends('layouts.master')
@section('title', 'Warehouse Receive Monitor')
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
	{!!Html :: script('js/customizedAngular/warehouse/warehouse-receive-monitor.js')!!}
@endsection
@section('content')
	<div class="col-md-12 ng-cloak text-center" ng-app="dateWiseWarehouseEntryMonitorApp" ng-controller="dateWiseWarehouseEntryMonitorCtrl">
        <div>
            <div class="form-inline">
               <label>Date:</label>
                <input class="form-control datePicker" type="text" name="date" id="date" ng-model="date">
                <button class="btn btn-success" type="button" ng-click="getTruckDetails(date)">Get</button>
            </div>
        </div>
		<div class="col-md-12">
            <span ng-if="dataLoading" style="color:green; text-align:center; font-size:20px">
                <img src="img/dataLoader.gif" width="250" height="15" />
                <br /> Please wait!
            </span>   
        </div>
        <div class="col-md-12 table-responsive" ng-show="dataDiv">
        	<table class="table table-bordered">
                <caption><h4 class="text-center ok">Truck Receive Details</h4><label class="form-inline">Search:<input class="form-control" ng-model="searchText"></label></caption>
        		<thead>
        			<th>S/L</th>
        			<th>Manifest No</th>
                    <th>Shed/Yard</th>
                    <th>Truck Type-No</th>
                    <th>Vehicle Type</th>
                    <th>Receive Weight</th>
                    <th>Receive By</th>
                    <th>Receive Datetime</th>
                    <th>Receive Updated By</th>
        			<th>Receive Updated Datetime</th>
        		</thead>
        		<tbody>
        			<tr dir-paginate="truck in allTruckReceive | orderBy: 'truck.truck_id' | filter:searchText | itemsPerPage:15" pagination-id="truck">
        				<td>@{{ $index + serial }}</td>
        				<td>@{{ truck.manifest }}</td>
                        <td>@{{ truck.yard_shed_name }}</td>
                        <td>@{{ truck.truck_type }}-@{{ truck.truck_no }}</td>
                        <td>@{{ truck.vehicle_type_flag | vehicleTypeFilter }}</td>
        				<td>@{{ truck.receive_weight | number : 2 }}</td>
                        <td>@{{ truck.receive_created_by }}</td>
        				<td>@{{ truck.receive_created_at }}</td>
        				<td>@{{ truck.receive_updated_by }}</td>
        				<td>@{{ truck.receive_updated_at }}</td>
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
        	<h2 class="text-warning">No Truck Receive at @{{ date }}.</h2>
        </div>
	</div>
@endsection