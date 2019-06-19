<!DOCTYPE html>
<html>
<head>
	<title>Manifest Report</title>
	<style>
    table {
        border-collapse: collapse;
        width: 100%;

    }
    table, th, td {
        border: 1px solid black;
        padding: 3px;
        text-align: center;
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
    		Manifest Report
    	</p>
    	<h5 style="text-align: right;padding-right: 35px;"> Date: {{$todayWithTime}}</h5>
    	<table>
	 		<caption style="padding-bottom: 10px;"><b><u>Manifest Details: {{$manifestNo}}</u><b></caption>
			<thead>
			<tr>
				<th>Serial No.</th>
				<th>Manifest Date</th>
				<th>Description of Goods</th>
				<th>Quantity</th>
				<th>Package</th> 
				<th>CNF Value</th>
				<th>Expoter Name and Address</th>
				<th>Importer Name and Address</th>
				<th>LC No and Date</th>
				<th>B/E No. and Date</th>
				<th>Indian B/E No. and Date</th>
			</tr>
			</thead>
			<tbody> @php($i=0)
			@foreach($manifestDetails as $key => $manifestDetail)
				<tr>	
					<td> {{ ++$i }}</td>
					<td>{{ $manifestDetail->manifest_date }}</td>
					<td>{{ $manifestDetail->cargo_name }}</td>
					<td>{{ $manifestDetail->gweight . " ". $manifestDetail->nweight }}</td>
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
		</table>
    	<table>
	 		<caption style="padding-bottom: 10px;"><b><u>Foreign Truck Details</u><b></caption>
			<thead>
			<tr>
				<th>Serial No.</th>
				<th>Manifest No.</th>
				<th>Truck No.</th>
				<th>Driver Name</th> 
				<th>Net Weight</th>
				<th>Receive Package</th>
				<th>Receive Datetime</th>
				<th>Labor Unload</th>
				<th>Labor Package</th>
				<th>Equipment Unload</th>
				<th>Equipment Name</th>
				<th>Equipment Package</th>
				{{--<th>Carpenter</th>
				<th>Offloding</th>--}}
			</tr>
			</thead>
			<tbody>  @php($i=0) @php($sumNetweight=0)
			@foreach($indianTruckData as $key => $indianTruck)
				<tr>	
					<td> {{ ++$i }}</td>
					<td>{{ $indianTruck->manifest }}</td>
					<td>{{ $indianTruck->truck_no }}</td>
					<td>{{ $indianTruck->driver_name }}</td>
					<td>{{ $indianTruck->tweight_wbridge }}</td> @php($sumNetweight += $indianTruck->tweight_wbridge ) {{--WEIGHTBRIDGE NET WEIGHT--}}
					<td>{{ $indianTruck->receive_package }}</td>
					<td>{{ $indianTruck->receive_datetime }}</td>
					<td>{{ $indianTruck->labor_unload }}</td>
					<td>{{ $indianTruck->labor_package }}</td>
					<td>{{ $indianTruck->equip_unload }}</td>
					<td>{{ $indianTruck->equip_name }}</td>
					<td>{{ $indianTruck->equipment_package }}</td>
					{{--<td>{{ $indianTruck->carpenter?'Yes':'No' }}</td>
					<td>{{ $indianTruck->offloading_flag?'Equipment':'Labour' }}</td>--}}
				</tr>
			@endforeach
			</tbody>
		</table>
		<p style="text-align: right"><b>Total Net Weight: {{ $sumNetweight }} &nbsp; &nbsp; &nbsp; Total: {{ $i }}</b></p>
	 	<table width="550">
	 		<caption style="padding-bottom: 10px;"><b><u>Local Truck Details</u><b></caption>
			<thead>
			<tr>
				<th>Serial No.</th>
				<th>Manifest No.</th>
				<th>Truck No.</th>
				<th>Driver Name</th> 
				{{--<th>Gross Weight</th>--}}
				<th>Loading Unit</th>
				<th>Package</th>
				<th>Delivery Date</th>
				{{--<th>Approve Date</th>--}}
				<th>Labor Load</th>
				<th>Labor Package</th>
				<th>Equipment Load</th>
				<th>Equipment Name</th>
				<th>Equipment Package</th>
                {{--<th>Loading</th>--}}
			</tr>
			</thead>
			<tbody> @php($i=0) @php($totalLoadingUnit=0)
			@foreach($bdTruckData as $key => $bdTruck)
				<tr>	
					<td> {{ ++$i }}</td>
					<td>{{ $bdTruck->manifest }}</td>
					<td>{{ $bdTruck->truck_no }}</td>
					<td>{{ $bdTruck->driver_name }}</td>
					{{--<td>{{ $bdTruck->gweight }}</td>--}}
					<td>{{ $bdTruck->loading_unit }}</td> @php($totalLoadingUnit += $bdTruck->loading_unit )
					<td>{{ $bdTruck->package }}</td>
					<td>{{ $bdTruck->delivery_dt }}</td>
					{{--<td>{{ $bdTruck->approve_dt }}</td>--}}
					<td>{{ $bdTruck->labor_load }}</td>
					<td>{{ $bdTruck->labor_package }}</td>
					<td>{{ $bdTruck->equip_load }}</td>
					<td>{{ $bdTruck->equip_name }}</td>
					<td>{{ $bdTruck->equipment_package }}</td>
					{{--<td>{{ $bdTruck->loading_flag?'Equipment':'Labour' }}</td>--}}
				</tr>
			@endforeach
			</tbody>
		</table>
		<p style="text-align: right"><b>Remaining Weight: {{ $sumNetweight-$totalLoadingUnit }} &nbsp;&nbsp; Total Gross Weight: {{ $totalLoadingUnit }}&nbsp;&nbsp; Total: {{ $i }}</b> </p>
	</body>
</html>