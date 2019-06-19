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
		.font-style {
			font-size: 12px;
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
		<h5 style="padding-left: 8px;">Shed/Yard:
			@foreach($shedYardName as $key => $name)
				<span>{{$name}} {{$key == (count($shedYardName)-1) ? '' : ', ' }}</span>
			@endforeach
		</h5>
	 	<table width="100%">
			<thead>
			<tr class="font-style">
				<th>1</th>
				<th>2</th>
				<th colspan="2">3</th>
				<th>4</th>
				<th colspan="4">5</th>
				<th colspan="2">6</th>
				<th>7</th>
				<th>8</th>
				<th>9</th>
			</tr>
			<tr class="font-style">
				<th rowspan="2"><nobr>S/L No.</nobr></th>
				<th style="width: 75px;" rowspan="2"><nobr>Date of</nobr><br> Arrived</th>
				<th colspan="2"><nobr>Transport Particulars</nobr></th>
				<th rowspan="2" style="width: 120px;"><nobr>Manifest</nobr><br>No. & Date </th>
				<th colspan="4" style="text-transform: uppercase;"><nobr>Particulars of Manifest</nobr></th>
				<th colspan="2" style="text-transform: uppercase; width: 160px;"><nobr>Particulars of Receipt</nobr></th>
				<th rowspan="2"><nobr>Location of Cargo</nobr></th>
				<th rowspan="2"><nobr>Consignor's/Consignee's</nobr><br>Name & Address</th>
				<th rowspan="2" style="width: 80px;">Delivery Date</th>
			</tr>
			<tr class="font-style">
				<th colspan="2"><nobr>Type-No</nobr></th>
				<th><nobr>Name of Goods</nobr></th>
				<th><nobr>Quantity</nobr><br>(No. of Pkgs)</th>
				<th><nobr>Weight</nobr></th>
				<th><nobr>Value</nobr></th>
				<th><nobr>Quantity</nobr><br>(No. of Pkgs)</th>
				<th><nobr>Weight</nobr></th>
			</tr>
			</thead>
			<tbody> @php($i=0) @php($totalManifestPackage=0) @php($totalgweight=0) @php($totalReceivePackage=0) @php($totalreceiveweight=0)
			@foreach($data as $key => $wareHouseEntry)
				<tr>
					<td> {{ ++$i }}</td>
					<td>{{ $wareHouseEntry->truckentry_datetime }}</td>
					<td colspan="2">{{ $wareHouseEntry->truck_type_no }}</td>
					<td><u>{{ $wareHouseEntry->manifest  }}</u><br>{{ $wareHouseEntry->manifest_date }} </td>
					<td>{{ $wareHouseEntry->goods_name }}</td>
					<td>{{ $wareHouseEntry->manifest_receive_package }}</td>
					@php( is_numeric($wareHouseEntry->manifest_receive_package) ? $totalManifestPackage+= $wareHouseEntry->manifest_receive_package : '')
					<td>{{ $wareHouseEntry->gweight != null ? number_format($wareHouseEntry->gweight, 2) : '' }}</td>
					@php( $wareHouseEntry->gweight != null ? $totalgweight+= $wareHouseEntry->gweight : '')
					<td>{{ $wareHouseEntry->cnf_value }}</td>
					<td>{{ $wareHouseEntry->receive_package }}</td>
					@php( is_numeric($wareHouseEntry->receive_package) ?  $totalReceivePackage+= $wareHouseEntry->receive_package : '')
					<td>{{ $wareHouseEntry->receive_weight != null ? number_format($wareHouseEntry->receive_weight, 2) : '' }}</td>
					@php( is_numeric($wareHouseEntry->receive_weight) ? $totalreceiveweight+= $wareHouseEntry->receive_weight : '')
					<td>{{ $wareHouseEntry->unload_comment }}</td>
					<td>{{ $wareHouseEntry->importer_name_and_address }}</td>
					<td>{{ $wareHouseEntry->approximate_delivery_date }}</td>
				</tr>
			@endforeach
			<tr>
				<th>Total</th>
				<th colspan="5"></th>
				<th>{{ $totalManifestPackage != 0 ? $totalManifestPackage : ''  }}</th>
				<th>{{ $totalgweight != 0 ? number_format($totalgweight,2) : '' }}</th>
				<th>&nbsp;</th>
				<th>{{ $totalReceivePackage != 0 ? $totalReceivePackage : '' }}</th>
				<th>{{ $totalreceiveweight != 0 ? number_format($totalreceiveweight,2) : '' }}</th>
				<th colspan="2">&nbsp;</th>
				<th></th>
			</tr>
		</tbody>
		</table>
</body>
</html>