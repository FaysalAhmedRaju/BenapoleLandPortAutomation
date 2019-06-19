<!DOCTYPE html>
<html>
<head>
    <title>Weight Bridge Entry Report</title>

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
            From {{date('d-m-Y',strtotime($from_date))}} to {{date('d-m-Y',strtotime($to_date))}} Weight Bridge Entry Report
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b> {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
        </td>

    </tr>
</table>
<br>
<table width="100%" class="dataTable">
    <thead>
    <tr>
        <th style="width: 10px; /*font-size: 13px;*/">S/L</th>
        <th>Manifest No.</th>
        <th style="width: 90px; /*font-size: 13px;*/">Truck No.</th>
        <th style="width: 90px; /*font-size: 13px;*/">Driver Name</th>
        <th style="width: 100px; /*font-size: 13px;*/">Gross Weight</th>
        <th style="width: 110px; /*font-size: 13px;*/">Entry Time</th>
        <th>Entry By</th>
        {{--<th>Created By</th>--}}
        <th>Tare Weight</th>
        <th>Net Weight</th>
        {{--<th>Weighbridge Exit Time</th>--}}
        {{--<th>Created By</th>--}}
    </tr>
    </thead>
    <tbody>	@php($i=0)
    @php($total_gweight_wbridge=0)
    @php($total_tr_weight=0)
    @php($total_tweight_wbridge=0)
    @foreach($todaysWeightBridgeEntry as $key => $weightBridgeEntry)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ $weightBridgeEntry->manifest }}</td>
            <td>{{ $weightBridgeEntry->truck_type."-".$weightBridgeEntry->truck_no }}</td>
            <td>{{ $weightBridgeEntry->driver_name }}</td>
            <td>{{ $weightBridgeEntry->gweight_wbridge }} @php($total_gweight_wbridge+=is_numeric($weightBridgeEntry->gweight_wbridge) ? $weightBridgeEntry->gweight_wbridge : 0)</td>
            <td>{{ $weightBridgeEntry->wbrdge_time1 }}</td>
            <td>{{ $weightBridgeEntry->name }}</td>
            {{--<td>{{ $weightBridgeEntry->wbridg_user1 }}</td>--}}
            <td>{{ $weightBridgeEntry->tr_weight }}@php($total_tr_weight+=is_numeric($weightBridgeEntry->tr_weight ) ? $weightBridgeEntry->tr_weight : 0)</td>
            <td>{{ $weightBridgeEntry->tweight_wbridge }}@php($total_tweight_wbridge+=is_numeric($weightBridgeEntry->tweight_wbridge) ? $weightBridgeEntry->tweight_wbridge : 0)</td>
            {{--<td>{{ $weightBridgeEntry->wbrdge_time2 }}</td>--}}
            {{--<td>{{ $weightBridgeEntry->wbridg_user2 }}</td>--}}
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td>Total:</td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> {{$total_gweight_wbridge>0 ?$total_gweight_wbridge:''}}</td>
        <td> </td>
        <td> </td>
        <td>{{$total_tr_weight>0 ?$total_tr_weight:''}} </td>
        <td>{{$total_tweight_wbridge>0 ?$total_tweight_wbridge:'' }} </td>
        


    </tr>
    </tfoot>
</table>
<p style="text-align: right"><b>Total: {{ $i }}</b> </p>
</body>
</html>