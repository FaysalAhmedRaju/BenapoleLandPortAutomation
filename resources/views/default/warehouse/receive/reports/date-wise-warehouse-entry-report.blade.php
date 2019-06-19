<!DOCTYPE html>
<html>
<head>
	<title>
	@if(Auth::user()->role->name == 'TransShipment')
        Transhipment
    @else
        WareHouse
    @endif
     Entry Report ({{$wise}})</title>
	<style>
        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        th {
        	text-align: center;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 250;
        }
    </style>
</head>
<body>
		<img src="../public/img/blpa.jpg">
		<p class="center">
			<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
			<span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    	{{date("d-m-Y",strtotime($date))}} @if(Auth::user()->role->name == 'TransShipment')
        Transhipment
	    @else
	        WareHouse
	    @endif
	    {{$typeOfReports}} ({{$wise}})
    	</p>
    	<h5 style="text-align: right;padding-right: 35px;">
			<b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
		</h5>
	 	<table width="100%">
			<thead>
			<tr>
				<th style=" font-size: 11px">&nbsp;</th>
				<th style="font-size: 11px"><nobr>&nbsp;</nobr></th>
				<th style="font-size: 11px"><nobr>&nbsp;</nobr></th>
				<th style=" font-size: 11px"><nobr>&nbsp;</nobr></th>
				<th style=" font-size: 11px" colspan="4"><nobr>Receive</nobr></th>
				<th style=" font-size: 11px" colspan="2"><nobr>Labor</nobr></th>
				<th style=" font-size: 11px" colspan="3"><nobr>Equipment</nobr></th>
				<th style=" font-size: 11px"><nobr>&nbsp;</nobr></th>
			</tr>
			<tr>
				<th style=" font-size: 11px">S/L</th>
				<th style="font-size: 11px"><nobr>Manifest No.</nobr></th>
				<th style="font-size: 11px"><nobr>Shed/Yard</nobr></th>
				<th style=" font-size: 11px; width: 100px;"><nobr>Truck No.</nobr></th>
				<th style=" font-size: 11px"><nobr>Weight</nobr></th>
				<th style=" font-size: 11px"><nobr>Package</nobr></th>
				<th style="font-size: 11px"><nobr>Comment</nobr></th>
				<th style=" font-size: 11px;  width: 100px;"><nobr>By</nobr></th>
				<th style=" font-size: 11px"><nobr>Unload</nobr></th>
				<th  style="font-size: 11px"><nobr>Package</nobr></th>
				<th style=" font-size: 11px"><nobr>Unload</nobr></th>
				<th  style=" font-size: 11px;"><nobr>Name</nobr></th>
				<th style=" font-size: 11px"><nobr>Package</nobr></th>
				<th style=" font-size: 11px; width: 80px;"><nobr>Delivery Date</nobr></th>
			</tr>
			</thead>
			<tbody> @php($i=0)  @php($sumLabourUnload=0) @php($sumEquipmentUnload=0)
			@foreach($todaysWareHouseEntry as $key => $wareHouseEntry)
				<tr>
					<td> {{ ++$i }}</td>
					<td>{{ $wareHouseEntry->manifest }}</td>
					<td>{{ $wareHouseEntry->yard_shed_name }}</td>
					<td>{{ $wareHouseEntry->truck_type_no }}</td>
					<td>{{ $wareHouseEntry->receive_weight != null ? number_format($wareHouseEntry->receive_weight, 2) : ''  }}</td>
					<td>{{ $wareHouseEntry->receive_package != null ? $wareHouseEntry->receive_package : '' }}</td>
					<td>{{ $wareHouseEntry->unload_comment }}</td>
					<td>{{  $wareHouseEntry->name }}</td>
					<td>{{ $wareHouseEntry->unload_labor_weight != null ? number_format($wareHouseEntry->unload_labor_weight, 2) : '' }}</td>
					@php($sumLabourUnload+= $wareHouseEntry->unload_labor_weight)
					<td>{{ $wareHouseEntry->unload_labor_package != null ? $wareHouseEntry->unload_labor_package : '' }}</td>
					<td>{{ $wareHouseEntry->unload_equip_weight != null ? number_format($wareHouseEntry->unload_equip_weight, 2) : '' }}</td>
					@php($sumEquipmentUnload += $wareHouseEntry->unload_equip_weight )
					<td>{{ $wareHouseEntry->unload_equip_name }}</td>
					<td>{{ $wareHouseEntry->unload_equipment_package != null ? $wareHouseEntry->unload_equipment_package : '' }}</td>
					<td>{{ $wareHouseEntry->approximate_delivery_date != null ? date('d-m-Y',strtotime($wareHouseEntry->approximate_delivery_date)) : '' }}</td>
				</tr>
			@endforeach
		</tbody>
		</table>
		<p><b>Total: {{ $i }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Gross Weight: {{ number_format($sumLabourUnload + $sumEquipmentUnload,2) }}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Labor Total: {{ $sumLabourUnload != 0 ? number_format($sumLabourUnload,2) : '' }}&nbsp;&nbsp;
				Equ. Total: {{ $sumEquipmentUnload != 0 ? number_format($sumEquipmentUnload,2) : '' }}</b> </p>

</body>
</html>