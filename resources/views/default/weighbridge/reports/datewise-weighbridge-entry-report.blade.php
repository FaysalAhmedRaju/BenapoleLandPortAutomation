<!DOCTYPE html>
<html>
<head>
	<title>Weight Bridge Entry Report</title>
	<style>
		html {
			margin: 5px 5px 0;
		}

		body{
			background-image: url(/img/Logo_BSBK.gif);
			/*background: url(/img/blpa.jpg );*/
			background-repeat:no-repeat;
			background-position:center center;
			background-size:250px 180px;
			opacity: .2;
		}

		table.dataTable {
			border-collapse: collapse;
		}

		table.dataTable, table.dataTable th, table.dataTable td {

			border: 1px solid black;
			padding: 5px;
			text-align: center;
		}

		.center {
			position: absolute;
			text-align: center;
			top: 0;
			left: 250px;
		}
	</style>
</head>
<body>
{{--
<img src="../public/img/blpa.jpg">
<p class="center">
	<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
	<span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
	Weight Bridge Entry Report
</p>
<h5 style="text-align: right;padding-right: 35px;"> Time: {{$todayWithTime}}</h5>

--}}




<table width="100%;" border="0">
	<tr>
		<td style="width: 25%">
			<img src="../public/img/blpa.jpg" height="100">
		</td>
		<td style="width: 50%; text-align: center">
			<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
			<span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
			Weight Bridge Entry Report
		</td>
		<td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
			<b>Time:</b> {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
		</td>

	</tr>
</table>

<br>
<table width="100%" class="dataTable">
	<thead>
	<tr>
		<th><nobr>S/L</nobr></th>
		<th><nobr>Manifest No.</nobr></th>
		<th><nobr>Truck No.</nobr></th>
		<th><nobr>Driver Name</nobr></th>
		<th><nobr>Gross Weight</nobr></th>
		<th><nobr>Tare Weight</nobr></th>
		<th><nobr>Net Weight</nobr></th>
		<th><nobr>Entry Time</nobr></th>
		<th><nobr>Entry By</nobr></th>
		<th><nobr>Scale No</nobr></th>
		

		{{--<th>Exit Time</th>--}}
		{{--<th>Exit By</th>--}}
	</tr>
	</thead>
	<tbody>	@php($i=0)
	@php($total_gweight_wbridge=0)
	@php($total_tr_weight=0)
	@php($total_tweight_wbridge=0)
	@foreach($dateWiseWeightBridgeEntry as $key => $weightBridgeEntry)
		<tr>
			<td>{{ ++$i }}</td>
			<td>{{ $weightBridgeEntry->manifest }}</td>
			<td><nobr>{{ $weightBridgeEntry->truck_type."-".$weightBridgeEntry->truck_no }}</nobr></td>
			<td>{{ $weightBridgeEntry->driver_name }}</td>
			<td>{{ $weightBridgeEntry->gweight_wbridge }} @php($total_gweight_wbridge+=$weightBridgeEntry->gweight_wbridge)</td>
			<td>{{ $weightBridgeEntry->tr_weight }}@php($total_tr_weight+=$weightBridgeEntry->tr_weight)</td>
			<td>{{ $weightBridgeEntry->tweight_wbridge }}@php($total_tweight_wbridge+=$weightBridgeEntry->tweight_wbridge)</td>
			<td><nobr>{{ date('d-m-Y h:i:s A',strtotime($weightBridgeEntry->wbrdge_time1))  }}</nobr></td>
			<td>{{ $weightBridgeEntry->user_name }}</td>
			<td>{{ $weightBridgeEntry->entry_scale }}</td>
			{{--<td>{{ $weightBridgeEntry->wbrdge_time2 }}</td>--}}
			{{--<td>{{ $weightBridgeEntry->name }}</td>--}}
			{{--<td>{{ $weightBridgeEntry->wbridg_user2 }}</td>--}}
		</tr>
	@endforeach
	</tbody>
	<tfoot>
	<tr>
		<td>Total:</td>
		<td> </td>
		<td> </td>
		<td> </td>
		<td> {{$total_gweight_wbridge>0 ?$total_gweight_wbridge:''}}</td>
		<td>{{$total_tr_weight>0 ?$total_tr_weight:''}} </td>
		<td>{{$total_tweight_wbridge>0 ?$total_tweight_wbridge:'' }} </td>
		<td> </td>
		<td> </td>
		<td> </td>
		{{--<td></td>--}}
		{{--<td></td>--}}


	</tr>


	<tr>
		<td colspan="10">
			<p style="text-align: right"><b>Total: {{ $i }}</b> </p>
		</td>
	</tr>
	</tfoot>
</table>




</body>
</html>