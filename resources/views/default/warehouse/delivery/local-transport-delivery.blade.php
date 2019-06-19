@extends('layouts.master')
@section('title', 'Local Transport Delivery')

@section('style')
    <style>
        .ui-autocomplete {
            z-index: 215000000 !important;
        }

        .selectedRow {
            background-color: #dbd3ff !important;
        }

        td {
            text-align: left;
        }
    </style>
@endsection

@section('script')


    <script type="text/javascript">
        var role_name = {!! json_encode(Auth::user()->role->name) !!};

        //below two linse code is for multiple modal and fixing scrollbar issue
        $(document).on('hidden.bs.modal', '.modal', function () {
            $('.modal:visible').length && $(document.body).addClass('modal-open');
        });
    </script>

    {!!Html :: script('js/customizedAngular/warehouse/local-transport-delivery.js')!!}

@endsection

@section('content')
    <div class="col-md-12" ng-app="deliveryApp" ng-controller="deliveryCtrl" ng-cloak>

        <input type="hidden" name="" id="manifest_no_fetch" value="{{$manifest_no}}">

        <div class="col-md-12" style="padding:0;">
            <div class="col-md-3">
                <form action="{{ route('warehouse-delivery-date-and-manifest-wise-local-transport-delivery-report') }}" class="form-inline" target="_blank"
                      method="POST">
                    {{ csrf_field() }}
                    <div class="input-group">
                        <input type="text" class="form-control datePicker" style="width: 120px" ng-model="dateWiseReport"
                               name="date" id="date" placeholder="Choose Date">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary">
                                 L/T Delivery
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-offset-1 col-md-3 text-center">
                <form class="form-inline" ng-submit="doSearch(searchText)">
                    <label for="searchText"> </label>
                    <input type="text" ng-model="searchText" name="searchText" class="form-control input-sm"
                           id="searchText" placeholder="Enter Manifest No" ng-keydown="keyBoard($event)"
                           ng-change="get_requisition_status(searchText,req_partial_status)">
                    <p ng-if="searching" style="color:green; font-size:12px;text-align: center">
                        <img src="{{URL::asset('/images/dataLoader.gif')}}" width="150" height="10"/>
                        <br>
                        <span>Searching...</span>
                    </p>

                </form>

            </div>
            <div class="col-md-2" style="">

                <select class="form-control input-sm" required="required"
                        ng-change="get_requisition_status(searchText,req_partial_status)"
                        name="req_partial_status" ng-model="req_partial_status"
                        ng-options="item as item for item in req_partial_number_list">
                    <option value="">Select List No</option>
                </select>
            </div>
            <div class="col-md-offset-1 col-md-1">
                <button type="button" class="btn btn-info btn-xs" data-toggle="modal"
                        data-target="#addTruckType">Add Truck Type
                </button>
            </div>

        </div>


        <div class="clear-fix"></div>

        <br>
        <br>

        {{-----------------###################Delivery local transport Form ###########------------------------------------------}}
        <div class="localTrnsportGlobalNotification col-md-6 col-md-offset-3 alert alert-warning text-center"
             ng-show="localTrnsportGlobalNotification">
            <i class="fa fa-warning"></i> @{{ localTrnsportGlobalNotificationTxt }}
        </div>

        <div class="col-md-12" style="box-shadow:0 0 12px #3b5998; border-radius: 5px">
            <h5 class="text-center"><b>B/E Information</b></h5>

            <div class="col-md-4">
                <h6><b>B/E No: </b>@{{be_no}}</h6>
                <h6><b>B/E Date: </b>@{{be_date |stringToDate:'mediumDate'}}</h6>
                <h6><b>Gate Pass: </b>@{{gate_pass_no}}</h6>
                <h6><b>Delivery Date: </b>@{{approximate_delivery_date  |stringToDate:'mediumDate'}}</h6>
            </div>

            <div class="col-md-4">

                <h6><b> C&F Name: </b>@{{cnf_name}}</h6>
                <h6><b>Manifest Gross Weight: </b>@{{getManiGWeight}}</h6>
                <h6><b>Manifest Net Weight: </b>@{{getManiNWeight}}</h6>
                <h6><b>Total Requested Local Truck:</b> @{{requestedLocalTruck}}</h6>

            </div>

            <div class="col-md-4">

                <h6><b>Carpenter Packages:</b> @{{ carpenter_packages }}</h6>
                <h6><b>Repair Packages:</b> @{{carpenter_repair_packages}}</h6>
                <h6><b>Total Requested Local Van:</b> @{{requestedLocalVan}}</h6>

            </div>
            <input type="hidden" ng-model="ManifestIdModal"/>

        </div>

        <div class="col-md-12" style="padding: 0;">
            <br>
            <br>
            <br>

            <div class="col-md-4 col-md-offset-4 formBgColor" style="">
                <table>
                    <tr>
                        <th>Transport Type:&nbsp;</th>
                        <td>
                            <label class="radio-inline">
                                <input type="radio" ng-model="transport_type"
                                       value="0" ng-checked="true"
                                       ng-init="transport_type=0"
                                       ng-change="LocalTransportFlag(transport_type)">Truck&nbsp;&nbsp;&nbsp;
                            </label>
                            <label class="radio-inline">
                                <input type="radio" ng-model="transport_type"
                                       value="1" ng-change="LocalTransportFlag(transport_type)">VAN
                            </label>
                            <label class="radio-inline">
                                <input type="radio" ng-model="transport_type" ng-init="transport_type=1"
                                       value="2" ng-change="LocalTransportFlag(transport_type)">
                                Self
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </table>

            </div>


            {{------------------------------------------------------- Local Truck Panel Panel ------------------------------------- start --------}}

            <div class="col-md-12" ng-show="LocalTransportTruckForm" style="padding: 0 ">

                <div class="col-md-10 col-md-offset-1" style="padding: 0">


                    <div class="panel panel-default">
                        <div class="panel-heading">

                            <span><b>Weight Loadable: </b>@{{billableWeight}}</span>
                            <span><b>Weight Delivered: </b>@{{totalLoadedWeight}}</span>
                            <span><b>Remaining: </b>@{{billableWeight-totalLoadedWeight}}</span>
                            <br>
                            <span><b>Package Loadable: </b>@{{loadablePackage}}</span>
                            <span><b>Package Delivered: </b>@{{totalLoadedPackage}}</span>
                            <span><b>Remaining: </b>@{{loadablePackage-totalLoadedPackage}}</span>
                        </div>
                        <div class="panel-body" style="padding: 0;">

                            <form class="formBgColor" name="localTransForm" id="localTransForm"
                                  style="padding: 10px; margin: 0 auto;">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12" >
                                        <label ng-if="undelivered_chassis.length>0" style="padding: 0" class="col-sm-4 col-md-2 control-label">
                                            Chassis/Tructor: <span class="mandatory">*</span> :
                                        </label>
                                        <div class="col-sm-8 col-md-10">
                                            <span ng-repeat="item in undelivered_chassis">
                                              <label class="checkbox-inline">
                                                 <input ng-model="onVehicleTransportId[item.id]"
                                                        type="checkbox"
                                                        value="@{{ item.id }}">
                                                <b>  @{{ item.chassis_type }}
                                                    - @{{ item.chassis_no }}</b>
                                               </label>
                                          </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-12 table-responsive">
                                        <table class="table table-bordered text-center" {{--ng-hide="Truck_List"--}}>
                                            <thead>
                                            <tr>
                                                <th colspan="2">Item List</th>
                                                <th colspan="7"></th>
                                            </tr>

                                            <tr>
                                                <th> {{-- <input type="checkbox"
                                                             ng-model="selectAll"
                                                             ng-click="checkAll()">Select All--}}
                                                </th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Shed/Yard</th>
                                                <th>Quantity</th>
                                                <th>Weight</th>
                                                <th>Package</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr {{--dir-paginate="ChTruck in newTruckSearch"--}} ng-repeat="item in item_delivery_list">
                                                <td>

                                                    <input type="hidden" id="idhidden" name="idhidden"
                                                           ng-model="delivery_item[$index].id"
                                                           value="@{{ item.id }}"
                                                           ng-init="delivery_item[$index].id = item.id">
                                                    <input ng-model="delivery_item[$index].checkbox" type="checkbox"
                                                           value="false"
                                                           ng-init="delivery_item[$index].checkbox = true"/>

                                                </td>
                                                <td>
                                                    @{{ item.Description }}
                                                </td>
                                                <td>
                                                    @{{ item.item_type | itemType}}
                                                </td>
                                                <td>
                                                    @{{ item.yard_shed ? 'Shed' :'Yard' }}
                                                </td>
                                                <td>
                                                    @{{ item.item_quantity }}
                                                </td>
                                                <td style="max-width: 50px;">
                                                    <input type="number" id="loadable_weight" class="form-control"
                                                           name="loadable_weight"
                                                           ng-model="delivery_item[$index].loadable_weight"
                                                           value="@{{ item.loadable_weight }}">
                                                </td>
                                                <td style="max-width: 50px;">
                                                    <input type="number" id="loadable_package" class="form-control"
                                                           name="loadable_package"
                                                           ng-model="delivery_item[$index].loadable_package"

                                                           value="@{{ item.loadable_package }}">
                                                </td>

                                            </tr>
                                            </tbody>


                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <label class="col-sm-4 col-md-5 control-label">
                                            Transport No<span class="mandatory">*</span> :
                                        </label>
                                        <div class="col-sm-8 col-md-7">
                                            <div class="input-group" style="width: 100%;">
                                                <select title="" class="form-control input-sm"
                                                        style="width: 50%"
                                                        name="truck_type" ng-model="truck_type"
                                                        ng-options="type.truck_id as type.type_name for type in truck_type_data"
                                                        required>
                                                </select>
                                                <input type="text"
                                                       style="width: 50%"
                                                       ng-model="bd_truck_no"
                                                       name="bd_truck_no" id="bdtruck_no"
                                                       class="form-control input-sm"
                                                       placeholder="Transport No." required>
                                                <span class="error"
                                                      ng-show="localTransForm.bd_truck_no.$invalid && submittedLocalTransportBtn">
                                                  Transport No is required
                                                </span>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">

                                        <label for="driver_name"
                                               class="col-sm-4 col-md-5 control-label">
                                            Driver Name:<span class="error">*</span>
                                        </label>

                                        <div class="col-sm-8 col-md-7">
                                            <input ng-model="driver_name"
                                                   maxlength="100"
                                                   id="chDriverName"
                                                   required="required"
                                                   class="input-sm form-control"
                                                   placeholder="Type Driver Name"
                                                   tabindex=3/>
                                            <br>
                                            <span ng-cloak class="error"
                                                  ng-show="!driver_name && submittedLocalTransportBtn">Please Type Driver's Name!</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-sm-6 col-md-6">
                                        <label for="labor_load"
                                               class="col-sm-4 col-md-5 control-label">
                                            Labour Weight:<span class="error">*</span>
                                        </label>
                                        <div class="col-sm-8 col-md-7">

                                            <input type="number" ng-model="labor_load"
                                                   name="labor_load"
                                                   class="form-control input-sm"
                                                   placeholder="Labor Load"
                                                   ng-required="!( labor_load || equip_load)"
                                                    {{-- ng-change="getEquipmentWeight()"--}}>

                                            <span class="error"
                                                  ng-show="(localTransForm.labor_load.$error.required || localTransForm.equip_load.$error.required) && submittedLocalTransportBtn">
                                                  Labour or Equipment Required
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <label for="driver_name"
                                               class="col-sm-4 col-md-5 control-label">
                                            Labour Packages:<span class="error"></span>
                                        </label>
                                        <div class="col-sm-8 col-md-7">
                                            <input type="number" ng-model="labor_package"
                                                   name="labor_package"
                                                   class="form-control input-sm"
                                                   placeholder="Labor Package"
                                                    {{--ng-change="getLabourWeight()"--}}>
                                            <br>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" ng-hide="cnfModuleFormHide">
                                    <div class="col-sm-6 col-md-6">
                                        <label for="driver_name"
                                               class="col-sm-4 col-md-5 control-label">
                                            Equip. Weight:<span class="error">*</span>
                                        </label>
                                        <div class="col-sm-8 col-md-7">
                                            <input type="number" ng-model="equip_load"
                                                   name="equip_load"
                                                   class="form-control input-sm" placeholder="Equipment Load"
                                                   ng-required="!( equip_load || labor_load)"
                                                    {{--ng-change="getLabourWeight()"--}}>

                                            <span class="error"
                                                  ng-show="(localTransForm.labor_load.$error.required || localTransForm.equip_load.$error.required) && submittedLocalTransportBtn">
                                                  Labour or Equipment Required
                                            </span>
                                            <br>
                                        </div>

                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <label for="driver_name"
                                               class="col-sm-4 col-md-5 control-label">
                                            Equip. Package:<span class="error">*</span>
                                        </label>
                                        <div class="col-sm-8 col-md-7">
                                            <input type="number" ng-model="equipment_package"
                                                   name="equipment_package"
                                                   class="form-control input-sm" placeholder="Equipment Package">
                                        </div>

                                    </div>
                                </div>
                                <div class="row" ng-hide="cnfModuleFormHide">
                                    <div class="col-sm-6 col-md-6">
                                        <label for="driver_name"
                                               class="col-sm-4 col-md-5 control-label">
                                            Equipment Name:<span class="error">*</span>
                                        </label>

                                        <div class="col-sm-8 col-md-7">

                                            <input type="text" ng-model="equip_name"
                                                   name="equip_name"
                                                   class="form-control input-sm equip_name"
                                                   placeholder="Equipment Name"
                                                   ng-required="equip_load" ng-disabled="disableWhenTranshipment">
                                            <span class="error"
                                                  ng-show="localTransForm.equip_name.$invalid && submittedLocalTransportBtn">
                                                    Equipment Name Required!
                                            </span>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <label for="driver_name"
                                               class="col-sm-4 col-md-5 control-label">
                                            Delivery Date:<span class="error">*</span>
                                        </label>

                                        <div class="col-sm-8 col-md-7">
                                            <input type="text" ng-model="delivery_dt" name="delivery_dt"
                                                   id="delivery_dt"
                                                   class="form-control input-sm datePicker"
                                                   placeholder="Select Date" disabled
                                                   required>
                                            <span class="error"
                                                  ng-show="localTransForm.delivery_dt.$invalid && submittedLocalTransportBtn && !BDTruckFull">Delevery Date is Required!</span>

                                        </div>

                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2 text-center">

                                        <span class="error" ng-if="assesmentDoneError">
                                        Assessment already Done. You cannot change truck details.
                                    </span>
                                        <br>
                                        <span class="error" ng-show="netWeightError && !BDTruckFull">
                                                    Can't Input more than Net Weight!
                                        </span>

                                        <button ng-show="!bdTruckIdForUpdate" id="saveBdTruckBtn" type="button"
                                                ng-click="savelocalTransData(localTransForm)"
                                                class="btn btn-primary btn-sm" ng-if="buttonBdTruck">
                                            Save Transport
                                        </button>

                                        <button ng-show="bdTruckIdForUpdate" id="saveBdTruckBtn" type="button"
                                                ng-click="savelocalTransData(localTransForm)"
                                                class="btn btn-primary btn-sm" ng-if="buttonBdTruck">
                                            Update Transport
                                        </button>
                                        <br>

                                        <span ng-show="savingLocalTransportData"
                                              style="color:green; text-align:center; font-size:15px">
                                                    <img src="{{URL::asset('/images/dataLoader.gif')}}" width="250"
                                                         height="15"/>
                                                    <br/> Please wait!
                                                </span>
                                    </div>
                                    <div class="col-md-8 col-md-offset-2 alert alert-success text-center"
                                         ng-show="localTransportSuccess" id="localTransportSuccess">
                                        @{{localTransportSuccessMsgTxt}}
                                    </div>
                                    <div ng-show="localTransportError"
                                         class="col-md-8 col-md-offset-2 alert alert-danger text-center"
                                         id="localTransportError">
                                        @{{localTransportErrorMsgTxt}}
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>

                </div>
                {{--local transport form div end--}}

                <div class="col-md-12 table-responsive">

                    <table class="table table-bordered table-hover table-striped" id="bdTruckListTbl">
                        <thead>
                        <tr>
                            <td colspan="11" class="text-center" ng-if="!bdTrucksdataLoading">
                                                <span style="color:green; text-align:center; font-size:15px">
                                                  Local Transport Data
                                                </span>
                            </td>
                            <td colspan="11" class="text-center" ng-if="bdTrucksdataLoading">
                                                <span style="color:green; text-align:center; font-size:15px">
                                                    <img src="{{URL::asset('/images/dataLoader.gif')}}" width="250"
                                                         height="15"/>
                                                    <br/> Please wait!
                                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>S/l</th>
                            <th>Truck No.</th>
                            <th>Driver Name</th>
                            <th>Labour Weight</th>
                            <th>Labour Package</th>
                            <th>Equip. Weight</th>
                            <th>Equip. Package</th>
                            <th ng-show="allBdTrucksData[0].equip_name!=null">Equipment Name</th>
                            <th>Delivered Date</th>
                            <th>Chassis/Tructor</th>
                            <th>Action</th>

                        </tr>
                        </thead>

                        <tbody>

                        <tr dir-paginate="bdTruck in allBdTrucksData|orderBy:'id':true|itemsPerPage:10">

                            <td>@{{$index +1}}</td> {{-- truck_type_id--}}
                            <td>@{{ bdTruck.type_name }}-@{{bdTruck.truck_no}}
                                (@{{bdTruck.transport_type |transportTypeFilter}})
                            </td>
                            <td>@{{bdTruck.driver_name}}</td>
                            <td>@{{bdTruck.labor_load}}</td>
                            <td>@{{bdTruck.labor_package}}</td>

                            <td>@{{bdTruck.equip_load}}</td>
                            <td>@{{bdTruck.equipment_package}}</td>
                            <td ng-show="allBdTrucksData[0].equip_name!=null">@{{bdTruck.equip_name}}</td>
                            <td>@{{ bdTruck.delivery_req_dt |stringToDate:"MMM d, y"}}</td>
                            <td>@{{ bdTruck.chassis_on_this_vehicle}}</td>


                            {{--2017-04-06 00:00:00--}}
                            {{--"2016-01-05T09:05:05.035Z" | date--}}
                            <td>
                                <button type="button" ng-click="editLocalTransport(bdTruck)"
                                        class="btn btn-primary btn-xs">Edit
                                </button>
                                <button type="button" ng-click="deleteLocalTransportConfirm(bdTruck)"
                                        class="btn btn-primary btn-xs">Delete
                                </button>
                            </td>


                        <tr ng-show="allBdTrucksData.length<=0 && !bdTrucksdataLoading ">
                            <td colspan="11" class="text-center">
                                <span style="color: red">No Data Found!</span>
                                <button type="button"
                                        ng-click="getBdTruckData(GetManiID)"
                                        class="btn btn-primary btn-xs">Reload
                                </button>
                            </td>
                        </tr>

                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="11" class="text-center">

                                <dir-pagination-controls max-size="5"
                                                         direction-links="true"
                                                         boundary-links="true">
                                </dir-pagination-controls>
                            </td>
                        </tr>


                        </tfoot>


                    </table>
                </div>

            </div>
            {{-------------local transport div end------------------------------}}

            {{------------------------------------------------------- Local VAN Panel Panel ------------------------------------- start --------}}
            <div class="col-md-12" ng-show="LocalTransportVanForm" style="padding: 0 ">
                <div class="col-md-10 col-md-offset-1" style="padding: 0">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span><b>Weight Loadable: </b>@{{billableWeight}}</span>
                            <span><b>Weight Delivered: </b>@{{totalLoadedWeight}}</span>
                            <span><b>Remaining: </b>@{{billableWeight-totalLoadedWeight}}</span>
                            <br>
                            <span><b>Package Loadable: </b>@{{loadablePackage}}</span>
                            <span><b>Package Delivered: </b>@{{totalLoadedPackage}}</span>
                            <span><b>Remaining: </b>@{{loadablePackage-totalLoadedPackage}}</span>
                        </div>
                        <div class="panel-body" style="padding: 0;">
                            <form class="formBgColor" name="localTransVanForm" id="localTransVanForm"
                                  style="padding: 10px; margin: 0 auto;">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <table class="table table-bordered text-center" {{--ng-hide="Truck_List"--}}>
                                            <thead>
                                            <tr>
                                                <th>  {{--<input type="checkbox"
                                                             ng-model="selectAll"
                                                             ng-click="checkAll()">Select All--}}
                                                </th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Quantity</th>
                                                <th>Weight</th>
                                                <th>Package</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr {{--dir-paginate="ChTruck in newTruckSearch"--}} ng-repeat="item in item_delivery_list">
                                                <td>

                                                    <input type="hidden" id="idhidden" name="idhidden"
                                                           ng-model="delivery_item[$index].id"
                                                           value="@{{ item.id }}"
                                                           ng-init="delivery_item[$index].id = item.id">
                                                    <input ng-model="delivery_item[$index].checkbox" type="checkbox"
                                                           checked="checked" value="true"
                                                           ng-init="delivery_item[$index].checkbox = true"/>

                                                </td>
                                                <td>
                                                    @{{ item.Description }}
                                                </td>
                                                <td>
                                                    @{{ item.item_type | itemType}}
                                                </td>
                                                <td>
                                                    @{{ item.item_quantity }}
                                                </td>
                                                <td>
                                                    <input type="text" id="loadable_weight" name="loadable_weight"
                                                           ng-model="delivery_item[$index].loadable_weight"
                                                           value="@{{ item.loadable_weight }}">
                                                </td>
                                                <td>
                                                    <input type="text" id="loadable_package" name="loadable_package"
                                                           ng-model="delivery_item[$index].loadable_package"

                                                           value="@{{ item.loadable_package }}">
                                                </td>

                                            </tr>
                                            </tbody>


                                        </table>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <label class="col-sm-4 col-md-5 control-label">
                                            Transport No<span class="mandatory">*</span> :
                                        </label>
                                        <div class="col-sm-8 col-md-7">
                                            <input type="text"
                                                   ng-model="bd_truck_no"
                                                   name="bd_truck_no" id="bdtruck_no"
                                                   class="form-control input-sm"
                                                   placeholder="Transport No." {{-- required --}}>
                                            <span class="error"
                                                  ng-show="localTransVanForm.bd_truck_no.$invalid && submittedLocalTransportBtn">
                                              Transport No is required
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <label for="driver_name"
                                               class="col-sm-4 col-md-5 control-label">
                                            Driver Name:<span class="error">*</span>
                                        </label>
                                        <div class="col-sm-8 col-md-7">
                                            <input ng-model="driver_name"
                                                   maxlength="100"
                                                   id=""
                                                   required="required"
                                                   class="input-sm form-control"
                                                   placeholder="Type Driver Name"/>
                                            <br>
                                            <span ng-cloak class="error"
                                                  ng-show="!driver_name && submittedLocalTransportBtn">Please Type Driver's Name!</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <label for="labor_load"
                                               class="col-sm-4 col-md-5 control-label">
                                            Labour Weight:<span class="error">*</span>
                                        </label>
                                        <div class="col-sm-8 col-md-7">

                                            <input type="number" ng-model="labor_load"
                                                   name="labor_load"
                                                   class="form-control input-sm"
                                                   placeholder="Labor Load"
                                                   ng-required="!( labor_load || equip_load)"
                                                    {{-- ng-change="getEquipmentWeight()"--}}>

                                            <span class="error"
                                                  ng-show="(localTransVanForm.labor_load.$error.required  && submittedLocalTransportBtn)">
                                                  Labour or Equipment Required
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <label for="labor_package"
                                               class="col-sm-4 col-md-5 control-label">
                                            Labour Packages:<span class="error"></span>
                                        </label>
                                        <div class="col-sm-8 col-md-7">
                                            <input type="number" ng-model="labor_package"
                                                   name="labor_package"
                                                   class="form-control input-sm"
                                                   placeholder="Labor Package"
                                                    {{--ng-change="getLabourWeight()"--}}>
                                            <br>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" ng-hide="cnfModuleFormHide">
                                    <div class="col-sm-6 col-md-6">
                                        <label for="driver_name"
                                               class="col-sm-4 col-md-5 control-label">
                                            Equip. Weight:<span class="error">*</span>
                                        </label>
                                        <div class="col-sm-8 col-md-7">
                                            <input type="number" ng-model="equip_load"
                                                   name="equip_load"
                                                   class="form-control input-sm" placeholder="Equipment Load"
                                                   ng-required="!( equip_load || labor_load)"
                                                    {{--ng-change="getLabourWeight()"--}}>
                                            <span class="error"
                                                  ng-show="( localTransVanForm.equip_load.$error.required && submittedLocalTransportBtn)">
                                                  Labour or Equipment Required
                                            </span>
                                            <br>
                                        </div>

                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <label for="driver_name"
                                               class="col-sm-4 col-md-5 control-label">
                                            Equip. Package:<span class="error">*</span>
                                        </label>
                                        <div class="col-sm-8 col-md-7">
                                            <input type="number" ng-model="equipment_package"
                                                   name="equipment_package"
                                                   class="form-control input-sm" placeholder="Equipment Package">
                                        </div>

                                    </div>
                                </div>
                                <div class="row" ng-hide="cnfModuleFormHide">
                                    <div class="col-sm-6 col-md-6">
                                        <label for="driver_name"
                                               class="col-sm-4 col-md-5 control-label">
                                            Equipment Name:<span class="error">*</span>
                                        </label>

                                        <div class="col-sm-8 col-md-7">
                                            <input type="text" ng-model="equip_name"
                                                   name="equip_name"
                                                   class="equip_name form-control input-sm"
                                                   placeholder="Equipment Name"
                                                   ng-required="equip_load">
                                            <span class="error"
                                                  ng-show="localTransVanForm.equip_name.$invalid && submittedLocalTransportBtn">
                                                    Equipment Name Required!
                                                </span>
                                            <br>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <label for="driver_name"
                                               class="col-sm-4 col-md-5 control-label">
                                            Delivery Date:<span class="error">*</span>
                                        </label>

                                        <div class="col-sm-8 col-md-7">
                                            <input type="text" ng-model="delivery_dt" name="delivery_dt"
                                                   id="delivery_dt"
                                                   class="form-control input-sm datePicker"
                                                   placeholder="Select Date" disabled
                                                   required>
                                            <span class="error"
                                                  ng-show="localTransVanForm.delivery_dt.$invalid && submittedLocalTransportBtn && !BDTruckFull">Delevery Date is Required!</span>
                                        </div>
                                    </div>
                                </div>

                                {{--<div class="row" ng-hide="cnfModuleFormHide">--}}
                                {{--<div class="col-sm-6 col-md-6">--}}
                                {{--<label for="haltage_day"--}}
                                {{--class="col-sm-4 col-md-5 control-label">--}}
                                {{--Haltage Day:--}}
                                {{--</label>--}}
                                {{--<div class="col-sm-8 col-md-7">--}}
                                {{--<input type="number" class="form-control input-sm"--}}
                                {{--name="haltage_day"--}}
                                {{--placeholder="Type Haltage Day"--}}
                                {{--ng-model="haltage_day" id="haltage_day">--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                {{--</div>--}}
                                <br>
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2 text-center">
                                        <span class="error" ng-if="assesmentDoneError">
                                        Assessment already Done. You cannot change van details.
                                    </span>
                                        <br>
                                        <span class="error" ng-show="netWeightError && !BDTruckFull">
                                                    Can't Input more than Net Weight!
                                        </span>

                                        <button ng-show="!bdTruckIdForUpdate" id="saveBdTruckBtn" type="button"
                                                ng-click="savelocalTransData(localTransForm)"
                                                class="btn btn-primary btn-sm" ng-if="buttonBdTruck">
                                            Save Transport
                                        </button>

                                        <button ng-show="bdTruckIdForUpdate" id="saveBdTruckBtn" type="button"
                                                ng-click="savelocalTransData(localTransForm)"
                                                class="btn btn-primary btn-sm" ng-if="buttonBdTruck">
                                            Update Transport
                                        </button>
                                        <br>

                                        <span ng-show="savingLocalTransportData"
                                              style="color:green; text-align:center; font-size:15px">
                                                    <img src="{{URL::asset('/images/dataLoader.gif')}}" width="250"
                                                         height="15"/>
                                                    <br/> Please wait!
                                                </span>
                                    </div>
                                    <div ng-show="localTransportSuccess"
                                         class="col-md-8 col-md-offset-2 alert alert-success text-center"
                                         id="localTransportSuccess">
                                        @{{localTransportSuccessMsgTxt}}
                                    </div>
                                    <div ng-show="localTransportError"
                                         class="col-md-8 col-md-offset-2 alert alert-danger text-center"
                                         id="localTransportError">
                                        @{{localTransportErrorMsgTxt}}
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>

                </div>
                {{--local transport form div end--}}

                <div class="col-md-12 table-responsive">

                    <table class="table table-bordered table-hover table-striped" id="">
                        <thead>
                        <tr>
                            <td colspan="11" class="text-center" ng-if="!bdTrucksdataLoading">
                                                <span style="color:green; text-align:center; font-size:15px">
                                                  Local Van Data
                                                </span>
                            </td>
                            <td colspan="11" class="text-center" ng-if="localVandataLoading">
                                                <span style="color:green; text-align:center; font-size:15px">
                                                    <img src="{{URL::asset('/images/dataLoader.gif')}}" width="250"
                                                         height="15"/>
                                                    <br/> Please wait!
                                                </span>
                            </td>
                        </tr>

                        <tr>

                            <th>S/l</th>
                            <th>Truck No.</th>
                            <th>Driver Name</th>
                            <th>Labour Weight</th>
                            <th>Labour Package</th>
                            <th>Equip. Weight</th>
                            <th>Equip. Package</th>
                            <th ng-show="allBdTrucksData[0].equip_name!=null">Equipment Name</th>
                            <th>Delivered Date</th>
                            <th>Chassis/Tructor</th>
                            <th>Action</th>

                        </tr>
                        </thead>

                        <tbody>

                        <tr dir-paginate="van in localVanData|orderBy:'id':true|itemsPerPage:10">

                            <td>@{{$index +1}}</td>
                            <td>@{{van.truck_no}} (@{{van.transport_type |transportTypeFilter}})</td>
                            <td>@{{van.driver_name}}</td>
                            <td>@{{van.labor_load}}</td>
                            <td>@{{van.labor_package}}</td>

                            <td>@{{van.equip_load}}</td>
                            <td>@{{van.equipment_package}}</td>
                            <td ng-show="allBdTrucksData[0].equip_name!=null">@{{van.equip_name}}</td>
                            <td>@{{ van.delivery_req_dt |stringToDate:"MMM d, y"}}</td>
                            <td>@{{ van.chassis_on_this_vehicle}}</td>


                            {{--2017-04-06 00:00:00--}}
                            {{--"2016-01-05T09:05:05.035Z" | date--}}
                            <td>
                                <button type="button" ng-click="editLocalTransport(van)"
                                        class="btn btn-primary btn-xs">Edit
                                </button>
                                <button type="button" ng-click="deleteLocalTransportConfirm(van)"
                                        class="btn btn-primary btn-xs">Delete
                                </button>
                            </td>


                        <tr ng-show="localVanData.length<=0 && !localVandataLoading">
                            <td colspan="11" class="text-center">
                                <span style="color: red">No Data Found!</span>
                                <button type="button"
                                        ng-click="getBdTruckData(GetManiID)"
                                        class="btn btn-primary btn-xs">Reload
                                </button>
                            </td>
                        </tr>

                        </tbody>

                        <tfoot>
                        <tr>
                            <td colspan="11" class="text-center">

                                <dir-pagination-controls max-size="5"
                                                         direction-links="true"
                                                         boundary-links="true">
                                </dir-pagination-controls>
                            </td>
                        </tr>


                        </tfoot>


                    </table>
                </div>

            </div>
            {{-------------local transport VAN div end------------------------------}}

            {{------------- ===========Self Chassis Form Panel ------------------------------}}
            <div class="col-md-12" ng-show="ChassisInformationForm" style="padding: 0 ">

                <div class="col-md-10 col-md-offset-1" style="padding:0; ">

                    <div class="panel panel-info">

                        <div class="panel-heading">Self Transport Information</div>

                        <div class="panel-body">
                            <div class="col-md-sm-12 col-md-10 col-md-offset-1">
                                <form class="formBgColor" name="selfTransportForm" id="selfTransportForm" novalidate
                                      style="padding: 10px; margin: 0 auto;">
                                    <div class="row" ng-show="selfItemList">
                                        <div class="col-sm-12 col-md-12">
                                            <table class="table table-bordered text-center" {{--ng-hide="Truck_List"--}}>
                                                <thead>
                                                <tr>
                                                    <th>  {{--<input type="checkbox"
                                                                 ng-model="selectAll"
                                                                 ng-click="checkAll()">Select All--}}
                                                    </th>
                                                    <th>Name</th>
                                                    <th>Type</th>
                                                    <th>Quantity</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr {{--dir-paginate="ChTruck in newTruckSearch"--}} ng-repeat="item in item_delivery_list">
                                                    <td>
                                                        <input type="hidden" id="idhidden" name="idhidden"
                                                               ng-model="delivery_item[$index].id"
                                                               value="@{{ item.id }}"
                                                               ng-init="delivery_item[$index].id = item.id">
                                                        <input ng-model="delivery_item[$index].checkbox" type="checkbox"
                                                               ng-init="delivery_item[$index].checkbox = true"/>

                                                    </td>
                                                    <td>
                                                        @{{ item.Description }}
                                                    </td>
                                                    <td>
                                                        @{{ item.item_type | itemType}}
                                                    </td>
                                                    <td>
                                                        @{{ item.item_quantity }}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">

                                            <label for="trSelfTransportId" style="padding: 0"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Chassis :<span class="error">*</span>
                                            </label>

                                            <div class="col-sm-8 col-md-8">
                                                <select title=""
                                                        data-ng-options="chassis.id as (chassis.chassis_type +'-'+chassis.chassis_no ) for chassis in undelivered_chassis"
                                                        class="form-control input-sm" name="selfTransportId"
                                                        data-ng-model="selfTransportId"
                                                        ng-init="selfTransportId=''" ng-disabled="chassis_edit">
                                                    <option value="">Please Select Chassis</option>
                                                </select>
                                                <span ng-cloak class="error"
                                                      ng-show="!selfTransportId && selfTransportFormSubmitted">Please Select Chassis!</span>

                                                <br/>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">

                                            <label for="delivery_dt" style="padding: 0"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Delivery Date:<span class="error">*</span>
                                            </label>
                                            <div class="col-sm-8 col-md-8">
                                                <input type="text" ng-model="delivery_dt"
                                                       name="delivery_dt"
                                                       class="form-control input-sm datePicker"
                                                       placeholder="Select Date"
                                                       required disabled>
                                                <span ng-cloak class="error"
                                                      ng-show="!delivery_dt && selfTransportFormSubmitted">Please Type Delivery Date!</span>

                                                <br/>
                                            </div>
                                        </div>

                                    </div>
                                    <br/>
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">

                                            <label for="selfTransportDriverName" style="padding: 0"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Driver Name:<span class="error">*</span>
                                            </label>

                                            <div class="col-sm-8 col-md-8">
                                                <input ng-model="selfTransportDriverName"
                                                       maxlength="100"
                                                       id="selfTransportDriverName"
                                                       required="required"
                                                       class="input-sm form-control"
                                                       placeholder="Type Driver Name"
                                                       tabindex=3 ng-disabled="chassis_edit"/>
                                                <span ng-cloak class="error"
                                                      ng-show="!selfTransportDriverName && selfTransportFormSubmitted">Please Type Driver's Name!</span>

                                                <br/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <label for="selfTransportDriverCard" style="padding: 0"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Driver Card:<span class="error">*</span>
                                            </label>
                                            <div class="col-sm-8 col-md-8">
                                                <input ng-model="selfTransportDriverCard"
                                                       maxlength="100"
                                                       id="selfTransportDriverCard"
                                                       required="required"
                                                       class="input-sm form-control"
                                                       placeholder="Type Driver Card"
                                                       tabindex=4 ng-disabled="chassis_edit"/>
                                                <span ng-cloak class="error"
                                                      ng-show="!selfTransportDriverCard && selfTransportFormSubmitted">Please Type Driver Card!</span>
                                                <br/>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <button ng-show="!bdSelfIdForUpdate" id="saveSelfButton" type="button"
                                                    ng-click="saveSelfTransportData(selfTransportForm)"
                                                    class="btn btn-primary btn-sm" ng-if="buttonSelfBd"><span
                                                        class="fa fa-file"></span> Save Self Transport
                                            </button>


                                            <button ng-show="bdSelfIdForUpdate" id="saveSelfButton" type="button"
                                                    ng-click="saveSelfTransportData(selfTransportForm)"
                                                    class="btn btn-primary btn-sm" ng-if="buttonSelfBd"><span
                                                        class="fa fa-file"></span>
                                                Update Self Transport
                                            </button>


                                            <div id="selfTransportSuccess" ng-show="selfTransportSuccess"
                                                 class="col-md-8 col-md-offset-2 alert alert-success text-center">
                                                @{{saveChSuccessMsgTxt}}
                                            </div>
                                            <div id="selfTransportError" ng-show="selfTransportError"
                                                 class="col-md-8 col-md-offset-2 alert alert-danger text-center">
                                                @{{ selfTransportError }}
                                            </div>

                                            <div id="selfTransportErrorItem" ng-show="selfTransportErrorItem"
                                                 class="col-md-8 col-md-offset-2 alert alert-danger text-center">
                                                @{{ selfTransportErrorItem }}
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>

                        <div class="panel-footer panel-primary">

                        </div><!--.panel-footer-->
                    </div>
                </div>
                {{--chassis form div end --}}
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>

                        <tr>
                            <td colspan="5" class="text-center">
                                                <span style="color:green; text-align:center; font-size:15px"
                                                      ng-show="chassisSelfDataLoading">
                                                    <img src="{{URL::asset('/images/dataLoader.gif')}}" width="120"
                                                         height="10"/>
                                                    <br/> Please wait!
                                                </span>

                                <p ng-show="!chassisSelfDataLoading"><b>Transport Delivered List (Self)</b>
                                </p>
                            </td>
                        </tr>

                        <tr>

                            <th>S/l</th>
                            <th>Transport No.</th>
                            <th>Driver Name</th>
                            <th>Delivered Date</th>
                            <th>Action</th>

                        </tr>
                        </thead>

                        <tbody>

                        <tr dir-paginate="self in selfDliveredChassisList|orderBy:'id':true|itemsPerPage:1000">

                            <td>@{{$index +1}}</td>
                            <td>@{{self.chassis_type}} - @{{self.chassis_no }}</td>
                            <td>@{{self.driver_name}}</td>
                            <td>@{{self.delivery_dt | stringToDate:"MMM d, y"}}</td>

                            <td>
                                <button type="button" ng-click="editSelfTransportDelivery(self)"
                                        class="btn btn-primary btn-xs">Edit
                                </button>
                                <button type="button" ng-click="deleteSelfTransportDelivery(self.id)"
                                        class="btn btn-primary btn-xs">Delete
                                </button>
                            </td>
                        </tr>

                        <tr class="text-center"
                            ng-if="(selfDliveredChassisList.length<=0 || errorWhileSelfDeliveredDataLoading) && !chassisSelfDataLoading">
                            <td colspan="5">
                                <span>Error Occured!</span>
                                <button type="button"
                                        ng-click="getSelfDeliveredChassisListByManifest(GetManiID)"
                                        class="btn btn-success btn-xs">Reload
                                </button>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            {{--chassis div end --}}


        </div>


        <!-- local transport entry and chassis entry Modal END -->


        <div class="modal fade text-center" style="left: 0;" id="addImporter" role="dialog">
            <div class="modal-dialog">
                <div class="modal-auto formBgColor">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center ok">Add AIN</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="savingSuccessBin"
                             ng-hide="!savingSuccessBin">@{{ savingSuccessBin }}</div>
                        <div class="alert alert-danger" id="savingErrorBin"
                             ng-hide="!savingErrorBin">@{{ savingErrorBin }}</div>
                        <form name="importerForm" id="importerForm" novalidate>
                            <table style="/*background-color: red; */">
                                <tr>
                                    <th>AIN No<span class="mandatory">*</span>:</th>
                                    <td>
                                        <input type="text" name="ain_no_f" id="ain_no_f" class="form-control input-sm"
                                               placeholder="AIN No" ng-model="ain_no_f"
                                               required {{--ng-pattern="/^\d{7,11}$/"--}} {{-- unique --}} {{-- ng-disabled="diableBINNUmber" --}}>
                                        <span class="error"
                                              ng-show="importerForm.ain_no_f.$error.required && submittedAin">AIN No is required.</span>
                                        <span class="error"
                                              ng-show="importerForm.ain_no_f.$error.pattern && submittedAin">AIN No must be 7 to 11 character.</span>
                                    </td>
                                    <th style="padding-left: 25px;">C&F Name<span class="mandatory">*</span>:</th>
                                    <td>
                                        <input type="text" name="cnfName_f" id="cnfName_f" class="form-control input-sm"
                                               ng-model="cnfName_f" required placeholder="C&F Name">
                                        <span class="error" ng-show="importerForm.cnfName_f.$invalid && submittedAin">C&F Name Is required.</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6">&nbsp;</td>
                                </tr>

                                <tr>
                                    <td colspan="6" class="text-center">
                                        <button type="button" class="btn btn-primary btn-sm center-block"
                                                ng-click="SaveAin()"><span class="fa fa-file"></span> Save
                                        </button>
                                        <span ng-if="dataLoadingBin">
                                            <img src="{{URL::asset('/images/dataLoader.gif')}}" width="250"
                                                 height="15"/>
                                            <br/>Please wait!
                                    </span>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-warning pull-right" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bd Truck Type add Modal -->
        <div id="addTruckType" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <div class="alert alert-success" id="savingSuccess"
                             ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                        <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <form name="bdTruckTypeForm" novalidate>
                                <table>
                                    <tr>
                                        {{--<th style="padding-left: 15px;">Vehicle&nbsp;:</th>
                                        <td>
                                            <label class="radio-inline">
                                                <input type="radio" ng-init="vehicle_type=1"ng-model="vehicle_type"  name="vehicle_type" id="vehicle_type"
                                                       ng-checked="true"   value="1">Truck
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" ng-model="vehicle_type" name="vehicle_type" id="vehicle_type" value="0">Bus
                                            </label>
                                        </td>--}}

                                        <th style="padding-left: 15px;">Type Name<span class="mandatory">*</span>:</th>
                                        <td>
                                            <input type="text" class="form-control" name="type_name" id="type_name"
                                                   ng-model="type_name" placeholder="Enter Type Name." required>
                                            <span class="error" ng-show="!type_name && bdTruckTypeFormInvalid">Type Name is required</span>
                                        </td>
                                        <td colspan="2" class="text-center">
                                            <button type="button" class="btn btn-primary center-block"
                                                    ng-click="saveBdTruckType(bdTruckTypeForm)">
                                                Save
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                            <br>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection