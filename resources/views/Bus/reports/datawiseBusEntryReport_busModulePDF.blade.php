<!DOCTYPE html>
<html>
<head>
    <title>Date Wiese Bus Entry Report Export</title>
    <style>

        html {
            margin: 5px 12px 0;
        }

        table.dataTable {
            border-collapse: collapse;
        }

        table.dataTable, table.dataTable th, table.dataTable td {
            /*border: 1px solid black;*/
            padding: 5px;
            text-align: center;
            border: 0px;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 250px;
        }




        table {
            border-collapse: collapse;
            width: 100%;

        }
        table, th, td {
            border: 1px solid black;
            padding: 1px;
            text-align: center;
        }
        /*.center{*/
        /*position: absolute;*/
        /*text-align: center;*/
        /*top: 0;*/
        /*left: 200px;*/
        /*}*/
    </style>
</head>
<body>

<table width="100%;"  class="dataTable">
    <tr>
        <td style="width: 15%">
            <img src="../public/img/blpa.jpg" height="100">
        </td>
        <td style="width: 60%; text-align:center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
            <span style="font-size: 19px;">Date Wise Bus Entry Report</span> <br>


        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
            {{--Print Date : {{$todayWithTime}}--}}
        </td>
    </tr>
</table>
<br>





<table style="page-break-inside:avoid;">
    <caption style="padding-bottom: 10px;"><b><u></u></b></caption>
    <thead>
    <tr>
        <th>S/L</th>
        <th>Bus No</th>
        <th>Entry Date</th>
        <th>Holtage Time(Day)</th>
        <th>Entrance Fee</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($DWRDate)  && count($DWRDate) > 0)


        @foreach($DWRDate as $key => $u)
            <tr>
                <td>{{ $key+1}}</td>
                <td>{{ $u->type_name }} - {{ $u->truck_bus_no }}</td>
                <td>{{date('d-m-Y',strtotime($u->entry_datetime))}}</td>
                <td>{{ $u->haltage_day}}</td>
                <td>{{ $u->entrance_fee }}</td>
            </tr>
        @endforeach

    @endif
    </tbody>
</table>
<p style="text-align: right"><b>Total Trucks Entry : {{ $key +1}}</b></p>
</body>
</html>