@extends('layouts.master')
@section('title', 'Posting Monitor')
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
	{!!Html :: script('js/customizedAngular/posting/posting-entry-monitor.js')!!}
@endsection
@section('content')
	<div class="col-md-12 ng-cloak text-center" ng-app="dateWisePostingMonitorApp" ng-controller="dateWisePostingMonitorCtrl">
        <div>
            <div class="form-inline">
               <label>Date:</label>
                <input class="form-control datePicker" type="text" name="date" id="date" ng-model="date">
                <button class="btn btn-success" type="button" ng-click="getPostingDetails(date)">Get</button>
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
                <caption><h4 class="text-center ok">Manifest Details</h4><label class="form-inline">Search:<input class="form-control" ng-model="searchText"></label></caption>
        		<thead>
        			<th>S/L</th>
        			<th>Manifest No</th>
                    <th>Posted Yard Shed</th>
                    <th>Goods Name</th>
                    <th>Posting Entry By</th>
                    <th>Posting Entry Datetime</th>
                    <th>Posting Updated By</th>
        			<th>Posting Update Datetime</th>
        		</thead>
        		<tbody>
        			<tr dir-paginate="posting in allPosting | orderBy: 'posting.manifest_id' | filter:searchText | itemsPerPage:15" pagination-id="posting">
        				<td>@{{ $index + serial }}</td>
        				<td>@{{ posting.manifest }}</td>
                        <td>@{{ posting.posted_yard_shed }}</td>
        				<td>@{{ posting.goods_name }}</td>
                        <td>@{{ posting.manifest_entry_by }}</td>
        				<td>@{{ posting.manifest_created_time }}</td>
        				<td>@{{ posting.manifest_updated_by }}</td>
        				<td>@{{ posting.manifest_update_at }}</td>
        			</tr>
        		</tbody>
        		<tfoot>
                    <tr>
                        <td colspan="9" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                on-page-change="getPageCount(newPageNumber)"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="posting">
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