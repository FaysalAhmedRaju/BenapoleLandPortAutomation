<!DOCTYPE html>
<html>
<head>
    <title>Yearly Truck Entry Exit</title>
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
            {{$year}}'s Truck Entry-Exit Report
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
        <th style="width:25%; ">Month</th>
        <th style="width:25%; ">Entry Truck No.</th>
        <th style="width:25%; ">Exit Truck No.</th>
        <th>Total</th>

    </tr>
    </thead>
    <tbody>	@php($i=0)
    @php($total_entry=0)
    @php($total_exit=0)

    @foreach($data as $key => $value)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{date('F Y',strtotime($value->truckentry_datetime))}}</td>
            <td>{{ $value->entry_truck }}@php($total_entry+=$value->entry_truck)</td>
            <td>{{ $value->exit_truck }}@php($total_exit+=$value->exit_truck)</td>
            <td>{{$value->entry_truck + $value->exit_truck }}</td>
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