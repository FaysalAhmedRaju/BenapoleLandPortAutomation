@extends('layouts.master')
@section('title','Generate Salary')
@section('style')
	<style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
@endsection
@section('script')
	{!! Html :: script('js/customizedAngular/payroll/generate-salary.js') !!}
    <script type="text/javascript">
        var employees = {!! json_encode($employees) !!};
    </script>
@endsection
@section('content')
	<div class="col-md-12 ng-cloak" ng-app="GenerateSalaryApp" ng-controller="GenerateSalaryCtrl">
		<div class="col-md-12">
			<form name="GenerateSalary" class="form-inline" novalidate>
                <div class="col-md-7 col-md-offset-4">
                    <div class="form-group">
                    <input style="width: 190px;" type="text" required  class="form-control"  ng-model="employee_name_id" name="employee_name" id="employee_name"
                           placeholder="Search Employee" >

                    </div>
                </div>

              <div class="col-md-6">
                  <label for="emp_id" class="form-controll">Employees:</label>
                  <label class="checkbox-inline">
                      <input type="checkbox" name="emp_id" id="select_all" value="select_all" {{-- ng-required="!emp_ids" --}} ng-click="toggleAll()" ng-model="isAllSelected">All
                  </label>
                  <span class="error" {{--style="margin-left: 90px;"--}} ng-show="emp_validation && submit">No Employee Selected</span>
              </div>



                <div class="form-group">
                    {{-- @foreach($employees as $k => $emp) --}}
                    <div class="col-md-3" ng-repeat="emp in employees">
                        <label class="checkbox-inline">
                            <input type="checkbox" name="emp_ids"  class="checkbox" ng-model="emp.selected"  ng-init="emp.selected=false" {{--ng-checked=""--}} {{-- value="{{$emp->id}}"  --}} {{-- ng-required="!emp_ids"  --}}
                            ng-change="optionToggled()">@{{emp.emp_id}} - @{{emp.name}}
                        </label>
                    </div>
                    {{-- @endforeach --}}
                </div>
                <div class="col-md-7 col-md-offset-3">
                    <div class="form-group">
                        <label for="month_year" class="form-controll">Month-Year:</label>
                        <input type="text" class="form-control datePicker" name="month_year" id="month_year" ng-model="month_year" required placeholder="Choose Month Year">
                        <button type="button" class="btn btn-primary" ng-click="generateSalary()">Generate</button>
                        <br>
                        <span class="error" style="margin-left: 90px;" ng-show="GenerateSalary.month_year.$invalid && submit">
                                Month-Year is Required
                        </span>
                    </div>
                    <a href="/accounts/salary/generate-salary/get-salary-report/@{{month_year}}" class="btn btn-success" target="_blank" ng-disabled="!month_year">PDF</a>
                    <span ng-if="dataLoading" style="margin-left: 50px; color:green; text-align:center; font-size:12px;">
                        <img src="/img/dataLoader.gif" width="250" height="15"/>
                        Please wait!
                    </span>
                </div>
			</form>
		</div>
		<div class="col-md-12 table-responsive">
			<table class="table table-bordered" ng-show="salarySheet">
				<caption><h4 class="ok text-center">Salary Sheet: @{{month_year}}</h4></caption>
				<thead>
					<tr>
						<th>Employee ID</th>
						<th>EmployeeName</th>
						<th>Desingnation</th>
                        <th>Grade</th>
						<th>Basic</th>
                        <th>Scale Year</th>
						<th>House<br>Rent</th>
						<th>Education<br>Allowance</th>
						<th>Medical</th>
						<th>Tiffin</th>
                        {{--<th>Wash</th>--}}
						<th>Total</th>
						<th>GPF</th>
						<th>Water</th>
						<th>Generator</th>
						<th>Electricity</th>
                        <th>House Rent<br></th>
						<th>Due</th>
						<th>Revenue</th>
						<th>Total<br>Deduction</th>
						<th>Payable<br>Amount</th>
					</tr>
				</thead>
				<tbody>
					<tr dir-paginate="employeeSalary in getEmployeesSalary | orderBy:'employeeSalary.id' | itemsPerPage:3000" {{--pagination-id="employeeSalary"--}}>
                            <td>@{{employeeSalary.emp_id}}</td>
                            <td>@{{employeeSalary.name}}</td>
                            <td>@{{employeeSalary.designation}}</td>
                            <td>@{{employeeSalary.grade}}</td>
                            <td>@{{employeeSalary.basic != 0 ? (employeeSalary.basic | numberFilter | number:2) : ""}}</td>
                            <td>@{{employeeSalary.scale_year != 0 ? (employeeSalary.scale_year) : ""}}</td>
                            <td>@{{ employeeSalary.house_rent != 0 ? (employeeSalary.house_rent | numberFilter | number:2) : ""}}</td>
                            <td>@{{employeeSalary.education_allow != 0 ? (employeeSalary.education_allow | numberFilter | number:2 ) : ""}}</td>
                            <td>@{{ employeeSalary.medical != 0 ? (employeeSalary.medical | numberFilter | number:2) : ""}}</td>
                            <td>@{{ employeeSalary.tiffin != 0 ? (employeeSalary.tiffin | numberFilter | number:2) : ""}}</td>
                            {{--<td>@{{ employeeSalary.washing != 0 ? (employeeSalary.washing | numberFilter | number:2) : ""}}</td>--}}
                            <td>@{{ employeeSalary.total_in != 0 ? (employeeSalary.total_in | numberFilter | number:2) : ""}}</td>
                            <td>@{{employeeSalary.gpf != 0 ?( employeeSalary.gpf | numberFilter | number:2 ): ""}}</td>
                            <td>@{{ employeeSalary.water != 0 ? (employeeSalary.water | numberFilter | number:2) : ""}}</td>
                            <td>@{{ employeeSalary.generator != 0 ? (employeeSalary.generator | numberFilter | number:2) : ""}}</td>
                            <td>@{{ employeeSalary.electricity != 0 ? (employeeSalary.electricity | numberFilter | number:2) : ""}}</td>
                            <td>@{{ employeeSalary.house_rent_deduction != 0 ? (employeeSalary.house_rent_deduction | numberFilter | number:2) : ""}}</td>
                            <td>@{{ employeeSalary.previous_due != 0 ? (employeeSalary.previous_due | numberFilter | number:2) : ""}}</td>
                            <td>@{{ employeeSalary.revenue != 0 ? (employeeSalary.revenue | numberFilter | number:2) : ""}}</td>
                            <td>@{{ employeeSalary.total_de != 0 ? (employeeSalary.total_de | numberFilter | number:2) : ""}}</td>
                            <td>@{{employeeSalary.total_payment != 0 ?( employeeSalary.total_payment | numberFilter | number:2 ): ""}}</td>
                        </tr>
				</tbody>
				<tfoot>
                       {{-- <tr>
                            <td colspan="20" class="text-center">
                                --}}{{--<dir-pagination-controls max-size="3"--}}{{--
                                                     --}}{{--direction-links="true"--}}{{--
                                                     --}}{{--boundary-links="true"--}}{{--
                                                     --}}{{--pagination-id="employeeSalary">--}}{{--
                                --}}{{--</dir-pagination-controls>--}}{{--
                            </td>
                        </tr>--}}
                </tfoot>
			</table>
		</div>
		<div class="col-md-12 text-center">
			<br>
			<div class="col-md-12" ng-show="saveSalaryDiv">
				<button type="button" class="btn btn-primary" ng-click="saveSalary()">Save Salary</button>
			</div>
			<div class="col-md-12">
				<span ng-if="savingData" style="color:green; text-align:center; font-size:12px">
                                        <img src="img/dataLoader.gif" width="250" height="15"/>
                                        <br/> Saving...!
             	</span>
             	<div id="saveSuccess" class="col-md-12 alert alert-success ok" ng-show="insertSuccessMsg">
                Salary Successfully Saved!
            </div>
			</div>
			
		</div>
		<script type="text/javascript">
            $(function() {
                $('#month_year').datepicker( {
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

           // $("#select_all").change(function(){  //"select all" change 
           //      $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
           //  });

           //  //".checkbox" change 
           //  $('.checkbox').change(function(){ 
           //      //uncheck "select all", if one of the listed checkbox item is unchecked
           //      if(false == $(this).prop("checked")){ //if this item is unchecked
           //          $("#select_all").prop('checked', false); //change "select all" checked status to false
           //      }
           //      //check "select all" if all checkbox items are checked
           //      if ($('.checkbox:checked').length == $('.checkbox').length ){
           //          $("#select_all").prop('checked', true);
           //      }
           //  });
        </script>
	</div>
@endsection