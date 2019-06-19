<!DOCTYPE html>
<html>
<head>
    <title>Date Wiese BD Truck Entry Report Export</title>
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
            <span style="font-size: 19px;">{{$from_date}} Date Wise BD Truck Entry Report</span> <br>


        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($from_date))}}
        </td>
    </tr>
</table>
<br>




{{--<br><br><br>--}}
<table style="page-break-inside:avoid;">
    <caption style="padding-bottom: 10px;"><b><u></u></b></caption>
    <thead>
    <tr>
        <th>S/L</th>
        {{--<th>Date</th>--}}
        <th>Manifest No</th>
        <th>Truck No</th>
        <th>Driver Name</th>
        <th>Entry Date</th>


        {{--<th>Truck Type</th>--}}
        <th>Entry By</th>
        {{--<th>Created Time</th>--}}
        {{--<th>Night Charges</th>--}}
        {{--<th>Holiday Charges</th>--}}
        {{--<th>Weighment Charge</th>--}}
        {{--<th>Removal Charges</th>--}}
        {{--<th>Truck Terminal Entry Fee</th>--}}
        {{--<th>Truck Terminal Haltage Fee</th>--}}
        {{--<th>Import Terminal Entry Fee</th>--}}
        {{--<th>Import Terminal Haltage Fee</th>--}}
        {{--<th>Miscellaneous Charges</th>--}}
        {{--<th>Total Taka</th>--}}
        {{--<th>VAT</th>--}}
    </tr>
    </thead>
    <tbody>
    @if(isset($DWRDate)  && count($DWRDate) > 0)


        @foreach($DWRDate as $key => $u)
            <tr>
                <td>{{ $key+1}}</td>
                <td>{{$u->manifest}}</td>
                <td>{{$u->truck_no}}</td>
                <td>{{ $u->driver_name}}</td>
                <td>{{date('d-m-Y',strtotime($u->delivery_req_dt))}}</td>


                <td>{{ $u->name }}</td>
                {{--<td>{{ $u->truckentry_datetime }}</td>--}}
                {{--<td>{{ $u->created_time }}</td>--}}
                {{--<td>{{ $u->Night_Charge }}</td>--}}
                {{--<td>{{ $u->Holiday_Charge }}</td>--}}
                {{--<td>{{ $u->Weighment_Charge }}</td>--}}
                {{--<td>{{ $u->Removal_Charge }}</td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td></td>--}}
                {{--<td>{{ number_format( $u->total,2) }}</td>--}}
                {{--<td class="amount-right">{{ number_format($u->total_Vat,2)}}</td>--}}
                {{--<td>{{ number_format($u->total_Vat,2) }}</td>--}}
            </tr>
        @endforeach

    @endif
    </tbody>
</table>
<p style="text-align: right"><b>Total BD Trucks Entry : {{ $key +1}}</b></p>
</body>
</html>