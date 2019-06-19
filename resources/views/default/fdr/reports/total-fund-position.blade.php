<!DOCTYPE html>
<html>
<head>
	<title>Total Fund Position</title>
	<style>
		table {
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
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
    		Fund Position as on {{$today}}
    	</p>
	 	<table>
			<thead>
			<tr>
				<th>SL.<br>No.</th>
                <th>Name of Bank</th>
                <th>F.D.R No</th>
                <th>S/L</th>
                <th>Opening Amount<br>TK.</th>
                <th>Issue/renew<br>Date</th>
                <th>Term</th>
                <th>Maturity<br>date</th>
                <th>Open/Renew<br>Rate</th>
               {{--  <th>Ratio</th> --}}
                <th>Renewed Amount</th>
               {{--  <th>Ratio</th> --}}
                <th>Bank wise<br>TK.(core)</th>
			</tr>
			</thead>
			{{-- @php
				$totalRow = count($totalFundPostion);
				foreach ($totalFundPostion as $key => $fund) {
					if($fund->bank_type == 0) {
						if(isset($bankCount[$fund->bank_type])) {
							$bankCount[$fund->bank_type]++;
						} else {
							$bankCount[$fund->bank_type] = 1;
						}
					} else {
						if(isset($bankCount[$fund->bank_type])) {
							$bankCount[$fund->bank_type]++;
						} else {
							$bankCount[$fund->bank_type] =1;
						}
					}
				}
				$ratioOfGovBank = number_format(($bankCount[0]/$totalRow)*100,2); 
			@endphp --}}
			<tbody>	@php($i=0)
			@php
				$totalGovtOpenningAmount = 0;
				$totalPrivateOpenningAmount = 0;
				$totalGovRenewAmount = 0;
				$totalPrivateRenewAmount = 0;
				$totalBankWiseCore = 0;
				$countBankWiseCore = 0;
			@endphp
			@foreach($totalFundPostion as $key => $fundPosition)
				<tr>
					<td>{{ ++$i }}</td>
					@php
		                if(isset($bankWiseFDrSerialNo[$fundPosition->bank_id])) {   
		                    $bankWiseFDrSerialNo[$fundPosition->bank_id]++;
		                } else {
		                   $bankWiseFDrSerialNo[$fundPosition->bank_id] = 1;
		                }
		                // if($fundPosition->bank_type == 0 && isset($govBankSerial[$fundPosition->bank_type])) {
		                // 	$govBankSerial[$fundPosition->bank_type]++;
		                // } else {
		                // 	$govBankSerial[$fundPosition->bank_type] =1;
		                // }
		                // if($fundPosition->bank_type == 1 && isset($govBankSerial[$fundPosition->bank_type])) {
		                // 	$govBankSerial[$fundPosition->bank_type]++;
		                // } else {
		                // 	$govBankSerial[$fundPosition->bank_type] =1;
		                // }
					@endphp
					@if($bankWiseFDrSerialNo[$fundPosition->bank_id] == 1)
						<td{{--  rowspan="{{ $fundPosition->bank_wise_account_count }}" --}}>
								{{ $fundPosition->name_and_address }}
						</td>
					@else
						<td>&nbsp;</td>
					@endif
					
					<td>{{ $fundPosition->fdr_no }}</td>
					<td>{{ $fundPosition->sl_no }}</td>
					<td class="amount-right">{{ number_format($fundPosition->openning_amount,2,'.',',') }}</td>
					@php
						if($fundPosition->bank_type == 0) {
							$totalGovtOpenningAmount += $fundPosition->openning_amount;
						} else {
							$totalPrivateOpenningAmount += $fundPosition->openning_amount;
						}
					@endphp
					<td>{{ $fundPosition->openning_or_renew_date }}</td>
					<td>{{ $fundPosition->term }}</td>
					<td>{{ $fundPosition->maturity_date }}</td>
					<td class="amount-right">{{ $fundPosition->open_or_renew_interest_rate }}</td>
					{{-- @if(isset($govBankSerial[0]))
						@if($govBankSerial[0]==1)
							<td rowspan="{{ $bankCount[0] }}">{{  $ratioOfGovBank }}</td>
						@endif	
					@endif
					@if(isset($govBankSerial[1]))
						@if($govBankSerial[1]==0)
							<td rowspan="{{ $bankCount[0] }}">{{  $ratioOfGovBank }}</td>
						@endif
					@endif --}}
					<td class="amount-right">{{ number_format($fundPosition->renewed_amount,2,'.',',') }}</td>
					@php
						if($fundPosition->bank_type == 0) {
							$totalGovRenewAmount += $fundPosition->renewed_amount;
						} else {
							$totalPrivateRenewAmount += $fundPosition->renewed_amount;
						}
					@endphp
					{{-- @if($bankWiseFDrSerialNo[$fundPosition->bank_id] == 1)
						<td class="amount-right">{{ $fundPosition->bank_wise_total }}</td>
					@else
						<td>&nbsp;</td>
					@endif
					@php
						if($bankWiseFDrSerialNo[$fundPosition->bank_id] == 1) {
							$totalBankWiseCore += $fundPosition->bank_wise_total;
						}
					@endphp --}}
					@php
						if(isset($bankWiseCore[$fundPosition->bank_id])) {
							$bankWiseCore[$fundPosition->bank_id] += $fundPosition->renewed_amount/10000000;
							//$totalBankWiseCore += $bankWiseCore[$fundPosition->bank_id];
							$countBankWiseCore++;
						} else {
							$bankWiseCore[$fundPosition->bank_id] = $fundPosition->renewed_amount/10000000;
							//$totalBankWiseCore += $bankWiseCore[$fundPosition->bank_id];
							$countBankWiseCore = 1;
						}
					@endphp
					@php
						if($countBankWiseCore == $fundPosition->bank_wise_account_count) {
							$totalBankWiseCore += $bankWiseCore[$fundPosition->bank_id];
						}

					@endphp
					@if($countBankWiseCore == $fundPosition->bank_wise_account_count)
						<td class="amount-right">{{ number_format($bankWiseCore[$fundPosition->bank_id],2,'.','') }}</td>
					@else
						<td class="amount-right">&nbsp;</td>
					@endif
				</tr>
			@endforeach
			</tbody>
		</table>
		<div>
			<span style="padding-left: 270px; font-weight: bold;">Government Bank= {{ number_format($totalGovtOpenningAmount,2,'.',',') }}</span>
			{{-- <span style="padding-left: 375px;">100</span> --}}
			<span style="padding-left: 340px; font-weight: bold;">{{ number_format($totalGovRenewAmount,2,'.',',') }}</span>
			{{-- <span style="padding-left: 30px;">100</span><br> --}}<br>
			<span style="padding-left: 305px; font-weight: bold;">Private Bank= <u>{{ number_format($totalPrivateOpenningAmount,2,'.',',') }}</u></span>
			<span style="padding-left: 340px; font-weight: bold; text-decoration: underline;">{{ number_format($totalPrivateRenewAmount,2,'.',',') }}</span>
			<span style="padding-left: 30px; text-decoration: underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br>
			<span style="padding-left: 410px; font-weight: bold;">{{ number_format($totalGovtOpenningAmount + $totalPrivateOpenningAmount,2,'.',',') }}</span>
			<span style="padding-left: 340px; font-weight: bold;">{{ number_format($totalGovRenewAmount + $totalPrivateRenewAmount,2,'.',',') }}</span>
			{{-- <span style="padding-left: 20px;">ok</span> --}}
			<span style="padding-left: 55px;">{{ number_format($totalBankWiseCore,2,'.',',') }}</span>

		</div>
	</body>
</html>