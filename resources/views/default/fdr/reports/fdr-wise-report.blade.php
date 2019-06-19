<!DOCTYPE html>
<html>
<head>
	<title>FDR Wise Report</title>
	<style>
		table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 4px;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 300;
        }

        .amount-right{
            text-align: right!important;

        }
	</style>
</head>
<body>
		<img src="../public/img/blpa.jpg">
		<p class="center">
			<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
			<span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    		FDR Wise Report as on {{$today}}
    	</p>
    	<p>
    		<span><b>FDR No. :</b> {{ $FDRWiseReport[0]->fdr_no }}</span><br>
    		<span><b>Name of Bank :</b> {{ $FDRWiseReport[0]->bank_name }}</span>
    	</p>
	 	<table>
			<thead>
			<tr>
				<th width="50">SL.<br>No.</th>
                {{-- <th>Name of Bank</th>
                <th>F.D.R No</th> --}}
                <th>Main Amount</th>
                <th width="60">Openning Date</th>
                <th>Duration</th>
                <th width="60">Maturity Date</th>
                <th>Rate of Interest(%)</th>
                <th>Total Interest</th>
                <th>Income Tax</th>
                <th>Excavator Tariff</th>
                <th>Net Interest</th>
                <th>Bank Charge</th>
                <th>VAT(%)</th>
                <th>Total Balance</th>
                <th>Comment</th>

			</tr>
			</thead>
			<tbody>	@php($i=0)
			@foreach($FDRWiseReport as $key => $FDR)
				<tr>
					<td>{{ isset($FDR->sl_no) ? $FDR->sl_no : "" }}</td>
					{{-- <td>{{ $FDR->bank_name }}</td>
					<td>{{ $FDR->fdr_no }}</td> --}}
					<td class="amount-right">{{ $FDR->main_amount !=0 ? number_format($FDR->main_amount,2,'.',',') : "" }}</td>
					<td>{{ $FDR->opening_date != 0 ? $FDR->opening_date : "" }}</td>
					<td>{{ $FDR->duration != 0 ? $FDR->duration : "" }}</td>
					<td>{{ $FDR->expire_date != 0 ? $FDR->expire_date : "" }}</td>
					<td class="amount-right">{{$FDR->interest_rate != 0 ? number_format($FDR->interest_rate,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $FDR->total_interest != 0 ? number_format($FDR->total_interest,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $FDR->income_tax != 0 ? number_format($FDR->income_tax ,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $FDR->excavator_tariff != 0 ? number_format($FDR->excavator_tariff,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $FDR->net_interest != 0 ? number_format($FDR->net_interest,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $FDR->bank_charge != 0 ? number_format($FDR->bank_charge,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $FDR->vat != 0 ? number_format($FDR->vat,2,'.',',') : ""}}</td>
					<td class="amount-right">{{ $FDR->total_balance !=0 ? number_format($FDR->total_balance,2,'.',',') : "" }}</td>
					<td>{{ $FDR->comments != 0 ? $FDR->comments :"" }}</td>
				</tr>
			@endforeach
			</tbody>
		</table>
	</body>
</html>