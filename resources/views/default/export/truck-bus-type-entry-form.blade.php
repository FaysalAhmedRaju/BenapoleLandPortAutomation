@extends('layouts.master')
@section('title', 'Vehicle Type Entry')
@section('style')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}
@endsection
@section('script')
    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}
    {!! Html::script('js/customizedAngular/export/truck-bus-type-entry.js') !!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="truckBusTypeApp" ng-controller="truckBusTypeCtrl">
        <div class="col-md-6 col-md-offset-2" style="background-color: #f8f9f9; border-radius:20px;">
            <h4 class="text-center ok">Vehicle Type Entry Form</h4>
            <br>
            <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
            <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
            <div class="alert alert-success" id="savingSuccessCombo" ng-hide="!savingSuccessCombo" ng-show="savigSuccessCombo">@{{ savingSuccess_combo }}</div>
            <div class="alert alert-danger" id="savingErrorCombo" ng-hide="!savingErrorCombo" ng-show="ErrorCombo">@{{ savingError_combo }}</div>
            <div class="col-md-12">
                <form name="ExTruckEntryExitForm" id="ExTruckEntryExitForm" novalidate>
                    <table>
                        <tr>
                            <th style="padding-left: 15px;">Vehicle&nbsp;:</th>
                            <td>
                                <label class="radio-inline">
                                    <input type="radio" ng-init="vehicle_type=1"ng-model="vehicle_type"  name="vehicle_type" id="vehicle_type"
                                           ng-checked="true"   value="1">Truck
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" ng-model="vehicle_type" name="vehicle_type" id="vehicle_type" value="0">Bus
                                </label>
                            </td>

                            <th style="padding-left: 15px;">Type Name<span class="mandatory">*</span>:</th>
                            <td>
                                <input type="text" class="form-control" name="type_name" id="type_name" ng-model="type_name" placeholder="Enter Type Name." required>
                                <span class="error" ng-show="ExTruckEntryExitForm.type_name.$invalid && submitted">Type Name is required</span>
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
                <caption><h4 class="text-center ok">Vehicle Type Details</h4><label class="form-inline">Search:<input class="form-control" ng-model="searchText" placeholder="Search"></label></caption>
                <thead>
                <tr>
                    <th>S/L</th>
                    <th>Vehicle Type</th>
                    <th>Type Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr dir-paginate="exTruck in allExTrucks | orderBy: 'exTruck.id':true | filter:searchText |  itemsPerPage:itemPerpage ">
                    <td>@{{ $index + serial }}</td>
                    <td>@{{ exTruck.vehicle_type|vehicleFilter }}</td>
                    <td>@{{ exTruck.type_name }}</td>
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
                                        {{--------------------------Delete Model----------------------------}}
        <div class="modal fade" id="deleteManifestConfirm" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <h4 class="modal-title text-center">Are you sure to delete Vehicle Type Name: <b>@{{ type_name_i }}?</b></h4>
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
                                            {{-- ------------------------Delete Model End----------------------------}}
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