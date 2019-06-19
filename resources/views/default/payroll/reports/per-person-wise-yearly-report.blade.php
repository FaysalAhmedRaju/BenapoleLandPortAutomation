<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<title>Per Person wise Yearly Salary Sheet</title>
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
            left:400px;
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
	    {{$from}} to {{$to}} Salary 
	</p>
    <h5 style="text-align: right;padding-right: 35px;"> Date: {{$todayWithTime}}</h5>
    <div>
        <span>Employee ID: <b>{{ isset($perPersonWiseYearlyReport[0]->emp_id) ? $perPersonWiseYearlyReport[0]->emp_id : ""}}</b></span><br>
        <span>Employee Name: <b>{{ isset($perPersonWiseYearlyReport[0]->emp_name) ? $perPersonWiseYearlyReport[0]->emp_name : "" }}</b></span><br>
        <span>Designation: <b>{{ isset($perPersonWiseYearlyReport[0]->emp_designation) ? $perPersonWiseYearlyReport[0]->emp_designation : "" }}</b></span><br>
        <span>&nbsp;</span>
    </div>
 	<table class="tab">
        <thead>
            <tr>
                <th style="width: 100px;">Month Year</th>
                <th>Basic</th>
                <th>Grade</th>
                <th>Scale Year</th>
                <th>Houserent</th>
                <th>Education Allowance</th>
                <th>Medical</th>
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
        <tbody>
            @foreach($perPersonWiseYearlyReport as $key => $person)
                <tr>
                    <td>{{ $person->month_name." ".$person->year_name }}</td>
                    <td class="amount-right">{{ $person->new_salary != 0 ? number_format($person->new_salary,2) : "" }}</td>
                    <td>{{$person->emp_grade}}</td>
                    <td>{{$person->scale_year}}</td>
                    <td class="amount-right">{{ $person->house_rent != 0 ? number_format($person->house_rent, 2) : ""}}</td>
                    <td class="amount-right">{{ $person->edu_allowance != 0 ? number_format($person->edu_allowance,2) : "" }}</td>
                    <td class="amount-right">{{ $person->medi_allowance != 0 ? number_format($person->medi_allowance,2) : "" }}</td>
                    <td class="amount-right">{{ $person->tiffin != 0 ? number_format($person->tiffin, 2) : "" }}</td>
                    <td class="amount-right">{{ $person->total_in != 0 ? number_format($person->total_in, 2) : "" }}</td>
                    <td class="amount-right">{{ $person->gpf != 0 ? number_format($person->gpf,2) : "" }}</td>
                    <td class="amount-right">{{ $person->house_rent_deduction != 0 ? number_format($person->house_rent_deduction, 2) : "" }}</td>
                    <td class="amount-right">{{ $person->water != 0 ? number_format($person->water,2) : "" }}</td>
                    <td class="amount-right">{{ $person->generator != 0 ? number_format($person->generator,2) : "" }}</td>
                    <td class="amount-right">{{ $person->electricity != 0 ? number_format($person->electricity, 2) : "" }}</td>
                    <td class="amount-right">{{ $person->previous_due != 0 ? number_format($person->previous_due,2) : "" }}</td>
                    <td class="amount-right">{{ $person->revenue != 0 ? number_format($person->revenue,2) : "" }}</td>
                    <td class="amount-right">{{ $person->total_deduction != 0 ? number_format($person->total_deduction,2) : "" }}</td>
                    <td class="amount-right">{{ $person->total_payable != 0 ? number_format($person->total_payable,2) : "" }}</td>




                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>