<!DOCTYPE html>
<html>
<head>
	<title>Local Truck Information</title>
	<style>
        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
			text-align: center;
        }
        .center{
            
            position: absolute;
            text-align: center;
            top: 0;
            left: 200px;
        }
    </style>
</head>
<body>
		<img  src="../public/img/blpa.jpg">
		<p class="center">
			<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
			<span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    		Local Truck/Van {{-- Request --}} Report
    	</p>
    	<h5 style="text-align: right;padding-right: 35px;"> Date: {{$today}}</h5>
		<h4>Manifest No: {{$bdTruckInfo[0]->manifest}}</h4>

		<table style="width: 100%;">

			<thead>
			<tr>

				<th>S/l</th>
				<th>Truck No.</th>
				<th>Driver Name</th>
				<th>Labour Loading</th>
				<th>Equipment Loading</th>
				<th>Labour Packages</th>
				<th>Equipment Packages</th>




			</tr>
			</thead>
			<tbody> @php($i=0)@php($totalLabourLoad=0)@php($totalEqupLoad=0)
			@foreach($bdTruckInfo as $key => $bd)
				<tr>
					<td> {{ ++$i }}</td>
					<td>{{$bd->truck_no}}</td>
					<td>{{$bd->driver_name}}</td>
					{{--<td>@if ( $bd->loading_flag==0) "Labour" @else "Equipment" @endif</td>--}}

					<td>{{$bd->labor_load}}</td>@php($totalLabourLoad+=$bd->labor_load)
					<td>{{$bd->equip_load}}</td>@php($totalEqupLoad+=$bd->equip_load)
					<td>{{$bd->labor_package}}</td>
					<td>{{$bd->equipment_package}}</td>


				</tr>
			@endforeach
			</tbody>
		</table>
		<div style="text-align: left">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<span><b>Total Labour Loading:</b> {{$totalLabourLoad}}</span>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span><b>Total Equipment Loading:</b> {{$totalEqupLoad}}</span>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			{{--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
		<span><b>Total Truck:</b> {{ $i }} </span>

		</div>
</body>
</html>