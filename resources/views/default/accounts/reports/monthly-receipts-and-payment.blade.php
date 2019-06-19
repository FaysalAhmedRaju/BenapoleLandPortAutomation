<!DOCTYPE html>
<html>
<head>
	<title>Monthly Receipts & Payment Account</title>
	<style>
		html {
	            margin: 5px 5px 0;
	    }

		body{
            background-image: url(/img/Logo_BSBK.gif);
            background-repeat:no-repeat;
            background-position:center center;
            background-size:250px 180px;
            opacity: .2;
        }

		.dataTable {
            border-collapse: collapse;
            width: 100%;
        }
        .dataTable, .dataTable th, .dataTable td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
        }
        .dataTable th {
        	text-align: center;
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
			    	<span style="font-size: 20px;">BANGLADESH LAND PORT AUTHORITY</span> <br>
			    	<span style="font-size: 19px;">Benapole Land Port, Jessore</span> <br>
			   		Receipts & Payment Account for the Month of {{ $month_year_receipts_and_payment }}
			    </td>
			    <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
			    	<b>Time:</b> {{ $todayWithTime }}
			    </td>
			 </tr>
		</table>
	 	<table class="dataTable">
			<thead>
				<tr>
					<th>SL.<br>No.</th>
					<th>Receipts</th>
	                <th>Amount (Tk.)</th>
	                <th>SL.<br>No.</th>
	                <th>Payment</th>
	                <th>Amount (Tk.)</th>
				</tr>
			</thead>
			<tbody>
				@php
					$i=0;
					$ReceiptTitle = array(0 =>'Balance B/D',1=>'FDR',2=>'FDR INTEREST',
						3=>'REVENUE',4=>'WRONG POSTING');
					$totalPayment = 0;
				@endphp
				@foreach($data as $key => $row)
					<tr>
						<td>{{ ++$i }}</td>
						<td>{{ isset($ReceiptTitle[$key]) ? $ReceiptTitle[$key] : "" }}</td>
						<td class="amount-right"></td>
						<td>{{ $i }}</td>
						<td>{{ $row->acc_sub_head }}</td>
						<td class="amount-right">{{ number_format($row->total,2,'.',',') }}</td>
						@php
							$totalPayment += $row->total;
						@endphp
					</tr>
				@endforeach
					<tr>
						<td></td>
						<td></td>
						<td class="amount-right"></td>
						<td>{{ ++$i }}</td>
						<td style="font-weight: bold;">Sub-Total</td>
						<td class="amount-right" style="font-weight: bold;">{{ number_format($totalPayment,2,'.',',') }}</td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td class="amount-right"></td>
						<td>{{ ++$i }}</td>
						<td style="font-weight: bold;">Balance C/D</td>
						<td class="amount-right" style="font-weight: bold;"></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td class="amount-right"></td>
						<td>{{ ++$i }}</td>
						<td style="font-weight: bold;">Total</td>
						<td class="amount-right" style="font-weight: bold;">{{ number_format($totalPayment,2,'.',',') }}</td>
					</tr>
			</tbody>
		</table>
	</body>
</html>