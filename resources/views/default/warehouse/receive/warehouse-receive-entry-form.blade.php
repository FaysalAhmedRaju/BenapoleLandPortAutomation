@extends('layouts.master')
@section('title', 'WareHouse Entry Form')
@section('style')
    {!! Html::style('css/jquery-ui-timepicker-addon.css') !!}
    {!! Html::style('css/jquery.growl.css') !!}


    <style type="text/css">

        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }

        /*.modal-dialog {*/
            /*width: 80%;*/
        /*}*/

        .modal {
            text-align: center;
            padding: 0!important;
            left: 0px;
        }

        .cell_background {

            color: #FFF;
            font-weight: bolder;
            padding: 10px;

            /*background: red; !* For browsers that do not support gradients *!*/
            /*background: -webkit-linear-gradient(left,rgba(255,0,0,0),rgba(255,0,0,1)); !*Safari 5.1-6*!*/
            /*background: -o-linear-gradient(right,rgba(255,0,0,0),rgba(255,0,0,1)); !*Opera 11.1-12*!*/
            /*background: -moz-linear-gradient(right,rgba(255,0,0,0),rgba(255,0,0,1)); !*Fx 3.6-15*!*/
            /*background: linear-gradient(to right, rgba(255,0,0,0), rgba(255,0,0,1)); !*Standard*!*/

            background: red; /* For browsers that do not support gradients */
            background: -webkit-radial-gradient(red, yellow, green); /* Safari 5.1 to 6.0 */
            background: -o-radial-gradient(red, yellow, green); /* For Opera 11.6 to 12.0 */
            background: -moz-radial-gradient(red, yellow, green); /* For Firefox 3.6 to 15 */
            background: radial-gradient(red, yellow, green); /* Standard syntax */

            /*background: linear-gradient(90deg, pink 50%, cyan 50%);*/

        }

        .normal_cell {
            padding: 10px;

        }

        .head_style {
            background-color: #0000cc;
            color: #fff;
            padding: 0 10px;
        }


    </style>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).on('hidden.bs.modal', '.modal', function () {
            $('.modal:visible').length && $(document.body).addClass('modal-open');
        });
        var role_name = {!! json_encode(Auth::user()->role->name) !!};
    </script>

    {!! Html::script('js/jquery-ui-timepicker-addon.js')!!}
    {!! Html::script('js/customizedAngular/warehouse/warehouse-receive.js')!!}
    {!!Html :: script('js/bootbox.min.js')!!}
    {!! Html::script('js/lodash.js') !!}
    {!! Html::script('js/jquery.growl.js') !!}


@endsection
@section('content')
    <div class="col-md-12  ng-cloak" ng-app="WareHouseEntryApp" ng-controller="WareHouseEntryController">

        <div class="col-md-12" style="/*background-color:  black*/">
            <div class="col-md-5 col-md-offset-0" style="/*background-color:  red*/">
                <form class="form-inline" ng-submit="truckNoOrManifestWiseSearch()">
                    <div class="form-group">
                        <label for="">Search By:</label>
                        <select title="" ng-change="select()" class="form-control"
                                ng-init="selection.singleSelect='manifestNo'" name="singleSelect"
                                ng-model="selection.singleSelect">
                            <option value="manifestNo">Manifest No</option>
                            <option value="truckNo">Truck Type-No</option>
                        </select>
                        <input type="text" class="form-control" name="searchKey" ng-model="searchKey"
                               {{--ng-disabled="serachField"--}} id="searchKey" placeholder="@{{ placeHolder }}"
                               ng-keydown="keyBoard($event)" style="width: 150px;" ng-change="Blank()">
                    </div>
                </form>
            </div>

            <div class="col-md-7" style="padding: 0px; /*background-color:  yellow*/">
                <form action="{{ route('warehouse-receive-date-wise-entry-report') }}" class="form-inline" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <select  ng-init="vehile_type_flage_pdf = '1'" style="width: 150px"  name="vehile_type_flage_pdf"  ng-model="vehile_type_flage_pdf"  class="form-control input-sm" >
                        {{--<optgroup label="(1). Truck">--}}
                            <option value="1" selected>Truck</option>
                            <option value="2">Chassis(on Trailer)</option>
                            <option value="3">Tractor(on Trailer)</option>
                            <option value="4">Covered Van</option>
                            <option value="5">Lorry</option>
                            <option value="6">Mini Pickup</option>
                            <option value="7">Prime Mover</option>
                            <option value="8">Tanker</option>
                            <option value="9">Vehicle in CBU</option>
                        {{--</optgroup>--}}
                        {{--<optgroup label="(2). Self">--}}
                            <option value="11">Chassis(Self)</option>
                            <option value="12">Trucktor(Self)</option>
                            <option value="13">Bus</option>
                            <option value="14">Three Wheller</option>
                            <option value="15">Rickshaw</option>
                            <option value="16">Car(Self)</option>
                            <option value="17">Pick Up(Self)</option>
                            <option value="18">Trailor(Self)</option>
                        {{--</optgroup>--}}
                    </select>
                    <div class="input-group" >
                        <input type="text" class="form-control datePicker" style="width: 150px" name="date" placeholder="Select Receive Date">
                        <div class="input-group-btn">
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                            <button type="submit" name="submit_truck" value="1" class="btn btn-primary">
                                {{-- <span class="fa fa-calendar-o"></span>--}} Truck Wise Report
                            </button>
                            <button type="submit" name="submit_manifest" value="1" class="btn btn-info">
                                {{-- <span class="fa fa-calendar-o"></span>--}} Manifest Wise Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{--<div class="col-md-2" style="/*padding: 0px;*/ /*background-color:  green*/">--}}
                {{--<a href="BeDoneManifestDetailsPDF/@{{ searchKey }}" target="_blank">--}}
                    {{--<button type="button" class="btn btn-primary"><span class="fa fa-search"></span>--}}
                        {{--Manifest Info--}}
                    {{--</button>--}}
                {{--</a>--}}
            {{--</div>--}}
        </div>

        <div class="clearfix"></div>
        <br>
        <div class="col-md-10 col-md-offset-1" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">WareHouse Receiving</h4>
            {{--<h5 class="text-center" style="color:  red" ><b>@{{ shedYardModelMessage }}</b></h5>--}}
            <h5 class="text-center" style="color:green">
                <b>You are from:</b>
                @foreach($shed_yard_details as $k=>$v)
                    <span style="color: sandybrown"> {{$v->yard_shed_name}}, </span>

                @endforeach
            </h5>
         <h5 class="text-center" style="color:  red"  ng-if="receiveWeightFlagWhenNoWeightBridge">@{{ weightMessage }}</h5>
            <div class="col-md-12">
                <table style="text-align: left; ">
                    <tr  ng-show="AfterSearchShow" >
                        <th style="color:green">Allocated Shed/Yard: &nbsp;&nbsp;</th>
                        <td>
                    <span ng-repeat="shedYard in  allTrucksData[0].posted_yard_shed_name.split('?')" >
                         <span class="label label-primary" style="margin-right:5px;">
                                @{{shedYard}}
                         </span>
                    </span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table style="text-align: left; ">


                    <tr ng-show="manifestInfo">
                        <th style="width:120px;">Importer Name:</th>
                        <td style="width: 180px;">@{{ allTrucksData[0].NAME }}</td>
                        <th style="width: 120px;">Exporter Name:</th>
                        <td style="width: 150px;">@{{ allTrucksData[0].exporter_name_addr }}</td>
                        <th style="width: 96px;">Goods Name:</th>
                        <td>@{{ allTrucksData[0].cargo_name }}</td>
                    </tr>
                    <tr ng-show="truckInfo">
                        <th style="width:72px;">Truck No:</th>
                        <td style="width: 100px;">@{{ truck_type + "-" + truck_no }}</td>
                        <th style="width: 96px;">Cargo Name:</th>
                        <td>@{{ goodsData[0].cargo_name }}</td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr ng-if="receiveWeightFlagWhenNoWeightBridge">
                        <th colspan="2">Manifest Gross Weight:</th>
                        <td>@{{gweight}}</td>
                        {{--<th class="text-warning" colspan="4">@{{ weightMessage }}</th>--}}
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6 col-md-offset-4">
                <table>
                    <tr>
                        <th>Location Type:&nbsp;&nbsp;</th>
                        <td>
                            <label class="radio-inline">
                                <input type="radio" ng-model="location_type" value="1" ng-checked="true" ng-init="location_type=1" ng-change="changeShedYardView(location_type)" ng-disabled="show"><b>Shed</b>&nbsp;&nbsp;&nbsp;
                            </label>
                            <label class="radio-inline">
                                <input type="radio" ng-model="location_type" value="0" ng-change="changeShedYardView(location_type)" ng-disabled="show"><b>Yard</b>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </table>
            </div>
            {{-- Shed Start --}}
            <div class="col-md-12" ng-show="shedView">
                <form name="shedForm" id="shedForm" novalidate>
                    <table>
                        <tr>
                            <th>Receive Weight:</th>
                            <td>
                                <input class="form-control" type="number" name="receive_weight" id="receive_weight" ng-model="receive_weight" ng-disabled="show" placeholder="Receive Weight">
                            </td>
                            <th style="padding-left: 15px;">Receive Package:</th>
                            <td>
                                <input class="form-control" type="text" name="receive_package" id="receive_package" ng-model="receive_package" ng-disabled="show" placeholder="Receive Package" required>
                                <span class="error" ng-show="shedForm.receive_package.$invalid && submitShedForm">Receive Package is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Parking Charge:</th>
                            <td>
                                <label class="radio-inline">
                                    <input type="radio" ng-model="holtage_charge_flag" value="1">Paid
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" ng-init="holtage_charge_flag=0" ng-model="holtage_charge_flag" ng-checked="true" value="0">Unpaid
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6"><hr style="border-width: 2px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <h4 class="text-center ok">Shed Receiving</h4>
                            </td>
                        </tr>
                        <tr>
                            <th>Labor Package:</th>
                            <td>
                                <input class="form-control" type="text" name="labor_package_shed" id="labor_package_shed" ng-model="labor_package_shed" ng-disabled="show" placeholder="Labor Package" ng-required="labor_unload_shed">
                                <span class="error" ng-show="shedForm.labor_package_shed.$invalid && submitShedForm">Labor Package is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Labor Weight:</th>
                            <td>
                                <input class="form-control" type="number" name="labor_unload_shed" id="labor_unload_shed" ng-model="labor_unload_shed" ng-disabled="show" placeholder="Labor Weight" ng-required="!(labor_unload_shed || equip_unload_shed)">
                                <span class="error" ng-show="shedForm.labor_unload_shed.$invalid && submitShedForm">Labor/Equ. Weight is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Equ. Package:</th>
                            <td>
                                <input class="form-control" type="text" name="equipment_package_shed"
                                id="equipment_package_shed" ng-model="equipment_package_shed" placeholder="Equ. Package" ng-disabled="show" ng-required="equip_unload_shed">
                                <span class="error" ng-show="shedForm.equipment_package_shed.$invalid && submitShedForm">Equ. Package is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Equ. Weight:</th>
                            <td>
                                <input class="form-control" type="number" name="equip_unload_shed" id="equip_unload_shed" ng-model="equip_unload_shed" ng-disabled="show" placeholder="Equ. Weight" ng-required="!(labor_unload_shed || equip_unload_shed)">
                                <span class="error" ng-show="shedForm.equip_unload_shed.$invalid && submitShedForm">Labor/Equ. Weight is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Equ. Name:</th>
                            <td>
                                <input class="form-control" type="text" name="equip_name_shed" id="equip_name_shed" ng-model="equip_name_shed" ng-disabled="show" placeholder="Equ. Name" ng-required="equip_unload_shed">
                                <span class="error" ng-show="shedForm.equip_name_shed.$invalid && submitShedForm">Equ. Name is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Receive Comment:</th>
                            <td>
                                <input class="form-control" type="text" name="recive_comment_shed" id="recive_comment_shed" ng-model="recive_comment_shed" ng-disabled="show" placeholder="Receive Comment">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Shifting:</th>
                            <td>
                                <label class="radio-inline">
                                    <input type="radio" ng-model="shifting_flag_shed" value="1">Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio"  ng-init="shifting_flag_shed=0" ng-model="shifting_flag_shed" value="0" ng-checked="true">No
                                </label>
                            </td>
                            <th  style="padding-left: 15px;">Shed:</th>
                            <td>
                                <select title="" class="form-control" ng-disabled="show" style="width: 190px;" name="posted_shed" ng-model="posted_shed" ng-change="YardNOForLevelNO()">
                                    @foreach($shed_details_array as $k=>$v)
                                        <option value="{{$v->id}}" @if($k == 0) ng-init="posted_shed = '{{$v->id}}'" @endif>{{$v->yard_shed_name}} </option>
                                    @endforeach
                                </select>
                                <level><b style="color: green">@{{ message_1 }} @{{ message_2 }} @{{ shed_count_no }}</b></level>
                            </td>
                            {{-- <th style="padding-left: 15px;"></th>
                            <td>
                                <button type="button" ng-click="shedYardWeightModalFunction()" data-toggle="modal" data-target="#shedYardWeightModal" class="btn btn-primary center-block" ng-disabled="ModalShow" data-backdrop="static" data-keyboard="false">Yard Weight
                                </button>
                            </td> --}}
                        </tr>
                        {{-- <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Goods Location:</th>
                            <td ng-show="selectedShed"> &nbsp; <p
                                        style="text-align: center; border: 2px solid green;box-shadow: 5px 8px 3px #888888; padding: 2px 5px; width: 100px; font-weight: bolder">@{{selectedShed}}</p>
                            </td>
                            <td>&nbsp;
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" ng-disabled="!graph_data" data-target="#graphView">
                                    Select Goods location
                                </button>
                            </td>
                        </tr> --}}
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="@{{ shed_yard_weight_id == null ? '6' : '4' }}">
                                <button type="button" class="btn btn-primary center-block" ng-click="saveShedData(shedForm)" ng-disabled="show" ng-if="button"><span class="fa fa-file"></span>Save
                                </button>
                            </td>
                            <td colspan="2">
                                <button type="button" class="btn btn-danger center-block" ng-click="clearShedData()" ng-disabled="show || shed_yard_weight_id == null" ng-show="shed_yard_weight_id != null"><span class="fa fa-eraser"></span>Clear
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center" ng-if="savingData">
                                <span style="color:green; text-align:center; font-size:15px">
                                    <img src="{{URL::asset('/img/dataLoader.gif')}}" width="250" height="15"/>
                                    <br/> Please wait!
                                </span>
                            </td>
                        </tr>
                    </table>
                </form>
                <br>
            </div>
            {{-- Shed End --}}
            {{-- Yard Start --}}
            <div class="col-md-12" ng-show="yardView">
                <form name="yardForm" id="yardForm" novalidate>
                    <table>
                        <tr>
                            <th>Receive Weight:</th>
                            <td>
                                <input class="form-control" type="number" name="receive_weight" id="receive_weight" ng-model="receive_weight" fraction="2" ng-disabled="show" placeholder="Receive Weight">
                                <span class="error" ng-show="receive_weight_required">Receive Weight is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Receive Package:</th>
                            <td>
                                <input class="form-control" type="text" name="receive_package" id="receive_package" ng-model="receive_package" ng-disabled="show" placeholder="Receive Package" required>
                                <span class="error" ng-show="yardForm.receive_package.$invalid && submitYardForm">Receive Package is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Parking Charge:</th>
                            <td>
                                <label class="radio-inline">
                                    <input type="radio" ng-model="holtage_charge_flag" value="1">Paid
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" ng-init="holtage_charge_flag=0" ng-model="holtage_charge_flag" ng-checked="true" value="0">Unpaid
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6"><hr style="border-width: 2px;"></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <h4 class="text-center ok">Yard Receiving</h4>
                            </td>
                        </tr>
                        <tr>
                            <th>Labor Package:</th>
                            <td>
                                <input class="form-control" type="text" name="labor_package_yard" id="labor_package_yard" ng-model="labor_package_yard" ng-disabled="show" placeholder="Labor Package" ng-required="labor_unload_yard">
                                <span class="error" ng-show="yardForm.labor_package_yard.$invalid && submitYardForm">Labor Package is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Labor Weight:</th>
                            <td>
                                <input class="form-control" type="number" name="labor_unload_yard" id="labor_unload_yard" ng-model="labor_unload_yard" ng-disabled="show" placeholder="Labor Weight" ng-required="!(labor_unload_yard || equip_unload_yard)">
                                <span class="error" ng-show="yardForm.labor_unload_yard.$invalid && submitYardForm">Labor/Equ. Weight is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Equ. Package:</th>
                            <td>
                                <input class="form-control" type="text" name="equipment_package_yard"
                                id="equipment_package_yard" ng-model="equipment_package_yard" placeholder="Equ. Package" ng-disabled="show" ng-required="equip_unload_yard">
                                <span class="error" ng-show="yardForm.equipment_package_yard.$invalid && submitYardForm">Equ. Package is Required</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Equ. Weight:</th>
                            <td>
                                <input class="form-control" type="number" name="equip_unload_yard" id="equip_unload_yard" ng-model="equip_unload_yard" ng-disabled="show" placeholder="Equ. Weight" ng-required="!(labor_unload_yard || equip_unload_yard)">
                                <span class="error" ng-show="yardForm.equip_unload_yard.$invalid && submitYardForm">Equ. Weight is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Equ. Name:</th>
                            <td>
                                <input class="form-control" type="text" name="equip_name_yard" id="equip_name_yard" ng-model="equip_name_yard" ng-disabled="show" placeholder="Equ. Name" ng-required="equip_unload_yard">
                                <span class="error" ng-show="yardForm.equip_name_yard.$invalid && submitYardForm">Equ. Name is Required</span>
                            </td>
                            <th style="padding-left: 15px;">Receive Comment:</th>
                            <td>
                                <input class="form-control" type="text" name="recive_comment_yard" id="recive_comment_yard" ng-model="recive_comment_yard" ng-disabled="show" placeholder="Receive Comment">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Shifting:</th>
                            <td>
                                <label class="radio-inline">
                                    <input type="radio" ng-model="shifting_flag_yard" value="1">Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" ng-init="shifting_flag_yard=0" ng-model="shifting_flag_yard" value="0" ng-checked="true">No
                                </label>
                            </td>
                            <th style="padding-left: 15px;">Yard:</th>
                            <td>
                                <select title="" class="form-control" ng-disabled="show" name="posted_yard" ng-model="posted_yard" ng-change="shedYardWeightCount()" required>
                                    @foreach($yard_details_array as $k=>$v)
                                            <option value="{{$v->id}}" @if($k == 0) ng-init="posted_yard = '{{$v->id}}'" @endif >{{$v->yard_shed_name}} </option>
                                    @endforeach
                                </select>
                                <level><b style="color: green">@{{ messagePartOne }} @{{ messagePartTwo }} @{{ yard_count_no }}</b></level>
                            </td>
                            <td style="padding-left: 15px;">
                                <button type="button" ng-show="showChasismodal" class="btn btn-success btn-sm" data-toggle="modal" data-target="#chassisModal"  ng-click="chassisDetailsFunction()" data-backdrop="static" data-keyboard="false">Chassis/Trucktor</button>
                            </td>
                        </tr>
                        {{-- <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <th>Goods Location:</th>
                            <td ng-show="selectedShed"> &nbsp; <p
                                        style="text-align: center; border: 2px solid green;box-shadow: 5px 8px 3px #888888; padding: 2px 5px; width: 100px; font-weight: bolder">@{{selectedShed}}</p>
                            </td>
                            <td>&nbsp;
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" ng-disabled="!graph_data" data-target="#graphView">
                                    Select Goods location
                                </button>
                            </td>
                        </tr> --}}
                        <tr>
                            <td colspan="6">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="@{{ shed_yard_weight_id == null ? '6' : '4' }}">
                                <button type="button" class="btn btn-primary center-block" ng-click="saveYardData(yardForm)" ng-disabled="show" ng-if="button"><span class="fa fa-file"></span>Save
                                </button>
                            </td>
                            <td colspan="2">
                                <button type="button" class="btn btn-danger center-block" ng-click="clearYardData()" ng-disabled="show || shed_yard_weight_id == null" ng-show="shed_yard_weight_id != null"><span class="fa fa-eraser"></span>Clear
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" class="text-center" ng-if="savingData">
                                <span style="color:green; text-align:center; font-size:15px">
                                    <img src="{{URL::asset('/img/dataLoader.gif')}}" width="250" height="15"/>
                                    <br/> Please wait!
                                </span>
                            </td>
                        </tr>
                    </table>
                </form>
                <br>
            </div>
            {{-- Yard End --}}
            <div class="col-md-12">
            <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
            <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="col-md-12 table-responsive" style="padding: 10px;">
            <div class="alert alert-danger" id="errorType" ng-hide="!errorType">@{{ errorType }}</div>
            <table class="table table-bordered" ng-show="table">
                <caption><h4 class="text-center ok">Manifest Details</h4></caption>
                <thead>
                <tr>
                    <th>S/L</th>
                    <th>Manifest No.</th>
                    <th>Truck No.</th>
                    <th>Receive Weight</th> {{--After Paased WeightBridge--}}
                    <th>Receive Package</th>
                    <th>Yard/Shed</th>
                    <th>Receive Comment</th>
                    <th>Receive Datetime</th>
                    <th>Labor Weight</th>
                    <th>Labor Package</th>
                    <th>Equipment Weight</th>
                    <th>Equipment Package</th>
                    <th>Equipment Name</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-style="{'background-color':(truck.id == selectedStyle?'#dbd3ff':'')}"
                    dir-paginate="truck in allTrucksData | orderBy:'truck.id' | itemsPerPage:50"
                    ng-click="getValues(truck)">

                    <td>@{{$index+1}}</td>
                    <td>@{{truck.manifest}}</td>
                    <td>@{{truck.truck_type +"-"+ truck.truck_no}}</td>
                    <td>
                        <span ng-if="truck.receive_weight == null || truck.receive_weight == 0">@{{truck.tweight_wbridge == 0 ? null : (truck.tweight_wbridge | number : 2) }}</span>
                        <span ng-if="truck.receive_weight != null || truck.receive_weight != 0">@{{truck.receive_weight == 0 ? null :  (truck.receive_weight | number : 2) }}</span>
                    </td>

                    <td>@{{truck.receive_package}}</td>
                    <td>
                        <span ng-repeat="sy in truck.shed_yard.split(',')">
                            <span>@{{sy}} <br></span>
                        </span>
                    </td>
                    <td>
                        <span ng-if="truck.recive_comment" ng-repeat="rcv_comment in truck.recive_comment.split(',')">
                            <span>@{{rcv_comment}} <br></span>
                        </span>
                    </td>
                    <td>
                        <span ng-repeat="rcv_datetime in truck.receive_datetime.split(',')">
                            <span>@{{rcv_datetime}} <br></span>
                        </span>
                    </td>
                    <td>@{{truck.total_labor_weight | number : 2 }}</td>
                    <td>@{{truck.total_labor_pkg}}</td>
                    <td>@{{truck.total_equip_weight | number : 2 }}</td>
                    <td>@{{truck.total_equip_pkg}}</td>
                    <td>@{{truck.all_equip_name}}</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th colspan="3">Total:</th>
                    <td>
                        <span ng-if="totalReceiveWeight > 0">@{{ totalReceiveWeight | number : 2  }}</span>
                    </td>
                    <td>
                        <span ng-if="totalReceivePkg > 0"> @{{ totalReceivePkg }}</span>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <span ng-if="totalUnloadLaborWeight > 0"> @{{ totalUnloadLaborWeight | number : 2  }}</span>
                    </td>
                    <td>
                        <span ng-if="totalUnloadLaborPkg > 0"> @{{ totalUnloadLaborPkg }}</span>
                    </td>
                    <td>
                        <span ng-if="totalUnloadEquipmetWeight > 0"> @{{ totalUnloadEquipmetWeight | number : 2  }}</span>
                    </td>
                    <td>
                       <span ng-if="totalUnloadEqupmentPkg > 0"> @{{ totalUnloadEqupmentPkg }}</span> 
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="13" class="text-center">
                        <dir-pagination-controls max-size="5"
                                                 direction-links="true"
                                                 boundary-links="true">
                        </dir-pagination-controls>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        {{-- Graphical View  Start--}}
        {{-- <div id="graphView" class="modal fade text-center" role="dialog">
            <div class="modal-dialog"  style="width: 80%">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">

                            <h3 class="text-muted"><u>Graphical View Of Shed @{{ posted_yard_shed }}</u></h3>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <table border="1" cellspacing="0">
                            <tbody ng-if="graph_data">
                            <tr>
                                <td></td>
                                <td class="head_style" ng-repeat="row in rows"><span ng-hide="row==0">@{{row}}</span>
                                </td>
                            </tr>
                            <tr ng-if="graph_data" ng-repeat="column in columns">
                                <td class="head_style">@{{column}}</td>

                                <td ng-repeat="row in rows" style="width: 100px; cursor: pointer"
                                    ng-class="getWeight(column,row)?'cell_background':'normal_cell'"
                                    ng-click="onCellselect(column,row,getWeight(column,row))">
                                    @{{column}}@{{row}} <br>
                                    @{{ getWeight(column,row) }}
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
            
        </div> --}}
        {{-- Graphical View  End--}}
        {{---------------------------------- Modal chassis Start----------------------------------}}
        <div id="chassisModal" class="modal fade text-center" {{--style="left:0px;"--}} role="dialog">
            <div class="modal-dialog modal-md">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">
                            <h5 class="text-muted"><u>Manifest No. <b>@{{manifest}}</b> Truck No. <b>@{{truck_no}}</b></u></h5>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-inline" name="chassisForm">

                            <table>
                                <tr style="/*background-color:  red*/">
                                    <th style="padding-left: 35px;">
                                        Type:
                                    </th>
                                    <td>
                                        <input type="text" style="width: 180px" class="form-control" id="chassis_type" placeholder="Enter chassis type" name="chassis_type" ng-model="chassis_type" required><br>
                                        <span class="error" ng-show="chassisForm.chassis_type.$invalid && submitChassisForm">Type is required</span>
                                    </td>
                                    <th style="padding-left: 25px;">
                                        No.:
                                    </th>
                                    <td>
                                        <input type="text" style="width: 180px" class="form-control" id="chassis_no" placeholder="Enter chassis no" name="chassis_no" ng-model="chassis_no" required><br>
                                        <span class="error" ng-show="chassisForm.chassis_no.$invalid && submitChassisForm">No is required</span>
                                    </td>

                                </tr>

                                <tr>
                                    <td colspan="6">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <button type="button"  ng-click="saveChasisData(chassisForm)" ng-if="!updateBtn" class="btn btn-primary">Save</button>
                                        <button type="button"  ng-click="saveChasisData(chassisForm)" ng-if="updateBtn" class="btn btn-primary">Update</button>
                                    </td>
                                </tr>

                            </table>

                        <div class="alert alert-success" id="savingChasisSuccess" ng-hide="!savingChasisSuccess">@{{ savingChasisSuccess }}</div>
                        <div class="alert alert-danger" id="savingChasisError" ng-hide="!savingChasisError">@{{ savingChasisError }}</div>

                        <div class="clearfix"></div>
                        <div class="col-md-12 table-responsive" style="padding: 10px;">
                            {{--<div class="alert alert-danger" id="errorType" ng-hide="!errorType">@{{ errorType }}</div>--}}
                            <table class="table table-bordered text-center" ng-show="table">
                                <caption><h4 class="text-center ok">Details</h4></caption>
                                <thead>
                                <tr>
                                    <th>S/L</th>
                                    <th>Type</th>
                                    <th>No.</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr dir-paginate="chassis in allChassisDetails | orderBy:'chassis.id':true | itemsPerPage:500">

                                    <td>@{{$index + 1}}</td>
                                    <td>@{{chassis.chassis_type}}</td>
                                    <td>@{{chassis.chassis_no}}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" ng-click="edit(chassis)">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteManifestConfirm"  ng-click="delete(chassis)">Delete</button>

                                    </td>

                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        {{--<dir-pagination-controls max-size="5"--}}
                                                                 {{--on-page-change="getPageCount(newPageNumber)"--}}
                                                                 {{--direction-links="true"--}}
                                                                 {{--boundary-links="true">--}}
                                        {{--</dir-pagination-controls>--}}
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>

                    </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
             {{---------------------------------- Modal chassis END----------------------------------}}
            
        </div>


        {{-- ------------------------Delete Model----------------------------}}
        <div  class="modal fade " id="deleteManifestConfirm" {{--style="left:0px;"--}} role="dialog" >
            <div class="modal-dialog">

                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>

                        {{--<h5 class="text-center">Manifest No:<b>@{{ d.ManifestNo }} </b></h5>--}}
                    </div>
                    <div class="modal-body">

                        <h4 class="modal-title text-center">Are you sure to delete Chassis Type: <b>@{{ chassis_type }}</b> And Chassis No. <b>@{{ chassis_no }}</b>?</h4>

                        <a href="" class="btn btn-primary center-block pull-right" ng-click="deleteChassis()">Yes</a>

                        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">No</button>

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
    </div>
    <script type="text/javascript">
        $('#receive_datetime').datetimepicker({
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'
        });
    </script>
@endsection