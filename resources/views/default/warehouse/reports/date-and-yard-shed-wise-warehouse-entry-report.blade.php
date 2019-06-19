<!DOCTYPE html>
<html>
<head>
    <title>
        @if(Auth::user()->role->name == 'TransShipment')
            Transhipment
        @else
            WareHouse
        @endif
        Entry Report</title>
    <style>
        /*table {*/
            /*border-collapse: collapse;*/
        /*}*/
        /*table, th, td {*/
            /*border: 1px solid black;*/
            /*padding: 5px;*/
        /*}*/

        table.dataTable {
            border-collapse: collapse;
            text-align: center;
        }

        table.dataTable, table.dataTable th, table.dataTable td {
            border: 1px solid black;
            padding: 5px;
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
    {{date("d-m-Y",strtotime($date))}} @if(Auth::user()->role->name == 'TransShipment')
        Transhipment
    @else
        WareHouse
    @endif
        Received Report For {{$todaysWareHouseEntry[0]->yard_shed_name}}

</p>
<h5 style="text-align: right;padding-right: 35px;">
   {{-- Date: {{$todayWithTime}}  --}}<b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
</h5>
<table width="100%" class="dataTable"  border="0">
    <thead>
    <tr>
        <th style=" font-size: 12px"><nobr>S/L</nobr></th>
        <th style="font-size: 12px"><nobr>Manifest No.</nobr></th>
        <th style=" font-size: 12px"><nobr>Truck No.</nobr></th>
        <th style=" font-size: 12px"><nobr>Receive Weight</nobr></th>
        <th style="font-size: 12px"><nobr>Receive Package</nobr></th>
        <th style=" font-size: 12px"><nobr>Receive Comment</nobr></th>
        <th style=" font-size: 12px"><nobr>Receive Datetime</nobr></th>
        <th style="font-size: 12px"><nobr>Labor Unload</nobr></th>
        <th style=" font-size: 12px"><nobr>Labor Package</nobr></th>
        <th style=" font-size: 12px"><nobr>Equipment Unload</nobr></th>
        <th style=" font-size: 12px"><nobr>Equipment Name</nobr></th>
        <th style=" font-size: 12px"><nobr>Equipment Package</nobr></th>
    {{--<th>Carpenter</th>
    <th>Offloding</th>
    <th>Equipment Name</th>--}}
    <!-- <th>Actions</th> -->
    </tr>
    </thead>
    <tbody> @php($i=0)  @php($sumLabourUnload=0) @php($sumEquipmentUnload=0)
    @foreach($todaysWareHouseEntry as $key => $wareHouseEntry)
        <tr>
            <td> {{ ++$i }}</td>
            <td>{{ $wareHouseEntry->manifest }}</td>
            <td>{{ $wareHouseEntry->truck_type."-".$wareHouseEntry->truck_no }}</td>
            <td>{{ $wareHouseEntry->receive_weight }}</td>
            <td>{{ $wareHouseEntry->receive_package }}</td>
            <td>{{ $wareHouseEntry->unload_comment }}</td>
            <td>{{ $wareHouseEntry->unload_receive_datetime }}</td>
            <td>{{ $wareHouseEntry->unload_labor_weight }}</td> @php($sumLabourUnload+= $wareHouseEntry->unload_labor_weight)
            <td>{{ $wareHouseEntry->unload_labor_package }}</td>
            <td>{{ $wareHouseEntry->unload_equip_weight }}</td> @php($sumEquipmentUnload += $wareHouseEntry->unload_equip_weight )
            <td>{{ $wareHouseEntry->unload_equip_name }}</td>
            <td>{{ $wareHouseEntry->unload_equipment_package }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<p><b>Total Truck: {{ $i }}&nbsp;&nbsp;&nbsp;&nbsp;Gross Weight: {{ $sumLabourUnload + $sumEquipmentUnload }}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Labor Total: {{ $sumLabourUnload }}&nbsp;&nbsp;&nbsp;&nbsp;Equip Total: {{ $sumEquipmentUnload }}</b> </p>

</body>
</html>