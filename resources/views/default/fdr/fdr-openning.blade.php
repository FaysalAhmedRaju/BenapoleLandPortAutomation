@extends('layouts.master')
@section('title','FDR Openning')
@section('style')
	<style type="text/css">
        [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
            display: none !important;
        }
    </style>
@endsection
@section('script')
		{!! Html :: script('js/customizedAngular/FDROpenning.js') !!}
		{!! Html :: script('js/bootbox.min.js')!!}
		<script type="text/javascript">
			$(function() {
				$('#opening_dt').datepicker( {
					changeMonth : true,
					changeYear : true,
					yearRange: "-100:+10",
	            	dateFormat: 'yy-mm-dd'
				});
				$('#expire_dt').datepicker( {
					changeMonth : true,
					changeYear : true,
					yearRange: "-100:+10",
	            	dateFormat: 'yy-mm-dd'
				});
			});
		</script>
@endsection
@section('content')
	<div class="col-md-12 ng-cloak" ng-app="FDROpenningApp" ng-controller="FDROpenningController">
		<div class="col-md-12" style="background-color: #f8f9f9; border-radius: 20px;">
			<h4 class="text-center ok">FDR Openning</h4>
			<div class="alert alert-success" id="savingSuccess" ng-hide="!savingSuccess">@{{savingSuccess}}</div>
			<div class="alert alert-danger" id="savingError" ng-hide="!savingError">@{{savingError}}</div>
			<div class="col-md-12">
				<form name="FDROpenningForm" id="FDROpenningForm" novalidate>
					<table>
						<tr>
							<th>S/l No.:</th>
							<td>
								<input type="text" class="form-control" name="sl_no" ng-model="sl_no" id="sl_no" required>
								<span class="error" ng-show="FDROpenningForm.sl_no.$invalid && submitted">S/l is required.</span>
							</td>
							<th style="padding-left: 15px;">Bank Name:</th>
							<td>
								<input type="text" class="form-control" name="bank_name" id="bank_name" ng-model="bank_name" required>
								<span class="error" ng-show="FDROpenningForm.bank_name.$invalid && submitted">Bank Name is required.</span>
							</td>
							<th style="padding-left: 15px;">FDR No.:</th>
							<td>
								<input type="text" class="form-control" name="fdr_no" id="fdr_no" ng-model="fdr_no" {{--required ng-pattern="/^\d{2,}[/]\d{2,}$/"--}}>
								<span class="error" ng-show="FDROpenningForm.fdr_no.$error.required && submitted">FDR No is required.</span>
								{{--<span class="error" ng-show="FDROpenningForm.fdr_no.$error.pattern && submitted">Input FDR No</span>--}}
							</td>
							<th style="padding-left: 15px;">Main Ammount:</th>
							<td>
								<input type="number" class="form-control" name="main_amount" id="main_amount" ng-model="main_amount" required ng-change="GetTotalInterest()">
								<span class="error" ng-show="FDROpenningForm.main_amount.$invalid && submitted">Main Ammount is required.</span>
							</td>
						</tr>
						<tr>
							<td colspan="8">&nbsp;</td>
						</tr>
						<tr>
							<th>Opening Date:</th>
							<td>
								<input type="text" class="form-control datePicker" name="opening_dt" id="opening_dt" ng-model="opening_dt" required ng-change="CountExpireDate()">
								<span class="error" ng-show="FDROpenningForm.opening_dt.$invalid && submitted">Opening Date is required.</span>
							</td>
							<th style="padding-left: 15px;">Duration:</th>
							<td>
								<input type="number" class="form-control" name="duration" id="duration" ng-model="duration" placeholder="Type year" required ng-change="CountExpireDate() || GetTotalInterest()">
								<span class="error" ng-show="FDROpenningForm.duration.$invalid && submitted">Duration is required.</span>
							</td>
							<th style="padding-left: 15px;">Expire Date:</th>
							<td>
								<input type="text" class="form-control datePicker" name="expire_dt" id="expire_dt" ng-model="expire_dt" required ng-disabled="disableExpireDate">
								<span class="error" ng-show="FDROpenningForm.expire_dt.$invalid && submitted">Expire Date is required.</span>
							</td>
							<th style="padding-left: 15px;">Interest Rate:</th>
							<td>
								<input type="number" class="form-control" name="interest_rate" id="interest_rate" ng-model="interest_rate" placeholder="Type %" required ng-change="GetTotalInterest()" ng-disabled="disableInterestRate">
								<span class="error" ng-show="FDROpenningForm.interest_rate.$invalid && submitted">Interest Rate is required.</span>
							</td>
						</tr>
						<tr>
							<td colspan="8">&nbsp;</td>
						</tr>
						<tr>
							<th>Total Interest:</th>
							<td>
								<input type="number" class="form-control" name="total_interest" id="total_interest" ng-model="total_interest" required {{-- ng-chan="GetIncomeTax()" --}}>
								<span class="error" ng-show="FDROpenningForm.total_interest.$invalid && submitted">Total Interest is required.</span>
							</td>
							<th style="padding-left: 15px;">Income Tax:</th>
							<td>
								<input type="number" class="form-control" name="income_tax" id="income_tax" ng-model="income_tax" required ng-disabled="disableIncomeTax">
								<span class="error" ng-show="FDROpenningForm.income_tax.$invalid && submitted">Income Tax is required.</span>
							</td>
							<th style="padding-left: 15px;">Excavator Tariff:</th>
							<td>
								<input type="number" class="form-control" name="excavator_tariff" id="excavator_tariff" ng-model="excavator_tariff" {{-- required --}}>
								<span class="error" ng-show="FDROpenningForm.excavator_tariff.$invalid && submitted">Excavator Tariff is required.</span>
							</td>
							<th style="padding-left: 15px;">Net Interest:</th>
							<td>
								<input type="number" class="form-control" name="net_interest" id="net_interest" ng-model="net_interest" required ng-disabled="disableNetInterest">
								<span class="error" ng-show="FDROpenningForm.net_interest.$invalid && submitted">Net Interest is required.</span>
							</td>
						</tr>
						<tr>
							<td colspan="8">&nbsp;</td>
						</tr>
						<tr>
							<th>Total with Interest:</th>
							<td>
								<input type="number" class="form-control" name="total_with_interest" id="total_with_interest" ng-model="total_with_interest" required ng-disabled="disableTotalWithInterest">
								<span class="error" ng-show="FDROpenningForm.total_with_interest.$invalid && submitted">Total with Interest is required.</span>
							</td>
							<th style="padding-left: 15px;">Comment:</th>
							<td>
								<input type="text" class="form-control" name="comments" id="comments" ng-model="comments">
								<span class="error" ng-show="FDROpenningForm.comments.$invalid && submitted">Comment is required.</span>
							</td>
						</tr>
						<tr>
							<td colspan="8">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="8" class="text-center">
								<button type="button" class="btn btn-primary center-block" ng-click="Save()" ng-if="btnSave">
									<span class="fa fa-download"></span>Save
								</button>
								{{--ng-show="btnSave"--}}
								<button type="button" class="btn btn-success center-block" ng-click="Update()" ng-if="btnUpdate">
									<span class="fa fa-download"></span>Update
								</button>
								{{--ng-show="btnUpdate"--}}
								<span ng-if="dataLoading">
									<img src="img/dataLoader.gif" width="250" height="15">
									<br> Please Wait !
								</span>
							</td>
						</tr>
					</table>
				</form>
				<br>
			</div>
		</div>
		<div class="col-md-12 table-responsive">
			<table class="table table-bordered" ng-show="showFDRDetails">
				<caption>
					<h4 class="text-center ok">FDR Accounts:</h4>
					<label class="form-inline">Search:<input class="form-control" ng-model="searchText"> </label>
				</caption>
				<thead>
					<tr>
						<th>S/L No.</th>
						<th>Bank Name</th>
						<th>FDR No.</th>
						<th>Main Ammount</th>
						<th>Openning Date</th>
						<th>Duration<br>(Year)</th>
						<th>Expire Date</th>
						<th>Interest Rate(%)</th>
						<th>Total Interest</th>
						<th>Income Tax</th>
						<th>Excavator Tariff</th>
						<th>Net Interest</th>
						<th>Total with Interest</th>
						<th>Comment</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-style="{'background-color':(FDR.id == selectedStyle?'#dbd3ff':'')}" 
					dir-paginate="FDR in allFDR | orderBy:'FDR.id' | itemsPerPage : 5 | filter : searchText" pagination-id="FDR">
						<td>@{{ FDR.sl_no }}</td>
						<td>@{{ FDR.bank_name }}</td>
						<td>@{{ FDR.fdr_no }}</td>
						<td>@{{ FDR.main_amount | number }}</td>
						<td>@{{ FDR.opening_dt }}</td>
						<td>@{{ FDR.duration }}</td>
						<td>@{{ FDR.expire_dt }}</td>
						<td>@{{ FDR.interest_rate | number }}</td>
						<td>@{{ FDR.total_interest | number }}</td>
						<td>@{{ FDR.income_tax | number }}</td>
						<td>@{{ FDR.excavator_tariff | number }}</td>
						<td>@{{ FDR.net_interest  | number }}</td>
						<td>@{{ FDR.total_with_interest | number }}</td>
						<td>@{{ FDR.comments }}</td>
						<td>
							<button style="width: 80px;" type="button" class="btn btn-success" ng-click="PressUpdateBtn(FDR)">Update</button>
							<button style="width: 80px;"  type="button" class="btn btn-danger" ng-click="PressDeleteBtn(FDR)">Delete</button>
						</td>
					</tr>
				</tbody>
				<tfoot>
                    <tr>
                        <td colspan="15" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="FDR">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                </tfoot>
			</table>
			
		</div>
	</div>
@endsection