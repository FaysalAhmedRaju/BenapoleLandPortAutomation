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
        font-size: 9px;
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
    		<span style="font-size: 19px;">Yearly Expenditure Report</span> <br>

    	</p>
        <br><br><br>
        <table style="border: none !important;">
            <tr>
                <td  style="border: none !important; text-align: left">

                </td>
                <td  style="border: none !important; text-align: right">Date : {{$todayWithTime}}</td>

            </tr>
        </table>

    <table style="page-break-inside:avoid;">
	 		<caption style="padding-bottom: 10px;"><b><u></u></b></caption>
			<thead>
			<tr>
		   <th>S/l</th>

               <th>Month/Year</th>
                <th>Salariesof Officers</th>
                <th>Salariesof Staffs</th>
                <th>HouseRent Allowance</th>
                <th>Medical Allowance</th>
                <th>Dearness Allowance</th>
                <th>Port Allowance</th>
                <th>Bleaching Allowance</th>
                <th>Night Allowance</th>
                <th>Overtime Allowance</th>
                <th>Encouragement Allowance</th>
                <th>Festival Allowance</th>
                <th>Incometax Allowance</th>
                <th>Allowance Allowance</th>
                <th>Group Insurance</th>
                <th>Insurance</th>
                <th>Rationsubsidy</th>
                <th>Tiffin Allowance</th>
                <th>Wax Entertainment Allowance</th>
                <th>Bangli NewYear Allowance</th>
                <th>NoWork NoPayment StaffSalaries</th>
                <th>Security SectorExpenses</th>
                <th>Remittance Allowance</th>
                <th>Educational Allowance</th>
                <th>Total</th>
		  </tr>
			</thead>
			<tbody>


            @php ([$total=0,$totalSalariesofOfficers=0,$totalSalariesofStaffs=0,$totalHouseRentAllowance=0,$totalMedicalAllowance=0])
            @php ([$totalDearnessAllowance=0,$totalPortAllowance=0,$totalBleachingAllowance=0,$totalNightAllowance=0,$totalOvertimeAllowance=0])
            @php ([$totalEncouragementAllowance=0,$totalFestivalAllowance=0,$totalIncometaxAllowance=0,$totalAllowanceAllowance=0,$totalGroupInsurance=0])
            @php ([$totalInsurance=0,$totalRationsubsidy=0,$totalTiffinAllowance=0,$totalWaxentertainmentAllowance=0,$totalBangliNewYearAllowance=0])
            @php ([$totalNoWorkNoPaymentStaffSalaries=0,$totalSecuritySectorExpenses=0,$totalRemittanceAllowance=0,$totalEducationalAllowance=0])


@foreach($expenditure as $key => $ex)
            <tr>
                <td width="60">{{ ++$key }}</td>
                <td>{{$ex->CreateMonths}}-{{$ex->YearName}}</td>
                <td>{{ $ex->SalariesofOfficers }}</td>
                <td>{{ $ex->SalariesofStaffs }}</td>
                <td>{{ $ex->HouseRentAllowance }}</td>
                <td>{{ $ex->MedicalAllowance }}</td>
                <td>{{ $ex->DearnessAllowance }}</td>
                <td>{{ $ex->PortAllowance }}</td>
                <td>{{ $ex->BleachingAllowance }}</td>
                <td>{{ $ex->NightAllowance }}</td>
                <td>{{ $ex->OvertimeAllowance }}</td>
                <td>{{ $ex->EncouragementAllowance }}</td>
                <td>{{ $ex->FestivalAllowance }}</td>
                <td>{{ $ex->IncometaxAllowance }}</td>
                <td>{{ $ex->AllowanceAllowance }}</td>
                <td>{{ $ex->GroupInsurance }}</td>
                <td>{{ $ex->Insurance }}</td>
                <td>{{ $ex->Rationsubsidy}}</td>
                <td>{{ $ex->TiffinAllowance }}</td>
                <td>{{ $ex->WaxentertainmentAllowance }}</td>
                <td>{{ $ex->BangliNewYearAllowance }}</td>
                <td>{{ $ex->NoWorkNoPaymentStaffSalaries }}</td>
                <td>{{ $ex->SecuritySectorExpenses }}</td>
                <td>{{ $ex->RemittanceAllowance }}</td>
                <td>{{ $ex->EducationalAllowance }}</td>


                <td class="txt-right">{{ number_format($ex->total , 0, '.', ',')}}</td>
                @php ([$total+=$ex->total,$totalSalariesofOfficers+=$ex->SalariesofOfficers,$totalSalariesofStaffs+=$ex->SalariesofStaffs,$totalHouseRentAllowance+=$ex->HouseRentAllowance,$totalMedicalAllowance+=$ex->MedicalAllowance])
                @php ([$totalDearnessAllowance+=$ex->DearnessAllowance,$totalPortAllowance+=$ex->PortAllowance,$totalBleachingAllowance+=$ex->BleachingAllowance,$totalNightAllowance+=$ex->NightAllowance,$totalOvertimeAllowance+=$ex->OvertimeAllowance])
                @php ([$totalEncouragementAllowance+=$ex->EncouragementAllowance,$totalFestivalAllowance+=$ex->FestivalAllowance,$totalIncometaxAllowance+=$ex->IncometaxAllowance,$totalAllowanceAllowance+=$ex->AllowanceAllowance,$totalGroupInsurance+=$ex->GroupInsurance])

                @php ([$totalInsurance+=$ex->Insurance,$totalRationsubsidy+=$ex->Rationsubsidy,$totalTiffinAllowance+=$ex->TiffinAllowance,$totalWaxentertainmentAllowance+=$ex->WaxentertainmentAllowance,$totalBangliNewYearAllowance+=$ex->BangliNewYearAllowance])
                @php ([$totalNoWorkNoPaymentStaffSalaries+=$ex->NoWorkNoPaymentStaffSalaries,$totalSecuritySectorExpenses+=$ex->SecuritySectorExpenses,$totalRemittanceAllowance+=$ex->RemittanceAllowance,$totalEducationalAllowance+=$ex->EducationalAllowance])


            </tr>
				@endforeach
			</tbody>


            <tfoot>
            <tr>
                <td colspan="2">
                    Total
                </td>

                <td>{{$totalSalariesofOfficers}}</td>
                <td>{{$totalSalariesofStaffs}}</td>
                <td>{{$totalHouseRentAllowance}}</td>
                <td>{{$totalMedicalAllowance}}</td>
                <td>{{$totalDearnessAllowance}}</td>
                <td>{{$totalPortAllowance}}</td>
                <td>{{$totalBleachingAllowance}}</td>
                <td>{{$totalNightAllowance}}</td>
                <td>{{$totalOvertimeAllowance}}</td>
                <td>{{$totalEncouragementAllowance}}</td>
                <td>{{$totalFestivalAllowance}}</td>
                <td>{{$totalIncometaxAllowance}}</td>
                <td>{{$totalAllowanceAllowance}}</td>
                <td>{{$totalGroupInsurance}}</td>

                <td>{{$totalInsurance}}</td>
                <td>{{$totalRationsubsidy}}</td>
                <td>{{$totalTiffinAllowance}}</td>
                <td>{{$totalWaxentertainmentAllowance}}</td>
                <td>{{$totalBangliNewYearAllowance}}</td>
                <td>{{$totalNoWorkNoPaymentStaffSalaries}}</td>
                <td>{{$totalSecuritySectorExpenses}}</td>
                <td>{{$totalRemittanceAllowance}}</td>
                <td>{{$totalEducationalAllowance}}</td>


                <td>{{$total}}</td>
            </tr>
            </tfoot>

		</table>


	</body>
</html>