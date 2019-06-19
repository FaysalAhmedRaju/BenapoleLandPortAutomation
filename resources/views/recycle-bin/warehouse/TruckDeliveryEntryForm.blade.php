@extends('layouts.master')
@section('title', 'Truck Delivery Entry')
@section('style')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')
   {{-- <script type="text/javascript">
        $('#delivery_dt').datepicker({
                dateFormat: 'yy-mm-dd',
        })

        $('#approve_dt').datepicker({
                dateFormat: 'yy-mm-dd',
        }) 
    </script> --}}
    {!!Html :: script('js/jquery-ui-timepicker-addon.js')!!}
    {!!Html :: script('js/bootbox.min.js')!!}
    {!!Html :: script('js/customizedAngular/truckDelivery.js')!!}
@endsection
@section('content')
        <div class="col-md-12  ng-cloak" ng-app="truckDeliveryEntryApp" ng-controller="truckDeliveryEntryController">
           <div class="col-md-10 col-md-offset-2">
                <form class="form-inline" ng-submit="truckNoOrManifestSearch()">
                    <div class="form-group">
                        Search By:
                        <select ng-change="select()" class="form-control" name="singleSelect" ng-model="selection.singleSelect">
                            <option value="">---Please Select---</option>
                            <option value="manifestNo">Manifest No</option>
                            <option value="truckNo">Truck No</option>
                          {{--<option value="yardNo">Yard No</option>--}}
                        </select>
                        <input type="text" class="form-control" name="searchKey" ng-model="searchKey" ng-disabled="serachField" id="searchKey" placeholder="@{{ placeHolder }}" ng-keydown="keyBoard($event)">
                    </div>
                </form>
                <div class="clear-fix"></div>
                <div class="col-md-8 col-md-offset-2">
                    <span style="color: red;" ng-show="assesmentStatus"><b>@{{assesmentStatus}}</b></span>
                </div>
                <div class="col-md-6 col-md-offset-6">
                    <a href="{{ url('todaysTruckDeliveryEntryPDF') }}" target="_blank"><button type="button" class="btn btn-primary"><span class="fa fa-search"></span>Today's Truck Delivery</button></a>
                    <a href="ManifestDetailsPDF/@{{ searchKey }}" target="_blank" ><button type="button" class="btn btn-primary" ng-disabled="reportManifest"><span class="fa fa-search"></span>Manifest Details</button></a>
                </div>
            </div>
            <div class="clear-fix"></div>
            <br>
            <div class="col-md-10 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">Truck Delivery Entry</h4>
                <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
                <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
                <div class="col-md-7">
                    <table class="table table-bordered" style="text-align: left;">
                        <tr>
                            <th>Truck No:</th>
                            <td>@{{truck_no }}</td>
                            <th>Remaining Weight:</th>
                            <td ng-hide="selection.singleSelect == 'truckNo'">@{{ (totalNetWeight() - totalLodingUnit()).toFixed(2) }}</td>
                            {{-- <th>Cargo Description:</th>
                            <td>@{{ goodsData[0].cargo_description }}</td> --}}
                        </tr>
                    </table>
                </div>
                <div class="col-md-12">
                    <form   name="truckdeliveryEntryForm" id="truckdeliveryEntryForm" novalidate>
                    <table>
                        <tr>
                           {{-- <th>Gross Weight:</th>
                            <td>
                                <input class="form-control" type="number" name="gweight" id="gweight" ng-model="gweight" ng-disabled="show">
                                <span class="error" ng-show="gweight_required">Gross Weight is Required</span>
                            </td> --}}
                            <th>Loading Unit:</th>
                            <td>
                                <input class="form-control" type="number" name="loading_unit" id="loading_unit" ng-model="loading_unit" ng-disabled="show">
                                <span class="error" ng-show="loading_unit_required">Loading Unit is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Package:</th>
                            <td>
                                <input class="form-control" type="text" name="package" id="package" ng-model="package" ng-disabled="show">
                                <span class="error" ng-show="package_required">Package is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Delivery Date:</th>
                            <td>
                                <input class="form-control {{--datePicker--}}" type="text" name="delivery_dt" id="delivery_dt" ng-model="delivery_dt" ng-disabled="show">
                                <span class="error" ng-show="delivery_dt_required">Delivery Date is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                           {{-- <th>Approve Date:</th>
                            <td>
                                <input class="form-control datePicker" type="text" name="approve_dt" id="approve_dt" ng-model="approve_dt" ng-disabled="show">
                                <span class="error" ng-show="approve_dt_required">Approve Date is Required</span>
                            </td> --}}
                            {{--<th>Loading:</th>
                            <td>
                               <label class="radio-inline">
                                    <input  ng-change="handlingLabourOrEquipment()" type="radio" ng-model="loading_flag" value="0" ng-disabled="show" ng-checked="true" ng-init="loading_flag=0">Labour
                                </label>
                                <label class="radio-inline">
                                    <input ng-change="handlingLabourOrEquipment()" type="radio" ng-model="loading_flag" value="1" ng-disabled="show">Equipment
                                </label>
                            </td>--}}
                            {{--<th style="padding-left: 15px;" ng-show="whenEquipment">Equipment Name:</th>
                            <td ng-show="whenEquipment">
                                <input class="form-control" type="text" name="equip_name" id="equip_name" ng-model="equip_name" ng-disabled="show">
                                <span class="error" ng-show="equip_name_required">Equipment Name is Required</span>
                            </td>--}}
                            <th>Labor Load:</th>
                            <td>
                                <input class="form-control" type="number" name="labor_load" id="labor_load" ng-model="labor_load" ng-disabled="show">
                                <span class="error" ng-show="labor_load_required">Labor Load is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Labor Package:</th>
                            <td>
                                <input class="form-control" type="text" name="labor_package" id="labor_package" ng-model="labor_package" ng-disabled="show">
                                <span class="error" ng-show="labor_package_required">Labor Package is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Equipment Load:</th>
                            <td>
                                <input class="form-control" type="number" name="equip_load" id="equip_load" ng-model="equip_load" ng-disabled="show">
                                <span class="error" ng-show="equip_load_required">Equipment Load is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Equipment Name:</th>
                            <td>
                                <input class="form-control" type="text" name="equip_name" id="equip_name" ng-model="equip_name" ng-disabled="show">
                                <span class="error" ng-show="equip_name_required">Equipment Name is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Equipment Package:</th>
                            <td>
                                <input class="form-control" type="text" name="equipment_package" id="equipment_package" ng-model="equipment_package" ng-disabled="show">
                                <span class="error" ng-show="equipment_package_required">Equipment Package is Required</span>
                            </td>
                        </tr>
                        {{--<tr ng-show="whenEquipment">
                            <th>Equipment Name:</th>
                            <td>
                                <input class="form-control" type="text" name="equip_name" id="equip_name" ng-model="equip_name" ng-disabled="show">
                                <span class="error" ng-show="equip_name_required">Equipment Name is Required</span>
                            </td> 
                        </tr>--}}
                        {{--<tr>
                            <th>Equipment Load:</th>
                            <td>
                                <input class="form-control" type="number" name="equip_load" id="equip_load" ng-model="equip_load" ng-disabled="show">
                                <span class="error" ng-show="equip_load_required">Equipment Load is Required</span>
                            </td>
                        </tr>--}}
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center">
                                <button type="button" class="btn btn-primary center-block" ng-click="save()" ng-disabled="show" ng-if="ButtonSave" {{--ng-disabled="!yardPostingEntry.$valid"--}}>Save</button>
                            <td>
                        </tr>
                    </table>
                    </form>
                    <br>
                </div>
            </div>

            <div class="clear-fix"></div>
            <div class="col-md-12">
                <div class="alert alert-danger" id="errorType" ng-hide="!errorType">@{{ errorType }}</div>
                <div class="col-md-12" ng-show="divTable">
                    <table class="table table-bordered">
                    <caption><h4 class="text-center ok">Local Truck Details:</h4></caption>
                        <thead>
                            <tr>
                                <th>S/L</th>
                                <th>Manifest No.</th>
                                <th>Truck No.</th>
                                <th>Driver Name</th>
                                {{--<th>Gross Weight</th>--}}
                                {{--<th>Approve Date</th>--}}
                                <th>Loading Unit</th>
                                <th>Package</th>
                                <th>Delivery Date</th>
                                <th>Labor Load</th>
                                <th>Labor Package</th>
                                <th>Equipment Load</th>
                                <th>Equipment Name</th>
                                <th>Equipment Package</th>
                                {{--<th>Loading Unit</th>
                                <th>Loading</th>
                                <th>Equipment Name</th>--}}
                               {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                     <tbody>
                            <tr ng-style="{'background-color':(bdtruck.id == selectedStyle?'#dbd3ff':'')}" dir-paginate="bdtruck in allBdTrucksData | itemsPerPage:5" pagination-id="bdtruck" ng-click="update(bdtruck)">
                                <td>@{{$index+1}}</td>
                                <td>@{{bdtruck.manifest}}</td>
                                <td>@{{bdtruck.truck_no}}</td>
                                <td>@{{bdtruck.driver_name}}</td>
                               {{-- <td>@{{bdtruck.gweight}}</td> --}}
                               <td>@{{bdtruck.loading_unit}}</td>
                                <td>@{{bdtruck.package}}</td>
                                <td>@{{bdtruck.delivery_dt}}</td>
                                {{--<td>@{{bdtruck.approve_dt}}</td>--}}
                                <td>@{{bdtruck.labor_load}}</td>
                                <td>@{{bdtruck.labor_package}}</td>
                                <td>@{{bdtruck.equip_load}}</td>
                                <td>@{{bdtruck.equip_name}}</td>
                                <td>@{{bdtruck.equipment_package}}</td>
                                {{--<td>@{{bdtruck.loading_flag | offOrloadingFilter}}</td>
                                <td>@{{bdtruck.equip_name}}</td>--}}
                               {{-- <td>
                                    <a class="btn btn-success" ng-click="update(bdtruck)">Update</a>
                                </td> --}}
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="12" class="text-center">
                                    <dir-pagination-controls max-size="5"
                                                         direction-links="true"
                                                         boundary-links="true"
                                                         pagination-id="bdtruck">
                                    </dir-pagination-controls>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <h4 ng-hide="selection.singleSelect == 'truckNo'">Total Loading Unit: @{{ totalLodingUnit() }}</h4>
                   {{--<h4 ng-hide="selection.singleSelect == 'truckNo'">Remaining Weight: @{{ (totalNetWeight() - totalGrossWeight()).toFixed(2) }}</h4> --}} 
                    {{-------------------Indian Truck Details--------------------------------}}
                    <table class="table table-bordered" ng-show="indianTrucksTable">
                    <caption><h4 class="text-center ok">Foreign Truck Details:</h4></caption>
                        <thead>
                            <tr>
                                <th>S/L</th>
                                <th>Manifest No.</th>
                                <th>Truck No.</th>
                                <th>Driver Name</th>
                                <th>Net Weight</th> {{--WeightBridge Net Weight--}}
                                <th>Receive Package</th>
                                <th>Receive Datetime</th>
                                <th>Labor Unload</th>
                                <th>Labor Package</th>
                                <th>Equipment Unload</th>
                                <th>Equipment Name</th>
                                <th>Equipment Package</th>
                                {{--<th>Offloading</th>
                                <th>Equipment Name</th>--}}
                            </tr>
                        </thead>
                     <tbody>
                            <tr dir-paginate="indianTruck in allIndianTrucksData | itemsPerPage:5" pagination-id="indianTruck">
                                <td>@{{$index+1}}</td>
                                <td>@{{indianTruck.manifest}}</td>
                                <td>@{{indianTruck.truck_no}}</td>
                                <td>@{{indianTruck.driver_name}}</td>
                                <td>@{{indianTruck.tweight_wbridge}}</td> {{--WeightBridge Net Weight--}}
                                <td>@{{indianTruck.receive_package}}</td>
                                <td>@{{indianTruck.receive_datetime}}</td>
                                <td>@{{indianTruck.labor_unload}}</td>
                                <td>@{{indianTruck.labor_package}}</td>
                                <td>@{{indianTruck.equip_unload}}</td>
                                <td>@{{indianTruck.equip_name}}</td>
                                <td>@{{indianTruck.equipment_package}}</td>
                                {{--<td>@{{indianTruck.offloading_flag | offOrloadingFilter }}</td>
                                <td>@{{indianTruck.equip_name}}</td>--}}
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="12" class="text-center">
                                    <dir-pagination-controls max-size="5"
                                                         direction-links="true"
                                                         boundary-links="true"
                                                         pagination-id="indianTruck">
                                    </dir-pagination-controls>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <h4 ng-hide="selection.singleSelect == 'truckNo'">Total Net Weight: @{{ totalNetWeight() }}</h4>
                <div>
            </div>
        </div>
        <script type="text/javascript">
            $('#delivery_dt').datetimepicker({
                showButtonPanel: true,
                dateFormat: 'yy-mm-dd',
                timeFormat: 'HH:mm:ss'
            });
        </script>
@endsection