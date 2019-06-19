angular.module('EmployeeDetailsApp', ['angularUtils.directives.dirPagination','customServiceModule'])
	.controller('EmployeeDetailsController', function ($scope, $http,enterKeyService) {

		$scope.childrenOptions = [
	      {value: 0, text:'None'},
	      {value: 1, text:'One'},
	      {value: 2, text:'Two'},
	      {value: 3, text:'More Than Two'}
	    ];
	    $scope.children = $scope.childrenOptions[0].value;

	    $scope.btnSave = true;
	    $scope.btnUpdate = false;
	    $scope.btnUpdaTetransfer = false;
        //$scope.photo_preview = false;
        $scope.employeeDetails = false;
	    // function readURL(input) {
	    //     if (input.files && input.files[0]) {
	    //         var reader = new FileReader();
	            
	    //         reader.onload = function (e) {
	    //             $('#photo_preview').attr('src', e.target.result);
	    //         }
	    //         reader.readAsDataURL(input.files[0]);
	    //     }
    	// }

    	// $("#photo").change(function(){
    	//     readURL(this);
     //        $scope.photo_preview = true;
    	// });

        $scope.allEmployeeDetails = function() {
            $http.get("/accounts/salary/api/get-all-employee-details")
                .then(function(data){
                    console.log(data);
                    if(data.data.length > 0) {
                        $scope.allEmployees = data.data;
                        $scope.employeeDetails = true;
                    } else {
                        $scope.employeeDetails = false;
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
        }
        $scope.allEmployeeDetails();

        enterKeyService.enterKey('#EmployeeEntry input ,#EmployeeEntry button')

        $scope.allSuspendedEmployeeDetails = function() {
            $http.get("/accounts/salary/api/get-all-suspended-employee")
                .then(function(data){
                    if(data.data.length > 0) {
                        $scope.allSuspendedEmployees = data.data;
                        $scope.suspendedEmployeeDetails = true;
                    } else {
                        $scope.suspendedEmployeeDetails = false;
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
        }
        $scope.allSuspendedEmployeeDetails();

    	$scope.blank = function() {
            $scope.name = null;
            $scope.father_name = null;
            $scope.mother_name = null;
            $scope.spouse_name = null;
            $scope.mobile = null;
            $scope.telephone = null;
            $scope.email = null;
            $scope.national_id = null;
            $scope.date_of_birth = null;
            $scope.date_join = null;
            $scope.present_address = null;
            $scope.permanent_address = null;
            $scope.children = 0;
            $scope.allocate_home = "0";
            $scope.house_area_flag = "3";
            angular.element("input[type='file']").val(null);
            //$('#photo_preview').attr('src', "");
            //$scope.photo_preview = false;
            $scope.national_id_photo = null;
            $scope.photo = null;
            $scope.photo_link = null;
            $scope.national_id_photo_link = null;

            $scope.date_transfer = null;
            $scope.port_id = null;
        }

        $scope.validation = function() {
            var f = 0;
            //console.log($scope.EmployeeEntry);
            //console.log($scope.national_id_photo);
            //console.log($scope.photo);
            if($scope.photo != null) {
                var pattern = /^image\/(jpe?g|png|gif|bmp)$/g;
                if($scope.photo.size/1024 > 2048) {
                    $scope.photo_error = 'Photo size must be in 2MB';
                    $('#photo').val('');
                    //angular.element("input[type='file']").val(null);
                    //$('#photo_preview').attr('src', "");
                    //$scope.photo_preview = false;
                    f = 1;
                } else if(!pattern.test($scope.photo.type)) {
                    $scope.photo_error = 'Invalid image file type';
                    $('#photo').val('');
                    //angular.element("input[type='file']").val(null);
                    //$('#photo_preview').attr('src', "");
                    //$scope.photo_preview = false;
                    f = 1;
                } else {
                   $scope.photo_error = false;
                }
            } else {
                $scope.photo_error = false;
            }
            if($scope.national_id_photo != null) {
                var pattern = /^image\/(jpe?g|png|gif|bmp)$/g;
                if($scope.national_id_photo.size/1024 > 2048) {
                    $scope.national_id_photo_error = 'Photo size must be in 2MB';
                    $('#national_id_photo').val('');
                    //angular.element("input[type='file']").val(null);
                    //$('#photo_preview').attr('src', "");
                    //$scope.photo_preview = false;
                    f = 2;
                } else if(!pattern.test($scope.national_id_photo.type)) {
                    $scope.national_id_photo_error = 'Invalid image file type';
                    $('#national_id_photo').val('');
                    //angular.element("input[type='file']").val(null);
                    //$('#photo_preview').attr('src', "");
                    //$scope.photo_preview = false;
                    f = 2;
                } else {
                    $scope.national_id_photo_error = false;
                }
            } else {
                //$scope.nid_photo_error = false;
                if($scope.national_id_photo_link == null) {
                    $scope.national_id_photo_error = 'National ID Photo is required';
                    f = 2;
                }
            }
            if($scope.EmployeeEntry.$invalid) {
                $scope.submitted = true;
                f = 3;
            } else {
                $scope.submitted = false;
            }
            if(f>=1 && f<=3) {
                return false;
            } else {
                return true;
            }

        }

    	$scope.save = function() {
            if($scope.validation() == false) {
                return;
            }    
            $scope.dataLoading = true;
            var data = new FormData();
            data.append('name', $scope.name);
            data.append('father_name',$scope.father_name);
            data.append('mother_name',$scope.mother_name);
            data.append('spouse_name', $scope.spouse_name);
            data.append('mobile', $scope.mobile);
            data.append('telephone', $scope.telephone);
            data.append('email', $scope.email);
            data.append('national_id', $scope.national_id);
            data.append('date_of_birth', $scope.date_of_birth);
            data.append('date_join', $scope.date_join);
            data.append('present_address', $scope.present_address);
            data.append('permanent_address', $scope.permanent_address);
            data.append('children', $scope.children);
            data.append('national_id_photo',$scope.national_id_photo);
            data.append('photo', $scope.photo);
            data.append('allocate_home', $scope.allocate_home);
            data.append('house_area_flag', $scope.house_area_flag);

        	$http.post("/accounts/salary/api/save-employee-data", data, {
                  withCredentials: true,
                  headers: {'Content-Type': undefined },
                  transformRequest: angular.identity
                }).then(function(data){
                    console.log(data);
        			$scope.savingSuccess = "Employee Entry Saved Successfully.";
                    $("#savingSuccess").show().delay(5000).slideUp(1000);
                    $scope.blank();
                    $scope.allEmployeeDetails();
                    $scope.dataLoading = false;
        		}).catch(function(r){
                    if(r.status == 401) {
                        $.growl.error({message: r.data});
                       $scope.savingError = r.data.nidPhoto;
                   } else {
                        $.growl.error({message: "It has Some Error!"});
                        $scope.savingError = r.statusText;
                   }
                    $("#savingError").show().delay(5000).slideUp(1000);
        		}).finally(function(){

        		})
    	}

        $scope.pressUpdateBtn = function(employee) {
    	    console.log(employee);
    	    console.log(employee.national_id_photo);
            $scope.btnSave = false;
            $scope.btnUpdate = true;
            $scope.selectedStyle = employee.id;
            $scope.id = employee.id;
            $scope.name = employee.name;
            $scope.father_name = employee.father_name;
            $scope.mother_name = employee.mother_name;
            $scope.spouse_name = employee.spouse_name;
            $scope.mobile = employee.mobile;
            $scope.telephone = employee.telephone;
            $scope.email = employee.email;
            $scope.national_id = employee.national_id;
            $scope.date_of_birth = employee.date_of_birth;
            $scope.date_join = employee.date_join;
            $scope.present_address = employee.present_address;
            $scope.permanent_address = employee.permanent_address;
            $scope.children = employee.children;
            $scope.photo_link = employee.photo;
            $scope.national_id_photo_link = employee.national_id_photo;
            $scope.allocate_home = employee.allocate_home.toString();
            $scope.house_area_flag = employee.house_area_flag.toString();

            //console.log($scope.photo_link);
            // if($scope.photo_link != null) {
            //     $('#photo_preview').attr('src', "img/Employees/"+$scope.photo_link);
            //     $scope.photo_preview = true;
            // } else {
            //     $('#photo_preview').attr('src', "");
            //     $scope.photo_preview = false;
            // }
        }


        $http.post("/accounts/salary/api/get-port-data-details")
            .then(function(data){
              //  console.log(data);
                $scope.PortDetailsData = data.data;
            }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });

        $scope.update = function() {
            if($scope.validation() == false) {
                return;
            }
            $scope.dataLoading = true;
            var data = new FormData();
            data.append('id', $scope.id);
            data.append('name', $scope.name);
            data.append('father_name',$scope.father_name);
            data.append('mother_name',$scope.mother_name);
            data.append('spouse_name', $scope.spouse_name);
            data.append('mobile', $scope.mobile);
            data.append('telephone', $scope.telephone);
            data.append('email', $scope.email);
            data.append('national_id', $scope.national_id);
            data.append('date_of_birth', $scope.date_of_birth);
            data.append('date_join', $scope.date_join);
            data.append('present_address', $scope.present_address);
            data.append('permanent_address', $scope.permanent_address);
            data.append('children', $scope.children);
            data.append('allocate_home', $scope.allocate_home);
            data.append('house_area_flag', $scope.house_area_flag);
            data.append('national_id_photo', $scope.national_id_photo);
            data.append('national_id_photo_link', $scope.national_id_photo_link);
            data.append('photo', $scope.photo);
            data.append('photo_link', $scope.photo_link);
            console.log(data);
            $http.post("/accounts/salary/api/update-employee-data", data, {
                  withCredentials: true,
                  headers: {'Content-Type': undefined },
                  transformRequest: angular.identity
                }).then(function(data){
                    console.log(data);
                    $scope.savingSuccess = "Employee Entry Updated Successfully.";
                    $("#savingSuccess").show().delay(5000).slideUp(1000, function() {
                        $scope.selectedStyle = 0;
                    });
                    $scope.blank();
                    $scope.allEmployeeDetails();
                    $scope.dataLoading = false;
                    $scope.btnSave = true;
                    $scope.btnUpdate = false;
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
        }

        $scope.pressSuspendBtn = function(employee) {
            var id = employee.id;
            var employeeName = employee.name;
            bootbox.confirm({
                message: "Do you want to Suspend <b>" + employeeName + "</b>?",
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
                    $scope.suspendEmployee(result, id, employeeName);
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

        $scope.suspendEmployee = function(result, id, employeeName) {
            if(result == true) {
                $http.get("/accounts/salary/api/suspend-employee-data/"+id)
                    .then(function(data){
                        $scope.savingSuccess = "Employee '" + employeeName + "' Suspended Successfully.";
                        $("#savingSuccess").show().delay(5000).slideUp(1000);
                        $scope.allEmployeeDetails();
                        $scope.allSuspendedEmployeeDetails();
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingError = "Something Went Wrong.";
                        $("#savingError").show().delay(5000).slideUp(1000);
                    }).finally(function(){

                    })
            } else {
                return false;
            }
        }

        $scope.pressReassignBtn = function(employee) {
            var id = employee.id;
            var employeeName = employee.name;
            bootbox.confirm({
                message: "Do you want to Reassign <b>" + employeeName + "</b>?",
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
                    $scope.reassignEmployee(result, id, employeeName);
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

        $scope.reassignEmployee = function(result, id, employeeName) {
            if(result == true) {
                $http.get("/accounts/salary/api/reassign-employee-data/"+id)
                    .then(function(data){
                        $scope.savingSuccess = "Employee '" + employeeName + "' Reassign Successfully.";
                        $("#savingSuccess").show().delay(5000).slideUp(1000);
                        $scope.allEmployeeDetails();
                        $scope.allSuspendedEmployeeDetails();
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingError = "Something Went Wrong.";
                        $("#savingError").show().delay(5000).slideUp(1000);
                    }).finally(function(){

                    })
            } else {
                return false;
            }
        }

        $scope.employeeTransferButton = function (employee) {
            console.log(employee);
            $scope.id = employee.id;
            $scope.emp_id_show = employee.emp_id;
            $scope.name_show = employee.name;
            $scope.mobile_show = employee.mobile;
            $scope.employeeTransferDetails(employee.id);
        }

        $scope.employeeTransferDetails = function (id) {
            $http.get("/accounts/salary/api/get-employee-transfer-details/"+id)
                .then(function(data){
                    console.log(data);
                    $scope.employeesTransferData = data.data;
                    console.log($scope.employeesTransferData);
                }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
            }).finally(function (r) {
            });
        }

        $scope.saveTransferData = function (employeeTransferForm) {
            if(employeeTransferForm.$invalid == true) {
                $scope.submittedTransfer = true;
                return;
            } else {
                $scope.submittedTransfer = false
            }
            $scope.dataLoading = true;
            var data = new FormData();
            data.append('transfer_date', $scope.date_transfer);
            data.append('port_id', $scope.port_id);
            data.append('employee_id', $scope.id);
            console.log(data);
            $http.post("/accounts/salary/api/save-employee-transfer-data", data, {
                withCredentials: true,
                headers: {'Content-Type': undefined },
                transformRequest: angular.identity
            }).then(function(data){
                //console.log(data);
                $scope.savingTransferSuccess = "Saved Successfully.";
                $("#savingTransferSuccess").show().delay(5000).slideUp(1000);
                $scope.blank();
                $scope.employeeTransferDetails($scope.id);
                $scope.allEmployeeDetails();
                $scope.dataLoading = false;
            }).catch(function(r){
                console.log(r)
                if(r.status == 401) {
                    $.growl.error({message: r.data});
                }
                $scope.savingErrorTransfer = "Something Went Wrong!";
                $("#savingErrorTransfer").show().delay(5000).slideUp(1000);
            }).finally(function(){
            })
        }

        $scope.ediTransferData = function (transferData) {
            $scope.btnUpdaTetransfer = true;
            console.log(transferData);
            $scope.transfer_id = transferData.id;
            $scope.date_transfer = transferData.transfer_date;
            $scope.port_id = transferData.port_id;
            $scope.oldTransferDate = transferData.transfer_date;
        }

        $scope.updateTransferData = function (employeeTransferForm) {
            if(employeeTransferForm.$invalid == true) {
                $scope.submittedTransfer = true;
                return;
            } else {
                $scope.submittedTransfer = false;
            }

            $scope.dataLoading = true;
            var data = new FormData();
            data.append('id', $scope.transfer_id);
            data.append('port_id', $scope.port_id);
            data.append('transfer_date', $scope.date_transfer);
             data.append('employee_id', $scope.id);

            $http.post("/accounts/salary/api/update-employee-transfer-data", data, {
                withCredentials: true,
                headers: {'Content-Type': undefined },
                transformRequest: angular.identity
            }).then(function(data){
                console.log(data);
                $scope.savingTransferSuccess = "Updated Successfully.";
                $("#savingTransferSuccess").show().delay(5000).slideUp(1000, function() {
                    $scope.selectedStyle = 0;
                });
                $scope.blank();
                $scope.employeeTransferDetails($scope.id);
                $scope.allEmployeeDetails();
                $scope.dataLoading = false;
                $scope.btnUpdaTetransfer = false;

            }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingErrorTransfer = "Something Went Wrong.";
                $("#savingErrorTransfer").show().delay(5000).slideUp(1000, function(){
                    $scope.selectedStyle = 0;
                });
            }).finally(function(){

            })
        }

	}).directive('fileModel', ['$parse', function ($parse) {
        return {
            restrict: 'A',
            link: function(scope, element, attrs) {
                var model = $parse(attrs.fileModel);
                var modelSetter = model.assign;

                element.bind('change', function(){
                    scope.$apply(function(){
                        modelSetter(scope, element[0].files[0]);
                    });
                });
            }
        };
    }]).filter('childrenFilter', function () {
        return function (val) {
            var children;
            if(val==0){
               return children='None';
            } else if(val==1) {
                return children='One';
            } else if(val==2) {
                return children='Two';
            } else if(val=3) {
                return children='More Than Two';
            }
            return children='';
        }
    }).filter('houseAreaFilter', function () {
    return function (val) {
        var area;
        if(val==1){
            return area='Dhaka Metro Area';
        } else if(val==2) {
            return area='Expensive Area';
        } else if(val==3) {
            return area='Other Area';
        }
        return area='';
    }
}).filter('houseAllocateFilter', function () {
    return function (val) {
        var houseAllocate;
        if(val==0){
            return houseAllocate='No';
        } else if(val==1) {
            return houseAllocate='Yes';
        }
        return houseAllocate='';
    }
});