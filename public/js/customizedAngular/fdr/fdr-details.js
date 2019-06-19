angular.module('FDRDetailsApp', ['angularUtils.directives.dirPagination','customServiceModule'])
	.controller('FDRDetailsCtrl', function($scope, $http){

		$scope.getAllBankDetails = function() {
			$http.get("/accounts/fdr/api/get-all-bank-details")
				.then(function(data){
					console.log(data)
					$scope.allbanks = data.data;
				}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
				}).finally(function(){

				})
		}
		$scope.getAllBankDetails();

		//FDR Details

		$scope.FDRDetailsSaveBtn = true;
		$scope.FDRDetailsUpdateBtn = false;

		$scope.validationFDRDetails = function(form) {
			if(form.$invalid) {
				$scope.submitFDRDetailsForm = true;
				return false;
			} else {
				$scope.submitFDRDetailsForm = false;
				return true;
			}
		}

		$scope.blankFDRDetailsForm = function() {
			$scope.bank_detail_id = null;
			//$scope.sl_no = null;
			$scope.fdr_no = null;
			$scope.fdr_account_id = null;
		}

		$scope.saveFDRDetails = function(FDRDetailsForm) {
			if($scope.validationFDRDetails(FDRDetailsForm) == false) {
				return false;
			}
			$scope.FDRDetailsdatLoading = true;
			var FDRDetailData = {
				bank_detail_id : $scope.bank_detail_id,
				//sl_no : $scope.sl_no,
				fdr_no : $scope.fdr_no
			}
			$http.post("/accounts/fdr/api/save-fdr-account-data",FDRDetailData)
				.then(function(response){
					console.log(response);
					$scope.savingSuccessFDRDetail = 'FDR Account successfully saved';
					$('#savingSuccessFDRDetail').show().delay(5000).slideUp(2000);
					$scope.blankFDRDetailsForm();
					$scope.getAllFDRaccounts();
				}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.savingErrorFDRDetail = 'Something Went Wrong';
					$('#savingErrorFDRDetail').show().delay(5000).slideUp(2000);
				}).finally(function() {
					$scope.FDRDetailsdatLoading = false;
				})
		}
		$scope.getAllFDRaccounts = function() {
			$scope.FDRAccountDetailsViewLoading = true;
			$http.get("/accounts/fdr/api/get-all-fdr-accounts-data")
				.then(function(data) {
					if(data.data.length > 0) {
						$scope.FDRAccountDetailsViewLoading = false;
						$scope.FDRAccountDetailsView = true;
						$scope.allaccounts = data.data;
						console.log($scope.allaccounts);
					} else {
						$scope.FDRAccountDetailsView = false;
					}
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
				}).finally(function() {
					$scope.FDRAccountDetailsViewLoading = false;
				})
		}
		$scope.getAllFDRaccounts();

		$scope.pressUpdateBtnFDRAccounts = function(account) {
			$scope.fdr_account_id = account.id;
			$scope.bank_detail_id = account.bank_detail_id;
			//$scope.sl_no = account.sl_no;
			$scope.fdr_no = account.fdr_no;
			$scope.FDRDetailsUpdateBtn = true;
			$scope.FDRDetailsSaveBtn = false;
		}

		$scope.updateFDRDetails = function(FDRDetailsForm) {
			if($scope.validationFDRDetails(FDRDetailsForm) == false) {
				return false;
			}
			$scope.FDRDetailsdatLoading = true;
			var FDRDetailData = {
				id : $scope.fdr_account_id,
				bank_detail_id : $scope.bank_detail_id,
				//sl_no : $scope.sl_no,
				fdr_no : $scope.fdr_no
			}
			$http.put("/accounts/fdr/api/update-fdr-account-data",FDRDetailData)
				.then(function(response){
					$scope.savingSuccessFDRDetail = 'FDR Account successfully updated';
					$('#savingSuccessFDRDetail').show().delay(5000).slideUp(2000);
					$scope.blankFDRDetailsForm();
					$scope.getAllFDRaccounts();
					$scope.FDRDetailsSaveBtn = true;
					$scope.FDRDetailsUpdateBtn = false;
				}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.savingErrorFDRDetail = 'Something Went Wrong';
					$('#savingErrorFDRDetail').show().delay(5000).slideUp(2000);
				}).finally(function() {
					$scope.FDRDetailsdatLoading = false;
				})

		}

		$scope.pressDeleteBtnFDRAccounts = function(account) {
			var fdrAccountId = account.id;
            var bank_name_and_address = account.bank_name_and_address;
            var fdr_no = account.fdr_no;
            bootbox.confirm({
                message: "Do you want to Delete FDR No <b>" + fdr_no + "</b> of <b>" + bank_name_and_address + "</b> bank?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    $scope.DeleteFDRAccount(result, fdr_no, fdrAccountId, bank_name_and_address);
                }
            }).css({
                    'text-align':'center',
                    'top':'0',
                    'bottom': '0',
                    'left': '0',
                    'right': '0',
                    'margin': 'auto'
            });
		}

		$scope.DeleteFDRAccount = function(result, fdr_no, fdrAccountId, bank_name_and_address) {
			if(result == true) {
                $http.delete("/accounts/fdr/api/delete-fdr-account-data/"+fdrAccountId)
                    .then(function(data){
                    	if(data.status == 202) {
                    		$scope.savingErrorFDRDetail = "FDR Account No '" + fdr_no +"' have Open/Reopen information, Delete them first";
                        	$("#savingErrorFDRDetail").show().delay(5000).slideUp(2000);
                        	return;
                    	}
                        $scope.savingSuccessFDRDetail = "FDR Account No '" + fdr_no +"' of " + bank_name_and_address + " bank successfully deleted";
                        $("#savingSuccessFDRDetail").show().delay(5000).slideUp(2000);
                        $scope.getAllFDRaccounts();
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingErrorFDRDetail = "Something went wrong";
                        $("#savingErrorFDRDetail").show().delay(5000).slideUp(2000);
                    }).finally(function(){

                    })
            } else {
                return false;
            }
		}

		$scope.btnReopenFDRAccout = function(account) {
			var fdrAccountId = account.id;
            var bank_name_and_address = account.bank_name_and_address;
            var fdr_no = account.fdr_no;
            bootbox.confirm({
                message: "Do you want to Re-Open FDR No <b>" + fdr_no + "</b> of <b>" + bank_name_and_address + "</b> bank?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    $scope.reopenFDRAccout(result, fdr_no, fdrAccountId, bank_name_and_address);
                }
            }).css({
                    'text-align':'center',
                    'top':'0',
                    'bottom': '0',
                    'left': '0',
                    'right': '0',
                    'margin': 'auto'
            });
		}

		$scope.reopenFDRAccout = function(result, fdr_no, fdrAccountId, bank_name_and_address) {
			if(result == true) {
                $http.get("/accounts/fdr/api/reopen-fdr-account/"+fdrAccountId)
                    .then(function(data){
                        $scope.savingSuccessFDRDetail = "FDR Account No '" + fdr_no +"' of " + bank_name_and_address + " bank successfully Re-Opened";
                        $("#savingSuccessFDRDetail").show().delay(5000).slideUp(2000);
                        $scope.getAllFDRaccounts();
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingErrorFDRDetail = "Something went wrong";
                        $("#savingErrorFDRDetail").show().delay(5000).slideUp(2000);
                    }).finally(function(){

                    })
            } else {
                return false;
            }
		}

		$scope.ClassRemover = function(className,index) {
			if($('#' + index).hasClass(className)) 
				$("#" + index).removeClass(className);
		}

		$scope.addClassParcentage = function(parcentage, index) {
			//console.log(parcentage);
			if(parcentage >= 0 && parcentage <= 25.9) {
				$scope.ClassRemover('progress-bar-info', index);
				$scope.ClassRemover('progress-bar-danger', index);
				$scope.ClassRemover('progress-bar-warning', index);
				$("#"+ index).addClass('progress-bar-success');
            } else if (parcentage >= 26 && parcentage <= 50.9) {
            	$scope.ClassRemover('progress-bar-success', index);
            	$scope.ClassRemover('progress-bar-danger', index);
            	$scope.ClassRemover('progress-bar-warning', index);
            	$("#"+ index).addClass('progress-bar-info');
            	//console.log(parcentage);
            } else if(parcentage >= 51 && parcentage <= 75.9) {
            	$scope.ClassRemover('progress-bar-success', index);
            	$scope.ClassRemover('progress-bar-danger', index);
            	$scope.ClassRemover('progress-bar-info', index);
            	$("#"+ index).addClass('progress-bar-warning');
            } else {
            	$scope.ClassRemover('progress-bar-success');
            	$scope.ClassRemover('progress-bar-warning');
            	$scope.ClassRemover('progress-bar-info');
            	$("#"+ index).addClass('progress-bar-danger');
            }
            //console.log(parcentage)
            //console.log($("#" +index).attr('class'));

		}

		$scope.getProgressbarValue = function(diff_from_today, total_day_difference , flag, index) {
			if(isNumeric(diff_from_today) && isNumeric(total_day_difference)) {
				if(diff_from_today > 0) {
					var parcentage = ((diff_from_today/total_day_difference)*100).toFixed(2);
					if(flag == 1) {
						$scope.addClassParcentage(parcentage, index);
					}
					
					return parcentage+"%";
				} else {
					return ;
				}
			} else {
				return false;
			}
		}

		//FDR Details
		//Bank
		$scope.BankSaveBtn = true;
		$scope.BankUpdateBtn = false;
		$scope.bankTypes = [
	      {value: 0, text:'Public'},
	      {value: 1, text:'Private'},
	    ];

	    $scope.validationBankForm = function(bankForm) {
	    	if(bankForm.$invalid) {
	    		$scope.submitBankForm = true;
	    		return false;
	    	} else {
	    		$scope.submitBankForm = false;
	    		return true;
	    	}
	    }

	    $scope.blankBankForm = function() {
	    	$scope.name_and_address = null;
	    	$scope.type = null;
	    	$scope.bankId = null;
	    }

	    $scope.saveBank = function(bankForm) {
	    	if($scope.validationBankForm(bankForm) == false) {
	    		return;
	    	}
	    	$scope.BankDetailsdatLoading = true;
	    	var bankData = {
	    		name_and_address : $scope.name_and_address,
	    		type : $scope.type
	    	}
	    	$http.post("/accounts/fdr/bank/api/save-bank-details",bankData)
	    		.then(function(response) {
	    			$scope.savingSuccessBank = 'Bank successfully saved';
	    			$('#savingSuccessBank').show().delay(5000).slideUp(2000);
	    			$scope.getAllBankDetails();
	    			$scope.blankBankForm();
	    		}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
	    			$scope.savingErrorBank = 'Something went wrong';
	    			$('#savingErrorBank').show().delay(5000).slideUp(2000);
	    		}).finally(function() {
	    			$scope.BankDetailsdatLoading = false;
	    		})
	    }

	    $scope.pressBankdetailUpdateBtn = function(bank) {
	    	$scope.bankId = bank.id;
	    	$scope.name_and_address = bank.name_and_address;
	    	$scope.type = bank.type;
	    	$scope.BankUpdateBtn = true;
	    	$scope.BankSaveBtn = false;
	    }

	    $scope.updateBank = function(bankForm) {
	    	if($scope.validationBankForm(bankForm) == false) {
	    		return;
	    	}
	    	$scope.BankDetailsdatLoading = true;
	    	var bankData = {
	    		id : $scope.bankId,
	    		name_and_address : $scope.name_and_address,
	    		type : $scope.type
	    	}
	    	$http.put("/accounts/fdr/bank/api/update-bank-details",bankData)
	    		.then(function(response) {
	    			$scope.savingSuccessBank = 'Bank successfully updated';
	    			$('#savingSuccessBank').show().delay(5000).slideUp(2000);
	    			$scope.BankSaveBtn = true;
	    			$scope.BankUpdateBtn = false;
	    			$scope.getAllBankDetails();
	    			$scope.blankBankForm();
	    		}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
	    			$scope.savingErrorBank = 'Something went wrong';
	    			$('#savingErrorBank').show().delay(5000).slideUp(2000);
	    		}).finally(function() {
	    			$scope.BankDetailsdatLoading = false;
	    		})
	    }

	    $scope.pressBankdetailDeleteBtn = function(bank) {
	    	var bankId = bank.id;
            var bankNameAndAddress = bank.name_and_address;
            bootbox.confirm({
                message: "Do you want to Delete <b>" + bankNameAndAddress + "</b> Bank?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    },
                },
                callback: function (result) {
                    $scope.DeleteBankDetail(result, bankId, bankNameAndAddress);
                }
            }).css({
                    'text-align':'center',
                    'top':'0',
                    'bottom': '0',
                    'left': '0',
                    'right': '0',
                    'margin': 'auto'
            });
	    }

	    $scope.DeleteBankDetail = function(result, bankId, bankNameAndAddress) {
	    	if(result == true) {
                $http.delete("/accounts/fdr/bank/api/delete-bank-details/"+bankId)
                    .then(function(data){
                    	if(data.status == 202) {
                    		$scope.savingErrorBank = bankNameAndAddress + " used in FDR accounts, Delete them first";
                        	$("#savingErrorBank").show().delay(5000).slideUp(2000);
                        	return;
                    	}
                        $scope.savingSuccessBank = "'" + bankNameAndAddress + "' bank successfully deleted";
                        $("#savingSuccessBank").show().delay(5000).slideUp(2000);
                        $scope.getAllBankDetails();
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingErrorBank = "Something went wrong";
                        $("#savingErrorBank").show().delay(5000).slideUp(2000);
                    }).finally(function(){

                    })
            } else {
                return false;
            }
	    }
	    //FDR OPEN OR RENEW
	    $scope.btnSavefdrOpenOrRenew = true;
	    $scope.btnUpdatefdrOpenOrRenew = false;
	    function isNumeric(n) {
		  return !isNaN(parseFloat(n)) && isFinite(n);
		}

	    $scope.CountExpireDate = function() {
			if($scope.opening_dtfdrOpenOrRenew != null && $scope.durationfdrOpenOrRenew != null) {
				var op_dt = new Date($scope.opening_dtfdrOpenOrRenew);
				var du = $scope.durationfdrOpenOrRenew;
				// var e_year = op_dt.getFullYear()+2;
				// var e_month = op_dt.getMonth();
				// var e_date = op_dt.getDate();
				var ex_dt = new Date($scope.opening_dtfdrOpenOrRenew);
				//ex_dt.setFullYear(op_dt.getFullYear() + du);
				ex_dt.setMonth(op_dt.getMonth() + du);
				//console.log(formatedDate);
				$('#expire_dtfdrOpenOrRenew').datepicker("setDate", new Date(ex_dt)).trigger('input');
				$scope.disableExpireDate = true;
			} else {
				$('#expire_dtfdrOpenOrRenew').val(null);
				$scope.disableExpireDate = false;
			}
		}

		$scope.GetTotalInterest = function() {
			if(isNumeric($scope.main_amountfdrOpenOrRenew) && isNumeric($scope.durationfdrOpenOrRenew) && isNumeric($scope.interest_ratefdrOpenOrRenew)) {
				$scope.total_interestfdrOpenOrRenew = parseFloat((($scope.main_amountfdrOpenOrRenew*($scope.durationfdrOpenOrRenew/12)*$scope.interest_ratefdrOpenOrRenew)/100).toFixed(2));
				//$scope.disableInterestRate = true;
			} else {
				$scope.total_interestfdrOpenOrRenew = null;
				//$scope.disableInterestRate = false;
			}
		}

		$scope.$watch('total_interestfdrOpenOrRenew', function(){
			$scope.GetIncomeTax();
		});

		$scope.GetIncomeTax = function() {
			if(isNumeric($scope.total_interestfdrOpenOrRenew)) {
				$scope.income_taxfdrOpenOrRenew = parseFloat((($scope.total_interestfdrOpenOrRenew*10)/100).toFixed(2));
				//$scope.disableIncomeTax = true;
			} else {
				$scope.income_taxfdrOpenOrRenew = null;
				//$scope.disableIncomeTax = false;
			}
		}

		$scope.$watchGroup(['total_interestfdrOpenOrRenew', 'income_taxfdrOpenOrRenew', 'excavator_tarifffdrOpenOrRenew','bank_chargefdrOpenOrRenew','vatfdrOpenOrRenew'], function(){
			$scope.GetNetInterest();
		});

		$scope.GetNetInterest = function() {
			if(isNumeric($scope.total_interestfdrOpenOrRenew) && isNumeric($scope.income_taxfdrOpenOrRenew)) {
				$scope.net_interestfdrOpenOrRenew = parseFloat(($scope.total_interestfdrOpenOrRenew - $scope.income_taxfdrOpenOrRenew).toFixed(2));
				if(isNumeric($scope.excavator_tarifffdrOpenOrRenew)) {
					$scope.net_interestfdrOpenOrRenew -=  parseFloat(($scope.excavator_tarifffdrOpenOrRenew).toFixed(2));
				}
				if(isNumeric($scope.bank_chargefdrOpenOrRenew)) {
					$scope.net_interestfdrOpenOrRenew -= parseFloat(($scope.bank_chargefdrOpenOrRenew).toFixed(2));
				} 
				if(isNumeric($scope.vatfdrOpenOrRenew) && isNumeric($scope.bank_chargefdrOpenOrRenew)) {
					
					$scope.vatInAmmount = ($scope.bank_chargefdrOpenOrRenew*$scope.vatfdrOpenOrRenew)/100;
					$scope.net_interestfdrOpenOrRenew -= parseFloat(($scope.vatInAmmount).toFixed(2));
				}
				$scope.disableNetInterest = true;
			} else {
				$scope.net_interestfdrOpenOrRenew = null;
				$scope.disableNetInterest = false;
			}
		}

		// $scope.$watch('net_interest', function(){
		// 	$scope.GetTotalBalance();
		// });

		$scope.$watch('net_interestfdrOpenOrRenew', function(){
			$scope.GetTotalBalance();
		});

		$scope.GetTotalBalance = function() {
			if(isNumeric($scope.net_interestfdrOpenOrRenew) && isNumeric($scope.main_amountfdrOpenOrRenew)) {
				$scope.total_balancefdrOpenOrRenew = parseFloat(($scope.main_amountfdrOpenOrRenew + $scope.net_interestfdrOpenOrRenew).toFixed(2));
				$scope.disableTotalBalance = true;
			} else {
				$scope.total_balancefdrOpenOrRenew = null;
				$scope.disableTotalBalance = false;
			}
		}

		$scope.showFDROpenningOrRenew = false;
		$scope.getFDROpenOrRenew = function(fdr_account_id) {
			$scope.showFDROpenningOrRenewLoading = true;
			$http.get("/accounts/fdr/open-or-renew/api/get-fdr-open-or-renew/"+fdr_account_id)
				.then(function(data) {
					if(data.data.length>0) {
						$scope.showFDROpenningOrRenew = true;
						$scope.allFDROpenningOrRenew = data.data;
						console.log($scope.allFDROpenningOrRenew);
						$scope.main_amountfdrOpenOrRenew = parseFloat(data.data[data.data.length-1].total_balance);
					} else {
						$scope.showFDROpenningOrRenew = false;
					}
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
				}).finally(function() {
					$scope.showFDROpenningOrRenewLoading = false;
				})
		}

		$scope.pressBtnFDROpenOrRenew = function(account) {
	    	$scope.showFdrNo = account.fdr_no;
	    	$scope.showBankName = account.bank_name_and_address;
	    	$scope.showfdrSlNo = account.sl_no;
	    	$scope.fdrAccountIdForFDRActions = account.id;
	    	$scope.getFDROpenOrRenew(account.id);
	    }

		$scope.validationFdrOpenOrRenew = function(fdrOpenOrRenewForm) {
			if(fdrOpenOrRenewForm.$invalid) {
				$scope.submittedFdrOpenOrRenew = true;
				return false;
			} else {
				$scope.submittedFdrOpenOrRenew = false;
				return true;
			}
		}

		$scope.BlankFDROpenningOrRenew = function() {
			$scope.main_amountfdrOpenOrRenew = null;
			$scope.opening_dtfdrOpenOrRenew = null;
			$scope.durationfdrOpenOrRenew = null;
			$scope.expire_dtfdrOpenOrRenew = null;
			$scope.interest_ratefdrOpenOrRenew = null;
			$scope.total_interestfdrOpenOrRenew = null;
			$scope.income_taxfdrOpenOrRenew = null;
			$scope.excavator_tarifffdrOpenOrRenew = null;
			$scope.net_interestfdrOpenOrRenew = null;
			$scope.bank_chargefdrOpenOrRenew = null;
			$scope.vatfdrOpenOrRenew = null;
			$scope.total_balancefdrOpenOrRenew = null;
			$scope.commentsfdrOpenOrRenew = null;
			$scope.FDROpenningOrRenewId = null;
		}

		$scope.SaveFdrOpenOrRenew = function(fdrOpenOrRenewForm) {
			if($scope.validationFdrOpenOrRenew(fdrOpenOrRenewForm) == false) {
				return;
			}
			$scope.dataLoadingfdrOpenOrRenew = true;
			var fdrOpenOrRenewData = {
				fdr_account_id : $scope.fdrAccountIdForFDRActions,
				fdr_account_sl_no : $scope.showfdrSlNo,
				main_amount: $scope.main_amountfdrOpenOrRenew ,
				opening_date: $scope.opening_dtfdrOpenOrRenew ,
				duration : $scope.durationfdrOpenOrRenew ,
				expire_date : $scope.expire_dtfdrOpenOrRenew ,
				interest_rate : $scope.interest_ratefdrOpenOrRenew ,
				total_interest : $scope.total_interestfdrOpenOrRenew ,
				income_tax : $scope.income_taxfdrOpenOrRenew ,
				excavator_tariff : $scope.excavator_tarifffdrOpenOrRenew ,
				net_interest : $scope.net_interestfdrOpenOrRenew ,
				bank_charge : $scope.bank_chargefdrOpenOrRenew ,
				vat : $scope.vatfdrOpenOrRenew ,
				total_balance : $scope.total_balancefdrOpenOrRenew ,
				comments : $scope.commentsfdrOpenOrRenew
			}
			$http.post("/accounts/fdr/open-or-renew/api/save-fdr-open-or-renew-data",fdrOpenOrRenewData)
				.then(function(response) {
					$scope.getFDROpenOrRenew($scope.fdrAccountIdForFDRActions);
					$scope.savingSuccessfdrOpenOrRenew = 'Successfully saved';
					$("#savingSuccessfdrOpenOrRenew").show().delay(5000).slideUp(2000);
					$scope.BlankFDROpenningOrRenew();
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.savingErrorfdrOpenOrRenew = 'Something went wrong';
					$("#savingErrorfdrOpenOrRenew").show().delay(5000).slideUp(2000);
				}).finally(function() {
					$scope.dataLoadingfdrOpenOrRenew = false;
				})
		}

		$scope.PressUpdateBtnFDROpenningOrRenew = function(FDROpenningOrRenew) {
			$scope.FDROpenningOrRenewId = FDROpenningOrRenew.id;
			$scope.main_amountfdrOpenOrRenew = parseFloat(FDROpenningOrRenew.main_amount);
			$scope.opening_dtfdrOpenOrRenew = 	FDROpenningOrRenew.opening_date;
			$scope.durationfdrOpenOrRenew = parseFloat(FDROpenningOrRenew.duration);
			$scope.expire_dtfdrOpenOrRenew = FDROpenningOrRenew.expire_date;
			$scope.interest_ratefdrOpenOrRenew = parseFloat(FDROpenningOrRenew.interest_rate);
			$scope.total_interestfdrOpenOrRenew = parseFloat(FDROpenningOrRenew.total_interest);
			$scope.income_taxfdrOpenOrRenew = parseFloat(FDROpenningOrRenew.income_tax);
			$scope.excavator_tarifffdrOpenOrRenew = parseFloat(FDROpenningOrRenew.excavator_tariff);
			$scope.net_interestfdrOpenOrRenew = parseFloat(FDROpenningOrRenew.net_interest);
			$scope.bank_chargefdrOpenOrRenew = parseFloat(FDROpenningOrRenew.bank_charge);
			$scope.vatfdrOpenOrRenew = parseFloat(FDROpenningOrRenew.vat);
			$scope.total_balancefdrOpenOrRenew = parseFloat(FDROpenningOrRenew.total_balance);
			$scope.commentsfdrOpenOrRenew = FDROpenningOrRenew.comments;
			$scope.btnUpdatefdrOpenOrRenew = true;
			$scope.btnSavefdrOpenOrRenew = false;
	    	
		}

		$scope.UpdateFdrOpenOrRenew = function(fdrOpenOrRenewForm) {
			if($scope.validationFdrOpenOrRenew(fdrOpenOrRenewForm) == false) {
				return;
			}
			$scope.dataLoadingfdrOpenOrRenew = true;
			var fdrOpenOrRenewData = {
				id : $scope.FDROpenningOrRenewId,
				main_amount: $scope.main_amountfdrOpenOrRenew ,
				opening_date: $scope.opening_dtfdrOpenOrRenew ,
				duration : $scope.durationfdrOpenOrRenew ,
				expire_date : $scope.expire_dtfdrOpenOrRenew ,
				interest_rate : $scope.interest_ratefdrOpenOrRenew ,
				total_interest : $scope.total_interestfdrOpenOrRenew ,
				income_tax : $scope.income_taxfdrOpenOrRenew ,
				excavator_tariff : $scope.excavator_tarifffdrOpenOrRenew ,
				net_interest : $scope.net_interestfdrOpenOrRenew ,
				bank_charge : $scope.bank_chargefdrOpenOrRenew ,
				vat : $scope.vatfdrOpenOrRenew ,
				total_balance : $scope.total_balancefdrOpenOrRenew ,
				comments : $scope.commentsfdrOpenOrRenew
			}
			$http.put("/accounts/fdr/open-or-renew/api/update-fdr-open-or-renew-data",fdrOpenOrRenewData)
				.then(function(response) {
					$scope.getFDROpenOrRenew($scope.fdrAccountIdForFDRActions);
					$scope.savingSuccessfdrOpenOrRenew = 'Successfully updated';
					$("#savingSuccessfdrOpenOrRenew").show().delay(5000).slideUp(2000);
					$scope.BlankFDROpenningOrRenew();
					$scope.btnSavefdrOpenOrRenew = true;
					$scope.btnUpdatefdrOpenOrRenew = false;
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.savingErrorfdrOpenOrRenew = 'Something went wrong';
					$("#savingErrorfdrOpenOrRenew").show().delay(5000).slideUp(2000);
				}).finally(function() {
					$scope.dataLoadingfdrOpenOrRenew = false;
				})
		}

		$scope.PressDeleteBtnFDROpenningOrRenew = function(FDROpenningOrRenew) {
			var FDROpenningOrRenewId = FDROpenningOrRenew.id;
            var FDROpenningOrRenewSlNo = FDROpenningOrRenew.sl_no;
            bootbox.confirm({
                message: "Do you want to Delete <b>" + FDROpenningOrRenewSlNo + "</b> ?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    $scope.DeleteFDROpenningOrRenew(result, FDROpenningOrRenewId, FDROpenningOrRenewSlNo);
                }
            }).css({
                    'text-align':'center',
                    'top':'0',
                    'bottom': '0',
                    'left': '0',
                    'right': '0',
                    'margin': 'auto'
            });
		}

		$scope.DeleteFDROpenningOrRenew = function(result, FDROpenningOrRenewId, FDROpenningOrRenewSlNo) {
			if(result == true) {
                $http.delete("/accounts/fdr/open-or-renew/api/delete-fdr-openning-or-renew/"+FDROpenningOrRenewId)
                    .then(function(data){
                        $scope.savingSuccessfdrOpenOrRenew = "'" + FDROpenningOrRenewSlNo + "' successfully deleted";
                        $("#savingSuccessfdrOpenOrRenew").show().delay(5000).slideUp(2000);
                        $scope.getFDROpenOrRenew($scope.fdrAccountIdForFDRActions);
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingErrorfdrOpenOrRenew = "Something went wrong";
                        $("#savingErrorfdrOpenOrRenew").show().delay(5000).slideUp(2000);
                    }).finally(function(){

                    })
            } else {
                return false;
            }
		}

		$scope.reLoadFDRAccounts = function() {
			$scope.getAllFDRaccounts();
		}

		//FDRCLose
		$scope.btnSaveFdrCLose = true;
		$scope.btnUpdateFdrCLose = false;
		$scope.pressBtnFDRClose = function(account) {
	    	$scope.showFdrNo = account.fdr_no;
	    	$scope.showBankName = account.bank_name_and_address;
	    	$scope.showfdrSlNo = account.sl_no;
	    	$scope.fdrAccountIdForFDRCLose = account.id;
	    	$scope.getTotalAmmountForFDRClose(account.id);
	    	$scope.getFdrCLose(account.id);
	    }

	    $scope.getTotalAmmountForFDRClose = function(account_id) {
	    	$scope.FdrCLoseInfoLoader = true;
	    	$http.get("/accounts/fdr/close/api/get-total-ammount-for-fdr-close/"+account_id)
	    		.then(function(data){
	    			if(data.data.length>0) {
	    				$scope.totalBalanceForFDRCLose = data.data[0].total_balance;
	    				$scope.expireDateForFDRClose = data.data[0].expire_date;
	    				$scope.total_closing_ammount = parseFloat(data.data[0].total_balance);
	    				//$scope.totalAmountVisible = true;
	    			}

	    		}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
	    		}).finally(function() {
	    			$scope.FdrCLoseInfoLoader = false;
	    		})
	    }

	    $scope.$watch('bank_chargefdrCLose', function(){
			$scope.GetTotalClosingAmountForBankCharge();
		});

		$scope.GetTotalClosingAmountForBankCharge = function() {
			if(isNumeric($scope.bank_chargefdrCLose)) {
				$scope.total_closing_ammount = parseFloat(($scope.totalBalanceForFDRCLose - $scope.bank_chargefdrCLose).toFixed(2));
			} else {
				$scope.total_closing_ammount = parseFloat($scope.totalBalanceForFDRCLose);
			}
			$scope.total_closing_ammount_backup = parseFloat(($scope.total_closing_ammount).toFixed(2));
		}

		$scope.$watch('vatfdrCLose', function(){
			$scope.GetTotalClosingAmountForVat();
		});

		$scope.GetTotalClosingAmountForVat = function() {
			if(isNumeric($scope.bank_chargefdrCLose) && isNumeric($scope.vatfdrCLose)) {
				$scope.vatInAmountforFDRClose = ($scope.vatfdrCLose*$scope.bank_chargefdrCLose)/100;
				$scope.total_closing_ammount = parseFloat(($scope.total_closing_ammount_backup - $scope.vatInAmountforFDRClose).toFixed(2));
			}
		}

		$scope.getFdrCLose = function(fdrAccountIdForFDRCLose) {
			$http.get('/accounts/fdr/close/api/get-fdr-close/'+fdrAccountIdForFDRCLose)
				.then(function(data) {
					if(data.data.length>0) {
						//console.log(data.data);
						$scope.showFdrCLose = true;
						$scope.allFDRCLosings = data.data;
					} else {
						$scope.showFdrCLose = false;
					}
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
				}).finally(function() {

				})
		}

		$scope.validationFDRCloseForm = function(fdrCLoseForm) {
			if(fdrCLoseForm.$invalid) {
				$scope.submitFDRClosingForm = true;
				return false;
			} else {
				$scope.submitFDRClosingForm = false;
				return true;
			}
		}

		$scope.BLankFDRCLose = function() {
			$scope.bank_detail_id_for_fdr_closing = null;
			$scope.payorder_cheque_payslip_no = null;
			$scope.transaction_acc_no = null;
			$scope.official_order_no = null;
			$scope.bank_chargefdrCLose = null;
			$scope.vatfdrCLose = null;
			$scope.total_closing_ammount = null;
			$scope.fdrCloseID = null;
		}

		$scope.SaveFdrCLose = function(fdrCLoseForm) {
			if($scope.validationFDRCloseForm(fdrCLoseForm) == false) {
				return;
			}
			$scope.dataLoadingFdrCLose = true;
			var fdrCLoseData = {
				fdr_account_id : $scope.fdrAccountIdForFDRCLose,
				bank_detail_id : $scope.bank_detail_id_for_fdr_closing,
				payorder_cheque_payslip_no : $scope.payorder_cheque_payslip_no,
				transaction_acc_no : $scope.transaction_acc_no,
				official_order_no : $scope.official_order_no,
				bank_charge : $scope.bank_chargefdrCLose,
				vat : $scope.vatfdrCLose,
				total_closing_ammount : $scope.total_closing_ammount
			}
			$http.post("/accounts/fdr/close/api/save-fdr-close",fdrCLoseData)
				.then(function(response) {
					$scope.BLankFDRCLose();
					$scope.savingSuccessfdrClose = 'Successfully closed';
					$("#savingSuccessfdrClose").show().delay(5000).slideUp(2000);
					$scope.getFdrCLose($scope.fdrAccountIdForFDRCLose);
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.savingErrorfdrClose = 'Something went wrong';
					$("#savingErrorfdrClose").show().delay(5000).slideUp(2000);
				}).finally(function(){
					$scope.dataLoadingFdrCLose = false;
				})
			
		}

		$scope.PressUpdateBtnFdrCLose = function(FDRCLosing) {
			$scope.fdrCloseID = FDRCLosing.id;
			$scope.bank_detail_id_for_fdr_closing = FDRCLosing.bank_detail_id;
			$scope.payorder_cheque_payslip_no = FDRCLosing.payorder_cheque_payslip_no;
			$scope.transaction_acc_no = FDRCLosing.transaction_acc_no;
			$scope.official_order_no = FDRCLosing.official_order_no;
			$scope.total_closing_ammount = null;
			$scope.total_closing_ammount = parseFloat(FDRCLosing.total_closing_ammount);
			$scope.bank_chargefdrCLose = parseFloat(FDRCLosing.bank_charge);
			$scope.vatfdrCLose = parseFloat(FDRCLosing.vat);
			$scope.btnUpdateFdrCLose = true;
			$scope.btnSaveFdrCLose = false;
		}

		$scope.UpdateFdrCLose = function(fdrCLoseForm) {
			if($scope.validationFDRCloseForm(fdrCLoseForm) == false) {
				return;
			}
			$scope.dataLoadingFdrCLose = true;
			var fdrCLoseData = {
				id : $scope.fdrCloseID,
				fdr_account_id : $scope.fdrAccountIdForFDRCLose,
				bank_detail_id : $scope.bank_detail_id_for_fdr_closing,
				payorder_cheque_payslip_no : $scope.payorder_cheque_payslip_no,
				transaction_acc_no : $scope.transaction_acc_no,
				official_order_no : $scope.official_order_no,
				bank_charge : $scope.bank_chargefdrCLose,
				vat : $scope.vatfdrCLose,
				total_closing_ammount : $scope.total_closing_ammount
			}
			$http.put("/accounts/fdr/close/api/update-fdr-close",fdrCLoseData)
				.then(function(response) {
					$scope.BLankFDRCLose();
					$scope.savingSuccessfdrClose = 'Successfully updated';
					$("#savingSuccessfdrClose").show().delay(5000).slideUp(2000);
					$scope.getFdrCLose($scope.fdrAccountIdForFDRCLose);
					$scope.btnSaveFdrCLose = true;
					$scope.btnUpdateFdrCLose = false;
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.savingErrorfdrClose = 'Something went wrong';
					$("#savingErrorfdrClose").show().delay(5000).slideUp(2000);
				}).finally(function(){
					$scope.dataLoadingFdrCLose = false;
				})
		}
 
	}).filter('bankTypeFilter', function () {
        return function (val) {
            var type;
            if(val==1){
               return type='Private';
            } else if(val==0) {
                return type='Public';
            }
            return type='';
        }
    });