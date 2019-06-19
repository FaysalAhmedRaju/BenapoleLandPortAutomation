angular.module('ImporterApp', ['angularUtils.directives.dirPagination','customServiceModule'])
	.controller('ImporterCtrl', function($scope, $http/*, $q*/ ,enterKeyService){

		enterKeyService.enterKey('#importerForm input ,#importerForm button');
		$scope.btnSave = true;
		$scope.btnUpdate = false;

		var current_bin_no = null;
		$scope.currentPage = 1;
		$scope.exist = false;

		$scope.resetPage = function() {
		$scope.currentPage = 1;
		}
		//WHEN SEARCH FROM PAGE NO 2 to ~ .TO SET PAGE 1 This Function Should Be called
		var pageno = 1;
	    $scope.GetData = function(pageno) {
	    	$scope.importers = [];
	    	var itemsPerPage = 10;
	    	$scope.bin_id = null;
	    	$scope.whenSingleImporter = false;
	    	$scope.listLoading = true;
	    	$scope.tableHeading = 'Importers List';
	        $http.get("/importer/api/importer/get-importer-list/"+itemsPerPage+"/"+pageno)
	        	.then(function(data){
	        		$scope.total_count = data.data[0].total;
	            	$scope.importers = data.data; 
	       		}).catch(function (r) {

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {


            });
	       	$scope.listLoading = false;
	    }
	    $scope.GetData(pageno);


	    $scope.Blank = function() {
	    	$scope.id = null;
	    	$scope.BIN = null;
	    	$scope.NAME = null;
	    	$scope.ADD1 = null;
	    	$scope.ADD2 = null;
	    	$scope.ADD3 = null;
	    	$scope.ADD4 = null;
	    	current_bin_no = null;
	    	$scope.exist = false;
	    	$scope.vat = '0';
	    }

	    $scope.Validation = function() {
	    	if($scope.exist == true) {
	    			$scope.submitted = true;
	    			return false;
	    	}

	    	if($scope.importerForm.$invalid) {
	    		$scope.submitted = true;
	    		return false;
	    	} else {
	    		$scope.submitted = false;
	    		return true;
	    	}
	    }

	    $scope.Save = function() {
	    	if($scope.Validation() == false) {
	    		return;
	    	}
	    	//return;
	    	$scope.dataLoading = true;
	    	var data = {
	    		BIN : $scope.BIN,
	    		vat : $scope.vat,
	    		NAME : $scope.NAME,
	    		ADD1 : $scope.ADD1,
	    		ADD2 : $scope.ADD2,
	    		ADD3 : $scope.ADD3,
	    		ADD4 : $scope.ADD4
	    	}

	    	$http.post("/importer/api/importer/save-importer-data",data)
	    		.then(function(data){
	    			$scope.savingSuccess = 'Importer details saved successfully.'
	    			$('#savingSuccess').show().delay(5000).slideUp(1000);
	    			$scope.GetSingleImporter($scope.BIN);
	    			$scope.Blank();
	    		}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
	    			$scope.savingError = 'Something went wrong.'
	    			$('#savingError').show().delay(5000).slideUp(1000);
	    		}).finally(function(){
	    			$scope.dataLoading = false;
	    		})
	    }

	    $scope.GetSingleImporter = function(bin_no) {
	    	$scope.tableHeading = 'Importer Details';
	    	$scope.whenSingleImporter = true;
	    	$http.get("/importer/api/importer/get-single-importer-data/"+bin_no)
	    		.then(function(data){
	    			if(data.data.length>0) {
	    				$scope.importers = data.data; 
	            		$scope.total_count = 0;
	    			} else {
	    				$scope.binNotFound = true;
	    				$scope.importers = [];
	    				$scope.total_count = 0;
	    				$('#binNotFound').show().delay(5000).slideUp(1000);
	    			}
	    		}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
	    		}).finally(function(){
	    			$scope.resetPage();
	    		})
	    }
	    //$scope.updateFlag = false;
	    $scope.PressUpdateBtn = function(importer) {
	    	//$scope.updateFlag = true;
	    	$scope.id = importer.id;
	    	$scope.selectedStyle = importer.id;
	    	$scope.BIN = parseFloat(importer.BIN);
	    	$scope.vat = importer.vat.toString();
	    	$scope.NAME = importer.NAME;
	    	$scope.ADD1 = importer.ADD1;
	    	$scope.ADD2 = importer.ADD2;
	    	$scope.ADD3 = importer.ADD3;
	    	$scope.ADD4 = importer.ADD4;
	    	$scope.btnSave = false;
			$scope.btnUpdate = true;
			current_bin_no = parseFloat(importer.BIN);
			//$scope.diableBINNUmber = true;
	    }

	    $scope.Update = function() {
	    	if($scope.Validation() == false) {
	    		return;
	    	}
	    	$scope.dataLoading = true;
	    	var data = {
	    		id : $scope.id,
	    		BIN : $scope.BIN,
	    		vat : $scope.vat,
	    		NAME : $scope.NAME,
	    		ADD1 : $scope.ADD1,
	    		ADD2 : $scope.ADD2,
	    		ADD3 : $scope.ADD3,
	    		ADD4 : $scope.ADD4
	    	}

	    	$http.put("/importer/api/importer/update-importer-data",data)
	    		.then(function(data){
	    			$scope.savingSuccess = 'Importer details updated successfully.'
	    			$('#savingSuccess').show().delay(5000).slideUp(1000, function(){
	    				$scope.selectedStyle = 0;
	    			});
	    			//console.log($scope.currentPage);
	    			if($scope.currentPage != 1) {
	    				$scope.GetData($scope.currentPage);
	    			} else {
	    				$scope.GetSingleImporter($scope.BIN);
	    			}
	    			$scope.Blank();
	    			$scope.btnSave = true;
					$scope.btnUpdate = false;
	    		}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
	    			$scope.savingError = 'Something went wrong.'
	    			$('#savingError').show().delay(5000).slideUp(1000, function() {
	    				$scope.selectedStyle = 0;
	    			});
	    		}).finally(function(){
	    			$scope.dataLoading = false;
	    			$scope.diableBINNUmber = false;
	    		})
	    }

	    $scope.PressDeleteBtn = function(importer) {
	    	var id = importer.id;
	    	$scope.selectedStyle = importer.id;
            var importerName = importer.NAME;
            bootbox.confirm({
                message: "Do you want to Delete <b>" + importerName + "</b>?",
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
                    $scope.DeleteImporter(result, id, importerName);
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

	    $scope.DeleteImporter = function(result, id, importerName) {
	    	if(result == true) {
                $http.delete("/importer/api/importer/delete-importer-data/"+id)
                    .then(function(data){
                        $scope.savingSuccess = "'" + importerName + "' Deleted Successfully.";
                        $("#savingSuccess").show().delay(5000).slideUp(1000, function() {
                        	$scope.selectedStyle = 0;
                        });
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingError = "Something Went Wrong.";
                        $("#savingError").show().delay(5000).slideUp(1000, function() {
                        	$scope.selectedStyle = 0;
                        });
                    }).finally(function(){
                    	$scope.resetPage();
                    	$scope.GetData(pageno);
                    })
            } else {
                return false;
                $scope.selectedStyle = 0;
            }
	    }

	    $scope.$watch('BIN', function(){
	    	$scope.checkBinNumber();
	    });

	    $scope.checkBinNumber = function() {
	    	if($scope.BIN != null) {
	    		if(current_bin_no != $scope.BIN) {
		    		$http.get("/importer/api/importer/check-bin-number-data/" + $scope.BIN)
					    .then(function(data) {
					    	console.log(data.data);
					    	if(data.data[0].exist > 0) {
					    		$scope.exist = true;
					    	} else {
					    		$scope.exist = false;
					    	}
					    }).catch(function (r) {

                        console.log(r)
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        } else {
                            $.growl.error({message: "It has Some Error!"});
                        }

                    }).finally(function () {


                    });
				} else {
					$scope.exist = false;
				}
	    	} else {
	    		$scope.exist = false;
	    	}
 	    }


	}).filter('vatFilter', function () {
    return function (val) {
        var vatW;
        if (val == 1) {
            return vatW = 'Yes';
        } else if (val == 0) {
            return vatW = 'No';
        }
        return vatW = '';
    }
});
