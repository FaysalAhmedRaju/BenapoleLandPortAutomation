<!DOCTYPE html>
<html>
<head>
    <title>Todays Truck Entry Report Export</title>
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


        table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
            text-align: center;
            padding: 2px;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 250px;
        }
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
            <span style="font-size: 19px;">Today's Truck Entry Report</span> <br>


        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}

        </td>
    </tr>
</table>
<br>


<table>
    <thead>
    <tr>

        <th>S/L</th>
        <th>Truck No</th>
        <th>Entry Date</th>
        <th>Holtage Time(Day)</th>
        <th>Entrance Fee</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($mainData)  && count($mainData) > 0)
        @foreach($mainData as $key => $manifestEntry)
            <tr>

                <td>{{ $key+1}}</td>
                <td>{{ $manifestEntry->type_name }} - {{ $manifestEntry->truck_bus_no }}</td>
                <th> {{date('d-m-Y',strtotime($manifestEntry->entry_datetime))}}</th>
                <td>{{ $manifestEntry->haltage_day }}</td>
                <td>{{ $manifestEntry->entrance_fee }}</td>

            </tr>
        @endforeach
    @endif

    </tbody>
</table>
<p style="text-align: right"><b>Total Trucks Entry: {{ $key +1}}</b> </p>


</body>
</html>