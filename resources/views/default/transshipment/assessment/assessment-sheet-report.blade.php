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

    <title>Assessment Report</title>
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

        .tble-warehouse tr td {
            border: 1px solid black;
        }

        .td-center {
            text-align: center !important;
        }

        .tab {
            border-collapse: collapse;
            width: 100%;

        }
        .tab tr, .tab th, .tab td{
            border: 1px solid black;
            padding: 1px;
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
            Assessment Sheet <b>{{$transshipment? ' (TransShipment)':''}}</b>

        </td>
        <td style="width: 19%; font-size: 14px;">
            <p style="vertical-align: top; text-align: right;"> <b>Serial No.:</b> {{ isset($yearly_serial) ? $yearly_serial : "" }} </p><br>
            <p style="vertical-align: bottom;"><b>Date:</b> {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}</p>
            {{--Print Date : {{$todayWithTime}}--}}
        </td>
    </tr>
    {{-- <tr>
        <td style="width: 15%"></td>
        <td style="width: 66%"></td>
        <td style="width: 19%  font-size: 14px; vertical-align: bottom;">
            <b>Serial No.:</b> {{ isset($yearly_serial) ? $yearly_serial : "" }}
        </td>
    </tr> --}}
</table>
<br>
{{--
<h5 style="text-align: right;padding-right: 35px;"> Date:{{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}</h5>
--}}


<br><br>
<b>
    <u>Assessment Details For Manifest: {{$manifestNo}}</u>
</b>


<div class="col-md-12" style="padding: 0;">

    <table style="box-shadow: 0px 0px 5px 1px darkgrey; width: 100%;">


        <tr>
            <td class="2">
                <b> Manifest/Export Application No & Date:</b>
            </td>

            <td class="2">
                <u> {{$manifestNo}}</u> <br> {{date('d-m-Y',strtotime($ManifestDate))}}
            </td>


            <td class="2">
                <b>Consignee:</b>
            </td>

            <td class="2">
                <p style="text-transform: capitalize"> {{$importer}}</p>
            </td>
        </tr>

        <tr>
            <td class="2">
                <b>Bill Of Entry No & Date:</b>
            </td>

            <td class="2"> {{--<span>C-</span>--}}
                <u> {{$bill_entry_no}}</u> <br>
                {{date('d-m-Y',strtotime($bill_entry_date))}}
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
            <td rowspan="2">
                <b>Custom's Release Order No & Date:</b>
            </td>

            <td rowspan="2"><span></span>
                <u> {{$custom_realise_order_No}}</u> <br> {{date('d-m-Y',strtotime($custom_realise_order_date))}}
            </td>


            <td>
                <b> C & F Agent:</b>
            </td>

            <td>
                {{$cnf_name?$cnf_name:''}}
            </td>
        </tr>

        <tr>

            <td><b>Shed / Yard No.</b></td>
            <td>{{$posted_yard_shed}}</td>
        </tr>


    </table>


</div>


<div class="" style="padding: 0; ">

    <table style="width: 100%;" {{-- class="tab" --}}>
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
        <tr style="">
            <td></td>
            <td colspan="2">Description of goods<br></td>
            <td colspan="2">{{--<span>Items= <b>{{ $totalItems }}</b> </span> <br>--}}
                {{$description_of_goods}}
            </td>
            <td colspan="7" rowspan="5" style="vertical-align: top;font-size: 13px; ">
                <table border="0" width="100%" class="tble-warehouse" >
                    <tr>
                        <td width="15%"><b>Item</b></td>
                        <td width="20%"><b>Basis Of Charge</b></td>
                        <td width="10%"><b>Slab</b></td>
                        <td width="12%"><b>Quantity</b></td>
                        <td width="10%" style="text-align: center"><b>Day</b></td>
                        <td width="17%"><b>Rate</b></td>
                        <td width="15%" class="amount-right"><b>Amount</b></td>
                    </tr>
                    @if(isset($item_wise_charge) && !empty($item_wise_charge) && $WareHouseRentDay>0)
                        @foreach($item_wise_charge as $key => $item)
                            <tr>

                                <td>
                                    <span style="text-transform: capitalize">{{ $item->Description }}</span>
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
                                <td colspan="5" style="font-size: 12px">
                                    <table border="0" width="100%">
                                        @php($danger=1)
                                        @if($firstSlabDay || $secondSlabDay || $thirdSlabDay )
                                            <tr>
                                                <td style="width: 14%"><b>1st</b></td>
                                                <td style="width: 15%">
                                                        {{$item->item_quantity }}
                                                    X
                                                </td>
                                                <td style="width: 14% ; text-align: center">{{ $firstSlabDay }} X</td>
                                                <td style="width: 22%">
                                                    {{ $item->first_slab }}
                                                    @if( $item->dangerous=='1')
                                                        <span> X {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>
                                                    @endif

                                                </td>

                                                <td style="text-align: right; width: 20%">
                                                        <b>={{number_format(ceil($item->item_quantity * $firstSlabDay * $danger * $item->first_slab),2)}}</b>
                                                </td>
                                            </tr>
                                        @endif

                                        @if( $secondSlabDay || $thirdSlabDay )
                                            <tr>
                                                <td><b>2nd</b></td>
                                                <td>{{$item->item_quantity }}
                                                    X
                                                </td>
                                                <td style="text-align: center"> {{ $secondSlabDay }} X</td>
                                                <td>
                                                    {{-- <span style="display: none"> @{{ item.dangerous=='1' ? danger=2 : 1 }}</span>--}}
                                                    {{ $item->second_slab }}
                                                    @if( $item->dangerous=='1')
                                                        <span> X {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>
                                                    @endif

                                                </td>
                                                <td style="text-align: right">
                                                        <b>={{number_format(ceil($item->item_quantity * $secondSlabDay  *$danger* $item->second_slab),2)}}</b>
                                                </td>
                                            </tr>
                                        @endif

                                        @if($thirdSlabDay )
                                            <tr>
                                                <td style=""><b>3rd</b></td>
                                                <td> {{$item->item_quantity }}
                                                    X
                                                </td>
                                                <td style="text-align: center">{{ $thirdSlabDay }} X</td>
                                                <td>
                                                    {{$item->third_slab}}
                                                    @if( $item->dangerous=='1')
                                                        <span> X {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>
                                                    @endif
                                                </td>
                                                <td style="text-align: right">
                                                        <b>={{number_format(ceil($item->item_quantity * $thirdSlabDay * $danger * $item->third_slab),2)}}</b>
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">No Of Package<br></td>
        <!--td colspan="2">{{$package_no ? $package_no:'' }} {{$package_type ? $package_type:''}}</td-->
            <td colspan="2">{{$package_no ? $package_no:'' }}</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">Basis Of Charge<br></td>
            <td colspan="2">{{$chargeable_ton}} Kgs</td>
        </tr>
        <tr style="">
            <td></td>
            <td colspan="2">Date of Unloading<br></td>
            <td colspan="2">
                <nobr> 
                    @if($partial_status == 1)
                        {{ $receive_date != null ? date('d-m-Y H:i:s',strtotime($receive_date)) : 'Truck To Truck' }}
                    @else
                        {{ $receive_date != null ? date('d-m-Y',strtotime($receive_date)) : 'Truck To Truck' }}(Partial)
                    @endif
                </nobr>
            </td>
        </tr>
        <tr style="">
            <td></td>
            <td colspan="2">Free Period<br></td>
        {{-- <td colspan="2">

                @if(!$partial_status)
                {{date('d-m-Y',strtotime($receive_date) )  }}
                    - {{date('d-m-Y',strtotime($FreeEndDate))}}
             = FT
                @endif
                </td --}}
            <td colspan="2">
                @if($partial_status==1)
                    {{$FreeEndDate}}
                @endif
            </td>

        </tr>
        <tr>
            <td></td>
            <td colspan="2">Rent Due Period</td>
            <td colspan="3">
                @if ($WareHouseRentDay>0)
                    {{ 
                        $RentDay
                    }}

                @else
                    'No Rent'
                @endif

            </td>

            <td colspan="2">
                <b>W.R Total</b>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td class="amount-right">
                {{number_format($TotalSlabCharge,2)}}
            </td>

        </tr>
        <tr>
            <th>2.</th>
            <th colspan="2">Handling Operation:<br></th>
            <th colspan="2">Handling Mode</th>
            <th colspan="3">Ton X Rate</th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @if($assessments_perishable_flag != '1')

        @if($OffloadLabour || $OffLoadingEquip )
            <tr>
                <td></td>
                <td rowspan="2"></td>
                <td rowspan="2" style="vertical-align: middle">Unloading<br></td>
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

            <tr >
                <td></td>
                <td colspan="2">@if($OffLoadingEquip) Equipment @else &nbsp; @endif</td>
                <td colspan="3">
                    @if($OffLoadingEquip)
                        {{$OffLoadingEquip}} X {{$OffLoadingEquipCharge}}
                        @if($offloadEquipShiftingFlag)X <span>2</span>@endif
                    @endif
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($OffLoadingEquip){{ number_format($TotalForOffloadEquip,2)}} @endif
                </td>
            </tr>
        @endif

        @if(($loadLabour || $loadEquip ) && !(!$OffloadLabour && !$OffLoadingEquip))

            <tr>
                <td></td>
                <td rowspan="2"></td>
                <td rowspan="2">Loading</td>
                <td colspan="2">
                    Manual
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
                    {{--@if($loadLabour && !$transshipment )--}}
                    @if($loadLabour )
                        {{ number_format($TotalForloadLabour,2)}}
                    @endif
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2"> @if($loadEquip) Equipment  @endif</td>
                <td colspan="3">
                    @if($loadEquip)
                        {{$loadEquip}} X {{$loadingEquipCharge}}  @if($loading_shifting)X 2 @endif
                    @endif

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

        @else
        {{--<tr>
            <td></td>
            <td rowspan="2">(</td>
            <td rowspan="2"> Re-Stacking</td>
            <td colspan="2">
                 Manual
            </td>
            <td colspan="2">

            </td>
            <td colspan="2"></td>
            <td></td>
            <td></td>
            <td class="amount-right">

            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">Equipment</td>
            <td colspan="2">

            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="amount-right">

            </td>
        </tr>

        <tr>
            <td></td>
            <td rowspan="2"></td>
            <td rowspan="2"> Removal</td>
            <td colspan="2">
                (a) Manual
            </td>
            <td colspan="2">

            </td>
            <td colspan="2"></td>
            <td></td>
            <td></td>
            <td class="amount-right">

            </td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2">Equipment</td>
            <td colspan="2">

            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="amount-right">

            </td>
        </tr>--}}



        @if($transshipment && (!$OffloadLabour && !$OffLoadingEquip))
            <tr >
                <td></td>
                <td rowspan="2"></td>
                <td rowspan="2">Transhipment</td>
                <td colspan="2">
                     @if($loadLabour) Manual @endif
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
                @if($loadLabour)
                    {{ number_format($TotalForloadLabour,2)}}
                @endif
                </td>
            </tr>

            <tr >
                <td></td>
                <td colspan="2"> @if($loadEquip) Equipment  @endif</td>
                <td colspan="3">
                    @if($loadEquip)
                        {{$loadEquip}} X {{$loadingEquipCharge}}  @if($loading_shifting)X 2 @endif
                    @endif

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
        @endif



        <tr>
            <th>3.</th>
            <th colspan="2">Other Dues<br></th>
            <th colspan="2">{{--Quantity--}}</th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="2">{{--Truck Type--}}<br></td>
            <td colspan="2">Truck X Rate</td>
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
            <td colspan="2">
                @if($entranceTotalForeignTruck>0)
                    Foreign Truck
                @else
                    &nbsp;
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
            <td colspan="2">
                @if($entranceTotalLocalTruck>0)
                    Local Truck
                @else
                    &nbsp;
                @endif
            </td>
            
            <td colspan="2">
                @if($entranceTotalLocalTruck>0)
                    {{$entranceTotalLocalTruck}} X {{$entranceFeeLocal}}
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
            <td colspan="2">
                @if($entranceTotalLocalVan>0)
                    Local Van
                @else
                    &nbsp;
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

            <tr>
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
            </tr>
            <tr>
                <td></td>
                <td colspan="2" rowspan="2">Carpenter Charge<br></td>
                <td colspan="2">
                    Opening/Closing<br>
                </td>
                <td class="td-center">
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
                <td colspan="2">Repair</td>
                <td class="td-center">
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

                <td colspan="2" rowspan="3"> Haltage Charge<br></td>
                <td colspan="2">Type</td>
                <td class="td-center">Number</td>
                <td colspan="2"> Day X Rate</td>
                <td></td>
                <td></td>
                <td></td>
                <td class="amount-right"></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                    @if($haltage_truck_foreign)
                        Foreign
                    @else
                        &nbsp;
                    @endif
                </td>
                <td class="td-center">
                    @if($haltage_truck_foreign)
                        {{$haltage_truck_foreign}}
                    @endif
                </td>
                <td colspan="2">
                    @if($haltage_truck_foreign)
                        &nbsp;&nbsp;{{number_format($haltage_day_foreign,0)}} X {{$haltage_charge_foreign}}
                    @endif
                </td>

                <td></td>
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
                        Local
                    @else
                        &nbsp;
                    @endif
                </td>
                <td class="td-center">
                    @if($haltage_truck_local)
                        {{$haltage_truck_local}}
                    @endif
                </td>
                <td colspan="2">
                    @if($haltage_truck_local)
                        &nbsp;&nbsp;{{number_format($haltage_day_local,0)}} X {{$haltage_charge_local}}
                    @endif
                </td>

                <td></td>
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
            <tr >
                <td></td>
                <td rowspan="3" colspan="4"> Weighment Charge<br></td>
                <td>{{--Truck--}}</td>
                <td>Total</td>
                <td>Twice</td>
                <td>Charge</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr >
                <td></td>
                <td>Foreign</td>
                <td>{{$totalForeignTruckForWeighment}}</td>
                <td>2</td>
                <td>{{$weighmentChargeForeign}}</td>
                {{--<td>{{$TotalweightmentChargesForeign}}</td>--}}
                <td></td>
                <td></td>
                <td class="amount-right">
                    {{ number_format($totalweightmentChargesForeign,2)}}
                </td>
            </tr>
            <tr {{--style="background-color:  yellow"--}}>
                <td></td>
                <td>Local</td>
                <td>@if($totalLocalTruckForWeighment) {{$totalLocalTruckForWeighment}}@endif</td>
                <td>@if($totalLocalTruckForWeighment)2 @endif</td>
                <td> @if($totalLocalTruckForWeighment){{$weighmentChargeLocal}} @endif</td>
                <td></td>
                <td></td>
                <td class="amount-right">
                    @if($totalLocalTruckForWeighment){{ number_format($totalweightmentChargesLocal,2)}} @endif
                </td>
            </tr>
        @endif

        @if($NightTotalTruck && $NightCharge)
        <tr>
            <td></td>

            <td colspan="3" class="td-center">{{--Charge Type<--}}<br></td>
            <td></td>
            <td class="td-center">Number X</td>
            <td>Rate</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        
            <tr>
                <td></td>

                <td colspan="2">Night Charge<br></td>
                <td></td>
                <td></td>
                <td class="td-center">@if($NightTotalTruck){{$NightTotalTruck}}@endif</td>
                {{--<td>{{$NightCharge}}</td>--}}
                <td>@if($NightTotalTruck){{ number_format($NightCharge,2)}}@endif</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{--<td>{{$TotalNightCharge}}</td>--}}
                <td class="amount-right">@if($NightTotalTruck){{ number_format($TotalNightCharge,2)}}@endif</td>
            </tr>
        @endif

        @if($totalDocumentCharge)
            <tr>
                <td></td>

                <td colspan="3">Documentation Charge<br></td>
                <td></td>
                <td class="td-center">
                    @if($totalDocumentCharge)
                        {{$number_of_documents}}
                    @endif
                </td>
                <td>
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
                <td class="td-center">
                    @if($HolidayTotalTruck)
                        {{$HolidayTotalTruck}}
                    @endif
                </td>
                <td>@if($HolidayTotalTruck){{ number_format($HolidayCharge,2)}}@endif</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{--<td>{{$TotalHolidayCharge}}</td>--}}
                <td class="amount-right">@if($HolidayTotalTruck){{ number_format($TotalHolidayCharge,2)}}@endif</td>
            </tr>
        @endif




        {{--
                <tr>
                    <td></td>
                    <td></td>
                    <td>Outstanding Bill<br></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>4.<br></th>
                    <td></td>
                    <th colspan="3">Equipment Hire Period Distance Rate<br></th>

                    <td></td>
                    <td></td>
                    <th colspan="2"></th>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Charge</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Mobile Crane<br></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Forklift</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Tarpaulin</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td colspan="2">Miscellaneous Charge<br>(To be specified)<br></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>--}}
        <tr>
            <td><br></td>
            <td></td>
            <td><br></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="3">
                <br><b>Sub Total Taka:</b>
            </td>

            <th colspan="2" class="amount-right">
                {{ number_format($TotalAssessmentValue,2)}}
            </th>
        </tr>
        <tr>
            <th>4.</th>

            <th colspan="2">VAT
                <span class="color-red">
                        @if($vat_flag == 1 || is_null($vat_flag))
                            (15%)
                        @else
                            (No VAT)
                        @endif
                </span>
            </th>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="4" class="amount-right">{{ $Vat!= 0 ? number_format($Vat,2) : null }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td colspan="3">
                <br><b>Grand Total Taka:</b>
            </td>

            <th colspan="2" class="amount-right">

                {{ number_format($TotalAssessmentWithVat,2)}}</th>
        </tr>

        <tr>
            <td colspan="3">In word:<br></td>
            <th colspan="9">&nbsp;<span
                        style="text-transform: capitalize">{{convert_number_to_words($TotalAssessmentWithVat)." Taka only"}}</span><br>
            </th>
        </tr>
    </table>

</div>


<div style="width: 100%">
    <br><br> <br><br>
    <table style="width:100%">
        <tr {{--style="background-color: yellow"--}}>
            <td>
                {{--<span>Signature Of </span><br>--}}
                {{-- <span>Warehouse Superintendent</span> --}}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;

            </td>

            <td>
                {{--<span>Signature Of </span><br>--}}
                <span>Created By</span>

            </td>

            <td>
                {{--<span></span><br>--}}
                <span>Checked & Verified By</span>
            </td>
            <td>
                {{--<span>Signature Of </span><br>--}}
                {{-- <span>Warehouse Superintendent</span> --}}
                {{-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
            </td>
        </tr>

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