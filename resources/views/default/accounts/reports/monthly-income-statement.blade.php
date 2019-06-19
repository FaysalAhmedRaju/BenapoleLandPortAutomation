<!DOCTYPE html>
<html>
<head>
	<title>Monthly Income Statement</title>
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
			   		Income Statement for the month of {{ $month_year_income_statement }}
			    </td>
			    <td style="width: 25%; font-size: 14px; text-align: right; vertical-align: bottom;">
			    	<b>Time:</b> {{ $todayWithTime }}
			    </td>
			 </tr>
		</table>
	 	<table class="dataTable">
			<thead>
				<tr>
					<th style="width: 3%;">S/L</th>
					<th>Particulars</th>
	                <th style="width: 25%;">Amount</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>1</td>
					<td><b><i>Revenue(Excluding VAT):</i></b></td>
					<td class="amount-right">{{ $totalIncome[0]->total != 0 ? number_format($totalIncome[0]->total,2,'.',',') : "" }}</td>
				</tr>
			@foreach($income as $key => $row)
				{{-- @php
					if(isset($countHead[$row->head_id])) {   
                    	$countHead[$row->head_id]++;
                	}else {
                   		$countHead[$row->head_id] = 1;
                	}
				@endphp --}}
				<tr>
					<td style="border: none;"></td>	
					<td>{{ $row->acc_sub_head }}</td>
					<td class="amount-right">{{ $row->total != 0 ? number_format($row->total,2,'.',',') : "" }}</td>
				</tr>
			@endforeach
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>2</td>
					<td><b><i>Revenue(Others):</i></b></td>
					<td class="amount-right">{{ $totalIncomeOthers[0]->total != 0 ? number_format($totalIncomeOthers[0]->total,2,'.',',') : "" }}</td>
				</tr>
				@foreach($incomeOthers as $key => $row)
					{{-- @php
                        if(isset($countHead[$row->head_id])) {
                            $countHead[$row->head_id]++;
                        }else {
                               $countHead[$row->head_id] = 1;
                        }
                    @endphp --}}
					<tr>
						<td style="border: none;"></td>
						<td>{{ $row->acc_sub_head }}</td>
						<td class="amount-right">{{ $row->total != 0 ? number_format($row->total,2,'.',',') : "" }}</td>
					</tr>
				@endforeach

				{{--<tr>--}}
					{{--<td style="border: none;"></td>--}}
					{{--<td>Investment</td>--}}
					{{--<td class="amount-right"></td>--}}
				{{--</tr>--}}
				{{--<tr>--}}
					{{--<td style="border: none;"></td>--}}
					{{--<td>Interest on Deposit</td>--}}
					{{--<td class="amount-right"></td>--}}
				{{--</tr>--}}
				{{--<tr>--}}
					{{--<td style="border: none;"></td>--}}
					{{--<td>Auction Sale</td>--}}
					{{--<td class="amount-right"></td>--}}
				{{--</tr>--}}
				{{--<tr>--}}
					{{--<td style="border: none;"></td>--}}
					{{--<td>Sale of Tender Sedule</td>--}}
					{{--<td class="amount-right"></td>--}}
				{{--</tr>--}}
				{{--<tr>--}}
					{{--<td style="border: none;"></td>--}}
					{{--<td>Interest on port dues</td>--}}
					{{--<td class="amount-right"></td>--}}
				{{--</tr>--}}
				{{--<tr>--}}
					{{--<td style="border: none;"></td>--}}
					{{--<td>Godown Rent of Port</td>--}}
					{{--<td class="amount-right"></td>--}}
				{{--</tr>--}}
				{{--<tr>--}}
					{{--<td style="border: none;"></td>--}}
					{{--<td>Leave Rent of Port</td>--}}
					{{--<td class="amount-right"></td>--}}
				{{--</tr>--}}
				{{--<tr>--}}
					{{--<td style="border: none;"></td>--}}
					{{--<td>Miscellaneous</td>--}}
					{{--<td class="amount-right"></td>--}}
				{{--</tr>--}}
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>3</td>
					<td><b><i>Total Revenue(1+2)</i></b></td>
					<td class="amount-right">{{ $totalRevenueOneTwo != 0 ? number_format($totalRevenueOneTwo,2,'.',',') : "" }}</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>4</td>
					<td><b><i>Total Expenses</i></b></td>
					<td class="amount-right">{{ $totalExpense[0]->total != 0 ? number_format($totalExpense[0]->total,2,'.',',') : "" }}</td>
				</tr>
				@foreach($expense as $key => $row)
					<tr>
						<td style="border: none;"></td>	
						<td>{{ $row->acc_sub_head }}</td>
						<td class="amount-right">{{ $row->total != 0 ? number_format($row->total,2,'.',',') : "" }}</td>
					</tr>
				@endforeach
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>5</td>
					<td><b><i>Net Income before TAX(3-4)</i></b></td>
					@php
						$netIncome = $totalRevenueOneTwo-$totalExpense[0]->total;
					@endphp
					<td class="amount-right">{{ number_format($netIncome,2,'.',',') }}</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>6</td>
					<td><b><i>VAT Paid by Port</i></b></td>
					@php
						$vat = ($netIncome/100)*30;
					@endphp
					<td class="amount-right">{{ number_format($vat,2,'.',',') }}</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td>7</td>
					<td><b><i>Investment and Transfer to HO</i></b></td>
					<td class="amount-right"></td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				@php
					$char = 97;
				@endphp
				@foreach($fdrInfo as $key => $row)
					<tr>
						<td colspan="2" style="border: none;">
							<span>{{ chr($char) }}</span>
							<span style="font-weight: bold;"> Investment: FDR NO: {{ $row->fdr_no }}</span>
							<br>
							<span style="font-size: 15px;">{!! 'Date :'.$row->fdr_closing_date.' Bank Name: '.$row->openning_bank_name_address.' Rate of Interest: '.$row->last_renew_interest_rate.'%' !!}</span>
						</td>
						<td class="amount-right">{{ $row->total_closing_ammount != 0 ? number_format($row->total_closing_ammount,2,'.',',') : "" }}</td>
					</tr>
					@php
						$char++;
					@endphp
				@endforeach
				<tr>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">{{ chr($char) }}<b><i> Transferred to Head Office</i></b></td>
					<td class="amount-right"></td>
				</tr>
				@php
					$char++;
				@endphp
				<tr>
					<td>&nbsp;</td>
					<td>
						Date : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Bank Name: &nbsp;&nbsp;&nbsp;
					</td>
					<td class="amount-right">&nbsp;</td>
				</tr>
				<tr>
					<td>{{ chr($char) }}</td>
					<td><b><i>Wrong Posting</i></b></td>
					<td class="amount-right">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td style="text-align: center;">SND 05</td>
					<td class="amount-right">&nbsp;</td>
				</tr>

			</tbody>
		</table>
	</body>
</html>