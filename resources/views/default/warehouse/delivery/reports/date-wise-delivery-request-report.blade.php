<!DOCTYPE html>
<html>
<head>
	<title>Truck Delivery {{-- Request --}} Report</title>
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
	{{--	<img src="../public/img/blpa.jpg">
		<p class="center">
			<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
			<span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    		{{$today}}'s Delivery --}}{{-- Request --}}{{--
    	</p>
    	<h5 style="text-align: right;padding-right: 35px;"> Date: {{$todayWithTime}}</h5>

--}}


		<table width="100%;" border="0">
			<tr>
				<td style="width: 25%">
					<img src="../public/img/blpa.jpg" height="100">
				</td>
				<td style="width: 50%; text-align: center">
					<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
					<span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
					{{date('d-m-Y',strtotime($today))}} 's Delivery Request
				</td>
				<td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
					<b>Time:</b> {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
				</td>

			</tr>
		</table>

		<br>
		<table  class="dataTable">

			<thead>
			<tr>
				<th style=" font-size: 10px;"><nobr>S/L</nobr></th>
				<th style="font-size: 10px;"><nobr>Manifest No.</nobr></th>
				<th style=" font-size: 10px;"><nobr>Manifest Date</nobr></th>
				<th style=" font-size: 10px;"><nobr>Shed/Yard</nobr></th>
				<th style=" font-size: 10px;"><nobr>Description of Goods</nobr></th>
				<th style="font-size: 10px;"><nobr>Quantity</nobr></th>
				<th style=" font-size: 10px;"><nobr>No Of Packages</nobr></th>
				<th style="font-size: 10px;"><nobr>C&F Value</nobr></th>
				<th style=" font-size: 10px;"><nobr>Name & Address Expoter</nobr></th>
				<th style=" font-size: 10px;"><nobr>Name & Address Importer</nobr></th>
				<th style="font-size: 10px;"><nobr>L.C No. & Date</nobr></th>
				<th style=" font-size: 10px;"><nobr>B/E No. & Date</nobr></th>
				<th style=" font-size: 10px;"><nobr>Indian B/E No. & Date</nobr></th>
			</tr>
			</thead>
			<tbody> @php($i=0)
			@foreach($todaysDeliveryRequest as $key => $manifestDetail)
				<tr>
					<td> {{ ++$i }}</td>
					<td> {{ $manifestDetail->manifest }}</td>
					<td>{{ $manifestDetail->manifest_date }}</td>
					<td>{{ $manifestDetail->yard_shed_name }}</td>
					<td>{{ $manifestDetail->cargo_name }}</td>
					<td>Gr. Wt-{{ $manifestDetail->gweight}} <br> Nt. Wt- {{$manifestDetail->nweight }}</td>
					<td>{{ $manifestDetail->package_no . " ". $manifestDetail->package_type }}</td>
					<td>{{ $manifestDetail->cnf_value }}</td>
					<td>{{ $manifestDetail->exporter_name_addr }}</td>
					<td>{{ $manifestDetail->NAME . " ". $manifestDetail->ADD1 }}</td>
					<td>{{ $manifestDetail->lc_no . " ". $manifestDetail->lc_date }}</td>
					<td>{{ $manifestDetail->be_no . " ". $manifestDetail->be_date}}</td>
					<td>{{ $manifestDetail->ind_be_no . " ". $manifestDetail->ind_be_date}}</td>
				</tr>
			@endforeach
			</tbody>
			<tfoot>
			<tr>
				<td colspan="13">
					<p style="text-align: right"><b>Total: {{ $i }}</b> </p>
				</td>
			</tr>
			</tfoot>
		</table>

</body>


</html>