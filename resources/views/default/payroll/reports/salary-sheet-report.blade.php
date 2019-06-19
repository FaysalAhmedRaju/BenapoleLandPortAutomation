<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<title>Salary Sheet</title>
	<style>
		.tab {
			border-collapse: collapse;
			width: 100%;

		}
        .tab tr, .tab th, .tab td{
            border: 1px solid black;
            padding: 4px;
        }
        .tab th {
            text-align: center;
        }
        .tab tr td {
            text-align: left;
        }
        .center{
            position: absolute;
            text-align: center;
            top: 0;
            left: 500px;
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
		Salary Sheet {{$month_year}}
	</p>
    <h5 style="text-align: right;padding-right: 35px;"> Date: {{$todayWithTime}}</h5>
 	<table class="tab">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Desingnation</th>
                <th width="70px">Grade</th>
                <th>Scale Year</th>
                <th>Basic</th>
                <th>Houserent</th>
                <th>Education Allowance</th>
                <th>Medical</th>
                {{--<th>Wash</th>--}}
                <th>Tiffin</th>
                <th>Total</th>
                <th>GPF</th>
                <th>House Rent</th>
                <th>Water</th>
                <th>Generator</th>
                <th>Electricity</th>
                <th>Due</th>
                <th>Revenue</th>
                <th>Total Deduction</th>
                <th>Payable Amount</th>
            </tr>
        </thead>
        @php
            $sumNewslary = 0;
            $sumHouseRent = 0;
            $sumEduAllow = 0;
            $sumMediAllow = 0;
            $sumWash = 0;
            $sumTiffin = 0;
            $sumtotalIn = 0;
            $sumGpf = 0;
            $sumHouseRentDeduction = 0;
            $sumWater = 0;
            $sumGenerator = 0;
            $sumElectricity = 0;
            $sumDue = 0;
            $sumRevenue = 0;
            $sumToalDeduction = 0;
            $sumTotalPayable = 0;
        @endphp
        <tbody>
            @foreach($salaries as $key => $salary)
                <tr>
                    <td>{{ $salary->emp_id }}</td>
                    <td>{{ $salary->emp_name }}</td>
                    <td>{{ $salary->emp_designation }}</td>
                    <td>{{ $salary->emp_grade }}</td>
                    <td>{{ $salary->scale_year }}</td>
                    <td class="amount-right">{{ $salary->new_salary != 0 ? number_format($salary->new_salary,2) : '' }}</td>

                        @php($sumNewslary += $salary->new_salary)
                    <td class="amount-right">{{ $salary->house_rent != 0 ?  number_format($salary->house_rent,2) : '' }}</td>

                        @php($sumHouseRent += $salary->house_rent)
                    <td class="amount-right">{{ $salary->edu_allowance != 0 ?  number_format($salary->edu_allowance,2) : ''  }}</td>

                        @php($sumEduAllow += $salary->edu_allowance)
                    <td class="amount-right">{{ $salary->medi_allowance != 0 ?  number_format($salary->medi_allowance,2) : ''  }}</td>

                        @php($sumMediAllow += $salary->medi_allowance)
                    {{--<td class="amount-right">{{ $salary->washing != 0 ?  number_format( $salary->washing,2) : '' }}</td>--}}

                    {{--@php($sumWash += $salary->washing)--}}
                    <td class="amount-right">{{ $salary->tiffin != 0 ?  number_format( $salary->tiffin,2) : '' }}</td>

                        @php($sumTiffin += $salary->tiffin)
                    <td class="amount-right">{{ $salary->total_in != 0 ? number_format($salary->total_in,2) : '' }}</td>
                    {{--<td class="amount-right">{{ number_format($salary->total_in,2)}}</td>--}}
                        @php($sumtotalIn += $salary->total_in)
                    <td class="amount-right">{{ $salary->gpf != 0 ?  number_format($salary->gpf,2) : '' }}</td>
                    {{--<td class="amount-right">{{ number_format($salary->gpf,2)}}</td>--}}
                        @php($sumGpf += $salary->gpf)
                    <td class="amount-right">{{ $salary->house_rent_deduction != 0 ?  number_format($salary->house_rent_deduction,2) : '' }}</td>
                    {{--<td class="amount-right">{{ number_format($salary->house_rent_deduction,2)}}</td>--}}
                        @php($sumHouseRentDeduction +=  $salary->house_rent_deduction)
                    <td class="amount-right">{{ $salary->water != 0 ?  number_format( $salary->water ,2) : ''}}</td>
                    {{--<td class="amount-right">{{ number_format( $salary->water ,2)}}</td>--}}
                        @php($sumWater += $salary->water)
                    <td class="amount-right">{{ $salary->generator != 0 ? number_format( $salary->generator,2) : '' }}</td>
                    {{--<td class="amount-right">{{ number_format( $salary->generator,2)}}</td>--}}
                        @php($sumGenerator += $salary->generator)
                    <td class="amount-right">{{ $salary->electricity != 0 ? number_format( $salary->electricity,2)  : '' }}</td>
                    {{--<td class="amount-right">{{ number_format( $salary->electricity,2)}}</td>--}}
                        @php($sumElectricity += $salary->electricity)
                    <td class="amount-right">{{ $salary->previous_due != 0 ?  number_format($salary->previous_due,2) : ''  }}</td>
                    {{--<td class="amount-right">{{ number_format($salary->previous_due,2)}}</td>--}}
                        @php($sumDue += $salary->previous_due)
                    <td class="amount-right">{{ $salary->revenue != 0 ?  number_format($salary->revenue,2) : ''  }}</td>
                    {{--<td class="amount-right">{{ number_format($salary->revenue,2)}}</td>--}}
                        @php($sumRevenue += $salary->revenue)
                    <td class="amount-right">{{ $salary->total_deduction != 0 ? number_format( $salary->total_deduction,2) : '' }}</td>

                        @php($sumToalDeduction += $salary->total_deduction)
                    <td class="amount-right">{{ $salary->total_payable != 0 ?  number_format($salary->total_payable,2) : '' }}</td>
                    {{--<td class="amount-right">{{ number_format($salary->total_payable,2)}}</td>--}}
                        @php($sumTotalPayable += $salary->total_payable)
                </tr>
            @endforeach
            <tr>
                <th>Total:</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                {{--<td>{{ $sumNewslary != 0 ? number_format($sumNewslary,2,'.','') : '' }}</td>--}}
                <th  class="amount-right">{{ $sumNewslary != 0 ? number_format($sumNewslary,2) : '' }}</th>
                <th  class="amount-right">{{ $sumHouseRent != 0 ? number_format($sumHouseRent,2) : '' }}</th>
                <th  class="amount-right">{{ $sumEduAllow != 0 ? number_format($sumEduAllow,2) : '' }}</th>
                <th  class="amount-right">{{ $sumMediAllow != 0 ? number_format($sumMediAllow,2) : '' }}</th>
                {{--<th  class="amount-right">{{ $sumWash != 0 ? number_format($sumWash,2) : '' }}</th>--}}
                <th  class="amount-right">{{ $sumTiffin != 0 ? number_format($sumTiffin,2) : '' }}</th>
                <th  class="amount-right">{{ $sumtotalIn != 0 ? number_format($sumtotalIn,2) : '' }}</th>
                <th  class="amount-right">{{ $sumGpf != 0 ? number_format($sumGpf,2) : '' }}</th>
                <th  class="amount-right">{{ $sumHouseRentDeduction != 0 ? number_format($sumHouseRentDeduction,2) : '' }}</th>
                <th  class="amount-right">{{ $sumWater != 0 ? number_format($sumWater,2) : '' }}</th>
                <th  class="amount-right">{{ $sumGenerator != 0 ? number_format($sumGenerator,2) : '' }}</th>
                <th  class="amount-right">{{ $sumElectricity != 0 ? number_format($sumElectricity,2) : '' }}</th>
                <th  class="amount-right">{{ $sumDue != 0 ? number_format($sumDue,2) : '' }}</th>
                <th  class="amount-right">{{ $sumRevenue != 0 ? number_format($sumRevenue,2) : '' }}</th>
                <th class="amount-right">{{ $sumToalDeduction != 0 ? number_format($sumToalDeduction,2) : '' }}</th>
                <th  class="amount-right">{{ $sumTotalPayable != 0 ? number_format($sumTotalPayable,2) : '' }}</th>
            </tr>
        </tbody>
    </table>
</body>
</html>