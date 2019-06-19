<!DOCTYPE html>
<html>
<head>
 <title>Monthly Revenue Income</title>
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
		Subject: {{ $MRdata[0]->monthName }} /{{ $MRdata[0]->yearName }} 
		 Sector Wise Monthly Revenue Income Details
	</p>
<br><br>
  <table border="1"  class="tabInfo">
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
 @foreach($MRdata as $key => $u)
  <tr>
   <!--td>{{ $key + 1 }}</td-->
   <td width="60">{{ $u->create_dt }}</td>
   <td class="amount-right">{{number_format( $u->Warehouse_Charge,2)}}</td>
    {{--<td class="amount-right">{{ number_format( $u->Warehouse_Charge,2)}}</td>--}}
   <td class="amount-right">{{number_format( $u->Handling_Equipment,2) }}</td>
    {{--<td class="amount-right">{{ number_format( $u->Handling_Equipment,2)}}</td>--}}
   <td class="amount-right">{{ number_format( $u->Handling_Labour,2) }}</td>
    {{--<td class="amount-right">{{ number_format( $u->Handling_Labour,2)}}</td>--}}
   <td class="amount-right">{{ number_format($u->Truck_Entrance_fee,2) }}</td>

   <td class="amount-right">{{  number_format($u->Haltage_Charges,2)}}</td>

   <td class="amount-right">{{  number_format( $u->Carpenter_Charges ,2) }}</td>

   <td class="amount-right">{{ number_format( $u->Night_Charge,2)}}</td>

   <td class="amount-right">{{ number_format( $u->Holiday_Charge ,2)}}</td>

   <td class="amount-right">{{ number_format( $u->Weighment_Charge,2) }}</td>
    {{--<td class="amount-right">{{ number_format( $u->Removal_Charge,2)}}</td>--}}
   <td class="amount-right">{{ number_format( $u->Removal_Charge,2) }}</td>
   <td></td>
   <td></td>
    <td></td>
   <td></td>
   <td></td>
   <td class="amount-right">{{ number_format( $u->passenger_terminal_entry_fee,2) }}</td>
   <td class="amount-right">{{number_format($u->total,2)}}</td>

   <td class="amount-right">{{ number_format($u->total_Vat,2) }}</td>
   
  </tr>
 @endforeach
 </tbody>
 </table>
</body>
</html>