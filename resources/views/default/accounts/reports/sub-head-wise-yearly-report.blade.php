<!DOCTYPE html>
<html>
<head>
    <title>Yearly Report</title>
    <style>
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
            left: 450px;
        }

        .amount-right{
            text-align: right;
        }
    </style>
</head>
<body>


<img  src="../public/img/blpa.jpg">
<p class="center">
    <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
    <span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    <span style="font-size: 19px;">Sub-Head Wise {{$year}}-{{$year+1}} Expenditure Report</span> <br>
    <span style="font-size: 19px;">Name:&nbsp;{{$acc_sub_head}}</span> <br>


</p>
<br><br><br>
<table style="border: none !important;">
    <tr>
        <td  style="border: none !important; text-align: left">

        </td>
        <td  style="border: none !important; text-align: right">Print Date : {{$todayWithTime}}</td>

    </tr>
</table>

    <table style="page-break-inside:avoid;">
            <caption style="padding-bottom: 10px;"><b><u></u></b></caption>
            <thead>
            <tr>
                <th>S/l</th>
                <th>Expenditure</th>
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

        @foreach($expenditure as $key => $ex)
            <tr>
                <td width="60">{{ ++$key }}</td>
                <th>{{$ex->acc_sub_head}}</th>
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
                {{--<td class="txt-right">{{ number_format($ex->Total , 0, '.', ',')}}</td>--}}
                <td class="txt-right amount-right">{{ number_format($ex->Total , 2)==0 ? "" : number_format($ex->Total , 2, '.', ',') }}</td>
            </tr>
        @endforeach
        </tbody>

        </table>


</body>
</html>