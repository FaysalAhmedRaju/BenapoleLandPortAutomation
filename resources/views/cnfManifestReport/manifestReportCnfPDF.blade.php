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
            left: 350px;
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
    <caption style="padding-bottom: 10px;"><b><u>Manifest Details: {{$manifestNo}}</u></b></caption>
    <thead>
    <tr>
        <th>S/L</th>
        <th>Manifest Date</th>
        <th>Description of Goods</th>
        <th style="width: 100px;">Quantity</th>
        <th>No Of Packages</th>
        <th>C&F Value</th>
        <th>Name & Address of Expoter</th>
        <th>Name & Address of Importer</th>
        <th>L.C No. & Date</th>
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
</table>
<table>
    <caption style="padding-bottom: 10px;"><b><u>Indian Truck Details</u><b></caption>
    <thead>
    <tr>
        <th>S/L</th>
        <th>Manifest No.</th>
        <th>Truck No.</th>
        <th>Driver Name</th>
        <th>Net Weight</th>
        <th>Receive Package</th>
        <th>Receive Date</th>
        <th>Labor Unload</th>
        <th>Equipment Name</th>
        <th>Equipment Load</th>

    </tr>
    </thead>
    <tbody>  @php($i=0)  @php($indTotalLabourUnload=0) @php($indTotalEquipmentUnload=0)
    @foreach($indianTruckData as $key => $indianTruck)
        <tr>
            <td> {{ ++$i }}</td>
            <td>{{ $indianTruck->manifest }}</td>
            <td>{{ $indianTruck->truck_no }}</td>
            <td>{{ $indianTruck->driver_name }}</td>
            <td>{{ $indianTruck->nweight }}</td>
            <td>{{ $indianTruck->receive_package }}</td>
            <td>{{ $indianTruck->receive_datetime }}</td>
            <td>{{ $indianTruck->labor_unload }}</td>@php($indTotalLabourUnload+= $indianTruck->labor_unload)
            <td>{{ $indianTruck->equip_name }}</td>
            <td>{{ $indianTruck->equip_unload }}</td>@php($indTotalEquipmentUnload+= $indianTruck->equip_unload)
        </tr>
    @endforeach
    </tbody>


</table>
<p><b>
        &nbsp; Total Truck: {{ $i }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Gross Weight: {{ $indTotalEquipmentUnload+ $indTotalLabourUnload}} &nbsp;

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Total Labour: {{ $indTotalLabourUnload }} &nbsp;

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Total Equipment: {{ $indTotalEquipmentUnload }}&nbsp;

    </b>
</p>
<table width="550">
    <caption style="padding-bottom: 10px;"><b><u>BD Truck Details</u></b></caption>
    <thead>
    <tr>
        <th>S/L</th>
        <th>Manifest No.</th>
        <th>Truck No.</th>
        <th>Driver Name</th>
        <th>Gross Weight</th>
        <th>Package</th>
        <th>Delivery Date</th>
        <th>Approve Date</th>
        <th>Labor Load</th>
        <th>Equipment Name</th>
        <th>Equipment Load</th>
        <!-- <th>Actions</th> -->
    </tr>
    </thead>
    <tbody> @php($i=0)  @php($bdTotalLabourUnload=0) @php($bdTotalEquipmentUnload=0)
    @foreach($bdTruckData as $key => $bdTruck)
        <tr>
            <td> {{ ++$i }}</td>
            <td>{{ $bdTruck->manifest }}</td>
            <td>{{ $bdTruck->truck_no }}</td>
            <td>{{ $bdTruck->driver_name }}</td>
            <td>{{ $bdTruck->gweight }}</td>
            <td>{{ $bdTruck->package }}</td>
            <td>{{ $bdTruck->delivery_dt }}</td>
            <td>{{ $bdTruck->approve_dt }}</td>
            <td>{{ $bdTruck->labor_load }}</td>@php($bdTotalLabourUnload+= $bdTruck->labor_load)
            <td>{{ $bdTruck->equip_name }}</td>
            <td>{{ $bdTruck->equip_load }}</td>@php($bdTotalEquipmentUnload+= $bdTruck->equip_load)
        </tr>
    @endforeach
    </tbody>
</table>

<p><b>
        &nbsp; Total Truck: {{ $i }}&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Gross Weight: {{ $bdTotalLabourUnload+$bdTotalEquipmentUnload }} &nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Total Labour: {{ $bdTotalLabourUnload }} &nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        Total Equipment: {{ $bdTotalEquipmentUnload }}&nbsp;

    </b>
</p>



</body>
</html>