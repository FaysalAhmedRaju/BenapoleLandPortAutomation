<!DOCTYPE html>
<html>
<head>
    <title>Yearly Report</title>
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
            padding:2px  1px;
            text-align: center;
            font-size: 15px;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 300px;
        }

        .txt-right{
            text-align: right;
        }
        .amount-right{
            text-align: right!important;

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

            <span style="font-size: 19px;">{{$year}} - {{$year+1}} Truck Challan Report</span> <br>
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
            {{--Print Date : {{$todayWithTime}}--}}
        </td>
    </tr>
</table>
<br>




<br>
<table style="page-break-inside:avoid;">
    <caption style="padding-bottom: 10px;"><b><u></u></b></caption>
    <thead>
    <tr>
        <th>S/l</th>
        {{--<th>Expenditure</th>--}}
        <th>July</th>
        <th>August</th>
        <th>September</th>
        <th>October</th>
        <th>November</th>
        <th>December</th>
        <th>January </th>
        <th>February </th>
        <th>March</th>
        <th>April</th>
        <th>May </th>
        <th>June</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($expenditure)  && count($expenditure) > 0)
        @foreach($expenditure as $key => $ex)
            <tr>
                <td width="60">{{ ++$key }}</td>
                {{--<th>{{$ex->acc_sub_head}}</th>--}}
                <td class="amount-right">{{ number_format( $ex->July , 2)==0 ? "" : number_format( $ex->July , 2, '.', ',') }}</td>
                <td class="amount-right">{{ number_format($ex->August, 2)==0 ? "" : number_format($ex->August, 2, '.', ',')}}</td>
                <td class="amount-right">{{ number_format($ex->September, 2)== 0 ? "" : number_format($ex->September, 2, '.', ',') }}</td>
                <td class="amount-right">{{number_format($ex->October, 2)==0 ? "" : number_format($ex->October, 2, '.', ',') }}</td>
                <td class="amount-right">{{ number_format($ex->November, 2)==0 ? "" : number_format($ex->November, 2, '.', ',') }}</td>
                <td class="amount-right">{{ number_format($ex->December, 2)==0 ? "" : number_format($ex->December, 2, '.', ',') }}</td>
                <td class="amount-right">{{number_format($ex->January, 2)==0 ? "" : number_format($ex->January, 2, '.', ',') }}</td>
                <td class="amount-right">{{ number_format($ex->February, 2)==0 ? "" : number_format($ex->February, 2, '.', ',')  }}</td>
                <td class="amount-right">{{ number_format($ex->March, 2)==0 ? "" : number_format($ex->March, 2, '.', ',') }}</td>
                <td class="amount-right">{{ number_format( $ex->April, 2)==0 ? "" : number_format( $ex->April, 2, '.', ',') }}</td>
                <td class="amount-right">{{ number_format( $ex->May, 2)==0 ? "" : number_format( $ex->May, 2, '.', ',') }}</td>
                <td class="amount-right">{{number_format($ex->June, 2)==0  ? "" : number_format($ex->June, 2, '.', ',')}}</td>
                <td class="txt-right amount-right">{{ number_format($ex->Total , 2)==0 ? "" : number_format($ex->Total , 2, '.', ',') }}</td>
            </tr>
        @endforeach
    @endif

    </tbody>

</table>
</body>
</html>