<!DOCTYPE html>
<html>
<head>
    <title>

       Manifest Shed Yard Wise Details Report</title>
    <style>
        table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        th {
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
                                   {{date('d-m-Y',strtotime($receive_date))}}'s Manifest Details Report
    {{--{{date("d-m-Y",strtotime($date))}} @if(Auth::user()->role->name == 'TransShipment')--}}
        {{--Transhipment--}}
    {{--@else--}}
        {{--WareHouse--}}
    {{--@endif--}}
    {{--{{$typeOfReports}} --}}
</p>
<h5 style="text-align: right;padding-right: 35px;">
    <b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
</h5>
<table width="100%">
    <thead>
    {{--<tr>--}}
        {{--<th style=" font-size: 11px">&nbsp;</th>--}}
        {{--<th style="font-size: 11px"><nobr>&nbsp;</nobr></th>--}}
        {{--<th style="font-size: 11px"><nobr>&nbsp;</nobr></th>--}}
        {{--<th style=" font-size: 11px"><nobr>&nbsp;</nobr></th>--}}
        {{--<th style=" font-size: 11px" colspan="5"><nobr>Receive</nobr></th>--}}
        {{--<th style=" font-size: 11px" colspan="2"><nobr>Labor</nobr></th>--}}
        {{--<th style=" font-size: 11px" colspan="3"><nobr>Equipment</nobr></th>--}}
    {{--</tr>--}}
    <tr>
        <th style=" font-size: 11px">S/L</th>
        <th style="font-size: 11px"><nobr>Manifest No.<br>And Date</nobr></th>
        <th style="width: 100px; font-size: 11px"><nobr>Truck No.</nobr></th>
        <th style="font-size: 11px"><nobr>Description Goods</nobr></th>
        <th style="font-size: 11px"><nobr>Importer Name</nobr></th>
        {{--<th style="font-size: 11px"><nobr>Shed/Yard</nobr></th>--}}
        <th style=" font-size: 11px"><nobr>Weight</nobr></th>
        <th style=" font-size: 11px"><nobr>Package</nobr></th>
        {{--<th style="font-size: 11px"><nobr>Comment</nobr></th>--}}

        <th style=" font-size: 11px"><nobr>Receive Time</nobr></th>

        {{--<th style=" font-size: 11px"><nobr>By</nobr></th>--}}
        <th style=" font-size: 11px"><nobr>Unload</nobr></th>
        <th  style="font-size: 11px"><nobr>Package</nobr></th>
        <th style=" font-size: 11px"><nobr>Unload</nobr></th>
        <th  style=" font-size: 11px"><nobr>Name</nobr></th>
        <th style=" font-size: 11px"><nobr>Package</nobr></th>
    </tr>
    </thead>
    <tbody> @php($i=0)  @php($sumLabourUnload=0) @php($sumEquipmentUnload=0)
    @foreach($todaysWareHouseEntry as $key => $wareHouseEntry)
        <tr>
            <td> {{ ++$i }}</td>
            <td><u>{{ $wareHouseEntry->manifest }}</u><br>{{$wareHouseEntry->manifest_date}}</td>
            <td>{{ $wareHouseEntry->truck_type."-".$wareHouseEntry->truck_no }}</td>
            <td>{{ $wareHouseEntry->cargo_name }}</td>
            <td>{{ $wareHouseEntry->NAME }}</td>
            {{--<td>{{ $wareHouseEntry->yard_shed_name }}</td>--}}

            <td>{{ $wareHouseEntry->receive_weight }}</td>
            <td>{{ $wareHouseEntry->receive_package }}</td>
            {{--<td>{{ $wareHouseEntry->unload_comment }}</td>--}}

            <td>{{ $wareHouseEntry->receive_time }}</td>

            {{--<td>{{  $wareHouseEntry->name }}</td>--}}
            <td>{{ $wareHouseEntry->unload_labor_weight }}</td> @php($sumLabourUnload+= $wareHouseEntry->unload_labor_weight)
            <td>{{ $wareHouseEntry->unload_labor_package }}</td>
            <td>{{ $wareHouseEntry->unload_equip_weight }}</td> @php($sumEquipmentUnload += $wareHouseEntry->unload_equip_weight )
            <td>{{ $wareHouseEntry->unload_equip_name }}</td>
            <td>{{ $wareHouseEntry->unload_equipment_package }}</td>
            {{--<td>{{ $wareHouseEntry->carpenter?'Yes':'No' }}</td>
            <td>{{ $wareHouseEntry->offloading_flag?'Equipment':'Labour' }}</td>
            <td>{{ $wareHouseEntry->equip_name }}</td>--}}
        </tr>
    @endforeach
    </tbody>
</table>
<p><b>Total Truck: {{ $i }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Gross Weight: {{ $sumLabourUnload + $sumEquipmentUnload }}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Labor Total: {{ $sumLabourUnload }}&nbsp;&nbsp;

        Equ. Total: {{ $sumEquipmentUnload }}</b> </p>

</body>
</html>