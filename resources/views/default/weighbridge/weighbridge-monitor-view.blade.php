@extends('layouts.master')
@section('title', 'Weighbridge Monitor')
@section('style')
<style>

    table.tbl-th-td-center th, table.tbl-th-td-center td {
        text-align: center!important;
    }
</style>
@endsection
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
    {!!Html :: script('js/customizedAngular/weighbridge/weighbridge-monitor.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak text-center" ng-app="dateWiseWeighbridgeEntryApp"
         ng-controller="dateWiseWeighbridgeEntryCtrl">
        <div>
            <div class="form-inline">
                <label>Date:</label>
                <input class="form-control datePicker" type="text" name="date" id="date" ng-model="date">
                <button class="btn btn-success" type="button" ng-click="getWeighbridgeEntryDetails(date)">Get</button>
            </div>
        </div>
        <div class="col-md-12">
            <span ng-if="dataLoading" style="color:green; text-align:center; font-size:20px">
                <img src="img/dataLoader.gif" width="250" height="15"/>
                <br/> Please wait!
            </span>
        </div>
        <div class="col-md-12 table-responsive" ng-show="dataDiv">
            <table class="table table-bordered tbl-th-td-center">
                <caption><h4 class="text-center ok">Truck Details</h4><label class="form-inline">Search:
                        <input class="form-control" ng-model="searchText" placeholder="Search By Anything"></label></caption>
                <thead>
                <tr>
                    <th colspan="3"></th>
                    <th colspan="3">Weight</th>
                    <th colspan="2" >Scale No.</th>
                    <th colspan="4" >Entry</th>
                    <th colspan="4">Exit</th>
                </tr>

                <tr>
                    <th>S/L</th>
                    <th>Manifest No</th>
                    <th><nobr>Truck Type-No</nobr></th>
                    <th><nobr>Gross</nobr></th>
                    <th><nobr>Tare</nobr></th>
                    <th><nobr>Net</nobr></th>
                    <th><nobr>Entry</nobr></th>
                    <th><nobr>Exit</nobr></th>
                    <th><nobr>Created By</nobr></th>
                    <th><nobr>Created At</nobr></th>
                    <th><nobr>Updated By</nobr> </th>
                    <th><nobr>Updated At</nobr> </th>
                    <th><nobr>Created By</nobr></th>
                    <th><nobr>Created At</nobr></th>
                    <th><nobr>Updated By</nobr></th>
                    <th><nobr>Updated At</nobr></th>
                </tr>
                </thead>

                <tbody>
                <tr dir-paginate="truck in allTruck | orderBy: 'truck.truck_id' | filter:searchText | itemsPerPage:15"
                    pagination-id="truck">
                    <td>@{{ $index + serial }}</td>
                    <td>@{{ truck.manifest }}</td>
                    <td>@{{ truck.truck_type }}-@{{ truck.truck_no }}</td>
                    <td>@{{ truck.gweight_wbridge|number:2 }}</td>
                    <td>@{{ truck.tr_weight |number:2 }}</td>
                    <td>@{{ truck.tweight_wbridge|number:2  }}</td>
                    <td>@{{ truck.entry_scale }}</td>
                    <td>@{{ truck.exit_scale }}</td>
                    <td>@{{ truck.entry_created_by }}</td>
                    <td><nobr>@{{ truck.entry_created_at }}</nobr></td>
                    <td>@{{ truck.entry_updated_by }}</td>
                    <td><nobr>@{{ truck.entry_updated_at }}</nobr></td>
                    <td>@{{ truck.exit_created_by }}</td>
                    <td><nobr>@{{ truck.exit_created_at }}</nobr></td>
                    <td>@{{ truck.exit_updated_by }}</td>
                    <td><nobr>@{{ truck.exit_updated_at }}</nobr></td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="16" class="text-center">
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