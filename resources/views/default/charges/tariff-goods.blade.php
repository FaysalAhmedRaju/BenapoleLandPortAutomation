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

    {!!Html::script('js/customizedAngular/charges/tariff-goods.js')!!}
    {!!Html :: script('js/bootbox.min.js')!!}
@endsection
@section('content')
    <div class="col-md-12 ng-cloak" ng-app="tariffApp" ng-controller="tariffController">

        <div class="col-md-12" id="head">
            <div class="col-md-12" style="background-color: #f8f9f9; border-radius: 20px;">
                <h4 class="text-center ok">Tariff Goods</h4>
                <br>
                <div class="alert alert-success" id="SuccessBonus" ng-hide="!SuccessBonus">@{{ SuccessBonus }}</div>
                <div class="alert alert-danger" id="bonusdError" ng-hide="!bonusdError">@{{ bonusdError }}</div>
                <div class="alert alert-success" id="SuccessIncreaseUpdate" ng-hide="!SuccessIncreaseUpdate">@{{ SuccessIncreaseUpdate }}</div>
                <div class="alert alert-danger" id="ErrorIncreaseUpdate" ng-hide="!ErrorIncreaseUpdate">@{{ ErrorIncreaseUpdate }}</div>
                <div class="col-md-12 col-md-offset-1">
                    <form  name="TariffGoodsForm" id="TariffGoodsForm" novalidate>
                        <table>
                            <tr >



                                <th>Year<span class="mandatory">*</span>:</th>
                                <td>
                                    <select class="form-control" required id="goods_year" name="goods_year" ng-model="goods_year"  ng-options="years.text as years.text  for years in years" >
                                        <option value="">Select Year</option>
                                    </select>
                                    <span class="error" ng-show="submitted && !goods_year">Year is required</span>
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
                                <td colspan="4">&nbsp;
                                </td>
                            </tr>
                            <tr>

                                <th >Goods<span class="mandatory">*</span> :</th>
                                <td>
                                    <input type="text" ng-model="goods_name" name="goods_name" id="goods_name"
                                           class="form-control" placeholder="Type Goods Name">
                                    <span class="error" ng-show="submitted && !goods_name">Goods is required</span>

                                </td>


                                <th style="padding-left: 15px;">Basis Charge<span class="mandatory">*</span> :</th>
                                <td>
                                    <input type="text" ng-model="basis_charge" name="basis_charge" id="basis_charge"
                                           class="form-control" placeholder="Type Basis Charge">
                                    <span class="error" ng-show="submitted && !basis_charge">Basis Charge is required</span>
                                </td>


                                <th style="padding-left: 15px;">Description{{--<span class="mandatory">*</span>--}} :</th>
                                <td>
                                    <input type="text" ng-model="description" name="description" id="description"
                                           class="form-control" placeholder="Type Description Charge">

                                </td>



                            </tr>

                            <tr>
                                <td colspan="4">&nbsp;
                                </td>
                            </tr>
                            <tr>
                                <th>Free Time:</th>
                                <td style="padding-left: 5px;">
                                    <label class="radio-inline">
                                        <input type="radio" ng-model="free_time_flag" value="1">{{--Yes--}}
                                        <i style="color: lightgreen;"  class="fa fa-check fa-lg" aria-hidden="true"></i>
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"  ng-init="free_time_flag=0" ng-model="free_time_flag" value="0" ng-checked="true">{{--No--}}
                                        <i style="color: lightblue;"  class="fa fa-close fa-lg" aria-hidden="true"></i>
                                    </label>
                                </td>

                            </tr>



                            <tr>
                                <td>
                                <td colspan="0"></td>
                                <td colspan="1" class="text-center">
                                    <br>
                                    <button type="button" ng-click="Save(TariffGoodsForm)" ng-if="SaveBonusBtn" class="btn btn-primary center-block"><span class="fa fa-file"></span> Save</button>

                                    <button type="button" ng-click="updateBonus(TariffGoodsForm)" ng-if="SaveUpdateBtn" class="btn btn-success center-block"><span class="fa fa-download"></span> Update</button>

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
                    <caption><h4 class="text-center ok">Tariff Goods List</h4>
                        <div class="alert alert-danger" id="noGoodsError" ng-hide="!noGoodsError">@{{ noGoodsError }}</div>
                        <div class="col-md-6 col-sm-6 col-xs-3 form-inline">
                            <div class="form-group">
                                <label for="user_type_search">
                                    Year:
                                </label>
                                <select class="form-control" required id="tariff_year_search" name="tariff_year_search" ng-model="tariff_year_search"  ng-options="years.text as years.text  for years in years"
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
                        </div>

                    </caption>
                    <thead>
                    <tr>
                        <th>S/L</th>
                        <th style="width: 100px">Goods Name</th>
                        {{--<th>Year</th>--}}
                        {{--<th>Port</th>--}}
                        <th>Basis Charge</th>
                        <th>Description</th>
                        <th>Free Time</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr dir-paginate="value in allTariffData | filter:searchText | orderBy:'value.id' | itemsPerPage:10" pagination-id="head">
                        <td>@{{ $index + serial }}</td>
                        <td>@{{value.particulars}}</td>
                        {{--<td>@{{value.year}}</td>--}}
                        {{--<td>@{{value.port_name}}</td>--}}
                        <td>@{{value.basis_of_charges}}</td>
                        <td>@{{value.description}}</td>
                        <td>{{--@{{value.flag | freeTimeFlagValue}}--}}
                        <i style="color: lightgreen;" ng-if="value.flag == 1" class="fa fa-check fa-lg" aria-hidden="true"></i>
                        <i style="color: lightblue;" ng-if="value.flag == 0" class="fa fa-close fa-lg" aria-hidden="true"></i>
                        </td>
                        <td>
                            <button type="button" class="btn btn-success btn-sm" ng-click="editBonous(value)">
                                Edit
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" ng-click="deleteBonus(value)">
                                Delete
                            </button>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6" class="text-center">
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