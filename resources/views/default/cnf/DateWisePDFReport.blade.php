<!DOCTYPE html>
<html>
<head>
	<title>Date Wise Manifest Report</title>
	<style>
    table {
        border-collapse: collapse;
        width: 100%;

    }
    table, th, td {
        border: 1px solid black;
        padding: 1px;
        text-align: center;
    }
    .center{
        position: absolute;
        text-align: center;
        top: 0;
        left: 250px;
    }
    </style>
</head>
	<body>
		<img src="../public/img/blpa.jpg">
		<p class="center">
			<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
			<span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    		<span style="font-size: 19px;">Manifest Report</span> <br>
    		Manifest Details Form : {{$from_date}} To: {{$to_date}}
    	</p>
    	<h5 style="text-align: right;padding-right: 35px;"> Date: {{$todayWithTime}}</h5>
    	@php($i=0)
		@foreach($dateWiseManifestReport as $key => $manifestDetail)
    	<table style="page-break-inside:avoid;">
	 		<caption style="padding-bottom: 10px;"><b><u>Manifest Details: {{ $manifestDetail->manifest }}</u><b></caption>
			<thead>
			<tr>
				<th>Description of Goods</th>
				<th style="width: 100px;">Quantity</th>
				<th>Package</th> 
				<th>CNF Value</th>
				<th>Expoter Name and Address</th>
				<th>Importer Name and Address</th>
				<th>LC No and Date</th>
				<th>Manifest Date</th>
				<th>B/E No. and Date</th>
				<th>Indian B/E No. and Date</th>
			</tr>
			</thead>
			<tbody> 
				<tr>	
					<td>{{ $manifestDetail->goodsName }}</td>
					<td>Gr. Wt-{{ $manifestDetail->gweight}} <br> Nt. Wt- {{$manifestDetail->nweight }}</td>
					<td>{{ $manifestDetail->package_no . " ". $manifestDetail->package_type }}</td>
					<td>{{ $manifestDetail->cnf_value }}</td>
					<td>{{ $manifestDetail->exporter_name_addr }}</td>
					<td>{{--{{ $manifestDetail->importerName . " ". $manifestDetail->importerAddress }}--}}</td>
					<td>{{ $manifestDetail->lc_no . " ". $manifestDetail->lc_date }}</td>
					<td>{{ $manifestDetail->manifest_date }}</td>
					<td>{{ $manifestDetail->be_no . " ". $manifestDetail->be_date}}</td>
					<td>{{ $manifestDetail->ind_be_no . " ". $manifestDetail->ind_be_date}}</td>
				</tr>
				<tr>
					<th colspan="2">Truck Details:</th>
					<td colspan="4">Local Truck: {{ $manifestDetail->localTruck}}</td>
					<td colspan="4">Foreign Truck: {{ $manifestDetail->foreignTruck}}</td>
				</tr>
			</tbody>
		</table>
		@endforeach
	</body>
</html>