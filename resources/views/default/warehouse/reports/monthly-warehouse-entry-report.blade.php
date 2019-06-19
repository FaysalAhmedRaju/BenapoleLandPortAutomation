
<!DOCTYPE html>
<html>
<head>
    <title>Monthly Warehouse Report</title>
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
        .center{

            text-align: center;

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
            Monthly Warehouse Entry
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b> {{ $todayWithTime }}
        </td>

    </tr>
</table>

<br>
<table width="100%" class="dataTable">
    <thead>
    <tr>
        <th>S/L</th>
        <th>Receive Date</th>
        <th>Total Receive</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $t)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ date('d-m-Y',strtotime($t->receive_datetime)) }}</td>
            <td>{{ $t->truck_count }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="4">
            <p style="text-align: right; color: black;"><b>Total Trucks: {{ $key +1 }} </b> </p>
        </td>
    </tr>
    </tfoot>
</table>
<br>
</body>
</html>