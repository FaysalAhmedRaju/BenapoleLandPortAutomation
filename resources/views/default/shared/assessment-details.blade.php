<style type="text/css">
    #one td {
        border: 1px solid black;
    }

    #one th {
        border: 1px solid black;
    }

    .tbl-td-center tr td {
        text-align: center !important;

    }

    td.td-width {
        width: 5px !important;
    }

    .td-color-blue {
        color: #0000cc;
    }

    .verticle-middle {
        vertical-align: middle !important;

    }

    /*body {
         font-size: 12px;
         font-family: Tahoma, Helvetica, sans-serif;
     }

    table {
        border: 1px solid #555;
        border-width: 0 0 1px 1px;
    }
    table td {
        border: 1px solid #555;
        border-width: 1px 1px 0 0;
    }
*/

    span.slab-period {
        /*margin-left: 8px;*/
        display: block;
        /*color: #b1b1b1;*/
        font-size: 12px;
        font-style: italic;
        font-weight: bold;

    }

</style>
<div id="aa" class="col-md-12 text-center" ng-animate="'animate'">
    {{--START PDF Div--}}

    <div class="col-md-12 ForPdf" ng-show="ForPdf">

        <img src="img/Logo_BSBK.gif" style="float: left; width: 120px">
        <p class="center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
            <span style="font-size: 19px;">Assessment Report</span> <br>
        </p>
        <h5 style="text-align: right;padding-right: 35px;">
            Date: <?php
            $timezone = 6; //(GMT -6:00) EST (Dhaka)
            echo gmdate("F j, Y, g:i a", time() + 3600 * ($timezone + date("I")));
            ?>
        </h5>
    </div>


    <div class="col-md-12" style="padding: 0">

        <table class="text-center-td-th" id="assessment" style="box-shadow: 0px 0px 5px 1px darkgrey; width: 100%">


            <tr>
                <td>
                    <b> Manifest No & Date:</b>
                </td>

                <td>
                    <u> <span>
                         {{$manifestNo}} : {{date('d-m-Y',strtotime($ManifestDate))}}
                        </span></u>
                </td>


                <td>
                    <b>Consignee:</b>
                </td>

                <td>
                    <span
                            style="text-transform: capitalize">{{$importer}}</span>
                </td>
            </tr>

            <tr>
                <td colspan="4"> &nbsp;</td>
            </tr>

            <tr>
                <td>
                    <b>Bill Of Entry No & Date:</b>
                </td>

                <td><span></span>
                    <u> <span>
                            {{$bill_entry_no}} : {{date('d-m-Y',strtotime($bill_entry_date))}}
                        </span></u>
                </td>


                <td class="2">
                    <b> Consignor:</b>
                </td>

                <td class="2">
                    <span>  {{$exporter}}</span>
                </td>
            </tr>

            <tr>
                <td colspan="4"> &nbsp;</td>
            </tr>

            <tr>
                <td rowspan="2">
                    <b>Custom's Release Order No & Date:</b>
                </td>

                <td rowspan="2"><span>C-</span>
                    <u> <span>{{$custom_realise_order_No}}
                            : {{date('d-m-Y',strtotime($custom_realise_order_date))}} </span>
                    </u>
                </td>


                <td>
                    <b> C & F Agent:</b>
                </td>

                <td>
                    <span> {{ isset($cnf_name) ? $cnf_name : 'No CnF' }}</span>
                </td>
            </tr>

            <tr>
                <td colspan="4"> &nbsp;</td>
            </tr>


        </table>

        <br> <br>
    </div>


    <div class="col-md-12" style=" text-align: left;padding: 0">

        <p style="padding: 3px ;float: left; box-shadow: 1px 0px 5px 2px #969696; width: 250px">
            <b>PARTICULARS OF CHARGES DUE</b>
        </p>

        <p style="padding: 3px ;float: left;margin: 0 30px; box-shadow: 1px 0px 5px 2px #969696; width: 450px">
            <b>Shed / Yard No.</b> {{ $posted_yard_shed }}

        </p>
        {{--<b><p style="padding: 3px ;box-shadow: 1px 0px 5px 2px #969696; width: 250px">PARTICULARS OF CHARGES DUE</p></b>--}}

    </div>


    <div class="col-md-12 td-left" id="assessment" style="padding: 0;">

        {{--==========================Warehouse Rent====START=========================--}}

        <div class="col-md-12" style="padding: 0;">

            <table border="1" class="table table-bordered td-bordered"
                   style="width: 100%;box-shadow: 0 0 5px 1px darkgrey">
                <tr>

                    <td></td>
                    <th colspan="2" style="width: 37%"></th>
                    <td></td>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
                <tr id="one">
                    <td><b>1.</b></td>
                    <th> Warehouse Rent</th>
                    <td></td>
                    <th colspan="5">
                        Assessment:

                    </th>
                    {{--<th>Basis of Charge</th>
                    <th>Day</th>
                    <th>Rate</th>
                    <th>Amount</th>--}}
                </tr>

                <tr id="one">
                    <td></td>
                    <td> Description of goods</td>
                    <td>
                        <span ng-show="totalItems">{{--Items= <b>@{{ totalItems }}</b> --}}
                        </span> {{$description_of_goods}}
                    </td>
                    <td colspan="5" rowspan="5" style="padding: 0">

                        @if(isset($warehouse_rent_for_items) && count($warehouse_rent_for_items)>0)
                            <table class="tbl-td-center" border="1" width="100%">
                                <tr>
                                    <td width="16%"><b>Item</b></td>
                                    <td width="18%"><b>Basis Of Charge</b></td>
                                    <td width="6%"><b>Slab</b></td>
                                    <td width="10%"><b>Period</b></td>
                                    <td width="7%" style="text-align: center"><b>Day</b></td>
                                    <td width="13%"><b>Quantity</b></td>
                                    <td width="12%"><b>Rate</b></td>
                                    <td width="32%" class="amount-right"><b>Amount</b></td>

                                </tr>

                                @if(isset($item_wise_shed_details) && !empty($item_wise_shed_details))
                                    <tr>
                                        <th colspan="8"><b>Shed</b></th>
                                    </tr>
                                    @foreach($item_wise_shed_details as $key => $item)
                                        <tr>
                                            <td>
                                                <span style="text-transform: capitalize; font-size: 10px">{{ $item->item_name }}</span>
                                                @if($item->dangerous=='1')
                                                    <span><b>({{ $item->dangerous=='1' ? '200%':''}}</b>)</span>
                                                @endif
                                            </td>
                                            <td style="vertical-align: middle;text-align: center;">
                                                @if($item->item_type==1)
                                                    Volumn
                                                @elseif($item->item_type==2)
                                                    Unit
                                                @elseif($item->item_type==3)
                                                    Package
                                                @else
                                                    Weight
                                                @endif
                                            </td>
                                            <td colspan="7" style="font-size: 12px">
                                                <table border="1" width="100%">
                                                    @php($danger=1)
                                                        <tr>
                                                            <td style="width: 7%">
                                                                <b>{{ $item->slab }}</b> <br>
                                                            </td>
                                                            <td style="width: 12%">
                                                        <span class="slab-period">
                                                           ( {{ $item->start_day }}
                                                            <br> to <br>
                                                            {{ $item->end_day }} )
                                                        </span>
                                                            </td>
                                                            <td class="td-center"
                                                                style="width: 7%;">{{ $item->slab_duration_day }} </td>
                                                            <td class="td-center" style="width: 13%">
                                                                {{$item->item_quantity }}
                                                            </td>
                                                            <td class="td-center" style="width: 12%">
                                                                {{ $item->charge }}
                                                                @if( $item->dangerous=='1')
                                                                    <span>X {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>
                                                                @endif

                                                            </td>

                                                            <td style="text-align: right; width:19%">
                                                                <b>={{number_format($item->total_charge,2)}}</b>
                                                            </td>
                                                        </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                {{--Yard--}}
                                @if(isset($item_wise_yard_details) && !empty($item_wise_yard_details))
                                    <tr>
                                        <th colspan="8"><b>Yard</b></th>
                                    </tr>
                                    @foreach($item_wise_yard_details as $key => $item)
                                        <tr>
                                            <td>
                                                <span style="text-transform: capitalize; font-size: 10px">{{ $item->item_name }}</span>
                                                @if($item->dangerous=='1')
                                                    <span><b>({{ $item->dangerous=='1' ? '200%':''}}</b>)</span>@endif
                                            </td>
                                            <td style="vertical-align: middle;text-align: center;">
                                                @if($item->item_type==1)
                                                    Volumn
                                                @elseif($item->item_type==2)
                                                    Unit
                                                @elseif($item->item_type==3)
                                                    Package
                                                @else
                                                    Weight
                                                @endif
                                            </td>
                                            <td colspan="7" style="font-size: 12px">
                                                <table border="1" width="100%">
                                                    @php($danger=1)
                                                        <tr>
                                                            <td style="width: 7%">
                                                                <b>{{ $item->slab }}</b> <br>
                                                            </td>
                                                            <td class="td-center" style="width:12% ;">
                                                          <span class="slab-period">
                                                              ( {{ $item->start_day }}
                                                              <br> to <br>
                                                              {{ $item->end_day }} )
                                                            </span>
                                                            </td>
                                                            <td style="width:7% ; text-align: center">{{ $item->slab_duration_day }} </td>
                                                            <td class="td-center" style="width: 13%">
                                                                {{ $item->item_quantity }}
                                                            </td>
                                                            <td class="td-center" style="width: 13%">
                                                                {{ $item->charge }}
                                                                @if( $item->dangerous=='1')
                                                                    <span>X {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>
                                                                @endif

                                                            </td>

                                                            <td style="text-align: right; width: 20%">
                                                                <b>={{number_format($item->total_charge,2)}}</b>
                                                            </td>
                                                        </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        @else
                            <h3 style=" transform: rotate(-35deg);vertical-align: middle; text-align: center; color: red">
                                Free Time
                            </h3>
                    @endif


                </tr>
                <tr id="one">
                    <td></td>
                    <td> No Of Package</td>
                    <td><span>{{$package_no ? $package_no:'' }}</span>
                        <span>@{{ package_type }}</span>
                    </td>
                    {{-- <td>(a) 1st slab</td>
                     <td><span ng-show="firstSlabDay">@{{ chargableTonForWarehouse }}</span></td>
                     <td><span ng-show="firstSlabDay">@{{ firstSlabDay }}</span></td>
                     <td><span ng-show="firstSlabDay">@{{ firstSlabCharge }}</span></td>
                     <td class="amount-right"><span ng-show="firstSlabDay">@{{ totalFirstSlabCharge|number:2 }}</span></td>--}}
                </tr>

                <tr id="one">
                    <td></td>
                    <td> Weight</td>
                    <td>

                        <form class="form-inline" ng-submit="changeBassisOfCharge(bassisOfCharge)" role="form">
                            <div class="form-group">
                                <label>{{$chargeable_ton}} KGs</label>


                            </div>
                        </form>


                        <span style="color: #b92c28;" id="changeBassisOfChargeError"
                              ng-show="changeBassisOfChargeError">@{{changebassisOfChargeErrorMsgTxt}}</span>

                        <span style="color: #3e8f3e;" id="changeBassisOfChargeSuccMsg"
                              ng-show="changeBassisOfChargeSuccMsg">@{{ bassisOfChargeSuccMsgTxt}}</span>
                    </td>
                </tr>


                <tr id="one">
                    <td></td>
                    <td> Date of Unloading<br/>@if(($receive_date != null) && ($partial_status != 1))<span>(Partial)</span>@endif
                    </td>

                    <td>

                        <nobr>
                            @if($partial_status == 1)
                                {{ $receive_date != null ? date('d-m-Y H:m:s',strtotime($receive_date)) : 'Truck To Truck' }}
                            @else
                                {{ $receive_date != null ?  date('d-m-Y',strtotime($receive_date)) : 'Truck To Truck' }} (Partial)
                            @endif
                        </nobr>
                    </td>
                </tr>


                <tr id="one">
                    <td></td>
                    <td> Free Period</td>
                    <td ng-model="freePeriod">
                        @if($partial_status == 1)
                            @if(isset($free_items) && !empty($free_items))
                                @foreach($free_items as $key => $ft)
                                    {{--<span style="font-style: oblique;"> {{ $ft->item_name }} -></span>--}}
                                    @if($key == 0)
                                        <span>{{ date('d-m-Y',strtotime($receive_date)) }}  - {{  $ft->free_day_end  }} = FT<br></span>
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </td>
                </tr>


                <tr id="one">
                    <td></td>
                    <td> Rent due Period</td>
                    <td ng-model="rentDuePeriod">
                        @if(count($warehouse_rent_for_items) > 0)
                            @foreach($warehouse_rent_for_items as $key => $ri)
                                {{--<span style="font-style: oblique;">{{  $ri->item_name  }}-></span>--}}
                                @if($key == 0)
                                    <span> {{$ri->rent_start_day}} - {{date('d-m-Y',strtotime($delivery_date))}} = {{ $ri->rent_day }}</span><br>
                                @endif
                            @endforeach
                        @else
                            'No Rent'
                        @endif
                    </td>
                    <!--td colspan="2"></td>


                    <td colspan="2">

                    </td-->
                    <td colspan="6" style="" class="amount-right">
                        <b>W.R Total:</b>
                        <span style="width: 200px;">
                            {{number_format($TotalSlabCharge,2)}}
                        </span>
                    </td>

                </tr>

            </table>


        </div>

        {{--==========================Warehouse Rent====END=========================--}}

        {{--==========================Handling Operation ====START=========================--}}

        <div class="col-md-12 td-bordered" style="padding: 0">
            {{-- <p class="col-md-5">
              <button class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Gross</button>
              <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">Net</button>
              <button class="btn btn-success" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Unload</button>
              <button class="btn btn-danger" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Load</button>
              <button class="btn btn-warning" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">Maximum</button>
            </p>
            <p class="col-md-2">
                <input type="number" class="form-control" name="weight_ton">
            </p> --}}


            <table class="table table-bordered" style="width: 100%;box-shadow: 0 0 5px 1px darkgrey">


                <tr>
                    <td></td>
                    <th colspan="3" style="width: 50%"></th>
                    <th colspan="4" style="width: 50%"></th>
                    <td></td>
                </tr>

                <tr>
                    <td><b>2.</b></td>
                    <th style="width:590px">Handling Operation</th>
                    <th colspan="2">Handling Mode</th>
                    <td>
                        <b>Ton</b><br/>
                    </td>
                    <td><b>Rate</b></td>
                    <th colspan="2"></th>
                    <th class="amount-right">Amount</th>
                </tr>
                @if($OffloadLabour || $OffLoadingEquip )

                    <tr>
                        <td></td>
                        <th rowspan="2" style="vertical-align: middle"> Unloading:</th>

                        <td colspan="2"> Manual</td>
                        <td>
                        <span>
                              @if($OffloadLabour )
                                {{ $OffloadLabour }}
                            @endif

                        </span>
                        </td>
                        <td>
                        <span>
                          @if($OffloadLabour )
                                {{$OffloadLabourCharge}}
                            @endif
                        </span>
                        </td>
                        <td colspan="2"></td>
                        <td class="amount-right">

                            <span>
                             @if($OffloadLabour)
                                    {{number_format($TotalForOffloadLabour,2)}}
                                @endif
                            </span>


                        </td>
                    </tr>

                    <tr>
                        <td></td>

                        <td colspan="2">
                            Equipment
                        </td>
                        <td>
                        <span>
                   @if($OffLoadingEquip)
                                {{$OffLoadingEquip}}
                                @if($offloadEquipShiftingFlag) X <span>2</span>@endif

                            @endif
                        </span>
                        </td>
                        <td>
                        <span>
                              @if($OffLoadingEquip)
                                {{ $OffLoadingEquipCharge }}
                                @if($offloadEquipShiftingFlag) X <span>2</span>@endif
                            @endif

                        </span>
                        </td>
                        <td colspan="2">
                            <span>
                                @if(!$OffLoadingEquip && $self_flag) (Self)    @endif
                            </span>
                        </td>
                        <td class="amount-right">
                        <span>
                             @if($OffLoadingEquip){{ number_format($TotalForOffloadEquip,2)}} @endif
                        </span>
                        </td>
                    </tr>
                @endif

                {{--Loading--}}

                @if($loadLabour || $loadEquip)
                    <tr>
                        <td></td>
                        <th rowspan="2" class="verticle-middle"> Loading:</th>
                        <td colspan="2"> Manual</td>


                        <td>
                      <span>
                            @if($loadLabour)
                              {{$loadLabour}}
                          @endif
                        </span>
                        </td>
                        <td>
                        <span>
                            @if($loadLabour)
                                {{ $loadLabourCharge }}
                            @endif

                        </span>
                        </td>
                        <td colspan="2"></td>
                        <td class="amount-right">
                        <span>
                          @if($loadLabour )
                                {{ number_format($TotalForloadLabour,2)}}
                            @endif
                        </span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2">
                            Equipment
                        </td>

                        <td>

                         <span>
                              @if($loadEquip)
                                 {{$loadEquip}}  @if($loading_shifting)X 2 @endif
                              @endif

                        </span>
                        </td>
                        <td>
                         <span>
                             @if($loadEquip)

                                 {{$loadingEquipCharge}}
                             @endif

                        </span>
                        </td>
                        <td colspan="2">
                        <span>
                            @if(!$loadEquip && $self_flag)
                                (Self)
                            @endif
                        </span>
                        </td>

                        <td class="amount-right">
                        <span>
                            @if($loadEquip)
                                {{ number_format($TotalForloadEquip,2)}}
                            @endif
                        </span>
                        </td>
                    </tr>
                @endif


            </table>
        </div>


        {{--==========================Handling Operation ====END=========================--}}

        {{--========================== Other Dues ====START=========================--}}

        <div class="col-md-12 td-bordered" style="padding: 0">
            <br>

            <table class="table table-bordered" style="width: 100%;box-shadow: 0 0 5px 1px darkgrey">


                <tr>
                    <td></td>
                    <th colspan="4" style="width: 50%"></th>

                    <th colspan="4" style="width: 50%"></th>
                    <td></td>

                </tr>

                <tr>
                    <td><b>3.</b></td>
                    <th colspan="2" style="width:590px"> Other Dues</th>
                    <th colspan="2"><b>Truck</b></th>
                    <td><b>Number</b></td>
                    <td><b>Rate</b></td>
                    <th colspan="2"></th>
                    <th class="amount-right">Amount</th>
                </tr>


                {{--Truck Entrance fee--}}

                <tr>
                    <td></td>
                    <th colspan="2" rowspan="3" class="verticle-middle"> Entrance Fee:</th>
                    <td colspan="2"> Foreign</td>
                    <td class="td-center">
                        <span>
                             @if($entranceTotalForeignTruck>0)
                                {{$entranceTotalForeignTruck}}
                            @endif
                        </span>
                    </td>
                    <td>
                        <span style="">
                         @if($entranceTotalForeignTruck>0)
                                {{$entranceFeeForeign}}
                            @endif
                        </span>
                    </td>
                    <td colspan="2"></td>

                    <td class="amount-right">
                        <span>
                         @if($entranceTotalForeignTruck>0)
                                {{ number_format($totalForeignTruckEntranceFee,2) }}
                            @endif
                        </span>
                    </td>
                </tr>

                <tr>
                    <td></td>
                    <td colspan="2"> Local Truck</td>
                    <td class="td-center">
                        <span>
                        @if($entranceTotalLocalTruck>0)
                                {{$entranceTotalLocalTruck}}
                            @endif
                        </span>
                    </td>
                    <td>
                        <span>
                         @if($entranceTotalLocalTruck>0)
                                {{$entranceFeeLocalTruck}}
                            @endif
                        </span>
                    </td>
                    <td colspan="2"></td>

                    <td class="amount-right">
                        <span>
                            @if($totalLocalTruckEntranceFee>0)
                                {{number_format($totalLocalTruckEntranceFee,2)}}
                            @endif
                        </span>
                    </td>

                </tr>


                <tr>
                    <td></td>
                    <td colspan="2"> Local Van</td>
                    <td class="td-center">
                        <span>
                         @if($entranceTotalLocalVan>0)
                                {{$entranceTotalLocalVan}}
                            @endif
                        </span>
                    </td>
                    <td>
                        <span>
                         @if($entranceTotalLocalVan>0)
                                {{$entranceFeeVan}}
                            @endif
                        </span>
                    </td>
                    <td colspan="2"></td>

                    <td class="amount-right">
                        <span>
                            @if($entranceTotalLocalVan>0)
                                {{number_format($totalLocalVanEntranceFee,2)}}
                            @endif
                        </span>
                    </td>

                </tr>

                {{--Carpenter Charge--}}

                <tr>
                    <td></td>
                    <th colspan="2" rowspan="2" class="verticle-middle"> Carpenter charge:</th>
                    <td colspan="2"><b>Type</b></td>
                    <td><b>Packs</b></td>
                    <td><b>Rate</b></td>
                    <td colspan="2"></td>
                    <td></td>

                </tr>


                <tr>
                    <td></td>
                    <td colspan="2">
                        <nobr> Opening/Closing</nobr>
                    </td>
                    <td class="td-center">
                        <span>
                              @if($carpenterOPPackages)
                                {{$carpenterOPPackages}}
                            @endif
                        </span>
                    </td>

                    <td>
                        <span>
                            @if($carpenterOPPackages)
                                {{$carpenterChargesOpenClose}}
                            @endif
                        </span>
                    </td>

                    <td colspan="2"></td>

                    <td class="amount-right"><span>
                          @if($carpenterOPPackages)
                                {{ number_format($totalCarpenterChargesOpenClose,2) }}
                            @endif
                        </span>
                    </td>
                </tr>

                {{-- <tr ng-if="carpenterRepairPackages > 0">
                    <td></td>
                    <td colspan="2" ng-if="carpenterRepairPackages > 0">Repair</td>
                    <td colspan="4">
                        <span style="padding-left: 10%;" ng-if="carpenterRepairPackages > 0">@{{ carpenterRepairPackages }}</span>
                        <span style="padding-left: 6%;" ng-if="carpenterRepairPackages > 0"> X </span>
                        <span style="padding-left: 3%;" ng-if="carpenterRepairPackages > 0">@{{ carpenterChargesRepair }}</span>
                    </td>
                    <td class="amount-right">
                        <span ng-if="totalcarpenterChargesRepair>0"> @{{ totalcarpenterChargesRepair |number:2}}</span>
                    </td>
                </tr> --}}



                {{--Weighment Charge--}}

                <tr>
                    <td></td>
                    <th rowspan="3" colspan="2" style="vertical-align:middle"> Weighment Charge:</th>
                    <th><b>Truck</b>
                    </th>
                    <td>
                        <b>
                            <nobr>Total Truck</nobr>
                        </b>
                    </td>
                    <td><b>Twice</b></td>
                    <td><b>Charge</b></td>
                    <td colspan="2">

                    </td>

                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        Foreign
                    </td>
                    <td class="td-center">
                        <span>
                        @if($totalForeignTruckForWeighment)
                                {{$totalForeignTruckForWeighment}}
                            @endif
                        </span>
                    </td>
                    <td class="td-center">
                        <span>
                           @if($totalForeignTruckForWeighment)
                                2
                            @endif
                        </span>
                    </td>
                    <td>
                        <span>
                        @if($totalForeignTruckForWeighment)
                                {{$weighmentChargeForeign}}
                            @endif
                        </span>
                    </td>
                    <td colspan="2">

                    </td>
                    <td class="amount-right">
                        <span>
                         @if($totalForeignTruckForWeighment)
                                {{ number_format($totalweightmentChargesForeign,2)}}
                            @endif
                        </span>
                    </td>

                </tr>

                <tr>
                    <td></td>
                    <td>
                        Local
                    </td>
                    <td class="td-center">
                        <span>
                            @if($totalLocalTruckForWeighment)
                                {{$totalLocalTruckForWeighment}}
                            @endif
                        </span>
                    </td>
                    <td class="td-center">
                        <span>

                            @if($totalLocalTruckForWeighment)
                                2
                            @endif
                        </span>
                    </td>
                    <td>
                        <span>
                           @if($totalLocalTruckForWeighment)
                                {{ $weighmentChargeLocal }}
                            @endif
                        </span>
                    </td>
                    <td colspan="2">

                    </td>

                    <td class="amount-right">
                        <span>
                        @if($totalLocalTruckForWeighment)
                                {{ number_format($totalweightmentChargesLocal,2)}}
                            @endif
                    </span>
                    </td>
                </tr>

                {{--Haltage Charge--}}

                <tr>
                    <td></td>
                    <th colspan="2" data-toggle="modal"
                        data-target="#haltageChargeChangeModal" ng-click="getTrucksForHaltageChargeChange()"
                        data-backdrop="static"
                        data-keyboard="false">
                        Haltage Charge:
                    </th>
                    <td colspan="6"></td>

                    <td></td>
                </tr>


                <tr {{--data-toggle="modal"
                    data-target="#haltageChargeChangeModal" ng-click="getTrucksForHaltageChargeChange()"
                    data-backdrop="static"
                    data-keyboard="false"--}}>
                    <td></td>
                    <td colspan="9" style="padding: 0;">

                        <table class="table table-bordered">
                            <tbody>

                            <tr>
                                <th style="">Truck</th>
                                <th>Truck No.</th>
                                <th>Scale Weight</th>
                                <th>Receive Weight</th>
                                <th colspan="2">Entry Date</th>
                                <th colspan="2">Unloading Date</th>
                                <td><b>Day</b></td>
                                <td><b>Rate</b></td>
                                <td></td>

                            </tr>

                            @if($foreign_haltage_details)

                                @php
                                    $haltagesForeignScaleWeight=0;
                                    $haltagesForeignReceiveWeight=0
                                @endphp

                                @foreach($foreign_haltage_details as$k=>$v)
                                    <tr>
                                        @if($k==0)
                                            <td style="vertical-align: middle;padding: 0"
                                                rowspan="{{count($foreign_haltage_details)}}">
                                                <b> &nbsp;&nbsp;&nbsp; Foreign Truck</b>
                                            </td>
                                        @endif
                                        <td>{{ $v->truck_no }}</td>
                                        <td>{{ $v->tweight_wbridge }}</td>@php $haltagesForeignScaleWeight+=$v->tweight_wbridge  @endphp
                                        <td>{{ $v->receive_weight }} </td> @php $haltagesForeignReceiveWeight+=$v->receive_weight @endphp
                                        <td colspan="2">
                                            <nobr><b style="font-size: 12px">{{ $v->truckentry_datetime != null ? date("d-m-Y", strtotime($v->truckentry_datetime)) : "" }}</b></nobr>
                                        </td>
                                        <td colspan="2">
                                            <nobr><b style="font-size: 12px">{{ $v->receive_datetime != null ? date("d-m-Y", strtotime($v->receive_datetime)) : "" }}</b></nobr>
                                        </td>
                                        <td>
                                            <span>{{ $v->haltage_days}}</span>
                                        </td>
                                        <td>
                                            <span>{{  $haltage_charge_foreign }}</span>
                                        </td>
                                        <td class="amount-right" width="68px">
                                            @if($v->holtage_charge_flag==0 && $v->haltage_days>0)
                                                <span>
                                                    {{ ($v->haltage_days *  $haltage_charge_foreign) }}
                                                </span>
                                            @endif
                                            @if($v->holtage_charge_flag==1 && $v->haltage_days>0)
                                                <span class="ok">
                                                     Paid
                                                </span>
                                            @endif

                                        </td>
                                    </tr>

                                @endforeach
                            @endif

                            @if($haltage_truck_local)
                                <tr>
                                    <td style="vertical-align: middle;padding: 0; "
                                        rowspan="1"><b> &nbsp;&nbsp;&nbsp; Local Truck</b>
                                    </td>
                                    <td>
                                        @if($haltage_truck_local)
                                            {{$haltage_truck_local}}
                                        @endif
                                        <span style="font-size: 12px;"><i>(Number of Truck)</i></span>
                                    </td>
                                    <td colspan="6"></td>
                                    <td>
                                        @if($haltage_truck_local)
                                            {{number_format($haltage_day_local,0)}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($haltage_truck_local)
                                            {{$haltage_charge_local}}
                                        @endif
                                    </td>
                                    <td class="amount-right"
                                        style="width:20px; ">
                                        @if($haltage_truck_local)
                                            {{ number_format($haltage_total_local,2)}}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="amount-right">{{ number_format($haltagesForeignScaleWeight,2 )}}</td>
                                <td class="amount-right">{{ number_format($haltagesForeignReceiveWeight,2 )}}</td>
                                <td class="amount-right" colspan="6"></td>
                                <td class="amount-right" style="width:20px;">
                                    <span>{{ number_format($haltage_total_foreign + $haltage_total_local,2)}}</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                {{--============================Night Charge--}}

                <tr>
                    <td></td>
                    <th colspan="2">Charge:</th>
                    <td><b>Number</b></td>
                    <td><b>Rate</b></td>
                    <th colspan="4"></th>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <th colspan="2">
                        Documentation Charge:
                    </th>
                    <td class="td-center">
                        <span>

                        @if($totalDocumentCharge)
                                {{$number_of_documents}}
                            @endif

                        </span>
                    </td>
                    <td>
                        <span style="padding-left:5%">
                            @if($totalDocumentCharge)
                                {{$document_charge}}
                            @endif
                        </span>
                    </td>

                    <th style="padding-left:6%" colspan="4">

                    </th>
                    <td class="amount-right">
                        <span>
                         @if($totalDocumentCharge)
                                {{$totalDocumentCharge}}
                            @endif

                        </span>
                    </td>
                </tr>


                {{--=====================Holiday Charge================--}}

                {{--<tr>--}}
                    {{--<td></td>--}}
                    {{--<td rowspan="2" style="vertical-align: middle">--}}
                        {{--<b>Holiday charge:</b>--}}
                    {{--</td>--}}
                    {{--<td>Unloading</td>--}}


                    {{--<td class="td-center">--}}
                        {{--<span>--}}

                          {{--@if($HolidayTotalTruck)--}}
                                {{--{{$HolidayTotalTruck}}--}}
                            {{--@endif--}}
                        {{--</span>--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--<span>--}}

                        {{--@if($HolidayTotalTruck)--}}
                                {{--{{ number_format($HolidayCharge,2)}}--}}
                            {{--@endif--}}
                        {{--</span>--}}
                    {{--</td>--}}
                    {{--<td colspan="4"></td>--}}
                    {{--<td class="amount-right">--}}
                        {{--<span>--}}
                         {{--@if($HolidayTotalTruck)--}}
                                {{--{{ number_format($TotalHolidayCharge,2)}}--}}
                            {{--@endif--}}

                        {{--</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}

                {{--<tr>--}}
                    {{--<td></td>--}}
                    {{--<td>Loading</td>--}}

                    {{--<td class="td-center">--}}
                        {{--<span ng-if="TotalLocalHolidayCharge>0">@{{ holidayTotalLocalTruck }}</span>--}}
                    {{--</td>--}}
                    {{--<td>--}}
                        {{--<span ng-if="TotalLocalHolidayCharge>0">@{{ local_holiday_charge }}</span>--}}
                    {{--</td>--}}
                    {{--<td colspan="4"></td>--}}
                    {{--<td class="amount-right">--}}
                        {{--<span ng-if="TotalLocalHolidayCharge>0">@{{ TotalLocalHolidayCharge }}</span>--}}
                    {{--</td>--}}
                {{--</tr>--}}


                {{--outstanding Charge--}}

                {{--<tr>
                    <td></td>
                    <th>(viii). Outstanding Bill(if any):</th>
                    <td></td>
                    <td></td>
                    <td colspan="4"></td>

                    <td></td>
                </tr>--}}
            </table>
            <br>
        </div>

        {{--Equipment hired period distance rate--}}

        {{--  <div class="col-md-12 box-shadow" style="padding: 0;">

          -- <table class="table-bordered td-bordered" border="0" style="width: 100%">
                  <tr>
                      <td></td>
                      <th colspan="2" style="width: 50%"></th>
                      <td></td>

                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>

                  </tr>
             {{-- <tr>
                      <td><b>4.</b></td>
                      <th> Equipment Hire Period Distance Rate Charge:</th>
                      <td></td>
                      <th colspan="4"></th>
                      <td></td>
                  </tr>

                  <tr>
                      <td></td>
                      <td>(a) Mobile Crane</td>
                      <td></td>
                      <th colspan="4"></th>
                      <td></td>
                  </tr>

                  <tr>
                      <td></td>
                      <td>(b) Forklift</td>
                      <td></td>
                      <th colspan="4"></th>
                      <td></td>
                  </tr>
                  <tr>
                      <td></td>
                      <td>(c) Tarpaulin</td>
                      <td></td>
                      <th colspan="4"></th>
                      <td></td>
                  </tr>

              </table>
            <br>
        </div>
        <div class="clearfix">&nbsp;</div>--}}

        <div class="col-md-12 box-shadow" style="padding:0">


            <table border="1" class="table table-bordered td-bordered" style="width: 100%">

                <tr>
                    <td></td>
                    <th rowspan="3" style="width: 70%; vertical-align: middle">

                    </th>

                    <td class="amount-right">
                        <b>Sub Total Taka:</b> <span>
                          {{ number_format($TotalAssessmentValue,2)}}
                        </span>
                    </td>


                </tr>

                <tr>
                    <td><b>4.</b></td>

                    <td>
                        <b>VAT
                            <span class="color-red">
                        @if($vat_flag == 1 || is_null($vat_flag))
                                    (15%)
                                @else
                                    (No VAT)
                                @endif
                </span>
                        </b>
                        <span style="float: right;"
                              class="amount-right">{{ $Vat!= 0 ? number_format($Vat,2) : null }}</span>

                    </td>


                </tr>
                <tr>
                    <td></td>

                    <td class="amount-right">
                        <b>Grand Total Taka:</b> <span>

                        {{ number_format($TotalAssessmentWithVat,2)}}
                        </span>
                    </td>


                </tr>

                <tr>
                    <td></td>
                    <td colspan="2"><b>In Words (Taka)</b>

                        <span id="totalInWord" class="text-capitalize" style="text-decoration: underline"></span>
                        {{convert_number_to_words($TotalAssessmentWithVat)." Taka only"}}
                    </td>


                </tr>

            </table>

        </div>

    </div>


    <div class="col-md-12 ForPdf" ng-show="ForPdf">

        <br><br><br><br><br><br>

        <div class="col-md-4">
            <span>Signature Of </span><br>
            <span>Warehouse Superintendent</span>
        </div>

        <div class="col-md-4">
            <span>Signature Of </span><br>
            <span>Warehouse Superintendent</span>
        </div>

        <div class="col-md-4">
            <span></span><br>
            <span>Checked & Verified By</span>
        </div>

    </div>


</div>
{{--END PDF Div--}}
{{--
<script type="text/javascript">
    $('#receive_datetime').datetimepicker({
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        timeFormat: 'HH:mm:ss'
    });
</script>--}}
<script>
    $(document).ready(function () {

        $('#receive_datetime').datetimepicker({
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            timeFormat: 'HH:mm:ss'


        });
    });


</script>


@php
    function convert_number_to_words($number) {
    $hyphen      = ' ';
    $conjunction = ' and ';
    $separator   = ' ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
       0                   => 'zero',
       1                   => 'one',
       2                   => 'two',
       3                   => 'three',
       4                   => 'four',
       5                   => 'five',
       6                   => 'six',
       7                   => 'seven',
       8                   => 'eight',
       9                   => 'nine',
       10                  => 'ten',
       11                  => 'eleven',
       12                  => 'twelve',
       13                  => 'thirteen',
       14                  => 'fourteen',
       15                  => 'fifteen',
       16                  => 'sixteen',
       17                  => 'seventeen',
       18                  => 'eighteen',
       19                  => 'nineteen',
       20                  => 'twenty',
       30                  => 'thirty',
       40                  => 'fourty',
       50                  => 'fifty',
       60                  => 'sixty',
       70                  => 'seventy',
       80                  => 'eighty',
       90                  => 'ninety',
       100                 => 'hundred',
       1000                => 'thousand',
       1000000             => 'million',
       1000000000          => 'billion',
       1000000000000       => 'trillion',
       1000000000000000    => 'quadrillion',
       1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
       return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
       // overflow
       trigger_error(
           'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
           E_USER_WARNING
       );
       return false;
    }

    if ($number < 0) {
       return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
       list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
       case $number < 21:
           $string = $dictionary[$number];
           break;
       case $number < 100:
           $tens   = ((int) ($number / 10)) * 10;
           $units  = $number % 10;
           $string = $dictionary[$tens];
           if ($units) {
               $string .= $hyphen . $dictionary[$units];
           }
           break;
       case $number < 1000:
           $hundreds  = $number / 100;
           $remainder = $number % 100;
           $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
           if ($remainder) {
               $string .= $conjunction . convert_number_to_words($remainder);
           }
           break;
       default:
           $baseUnit = pow(1000, floor(log($number, 1000)));
           $numBaseUnits = (int) ($number / $baseUnit);
           $remainder = $number % $baseUnit;
           $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
           if ($remainder) {
               $string .= $remainder < 100 ? $conjunction : $separator;
               $string .= convert_number_to_words($remainder);
           }
           break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
       $string .= $decimal;
       $words = array();
       foreach (str_split((string) $fraction) as $number) {
           $words[] = $dictionary[$number];
       }
       $string .= implode(' ', $words);
    }

    return $string;
    }
@endphp