angular.module('FDROpenningApp', ['angularUtils.directives.dirPagination','customServiceModule'])
	.controller('FDROpenningController', function($scope, $http,enterKeyService){
		$scope.btnSave = true;
		$scope.btnUpdate = false;

		$scope.Blank = function() {
			$scope.id = null;
			$scope.sl_no = null;
			$scope.bank_name = null;
			$scope.fdr_no = null;
			$scope.main_amount = null;
			$scope.opening_dt = null;
			$scope.duration = null;
			$scope.expire_dt = null;
			$scope.interest_rate = null;
			$scope.income_tax = null;
			$scope.excavator_tariff = null;
			$scope.net_interest = null;
			$scope.total_with_interest = null;
			$scope.comments = null;
			$scope.total_interest = null;
			$scope.disableExpireDate = false;
			$scope.disableInterestRate = false;
			//$scope.disableInterestRate = false;
			//$scope.disableIncomeTax = false;
			$scope.disableNetInterest = false;
			$scope.disableTotalWithInterest = false;
		}

		$scope.Validation = function() {
			if($scope.FDROpenningForm.$invalid) {
				$scope.submitted = true;
				return false;
			} else {
				$scope.submitted = false;
				return true;			
			}
		}

        enterKeyService.enterKey('#FDROpenningForm input ,#FDROpenningForm button')

		$scope.Save = function() {
			if($scope.Validation()== false) {
				return;
			}
			$scope.dataLoading = true;
			var data = {
				sl_no : $scope.sl_no,
				bank_name : $scope.bank_name,
				fdr_no : $scope.fdr_no,
				main_amount : $scope.main_amount,
				opening_dt : $scope.opening_dt,
				duration : $scope.duration,
				expire_dt : $scope.expire_dt,
				interest_rate : $scope.interest_rate,
				total_interest : $scope.total_interest,
				income_tax : $scope.income_tax,
				excavator_tariff : $scope.excavator_tariff,
				net_interest : $scope.net_interest,
				total_with_interest : $scope.total_with_interest,
				comments : $scope.comments
			}

			$http.post("/api/postFDRDetails",data)
				.then(function(data) {
					$scope.savingSuccess = 'Successfully Saved.';
					$('#savingSuccess').show().delay(5000).slideUp(1000);
					$scope.Blank();
					$scope.getFDRDetails();
				}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.savingError = 'Something went wrong.';
					$('#savingError').show().delay(5000).slideUp(1000);
				}).finally(function(){
					$scope.dataLoading = false;
				})
		}

		$scope.getFDRDetails = function() {
			$http.get("/api/getFDRDetails")
				.then(function(data){
					if(data.data.length>0) {
						$scope.showFDRDetails = true;
						$scope.allFDR = data.data;
					} else {
						$scope.showFDRDetails = false;
					}
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
		$scope.getFDRDetails();

		$scope.PressUpdateBtn = function(fdr) {
			$scope.btnSave = false;
			$scope.btnUpdate = true;
			$scope.id = fdr.id;
			$scope.selectedStyle = fdr.id;
			$scope.sl_no = fdr.sl_no;
			$scope.bank_name = fdr.bank_name;
			$scope.fdr_no = fdr.fdr_no;
			$scope.main_amount = parseFloat(fdr.main_amount);
			$scope.opening_dt = fdr.opening_dt;
			$scope.duration = parseFloat(fdr.duration);
			$scope.expire_dt = fdr.expire_dt;
			$scope.interest_rate = parseFloat(fdr.interest_rate);
			$scope.total_interest = parseFloat(fdr.total_interest);
			$scope.income_tax = parseFloat(fdr.income_tax);
			$scope.excavator_tariff = parseFloat(fdr.excavator_tariff);
			$scope.net_interest = parseFloat(fdr.net_interest);
			$scope.total_with_interest = parseFloat(fdr.total_with_interest);
			$scope.comments = fdr.comments;
		}

		$scope.Update = function() {
			if($scope.Validation() == false) {
				return;
			}
			$scope.dataLoading = true;
			var data = {
				id : $scope.id,
				sl_no : $scope.sl_no,
				bank_name : $scope.bank_name,
				fdr_no : $scope.fdr_no,
				main_amount : $scope.main_amount,
				opening_dt : $scope.opening_dt,
				duration : $scope.duration,
				expire_dt : $scope.expire_dt,
				interest_rate : $scope.interest_rate,
				total_interest : $scope.total_interest,
				income_tax : $scope.income_tax,
				excavator_tariff : $scope.excavator_tariff != null ? $scope.excavator_tariff : 0,
				net_interest : $scope.net_interest,
				total_with_interest : $scope.total_with_interest,
				comments : $scope.comments
			}

			$http.put("/api/updateFDRDetails",data)
				.then(function(data){
					$scope.savingSuccess = 'Successfully Updated.';
					$('#savingSuccess').show().delay(5000).slideUp(1000, function(){
						$scope.selectedStyle = 0;
					});
					$scope.Blank();
					$scope.getFDRDetails();
					$scope.btnSave = true;
					$scope.btnUpdate = false;
				}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.savingError = 'Something went wrong.';
					$('#savingError').show().delay(5000).slideUp(1000, function(){
						$scope.selectedStyle = 0;
					});
				}).finally(function(){
					$scope.dataLoading = false;
				})
		}

		$scope.PressDeleteBtn = function(fdr) {
			$scope.selectedStyle = fdr.id;
			var id = fdr.id;
            bootbox.confirm({
                message: "Do you want to Delete this FDR Account?",
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
                    $scope.deleteFDR(result, id);
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

		$scope.deleteFDR = function(result, id) {
			if(result == true) {
                $http.delete("/api/deleteFDR/"+id)
                    .then(function(data){
                        $scope.savingSuccess = "FDR Account Deleted Successfully.";
                        $("#savingSuccess").show().delay(5000).slideUp(1000, function(){
                        	$scope.selectedStyle = 0;
                        });
                        $scope.getFDRDetails();
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingError = "Something Went Wrong.";
                        $("#savingError").show().delay(5000).slideUp(1000, function(){
                        	$scope.selectedStyle = 0;
                        });
                    }).finally(function(){

                    })
            } else {
                return false;
            }
		}

		$scope.CountExpireDate = function() {
			if($scope.opening_dt != null && $scope.duration != null) {
				var op_dt = new Date($scope.opening_dt);
				var du = $scope.duration;
				// var e_year = op_dt.getFullYear()+2;
				// var e_month = op_dt.getMonth();
				// var e_date = op_dt.getDate();
				var ex_dt = new Date($scope.opening_dt);
				ex_dt.setFullYear(op_dt.getFullYear() + du);
				$('#expire_dt').datepicker("setDate", new Date(ex_dt)).trigger('input');
				$scope.disableExpireDate = true;
			} else {
				$('#expire_dt').val(null);
				$scope.disableExpireDate = false;
			}
		}

		$scope.GetTotalInterest = function() {
			if($scope.main_amount != null && $scope.duration != null && $scope.interest_rate != null) {
				var year = $scope.duration/12;
				console.log(year);
				$scope.total_interest = ($scope.main_amount*($scope.duration/12)*$scope.interest_rate)/100;
				//$scope.disableInterestRate = true;
			} else {
				$scope.total_interest = null;
				//$scope.disableInterestRate = false;
			}
		}

		$scope.$watch('total_interest', function(){
			$scope.GetIncomeTax();
		});

		$scope.GetIncomeTax = function() {
			if($scope.total_interest != null) {
				$scope.income_tax = ($scope.total_interest*10)/100;
				//$scope.disableIncomeTax = true;
			} else {
				$scope.income_tax = null;
				//$scope.disableIncomeTax = false;
			}
		}

		$scope.$watchGroup(['total_interest', 'income_tax', 'excavator_tariff'], function(){
			$scope.GetNetInterest();
		});

		$scope.GetNetInterest = function() {
			if($scope.total_interest != null && $scope.income_tax != null) {
				$scope.net_interest = $scope.total_interest - $scope.income_tax;
				if($scope.excavator_tariff != null) {
					$scope.net_interest = $scope.net_interest - $scope.excavator_tariff;
				}
				$scope.disableNetInterest = true;
			} else {
				$scope.net_interest = null;
				$scope.disableNetInterest = false;
			}
		}

		$scope.$watch('net_interest', function(){
			$scope.GetTotalWithInterest();
		});

		$scope.GetTotalWithInterest = function() {
			if($scope.net_interest != null && $scope.main_amount != null) {
				$scope.total_with_interest = $scope.main_amount + $scope.net_interest;
				$scope.disableTotalWithInterest = true;
			} else {
				$scope.total_with_interest = null;
				$scope.disableTotalWithInterest = false;
			}
		}

	});