<!DOCTYPE html>
<html>
<head>
    <title>Month Wise Truck Entry Report</title>
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
            From {{date('d-m-Y',strtotime($from_date))}} to {{date('d-m-Y',strtotime($to_date))}} Truck Entry Report | S/L : {{ $ranges }}
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
        <th style="font-size: 13px">S/L</th>
        <th style="font-size: 13px">Manifest No.</th>
        <th style="width: 170px;font-size: 13px">Description of Goods</th>
        <th style="width: 75px;font-size: 13px">Truck No.</th>
        <th style="font-size: 13px">Driver Card</th>
        <th style="width: 170px;font-size: 13px">Entry Date</th>
        <th style="width: 90px;font-size: 13px">Entry By</th>
    </tr>
    </thead>
    <tbody>
        @php
            $firstSl = $sl;
        @endphp
        @foreach($manifestdata as $key => $t)
            <tr>
                <td>{{ $sl++ }}</td>
                <th>{{ $t->manifest}} </th>
                <td> {{$t->cargo_name }}</td>
                <td>{{ $t->truck_type }}-{{ $t->truck_no }}</td>
                <td>{{ $t->driver_card }}</td>
                <td>{{date('d-m-Y h:i:s A',strtotime($t->truckentry_datetime))}}</td>
                <td style="font-size: 14px;text-transform: capitalize">{{ $t->entryBy }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<table width="100%">
    <tr>
        <td>
            <p style="text-align: right"><b>S/L : {{ $ranges }} | Total Trucks: {{ $key + 1}} </b></p>
        </td>
    </tr>
</table>

</body>
</html>

