<!DOCTYPE html>
<html>
<head>
	<title>Posting Branch Entry Done, But WareHouse Entry Not Done Report</title>
	<style>
		table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 180px;
        }
	</style>
</head>
<body>
		<img src="../public/img/blpa.jpg">
		<p class="center">
			<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
			<span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    		Posting Branch Entry Done, But WareHouse Entry Not Done Report
    	</p>
	 	<h5 style="text-align: right;padding-right: 35px;"> Date: {{$todayWithTime}}</h5>
	 	<table>
			<thead>
			<tr>
				<th>Serial No.</th>
				<th>Manifest No.</th>
				<th>Truck No.</th>
			</tr>
			</thead>
			<tbody>	@php($i=0)
			@foreach($data as $key => $Singledata)
				<tr>
					<td>{{ ++$i }}</td>
					<td>{{ $Singledata->manifest }}</td>
					<td>{{ $Singledata->truck_type."-".$Singledata->truck_no }}</td>
				</tr>
			@endforeach
		</tbody>
		</table>
		<p style="text-align: right"><b>Total: {{ $i }}</b> </p>
</body>
</html>