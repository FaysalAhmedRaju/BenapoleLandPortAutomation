<!DOCTYPE html>
<html>
<head>
	<title>Yearly Fixed Maintenance Report</title>
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
            text-align: center;
        }

        table.dataTable, table.dataTable th, table.dataTable td {
            border: 1px solid black;
            padding: 1px;
            text-align: center;
        }
        /*.center{*/
            /*position: absolute;*/
            /*text-align: center;*/
            /*top: 0;*/
            /*left: 300px;*/
        /*}*/

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
            <span style="font-size: 19px;">{{$year}}-{{$year+1}} Fixed Maintenance Report</span> <br>
        </td>
        <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
            <b>Time:</b>  {{date('d-m-Y h:i:s A',strtotime($todayWithTime))}}
        </td>

    </tr>
</table>
<br>
<table class="dataTable" {{--width="100%;"--}} style="page-break-inside:avoid;">

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
                <td>{{ $key+1 }}</td>
                <th>{{$ex->acc_sub_head}}</th>
                <td class="amount-right">{{ number_format( $ex->Budget , 0)==0 ? "" : number_format( $ex->Budget , 0, '.', ',') }}</td>
                <td class="amount-right">{{ number_format( $ex->July , 0)==0 ? "" : number_format( $ex->July , 0, '.', ',') }}</td>
                <td class="amount-right">{{ number_format($ex->August, 0)==0 ? "" : number_format($ex->August, 0, '.', ',')}}</td>
                <td class="amount-right">{{ number_format($ex->September, 0)== 0 ? "" : number_format($ex->September, 0, '.', ',') }}</td>
                <td class="amount-right">{{number_format($ex->October, 0)==0 ? "" : number_format($ex->October, 0, '.', ',') }}</td>
                <td class="amount-right">{{ number_format($ex->November,0)==0 ? "" : number_format($ex->November, 0, '.', ',') }}</td>
                <td class="amount-right">{{ number_format($ex->December, 0)==0 ? "" : number_format($ex->December, 0, '.', ',') }}</td>
                <td class="amount-right">{{number_format($ex->January, 0)==0 ? "" : number_format($ex->January, 0, '.', ',') }}</td>
                <td class="amount-right">{{ number_format($ex->February, 0)==0 ? "" : number_format($ex->February, 0, '.', ',')  }}</td>
                <td class="amount-right">{{ number_format($ex->March, 0)==0 ? "" : number_format($ex->March, 0, '.', ',') }}</td>
                <td class="amount-right">{{ number_format( $ex->April,0)==0 ? "" : number_format( $ex->April, 0, '.', ',') }}</td>
                <td class="amount-right">{{ number_format( $ex->May, 0)==0 ? "" : number_format( $ex->May, 0, '.', ',') }}</td>
                <td class="amount-right">{{number_format($ex->June, 0)==0  ? "" : number_format($ex->June, 0, '.', ',')}}</td>
                {{--<td class="txt-right">{{ number_format($ex->Total , 0, '.', ',')}}</td>--}}
                <td class="amount-right">{{ number_format($ex->Total , 0)==0 ? "" : number_format($ex->Total , 0, '.', ',') }}</td>
                <td>{{$ex->Budget - $ex->Total }}</td>
            </tr>


            @else
                <tr>
                    <td width="60"></td>
                    <th>{{$ex->acc_sub_head}}</th>
                    <th class="amount-right">{{ number_format( $ex->Budget , 0)==0 ? "" : number_format( $ex->Budget , 0, '.', ',') }}</th>
                    <th class="amount-right">{{ number_format( $ex->July , 0)==0 ? "" : number_format( $ex->July , 0, '.', ',') }}</th>
                    <th class="amount-right">{{ number_format($ex->August, 0)==0 ? "" : number_format($ex->August, 0, '.', ',')}}</th>
                    <th class="amount-right">{{ number_format($ex->September, 0)== 0 ? "" : number_format($ex->September, 0, '.', ',') }}</th>
                    <th class="amount-right">{{number_format($ex->October, 0)==0 ? "" : number_format($ex->October, 0, '.', ',') }}</th>
                    <th class="amount-right">{{ number_format($ex->November, 0)==0 ? "" : number_format($ex->November, 0, '.', ',') }}</th>
                    <th class="amount-right">{{ number_format($ex->December, 0)==0 ? "" : number_format($ex->December, 0, '.', ',') }}</th>
                    <th class="amount-right">{{number_format($ex->January, 0)==0 ? "" : number_format($ex->January, 0, '.', ',') }}</th>
                    <th class="amount-right">{{ number_format($ex->February, 0)==0 ? "" : number_format($ex->February, 0, '.', ',')  }}</th>
                    <th class="amount-right">{{ number_format($ex->March, 0)==0 ? "" : number_format($ex->March, 0, '.', ',') }}</th>
                    <th class="amount-right">{{ number_format( $ex->April, 0)==0 ? "" : number_format( $ex->April, 0, '.', ',') }}</th>
                    <th class="amount-right">{{ number_format( $ex->May, 0)==0 ? "" : number_format( $ex->May, 0, '.', ',') }}</th>
                    <th class="amount-right">{{number_format($ex->June, 0)==0  ? "" : number_format($ex->June, 0, '.', ',')}}</th>
                    {{--<td class="txt-right">{{ number_format($ex->Total , 0, '.', ',')}}</td>--}}
                    <th class="txt-right amount-right">{{ number_format($ex->Total , 0)==0 ? "" : number_format($ex->Total , 2, '.', ',') }}</th>
                    <th>{{$ex->Budget - $ex->Total }}</th>
                </tr>
                @endif

        @endforeach
        </tbody>

		</table>


	</body>
</html>