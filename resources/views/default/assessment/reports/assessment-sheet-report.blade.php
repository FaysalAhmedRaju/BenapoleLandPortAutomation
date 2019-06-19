{{--
@php
   $userRoleId= Auth::user()->role->id
@endphp--}}

@if($permitted)

        <!DOCTYPE html>
<html>
<head>

    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">

    <title>Assessment Report- {{$manifestNo}}</title>
    <style type="text/css">


        /* @page {
             margin: 5px 30px;
         }*/

        html {
            margin: 5px 25px 0;
        }

        .center {
            text-align: center;
        }

        .amount-right {
            text-align: right !important;
        }

        .tble-warehouse {
            border-collapse: collapse;
            border-right: 1px solid black !important;
        }


        .tble-warehouse tr:first-child  td {
            border-bottom: 1px solid black;

        }

        .td-center {
            text-align: center !important;
        }

        .body-border {
            border-bottom: 1px solid black;
        }

        .color-red {
            color: red;
        }
        span.slab-period {
            /*margin-left: 8px;*/
            display: block;
            /*color: #b1b1b1;*/
            font-size: 10px;
            font-style: italic;
            font-weight: bold;
            text-align: center;

        }

        .table-item {
            margin: 0 auto;
            border-collapse: collapse;
            border-spacing: 0;
            border-top: none;
            border-bottom: none;
        }

       /* .table-item tr {
            border-top: none;
            border-bottom: none;
        }*/

        .table-item tr td {
            border-right: solid 1px black;
            border-left: solid 1px black;
        }
        .table-item tr td:first-child {
            border-right: none;
            border-left: none;
        }
        .table-item tr td:last-child {
            border-right: none;
            border-left: none;
        }
    </style>


</head>


<body>
<table width="100%;" class="dataTable">
    <tr>
        <td style="width: 15%">
            <img src="../public/img/blpa.jpg" height="100">
        </td>
        <td style="width: 66%; text-align:center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
            <b>
                <u>Assessment Sheet</u>
            </b>

        </td>
        <td style="width: 19%; font-size: 14px;">
            <p style="vertical-align: top; text-align: right;"> <b>Serial No.:</b> {{ isset($yearly_serial) ? $yearly_serial : "" }} </p><br>
            <p style="vertical-align: bottom;"><b>Date:</b> {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}</p>
        </td>
    </tr>
</table>
<br>
<br>


<div style="padding: 0">

    <table border="0" class="body-border" style="box-shadow: 0 0 5px 1px darkgrey; width: 100%; ">

        <tr>
            <td class="2">
                <b> Manifest No & Date:</b>
            </td>

            <td class="2">
                <u> {{$manifestNo}} : {{date('d-m-Y',strtotime($ManifestDate))}}</u>
            </td>


            <td class="2">
                <b>Consignee:</b>
            </td>

            <td class="2">
                <p style="text-transform: capitalize"> {{$importer}}</p>
            </td>
        </tr>

        <tr>
            <td>
                <b>Bill Of Entry No & Date:</b>
            </td>

            <td class="2"> {{--<span>C-</span>--}}
                <u> {{$bill_entry_no}} : {{date('d-m-Y',strtotime($bill_entry_date))}}</u>
            </td>


            <td class="2">
                <b> Consignor:</b>
            </td>

            <td class="2">
                <p style="text-transform: capitalize">
                    {{$exporter}}
                </p>
            </td>
        </tr>


        <tr>
            <td>
                <b>Custom's Release Order No & Date:</b>
            </td>

            <td><span></span>
                <u>  {{$custom_realise_order_No}} : {{date('d-m-Y',strtotime($custom_realise_order_date))}} </u>
            </td>


            <td>
                <b> C & F Agent:</b>
            </td>

            <td>
                {{$cnf_name?$cnf_name:''}}
            </td>
        </tr>

        <tr>
            <td></td>
            <td></td>

            <td><b>Shed / Yard No.</b></td>
            <td>{{$posted_yard_shed}}</td>
        </tr>


    </table>


</div>


<div class="" style="padding: 0">

    <table border="0">
        <tr>
            <th colspan="5"></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th colspan="5">PARTICULAR OF CHARGES DUE<br></th>
            <th colspan="3"></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="amount-right"><br></th>
        </tr>
        <tbody class="body-border">
        <tr>
            <th>1</th>
            <th colspan="2">Warehouse Rent<br></th>
            <td></td>
            <td><br></td>
            <th colspan="2">Assessment:</th>
            <td colspan="2"></td>
            <td colspan="3" style="text-align: right;font-size: 13px">
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2"> Description of Goods<br></td>
            <td colspan="2">{{--<span>Items= <b>{{ $totalItems }}</b> </span> <br>--}}
                {{$description_of_goods}}
            </td>
            <td colspan="7" rowspan="5" style="vertical-align: top;font-size: 13px">

                <table border="1" width="100%" class="tble-warehouse">
                 <tr>
                        <td width="23%"><b>Item</b></td>
                        <td width="22%"><b>Basis Of Charge</b></td>
                        <td width="10%"><b>Slab</b></td>
                        <td class="td-center" width="14%"><b>Period</b></td>
                        <td width="9%" style="text-align: center"><b>Day</b></td>
                        <td class="td-center" width="14%"><b>Quantity</b></td>
                        <td class="td-center" width="16%"><b>Rate</b></td>
                        <td width="24%" class="amount-right"><b>Amount</b></td>
                    </tr>

                    @if(isset($warehouse_rent_for_items) && count($warehouse_rent_for_items)>0 &&
                    (!empty($item_wise_shed_details) || !empty($item_wise_yard_details)))

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
                                        <table class="table-item" width="100%">
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
                                                    <td class="td-center" style="width: 7%;">{{ $item->slab_duration_day }} </td>
                                                    <td class="td-center" style="width: 13%">
                                                            {{$item->item_quantity }}
                                                    </td>
                                                    <td class="td-center" style="width: 12%">
                                                        {{ $item->charge }}
                                                        @if( $item->dangerous=='1')
                                                            <span>X  {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>
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
                                        <table class="table-item" width="100%">
                                            @php($danger=1)
                                                <tr>
                                                    <td style="width: 7%">
                                                        <b>{{ $item->slab }}</b> <br>
                                                    </td>
                                                     <td class="td-center" style="width:12% ;">
                                                      <span class="slab-period">
                                                          (  {{ $item->start_day }}
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
                                                            <span>X  {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>
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
                    @else
                        <tr>
                            <td colspan="8">
                                <h3 style=" transform: rotate(-35deg);vertical-align: middle; text-align: center; color: red">
                                    Free Time
                                </h3>
                            </td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">No of Package<br></td>
            <td colspan="2">{{$package_no ? $package_no:'' }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">Weight <br></td>
            <td colspan="2">{{$chargeable_ton}} Kgs</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">
                @if($partial_status == 1)
                    Date of Unloading
                @else 
                   Date of Unloading
                @endif
            </td>
            <td colspan="2">
                <nobr>
                    @if($partial_status == 1)
                        {{ $receive_date != null ? date('d-m-Y H:m:s',strtotime($receive_date)) : 'Truck To Truck' }}
                    @else
                        {{ $receive_date != null ? date('d-m-Y',strtotime($receive_date)) : 'Truck To Truck' }} (Partial)
                    @endif
                </nobr>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">Free Period<br></td>
            <td colspan="2">
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
        <tr>
            <td></td>
            <td colspan="2">Rent Due Period</td>

            <td colspan="3">
                @if(count($warehouse_rent_for_items) > 0)
                    @foreach($warehouse_rent_for_items as $key => $ri)
                        {{--<span style="font-style: oblique;">{{  $ri->item_name  }}-></span>--}}
                        @if($key == 0)
                            <span>{{$ri->rent_start_day}} - {{date('d-m-Y',strtotime($delivery_date))}} = {{ $ri->rent_day }}</span><br>
                        @endif
                    @endforeach
                @else
                    'No Rent'
                @endif

            </td>

            <td></td>
            <td colspan="4" class="amount-right">
                <b>Warehouse Rent Total</b>
            </td>
            <td class="amount-right">
                ---------------------------------------------------------------------
                <br>
                {{number_format($TotalSlabCharge,2)}}
            </td>

        </tr>
        </tbody>
        <tbody class="body-border">
        <tr>
            <th>2.</th>
            <th colspan="3">Handling Operation:<br></th>
            <th>{{-- Handling Mode --}}</th>
            <th colspan="3">{{-- Ton X Rate --}}</th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @if($OffloadLabour || $OffLoadingEquip )
            <tr>
                <td></td>
                <td colspan="2" rowspan="2"
                    style="vertical-align: middle">
                    Unloading
                </td>
                <td colspan="2">
                    @if($OffloadLabour) Manual @else &nbsp; @endif
                </td>
                <td colspan="3">
                    @if($OffloadLabour )
                        {{$OffloadLabour}} X {{$OffloadLabourCharge}}
                    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($OffloadLabour)
                        {{number_format($TotalForOffloadLabour,2)}}
                    @endif
                </td>
            </tr>

            <tr>
                <td></td>
                <td colspan="2"> @if($OffLoadingEquip) Equipment @endif </td>
                <td colspan="3">
                    @if($OffLoadingEquip)
                        {{$OffLoadingEquip}} X {{$OffLoadingEquipCharge}}
                        @if($offloadEquipShiftingFlag) X <span>2</span>@endif

                    @endif
                        @if(!$OffLoadingEquip && $self_flag) (Self)    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($OffLoadingEquip){{ number_format($TotalForOffloadEquip,2)}} @endif
                </td>
            </tr>
        @endif

        @if($loadLabour || $loadEquip)

            <tr>
                <td></td>
                <td colspan="2" rowspan="2" style="vertical-align: middle">Loading</td>
                <td colspan="2">
                    @if($loadLabour) Manual  @endif
                </td>
                <td colspan="3">
                    @if($loadLabour)
                        {{$loadLabour}} X {{$loadLabourCharge}}
                    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($loadLabour )
                        {{ number_format($TotalForloadLabour,2)}}
                    @endif
                </td>
            </tr>

            <tr>
                <td></td>
                <td colspan="2"> @if($loadEquip) Equipment @else &nbsp; @endif</td>
                <td colspan="3">
                    @if($loadEquip)
                        {{$loadEquip}} X {{$loadingEquipCharge}}  @if($loading_shifting)X 2 @endif

                    @endif
                        @if(!$loadEquip && $self_flag) (Self)    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                {{--<td>{{$TotalForloadEquip}}</td>--}}
                <td class="amount-right">
                    @if($loadEquip)
                        {{ number_format($TotalForloadEquip,2)}}
                    @endif
                </td>
            </tr>

        @endif

        </tbody>


        <tr>
            <td colspan="12">

            </td>
        </tr>
        <tr>
            <td colspan="12">

            </td>
        </tr>

        <tbody style="" class="body-border">
        <tr>
            <th>3.</th>
            <th colspan="2">Other Dues<br></th>
            <th colspan="2">{{-- Quantity --}}</th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @if($entranceTotalForeignTruck || $entranceTotalLocalTruck || $entranceTotalLocalVan)
        <tr>
            <td></td>
            <!--td rowspan="2"></td-->
            <td rowspan="3" colspan="2">
                <nobr>Truck Entrance Fee</nobr>
            </td>
            <td colspan="2">&nbsp;
                @if($entranceTotalForeignTruck>0)
                    Foreign Truck
                @endif
            </td>
            <td colspan="2">
                @if($entranceTotalForeignTruck>0)
                    {{$entranceTotalForeignTruck}} X {{$entranceFeeForeign}}
                @endif
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="amount-right">
                @if($entranceTotalForeignTruck>0)
                    {{ number_format($totalForeignTruckEntranceFee,2) }}
                @endif
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">  &nbsp;
                @if($entranceTotalLocalTruck>0)
                    Local Truck
                @endif
            </td>
            <td colspan="2">
                @if($entranceTotalLocalTruck>0)
                    {{$entranceTotalLocalTruck}} X {{$entranceFeeLocalTruck}}
                @endif
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="amount-right">
                @if($totalLocalTruckEntranceFee>0)
                    {{number_format($totalLocalTruckEntranceFee,2)}}
                @endif
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">&nbsp;
                @if($entranceTotalLocalVan>0)
                    Local Van
                @endif
            </td>
            <td colspan="2">
                @if($entranceTotalLocalVan>0)
                    {{$entranceTotalLocalVan}} X {{$entranceFeeVan}}
                @endif
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="amount-right">
                @if($entranceTotalLocalVan>0)
                    {{number_format($totalLocalVanEntranceFee,2)}}
                @endif
            </td>
        </tr>
        @endif

        @if($carpenterOPPackages || $carpenterRepairPackages)

            {{-- <tr>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2">Charge<br></td>
                <td colspan="2">Package X Rate</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr> --}}
            <tr>
                <td></td>
                <td colspan="2" rowspan="2">@if($carpenterOPPackages) Carpenter Charge<br> @endif</td>
                <td colspan="2">
                    @if($carpenterOPPackages) Opening/Closing<br> @endif
                </td>
                <td>
                    @if($carpenterOPPackages)
                        {{$carpenterOPPackages}} X {{$carpenterChargesOpenClose}}
                    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($carpenterOPPackages) {{ number_format($totalCarpenterChargesOpenClose,2) }}
                    @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">@if($carpenterRepairPackages) Repair @endif</td>
                <td>
                    @if($carpenterRepairPackages)
                        {{$carpenterRepairPackages}} X {{$carpenterChargesRepair}}
                    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($carpenterRepairPackages) {{ number_format($totalCarpenterChargesRepair,2)}}
                    @endif
                </td>
            </tr>
        @endif
        @if($haltage_truck_foreign || $haltage_truck_local)
            <tr>
                <td></td>

                <td colspan="2" rowspan="3">Haltage Charge</td>
                <td colspan="2">{{-- Type --}}</td>
                <td class="td-center">{{-- Number --}}</td>
                <td colspan="2"> {{-- Day X Rate --}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="amount-right"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                    @if($haltage_truck_foreign)
                        Foreign Truck
                    @endif
                </td>
                <td colspan="2">
                    @if($haltage_truck_foreign)
                        {{number_format($haltage_day_foreign,0)}} X {{$haltage_charge_foreign}}
                    @endif
                </td>
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($haltage_truck_foreign)
                        {{ number_format($haltage_total_foreign,2)}}
                    @endif
                </td>
            </tr>
            <tr>

                <td></td>
                <td colspan="2">
                    @if($haltage_truck_local)
                        Local Truck
                    @endif
                </td>
                <td colspan="2">
                    @if($haltage_truck_local)
                        {{number_format($haltage_day_local,0)}} X {{$haltage_charge_local}}
                    @endif
                </td>
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($haltage_truck_local)
                        {{ number_format($haltage_total_local,2)}}
                    @endif
                </td>
            </tr>
        @endif

        @if($totalForeignTruckForWeighment || $totalLocalTruckForWeighment)
            <tr>
                <td></td>
                <td rowspan="3" colspan="2">Weighment Charge<br></td>
                <td>{{-- Truck --}}</td>
                <td>{{-- Total --}}</td>
                <td>{{-- Twice --}}</td>
                <td>{{-- Charge --}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">Foreign Truck</td>
                <td colspan="2">@if($totalForeignTruckForWeighment) {{$totalForeignTruckForWeighment}} X 2
                    X {{$weighmentChargeForeign}} @endif</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($totalForeignTruckForWeighment) {{ number_format($totalweightmentChargesForeign,2)}} @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                    @if($totalLocalTruckForWeighment) Local Truck @endif
                </td>
                <td colspan="2">
                    @if($totalLocalTruckForWeighment)
                        {{$totalLocalTruckForWeighment}} X 2  X {{ $weighmentChargeLocal }}
                    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($totalLocalTruckForWeighment){{ number_format($totalweightmentChargesLocal,2)}}
                    @endif
                </td>
            </tr>
        @endif


        @if($NightTotalTruck && $NightCharge)
            <tr>
                <td></td>
                <td colspan="2">Night Charge<br></td>
                <td></td>
                <td></td>
                <td colspan="2">
                    @if($NightTotalTruck){{$NightTotalTruck}}
                    X {{ number_format($NightCharge,2)}}
                    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($NightTotalTruck)
                        {{ number_format($TotalNightCharge,2)}}
                    @endif
                </td>
            </tr>
        @endif

        @if($totalDocumentCharge)
            <tr>
                <td></td>

                <td colspan="3">Documentation Charge<br></td>
                <td></td>
                <td colspan="2">
                    @if($totalDocumentCharge)
                        {{$number_of_documents}}
                    @endif
                    X
                    @if($totalDocumentCharge)
                        {{$document_charge}}
                    @endif
                </td>
                <td colspan="2"></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($totalDocumentCharge)
                        {{$totalDocumentCharge}}
                    @endif
                </td>
            </tr>
        @endif

        @if($HolidayTotalTruck)
            <tr>
                <td></td>

                <td colspan="2">Holiday Charge<br></td>
                <td></td>
                <td></td>
                <td colspan="2">
                    @if($HolidayTotalTruck)
                        {{$HolidayTotalTruck}}
                        X
                        {{ number_format($HolidayCharge,2)}}
                    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{--<td>{{$TotalHolidayCharge}}</td>--}}
                <td class="amount-right">
                    @if($HolidayTotalTruck)
                    {{ number_format($TotalHolidayCharge,2)}}
                    @endif
                </td>
            </tr>
        @endif
        </tbody>


        <tfoot>

        <tr>
            <td colspan="12"><br><br></td>

        </tr>

        <tr>
            <td><br></td>
            <td></td>
            <td><br></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4">
                <b>Sub Total Taka:</b>
            </td>

            <th colspan="1" class="amount-right">
                {{ number_format($TotalAssessmentValue,2)}}
            </th>
        </tr>
        <tr>
            <th></th>

            <th colspan="2"></th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4">
                <b>VAT
                <span class="color-red">
                        @if($vat_flag == 1 || is_null($vat_flag))
                            (15%)
                        @else
                            (No VAT)
                        @endif
                </span></b>
            </td>
            <td colspan="1" class="amount-right">{{ $Vat!= 0 ? number_format($Vat,2) : null }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4">
                <br><b>Grand Total Taka:</b>
            </td>

            <th colspan="1" class="amount-right">

                -----------------------------------------------------------------------
                <br>{{ number_format($TotalAssessmentWithVat,2)}}</th>
        </tr>

        <tr>
            <td colspan="3">In word:<br></td>
            <th colspan="9">&nbsp;<span
                        style="text-transform: capitalize">{{convert_number_to_words($TotalAssessmentWithVat)." Taka only"}}</span><br>
            </th>
        </tr>
        </tfoot>
    </table>

</div>


<div style="width: 100%">
    <table style="text-align: left; margin-right: auto; margin-left: auto;">
        <tr>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
        </tr>
        <tr>
            <th>&nbsp;</th>
        </tr>

        <tr>
            <th>
               Prepared By
            </th>
        </tr>
        @if(isset($prepared_by) && $prepared_by != null)
            <tr>
                <td>
                    {{  $prepared_by }}
                </td>
            </tr>
        @endif
        @if(isset($prepared_by_designation) && $prepared_by_designation != null)
            <tr>
                <td>
                    {{  $prepared_by_designation }}
                </td>
            </tr>
        @endif
        @if(isset($prepared_by_organization) && $prepared_by_organization != null)
            <tr>
                <td>
                    {{  $prepared_by_organization }}
                </td>
            </tr>
        @endif
    </table>

</div>
</body>
</html>

@else
    <p style="text-align: center; color: red;">You Don't Have Permission To Get The Assessment PDF</p>
@endif




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