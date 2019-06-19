<!DOCTYPE html>
<html>
<head>
	<title>Yearly Report</title>
	<style>
    table {
           border-collapse: collapse;
           width: 100%;

       }
    table, th, td {
        border: 1px solid black;
        padding:4px  1px;
        text-align: center;
        font-size: 15px;
    }
    .center{
        position: absolute;
        text-align: center;
        top: 0;
        left: 250px;
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
    		<span style="font-size: 19px;">Yearly Expenditure Report <br>Yearly Expenditure Fuel & Energy Report</span> <br>

    	</p>
        <br><br><br>
        <table style="border: none !important;">
            <tr>
                <td  style="border: none !important; text-align: left">

                </td>
                <td  style="border: none !important; text-align: right">Date : {{$todayWithTime}}</td>

            </tr>
        </table>

    	<br>
    	<table style="page-break-inside:avoid;">
	 		<caption style="padding-bottom: 10px;"><b><u></u></b></caption>
			<thead>
			<tr>
		   <th>S/l</th>

               <th>Month/Year</th>

                <th>Electricity SectorExpenses</th>
                <th>PetrolMobile Expenses</th>
                <th>FuelGenerator</th>
                <th>Total</th>
		  </tr>
			</thead>
			<tbody>


            @php ([$total=0,$totalElectricity=0,$totalPetrolMobile=0,$totalFuelGenerator=0])


@foreach($expenditure as $key => $ex)
            <tr>
                <td width="60">{{ ++$key }}</td>
                <td>{{$ex->CreateMonths}}-{{$ex->YearName}}</td>
                <td class="txt-right">{{ $ex->ElectricitySectorExpenses }}</td>
                <td class="txt-right">{{ $ex->PetrolMobileExpenses }}</td>
                <td class="txt-right">{{ $ex->FuelGenerator }}</td>


                <td class="txt-right">{{ number_format($ex->total , 0, '.', ',')}}</td>
                @php ([$total+=$ex->total,$totalElectricity+=$ex->ElectricitySectorExpenses,$totalPetrolMobile+=$ex->PetrolMobileExpenses,$totalFuelGenerator+=$ex->FuelGenerator])


            </tr>
				@endforeach
			</tbody>


            <tfoot>
            <tr>
                <td colspan="2">
                    Total
                </td>

                <td class="txt-right">{{number_format($totalElectricity , 0, '.', ',')}}</td>
                <td class="txt-right">{{number_format($totalPetrolMobile , 0, '.', ',')}}</td>
                <td class="txt-right">{{number_format($totalFuelGenerator , 0, '.', ',')}}</td>
                <td class="txt-right">{{number_format($total , 0, '.', ',')}}</td>

            </tr>
            </tfoot>

		</table>


	</body>
</html>