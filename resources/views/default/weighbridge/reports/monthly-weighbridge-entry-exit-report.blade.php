<!DOCTYPE html>
<html>
<head>
    <title>Monthly Weight Bridge Entry Exit</title>
    <style>
        html {
            margin: 5px 5px 0;
        }

        body{
            background-image: url(/img/Logo_BSBK.gif);
            /*background: url(/img/blpa.jpg );*/
            background-repeat:no-repeat;
            background-position:center center;
            background-size:250px 180px;
            opacity: .2;
        }

        table.dataTable {
            border-collapse: collapse;
            text-align: center;
        }

        table.dataTable, table.dataTable th, table.dataTable td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        .center {
            position: absolute;
            text-align: center;
            top: 0;
            left: 250px;
        }
    </style>
</head>
<body>


<table width="100%;" border="0">
    <tr>
        <td style="width: 25%">
            <img src="../public/img/blpa.jpg" height="100">
        </td>
        <td style="width: 50%; text-align: center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
            {{$month}}'s WeighBridge Entry-Exit Report
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b> {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
        </td>

    </tr>
</table>

<br>
<table  class="dataTable" style="width: 100%;">
    <thead>
    <tr>
        <th style="width:25%; ">S/L</th>
        <th style="width:25%; ">Date</th>
        <th style="width:25%; ">Entry Truck No.</th>
        <th style="width:25%; ">Exit Truck No.</th>
        <th>Total</th>

    </tr>
    </thead>
    <tbody>	@php($i=0)
    @php($total_entry=0)
    @php($total_exit=0)

    @foreach($data as $key => $weightBridgeEntry)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{date('d-m-Y',strtotime($weightBridgeEntry->wbrdge_time1))}}</td>
            <td>{{ $weightBridgeEntry->entry_truck }}@php($total_entry+=$weightBridgeEntry->entry_truck)</td>
            <td>{{ $weightBridgeEntry->exit_truck }}@php($total_exit+=$weightBridgeEntry->exit_truck)</td>
            <td>{{ $weightBridgeEntry->entry_truck + $weightBridgeEntry->exit_truck }}</td>

        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Total:</th>
        <td></td>
        <th> {{$total_entry>0 ?$total_entry:''}}</th>
        <th>{{$total_exit>0 ?$total_exit:''}} </th>
        <th>{{$total_entry + $total_exit}}</th>
    </tr>


    </tfoot>
</table>




</body>
</html>