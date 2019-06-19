<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<title>Per Person wise Salary Sheet</title>
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
	    {{$month_year}} Salary
	</p>
    <h5 style="text-align: right;padding-right: 35px;"> Date: {{$todayWithTime}}</h5>
    <div>
        <span>Employee ID: <b>{{ isset($perPersonWiseMonthlyReport[0]->emp_id) ? $perPersonWiseMonthlyReport[0]->emp_id : ""}}</b></span><br>
        <span>Employee Name: <b>{{ isset($perPersonWiseMonthlyReport[0]->emp_name) ? $perPersonWiseMonthlyReport[0]->emp_name : "" }}</b></span><br>
        <span>Designation: <b>{{ isset($perPersonWiseMonthlyReport[0]->emp_designation) ? $perPersonWiseMonthlyReport[0]->emp_designation : "" }}</b></span><br>
        <span>&nbsp;</span>
    </div>
 	<table class="tab">
        <thead>
            <tr>
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
            @foreach($perPersonWiseMonthlyReport as $key => $person)
                <tr class="amount-right">
                    <td>{{ $person->new_salary != 0 ? number_format($person->new_salary,2) : "" }}</td>
                    <td>{{$person->emp_grade}}</td>
                    <td>{{$person->scale_year}}</td>
                    <td>{{ $person->house_rent != 0 ? number_format($person->house_rent, 2) : ""}}</td>
                    <td>{{ $person->edu_allowance != 0 ? number_format($person->edu_allowance,2) : "" }}</td>
                    <td>{{ $person->medi_allowance != 0 ? number_format($person->medi_allowance,2) : "" }}</td>
                    <td>{{ $person->tiffin != 0 ? number_format($person->tiffin, 2) : "" }}</td>
                    <td>{{ $person->total_in != 0 ? number_format($person->total_in, 2) : "" }}</td>
                    <td>{{ $person->gpf != 0 ? number_format($person->gpf,2) : "" }}</td>
                    <td>{{ $person->house_rent_deduction != 0 ? number_format($person->house_rent_deduction, 2) : "" }}</td>
                    <td>{{ $person->water != 0 ? number_format($person->water,2) : "" }}</td>
                    <td>{{ $person->generator != 0 ? number_format($person->generator,2) : "" }}</td>
                    <td>{{ $person->electricity != 0 ? number_format($person->electricity, 2) : "" }}</td>
                    <td>{{ $person->previous_due != 0 ? number_format($person->previous_due,2) : "" }}</td>
                    <td>{{ $person->revenue != 0 ? number_format($person->revenue,2) : "" }}</td>
                    <td>{{ $person->total_deduction != 0 ? number_format($person->total_deduction,2) : "" }}</td>
                    <td>{{ $person->total_payable != 0 ? number_format($person->total_payable,2) : "" }}</td>




                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>