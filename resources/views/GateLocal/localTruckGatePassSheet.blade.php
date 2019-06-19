<!DOCTYPE html>
<html>
<head>
    <title>Today's Truck Entry Report</title>
    <style>
        html {
            margin: 5px 5px 0;
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
           GATE PASS FOR TRANSPORT WITH CARGO
        </td>
        <td style="width: 25%; font-size: 14px; text-align: left; vertical-align: bottom;">
            <b>Time Of Departure:</b> {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
        </td>

    </tr>
    <tr>
        <td>
            <b>Pass No.</b> {{$getManifestWithGatePass[0]->gate_pass_no}} <br>
            <b>Shed:</b> {{$getManifestWithGatePass[0]->yard_shed_name}} <br>
            <b>Manifest No:</b>  {{$manifestNo}}
        </td>
        <td></td>
        <td style="text-align: left">
            <b>Consignee:</b> {{$getManifestWithGatePass[0]->cnf_name}}<br>
            <b>Address: {{$getManifestWithGatePass[0]->address}}</b>
        </td>

    </tr>

</table>

<br>

<table width="100%" class="dataTable">
    <thead>
    <tr>
        <th>1</th>
        <th colspan="2">2</th>
        <th colspan="2">3</th>
        <th colspan="2">4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>
    </tr>
    <tr>
        <th rowspan="2">S/L</th>
        <th colspan="2">Transport Particular</th>
        <th colspan="2">Manifest</th>
        <th colspan="2">B/E OR E/A</th>
        <th style="width: 170px;font-size: 13px" rowspan="2">Description</th>
        <th style="width: 75px;font-size: 13px" rowspan="2">Marks</th>
        <th style="font-size: 13px" rowspan="2">Quantity</th>
        <th>Weight</th>
    </tr>
    <tr>
        <th>Type</th>
        <th>No.</th>

        <th style="font-size: 13px">No.</th>
        <th>Date</th>
        <th style="font-size: 13px">No.</th>
        <th>Date</th>
        <th style="width: 170px;font-size: 13px">Volumn</th>

    </tr>
    </thead>
    <tbody>

    @if(count($getLocalTrucks)>0)
        @foreach($getLocalTrucks as $k=>$localTruck)

        <tr>
            <td>{{++$k}}</td>
            <td>{{$localTruck->truck_type}}</td>
            <td>{{$localTruck->truck_no}}</td>
            <td>{{$localTruck->manifest}}</td>
            <td>{{date('d-m-Y',strtotime($localTruck->manifest_date))}}</td>
            <td>{{$localTruck->be_no}}</td>
            <td>{{date('d-m-Y',strtotime($localTruck->be_date))}}</td>
            <td>{{$localTruck->cargo_name}}</td>
            <td>{{$localTruck->marks_no}}</td>
            <td>{{ $localTruck->package_no }}</td>
            <td>{{ $localTruck->gweight }}</td>

        </tr>
        @endforeach


    @endif

    </tbody>
</table>
<br> <br>

<table width="100%">
    <tr>
        <td>
            <span>
        Checked &amp; Signed By C&F Agent <br>
        (with Card and Licened No. & Date)
    </span>
        </td>
        <td>
              <span>
       Signed By Custome Inspector <br>
        (with office seal and date)
    </span>
        </td>
        <td>
            <span>
        Signed By TI/WHS <br>
        (with office seal and date)
    </span>
        </td>
        <td>
            <span>
        Signed By TI/WHS (Exit Pass) <br>
        (with office seal and date)
    </span>
        </td>

    </tr>
</table>
<p>


</p>
</body>
</html>

