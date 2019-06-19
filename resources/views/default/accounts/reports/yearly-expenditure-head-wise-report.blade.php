<!DOCTYPE html>
<html>
<head>
    <title>Yearly Head Wise Expenditure Report</title>
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
            padding: 0;
            text-align: center;
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

<table width="100%;" border="0">
    <tr>
        <td style="width: 25%">
            <img src="../public/img/blpa.jpg" height="100">
        </td>
        <td style="width: 50%; text-align: center">
            <span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span> <br>
            <span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
            <span style="font-size: 19px;">{{$year}}-{{$year+1}} Head Wise Expenditure  Report</span> <br>
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
        </td>

    </tr>
</table>
<table class="dataTable">
    <caption style="padding-bottom: 10px;"><b><u></u></b></caption>
    <thead>
    <tr>
        <th>S/l</th>
        <th>Expenditure</th>
        <th>Budget</th>
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
        <th>Remaining</th>
    </tr>
    </thead>
    <tbody>

    @foreach($expenditure as $key => $ex)

        @if(count($expenditure)!=$key+1)
        <tr>
            <td width="60">{{ $key+1 }}</td>
            <th>{{$ex->acc_head}}</th>

            <td class="amount-right">{{ number_format( $ex->Budget , 2)==0 ? "" : number_format( $ex->Budget , 2, '.', ',') }}</td>
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
            <td>{{$ex->Budget - $ex->Total }}</td>
        </tr>
        @else
            <tr>
                <td width="60"></td>
                <th>{{$ex->acc_head}}</th>

                <th class="amount-right">{{ number_format( $ex->Budget , 2)==0 ? "" : number_format( $ex->Budget , 2, '.', ',') }}</th>
                <th class="amount-right">{{ number_format( $ex->July , 2)==0 ? "" : number_format( $ex->July , 2, '.', ',') }}</th>
                <th class="amount-right">{{ number_format($ex->August, 2)==0 ? "" : number_format($ex->August, 2, '.', ',')}}</th>
                <th class="amount-right">{{ number_format($ex->September, 2)== 0 ? "" : number_format($ex->September, 2, '.', ',') }}</th>
                <th class="amount-right">{{number_format($ex->October, 2)==0 ? "" : number_format($ex->October, 2, '.', ',') }}</th>
                <th class="amount-right">{{ number_format($ex->November, 2)==0 ? "" : number_format($ex->November, 2, '.', ',') }}</th>
                <th class="amount-right">{{ number_format($ex->December, 2)==0 ? "" : number_format($ex->December, 2, '.', ',') }}</th>
                <th class="amount-right">{{number_format($ex->January, 2)==0 ? "" : number_format($ex->January, 2, '.', ',') }}</th>
                <th class="amount-right">{{ number_format($ex->February, 2)==0 ? "" : number_format($ex->February, 2, '.', ',')  }}</th>
                <th class="amount-right">{{ number_format($ex->March, 2)==0 ? "" : number_format($ex->March, 2, '.', ',') }}</th>
                <th class="amount-right">{{ number_format( $ex->April, 2)==0 ? "" : number_format( $ex->April, 2, '.', ',') }}</th>
                <th class="amount-right">{{ number_format( $ex->May, 2)==0 ? "" : number_format( $ex->May, 2, '.', ',') }}</th>
                <th class="amount-right">{{number_format($ex->June, 2)==0  ? "" : number_format($ex->June, 2, '.', ',')}}</th>
                {{--<td class="txt-right">{{ number_format($ex->Total , 0, '.', ',')}}</td>--}}
                <th class="txt-right amount-right">{{ number_format($ex->Total , 2)==0 ? "" : number_format($ex->Total , 2, '.', ',') }}</th>
                <th>{{$ex->Budget - $ex->Total }}</th>
            </tr>
            @endif

    @endforeach
    </tbody>

</table>
</body>
</html>