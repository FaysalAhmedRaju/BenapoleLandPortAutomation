<!DOCTYPE html>
<html>
<head>
    <title>Import-Export Information</title>

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

        .txt-right {
            text-align: right;
        }

        .txt-left {
            text-align: left;
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
            {{$year}}-{{$year+1}} Import-Export Information
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b> {{$todayWithTime}}
        </td>

    </tr>
</table>
<br>
<table width="100%" class="dataTable">
    <thead>
    <tr>
        <th rowspan="2">Month Name</th>
        <th colspan="3">Truck Count</th>
        <th colspan="2">Import</th>
        <th colspan="2">Export</th>
    </tr>
    <tr>
        <th>Foreign Truck</th>
        <th>Local Truck</th>
        <th>Total Truck</th>
        <th>Amount<br>(M. Ton)</th>
        <th>Taka</th>
        <th>Amount<br>(M. Ton)</th>
        <th>Taka</th>   
    </tr>
    <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>  
    </tr>
        @foreach($data as $key => $t)
            <tr>
                <td class="txt-left">{{ $t->month_name."'".$t->year_name }} </td>
                <td class="txt-right">{{ $t->total_foreign_truck_count }}</td>
                <td class="txt-right">{{ $t->total_local_truck_count }}</td>
                <td class="txt-right">{{ $t->total_foreign_truck_count + $t->total_local_truck_count }}</td>
                <td class="txt-right">{{number_format($t->receive_weight_ton , 2, '.', ',') }}</td>
                <td class="txt-right">&nbsp;</td>
                <td class="txt-right">&nbsp;</td>
                <td class="txt-right">&nbsp;</td>
            </tr>
        @endforeach
    </thead>
    <tbody>
    </tbody>
</table>
</body>
</html>