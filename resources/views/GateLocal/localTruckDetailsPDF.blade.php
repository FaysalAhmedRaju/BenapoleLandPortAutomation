<!DOCTYPE html>
<html>
<head>
	<title>Exit Pass For Transport With Cargo</title>
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
    		<span>Exit Pass For Transport With Cargo</span> <br>
    		<span>Time: <u>{{$getLocalTruckDetails[0]->delivery_dt}}</u> To: <u>{{$getLocalTruckDetails[0]->exit_dt}}</u>  </span>
    	</p>
    	<br>
    	<br>
    	<p>
	 	<span style="text-align: left;padding-left: 10px;"> Date: {{$todayWithTime}}</span>
	 	<span style="padding-left: 270px; font-size: 8px;">Consignee:{{$getLocalTruckDetails[0]->NAME}}</span><br>
	 	<span style="padding-left: 485px; font-size: 8px;">Address: {{$getLocalTruckDetails[0]->ADD1}}</span>
	 	</p>
	 	<table>
			<thead>
			<tr>
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
			@foreach($getLocalTruckDetails as $key => $LocalTruckDetails)
				<tr>
					<td>{{ $LocalTruckDetails->truck_no }}</td>
					<td>{{ $LocalTruckDetails->manifest }}</td>
					<td>{{ $LocalTruckDetails->manifest_date }}</td>
					<td>{{ $LocalTruckDetails->be_no }}</td>
					<td>{{ $LocalTruckDetails->be_date }}</td>
					<td>{{ $LocalTruckDetails->cargo_name }}</td>
					<td>{{ $LocalTruckDetails->marks_no }}</td>
					<td>{{ $LocalTruckDetails->loading_unit }}</td>
				</tr>
			@endforeach
		</tbody>
		</table>
		<br>
		<br>
		<span style="font-size: 14px;">
			<span style="padding-left: 30px;">Signed By C&F Agent</span>
			<span style="padding-left: 50px;">Signed By Custom Inspector</span>
			<span style="padding-left: 20px;">Signed By TI/WHS</span>
			<span style="padding-left: 20px;">Signed By TI/WHS(Exit Pass)</span>
		</span><br>
		<span style="font-size: 14px;">
			<span>(with card and licence no, & date)</span>
			<span style="padding-left: 25px;">(with office seal and date)</span>
			<span style="padding-left: 37px;">shed Incharge</span>
			<span style="padding-left: 50px;">(with office seal and date)</span>
		</span><br>
		<span style="font-size: 14px;">
			<span style="padding-left: 370px;">(with office seal and date)</span>
		</span>
</body>
</html>