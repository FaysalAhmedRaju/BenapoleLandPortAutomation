<!DOCTYPE html>
<html>
<head>
	<title>Sub Head Wise Monthly Income</title>
	<style>
		table {
            border-collapse: collapse;
            width: 100%;
        }
        table, th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
        }
        th {
        	text-align: center;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 150;
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
    		Sub Head Wise Monthly Income | {{$month_year_income}}
    	</p>
    	<p style="text-align: right;">
    		Print Time: {{$todayWithTime}}
    	</p>
	 	<table>
			<thead>
			<tr>
				<th>SL.<br>No.</th>
				<th>Head Name</th>
                <th>Sub-head Name</th>
                <th>Total Amount</th>
			</tr>
			</thead>
			<tbody>
			@php
				$i=0;
				$totalIncome = 0;
			@endphp
			@foreach($data as $key => $row)
				@php
					if(isset($countHead[$row->head_id])) {   
                    	$countHead[$row->head_id]++;
                	}else {
                   		$countHead[$row->head_id] = 1;
                	}
				@endphp
				<tr>
					<td>{{ $countHead[$row->head_id]==1 ? ++$i : "" }}</td>			
					<td>{{ $countHead[$row->head_id]==1 ? $row->acc_head: "" }}</td>
					<td>{{ $row->acc_sub_head }}</td>
					<td class="amount-right">{{ number_format($row->total,2,'.',',') }}</td>
					@php
						$totalIncome +=	$row->total;
					@endphp
				</tr>
			@endforeach
			<tr>
				<th colspan="3">Total</th>
				<td class="amount-right">{{ number_format($totalIncome,2,'.',',') }}</td>
			</tr>
			</tbody>
		</table>
	</body>
</html>