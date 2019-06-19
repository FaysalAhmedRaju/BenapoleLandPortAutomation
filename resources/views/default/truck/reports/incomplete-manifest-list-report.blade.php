<!DOCTYPE html>
<html>
<head>
    <title>Incomplete Manifest Report</title>
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
            InComplete Manifest List
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b> {{date('d-m-Y h:i:s A',strtotime($date))}}
        </td>

    </tr>
</table>


<h4 style="text-align: center; text-decoration: underline">Incomplete Manifest Report</h4>

<table width="100%" class="dataTable">
    <thead>
    <tr>
        <th>S/L</th>
        <th>Manifest No.</th>
        <th>Total Foreign Truck</th>
        <th>Remaining Foreign Truck</th>

    </tr>
    </thead>
    <tbody>
    @php($incompleteManifestCount=0)
    @if(isset($inCompleteManifestdata)  && count($inCompleteManifestdata) > 0)
        @foreach($inCompleteManifestdata as $key => $u)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $u->Manifest_No }}</td>
                <td>{{ $u->TotalForeignTruck }}</td>
                <td>{{ $u->remaining < 0 ? "" : $u->remaining }}</td>
            </tr>
            @php($incompleteManifestCount++)
        @endforeach
    @endif
    </tbody>
</table>

<p style="text-align: right"><b>Total Incomplete Manifest: {{$incompleteManifestCount}}</b></p>
</body>
</html>

