<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<title>Challan</title>
	<style>
		.tab {
			border-collapse: collapse;
			width: 100%;

		}
        .tab tr, .tab th, .tab td{
            border: 1px solid black;
            padding: 1px;
        }
        .tab th {
            text-align: center;
        }
        .tab tr td {
            text-align: left;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 500px;
        }
        .tabInfo {
        	width: 100%;
        	padding-top: 20px;
        }
        .amount-right{
            text-align: right !important;

        }
        #warehouse-charge tr td,th{
            /*border: none !important;*/
        }
        #item-wise-carhge tr td  {
            border: none !important;
        }


        html{
            margin: 22px 50px 0px 10px;
            padding:12px 10px;
        }
    </style>
</head>
<body>
	<img src="../public/img/blpa.jpg">
	<p class="center">
		<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
		<span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
		Warehouse Charges Challan
	</p>
 	<table class="tabInfo">
		<tr>
			<th>
				Name of Consignee:
			</th>
			<td>
				{{count($manifestReport) ? $manifestReport[0]->consignee : ""}}
			</td>
			<td>
				<b>A/C. Goods under BCP Entry/Manifest No.:</b>
			</td>
			<td>
				{{count($manifestReport) ? $manifestReport[0]->manifest : ""}}
			</td>
			<th>
				Dated:
			</th>
			<td colspan="3">
				{{count($manifestReport) ? $manifestReport[0]->manifest_date : ""}}
			</td>
		</tr>
		<tr>
			<th>
				Consigner:
			</th>
			<td>
				{{count($manifestReport) ? $manifestReport[0]->consigner : ""}}
			</td>
			<th>
				B/E or E/A No:
			</th>
			<td>
				{{count($manifestReport) ? $manifestReport[0]->bill_of_entry_no : ""}}
			</td>
			<th>
				Dated:
			</th>
			<td>
				{{count($manifestReport) ? $manifestReport[0]->bill_of_entry_date : ""}}
			</td>
            <th>
                Challan No:
            </th>
            <td>
                {{--{{count($CallanNo) ? $CallanNo: ""}}--}}
                {{count($CallanNoCheck) ? $CallanNoCheck[0]->challan_no: ""}}
            </td>
		</tr>
		<tr>
			<th>
				Address:
			</th>
			<td>
				{{count($manifestReport) ? $manifestReport[0]->consigner : ""}}
			</td>
			<th>
				A/C. Shed/Yard No:
			</th>
			<td colspan="3">
				{{count($manifestReport) ? $manifestReport[0]->posted_yard_shed : ""}}
			</td>
            <th>
                Date:
            </th>
            <td>
                {{$todayWithTime}}
            </td>
		</tr>
    </table>
    <table class="tab">
    <thead>
        <tr>
            <th rowspan="2">
                Name &amp; Address of C&amp;F<br>
                Agent/Party making payment
            </th>
            <th rowspan="2">
                SL<br>
                No.
            </th>
            <th rowspan="2" style="width: 180px;">
                Particulars of Charges
            </th>
            <th rowspan="2">
                Description of<br>
                Goods/Services
            </th>
            <th rowspan="2">
                Quantity<br>
                Pkgs
            </th>
            <th rowspan="2">
                Weight
            </th>
            <th colspan="3">
                PERIOD
            </th>
            <th rowspan="2" width="110px;">
                Basis of<br>
                Charges
            </th>
            <th colspan="2">
                RATE
            </th>
            <th colspan="2">
                AMOUNT
            </th>
        </tr>
        <tr>
            <th>From</th>
            <th>To</th>
            <th>Total<br>Days</th>
            <th>Shed</th>
            <th>Yard</th>
            <th>Taka</th>
            <th>Ps.</th>
        </tr>
    </thead>
    @php
    	$totalAmount = 0;
        $tickMark = '<span style="font-family: DejaVu Sans, sans-serif;">✓</span>';
    @endphp
    <tbody>
        <tr>
            <td rowspan="17">
            	<br><br><br><br><br><br><br><br><br><br><br>
                MODE OF<br>
                PAYMENT/<br>
                Cash/Pay-order/<br>
                Draft/Cheque/<br>
                Credit Note
            </td>
            <td rowspan="3">
                <b>1.</b>
            </td>
            <td rowspan="3">
                Warehouse Rent<br>
                Shed/Yard
            </td>
            <td rowspan="3">  {{-----------------GOODS NAME FROM Manifest--------------------}}
                {{ count($goodsNameTotalPkgMaxNet) ? $goodsNameTotalPkgMaxNet[0]->description_of_goods : "" }}
            </td>
            <td rowspan="3">  {{----------------From Manifest--------------------}}
                {{ count($goodsNameTotalPkgMaxNet) ? $goodsNameTotalPkgMaxNet[0]->package_no : "" }}
            </td>
            <td rowspan="3">   {{--------------MAX NETWEIGHT BY WEIGHTBRIDGE
                                OR Manifest Grossweight--------------}}
                {{ count($goodsNameTotalPkgMaxNet) ? $goodsNameTotalPkgMaxNet[0]->max_Net_Weight : "" }}
            </td>
            {{-----------------PERIOD TO AMOUNT--------------------}}
            {{--FIRST SLAB--}}
            <td>

            </td>
            <td>

            </td>
            <td>

            </td>
            <td rowspan="3" id="warehouse-charge" colspan="3" style="font-size: 10px; height:auto; width: auto">
                <table border="0">
                    <tr>
                        <th style="width: 100px">Item</th>
                        <td style="width: 30px"><b>Quantity</b></td>
                        <td><b>Slab</b></td>
                        <th style="width: 120px">Charge <br> Unit X Day X Rate (X 200%)</th>
                    </tr>

                    @foreach($item_wise_charge as $key => $item)
                        <tr>

                            <td><span style="text-transform: capitalize">{{ $item->Description }}</span>
                                @if($item->dangerous=='1') <span><b>({{ $item->dangerous=='1' ? '200%':''}}</b>)</span>@endif
                            </td>
                            <td style="vertical-align: middle;text-align: center">{{ $item->item_quantity}}</td>
                            <td colspan="2">
                                <table id="item-wise-carhge" border="0" width="100%" style="border: none">

                                    @php($danger=1)

                                    @if($firstSlabDay || $secondSlabDay || $thirdSlabDay )
                                        <tr>
                                            <td style=""><b>1St Slab</b></td>
                                            <td style="text-align: right">
                                                {{$item->item_quantity }} X {{ $firstSlabDay }} X {{ $item->first_slab }} @if( $item->dangerous=='1')<span> X  {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>@endif
                                                <b> ={{number_format(ceil($item->item_quantity * $firstSlabDay * $danger * $item->first_slab),2)}}</b>
                                            </td>
                                        </tr>
                                    @endif

                                    @if( $secondSlabDay || $thirdSlabDay )
                                        <tr>
                                            <td style=""><b>2nd Slab</b></td>
                                            <td style="text-align: right">
                                                 <span style="display: none"> @{{ item.dangerous=='1' ? danger=2 : 1 }}</span>
                                                {{ $item->item_quantity }} X {{ $secondSlabDay }} X {{ $item->second_slab }} @if( $item->dangerous=='1') <span> X  {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>@endif
                                                <b>={{number_format(ceil($item->item_quantity * $secondSlabDay  *$danger* $item->second_slab),2)}}</b>
                                            </td>
                                        </tr>
                                    @endif

                                    @if($thirdSlabDay )
                                        <tr>
                                            <td style=""><b>3rd Slab</b></td>
                                            <td style="text-align: right">

                                                {{ $item->item_quantity }} X {{ $thirdSlabDay }} X {{$item->third_slab}} @if( $item->dangerous=='1')<span> X  {{ $item->dangerous=='1' ?  $danger=2 : $danger=1}}</span>@endif
                                                <b>={{number_format(ceil($item->item_quantity * $thirdSlabDay * $danger * $item->third_slab),2)}}</b>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </td>

                        </tr>

                    @endforeach
                </table>












            </td>


            <td class="amount-right">

            </td>
            <td class="amount-right">

            </td>
            {{--FIRST SLAB--}}
        </tr>
        <tr>
            {{--Second SLAB--}}
            <td></td>
            <td></td>
            <td>

            </td>



            <td class="amount-right">

            </td>
            <td class="amount-right">

            </td>
            {{--Second SLAB--}}
        </tr>
        <tr>
            {{--Third SLAB--}}
            <td></td>
            <td></td>
            <td>

            </td>
            @php
            $TotalSlabCharge = number_format($TotalSlabCharge,2,'.','');//number_format($TotalSlabCharge,2); 
             if(isset($TotalSlabCharge)) {
                    list($WareHousetk, $WareHouseps) = explode(".", $TotalSlabCharge);
                }   
            @endphp


            <td class="amount-right" style="vertical-align: bottom">
                {{ isset($WareHousetk) ? $WareHousetk : "" }}
                

              
            </td>
            <td class="amount-right" style="vertical-align: bottom">
                {{ isset($WareHouseps) ? $WareHouseps : "" }}
            </td>


        </tr>
         @php 
            $totalAmount += $TotalSlabCharge; 
         @endphp
        {{-----------------PERIOD TO AMOUNT--------------------}}
        {{-----------------OffLoading--------------------}}
        <tr>
            <td>
                <b>2.</b>
            </td>
            <td>
                <b>Off Loading</b><br>
                {!! count($offLoadingLabour) ? ($offLoadingLabour[0]->tcharge != 0 ? $tickMark : "") : "" !!}
                a) By Manual Labour<br>
                {!! count($offLoadingEquipment) ? ($offLoadingEquipment[0]->tcharge != 0 ? $tickMark : "") : "" !!}
                b) By BLPA Equipment
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                {!! count($offLoadingLabour) ? ($offLoadingLabour[0]->tcharge != 0 ? $offLoadingLabour[0]->unit." X ".$offLoadingLabour[0]->charge_per_unit.(count($offLoadingLabour) && count($offLoadingEquipment) ? "<br>".(count($offLoadingEquipment) ? ($offLoadingEquipment[0]->tcharge != 0 ? $offLoadingEquipment[0]->unit." X ".$offLoadingEquipment[0]->charge_per_unit: "") : "") : "") : "".(count($offLoadingEquipment) ? ($offLoadingEquipment[0]->tcharge != 0 ? $offLoadingEquipment[0]->unit." X ".$offLoadingEquipment[0]->charge_per_unit: "") : "")) : "".(count($offLoadingEquipment) ? ($offLoadingEquipment[0]->tcharge != 0 ? $offLoadingEquipment[0]->unit." X ".$offLoadingEquipment[0]->charge_per_unit: "") : "") !!}
            </td>
            <td></td>
            <td></td>
            @php
                if(count($offLoadingLabour)) {
                    if($offLoadingLabour[0]->tcharge != 0) {
                        list($offLotaka, $offLoFTps) = explode(".", $offLoadingLabour[0]->tcharge);
                        $totalAmount += $offLoadingLabour[0]->tcharge;
                    }
                }
                if(count($offLoadingEquipment)) {
                    if($offLoadingEquipment[0]->tcharge != 0) {
                        list($offEqtaka, $offEqFTps) = explode(".", $offLoadingEquipment[0]->tcharge);
                        $totalAmount += $offLoadingEquipment[0]->tcharge;
                    }
                }
            @endphp
            <td class="amount-right">
                {!! isset($offLotaka) ? $offLotaka.(isset($offLotaka) && isset($offEqtaka) ? "<br>".(isset($offEqtaka) ? $offEqtaka : "") : "") : "".(isset($offEqtaka) ? $offEqtaka : "") !!}
                {{--<td class="amount-right">{{ number_format($offEqtaka,2)}}</td>--}}

            </td>
            <td class="amount-right">
                {!! isset($offLoFTps) ? $offLoFTps.(isset($offLoFTps) && isset($offEqFTps) ? "<br>".(isset($offEqFTps) ? $offEqFTps : "") : "") : "".(isset($offEqFTps) ? $offEqFTps : "") !!}
            </td>
        </tr>
        {{-----------------OffLoading--------------------}}
        {{-----------------Loading--------------------}}
        <tr>
            <td>
                <b>3.</b>
            </td>
            <td>
                <b>Loading</b><br>
                {!! count($loadingLabour) ? ($loadingLabour[0]->tcharge != 0 ? $tickMark : "") : "" !!}
                a) By Manual Labour<br>
                {!! count($loadingEquip) ? ($loadingEquip[0]->tcharge != 0 ? $tickMark : "") : "" !!}
                b) By BLPA Equipment
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                {!! count($loadingLabour) ? ($loadingLabour[0]->tcharge != 0 ? $loadingLabour[0]->unit." X ".$loadingLabour[0]->charge_per_unit.(count($loadingLabour) && count($loadingEquip) ? "<br>".(count($loadingEquip) ? ($loadingEquip[0]->tcharge != 0 ?$loadingEquip[0]->unit." X ".$loadingEquip[0]->charge_per_unit : "") : "") : "") : "".(count($loadingEquip) ? ($loadingEquip[0]->tcharge != 0 ?$loadingEquip[0]->unit." X ".$loadingEquip[0]->charge_per_unit : "") : "")) : "".(count($loadingEquip) ? ($loadingEquip[0]->tcharge != 0 ?$loadingEquip[0]->unit." X ".$loadingEquip[0]->charge_per_unit : "") : "") !!}
            </td>
            <td></td>
            <td></td>
            @php
                if(count($loadingLabour)) {
                    if($loadingLabour[0]->tcharge != 0) {
                        list($loLataka, $loLaps) = explode(".", $loadingLabour[0]->tcharge);
                        $totalAmount += $loadingLabour[0]->tcharge;
                    }
                }
                if(count($loadingEquip)) {
                    if($loadingEquip[0]->tcharge != 0) {
                        list($loEqtaka, $loEqFTps) = explode(".", $loadingEquip[0]->tcharge);
                        $totalAmount += $loadingEquip[0]->tcharge;
                    }
                }
            @endphp
            <td class="amount-right">
                {!! isset($loLataka) ? $loLataka.(isset($loLataka) && isset($loEqtaka) ? "<br>".(isset($loEqtaka) ? $loEqtaka : "") : "") : "".(isset($loEqtaka) ? $loEqtaka : "") !!}
            </td>
            <td class="amount-right">
                {!! isset($loLaps) ? $loLaps.(isset($loLaps) && isset($loEqFTps) ? "<br>".(isset($loEqFTps) ? $loEqFTps : "") : "") : "".(isset($loEqFTps) ? $loEqFTps : "") !!}
            </td>
        </tr>
        {{-----------------Loading--------------------}}
        <tr>
            <td>
                <b>4.</b>
            </td>
            <td>
                <b>Re-stacking / Removal /<br>
                Transhipment</b><br>
                a) By Manual Labour<br>
                b) By BLPA Equipment
            </td>
            <td></td>
            <td></td>
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
        {{----------------FOREIGN TRUCK CHARGES------------------}}
        <tr>
            <td>
                <b>5.</b>
            </td>
            <td>
                <b>Other</b><br>
                a) F/T
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
            	{{ count($foreignTruck) ? ($foreignTruck[0]->tcharge != 0 ? $foreignTruck[0]->unit." X ".$foreignTruck[0]->charge_per_unit : "") : "" }}
            </td>
            <td></td>
            <td></td>
            @php
            	if(count($foreignTruck)) {
                    if($foreignTruck[0]->tcharge != 0) {
                        list($FTtaka, $FTps) = explode(".", $foreignTruck[0]->tcharge);
                        $totalAmount += $foreignTruck[0]->tcharge; 
                    }
            	}
			@endphp
            <td class="amount-right">{{ isset($FTtaka) ? $FTtaka : "" }}</td>

            <td class="amount-right">{{ isset($FTps) ? $FTps : "" }}</td>
        </tr>
        {{----------------FOREIGN TRUCK CHARGES------------------}}
        {{----------------LOCAL TRUCK CHARGES------------------}}
        <tr>
            <td></td>
            <td>
                b) L/T
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
            	{{ count($localTruck) ? ($localTruck[0]->tcharge != 0 ? $localTruck[0]->unit." X ".$localTruck[0]->charge_per_unit : "") : "" }}
            </td>
            <td></td>
            <td></td>
            @php
            	if(count($localTruck)) {
                    if($localTruck[0]->tcharge != 0) {
                        list($LTtaka, $LTps) = explode(".", $localTruck[0]->tcharge);
                        $totalAmount += $localTruck[0]->tcharge; 
                    }
            	}
			@endphp
            <td class="amount-right">{{ isset($LTtaka) ? $LTtaka : "" }}</td>

            <td class="amount-right">{{ isset($LTps) ? $LTps : "" }}</td>
        </tr>
        {{----------------LOCAL TRUCK CHARGES------------------}}
        {{----------------CARPENTER CHARGES------------------}}
        <tr>
            <td></td>
            <td>
                c) C/C
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
            	{!! count($carpenterChargesOpenningOrClosing) ? ($carpenterChargesOpenningOrClosing[0]->tcharge != 0 ? $carpenterChargesOpenningOrClosing[0]->unit." X ".$carpenterChargesOpenningOrClosing[0]->charge_per_unit.(count($carpenterChargesOpenningOrClosing) && count($carpenterChargesRepair) ? "<br>".(count($carpenterChargesRepair) ? ($carpenterChargesRepair[0]->tcharge != 0 ? $carpenterChargesRepair[0]->unit." X ".$carpenterChargesRepair[0]->charge_per_unit : "") : "") : "") : "".(count($carpenterChargesRepair) ? ($carpenterChargesRepair[0]->tcharge != 0 ? $carpenterChargesRepair[0]->unit." X ".$carpenterChargesRepair[0]->charge_per_unit : "") : "")) : "".(count($carpenterChargesRepair) ? ($carpenterChargesRepair[0]->tcharge != 0 ? $carpenterChargesRepair[0]->unit." X ".$carpenterChargesRepair[0]->charge_per_unit : "") : "") !!}
            </td>
            <td></td>
            <td></td>
            @php
            	if(count($carpenterChargesOpenningOrClosing)) {
                    if($carpenterChargesOpenningOrClosing[0]->tcharge != 0) {
                        list($CCOCtaka, $CCOCps) = explode(".", $carpenterChargesOpenningOrClosing[0]->tcharge);
                        $totalAmount += $carpenterChargesOpenningOrClosing[0]->tcharge;
                    }
            		
            	}
                if(count($carpenterChargesRepair)) {
                    if($carpenterChargesRepair[0]->tcharge != 0) {
                        list($CCRtaka, $CCRps) = explode(".", $carpenterChargesRepair[0]->tcharge);
                        $totalAmount += $carpenterChargesRepair[0]->tcharge;
                    }
                }
			@endphp
            <td class="amount-right">
                {!! isset($CCOCtaka) ? $CCOCtaka.(isset($CCOCtaka) && isset($CCRtaka) ? "<br>".(isset($CCRtaka) ? $CCRtaka : "") : "") : "".(isset($CCRtaka) ? $CCRtaka : "") !!}
               {{--  {!! isset($CCOCtaka) && isset($CCRtaka) ? "<br>" : "" !!}
                {{ isset($CCRtaka) ? $CCRtaka : "" }} --}}
            </td>
            <td class="amount-right">
                {!! isset($CCOCps) ? $CCOCps.(isset($CCOCps) && isset($CCRps) ? "<br>".(isset($CCRps) ? $CCRps : "") : "") : "".(isset($CCRps) ? $CCRps : "") !!}
            </td>
        </tr>
        {{----------------CARPENTER CHARGES------------------}}
        {{----------------HOLIDAY CHARGES------------------}}
        <tr>
            <td></td>
            <td>
                d) Holiday Charge
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
            	{!! count($holidayChargesFT) ? ($holidayChargesFT[0]->tcharge != 0 ? $holidayChargesFT[0]->unit." X ".$holidayChargesFT[0]->charge_per_unit.(count($holidayChargesFT) && count($holidayChargesLT) ? "<br>".(count($holidayChargesLT) ? ($holidayChargesLT[0]->tcharge != 0 ? $holidayChargesLT[0]->unit." X ".$holidayChargesLT[0]->charge_per_unit : "") : "") : "") : "".(count($holidayChargesLT) ? ($holidayChargesLT[0]->tcharge != 0 ? $holidayChargesLT[0]->unit." X ".$holidayChargesLT[0]->charge_per_unit : "") : "")) : "".(count($holidayChargesLT) ? ($holidayChargesLT[0]->tcharge != 0 ? $holidayChargesLT[0]->unit." X ".$holidayChargesLT[0]->charge_per_unit : "") : "") !!}
            </td>
            <td></td>
            <td></td>
            @php
            	if(count($holidayChargesFT)) {
                    if($holidayChargesFT[0]->tcharge != 0) {
                        list($HFTtaka, $HFTps) = explode(".", $holidayChargesFT[0]->tcharge);
                        $totalAmount += $holidayChargesFT[0]->tcharge;
                    }
            	}
                if(count($holidayChargesLT)) {
                    if($holidayChargesLT[0]->tcharge != 0) {
                        list($HLTtaka, $HLTps) = explode(".", $holidayChargesLT[0]->tcharge);
                        $totalAmount += $holidayChargesLT[0]->tcharge;
                    }
                }
			@endphp
            <td class="amount-right">
                {!! isset($HFTtaka) ? $HFTtaka.(isset($HFTtaka) && isset($HLTtaka) ? "<br>".(isset($HLTtaka) ? $HLTtaka : "") : "") : "".(isset($HLTtaka) ? $HLTtaka : "") !!}
            </td>
            <td class="amount-right">
                {!! isset($HFTps) ? $HFTps.(isset($HFTps) && isset($HLTps) ? "<br>".(isset($HLTps) ? $HLTps : "") : "") : "".(isset($HLTps) ? $HLTps : "") !!}
            </td>
        </tr>
        {{----------------HOLIDAY CHARGES------------------}}
        {{----------------NIGHT CHARGES------------------}}
        <tr>
            <td></td>
            <td>
                e) Night Charge
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
            	{!! count($nightChargesFT) ? ($nightChargesFT[0]->tcharge != 0 ? $nightChargesFT[0]->unit." X ".$nightChargesFT[0]->charge_per_unit.(count($nightChargesFT) && count($nightChargesLT) ? "<br>".(count($nightChargesLT) ? ($nightChargesLT[0]->tcharge != 0 ? $nightChargesLT[0]->unit." X ".$nightChargesLT[0]->charge_per_unit : "") : "") : "") : "".(count($nightChargesLT) ? ($nightChargesLT[0]->tcharge != 0 ? $nightChargesLT[0]->unit." X ".$nightChargesLT[0]->charge_per_unit : "") : "")) : "".(count($nightChargesLT) ? ($nightChargesLT[0]->tcharge != 0 ? $nightChargesLT[0]->unit." X ".$nightChargesLT[0]->charge_per_unit : "") : "") !!}
            </td>
            <td></td>
            <td></td>
            @php
            	if(count($nightChargesFT)) {
                    if($nightChargesFT[0]->tcharge != 0) {
                        list($NFTtaka, $NFTps) = explode(".", $nightChargesFT[0]->tcharge);
                        $totalAmount += $nightChargesFT[0]->tcharge;
                    }
            	}
                if(count($nightChargesLT)) {
                    if($nightChargesLT[0]->tcharge != 0) {
                        list($NLTtaka, $NLTps) = explode(".", $nightChargesLT[0]->tcharge);
                        $totalAmount += $nightChargesLT[0]->tcharge;
                    }
                }
			@endphp
            <td class="amount-right">
                {!! isset($NFTtaka) ?  $NFTtaka.(isset($NFTtaka) && isset($NLTtaka) ? "<br>".(isset($NLTtaka) ? $NLTtaka : "") : "") : "".(isset($NLTtaka) ? $NLTtaka : "") !!}
            </td>
            <td class="amount-right">
                {!! isset($NFTps) ? $NFTps.(isset($NFTps) && isset($NLTps) ? "<br>".(isset($NLTps) ? $NLTps : "") : "") : "".(isset($NLTps) ? $NLTps : "") !!}
            </td>
        </tr>
        {{----------------NIGHT CHARGES------------------}}
        {{----------------HOLTAGE CHARGES------------------}}
        <tr>
            <td></td>
            <td>
                f) Holtage Charge
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
            	@php
            		if(count($holtageChargesFT)) {
            			list($otherUnitFTInt, $otherUnitFTDecimal) = explode(".", $holtageChargesFT[0]->other_unit);
            		}
                    if(count($holtageChargesLT)) {
                        list($otherUnitLTInt, $otherUnitLTDecimal) = explode(".", $holtageChargesLT[0]->other_unit);
                    }
            	@endphp
            	{!! count($holtageChargesFT) ? ($holtageChargesFT[0]->tcharge != 0 ? $holtageChargesFT[0]->unit." -> ".$otherUnitFTInt." X ".$holtageChargesFT[0]->charge_per_unit.(count($holtageChargesFT) && count($holtageChargesLT) ? "<br>".(count($holtageChargesLT) ? ($holtageChargesLT[0]->tcharge != 0 ? $holtageChargesLT[0]->unit." -> ".$otherUnitLTInt." X ".$holtageChargesLT[0]->charge_per_unit : "") : "") : "") : "".(count($holtageChargesLT) ? ($holtageChargesLT[0]->tcharge != 0 ? $holtageChargesLT[0]->unit." -> ".$otherUnitLTInt." X ".$holtageChargesLT[0]->charge_per_unit : "") : "")) : "".(count($holtageChargesLT) ? ($holtageChargesLT[0]->tcharge != 0 ? $holtageChargesLT[0]->unit." -> ".$otherUnitLTInt." X ".$holtageChargesLT[0]->charge_per_unit : "") : "") !!}
            </td>
            <td></td>
            <td></td>
            @php
            	if(count($holtageChargesFT)) {
                    if($holtageChargesFT[0]->tcharge != 0) {
                        list($HolFTtaka, $HolFTps) = explode(".", $holtageChargesFT[0]->tcharge);
                        $totalAmount += $holtageChargesFT[0]->tcharge;
                    }
            	}
                if(count($holtageChargesLT)) {
                    if($holtageChargesLT[0]->tcharge != 0) {
                        list($HolLTtaka, $HolLTps) = explode(".", $holtageChargesLT[0]->tcharge);
                        $totalAmount += $holtageChargesLT[0]->tcharge;
                    }
                }
			@endphp
            <td class="amount-right">
                {!! isset($HolFTtaka) ? $HolFTtaka.(isset($HolFTtaka) && isset($HolLTtaka) ? "<br>".(isset($HolLTtaka) ? $HolLTtaka : "") : "") : "".(isset($HolLTtaka) ? $HolLTtaka : "") !!}
            </td>
            <td class="amount-right">
                {!! isset($HolFTps) ? $HolFTps.(isset($HolFTps) && isset($HolLTps) ? "<br>".(isset($HolLTps) ? $HolLTps : "") : "") : "".(isset($HolLTps) ? $HolLTps : "") !!}
            </td>
        </tr>
        {{----------------HOLTAGE CHARGES----------------------}}
        {{----------------DOCUMENTATION CHARGES------------------}}
        <tr>
            <td></td>
            <td>
                g) Documentation Charge
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
            	{{ count($documentationCharges) ? ($documentationCharges[0]->tcharge != 0 ? $documentationCharges[0]->unit." X ".$documentationCharges[0]->charge_per_unit : "") : "" }}
            </td>
            <td></td>
            <td></td>
            @php
            	if(count($documentationCharges)) {
                    if($documentationCharges[0]->tcharge != 0) {
                        list($Dtaka, $Dps) = explode(".", $documentationCharges[0]->tcharge);
                        $totalAmount += $documentationCharges[0]->tcharge;
                    }
            	}
			@endphp
            <td class="amount-right">{{ isset($Dtaka) ? $Dtaka : "" }}</td>
            {{--<td class="amount-right">{{ number_format($Dtaka,2)}}</td>--}}
            <td class="amount-right">{{ isset($Dps) ? $Dps : "" }}</td>
        </tr>
        {{----------------DOCUMENTATION CHARGES------------------}}
        {{----------------WEIGHTMENT CHARGES------------------}}
        <tr>
            <td></td>
            <td>
                h) Weighment Charge
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
            	{!! count($weighmentChargesFT) ? ($weighmentChargesFT[0]->tcharge != 0 ? $weighmentChargesFT[0]->unit." X ".$weighmentChargesFT[0]->charge_per_unit.(count($weighmentChargesFT) && count($weighmentChargesLT) ? "<br>".(count($weighmentChargesLT) ? ($weighmentChargesLT[0]->tcharge != 0 ? $weighmentChargesLT[0]->unit." X ".$weighmentChargesLT[0]->charge_per_unit : "") : "") : "") : "".(count($weighmentChargesLT) ? ($weighmentChargesLT[0]->tcharge != 0 ? $weighmentChargesLT[0]->unit." X ".$weighmentChargesLT[0]->charge_per_unit : "") : "")) : "".(count($weighmentChargesLT) ? ($weighmentChargesLT[0]->tcharge != 0 ? $weighmentChargesLT[0]->unit." X ".$weighmentChargesLT[0]->charge_per_unit : "") : "") !!}
            </td>
            <td></td>
            <td></td>
            @php
            	if(count($weighmentChargesFT)) {
                    if($weighmentChargesFT[0]->tcharge != 0) {
                        list($WFTtaka, $WFTps) = explode(".", $weighmentChargesFT[0]->tcharge);
                        $totalAmount += $weighmentChargesFT[0]->tcharge;
                    }
            	}
                if(count($weighmentChargesLT)) {
                    if($weighmentChargesLT[0]->tcharge != 0) {
                        list($WLTtaka, $WLTps) = explode(".", $weighmentChargesLT[0]->tcharge);
                        $totalAmount += $weighmentChargesLT[0]->tcharge;
                    }
                }
			@endphp
            <td class="amount-right">
                {!! isset($WFTtaka) ? $WFTtaka.(isset($WFTtaka) && isset($WLTtaka) ? "<br>".(isset($WLTtaka) ? $WLTtaka : "") : "") : "".(isset($WLTtaka) ? $WLTtaka : "") !!}
            </td>
            <td class="amount-right">
                {!! isset($WFTps) ? $WFTps.(isset($WFTps) && isset($WLTps) ? "<br>".(isset($WLTps) ? $WLTps : "") : "") : "".(isset($WLTps) ? $WLTps : "") !!}
            </td>
        </tr>
        {{----------------WEIGHTMENT CHARGES------------------}}
        <tr>
            <td>
                <b>6.</b>
            </td>
            <td>
                Outstanding (if any)
            </td>
            <td></td>
            <td></td>
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
        {{------------------------------SUB TOTAL----------------------------}}
        <tr>
            <td>
                <b>7.</b>
            </td>
            <td>
                Sub-Total
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            @php
                $totalAmount_ceil = number_format(ceil($totalAmount),2,'.','');
            	if(isset($totalAmount_ceil)) {
            		list($totaltaka, $totalps) = explode(".", $totalAmount_ceil);
            	}
			@endphp
            <td class="amount-right">{{ isset($totaltaka) ? $totaltaka : "" }}</td>
            {{--<td class="amount-right">{{ number_format($totaltaka,2)}}</td>--}}
            <td class="amount-right">{{ isset($totalps) ? $totalps : "" }}</td>
        </tr>
        {{-----------------------SUB TOTAL---------------------------}}
        {{-----------------------VAT----------------------------}}
        <tr>
            <td>
                <b>8.</b>
            </td>
            <td>
                VAT
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>15%</td>
            <td></td>
            <td></td>
            @php
            	if(isset($totalAmount_ceil)) {
            		$vat = (15/100)*$totalAmount_ceil;
            		$totalAmountWithVat = number_format($totalAmount_ceil + $vat,2,'.',''); //$totalAmount + $vat;
            		list($totaltakaWithVat, $totalpsWithVat) = explode(".", $totalAmountWithVat);
            	}
            @endphp
            <td class="amount-right">{{ isset($totaltakaWithVat) ? $totaltakaWithVat : "" }}</td>
            {{--<td class="amount-right">{{ number_format($totaltakaWithVat,2)}}</td>--}}
            <td class="amount-right">{{ isset($totalpsWithVat) ? $totalpsWithVat : "" }}</td>
        </tr>
        {{-----------------------VAT----------------------------}}
    </tbody>
    </table>
    <div style="text-align: right; padding-top: 10px;">
	    <span>
	    	<b>Received Tk &nbsp; </b>{{isset($totalAmountWithVat) ? number_format(ceil($totalAmountWithVat),2) : ""}}
            {{--<td class="amount-right">{{ number_format($totaltakaWithVat,2)}}</td>--}}
	    </span><br>
	    <span>
	    	<b>Taka(In Words)</b> &nbsp;<span style="text-transform: capitalize;">{{isset($totalAmountWithVat) ? convert_number_to_words(ceil($totalAmountWithVat))." Taka only" : ""}}</span>
	    </span >
    </div>
    <br>
    <br>
    <p>
    	<span>Perpared By</span>
    	<span style="padding-left: 95px;">Checked &amp; Verified By</span>
    	<span style="padding-left: 95px;">A.D.(T)/Sr. A.D.(T)</span>
    	<span style="padding-left: 95px;">seal with Date</span>
    	<span style="padding-left: 95px;">Cash Progressive</span>
    	<span style="padding-left: 95px;">Authorised Officer of Bank</span><br>
    	<span style="padding-left: 850px;">No...................</span>
    </p>
</body>
</html>
@php
    function convert_number_to_words($number) {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
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