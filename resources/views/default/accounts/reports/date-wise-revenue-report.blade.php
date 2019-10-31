<!DOCTYPE html>
<html>
<head>
	<title>Date Wise Manifest Report</title>
	<style>
    table {
        border-collapse: collapse;
        width: 100%;

    }
    table, th, td {
        border: 1px solid black;
        padding: 1px;
        text-align: center;
    }
    .center{
        position: absolute;
        text-align: center;
        top: 0;
        left: 450px;
    }
    .txt-right{
            text-align: right;
        }
    </style>
</head>
	<body>
		<img src="../public/img/blpa.jpg">
		<p class="center">
			<span style="font-size: 20px;">BANGLADESH LANDPORT AUTHORITY</span><br>
			<span style="font-size: 19px;">Benapole Land Port, Jessore </span> <br>
    		<span style="font-size: 19px;">Sector Wise Daily Revenue Income Details</span> <br>
    		Date : {{$from_date}}
    	</p>
    	
    	<br><br><br>
    	<table style="page-break-inside:avoid;">
	 		<caption style="padding-bottom: 10px;"><b><u></u><b></caption>
			<thead>
			<tr>
		   <!--th>Sl</th-->
		   <th>Date</th>
		   <th>WareHouse Charges</th>
		   <th>Equipment Handling Charges</th> 
		   <th>Labour Handling Charges</th>
		   <th>Truck Entrance Fee</th>
		   <th>Haltage Fee</th>
		   <th>Carpenter Charges</th>
		   <th>Night Charges</th>
		   <th>Holiday Charges</th>
		   <th>Weighment Charge</th>
		   <th>Removal Charges</th>
		   <th>Truck Terminal Entry Fee</th>
		   <th>Truck Terminal Haltage Fee</th>
		   <th>Export Terminal Entry Fee</th>
		   <th>Export Terminal Haltage Fee</th>
		   <th>Miscellaneous Charges</th>
			<th>Passenger Terminal Entry Fee</th>
		   <th>Total Taka</th>
		   <th>VAT</th>
		  </tr>
			</thead>
			<tbody>

		@foreach($DWRDate as $key => $u)
			<tr>	
			<td width="60">{{ $u->create_dt }}</td>
		   <td class="txt-right">{{ $u->Warehouse_Charge }}</td>
		   <td class="txt-right">{{ $u->Handling_Equipment }}</td>
		   <td class="txt-right">{{ $u->Handling_Labour }}</td>
		   <td class="txt-right">{{ $u->Truck_Entrance_fee }}</td>
		   <td class="txt-right">{{ $u->Haltage_Charges }}</td>
		   <td class="txt-right">{{ $u->Carpenter_Charges }}</td>
		   <td class="txt-right">{{ $u->Night_Charge }}</td>
		   <td class="txt-right">{{ $u->Holiday_Charge }}</td>
		   <td class="txt-right">{{ $u->Weighment_Charge }}</td>
		   <td class="txt-right">{{ $u->Removal_Charge }}</td>
			<td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td></td>
		   <td class="txt-right"> {{$u->passenger_terminal_entry_fee}}</td>
		   <td class="txt-right">{{ number_format( $u->total,2) }}</td>
				{{--<td class="amount-right">{{ number_format($u->total_Vat,2)}}</td>--}}
		   <td class="txt-right">{{ number_format($u->total_Vat,2) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		
	</body>
</html>