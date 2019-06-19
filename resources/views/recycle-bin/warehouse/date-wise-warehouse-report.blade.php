
<!DOCTYPE html>
<html>
<head>
    <title>Date Wise
    @if(Auth::user()->role->name == 'TransShipment')
        Transhipment
    @else
        WareHouse
    @endif
    Report</title>



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
{{--<img src="../public/img/blpa.jpg">
<p class="center"><span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span>  <br>
    <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    {{$requestedDate}} 's 
    @if(Auth::user()->role->name == 'TransShipment')
        Transhipment
    @else
        WareHouse
    @endif
    Entry Report</p>
<h5 style="text-align: right;padding-right: 35px;"> Date: {{$date}}</h5>

<table width="550">--}}


<table width="100%" border="0">
    <tr>
        <td style="width: 25%">
            <img src="../public/img/blpa.jpg" height="100">
        </td>
        <td style="width: 50%; text-align: center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
            {{date('d-m-Y',strtotime($requestedDate))}} 's
            @if(Auth::user()->role->name == 'TransShipment')
                Transhipment
            @else
                WareHouse
            @endif
            Delivery Request
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
        <th>Truck No.</th>
        <th>Receive By</th>

    </tr>
    </thead>
    <tbody>
    @foreach($data as $key => $t)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $t->manifes_no }}</td>
            <td>{{ $t->truck_type }}-{{ $t->truck_no }}</td>
            <td>{{ $t->receive_by }}</td>


        </tr>
    @endforeach

    </tbody>

    <tfoot>
    <tr>
        <td colspan="4">
            <p style="text-align: right; color: black;"><b>Total Trucks: {{ $key +1}} </b> </p>
        </td>
    </tr>
    </tfoot>
</table>




<br>

{{--Incompleted Manifest Report--}}

</body>
</html>

