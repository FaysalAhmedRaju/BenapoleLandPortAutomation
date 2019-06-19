@extends('layouts.master')
@section('title','Handling Other Charges')
@section('style')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}
@endsection
@section('script')
    <script type="text/javascript">
        var portList = {!! json_encode( $portList) !!};

    </script>
    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}
    {!!Html::script('js/customizedAngular/handlingOtherCharges/handlingOtherCharges.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="handlingOtherChargesApp" ng-controller="handlingOtherChargesController">
        <div class="col-md-12">

            <div class="col-md-9 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">Handiling Other Charges</h4>
                <br>
                <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
                <div class="alert alert-success" id="savingSuccessCombo" ng-hide="!savingSuccessCombo" ng-show="savigSuccessCombo">@{{ savingSuccess_combo }}</div>
                <div class="alert alert-danger" id="savingErrorCombo" ng-hide="!savingErrorCombo" ng-show="ErrorCombo">@{{ savingError_combo }}</div>
                <div class="col-md-12">
                    <form name="handilingOtherChargeForm" id="handilingOtherChargeForm" novalidate>
                        <table>
                            <tr>
                                <th  style="padding-left: 15px;">Charge Type<span class="mandatory">*</span>:</th>
                                <td>
                                    <select class="form-control" {{--ng-disabled="UpdateHide"--}} style="width: 200px" name="Charge_type"  id="Charge_type" ng-model="Charge_type"
                                            ng-options="type.charge_id as type.type_of_charge+'-'+type.name_of_charge  for type in chargeTypeArray" required>
                                        <option value="" selected="selected">Type Name</option>
                                    </select>
                                    <span class="error" ng-show="handilingOtherChargeForm.Charge_type.$invalid && submitted">Type is required</span>
                                </td>


                                <th style="padding-left: 35px;">Charge Rate<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="text" class="form-control" name="charge_rate" id="charge_rate" ng-model="charge_rate" placeholder="Enter Charge Rate" required>
                                    <span class="error" ng-show="handilingOtherChargeForm.charge_rate.$invalid && submitted">Rate is required</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>
                            <tr>
                                <th  style="padding-left: 15px;">Year<span class="mandatory">*</span>:</th>
                                <td>
                                    {{--<select class="form-control" style="width: 200px" name="Charge_type"  id="Charge_type" ng-model="Charge_type"--}}
                                            {{--ng-options="type.charge_id as type.type_of_charge+'-'+type.name_of_charge  for type in chargeTypeArray" required>--}}
                                        {{--<option value="" selected="selected">Type Name</option>--}}
                                    {{--</select>--}}

                                    <select class="form-control" required id="charge_year" {{--ng-disabled="UpdateHide"--}}
                                    name="charge_year" ng-model="charge_year" ng-options="year.value as year.text for year in years">
                                        <option value="">Select Year</option>
                                    </select>
                                    <span class="error" ng-show="handilingOtherChargeForm.charge_year.$invalid && submitted">Year is required</span>
                                </td>
                                @if(Auth::user()->role->id == 11 || Auth::user()->role->id == 2)
                                    <th style="padding-left: 15px;">Port<span class="mandatory">*</span>:</th>
                                    <td>
                                        <select title="No Port Selected" style="width: 190px;"  name="port_id" class="form-control" ng-model="port_id">
                                            <option value="">Select Port</option>
                                            @foreach($portList as $i=>$d)
                                                <option value="{{$i}}">{{$d}}</option>
                                            @endforeach
                                        </select>
                                        <span class="error" ng-show="submitted && !port_id">Port is required</span>
                                    </td>
                                @endif

                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>

                            <tr>
                                <td colspan="6" class="text-center">
                                    <button type="button" class="btn btn-primary center-block " ng-click="Save()" ng-if="!updateBtn">Save</button>
                                    <button type="button" ng-click="update()"  class="btn btn-primary center-block " ng-if="updateBtn">Update</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                    <br>
                </div>
            </div>



            <div class="col-md-12 table-responsive">
                <table class="table table-bordered text-center">
                    <caption><h4 class="text-center ok">Details</h4>
                        <div class="col-md-6 col-sm-6 col-xs-3 form-inline">

                        {{--<label class="form-inline">--}}
                            {{--Search : <input class="form-control" ng-model="searchText" placeholder="Search"></label> <label class="form-inline" >--}}
                            {{--<input type="text" style="width: 210px;" class="form-control datePicker" name="from_date_Truck" id="from_date_Truck"--}}
                                   {{--placeholder="Select Date" ng-model="from_date_Truck" ng-change="searchDateWiseAllTrucks(from_date_Truck)" ></label>--}}

                        <div class="form-group">
                            <label for="user_type_search">
                                Year:
                            </label>
                            <select class="form-control" required id="tariff_year_search" name="tariff_year_search" ng-model="tariff_year_search"  ng-options="years.text as years.text  for years in years"
                                    ng-change="searchDateWiseAllTrucks(searchValue)">
                                <option value="">Select Year</option>
                            </select>
                        </div>
                        <div class="form-group">
                            @if(Auth::user()->role->id == 11 || Auth::user()->role->id == 2)
                                <label>
                                    Port:
                                </label>

                                <select title="No Port Selected" style="width: 190px;" {{--ng-init="port_id = '1'"--}}  name="port_id_search" class="form-control"
                                        ng-model="port_id_search" ng-change="searchDateWiseAllTrucks(searchValue)">
                                    <option value="">Select Port</option>
                                    @foreach($portList as $i=>$d)
                                        <option value="{{$i}}">{{$d}}</option>
                                    @endforeach
                                </select>

                            @endif
                        </div>

                    </caption>

                    <thead>
                    <tr>
                        <th>S/L</th>
                        <th style="width:100px;">Charge Type</th>
                        <th style="width: 200px;">Charge Name</th>
                        <th>Charge Rate</th>
                        <th>Year</th>
                        <th>Port</th>
                        <th>Created BY</th>
                        <th>Created Time</th>

                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="cData in allChargesData | orderBy: 'cData.id':true | filter:searchText | itemsPerPage:itemPerpage ">
                        <td>@{{ $index + serial }}</td>
                        <td>@{{ cData.type_of_charge }}</td>
                        <td>@{{ cData.name_of_charge }}</td>
                        <td>@{{ cData.rate_of_charges }}</td>
                        <td>@{{ cData.charges_year }}</td>
                        <td>@{{ cData.port_name }}</td>
                        <td>@{{ cData.created_by }}</td>
                        <td>@{{ cData.created_at }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" ng-click="edit(cData)">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm" data-target="#deleteManifestConfirm" data-toggle="modal" ng-click="delete(cData)">Delete</button>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="11" class="text-center">
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


            {{-- ------------------------Delete Model----------------------------}}
            <div class="modal fade" id="deleteManifestConfirm" role="dialog">
                <div class="modal-dialog">

                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal">&times;</button>

                            {{--<h5 class="text-center">Manifest No:<b>@{{ d.ManifestNo }} </b></h5>--}}
                        </div>
                        <div class="modal-body">
                            <h4 class="modal-title text-center">Charge Rate: <b>@{{ amount_of_charge }}</b>  &nbsp;&nbsp;&nbsp;&nbsp; Charge Year:  <b>@{{ charge_type_year }}</b> </h4> <br>
                            <h4 class="modal-title text-center">Are you sure to delete Charge Type : <b>@{{ charge_type }}?</b></h4>

                            <a href="" class="btn btn-primary center-block pull-right" ng-click="deleteTruck()">Yes</a>

                            <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>

                        </div>
                        <div class="modal-footer">
                            <span ng-show="deleteFailMsg">Something wrong!</span>
                            <div id="deleteSuccess" class="alert alert-warning text-center" ng-show="deleteSuccessMsg">
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

@endsection