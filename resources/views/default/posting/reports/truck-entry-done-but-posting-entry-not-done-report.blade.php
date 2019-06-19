<!DOCTYPE html>
<html>
<head>
	<title>Truck Entry Done, But Posting Branch Entry Not Done Report</title>
	<style>


		html {
			margin: 5px 12px 0;
		}

		table.dataTable {
			border-collapse: collapse;
		}

		table.dataTable, table.dataTable th, table.dataTable td {
			/*border: 1px solid black;*/
			padding: 5px;
			text-align: center;
			border: 0px;
		}
		.center{
			position: absolute;
			text-align: center;
			top: 0;
			left: 250px;
		}


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
<table width="100%;"  class="dataTable">
	<tr>
		<td style="width: 15%">
			<img src="../public/img/blpa.jpg" height="100">
		</td>
		<td style="width: 60%; text-align:center">
			<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
			<span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
			Truck Entry Done, But Posting Branch Entry Not Done Report


		</td>
		<td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
			<b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
			{{--Print Date : {{$todayWithTime}}--}}
		</td>
	</tr>
</table>
<br>



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