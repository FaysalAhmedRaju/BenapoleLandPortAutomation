@extends('layouts.master')
@section('title','FDR Details')
@section('style')
@endsection
@section('script')
	{!! Html::script('js/customizedAngular/fdr/fdr-details.js') !!}
	{!! Html::script('js/bootbox.min.js')!!}
	<script type="text/javascript">
		$(function() {
			$('#opening_dtfdrOpenOrRenew').datepicker( {
				changeMonth : true,
				changeYear : true,
				yearRange: "-100:+10",
            	dateFormat: 'yy-mm-dd'
			});
			$('#expire_dtfdrOpenOrRenew').datepicker( {
				changeMonth : true,
				changeYear : true,
				yearRange: "-100:+10",
            	dateFormat: 'yy-mm-dd'
			});
		});
	</script>
@endsection
@section('content')
	<div class="col-md-12 ng-cloak" ng-app="FDRDetailsApp" ng-controller="FDRDetailsCtrl">
		<div class="col-md-8 col-md-offset-2 formBgColor">
			<h4 class="text-center ok">FDR Account</h4>
			<div class="alert alert-success" id="savingSuccessFDRDetail" ng-hide="!savingSuccessFDRDetail">@{{savingSuccessFDRDetail}}</div>
			<div class="alert alert-danger" id="savingErrorFDRDetail" ng-hide="!savingErrorFDRDetail">@{{savingErrorFDRDetail}}</div>
			<div class="col-md-12">
				<form name="FDRDetailsForm" id="FDRDetailsForm" novalidate>
					<table>
						<tr>
							<th>Bank Name<span class="mandatory">*</span>:</th>
							<td>
								<select class="form-control" name="bank_detail_id" id="bank_detail_id" ng-model="bank_detail_id" ng-options="bank.id as bank.name_and_address for bank in allbanks" required style="width: 180px;">
									<option value="" selected="selected">Please Select</option>
								</select>
								<span class="error" ng-show="FDRDetailsForm.bank_detail_id.$invalid && submitFDRDetailsForm">Bank Name is required</span>
							</td>
							<th style="padding-left: 30px;">FDR No<span class="mandatory">*</span>:</th>
							<td>
								<input type="text" class="form-control" name="fdr_no" id="fdr_no" ng-model="fdr_no" placeholder="Enter FDR No" required>
								<span class="error" ng-show="FDRDetailsForm.fdr_no.$invalid && submitFDRDetailsForm">FDR No is required</span>
							</td>
							{{-- <th style="padding-left: 30px;">S/L No:</th>
							<td>
								<input type="text" class="form-control" name="sl_no" id="sl_no" ng-model="sl_no" placeholder="Enter Serial No" required>
								<span class="error" ng-show="FDRDetailsForm.sl_no.$invalid && submitFDRDetailsForm">S/L No is required</span>
							</td> --}}
							<td class="text-center" style="padding-left: 30px;">
								<button type="button" class="btn btn-primary center-block" ng-click="saveFDRDetails(FDRDetailsForm)" ng-if="FDRDetailsSaveBtn">Save</button>
								<button type="button" class="btn btn-info center-block" ng-click="updateFDRDetails(FDRDetailsForm)" ng-if="FDRDetailsUpdateBtn">Update</button>
							</td>
						</tr>
						<tr>
							<td colspan="6">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="6" class="text-center">
								{{-- <button type="button" class="btn btn-primary center-block" ng-click="saveFDRDetails(FDRDetailsForm)" ng-if="FDRDetailsSaveBtn">Save</button>
								<button type="button" class="btn btn-info center-block" ng-click="updateFDRDetails(FDRDetailsForm)" ng-if="FDRDetailsUpdateBtn">Update</button> --}}
								<span ng-if="FDRDetailsdatLoading">
									<img src="img/dataLoader.gif" width="250" height="15" />
                                    <br />Please wait!
								</span>
							</td>
						</tr>
					</table>
				</form>
				<br>			
			</div>
		</div>
		<div class="col-md-2">
			<button type="button" class="btn btn-success pull-right" data-target="#addBank" data-toggle="modal">Add Bank</button>
			<a class="btn btn-primary pull-right" href="{{route('accounts-fdr-report-get-total-fund-postion-report')}}" target="_blank" style="margin-top: 2em;">Total Fund Position</a>
		</div>
		<span class="col-md-12 text-center" ng-if="FDRAccountDetailsViewLoading">
			<img src="img/dataLoader.gif" width="550" height="30" />
            <br />Please Wait !
		</span>
		<div class="col-md-12 table-responsive">
			<table class="table table-bordered text-center" ng-show="FDRAccountDetailsView">
				<caption><h4 class="text-center ok">FDR Account Details</h4><label class="form-inline">Search:<input class="form-control" ng-model="searchTextFDRAccount"></label></caption>
				<thead>
					<tr>
						<th>S/L No</th>
						<th>Bank Name</th>
						<th>FDR No</th>
						<th>Account Status & Duration</th>
						<th>Account Operations</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<tr dir-paginate="account in allaccounts | orderBy:'account.id':true | filter:searchTextFDRAccount | itemsPerPage:10 " pagination-id="account">
						<td>@{{ account.sl_no }}</td>
						<td>@{{ account.bank_name_and_address }}</td>
						<td>@{{ account.fdr_no }}</td>
						<td>
						    <div class="progress" ng-show="account.status && !((getProgressbarValue(account.diff_from_today,account.total_day_difference, 1, $index)==2) || (getProgressbarValue(account.diff_from_today,account.total_day_difference, 2, $index)==false))">
							    <div id="@{{ $index }}" class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:@{{ getProgressbarValue(account.diff_from_today,account.total_day_difference, 3, $index)}}"getProgressbarValue(account.diff_from_today,account.total_day_difference, 4, $index)">
							      @{{getProgressbarValue(account.diff_from_today,account.total_day_difference, 5, $index)}}
							    </div>
						  </div>
						  <span class="text-warning" ng-show="!account.status">Already Closed</span>
						  <span class="text-info" ng-show="getProgressbarValue(account.diff_from_today,account.total_day_difference, 6, $index) ==false">Open Account</span>
						  <span class="text-danger" ng-show="getProgressbarValue(account.diff_from_today,account.total_day_difference, 7, $index) ==2">Invalid Input</span>
						</td>
						<td>
							<button type="button" class="btn btn-success" ng-if="account.status" data-target="#fdrOpenOrRenew" data-toggle="modal" ng-click="pressBtnFDROpenOrRenew(account)">@{{ account.fdr_actions_count != 0 ? "Renew" : "Open"}}</button>
							<button type="button" class="btn btn-primary" ng-show="account.status && account.fdr_actions_count" data-target="#fdrClose" data-toggle="modal" ng-click="pressBtnFDRClose(account)">Close</button>
							<button type="button" class="btn btn-primary" ng-show="!account.status" ng-click="btnReopenFDRAccout(account)">Reopen</button>
							<a class="btn btn-default" ng-show="account.fdr_actions_count" target="_blank" href="/accounts/fdr/report/get-fdr-wise-report/@{{account.id}}">Report</a>
						</td>
						<td>
							<button type="button" class="btn btn-info" ng-click="pressUpdateBtnFDRAccounts(account)">Update</button>
							<button type="button" class="btn btn-danger" ng-click="pressDeleteBtnFDRAccounts(account)">Delete</button>
						</td>
					</tr>
				</tbody>
				<tfoot>
                    <tr>
                        <td colspan="6" class="text-center">
                            <dir-pagination-controls max-size="5"
                                                 direction-links="true"
                                                 boundary-links="true"
                                                 pagination-id="account">
                            </dir-pagination-controls>
                        </td>
                    </tr>
                </tfoot>
			</table>
		</div>
		{{--------------------------------- Add Bank Model--------------------------}}
        <div class="modal fade text-center" style="left: 0;" id="addBank" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content formBgColor">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title text-center ok">Add Bank</h4>
                    </div>
                    <div class="modal-body">
	                    <div class="alert alert-success" id="savingSuccessBank" ng-hide="!savingSuccessBank">@{{savingSuccessBank}}</div>
						<div class="alert alert-danger" id="savingErrorBank" ng-hide="!savingErrorBank">@{{savingErrorBank}}</div>
                    	<form name="bankForm" id="bankForm">
							<table>
								<tr>
									<th>Name & Address:</th>
									<td>
										<input type="text" class="form-control" name="name_and_address" id="name_and_address" ng-model="name_and_address" placeholder="Enter Bank Name & Address" required>
										<span class="error" ng-show="bankForm.name_and_address.$invalid && submitBankForm">Name & Address is required</span>
									</td>
									<th style="padding-left: 20px;">Bank Type:</th>
									<td>
										<select class="form-control" name="type" id="type" ng-model="type" ng-options="bankType.value as bankType.text for bankType in bankTypes" required>
											<option value="" selected>Please Select</option>
										</select>
										<span class="error" ng-show="bankForm.type.$invalid && submitBankForm">Bank Type is required</span>
									</td>
								</tr>
								<tr>
                   					<td colspan="6">&nbsp;</td>
                   				</tr>
                   				<tr>
                   					<td colspan="6" class="text-center">
                   						<button type="button" class="btn btn-primary center-block" ng-click="saveBank(bankForm)" ng-if="BankSaveBtn">Save</button>
										<button type="button" class="btn btn-success center-block" ng-click="updateBank(bankForm)" ng-if="BankUpdateBtn">Update</button>
										<span ng-if="BankDetailsdatLoading">
											<img src="img/dataLoader.gif" width="250" height="15" />
		                                    <br />Please wait!
										</span>
                   					</td>
                   				</tr>
							</table>                    		
                    	</form>
                    </div>
                    <div class="modal-footer">
                   		<table class="table table-bordered text-center">
                   			<caption><h4 class="text-center ok">Bank List</h4><label class="form-inline">Search:<input class="form-control" ng-model="searchTextBank"></label></caption>
                   			<thead>
                   				<tr>
                   					<th>S/L</th>
                   					<th>Name & Address</th>
                   					<th>Bank Type</th>
                   					<th>Action</th>
                   				</tr>
                   			</thead>
                   			<tbody>
                   				<tr dir-paginate="bank in allbanks | orderBy:'bank.id':true | itemsPerPage:5 | filter:searchTextBank" pagination-id="bank">
                   					<td>@{{ $index+1 }}</td>
                   					<td>@{{ bank.name_and_address }}</td>
                   					<td>@{{ bank.type | bankTypeFilter }}</td>
                   					<td>
                   						<div class="btn-group">
	                   						<button type="button" class="btn btn-success btn-xs" ng-click="pressBankdetailUpdateBtn(bank)">Update</button>
											<button type="button" class="btn btn-danger btn-xs" ng-click="pressBankdetailDeleteBtn(bank)">Delete</button>
										</div>
                   					</td>
                   				</tr>
                   			</tbody>
                   			<tfoot>
			                    <tr>
			                        <td colspan="4" class="text-center">
			                            <dir-pagination-controls max-size="5"
			                                                 direction-links="true"
			                                                 boundary-links="true"
			                                                 pagination-id="bank">
			                            </dir-pagination-controls>
			                        </td>
			                    </tr>
			                </tfoot>
                   		</table>
                    </div>
                </div>
            </div>
        </div>
        {{-- ------------------------Add Bank Model--------------------------}}
        {{--------------------------------- FDR ACCOUNT OPEN OR RENEW Model--------------------------}}
        <div class="modal fade text-center" id="fdrOpenOrRenew" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content formBgColor" style="width: 1250px; left: -120px;">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" ng-click="reLoadFDRAccounts()">&times;</button>
                        <h4 class="modal-title text-center ok">Account Open Or Renew</h4>
                    </div>
                    <div class="modal-body">
                    	<div class="col-md-12">
                    		<div class="col-md-4">
                    			<p><b>Bank Name & Address:</b> @{{showBankName}}</p>
                    		</div>
                    		<div class="col-md-4">
                    			<p><b>S/L No:</b> @{{showfdrSlNo}}</p>
                    		</div>
                    		<div class="col-md-4">
                    			<p><b>FDR No:</b> @{{showFdrNo}}</p>
                    		</div>
                    		
                    	</div>
                    	<div class="col-md-12">
		                    <div class="alert alert-success" id="savingSuccessfdrOpenOrRenew" ng-hide="!savingSuccessfdrOpenOrRenew">@{{savingSuccessfdrOpenOrRenew}}</div>
							<div class="alert alert-danger" id="savingErrorfdrOpenOrRenew" ng-hide="!savingErrorfdrOpenOrRenew">@{{savingErrorfdrOpenOrRenew}}</div>
						</div>
                    	<form name="fdrOpenOrRenewForm" id="fdrOpenOrRenewForm" novalidate>
							<table>
								<tr>
									{{-- <th>S/l No.:</th>
									<td>
										<input type="text" class="form-control" name="sl_nofdrOpenOrRenew" ng-model="sl_nofdrOpenOrRenew" id="sl_nofdrOpenOrRenew" required>
										<span class="error" ng-show="fdrOpenOrRenewForm.sl_nofdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">S/l is required.</span>
									</td> --}}
									<th>Main Ammount:</th>
									<td>
										<input type="number" class="form-control" name="main_amountfdrOpenOrRenew" id="main_amountfdrOpenOrRenew" ng-model="main_amountfdrOpenOrRenew" required ng-change="GetTotalInterest()" placeholder="Enter Main Amount">
										<span class="error" ng-show="fdrOpenOrRenewForm.main_amountfdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Main Ammount is required.</span>
									</td>
									<th style="padding-left: 15px;">Opening Date:</th>
									<td>
										<input type="text" class="form-control datePicker" name="opening_dtfdrOpenOrRenew" id="opening_dtfdrOpenOrRenew" ng-model="opening_dtfdrOpenOrRenew" required ng-change="CountExpireDate()" placeholder="Choose Opening Date">
										<span class="error" ng-show="fdrOpenOrRenewForm.opening_dtfdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Opening Date is required.</span>
									</td>
									<th style="padding-left: 15px;">Duration:</th>
									<td>
										<input type="number" class="form-control" name="durationfdrOpenOrRenew" id="durationfdrOpenOrRenew" ng-model="durationfdrOpenOrRenew" placeholder="Type Duration in Month" required ng-change="CountExpireDate() || GetTotalInterest()">
										<span class="error" ng-show="fdrOpenOrRenewForm.durationfdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Duration is required.</span>
									</td>
									<th style="padding-left: 15px;">Expire Date:</th>
									<td>
										<input type="text" class="form-control datePicker" name="expire_dtfdrOpenOrRenew" id="expire_dtfdrOpenOrRenew" ng-model="expire_dtfdrOpenOrRenew" required ng-disabled="disableExpireDate" placeholder="Choose Expire Date">
										<span class="error" ng-show="fdrOpenOrRenewForm.expire_dtfdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Expire Date is required.</span>
									</td>
								</tr>
								<tr>
									<td colspan="8">&nbsp;</td>
								</tr>
								<tr>
									<th>Interest Rate:</th>
									<td>
										<input type="number" class="form-control" name="interest_ratefdrOpenOrRenew" id="interest_ratefdrOpenOrRenew" ng-model="interest_ratefdrOpenOrRenew" required ng-change="GetTotalInterest()" ng-disabled="disableInterestRate" placeholder="Enter Rate in %">
										<span class="error" ng-show="fdrOpenOrRenewForm.interest_ratefdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Interest Rate is required.</span>
									</td>
									<th style="padding-left: 15px;">Total Interest:</th>
									<td>
										<input type="number" class="form-control" name="total_interestfdrOpenOrRenew" id="total_interestfdrOpenOrRenew" ng-model="total_interestfdrOpenOrRenew" required {{-- ng-chan="GetIncomeTax()" --}} placeholder="Enter Total Interest">
										<span class="error" ng-show="fdrOpenOrRenewForm.total_interestfdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Total Interest is required.</span>
									</td>
									<th style="padding-left: 15px;">Income Tax:</th>
									<td>
										<input type="number" class="form-control" name="income_taxfdrOpenOrRenew" id="income_taxfdrOpenOrRenew" ng-model="income_taxfdrOpenOrRenew" required ng-disabled="disableIncomeTax" placeholder="Enter Income Tax">
										<span class="error" ng-show="fdrOpenOrRenewForm.income_taxfdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Income Tax is required.</span>
									</td>
									<th style="padding-left: 15px;">Excavator Tariff:</th>
									<td>
										<input type="number" class="form-control" name="excavator_tarifffdrOpenOrRenew" id="excavator_tarifffdrOpenOrRenew" ng-model="excavator_tarifffdrOpenOrRenew" {{-- required --}} placeholder="Enter Excavator Tariff">
										<span class="error" ng-show="fdrOpenOrRenewForm.excavator_tarifffdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Excavator Tariff is required.</span>
									</td>
								</tr>
								<tr>
									<td colspan="8">&nbsp;</td>
								</tr>
								<tr>
									<th>Bank Charge:</th>
									<td>
										<input type="number" class="form-control" name="bank_chargefdrOpenOrRenew" id="bank_chargefdrOpenOrRenew" ng-model="bank_chargefdrOpenOrRenew" placeholder="Enter Bank Charge" ng-disabled="!showFDROpenningOrRenew">
										<span class="error" ng-show="fdrOpenOrRenewForm.bank_chargefdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Bank Charge is required.</span>
									</td>
									<th style="padding-left: 15px;">VAT:</th>
									<td>
										<input type="number" class="form-control" name="vatfdrOpenOrRenew" id="vatfdrOpenOrRenew" ng-model="vatfdrOpenOrRenew" placeholder="Type VAT in %" ng-disabled="!showFDROpenningOrRenew">
										<span class="error" ng-show="fdrOpenOrRenewForm.vatfdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">VAT is required.</span>
									</td>
									<th style="padding-left: 15px;">Net Interest:</th>
									<td>
										<input type="number" class="form-control" name="net_interestfdrOpenOrRenew" id="net_interestfdrOpenOrRenew" ng-model="net_interestfdrOpenOrRenew" required ng-disabled="disableNetInterest" placeholder="Enter Net Interest">
										<span class="error" ng-show="fdrOpenOrRenewForm.net_interestfdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Net Interest is required.</span>
									</td>
									<th style="padding-left: 15px;">Total Balance:</th>
									<td>
										<input type="number" class="form-control" name="total_balancefdrOpenOrRenew" id="total_balancefdrOpenOrRenew" ng-model="total_balancefdrOpenOrRenew" required ng-disabled="disableTotalBalance" placeholder="Enter Total Balance">
										<span class="error" ng-show="fdrOpenOrRenewForm.total_balancefdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Total Balance is required.</span>
									</td>
								</tr>
								<tr>
									<td colspan="8">&nbsp;</td>
								</tr>
								<tr>
									<th>Comments:</th>
									<td>
										<input type="text" class="form-control" name="commentsfdrOpenOrRenew" id="commentsfdrOpenOrRenew" ng-model="commentsfdrOpenOrRenew" placeholder="Enter Comments">
										<span class="error" ng-show="fdrOpenOrRenewForm.commentsfdrOpenOrRenew.$invalid && submittedFdrOpenOrRenew">Comments is required.</span>
									</td>
								</tr>
								<tr>
									<td colspan="8">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="8" class="text-center">
										<button type="button" class="btn btn-primary center-block" ng-click="SaveFdrOpenOrRenew(fdrOpenOrRenewForm)" ng-if="btnSavefdrOpenOrRenew">
											<span class="fa fa-download"></span>Save
										</button>
										{{--ng-show="btnSave"--}}
										<button type="button" class="btn btn-success center-block" ng-click="UpdateFdrOpenOrRenew(fdrOpenOrRenewForm)" ng-if="btnUpdatefdrOpenOrRenew">
											<span class="fa fa-download"></span>Update
										</button>
										{{--ng-show="btnUpdate"--}}
										<span ng-if="dataLoadingfdrOpenOrRenew">
											<img src="img/dataLoader.gif" width="250" height="15">
											<br> Please Wait !
										</span>
									</td>
								</tr>
							</table>
						</form>
                    </div>
                    <div class="modal-footer table-responsive">
                    	<span class="col-md-12 text-center" ng-if="showFDROpenningOrRenewLoading">
							<img src="img/dataLoader.gif" width="550" height="30" />
				            <br />Please Wait !
						</span>
                   		<table class="table table-bordered text-center" ng-if="showFDROpenningOrRenew">
							<caption>
								<h4 class="text-center ok">Accounts Details</h4>
								<label class="form-inline">Search:<input class="form-control" ng-model="searchTextFDROpenOrClose"> </label>
							</caption>
							<thead>
								<tr>
									<th>S/L No.</th>
									<th>Main Ammount</th>
									<th>Openning Date</th>
									<th>Duration<br>(Mounth)</th>
									<th>Expire Date</th>
									<th>Interest Rate(%)</th>
									<th>Total Interest</th>
									<th>Income Tax</th>
									<th>Excavator Tariff</th>
									<th>Bank Charge</th>
									<th>VAT(%)</th>
									<th>Net Interest</th>
									<th>Total Amount</th>
									<th>Comment</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr {{-- ng-style="{'background-color':(FDR.id == selectedStyle?'#dbd3ff':'')}" --}} dir-paginate="FDROpenningOrRenew in allFDROpenningOrRenew | orderBy:'FDROpenningOrRenew.id' | itemsPerPage : 7 | filter : searchTextFDROpenOrClose" pagination-id="FDROpenningOrRenew">
									<td>@{{ FDROpenningOrRenew.sl_no }}</td>
									<td class="amount-right">@{{ FDROpenningOrRenew.main_amount | number }}</td>
									<td>@{{ FDROpenningOrRenew.opening_date }}</td>
									<td>@{{ FDROpenningOrRenew.duration }}</td>
									<td>@{{ FDROpenningOrRenew.expire_date }}</td>
									<td>@{{ FDROpenningOrRenew.interest_rate | number }}</td>
									<td class="amount-right">@{{ FDROpenningOrRenew.total_interest | number }}</td>
									<td class="amount-right">@{{ FDROpenningOrRenew.income_tax | number }}</td>
									<td class="amount-right">@{{ FDROpenningOrRenew.excavator_tariff | number }}</td>
									<td class="amount-right">@{{ FDROpenningOrRenew.bank_charge | number }}</td>
									<td class="amount-right">@{{ FDROpenningOrRenew.vat  | number }}</td>
									<td class="amount-right">@{{ FDROpenningOrRenew.net_interest  | number }}</td>
									<td class="amount-right">@{{ FDROpenningOrRenew.total_balance | number }}</td>
									<td>@{{ FDROpenningOrRenew.comments }}</td>
									<td>
										<button type="button" class="btn btn-success btn-xs" ng-click="PressUpdateBtnFDROpenningOrRenew(FDROpenningOrRenew)">Update</button>
										<button type="button" class="btn btn-danger btn-xs" ng-click="PressDeleteBtnFDROpenningOrRenew(FDROpenningOrRenew)" ng-disabled="FDROpenningOrRenew.status == 0">Delete</button>
									</td>
								</tr>
							</tbody>
							<tfoot>
			                    <tr>
			                        <td colspan="15" class="text-center">
			                            <dir-pagination-controls max-size="5"
			                                                 direction-links="true"
			                                                 boundary-links="true"
			                                                 pagination-id="FDROpenningOrRenew">
			                            </dir-pagination-controls>
			                        </td>
			                    </tr>
			                </tfoot>
						</table>
						<button class="btn btn-warning center-block" data-dismiss="modal" ng-click="reLoadFDRAccounts()">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ------------------------FDR ACCOUNT OPEN OR RENEW Model--------------------------}}
        {{--------------------------------- FDR CLOSE Model--------------------------}}
        <div class="modal fade text-center" id="fdrClose" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content formBgColor" style="width: 1250px; left: -120px;">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" ng-click="reLoadFDRAccounts()">&times;</button>
                        <h4 class="modal-title text-center ok">FDR Close</h4>
                    </div>
                    <div class="modal-body">
                    	<div class="col-md-12">
                    		<div class="col-md-3">
                    			<p><b>Bank Name & Address:</b> @{{showBankName}}</p>
                    		</div>
                    		<div class="col-md-2">
                    			<p><b>S/L No:</b> @{{showfdrSlNo}}</p>
                    		</div>
                    		<div class="col-md-2">
                    			<p><b>FDR No:</b> @{{showFdrNo}}</p>
                    		</div>
                    		<div class="col-md-2">
                    			<p><b>Expire At:</b>@{{expireDateForFDRClose}}</p>
                    		</div>
                    		<div class="col-md-3">
                    			<p><b>Total Balance:</b>@{{totalBalanceForFDRCLose}}</p>
                    		</div>
                    		
                    	</div>
                    	<div class="col-md-12">
		                    <div class="alert alert-success" id="savingSuccessfdrClose" ng-hide="!savingSuccessfdrClose">@{{savingSuccessfdrClose}}</div>
							<div class="alert alert-danger" id="savingErrorfdrClose" ng-hide="!savingErrorfdrClose">@{{savingErrorfdrClose}}</div>
						</div>
                    	<form name="fdrCLoseForm" id="fdrCLoseForm" novalidate>
							<table>
								<tr>
									<th>Bank Name:</th>
									<td>
										<select class="form-control" name="bank_detail_id_for_fdr_closing" id="bank_detail_id_for_fdr_closing" ng-model="bank_detail_id_for_fdr_closing" ng-options="bank.id as bank.name_and_address for bank in allbanks" required style="width: 200px;">
											<option value="" selected="selected">Please Select</option>
										</select>
										<span class="error" ng-show="fdrCLoseForm.bank_detail_id_for_fdr_closing.$invalid && submitFDRClosingForm">Bank Name is required</span>
									</td>
									<th style="padding-left: 15px;">Payorde/Cheque/Payslip No:</th>
									<td>
										<input type="text" class="form-control" name="payorder_cheque_payslip_no" id="payorder_cheque_payslip_no" ng-model="payorder_cheque_payslip_no" required placeholder="Enter Payorde/Cheque/Payslip No">
										<span class="error" ng-show="fdrCLoseForm.payorder_cheque_payslip_no.$invalid && submitFDRClosingForm">Payorde/Cheque/Payslip No is required</span>
									</td>
									<th style="padding-left: 15px;">Transaction Account No:</th>
									<td>
										<input type="text" class="form-control" name="transaction_acc_no" id="transaction_acc_no" ng-model="transaction_acc_no" placeholder="Enter Transaction Account No" required>
										<span class="error" ng-show="fdrCLoseForm.transaction_acc_no.$invalid && submitFDRClosingForm">Transaction Account No is required</span>
									</td>
								</tr>
								<tr>
									<td colspan="6">&nbsp;</td>
								</tr>
								<tr>
									<th>Official Order No:</th>
									<td>
										<input type="text" class="form-control" name="official_order_no" id="official_order_no" ng-model="official_order_no" required placeholder="Enter Official Order No">
										<span class="error" ng-show="fdrCLoseForm.official_order_no.$invalid && submitFDRClosingForm">Official Order No is required</span>
									</td>
									<th style="padding-left: 15px;">Bank Charge:</th>
									<td>
										<input type="number" class="form-control" name="bank_chargefdrCLose" id="bank_chargefdrCLose" ng-model="bank_chargefdrCLose" required placeholder="Enter Bank Charge">
										<span class="error" ng-show="fdrCLoseForm.bank_chargefdrCLose.$invalid && submitFDRClosingForm">Bank Charge is required.</span>
									</td>
									<th style="padding-left: 15px;">VAT:</th>
									<td>
										<input type="number" class="form-control" name="vatfdrCLose" id="vatfdrCLose" ng-model="vatfdrCLose" placeholder="Type VAT in %">
										<span class="error" ng-show="fdrCLoseForm.vatfdrCLose.$invalid && submitFDRClosingForm">VAT is required.</span>
									</td>
								</tr>
								<tr>
									<td colspan="6">&nbsp;</td>
								</tr>
								<tr>
									<th>Total Closing Amount:</th>
									<td>
										<input type="number" class="form-control" name="total_closing_ammount" id="total_closing_ammount" ng-model="total_closing_ammount" ng-disabled="totalAmountVisible" required placeholder="Enter Total Closing Amount">
										<span class="error" ng-show="fdrCLoseForm.total_closing_ammount.$invalid && submitFDRClosingForm">Total Closing Amount is required</span>
									</td>
								</tr>
								<tr>
									<td colspan="6">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="6" class="text-center">
										<button type="button" class="btn btn-primary center-block" ng-click="SaveFdrCLose(fdrCLoseForm)" ng-if="btnSaveFdrCLose">
											<span class="fa fa-download"></span>Save
										</button>
										{{--ng-show="btnSave"--}}
										<button type="button" class="btn btn-success center-block" ng-click="UpdateFdrCLose(fdrCLoseForm)" ng-if="btnUpdateFdrCLose">
											<span class="fa fa-download"></span>Update
										</button>
										{{--ng-show="btnUpdate"--}}
										<span ng-if="dataLoadingFdrCLose">
											<img src="img/dataLoader.gif" width="250" height="15">
											<br> Please Wait !
										</span>
									</td>
								</tr>
							</table>
						</form>
                    </div>
                    <div class="modal-footer table-responsive">
                    	<span class="col-md-12 text-center" ng-if="FdrCLoseInfoLoader">
							<img src="img/dataLoader.gif" width="550" height="30" />
				            <br />Please Wait !
						</span>
                   		<table class="table table-bordered text-center" ng-if="showFdrCLose">
							<caption>
								<h4 class="text-center ok">Accounts Details</h4>
							</caption>
							<thead>
								<tr>
									<th>S/L No.</th>
									<th>Bank Name</th>
									<th>Payorde/Cheque/Payslip No</th>
									<th>Transaction Account No</th>
									<th>Official Order No</th>
									<th>Bank Charge</th>
									<th>VAT(%)</th>
									<th>Total Closing Amount</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr dir-paginate="FDRCLosing in allFDRCLosings | orderBy:'allFDRCLosing.id' | itemsPerPage : 5" pagination-id="FdrCLose">
									<td>@{{ $index+1 }}</td>
									<td>@{{ FDRCLosing.bank_name_and_address }}</td>
									<td>@{{ FDRCLosing.payorder_cheque_payslip_no }}</td>
									<td>@{{ FDRCLosing.transaction_acc_no }}</td>
									<td>@{{ FDRCLosing.official_order_no }}</td>
									<td class="amount-right">@{{ FDRCLosing.bank_charge | number }}</td>
									<td>@{{ FDRCLosing.vat | number }}</td>
									<td class="amount-right">@{{ FDRCLosing.total_closing_ammount | number }}</td>
									<td>
										<button type="button" class="btn btn-success btn-xs" ng-click="PressUpdateBtnFdrCLose(FDRCLosing)">Update</button>
									</td>
								</tr>
							</tbody>
							<tfoot>
			                    <tr>
			                        <td colspan="15" class="text-center">
			                            <dir-pagination-controls max-size="5"
			                                                 direction-links="true"
			                                                 boundary-links="true"
			                                                 pagination-id="FdrCLose">
			                            </dir-pagination-controls>
			                        </td>
			                    </tr>
			                </tfoot>
						</table>
						<button class="btn btn-warning center-block" data-dismiss="modal" ng-click="reLoadFDRAccounts()">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- ------------------------FDR CLOSE Model--------------------------}}
        {{-- <a class="btn btn-primary pull-left" href="{{url('/getTotalFundPostion')}}" target="_blank" style="margin-top: 2em;">Total Fund Position</a>
 --}}
	</div>
@endsection