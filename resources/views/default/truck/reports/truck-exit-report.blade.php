<!DOCTYPE html>
<html>
<head>
    <title>Todays Truck Exit Report</title>

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

<table width="100%" border="0">
    <tr>
        <td style="width: 25%">
            <img src="../public/img/blpa.jpg" height="100">
        </td>
        <td style="width: 50%; text-align: center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
            {{date('d-m-Y',strtotime($date))}} 's Truck Exit Report
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b> {{date('d-m-Y h:i:s A',strtotime($date))}}
        </td>

    </tr>
</table>
<br>
<table width="100%" class="dataTable">
    <thead>
    <tr>
        <th style="width: 20px;font-size: 13px">S/L</th>
        <th style="width: 60px;font-size: 13px">Entry NO.</th>
        <th style="width: 100px;font-size: 13px">Entry Date</th>
        <th style="width: 110px; font-size: 13px">Truck No.</th>

        <th style="width: 110px;font-size: 13px">Manifest No.</th>
        <th style="width: 120px;font-size: 13px">Unloaded Shed/Yard</th>

        <th style="font-size: 13px;width: 70px">Driver Card</th>
        <th  style="width: 170px;font-size: 13px">Exit Date</th>

        <th  style="width: 110px;font-size: 13px">Exit By</th>

     {{--    <th>Comment</th> --}}


    </tr>
    </thead>
    <tbody>
    @foreach($manifestdata as $key => $u)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $u->truck_entry_sl}}</td>
            <td> {{date('d-m-Y',strtotime($u->truckentry_datetime))}}</td>
            <td>{{ $u->truck_type }}-{{ $u->truck_no }}</td>

            <th>{{ $u->manifest }}</th>
            <td>{{ $u->posted_yard_shed}}</td>

            <td>{{ $u->driver_card }}</td>
            <td> {{date('d-m-Y h:i:s A',strtotime($u->out_date))}}</td>
            <td style="font-size: 14px;text-transform: capitalize"> {{ $u->name }}</td>
            {{-- <td>{{ $u->out_comment }}</td> --}}



        </tr>
    @endforeach

    </tbody>
    <tfoot>
    <tr>
        <td colspan="9">
            <p style="text-align: right"><b>Total Trucks: {{$todaysTotalCount}}</b> </p>
        </td>
    </tr>
    </tfoot>
</table>

</body>
</html>

