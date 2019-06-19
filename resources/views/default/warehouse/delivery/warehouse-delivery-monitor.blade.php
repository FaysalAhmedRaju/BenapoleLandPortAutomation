@extends('layouts.master')
@section('title', 'Warehouse Delivery Monitor')
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
	{!!Html :: script('js/customizedAngular/warehouse/warehouse-delivery-monitor.js')!!}
@endsection
@section('content')
	<div class="col-md-12 ng-cloak text-center" ng-app="dateWiseWarehouseDeliveryMonitorApp" ng-controller="dateWiseWarehouseDeliveryMonitorCtrl">
      <h4>Warehouse Devlivery Monitor</h4>
        <div>
            <div class="form-inline">

               <label>Delivery Date:</label>
                <input class="form-control datePicker" type="text" name="date" id="date" ng-model="date">
                <button class="btn btn-success" type="button" ng-click="getTruckDetails(date)">Get</button>
                <a target="_blank" href="/warehouse/delivery/report/date-wise-delivery-report/@{{date}}" class="btn btn-success">PDF</a>



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
                <caption><h4 class="text-center ok">Truck Delivery Details</h4><label class="form-inline">Search:<input class="form-control" ng-model="searchText"></label></caption>
        		<thead>
                <tr>

        			<th>S/L</th>
        			<th>Manifest No</th>
                    <th>Approximate Delivery Date</th>
                    <th>Truck Type-No</th>
                    <th>Self Type-No</th>
                    <th>Shed/Yard</th>
                    <th>Driver Name</th>
                    <th>Created By</th>
                    <th>Created Datetime</th>
                    <th>Updated By</th>
        			<th>Updated Datetime</th>
                    <th>Truck/Self Delivery Date</th>
                    <th>Truck/Self Entry By</th>
                    <th>Truck/Self Entry At</th>
                    <th>Truck/Self Updated By</th>
                    <th>Truck/Self Updated At</th>
                </tr>
        		</thead>
        		<tbody>
        			<tr dir-paginate="Ltruck in allTruckDelivery | filter:searchText | itemsPerPage:15" pagination-id="truck">
        				<td>@{{ $index + serial }}</td>
        				<td>
                            @{{ Ltruck.manifest }}<br/>
                            <span ng-if="Ltruck.self_flag == 1">Self</span>
                            <span ng-if="Ltruck.self_flag == 2">Truck on Self</span>
                        </td>
                        <td>@{{ Ltruck.approximate_delivery_date }}</td>
                        <td>@{{ Ltruck.truck_type_no }}</td>
                        <td>@{{ Ltruck.self_type_no }}</td>
                        <td>@{{ Ltruck.yard_shed_name }}</td>
        				<td>@{{ Ltruck.driver_name }}</td>
                        <td>@{{ Ltruck.created_by }}</td>
        				<td>@{{ Ltruck.created_at }}</td>
        				<td>@{{ Ltruck.updated_by }}</td>
        				<td>@{{ Ltruck.updated_at }}</td>
                        <td>
                            <span ng-if="Ltruck.truck_delivery_date">@{{ Ltruck.truck_delivery_date }}</span>
                            <span ng-if="Ltruck.self_delivery_date"><br/>Self-@{{ Ltruck.self_delivery_date }}</span>
                        </td>
                        <td>
                            <span ng-if="Ltruck.local_transport_entry_by">@{{ Ltruck.local_transport_entry_by }}</span>
                            <span ng-if="Ltruck.local_self_entry_by"><br/>Self-@{{ Ltruck.local_self_entry_by }}</span>
                        </td>
                        <td>
                            <span ng-if="Ltruck.local_transport_entry_at">@{{ Ltruck.local_transport_entry_at }}</span>
                            <span ng-if="Ltruck.local_self_entry_at"><br/>Self-@{{ Ltruck.local_self_entry_at }}</span>
                        </td>
                        <td>
                            <span ng-if="Ltruck.local_transport_updated_by">@{{ Ltruck.local_transport_updated_by }}</span>
                            <span ng-if="Ltruck.local_self_updated_by"><br/>Self-@{{ Ltruck.local_self_updated_by }}</span>
                        </td>
                        <td>
                            <span ng-if="local_transport_updated_at">@{{ Ltruck.local_transport_updated_at }}</span>
                            <span ng-if="local_self_updated_at"><br/>Self-@{{ Ltruck.local_self_updated_at }}</span>
                        </td>
        			</tr>
        		</tbody>
        		<tfoot>
                    <tr>
                        <td colspan="14" class="text-center">
                            <dir-pagination-controls max-size="7"
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
        	<h2 class="text-warning">No Delivery Found at @{{ date }}.</h2>
        </div>
	</div>
@endsection