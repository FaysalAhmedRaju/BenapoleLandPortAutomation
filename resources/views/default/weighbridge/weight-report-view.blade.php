@extends('layouts.master')
@section('title','Weight Report')
@section('style')
	<style type="text/css">
		[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
	</style>
@endsection
@section('script')
	{!! Html::script('js/customizedAngular/weighbridge/WeightBridgeReport.js') !!}
@endsection
@section('content')
	<div class="col-md-12 ng-cloak text-center" ng-app="WeightReportApp" ng-controller="WeightReportController">
		<div class="col-md-7 col-md-offset-2" style="border: 1px solid green; padding-top: 10px; padding-bottom: 10px;">
			<form name="WeightReportForm" class="form-inline" novalidate ng-submit="Search(manifest)">
				<input type="text" class="form-control" name="manifest" ng-model="manifest" placeholder="Enter Manifest No." ng-pattern="/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A|a)]{1}|[(A-Z-A-Z)]{3})[\/]{1}[0-9]{4}$/" required ng-change="Clear()" ng-model-options="{allowInvalid: true}" ng-keydown="keyBoard($event)">
				<br>
				<span class="error" ng-show="WeightReportForm.manifest.$error.required && submitted">Manifest No. is required.</span>
				<span class="error" ng-show="WeightReportForm.manifest.$error.pattern && submitted">Manifest No Like 947/2/2017</span>
			</form>
		</div>
		<div class="col-md-12 table-responsive">
			<div class="alert alert-danger" id="notFoundError" ng-hide="!notFoundError">@{{ notFoundError }}</div>
			<table class="table table-bordered" ng-show="dataTable">
				<caption><h4 class="text-center">Manifest Details: @{{ manifest }}</h4></caption>
					<thead>
						<tr>
							<th>S/L</th>
							<th>Truck No</th>
							<th>Driver Name</th>
							<th>Goods</th>
							<th>Gross Weight</th>
							<th>Tare Weight</th>
							<th>Net Weight</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr dir-paginate="truck in allTruck | orderBy:'truck.id':false | itemsPerPage:5" pagination-id="truck">
							<td>@{{ $index+1 }}</td>
							<td>@{{ truck.truck_type+"-"+truck.truck_no }}</td>
							<td>@{{ truck.driver_name }}</td>
							<td>@{{ truck.goods}}</td>
							<td>@{{ truck.gweight_wbridge }}</td>
							<td>@{{ truck.tr_weight }}</td>
							<td>@{{ truck.tweight_wbridge }}</td>
							<td>
								<a href="/weighbridge/get-weight-report/@{{ truck.id }}" class="btn btn-success" target="_blank">Report</a>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="8" class="text-center">
								<dir-pagination-controls max-size="5"
														direction-links="true"
														boundary-links="true"
														pagination-id="truck">
								</dir-pagination-controls>
							</td>
						</tr>
					</tfoot>
			</table>		
		</div>
	</div>
@endsection