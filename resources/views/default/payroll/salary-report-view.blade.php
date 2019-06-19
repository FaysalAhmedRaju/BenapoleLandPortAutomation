@extends('layouts.master')
@section('title', 'Salary Reports')
@section('script')
	{!! Html :: script('js/customizedAngular/salaryReport.js') !!}
@endsection
@section('style')
	<style type="text/css">
		.ui-datepicker-calendar {
			display: none;
		}
	</style>

@endsection
@section('content')
	<div class="col-md-12 ng-cloak" ng-app="salaryReportApp" ng-controller="salaryReportCtrl">
		<h4 class="ok text-center">Salary Reports</h4>
		<div class="col-md-10 col-md-offset-2">
		<div class="col-md-4" style="box-shadow: 0 0 5px gray;">
			<h4 class="ok text-center">Per Person Wise Monthly Report</h4>
			<form name="PerPersonWiseMonthlyReportForm" action="{{ route('accounts-salary-salary-report-per-person-wise-monthly-report') }}" target="_blank" ng-submit="validationPerPersonWiseMonthlyReport(PerPersonWiseMonthlyReportForm)" novalidate>
				<table>
					<tr>
						<th>Employee<span class="mandatory">*</span>:</th>
						<td>
                            <select class="form-control" name ="emp_id" ng-model="emp_id" id="emp_id" ng-options="emp.id as emp.emp_id+'-'+emp.name for emp in allValidEmployees" required>
                                <option value="" selected="selected">Select Employee</option>
                            </select>
                            <span class="error" ng-show="PerPersonWiseMonthlyReportForm.emp_id.$invalid && submittedPerPersonWiseMonthlyReport">Employee is required</span>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<th>Month-Year<span class="mandatory">*</span>:</th>
						<td>
							<input type="text" class="form-control datePicker" name="month_year" id="month_year" ng-model="month_year" placeholder="Choose Month-Year" required>
                            <span class="error" ng-show="PerPersonWiseMonthlyReportForm.month_year.$invalid && submittedPerPersonWiseMonthlyReport">Month-Year is required</span>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">
							<button class="btn btn-primary center-block" type="submit">Get</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<div class="col-md-2">
			
		</div>
		<div class="col-md-4" style="box-shadow: 0 0 5px gray;">
			<h4 class="ok text-center">Per Person Wise Yearly Report<br><span style="color: blue;">(Fiscal Year)</span></h4>
			<form name="PerPersonWiseYearlyReportForm" action="{{ route('accounts-salary-salary-report-per-person-wise-yearly-report') }}" target="_blank" ng-submit="validationPerPersonWiseYearlyReport(PerPersonWiseYearlyReportForm)" novalidate>
				<table>
					<tr>
						<th>Employee<span class="mandatory">*</span>:</th>
						<td>
                            <select class="form-control" name ="emp_id" ng-model="emp_id1" id="emp_id" ng-options="emp.id as emp.emp_id+'-'+emp.name for emp in allValidEmployees" required>
                                <option value="" selected="selected">Select Employee</option>
                            </select>
                            <span class="error" ng-show="PerPersonWiseYearlyReportForm.emp_id1.$invalid && submittedPerPersonWiseYearly">Employee is required</span>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<th>From<span class="mandatory">*</span>:</th>
						<td>
							{{-- <select class="form-control" id="year" name="year" ng-model="year" ng-options="year.value as year.text for year in years" required>
                                <option value="">Select Year</option>
                            </select>
                            <span class="error" ng-show="PerPersonWiseYearlyReportForm.year.$invalid && submittedPerPersonWiseYearly">Fiscal Year is required</span> --}}
                            <input type="text" class="form-control datePicker" name="from" id="from" ng-model="from" placeholder="Choose Month-Year" required>
                            <span class="error" ng-show="PerPersonWiseYearlyReportForm.from.$invalid && submittedPerPersonWiseYearly">From is required</span>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					<tr>
					<tr>
						<th>To<span class="mandatory">*</span>:</th>
						<td>
							<input type="text" class="form-control datePicker" name="to" id="to" ng-model="to" placeholder="Choose Month-Year" required>
                            <span class="error" ng-show="PerPersonWiseYearlyReportForm.to.$invalid && submittedPerPersonWiseYearly">To is required</span>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">
							<button class="btn btn-primary center-block" type="submit">Get</button>
						</td>
					</tr>
				</table>
			</form>
		</div>
			<div class="col-md-4" style="box-shadow: 0 0 5px gray;">
				<h4 class="ok text-center">Monthly Salaray Report</h4>
				<table>
					<tr>
						<th>Grade:<span class="mandatory">*</span>:</th>
						<td>
							<select class="form-control" name ="grade_name" ng-model="grade" id="grade_name" required>
								<option value="" selected="selected">Select Grade:</option>
								@if($grade_list)
									@foreach($grade_list as $key=>$value)
										<option value="{{$value->grade_name}}">{{$value->grade_name}}</option>
									@endforeach
								@endif
							</select>
							<span class="error" ng-show="PerPersonWiseYearlyReportForm.grade_name.$invalid && submittedPerPersonWiseYearly">Grade is required</span>
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					@if(Auth::user()->role->id ==1)
						<tr>
							<th>Designation:<span class="mandatory">*</span>:</th>
							<td>
								<select class="form-control" name ="emp_id" ng-model="designation" id="emp_id" ng-init="designation='0'" required>
									<option value="0" selected="selected">Select Designation:</option>
									@if($designation_list)
										@foreach($designation_list as $key=>$value)
											<option value="{{$value->designation}}">{{$value->designation}}</option>
											@endforeach
									@endif
								</select>
								<span class="error" ng-show="PerPersonWiseYearlyReportForm.emp_id.$invalid && submittedPerPersonWiseYearly">Designation is required</span>
							</td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
					@endif
					<tr>
						<th>Month-Year:<span class="mandatory">*</span>:</th>
						<td>
							<input type="text" class="form-control datePicker" name="monthly" id="monthly" ng-model="monthly" placeholder="Choose Month-Year">
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" class="text-center">

							<a href="/accounts/salary/generate-salary/get-salary-report/@{{monthly}}/@{{grade}}@if(Auth::user()->role->id ==1)/@{{designation}}@endif" class="btn btn-primary" target="_blank">Get</a>
						</td>
					</tr>
				</table>

			</div>
		</div>
		<script type="text/javascript">
            $(function() {
                $('#month_year , #from , #to, #monthly').datepicker( {
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'MM yy',
                    onClose: function(dateText, inst) { 
                        function isDonePressed(){
                            return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
                        }

                        if (isDonePressed()){

                            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                            $(this).datepicker('setDate', new Date(year, month, 1)).trigger('input');
                            //console.log(a);

                        }
                    }
                });
            });
        </script>
	</div>
@endsection
