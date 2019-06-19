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
            left: 350;
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
    		Sub Head Wise Yearly Income | {{$year}}-{{$year+1}}
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
                <th>July</th>
                <th>August</th>
                <th>September</th>
                <th>October</th>
                <th>November</th>
                <th>December</th>
                <th>January</th>
                <th>February</th>
                <th>March</th>
                <th>April</th>
                <th>May</th>
                <th>June</th>
                <th>Total Amount</th>
			</tr>
			</thead>
			<tbody>
			@php
				$i=0;
				//$totalIncome = 0;
			@endphp
			@foreach($data as $key => $row)
				@php
					if(isset($countHead[$row->head_id])) {   
                    	$countHead[$row->head_id]++;
                	}else {
                   		$countHead[$row->head_id] = 1;
                	}
				@endphp
				<tr @if($row->acc_head == 'Total')
					{!! 'style="font-weight : bold;"' !!}
				@endif>
					<td>{{ $countHead[$row->head_id]==1 && $row->acc_head != 'Total' ? ++$i : "" }}</td>			
					<td>{{ $countHead[$row->head_id]==1 ? $row->acc_head: "" }}</td>
					<td>{{ $row->acc_sub_head }}</td>
					<td class="amount-right">{{ $row->july != 0 ?number_format($row->july,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->august != 0 ? number_format($row->august,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->september != 0 ? number_format($row->september,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->october != 0 ? number_format($row->october,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->november != 0 ? number_format($row->november,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->december != 0 ? number_format($row->december,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->january != 0 ? number_format($row->january,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->february != 0 ? number_format($row->february,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->march != 0 ? number_format($row->march,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->april != 0 ? number_format($row->april,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->may != 0 ? number_format($row->may,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->june != 0 ? number_format($row->june,2,'.',',') : "" }}</td>
					<td class="amount-right">{{ $row->total != 0 ? number_format($row->total,2,'.',',') : "" }}</td>
					{{-- @php
						$totalIncome +=	$row->total;
					@endphp --}}
				</tr>
			@endforeach
			{{-- <tr>
				<th colspan="3">Total</th>
				<td class="amount-right">{{ number_format($totalIncome,2,'.',',') }}</td>
			</tr> --}}
			</tbody>
		</table>
	</body>
</html>