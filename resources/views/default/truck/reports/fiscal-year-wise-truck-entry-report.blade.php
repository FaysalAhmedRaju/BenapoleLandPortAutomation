<!DOCTYPE html>
<html>
<head>
    <title>Year Wise Truck Entry Report</title>
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
            {{$year}}-{{$year+1}} Truck Entry Report
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
        <th>S/L</th>
        <th>Manifest No.</th>
        <th>Description of Goods</th>
        <th>Truck No.</th>
        <th>Driver Card</th>
        <th>Entry Date</th>
        <th>Entry By</th>
    </tr>
    </thead>
    <tbody>
        @foreach($manifestdata as $key => $t)
            <tr>
                <td>{{ $key ++ }}</td>
                <th>{{ $t->manifest }} </th>
                <td> {{$t->cargo_name }}</td>
                <td>{{ $t->truck_type }}-{{ $t->truck_no }}</td>
                <td>{{ $t->driver_card }}</td>
                <td>{{$t->truckentry_datetime}}</td>
                <td>{{ $t->entryBy }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<p style="text-align: right"><b>Total Trucks: {{ $key + 1}} </b></p>


</body>
</html>

