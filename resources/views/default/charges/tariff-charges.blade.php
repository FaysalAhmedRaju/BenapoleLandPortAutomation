@extends('layouts.master')
@section('title','Tarif Schedule')
@section('style')
    <style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
@endsection
@section('script')
    <script type="text/javascript">
        var port_id_current = {!! json_encode( Session::get('PORT_ID')) !!};
        var portList = {!! json_encode( $portList) !!};

    </script>
    {!!Html::script('js/customizedAngular/charges/tariff-charges.js')!!}
    {!!Html :: script('js/bootbox.min.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="tariffApp" ng-controller="tariffController">

        <div class="col-md-12" id="head">
            <div class="col-md-12" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">Tariff Schedule</h4>
                <br>
                <div class="alert alert-success" id="SuccessBonus" ng-hide="!SuccessBonus">@{{ SuccessBonus }}</div>
                <div class="alert alert-danger" id="bonusdError" ng-hide="!bonusdError">@{{ bonusdError }}</div>
                <div class="alert alert-success" id="SuccessIncreaseUpdate" ng-hide="!SuccessIncreaseUpdate">@{{ SuccessIncreaseUpdate }}</div>
                <div class="alert alert-danger" id="ErrorIncreaseUpdate" ng-hide="!ErrorIncreaseUpdate">@{{ ErrorIncreaseUpdate }}</div>
                <div class="col-md-12 col-md-offset-1">
                    <form  name="TariffForm" id="TariffForm" novalidate>
                        <table>
                            <tr >



                                <th>Year{{--<span class="mandatory">*</span>--}}:</th>
                                <td>
                                    <select class="form-control" required id="tariff_year" name="tariff_year" ng-model="tariff_year"  ng-options="charge_year.tariff_goods_year as charge_year.tariff_goods_year  for charge_year in yearData"  ng-change="chargeYearGoods(values)">
                                        <option value="">Select Year</option>
                                    </select>
                                    {{--<span class="error" ng-show="TariffForm.tariff_year.$invalid && submittedTariffForm">Year is required</span>--}}
                                </td>
                                @if(Auth::user()->role->id == 11 || Auth::user()->role->id == 2)
                                <th style="padding-left: 15px;">Port{{--<span class="mandatory">*</span>--}}:</th>
                                <td>

                                    <select title="No Port Selected" style="width: 190px;" {{--ng-init="port_id = '1'"--}}  name="port_id" class="form-control" ng-model="port_id" ng-change="chargeYearGoods(values)">
                                        <option value="">Select Port</option>
                                        @foreach($portList as $i=>$d)
                                            <option value="{{$i}}">{{$d}}</option>
                                        @endforeach
                                    </select>
                                    <span class="error" ng-show="TariffForm.port_id.$invalid && submittedTariffForm">Port is required</span>
                                </td>
                                @endif


                            </tr>
                            <tr>
                                <td colspan="4">&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th>Goods<span class="mandatory">*</span>:</th>
                                <td style="width: 175px;">
                                    <select  class="form-control" {{--style="width: 190px;"--}} required  name ="tariff_goods" ng-model="tariff_goods" id="tariff_goods" ng-options="goods.id as goods.id+'-'+goods.particulars for goods in getallGoodsData " ng-disabled="slabDisableUpdate">
                                        <option value="" selected="selected">Select Goods Name</option>
                                    </select>
                                    <span class="error" ng-show="TariffForm.tariff_goods.$invalid && submittedTariffForm">Select Goods Name</span>
                                </td>

                                <th style="padding-left: 15px;">Slab<span class="mandatory">*</span>:</th>
                                <td style="width: 175px;">

                                    <select class="form-control" {{--style="width: 190px;"--}} {{--ng-init="slab_position = '1st'" --}}name="slab_position" ng-model="slab_position" id="slab_position" ng-disabled="slabDisableUpdate">
                                        <option value="">Select Slab</option>
                                        <option value="1st">1st</option>
                                        <option value="2nd">2nd</option>
                                        <option value="3rd">3rd</option>
                                        <option value="4th">4th</option>
                                        <option value="5th">5th</option>
                                        <option value="6th">6th</option>
                                    </select>
                                    <span class="error" ng-show="TariffForm.slab_position.$invalid && submittedTariffForm">Select Slab</span>
                                </td>


                                <th style="padding-left: 15px;">Start Day<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="number" {{--style="width: 190px;"--}} required ng-model="start_day" name="start_day" id="start_day" class="form-control input-sm" placeholder="Start Day">
                                    <span class="error" ng-show="TariffForm.start_day.$invalid && submittedTariffForm">Start Day is required</span>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="4">&nbsp;
                                </td>
                            </tr>

                            <tr>
                                <th>End Day<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="checkbox" name="limitless_day" value="limitless_day" ng-model="limitless_day" ng-click="limitlessDayCheckBox(limitless_day)">OnWord
                                    <input type="number"  {{--style="width: 190px;"--}} required ng-model="end_day" name="end_day" id="end_day" class="form-control input-sm" placeholder="End Day" ng-disabled="ifLimitlessDayDisable">
                                    <span class="error" ng-show="TariffForm.end_day.$invalid && submittedTariffForm">End Day is required</span>
                                </td>
                                <th style="padding-left: 15px;">Shed Charge<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="number" {{--style="width: 190px;"--}} required ng-model="shed_charge" name="shed_charge" id="shed_charge" class="form-control input-sm" placeholder="Shed Charge">
                                    <span class="error" ng-show="TariffForm.shed_charge.$invalid && submittedTariffForm">Shed Charge is required</span>
                                </td>

                                <th style="padding-left: 15px;">Yard Charge<span class="mandatory">*</span>:</th>
                                <td>
                                    <input type="number" {{--style="width: 190px;"--}} required ng-model="yard_charge" name="yard_charge" id="yard_charge" class="form-control input-sm" placeholder="Yard Charge">
                                    <span class="error" ng-show="TariffForm.yard_charge.$invalid && submittedTariffForm">Yard Charge is required</span>
                                </td>

                            </tr>

                            <tr>
                                <td>
                                <td colspan="0"></td>
                                <td colspan="3" class="text-center">
                                    <br>
                                    <button type="button" ng-click="SaveBonus()" ng-if="SaveBonusBtn" class="btn btn-primary center-block"><span class="fa fa-file"></span> Save</button>

                                    <button type="button" ng-click="updateBonus(value)" ng-if="SaveUpdateBtn" class="btn btn-success center-block"><span class="fa fa-download"></span> Update</button>

                                </td>
                                <td colspan="2"></td>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <div class="col-md-12">
                <table class="table table-bordered" {{--ng-show="BonusTable"--}}>
                    <caption><h4 class="text-center ok">Tariff Goods And Charges List</h4>
                        <div class="alert alert-danger" id="chargeError" ng-hide="!chargeError">@{{ chargeError }}</div>
                        <div class="col-md-6 col-sm-6 col-xs-3 form-inline">
                            <div class="form-group">
                                <label for="user_type_search">
                                    Year:
                                </label>
                                <select class="form-control" required id="tariff_year_search" name="tariff_year_search" ng-model="tariff_year_search"  ng-options="charge_year.tariff_goods_year as charge_year.tariff_goods_year  for charge_year in yearData"
                                        ng-change="getTariffGoodsChargesData(searchValue)">
                                    <option value="">Select Year</option>
                                </select>



                            </div>
                            <div class="form-group">
                                @if(Auth::user()->role->id == 11 || Auth::user()->role->id == 2)
                                    <label>
                                        Port:
                                    </label>

                                        <select title="No Port Selected" style="width: 190px;" {{--ng-init="port_id = '1'"--}}  name="port_id_search" class="form-control"
                                                ng-model="port_id_search" ng-change="getTariffGoodsChargesData(searchValue)">
                                            <option value="">Select Port</option>
                                            @foreach($portList as $i=>$d)
                                                <option value="{{$i}}">{{$d}}</option>
                                            @endforeach
                                        </select>

                                @endif
                            </div>
                            {{--<div class="form-group">--}}
                                {{--<label for="searchText">Search:</label>--}}
                                {{--<input class="form-control" ng-model="searchText">--}}
                            {{--</div>--}}
                        </div>

                    </caption>
                    <thead>
                    <tr>
                        <th>S/L</th>
                        <th style="width: 100px">Goods Name</th>
                        <th>Slab</th>
                        <th>Duration</th>
                        {{--<th>End Day</th>--}}
                        <th>Shed Charge</th>
                        <th>Yard Charge</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="value in allTariffData | filter:searchText | orderBy:'value.id' | itemsPerPage:10" pagination-id="head">
                        <td>@{{ $index + serial }}</td>
                        <td>@{{value.tariff_goods_name}}</td>
                        <td>@{{value.slab}}</td>

                        <td>@{{value.from}} - @{{value.to | numberToText}}</td>
                        {{--<td>@{{value.to | numberToText}}</td>--}}
                        <td>@{{value.shed_charge}}</td>
                        <td>@{{value.yard_charge}}</td>
                        <td>@{{value.tariff_goods_year}}</td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" ng-click="editBonous(value)">
                                Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" ng-click="deleteBonus(value)"  >
                                {{--data-target="#deleteDegnationOnlyModal"  data-toggle="modal"--}}
                                Delete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="9" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                     on-page-change="getPageCount(newPageNumber)"
                                                     direction-links="true"
                                                     boundary-links="true"
                                                     pagination-id="head">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>


    </div>
    <script>
        $(function() {
            $('#tariff_year').datepicker( {
                changeMonth: false,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'yy',
                onClose: function(dateText, inst) {
                    function isDonePressed(){
                        return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                    }
                    if (isDonePressed()){
//                        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                        $(this).datepicker('setDate', new Date(year,1)).trigger('input');
                        //console.log(a);
                    }
                }
            });
        });
    </script>
@endsection