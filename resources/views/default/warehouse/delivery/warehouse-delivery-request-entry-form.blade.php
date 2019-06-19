@extends('layouts.master')
@section('title', 'Delivery')

@section('style')
    <style>
        .ui-autocomplete {
            z-index: 215000000 !important;
        }

        .selectedRow {
            background-color: #dbd3ff !important;
        }
        td{
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

    {!!Html :: script('js/customizedAngular/warehouse/delivery-request.js')!!}

@endsection

@section('content')
    <div ng-app="deliveryRequestApp" ng-controller="deliveryRequestCtrl" ng-cloak>

        <div class="col-md-11 col-md-offset-1">
            <div class="col-md-6" style="padding: 0;">
                <div class="col-md-5">
                    <input type="hidden" name="" id="manifest_no_fetch" value="{{$manifest_no}}">
                    <form class="form-inline" ng-submit="doSearch({{-- searchBy --}})">
                        {{-- <div class="form-group">
                            <label for="email">Search By:</label>
                            <select name="" id="" class="form-control input-sm" ng-change="setFocusOnInput(searchBy)" ng-model="searchBy">
                                <option value="">Select please</option>
                                <option value="ManifestNo" selected="selected">Manifest No</option>
                                <option value="TruckNo">Truck No</option>
                                <option value="YardNo">Yard No</option>
                            </select>
                        </div> --}}
                        <label for="searchText"> </label>
                        <input type="text" ng-model="searchText" name="searchText" class="form-control input-sm"
                               id="searchText" placeholder="Enter Manifest No" ng-keydown="keyBoard($event)">
                    </form>
                    <br>
                </div>
                <div class="col-md-7">
                    <form action="{{ route('warehouse-delivery-date-wise-report') }}" class="form-inline" target="_blank"
                          method="POST">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <input type="text" class="form-control datePicker" ng-model="dateWiseReport"
                                   name="date" id="date" placeholder="Select Delivery Date">
                            <div class="input-group-btn">
                                {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                                <button ng-disabled="!dateWiseReport" type="submit" class="btn btn-primary">
                                    {{-- <span class="fa fa-calendar-o"></span>--}} Get Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- <a href="{{ url('todaysDeliveryRequestPDF') }}" target="_blank">
                <button type="button" class="btn btn-primary"><span class="fa fa-search"></span>Today's Delivery
                </button>
            </a> --}}
            {{--<div class="col-md-3">--}}
                {{--<a href="{{ route('warehouse-delivery-todays-truck-entry-report') }}" target="_blank">--}}
                    {{--<button type="button" class="btn btn-primary"><span class="fa fa-search"></span>--}}
                        {{--Today's Delivery Request--}}
                    {{--</button>--}}
                {{--</a>--}}
            {{--</div>--}}
            {{--<div class="col-md-2">--}}
                {{--<a href="/warehouse/delivery/manifest-information-details-report/@{{ searchKeyManifestNo }}" target="_blank">--}}
                    {{--<button type="button" class="btn btn-primary" ng-disabled="reportByManifestBtn">--}}
                        {{--<span class="fa fa-search"></span>Manifest Info--}}
                    {{--</button>--}}
                {{--</a>--}}
            {{--</div>--}}
            <div class="col-md-offset-4 col-md-1">
                <button type="button" class="btn  btn-success pull-right" data-target="#addImporter"
                        data-toggle="modal">Add AIN
                </button>
            </div>

        </div>
        <div class="clear-fix"></div>
        {{-----------------------------------------------------------------###################DElivery Request Form ###########------------------------------------------}}
        <div class="col-md-10 col-md-offset-1 table-responsive" style="background-color: #f8f9f9; border-radius: 5px; padding: 5px 10px; text-align: center">

            <form name="dRForm" id="dRForm" novalidate>
                <table style="width: 100%;">

                    <tr>
                        <td class="text-center" colspan="6">
                            <h4 class="ok">Warehouse Delivery Request</h4>
                            <br>
                        </td>
                    </tr>

                    <tr  ng-show="showManifestInfoDiv">
                        <td colspan="6">
                            <div class="col-md-10 col-md-offset-1" style="box-shadow: 0 0 5px 1px darkgrey;">
                                {{--<p class="text-center"><b>Manifest Info</b></p>--}}

                                <div class="col-md-6">
                                    <span><b>Manifest No.:</b><span> @{{ GetManiNo }}</span></span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>Importer Name: </b> @{{ ImporterName }}</span>
                                </div>
                                <br>
                                <br>
                                <div class="col-md-6">
                                    <span><b>Manifest G. Weight: </b> @{{ GetManiGWeight }}</span>
                                </div>
                                <div class="col-md-6">
                                    <span><b>WeighBridge Weight: </b> @{{ weigh_bridge_net_weight }}</span>
                                </div>
                                <br>
                                <br>
                                <div class="col-md-6">
                                    <span><b>Receive Weight: </b> @{{ receive_weight }}</span>
                                </div>

                                <div class="col-md-6">
                                    <span><b>Poseted Yard/Shed: </b> @{{ posted_yard_shed }}</span>
                                </div>

                            </div>

                        </td>
                    </tr>


                    <tr>
                        <td colspan="6">
                            &nbsp;
                        </td>
                    </tr>

                    <tr>
                        <th>B/E No<span class="mandatory">*</span> :</th>
                        <td>
                            <input type="text" ng-model="be_no" name="be_no" id="be_no" class="form-control input-sm"
                                   placeholder="B/E No." required>
                            <span class="error" ng-show="dRForm.be_no.$invalid && submitted">
                              B/E No is required
                        </span>
                        </td>
                        <th>&nbsp; B/E Date<span class="mandatory">*</span> :</th>
                        <td>
                            <input type="text" ng-model="be_date" required="required" name="be_date" id="be_date"
                                   class="form-control datePicker input-sm" placeholder="B/E date" required>
                            <span ng-show="dRForm.be_date.$invalid && submitted" class="error">Select a date</span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4">&nbsp;
                        </td>
                    </tr>


                    <tr>

                        <th>Custom Release Order No<span class="mandatory">*</span>:</th>
                        <td>
                            <input type="text" ng-model="custom_release_order_no" name="custom_release_order_no"
                                   id="custom_release_order_no" class="form-control input-sm"
                                   placeholder="Custom Release Order No" required>
                            <span class="error" ng-show="dRForm.custom_release_order_no.$invalid && submitted">
                              Custom Release Order No is required
                        </span>
                        </td>
                        <th>&nbsp; Custom Release Order Date<span class="mandatory">*</span>:</th>
                        <td>
                            <input type="text" ng-model="custom_release_order_date" name="custom_release_order_date"
                                   id="custom_release_order_date"
                                   class="form-control input-sm datePicker" placeholder="Custom Release Order Date"
                                   required>
                            <span class="error" ng-show="dRForm.custom_release_order_date.$invalid && submitted">
                         Custom Release Order Date is required
                        </span>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;

                        </td>
                    </tr>
                    <tr>
                        {{-- <th>Paid Tax : </th> --}}
                        <th>AIN No<span class="mandatory">*</span> :</th>
                        <td>
                            <input type="text" ng-model="ain_no" name="ain_no" id="m_Importer_Name"
                                   class="form-control input-sm" placeholder="AIN No" required>
                            <span class="error" ng-show="dRForm.ain_no.$invalid && submitted">
                          AIN No is required
                        </span>
                        </td>
                        <th>&nbsp; C&F Name<span class="mandatory">*</span> :</th>
                        <td>
                            {{-- <input type="text"  ng-model="paid_date"  name="paid_date" id="paid_date" class="form-control datePicker input-sm" placeholder="Paid Date"> --}}
                            <input type="text" ng-model="cnf_name" ng-disabled="cnfNameDisable" name="cnf_name"
                                   id="cnf_name" class="form-control input-sm" placeholder="C&F Name" required>

                            {{-- <span class="error" ng-show="dRForm.paid_date.$touched && !paid_date">
                                  Paid Date is required
                                </span> --}}
                            <span class="error" ng-show="dRForm.cnf_name.$invalid && submitted">
                              C&F Name is required
                            </span>
                        </td>


                    </tr>

                    <tr>
                        <td colspan="4">
                            <hr style="border-width: 2px;">
                        </td>
                    </tr>


                    <tr>
                        <th>Carpenter Packages :</th>

                        <td>
                            <input type="number" ng-model="carpenter_packages" name="carpenter_packages"
                                   id="carpenter_packages"
                                   class="form-control input-sm" placeholder="Packages No">

                        </td>
                        <th>&nbsp; Repair Packages :</th>
                        <td>
                            <input type="number" ng-model="carpenter_repair_packages" name="carpenter_repair_packages"
                                   id="carpenter_repair_packages" class="form-control input-sm"
                                   placeholder="Carpenter Repair Packages No">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;
                        </td>
                    </tr>

                    <tr>

                        <th>Delivery Date<span class="mandatory">*</span>:</th>
                        <td>
                            <input type="text" ng-model="approximate_delivery_date" name="approximate_delivery_date"
                                   id="approximate_delivery_date"
                                   class="form-control input-sm datePicker" placeholder="Delivery Date" required ng-disabled="delivery_date">
                            <span class="error" ng-show="dRForm.approximate_delivery_date.$invalid && submitted">
                                Delivery Date is required
                            </span>
                        </td>
                        <th>&nbsp; Loading Type<span class="mandatory">*</span>:</th>
                        <td>

                            <select title="" class="form-control input-sm" ng-change="changeAapproximateDeliveryType(approximate_delivery_type)" ng-model="approximate_delivery_type" ng-init="approximate_delivery_type='0'">

                                <option value="0">Labour</option>
                                <option value="1">Equipment</option>
                                <option value="2">Both</option>
                                <option value="3">Self</option>
                                <option value="4">None</option>

                            </select>
                        </td>

                    </tr>

                    <tr>
                        <td colspan="4">&nbsp;

                        </td>
                    </tr>

                    <tr>
                        <th>Labour Weight:</th>
                        <td>
                            <input type="number" ng-model="approximate_labour_load" name="approximate_labour_load"
                                   class="form-control input-sm" placeholder="Type Appx. Labour Weight"
                                   ng-required="labourWeightMust" ng-disabled="equipmentWeightMust && !(labourWeightMust && equipmentWeightMust) || (!labourWeightMust && !equipmentWeightMust)">
                            <span class="error" ng-show="dRForm.approximate_labour_load.$invalid && submitted">
                                Approximate Labour Weight
                            </span>
                        </td>
                        <th>&nbsp;Equipment Weight:</th>
                        <td>
                            <input type="number" ng-model="approximate_equipment_load" name="approximate_equipment_load"
                                   class="form-control input-sm" placeholder="Type Appx. Equipment Load"
                                   ng-required="equipmentWeightMust" ng-disabled="labourWeightMust && !(labourWeightMust && equipmentWeightMust) || (!labourWeightMust && !equipmentWeightMust)" />
                            <span class="error"
                                  ng-show="dRForm.approximate_equipment_load.$invalid && submitted">
                                Approximate Equipment Weight
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4">&nbsp;

                        </td>
                    </tr>

                    <tr>
                        <th>Transport Type<span class="mandatory">*</span>:</th>
                        <td style="text-align: left">
                            <select class="form-control input-sm" ng-change="changeApprxTransportFlag(local_transport_type)" ng-model="local_transport_type" ng-init="local_transport_type='0'">
                                <option value="0">Truck</option>
                                <option value="1">VAN</option>
                                <option value="2">Self</option>
                                <option value="3">Both</option>
                            </select>
                        </td>

                        <th>&nbsp;Gate Pass No{{--<span class="mandatory">*</span> --}}:</th>
                        <td>
                            <input type="text" ng-model="gate_pass_no" ng-disabled="gate_pass" name="gate_pass_no"
                                   id="gate_pass_no" class="form-control input-sm"
                                   placeholder="Gate Pass No" {{--required--}}>
                            {{--<span class="error" ng-show="dRForm.gate_pass_no.$invalid && submitted">--}}
                            {{--Gate Pass No is required--}}
                            {{--</span>--}}
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4">&nbsp;

                        </td>
                    </tr>

                    <tr>
                        <th>Transport Truck:</th>
                        <td>
                            <input type="number" ng-model="transport_truck" name="transport_truck" id="transport_truck" class="form-control input-sm" placeholder="Transport Truck" ng-required="!transportTruckMust" ng-disabled="transportTruckMust"/>
                            <span class="error" ng-show="dRForm.transport_truck.$invalid && submitted">
                              Transport Truck is required
                            </span>
                        </td>
                        <th>&nbsp;Transport VAN:</th>
                        <td>
                            <input type="number" ng-model="transport_van" name="transport_van" id="transport_van" class="form-control input-sm" placeholder="Transport VAN" ng-required="!transportVanMust" ng-disabled="transportVanMust"/>
                            <span class="error" ng-show="dRForm.transport_van.$invalid && submitted">
                                Transport VAN is required
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;

                        </td>
                    </tr>
                    

                    <tr>

                        <th>BD Weighment:</th>
                        <td>
                            <input type="number" ng-model="bd_weighment" name="bd_weighment"
                                   class="form-control input-sm"
                                   placeholder="BD Weighment" {{--ng-required="!chassis_transport"--}}/>
                            {{--<span class="error"--}}
                                  {{--ng-show="dRForm.bd_weighment.$invalid && submitted">--}}
                                {{--BD Weighment is required--}}
                            {{--</span>--}}
                        </td>
                        <th  {{--style="padding-left: 15px;"--}}>&nbsp;Shifting:</th>
                        <td>
                            <label class="radio-inline">
                                <input type="radio"  ng-model="shifting_flag" value="1">Yes
                            </label>
                            <label class="radio-inline">
                                <input type="radio" ng-model="shifting_flag" ng-init="shifting_flag='0'"  ng-checked="true" value="0">No
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;

                        </td>
                    </tr>

                    <tr>

                        <th>BD Haltage:</th>
                        <td>
                            <input type="number" ng-model="bd_haltage" name="bd_haltage"
                                   class="form-control input-sm"
                                   placeholder="BD Haltage"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;

                        </td>
                    </tr>

                    <tr>
                        <td></td>

                        <td style="/*background-color: yellow;*/ padding-left: 100px">
                            <br>

                            <button style="" type="button" ng-click="saveDeliveryData(dRForm)" ng-if="!updateBtn"
                                    {{-- ng-disabled="!be_no ||!be_date||!ain_no||!cnf_name||!no_del_truck" --}}
                                    class="btn btn-primary center-block">{{--<span class="fa fa-file"></span>--}} <span
                                        {{--id="saveManifestDataBtn"--}}>Save Request</span>
                            </button>
                            <button type="button" ng-click="updateDeliveryData(dRForm)" ng-if="updateBtn"
                                    class="btn btn-primary center-block">
                                Update Request
                            </button>
                            <br>

                        </td>

                        <td></td>

                        <td>
                            {{--<button type="button" ng-click="addTruckModalBtn()" data-toggle="modal"--}}
                                    {{--data-target="#addTruckFormModal" class="btn btn-primary center-block"><span--}}
                                        {{--class="fa fa-download"></span> Local Transport--}}
                            {{--</button>--}}
                            <a class="btn btn-success btn-sm text-right" href="{{url('/warehouse/delivery/delivery-local-transport-delivery-form')}}/@{{GetManiNo }}">
                                <i class="fa fa-road"></i>Delivery
                            </a>
                        </td>

                    </tr>

                    <tr>
                        <td class="text-center" colspan="6">

                            <div id="maniBEsuccessmsg" class="col-md-12 alert alert-success" ng-show="maniBEsuccessmsg">
                                Successfully @{{ SuccessMessage }}
                            </div>
                            <div id="maniBEerrormsg" class="col-md-12 alert alert-danger" ng-show="maniBEerrormsg">
                                @{{message}}
                            </div>



                            <div id="errormsgdiv" class="col-md-12 alert alert-danger" ng-show="errormsgdiv">
                                @{{errormsg}}
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="col-md-12 table-responsive">
            <div id="manifestDetails" ng-hide="todaysEntryDiv">
                <h4 class="text-center ok">Delivery Request Details:</h4>


                <table class="table table-bordered table-hover table-striped" id="manifestTbl">
                    <thead>

                    <tr>
                        <td colspan="14" class="text-center" ng-if="manifestDataLoading">
                        <span style="color:green; text-align:center; font-size:15px">
                            <img src="{{URL::asset('/img/dataLoader.gif')}}" width="250" height="15"/>
                            <br/> Please wait!
                        </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="14" class="text-center" ng-if="manifestDataLoadingError">
                        <span style="color:red; text-align:center; font-size:15px">
                            <p>@{{ searchTextNotFoundTxt }} Couldn't found</p>
                        </span>
                        </td>
                        <td colspan="14" class="text-center" ng-if="permissionError" id="permissionError">
                        <span style="color:red; text-align:center; font-size:15px">
                            <p>@{{ permissionError}}</p>
                        </span>
                        </td>
                    </tr>
                    <tr>

                        <th>S/L</th>
                        <th>Carpenter Packages</th>
                        <th>Delivery Date</th>
                        <th>Loading Type</th>
                        <th>Labour Weight</th>
                        <th>Equipment Weight</th>
                        <th>Transport Type</th>
                        <th>Gate Pass No</th>
                        <th>Transport Truck</th>
                        <th>Transport VAN</th>
                        <th>BD Weighment</th>
                        <th>Shifting</th>
                        <th>BD Haltage</th>
                        <th>Action</th>

                    </tr>
                    </thead>

                    <tbody>

                    <tr dir-paginate="data in allData|itemsPerPage:10">

                        <td>@{{$index+1}}</td>
                        <td>@{{data.carpenter_packages}}</td>
                        <td>@{{data.approximate_delivery_date}}</td>
                        <td>@{{data.approximate_delivery_type | loading}}</td>
                        <td>@{{data.approximate_labour_load}}</td>
                        <td>@{{data.approximate_equipment_load}}</td>
                        <td>@{{data.local_transport_type |transportTypeFilter }}</td>
                        <td>@{{data.gate_pass_no}}</td>
                        <td>@{{data.transport_truck}}</td>
                        <td>@{{data.transport_van}}</td>
                        <td>@{{ data.bd_weighment }}</td>
                        <td>@{{ data.m_shifting_flag | shifting_flag }}</td>
                        <td>@{{ data.bd_haltage }}</td>
                        <td style="text-align: center">
                            <a class="btn btn-primary btn-md" ng-click="edit(data)" {{--data-target="#editTrucEntryModal"--}}
                               data-toggle="modal">Edit</a>
                            {{--<a class="btn btn-primary" ng-hide="truck.receive_datetime ==null || truck.be_no !=null" ng-click="Request(truck)">Add Request</a>--}}
                            {{--<p class="error" ng-show="truck.receive_datetime ==null">Haven't Received Yet</p>--}}
                            {{--<p class="ok" ng-show="truck.be_no !=null">B/E Completed</p>--}}

                        </td>

                    </tr>

                    </tbody>

                    <tfoot>
                    <tr>
                        <td colspan="14" class="text-center">

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
        <!-- addTruckFormModal Modal START=====================================================================================================MODAL========== -->
        <div id="addTruckFormModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content largeModal" id="">
                    <div class="modal-header">
                        <button type="button" class="btn btn-info btn-xs" data-toggle="modal"
                                data-target="#addTruckType">Add Truck Type
                        </button>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center" style="color: #000;">Input Local Transport Information</h4>
                        <h5 class="text-center" style="color: red;" ng-show="assesmentStatus">@{{assesmentStatus}}</h5>
                        <h5>You are inserting data against Manifest No: <b>@{{GetManiNo}}</b></h5>
                        {{--<a href="" class="pull-right" onclick="generatePDF()">Get DB Truck Info</a>--}}
                        <a ng-show="GetManiID" href="{{ url('getBdTruckInfoPDF') }}/@{{ ManifestIdModal }}"
                           target="_blank">
                            <button type="button" class="btn btn-primary pull-right"><span class="fa fa-search"></span>
                                Local Truck Report
                            </button>
                        </a>
                        <div class="col-md-3">

                            <h6>Manifest Gross Weight: <b>@{{ ManiNweight }}</b></h6>
                            <h6> Total BD Truck Weight: <b>@{{BdTruckTotalLoad}}</b></h6>
                            <h6> Remaining Weight: <b>@{{ManiNweight-BdTruckTotalLoad}}</b></h6>
                        </div>

                        <div class="col-md-6">
                            <h5><span class="error">@{{ BdTruckNoFull }}</span></h5>
                            {{-- <h5>Scdasdasdasdasdas das adasd </h5> --}}
                        </div>

                        <div id="localTrnsportGlobalNotification" class="col-md-12 alert alert-warning text-center" ng-show="localTrnsportGlobalNotification">
                            <i class="fa fa-warning"></i> @{{ localTrnsportGlobalNotificationTxt }}
                        </div>



                        <input type="hidden" ng-model="ManifestIdModal"/>
                    </div>
                    <div class="modal-body">

                        <div class="col-md-7 col-md-offset-3">
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

                        <div id="localTrnsportGlobalError" class="col-md-12 alert alert-danger text-center" ng-show="localTrnsportGlobalError">
                            @{{ localTrnsportGlobalErrorTxt }}
                        </div>

                        <div id="localTrnsportGlobalSuccess" class="col-md-12 alert alert-success text-center" ng-show="localTrnsportGlobalSuccess">
                            @{{ localTrnsportGlobalSuccessTxt }}
                        </div>

                        <br><br>

                        {{------------------------------------------------------- Local Truck Panel Panel ------------------------------------- start -------}}

                        <div class="panel panel-default" ng-show="LocalTransportTruckForm">

                            <div class="panel-heading">Local Truck Information
                                {{--<div class="row">
                                    <div class="col-sm-6 col-md-6">
                                        <p ng-if="BdTruckManiIDBlankMsg" class="error text-center">
                                            Please insert B/E First!</p>
                                    </div>
                                </div>--}}

                                <button type="button"
                                        ng-click="getBdTruckData(GetManiID)" class="btn btn-primary btn-xs">
                                    Reload
                                </button>
                            </div>
                            <div class="panel-body">

                                <form class="formBgColor" name="bdTruckForm" id="bdTruckForm"
                                      style="padding: 10px; margin: 0 auto;">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <label style="padding: 0" class="col-sm-4 col-md-2 control-label">
                                                Chassis/Tructor: <span class="mandatory">*</span> :
                                            </label>
                                            <div class="col-sm-8 col-md-10">
                                                <span ng-repeat="item in undelivered_chassis">
                                                     <label class="checkbox-inline">
                                                        <input ng-model="onVehicleTransportId[item.id]" type="checkbox"
                                                               value="@{{ item.id }}">
                                                       <b>  @{{ item.chassis_type }} - @{{ item.chassis_no }}</b>
                                                      </label>
                                                </span>

                                            </div>
                                        </div>
                                    </div>
                                    <br/>

                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            <label style="padding: 0" class="col-sm-4 col-md-4 control-label">
                                                Transport No<span class="mandatory">*</span> :
                                            </label>
                                            <div class="col-sm-8 col-md-8">
                                                <div class="input-group">
                                                    <select title="" class="form-control input-sm"
                                                            style="width: 50%"
                                                            name="truck_type" id="truck_type" ng-model="truck_type"
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
                                                          ng-show="bdTruckForm.bd_truck_no.$invalid && submittedBDTruck && !BDTruckFull">
                                                  Transport No is required
                                                </span>
                                                    <br/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">

                                            <label style="padding: 0" for="driver_name"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Driver Name:<span class="error">*</span>
                                            </label>

                                            <div class="col-sm-8 col-md-8">
                                                <input ng-model="driver_name"
                                                       maxlength="100"
                                                       id="chDriverName"
                                                       required="required"
                                                       class="input-sm form-control"
                                                       placeholder="Type Driver Name"
                                                       tabindex=3/>
                                                <span ng-cloak class="error" ng-show="!driver_name && submittedBDTruck">Please Type Driver's Name!</span>

                                                <br/>
                                            </div>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row">

                                        <div class="col-sm-6 col-md-6">
                                            <label style="padding: 0" for="labor_load"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Labour Weight:<span class="error">*</span>
                                            </label>
                                            <div class="col-sm-8 col-md-8">

                                                <input type="number" ng-model="labor_load"
                                                       name="labor_load"
                                                       class="form-control input-sm"
                                                       placeholder="Labor Load"
                                                       ng-required="!( labor_load || equip_load)"
                                                       ng-change="getEquipmentWeight()"
                                                       ng-disabled="disableWhenTranshipment">
                                                <br/>
                                                <span ng-cloak class="error" ng-show="!labor_load && submittedBDTruck">Please Type Labour Weight!</span>

                                            </div>


                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <label style="padding: 0" for="driver_name"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Labour Packages:<span class="error"></span>
                                            </label>
                                            <div class="col-sm-8 col-md-8">
                                                <input type="number" ng-model="labor_package"
                                                       name="labor_package"
                                                       class="form-control input-sm"
                                                       placeholder="Labor Package"
                                                       ng-change="getLabourWeight()">
                                                <br>
                                            </div>
                                        </div>

                                    </div>
                                    <br>

                                    <div class="row" ng-hide="cnfModuleFormHide">
                                        <div class="col-sm-6 col-md-6">
                                            <label style="padding: 0" for="driver_name"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Equip. Weight:<span class="error">*</span>
                                            </label>
                                            <div class="col-sm-8 col-md-8">
                                                <input type="number" ng-model="equip_load"
                                                       name="equip_load"
                                                       class="form-control input-sm" placeholder="Equipment Load"
                                                       ng-required="!(labor_load || equip_load)"
                                                       ng-change="getLabourWeight()"
                                                       ng-disabled="disableWhenTranshipment">

                                                <br>
                                            </div>


                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <label style="padding: 0" for="driver_name"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Equip. Package:<span class="error">*</span>
                                            </label>
                                            <div class="col-sm-8 col-md-8">
                                                <input type="number" ng-model="equipment_package"
                                                       name="equipment_package"
                                                       class="form-control input-sm" placeholder="Equipment Package"
                                                       ng-disabled="disableWhenTranshipment">
                                                <br>
                                            </div>

                                        </div>
                                    </div>
                                    <br>
                                    <div class="row" ng-hide="cnfModuleFormHide">
                                        <div class="col-sm-6 col-md-6">
                                            <label style="padding: 0" for="driver_name"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Equipment Name:<span class="error">*</span>
                                            </label>

                                            <div class="col-sm-8 col-md-8">

                                                <input type="text" ng-model="equip_name"
                                                       name="equip_name" id="equip_name"
                                                       class="form-control input-sm"
                                                       placeholder="Equipment Name"
                                                       ng-required="equip_load" ng-disabled="disableWhenTranshipment">

                                                <span class="error"
                                                      ng-show="bdTruckForm.equip_name.$invalid && submittedBDTruck && !BDTruckFull">
                                                    Equipment Name Required!
                                                </span>

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <label style="padding: 0" for="driver_name"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Delivery Date:<span class="error">*</span>
                                            </label>

                                            <div class="col-sm-8 col-md-8">
                                                <input type="text" ng-model="delivery_dt" name="delivery_dt"
                                                       id="delivery_dt"
                                                       class="form-control input-sm datePicker"
                                                       placeholder="Select Date"
                                                       required>
                                                <span class="error"
                                                      ng-show="bdTruckForm.delivery_dt.$invalid && submittedBDTruck && !BDTruckFull">Delevery Date is Required!</span>
                                                <br>
                                            </div>

                                        </div>
                                    </div>
                                    <br>
                                    <div class="row" ng-hide="cnfModuleFormHide">
                                        <div class="col-sm-6 col-md-6">
                                            <label style="padding: 0" for="driver_name"
                                                   class="col-sm-4 col-md-4 control-label">
                                                Haltage Day:<span class="error" ng-show="!FatherName">*</span>
                                            </label>
                                            <div class="col-sm-8 col-md-8">
                                                <input type="number" class="form-control input-sm"
                                                       name="haltage_day"
                                                       placeholder="Type Haltage Day"
                                                       ng-model="haltage_day" id="haltage_day">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <label style="padding: 0" for="driver_name"
                                                   class="col-sm-4 col-md-4 control-label">
                                                WeighMent:<span class="error" ng-show="!FatherName">*</span>
                                            </label>
                                            <div class="col-sm-8 col-md-8">

                                                <label class="radio-inline">
                                                    <input type="radio" ng-model="weightment_flag"
                                                           value="1">Yes
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" ng-init="weightment_flag=0"
                                                           ng-model="weightment_flag"
                                                           value="0" ng-checked="true">No
                                                </label>
                                                <br>
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

                                            <br>
                                            <span class="error" ng-show="(bdTruckForm.labor_load.$error.required || bdTruckForm.equip_load.$error.required) && submittedBDTruck && !BDTruckFull">
                                                 Must be fillup Labour or Equipment Load
                                            </span>
                                            <br>
                                            <button id="saveBdTruckData" type="button"
                                                    ng-click="saveBdTruckData(bdTruckForm)"
                                                    class="btn btn-primary" ng-if="buttonBdTruck"><span
                                                        class="fa fa-file"></span> Save
                                            </button>
                                            <br>

                                            <span ng-show="savingLocalTransportData" style="color:green; text-align:center; font-size:15px">
                                                    <img src="{{URL::asset('/images/dataLoader.gif')}}" width="250"
                                                         height="15"/>
                                                    <br/> Please wait!
                                                </span>
                                        </div>
                                        <div id="localTransportSuccess" ng-show="localTransportSuccess"
                                             class="col-md-8 col-md-offset-2 alert alert-success text-center">
                                            @{{localTransportSuccessMsgTxt}}
                                        </div>
                                        <div id="localTransportError" ng-show="localTransportError"
                                             class="col-md-8 col-md-offset-2 alert alert-danger text-center">
                                            @{{localTransportErrorMsgTxt}}
                                        </div>


                                        {{--</div>--}}
                                        {{--</div>--}}

                                    </div>
                                </form>

                            </div>

                            <div class="panel-footer">
                                {{--List Od all trucks of the manifest--}}
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

                                        <td>@{{$index +1}}</td>
                                        <td>@{{bdTruck.truck_no}} @{{bdTruck.transport_type }}</td>
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
                                            <button type="button" ng-click="deleteBdTruck(bdTruck)"
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
                        {{------------- ===========Self Chassis Form Panel ------------------------------}}

                        <div class="panel panel-info" ng-show="ChassisInformationForm">

                            <div class="panel-heading">Self Transport Information</div>

                            <div class="panel-body">
                                <div class="col-md-sm-12 col-md-10 col-md-offset-1">
                                    <form class="formBgColor" name="selfTransportForm" id="selfTransportForm" novalidate
                                          style="padding: 10px; margin: 0 auto;">
                                        <div class="row">
                                            <div class="col-sm-6 col-md-6">

                                                <label for="trSelfTransportId" style="padding: 0"
                                                       class="col-sm-4 col-md-4 control-label">
                                                    Chassis :<span class="error">*</span>
                                                </label>

                                                <div class="col-sm-8 col-md-8">
                                                    <select title="" data-ng-options="chassis.id as (chassis.chassis_type +'-'+chassis.chassis_no ) for chassis in undelivered_chassis"
                                                            class="form-control input-sm" name="selfTransportId"
                                                            data-ng-model="selfTransportId"
                                                            ng-init="selfTransportId=''">
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
                                                           required>
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
                                                           tabindex=3/>
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
                                                           tabindex=4/>
                                                    <span ng-cloak class="error"
                                                          ng-show="!selfTransportDriverCard && selfTransportFormSubmitted">Please Type Driver Card!</span>
                                                    <br/>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col-md-12 text-center">


                                                <button id="" type="button"
                                                        ng-click="saveSelfTransportData(selfTransportForm)"
                                                        class="btn btn-primary btn-sm"><span
                                                            class="fa fa-file"></span> Save Self Transport
                                                </button>
                                                <div id="selfTransportSuccess" ng-show="selfTransportSuccess"
                                                     class="col-md-8 col-md-offset-2 alert alert-success text-center">
                                                    @{{saveChSuccessMsgTxt}}
                                                </div>
                                                <div id="selfTransportError" ng-show="selfTransportError"
                                                     class="col-md-8 col-md-offset-2 alert alert-danger text-center">
                                                    Something went worng!
                                                </div>


                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>

                            <div class="panel-footer panel-primary">
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
                                        <td>@{{self.delivery_date|stringToDate:'medium'}}</td>

                                        <td>
                                            {{--<button type="button" ng-click="editBdTruck(chassis)"
                                                    class="btn btn-primary btn-xs">Edit
                                            </button>--}}
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
                            </div><!--.panel-footer-->
                        </div>
                        {{--=========Self Chassis Panel End=======================--}}


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
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
                                            <img src="img/dataLoader.gif" width="250" height="15"/>
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