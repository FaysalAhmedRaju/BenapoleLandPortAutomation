<!DOCTYPE html>
<html>
<head>
	<title>Delivery Request Requisition Entry Report</title>
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
            left: 250;
        }
    </style>
</head>
<body>
		<img src="../public/img/blpa.jpg">
		<p class="center">
			<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
			<span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
			Delivery Request Requisition Entry Report
    	</p>
    	<h5 style="text-align: right;padding-right: 35px;"> Date: {{$todayWithTime}}</h5>
	 	<table>
			<thead>
			<tr>
				<th>Serial No.</th>
				<th>Manifest No.</th>
				<th>Carpenter Packages</th>
				<th>Repair Packages</th>


				{{--<th>Gross Weight</th>--}}
				<th>Delivery Date</th>
				{{--<th>Approve Date</th>--}}
				<th>Labour Weight</th>
				<th>Equipment Weight</th>

				<th>Transport Truck</th>
				<th>Transport VAN</th>

				<th>BD Weighment</th>
				{{--<th>Loading</th>--}}
			</tr>
			</thead>
			<tbody> @php($i=0)
			@foreach($todaysTruckDeliveryEntry as $key => $truckDeliveryEntry)
				<tr>	
					<td> {{ ++$i }}</td>
					<td>{{ $truckDeliveryEntry->manifest }}</td>
					<td>{{ $truckDeliveryEntry->carpenter_packages }}</td>
					<td>{{ $truckDeliveryEntry->carpenter_repair_packages }}</td>


					{{--<td>{{ $truckDeliveryEntry->gweight }}</td>--}}
					<td>{{$truckDeliveryEntry->approximate_delivery_date}}</td>
					<td>{{ $truckDeliveryEntry->approximate_labour_load }}</td>
					<td>{{ $truckDeliveryEntry->approximate_equipment_load }}</td>


					{{--<td>{{ $truckDeliveryEntry->approve_dt }}</td>--}}
					<td>{{ $truckDeliveryEntry->transport_truck }}</td>
					<td>{{ $truckDeliveryEntry->transport_van }}</td>

					<td>{{ $truckDeliveryEntry->local_weighment }}</td>
					{{--<td>{{ $truckDeliveryEntry->equip_name }}</td>--}}
					{{--<td>{{ $truckDeliveryEntry->equipment_package }}</td>--}}
					{{--<td>{{$truckDeliveryEntry->loading_flag?'Equipment':'Labour'}}</td>--}}
				</tr>
			@endforeach
		</tbody>
		</table>
		<p style="text-align: right"><b>Total: {{ $i }}</b> </p>
</body>
</html>