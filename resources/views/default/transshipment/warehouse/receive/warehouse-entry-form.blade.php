@extends('layouts.master')
@section('title', 'WareHouse Entry Form')
@section('style')
    {!!Html :: style('css/jquery-ui-timepicker-addon.css')!!}
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
            left: 0;
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
        var role_name = {!! json_encode(Auth::user()->role->name) !!};
    </script>
    {!!Html :: script('js/customizedAngular/transshipment/warehouse/warehouse-receive.js')!!}
    {!!Html :: script('js/lodash.js')!!}




@endsection
@section('content')
    <div class="col-md-12  ng-cloak" ng-app="WareHouseEntryApp" ng-controller="WareHouseEntryController">

        <div class="col-md-12">
            <div class="col-md-5 col-md-offset-0">
                <form class="form-inline" ng-submit="truckNoOrManifestOrYardSearch()">
                    <div class="form-group">
                        <label for="">Search By:</label>
                        <select title="" ng-change="select()" class="form-control"
                                ng-init="selection.singleSelect='manifestNo'" name="singleSelect"
                                ng-model="selection.singleSelect">
                            <option value="manifestNo">Manifest No</option>
                            <option value="truckNo">Truck Type-No</option>
                            {{-- <option value="yardNo">Yard No</option> --}}
                        </select>
                        <input type="text" class="form-control" name="searchKey" ng-model="searchKey"
                               {{--ng-disabled="serachField"--}} id="searchKey" placeholder="@{{ placeHolder }}"
                               ng-keydown="keyBoard($event)" style="width: 150px;">
                    </div>
                </form>
            </div>
            <div class="col-md-5">
                {{-- <a href="{{ url('todaysWareHouseEntryPDF') }}" target="_blank">
                     <button type="button" class="btn btn-primary"><span class="fa fa-search"></span>Today's Receive
                     </button>
                 </a>--}}

                <form action="{{ route('transshipment-warehouse-receive-date-wise-entry-report') }}" class="form-inline" target="_blank"
                      method="POST">
                    {{ csrf_field() }}
                    <select  ng-init="vehile_type_flage_pdf = '1'" style="width: 150px"  name="vehile_type_flage_pdf"  ng-model="vehile_type_flage_pdf"  class="form-control input-sm" >
                        <optgroup label="(1). Truck">
                            <option    value="1" selected >Goods</option>
                            {{--<option   value="2">Chassis(Chassis on Truck)</option>--}}
                            {{--<option   value="3">Trucktor(Trucktor on Truck)</option>--}}
                        </optgroup>
                        {{--<optgroup label="(2). Self">--}}
                            {{--<option  value="4">Chassis(Self)</option>--}}
                            {{--<option  value="5">Trucktor(Self)</option>--}}
                            {{--<option   value="6">Bus</option>--}}
                            {{--<option   value="7">Three Wheller</option>--}}
                            {{--<option   value="8">Rickshaw</option>--}}
                        {{--</optgroup>--}}
                    </select>
                    <div class="input-group"  >
                        <input type="text" class="form-control datePicker" style="width: 150px"
                               name="date" placeholder="Select Receive Date">
                        <div class="input-group-btn">
                            {{--<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>--}}
                            <button type="submit" class="btn btn-primary">
                                {{-- <span class="fa fa-calendar-o"></span>--}} Get Report
                            </button>
                        </div>
                    </div>
                </form>


            </div>
            {{--<div class="col-md-2">--}}
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

                <h4 class="text-center ok">Transhipment Receiving

                    @foreach($yard_details_array  as $k=>$v)
                        (<span style="color: sandybrown"> {{$v->yard_shed_name}} </span>)
                    @endforeach
                </h4>


            <div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{ savingSuccess }}</div>
            <div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{ savingError }}</div>

            <div class="col-md-12">
                <table style="text-align: left;">
                    <tr ng-show="truckInfo">
                        <th style="width:72px;">Truck No:</th>
                        <td style="width: 100px;">@{{ truck_type + "-" + truck_no }}</td>
                        <th style="width: 96px;">Cargo Name:</th>
                        <td>@{{ goodsData[0].cargo_name }}</td>
                    </tr>
                    <tr ng-show="manifestInfo">
                        <th style="width:120px;">Importer Name:</th>
                        <td style="width: 180px;">@{{ allTrucksData[0].NAME }}</td>
                        <th style="width: 120px;">Exporter Name:</th>
                        <td style="width: 150px;">@{{ allTrucksData[0].exporter_name_addr }}</td>
                        <th style="width: 96px;">Goods Name:</th>
                        <td>@{{ allTrucksData[0].cargo_name }}</td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr ng-if="receiveWeightFlagWhenNoWeightBridge">
                        <th colspan="2">Manifest Gross Weight:</th>
                        <td>@{{gweight}}</td>
                        <th class="text-warning" colspan="4">Truck is not assigned as weightbridge.</th>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                    <form name="wareHouseform" id="wareHouseform" novalidate>
                        <table>
                            <tr>
                                <th>Receive Weight:</th>
                                <td>
                                    <input class="form-control" type="number" name="receive_weight" id="receive_weight"
                                           ng-model="receive_weight" ng-disabled="show || showonly">
                                    <span class="error"
                                          ng-show="receive_weight_required">Receive Weight is Required</span>
                                </td>
                                <th style="padding-left: 15px;">Receive Package <span class="mandatory">*</span>:</th>
                                <td>
                                    <input class="form-control" type="text" name="receive_package" id="receive_package"
                                           ng-model="receive_package" ng-disabled="show">
                                    <span class="error"
                                          ng-show="receive_package_required">Receive Package is Required</span>
                                </td>
                                <th style="padding-left: 15px;">Receive Comment:</th>
                                <td>
                                    <input class="form-control" type="text" name="recive_comment" id="recive_comment"
                                           ng-model="recive_comment" ng-disabled="show">
                                    <span class="error"
                                          ng-show="recive_comment_required">Receive Comment is Required</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>
                            <tr>
                                <th>Receive Datetime <span class="mandatory">*</span>:</th>                      {{-- ###################  work here --}}
                                <td>
                                    <input class="form-control datePicker" type="text" name="receive_datetime"
                                           id="receive_datetime" ng-model="receive_datetime" ng-disabled="ReceiveDatetimeDisable">
                                 {{--   <span class="error"
                                          ng-show="receive_datetime_required">Receive Datetime is Required</span>--}}
                                </td>
                                <th style="padding-left: 15px;">Labor Weight:</th>
                                <td>
                                    <input class="form-control" type="number" name="labor_unload" id="labor_unload"
                                           ng-model="labor_unload"
                                           ng-disabled="show" {{-- ng-change="getEquipmentUnload()" --}}>
                                    <span class="error" ng-show="labor_unload_required">Labor Weight is Required</span>
                                </td>
                                <th style="padding-left: 15px;">Labor Package:</th>
                                <td>
                                    <input class="form-control" type="text" name="labor_package" id="labor_package"
                                           ng-model="labor_package" ng-disabled="show">
                                    <span class="error"
                                          ng-show="labor_package_required">Labor Package is Required</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>
                            <tr>
                                <th>Equipment Weight:</th>
                                <td>
                                    <input class="form-control" type="number" name="equip_unload" id="equip_unload"
                                           ng-model="equip_unload"
                                           ng-disabled="show || whenLaborUnloadTyping || disableWhenTransshipment">
                                    <span class="error"
                                          ng-show="equip_unload_required">Equipment Weight is Required</span>
                                </td>
                                <th style="padding-left: 15px;">Equipment Name:</th>
                                <td>
                                    <input class="form-control" type="text" name="equip_name" id="equip_name"
                                           ng-model="equip_name" ng-disabled="show || disableWhenTransshipment">
                                    <span class="error" ng-show="equip_name_required">Equipment Name is Required</span>
                                </td>
                                <th style="padding-left: 15px;">Equipment Package:</th>
                                <td>
                                    <input class="form-control" type="text" name="equipment_package"
                                           id="equipment_package"
                                           ng-model="equipment_package" ng-disabled="show || disableWhenTransshipment">
                                    <span class="error"
                                          ng-show="equipment_package_required">Equipment Package is Required</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>

                            <tr>
                                <th style="padding-left: 15px;">Parking Charge:</th>
                                <td>
                                    <label class="radio-inline">
                                        <input type="radio" ng-init="holtage_charge_flag=1"
                                               ng-model="holtage_charge_flag" ng-checked="true" value="1">Paid
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="holtage_charge_flag" value="0">Unpaid
                                    </label>
                                </td>

                                <th style="padding-left: 15px;">Shifting:</th>
                                <td>
                                    <label class="radio-inline">
                                        <input type="radio" ng-init="shifting_flag=1" ng-model="shifting_flag"
                                               value="1">Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="shifting_flag" value="0" ng-checked="true">No
                                    </label>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>

                            <tr>
                                {{--<th>Yard <span class="mandatory">*</span> :</th>--}}
                                {{--<td>--}}
                                    {{--<select title="" class="form-control" --}}{{--ng-disabled="show || showonly"--}}{{-- style="width: 190px;"--}}

                                    {{--@foreach(Auth::user()->shedYards as $k=>$v)--}}
                                         {{--ng-init="t_posted_yard_shed = '{{$v->id}}'"--}}
                                                 {{--break;--}}
                                    {{--@endforeach--}}
                                            {{--name="t_posted_yard_shed" ng-model="t_posted_yard_shed" --}}{{--required--}}{{--  ng-change="YardNOForLevelNO()">--}}
                                        {{--<option value=""  selected="selected" >Choose Yard/Shed </option>--}}
                                        {{--@foreach($yard_details_array as $k=>$v)--}}
                                            {{--<option  value="{{$v->id}}" @if($k == 0) ng-init="t_posted_yard_shed = '{{$v->id}}'" @endif>{{$v->yard_shed_name}} </option>--}}
                                        {{--@endforeach--}}
                                    {{--</select>--}}

                                    {{--<span class="error" ng-show="Yard_shed_required">Yard is Required</span>--}}

                                    {{--<level><b style="color: green">@{{ message_1 }} @{{ message_2 }} @{{ yard_count_no }}</b></level>--}}
                                {{--</td>--}}
                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>

                            <tr>
                                <td colspan="6" class="text-center">
                                    <button id="saveBtn" type="button" class="btn btn-primary center-block" ng-click="save()"
                                            ng-disabled="show"
                                            ng-if="button"><span class="fa fa-file"></span> Save
                                    </button>
                                <td>
                                <td>


                                    {{-- <form action="{{ route('DeliveryRequest') }}" target="_blank" method="POST">
                                         {{ csrf_field() }}
                                         <input title="" type="text"  class="form-control" name="mani_no" ng-model="searchKey">
                                         <button type="submit" class="btn btn-primary center-block">Delivery</button>
                                     </form>--}}
                                    <a class="btn btn-success" href="{{url('transshipment/warehouse/delivery-request')}}/@{{searchKey }}">
                                        <i class="fa fa-road"></i>Delivery
                                    </a>

                                </td>
                            </tr>
                        </table>
                    </form>

                <br>
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
                    <th>Receive Comment</th>
                    <th>Receive Datetime</th>
                    <th>Labor Weight</th>
                    <th>Labor Package</th>
                    <th>Equipment Weight</th>
                    <th>Equipment Name</th>
                    <th>Equipment Package</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-style="{'background-color':(truck.id == selectedStyle?'#dbd3ff':'')}"
                    dir-paginate="truck in allTrucksData | orderBy:'truck.id' | itemsPerPage:5"
                    ng-click="update(truck)">

                    <td>@{{$index+1}}</td>
                    <td>@{{truck.manifest}}</td>
                    <td>@{{truck.truck_type +"-"+ truck.truck_no}}</td>
                    <td ng-if="truck.receive_weight == null">@{{truck.tweight_wbridge | number : 2 }}</td>
                    <td ng-if="truck.receive_weight != null">@{{truck.receive_weight | number : 2 }}</td>
                    <td>@{{truck.receive_package}}</td>
                    <td>@{{truck.unload_comment}}</td>
                    <td>@{{truck.unload_receive_datetime}}</td>
                    <td>@{{truck.unload_labor_weight | number : 2 }}</td>
                    <td>@{{truck.unload_labor_package}}</td>
                    <td>@{{truck.unload_equip_weight | number : 2 }}</td>
                    <td>@{{truck.unload_equip_name}}</td>
                    <td>@{{truck.unload_equipment_package}}</td>
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
                    <td>
                        <span ng-if="totalUnloadLaborWeight > 0"> @{{ totalUnloadLaborWeight | number : 2  }}</span>
                    </td>
                    <td>
                        <span ng-if="totalUnloadLaborPkg > 0"> @{{ totalUnloadLaborPkg }}</span>
                    </td>
                    <td>
                        <span ng-if="totalUnloadEquipmetWeight > 0"> @{{ totalUnloadEquipmetWeight | number : 2  }}</span>
                    </td>
                    <td></td>
                    <td>
                       <span ng-if="totalUnloadEqupmentPkg > 0"> @{{ totalUnloadEqupmentPkg }}</span> 
                    </td>
                </tr>
                <tr>
                    <td colspan="12" class="text-center">
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
    <script type="text/javascript">
        $('#receive_datetime').datetimepicker({
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'
        });
    </script>
@endsection