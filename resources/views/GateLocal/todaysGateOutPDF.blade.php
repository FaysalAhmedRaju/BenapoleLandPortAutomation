<!DOCTYPE html>
<html>
<head>
	<title>GateOut Report</title>
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
    		GateOut Report
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
			@foreach($todaysGateOut as $key => $gateOut)
				<tr>
					<td>{{ ++$i }}</td>
					<td>{{ $gateOut->truck_no }}</td>
					<td>{{ $gateOut->manifest }}</td>
					<td>{{ $gateOut->manifest_date }}</td>
					<td>{{ $gateOut->be_no }}</td>
					<td>{{ $gateOut->be_date }}</td>
					<td>{{ $gateOut->cargo_name }}</td>
					<td>{{ $gateOut->marks_no }}</td>
					<td>{{ $gateOut->loading_unit }}</td>
				</tr>
			@endforeach
		</tbody>
		</table>
		<p style="text-align: right"><b>Total: {{ $i }}</b> </p>
</body>
</html>