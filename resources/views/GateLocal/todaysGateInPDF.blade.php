<!DOCTYPE html>
<html>
<head>
	<title>GateIn Report</title>
	<style>
		table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 150;
        }
	</style>
</head>
<body>
		<img src="../public/img/blpa.jpg">
		<p class="center">
			<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
			<span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    		GateIn Report
    	</p>
	 	<h5 style="text-align: right;padding-right: 35px;"> Date: {{$todayWithTime}}</h5>
	 	<table>
			<thead>
			<tr>
				<th>S/L</th>
                <th>Truck No</th>
                <th>Manifest No</th>
                <th style="width: 75px;">Manifest Date</th>
                <th>B/E No</th>
                <th style="width: 75px;">B/E Date</th>
                <th>Goods Name</th>
                <th>Marks</th>
                <th>Quantity</th>
			</tr>
			</thead>
			<tbody>	@php($i=0)
			@foreach($todaysGateIn as $key => $gateIn)
				<tr>
					<td>{{ ++$i }}</td>
					<td>{{ $gateIn->truck_no }}</td>
					<td>{{ $gateIn->manifest }}</td>
					<td>{{ $gateIn->manifest_date }}</td>
					<td>{{ $gateIn->be_no }}</td>
					<td>{{ $gateIn->be_date }}</td>
					<td>{{ $gateIn->cargo_name }}</td>
					<td>{{ $gateIn->marks_no }}</td>
					<td>{{ $gateIn->loading_unit }}</td>
				</tr>
			@endforeach
		</tbody>
		</table>
		<p style="text-align: right"><b>Total: {{ $i }}</b> </p>
</body>
</html>