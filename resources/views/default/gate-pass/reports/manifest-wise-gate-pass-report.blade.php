<!DOCTYPE html>
<html>
<head>
    <title>Manifest Wise Gate Pass Report</title>
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
            /*text-align: center;*/

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
           {{-- {{date('d-m-Y',strtotime($today))}} 's--}} Manifest {{--<b>{{$manifest}}</b>--}} Wise GATE PASS Report
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b> {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
        </td>

    </tr>
</table>

<br>
<table  class="dataTable">

    <thead>
    <tr>
        <th style=" font-size: 15px;"><nobr>S/L</nobr></th>
        <th style="font-size: 15px;"><nobr>Manifest No</nobr></th>
        <th style=" font-size: 15px;"><nobr>Goods Description</nobr></th>
        <th style=" font-size: 15px;"><nobr>Importer Name</nobr></th>
        <th style=" font-size: 15px;"><nobr>Shed-Yard</nobr></th>

        <th style="font-size: 15px;"><nobr>CNF Name</nobr></th>
        <th style=" font-size: 15px;"><nobr>Delivery Date</nobr></th>
        <th style="font-size: 15px;"><nobr>Challan No</nobr></th>
        <th style=" font-size: 15px;"><nobr>Gate Pass No</nobr></th>
        <th style=" font-size: 15px;"><nobr>Created By</nobr></th>
        {{--<th style="font-size: 10px;"><nobr>L.C No. & Date</nobr></th>--}}
        {{--<th style=" font-size: 10px;"><nobr>B/E No. & Date</nobr></th>--}}
        {{--<th style=" font-size: 10px;"><nobr>Indian B/E No. & Date</nobr></th>--}}
    </tr>
    </thead>
    <tbody> @php($i=0)
    @foreach($requestData as $key => $details)
        <tr>
            <td> {{ ++$i }}</td>
            <td> <u>{{ $details->manifest }}</u> <br> {{ $details->manifest_date }}<br>@if($details->partial_status >1)(Partial)@endif
                {{--<span ng-if="$details->partial_status>1">(Partial)</span>--}}</td>
            <td>{{ $details->cargo_name }}</td>
            <td>{{ $details->importer_name }}</td>
            <td>{{ $details->yard_shed_name }}</td>

            <td>{{ $details->cnf_name }}</td>
            <td>{{ $details->approximate_delivery_date }}</td>
            <td>{{ $details->challan_no }}</td>
            <td>{{ $details->gate_pass_no }}</td>
            <td>{{ $details->created_by }}</td>
            {{--<td>{{ $manifestDetail->lc_no . " ". $manifestDetail->lc_date }}</td>--}}
            {{--<td>{{ $manifestDetail->be_no . " ". $manifestDetail->be_date}}</td>--}}
            {{--<td>{{ $manifestDetail->ind_be_no . " ". $manifestDetail->ind_be_date}}</td>--}}
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="10">
            <p style="text-align: right"><b>Total: {{ $i }}</b> </p>
        </td>
    </tr>
    </tfoot>
</table>

</body>


</html>