angular.module('CnfCreateApp',['angularUtils.directives.dirPagination','customServiceModule'])
	.controller('CnfCreateController', function($scope, $http,enterKeyService){

        $scope.btnSave = true;
        $scope.btnUpdate = false;

        enterKeyService.enterKey('#userEntryForm input ,#userEntryForm button')

        // $scope.allCNFList = function() {
        // 	$http.get("/c&f/api/c&f/get-all-c&f-details")
        // 		.then(function(data){
        //             console.log(data.data);
        // 			$scope.allCNF = data.data;
        // 		}).catch(function(r) {
        //             console.log(r)
        //             if (r.status == 401) {
        //                 $.growl.error({message: r.data});
        //             } else {
        //                 $.growl.error({message: "It has Some Error!"});
        //             }
        //         })
        // }
        // $scope.allCNFList();

        $scope.validation = function() {
            var f = 0;
            if($scope.licence_photo != null) {
                var pattern = /^image\/(jpe?g|png|gif|bmp)$/g;
                if($scope.licence_photo.size/1024 > 2048) {
                    $scope.licence_photo_validation = 'Photo size must be in 2MB';
                    //angular.element("input[type='file']").val(null);
                    $('#licence_photo').val('');
                    f = 1;
                } else if(!pattern.test($scope.licence_photo.type)) {
                    $scope.licence_photo_validation = 'Invalid image file type';
                    //angular.element("input[type='file']").val(null);
                    $('#licence_photo').val('');
                    f = 1;
                } else {
                   $scope.licence_photo_validation = false;
                }
            } else {
                $scope.photo_validation = false;
            }
            if($scope.owner_photo != null) {
                var pattern = /^image\/(jpe?g|png|gif|bmp)$/g;
                if($scope.owner_photo.size/1024 > 2048) {
                    $scope.owner_photo_validation = 'Photo size must be in 2MB';
                    //angular.element("input[type='file']").val(null);
                    $('#owner_photo').val('');
                    f = 2;
                } else if(!pattern.test($scope.owner_photo.type)) {
                    $scope.owner_photo_validation = 'Invalid image file type';
                    //angular.element("input[type='file']").val(null);
                    $('#owner_photo').val('');
                    f = 2;
                } else {
                    $scope.owner_photo_validation = false;
                }
            } else {
                $scope.owner_photo_validation = false;
                // if($scope.nid_photo_link == null) {
                //     $scope.nid_photo_validation = 'NID Image is required';
                //     f = 2;
                // }
            }

            if($scope.owner_nid_photo != null) {
                var pattern = /^image\/(jpe?g|png|gif|bmp)$/g;
                if($scope.owner_nid_photo.size/1024 > 2048) {
                    $scope.owner_nid_photo_validation = 'Photo size must be in 2MB';
                    //angular.element("input[type='file']").val(null);
                    $('#owner_nid_photo').val('');
                    f = 3;
                } else if(!pattern.test($scope.owner_nid_photo.type)) {
                    $scope.owner_nid_photo_validation = 'Invalid image file type';
                    //angular.element("input[type='file']").val(null);
                    $('#owner_nid_photo').val('');
                    f = 3;
                } else {
                    $scope.owner_nid_photo_validation = false;
                }
            } else {
                $scope.owner_nid_photo_validation = false;
                // if($scope.nid_photo_link == null) {
                //     $scope.nid_photo_validation = 'NID Image is required';
                //     f = 2;
                // }
            }

            if($scope.bank_voucher_photo != null) {
                var pattern = /^image\/(jpe?g|png|gif|bmp)$/g;
                if($scope.bank_voucher_photo.size/1024 > 2048) {
                    $scope.bank_voucher_photo_validation = 'Photo size must be in 2MB';
                    //angular.element("input[type='file']").val(null);
                    $('#bank_voucher_photo').val('');
                    f = 4;
                } else if(!pattern.test($scope.bank_voucher_photo.type)) {
                    $scope.bank_voucher_photo_validation = 'Invalid image file type';
                    //angular.element("input[type='file']").val(null);
                    $('#owner_nid_photo').val('');
                    f = 4;
                } else {
                    $scope.bank_voucher_photo_validation = false;
                }
            } else {
                $scope.bank_voucher_photo_validation = false;
                // if($scope.nid_photo_link == null) {
                //     $scope.nid_photo_validation = 'NID Image is required';
                //     f = 2;
                // }
            }

            if($scope.shonchoypatro_photo != null) {
                var pattern = /^image\/(jpe?g|png|gif|bmp)$/g;
                if($scope.shonchoypatro_photo.size/1024 > 2048) {
                    $scope.shonchoypatro_photo_validation = 'Photo size must be in 2MB';
                    //angular.element("input[type='file']").val(null);
                    $('#shonchoypatro_photo').val('');
                    f = 5;
                } else if(!pattern.test($scope.shonchoypatro_photo.type)) {
                    $scope.shonchoypatro_photo_validation = 'Invalid image file type';
                    //angular.element("input[type='file']").val(null);
                    $('#shonchoypatro_photo').val('');
                    f = 5;
                } else {
                    $scope.shonchoypatro_photo_validation = false;
                }
            } else {
                $scope.shonchoypatro_photo_validation = false;
                // if($scope.nid_photo_link == null) {
                //     $scope.nid_photo_validation = 'NID Image is required';
                //     f = 2;
                // }
            }

            if($scope.agreement_photo != null) {
                var pattern = /^image\/(jpe?g|png|gif|bmp)$/g;
                if($scope.agreement_photo.size/1024 > 2048) {
                    $scope.agreement_photo_validation = 'Photo size must be in 2MB';
                    //angular.element("input[type='file']").val(null);
                    $('#agreement_photo').val('');
                    f = 6;
                } else if(!pattern.test($scope.agreement_photo.type)) {
                    $scope.agreement_photo_validation = 'Invalid image file type';
                    //angular.element("input[type='file']").val(null);
                    $('#agreement_photo').val('');
                    f = 6;
                } else {
                    $scope.agreement_photo_validation = false;
                }
            } else {
                $scope.agreement_photo_validation = false;
                // if($scope.nid_photo_link == null) {
                //     $scope.nid_photo_validation = 'NID Image is required';
                //     f = 2;
                // }
            }

            if($scope.cnfCreateForm.$invalid) {
                f = 7;
            }

            if (f>=1 && f<=7) {
                $scope.submitted = true;
                return false;
            } else {
                $scope.submitted = false;
                return true;
            }
        }

        $scope.blank = function() {
            $scope.cnf_name = null;
            $scope.ain_no = null;
            $scope.licence_date = null;
            $scope.address = null;
            $scope.mobile = null;
            $scope.email = null;
            $scope.register_date = null;
            $scope.validity = null;
            $scope.expired_date = null;
            $('.selectpicker').val([]);
            $('.selectpicker').trigger('change.abs.preserveSelected');
            $('.selectpicker').selectpicker('refresh');
            //$scope.port_id = null;
            $('#licence_photo').val('');
            $('#owner_photo').val('');
            $('#owner_nid_photo').val('');
            $('#bank_voucher_photo').val('');
            $('#shonchoypatro_photo').val('');
            $('#agreement_photo').val('');
            $scope.cnf_id = null;
        }

        $scope.save = function() {

            console.log($scope.validation());
            if($scope.validation() == false) {
                return;
            }
            $scope.dataLoading = true;
            var data = new FormData();
            data.append('port_id', $scope.port_id);
            data.append('cnf_name', $scope.cnf_name);
            data.append('ain_no', $scope.ain_no);
            data.append('licence_date', $scope.licence_date);
            data.append('address', $scope.address);
            data.append('mobile', $scope.mobile);
            data.append('email', $scope.email);
            data.append('register_date', $scope.register_date);
            data.append('validity', $scope.validity);
            data.append('expired_date', $scope.expired_date);
            data.append('licence_photo', $scope.licence_photo);
            data.append('owner_photo', $scope.owner_photo);
            data.append('owner_nid_photo', $scope.owner_nid_photo);
            data.append('bank_voucher_photo', $scope.bank_voucher_photo);
            data.append('shonchoypatro_photo', $scope.shonchoypatro_photo);
            data.append('agreement_photo', $scope.agreement_photo);
            console.log(data);
            $http.post("/c&f/api/c&f/save-c&f-data", data, {
                  withCredentials: true,
                  headers: {'Content-Type': undefined },
                  transformRequest: angular.identity
                }).then(function(data){
                    console.log(data);
        			$scope.savingSuccess = "CNF Entry Saved Successfully.";
                    $("#savingSuccess").show().delay(5000).slideUp(1000);
                    $scope.blank();
                   // $scope.allCNFList();
                $scope.getPageCount(pageNo);
                    $scope.dataLoading = false;
        		}).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    }
        			$scope.savingError = "Something Went Wrong.";
                    $("#savingError").show().delay(5000).slideUp(1000);
        		}).finally(function(){

        		})
        }

        $scope.pressUpdateBtn = function(cnf) {
            $scope.cnf_id = cnf.id;
            console.log($scope.cnf_id);
            $scope.cnf_name = cnf.cnf_name;
            $scope.ain_no = cnf.ain_no;
            $scope.licence_date = cnf.licence_date;
            $scope.address = cnf.address;
            $scope.mobile = cnf.mobile;
            $scope.email = cnf.email;
            $scope.register_date = cnf.register_date;
            $scope.validity = cnf.validity;
            $scope.expired_date = cnf.expired_date;

            console.log(cnf.port_id);
            if(cnf.port_id != null) {
                $('.selectpicker').val([]);
                $('.selectpicker').trigger('change.abs.preserveSelected');
                $('.selectpicker').selectpicker('refresh');
                var array = cnf.port_id.split(',');
                console.log(array)
                $('select[name=port_id]').val(array);// $('select[name=cnf.port_id]').val(array);
                $('.selectpicker').trigger('change.abs.preserveSelected');
                $('.selectpicker').selectpicker('refresh');
                $scope.port_id = array;
            }
            console.log($scope.port_id);


            $scope.btnUpdate = true;
            $scope.btnSave = false;
            
        }
        //console.log(typeof($scope.pressUpdateBtn));
        $scope.update = function() {
            console.log($scope.port_id);
            $scope.dataLoading = true;
            var data = new FormData();
            data.append('port_id', $scope.port_id);
            data.append('cnf_id', $scope.cnf_id);
            data.append('cnf_name', $scope.cnf_name);
            data.append('ain_no', $scope.ain_no);
            data.append('licence_date', $scope.licence_date);
            data.append('address', $scope.address);
            data.append('mobile', $scope.mobile);
            data.append('email', $scope.email);
            data.append('register_date', $scope.register_date);
            data.append('validity', $scope.validity);
            data.append('expired_date', $scope.expired_date);
            data.append('licence_photo', $scope.licence_photo);
            data.append('owner_photo', $scope.owner_photo);
            data.append('owner_nid_photo', $scope.owner_nid_photo);
            data.append('bank_voucher_photo', $scope.bank_voucher_photo);
            data.append('shonchoypatro_photo', $scope.shonchoypatro_photo);
            data.append('agreement_photo', $scope.agreement_photo);
            console.log(data);
            $http.post("/c&f/api/c&f/update-c&f-data", data, {
                  withCredentials: true,
                  headers: {'Content-Type': undefined },
                  transformRequest: angular.identity
                }).then(function(data){
                    console.log(data);
                    $scope.savingSuccess = "CNF Entry Updated Successfully.";
                    $("#savingSuccess").show().delay(5000).slideUp(1000);
                    $scope.blank();
                   // $scope.allCNFList();
                console.log($scope.currentPage);
                $scope.getPageCount($scope.currentPage);
                    $scope.dataLoading = false;
                }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    }
                    $scope.savingError = "Something Went Wrong.";
                    $("#savingError").show().delay(5000).slideUp(1000);
                }).finally(function(){

                })
        }

        $scope.pressDeleteBtn = function(cnf) {
            var confirmation = confirm("Do You Want To Delete This Data?");
            console.log(cnf);
            if(confirmation) {
                var data = {
                    id : cnf.id,
                    licence_photo : cnf.licence_photo,
                    owner_photo : cnf.owner_photo,
                    owner_nid_photo : cnf.owner_nid_photo,
                    shonchoypatro_photo : cnf.shonchoypatro_photo,
                    agreement_photo : cnf.agreement_photo,
                    bank_voucher_photo : cnf.bank_voucher_photo

                }
                $http.post("/c&f/api/c&f/delete-c&f-data", data)
                    .then(function(data){
                        console.log(data);
                        $scope.savingSuccess = "CNF Deleted Successfully.";
                        $("#savingSuccess").show().delay(5000).slideUp(1000);
                    }).catch(function(r){
                        console.log(r)
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        }
                        $scope.savingError = "Something Went Wrong.";
                        $("#savingError").show().delay(5000).slideUp(1000);
                    }).finally(function(){
                       // $scope.allCNFList();
                    $scope.getPageCount(pageNo);
                    })
            } else {
                return false;
            }
        }
       var pageNo = 1;
        $scope.serial = 1;
        $scope.getPageCount = function(pageNo){
           console.log(pageNo);

            $scope.itemPerpage = 5;



            $http.get("/c&f/api/c&f/get-all-c&f-details/"+$scope.itemPerpage+"/"+pageNo)
                .then(function(data){
                    console.log(data.data);
                    $scope.total_count = data.data[0].total;
                    $scope.allCNF = data.data;
                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            })
            $scope.serial = pageNo * $scope.itemPerpage  - ($scope.itemPerpage -1);
            console.log($scope.serial);
        }
        $scope.getPageCount(pageNo);

        $scope.disableExpireDate = true;
        $scope.getExpireDate = function() {
            if($scope.register_date != null && $scope.validity != null) {
                var op_dt = new Date($scope.register_date);
                var du = $scope.validity*12;
                var ex_dt = new Date($scope.register_date);
                //ex_dt.setFullYear(op_dt.getFullYear() + du);
                ex_dt.setMonth(op_dt.getMonth() + du);
                //console.log(formatedDate);
                $('#expired_date').datepicker("setDate", new Date(ex_dt)).trigger('input');
                $scope.disableExpireDate = true;
            } else {
                $('#expired_date').val(null);
                //$scope.disableExpireDate = false;
            }
        }

        $scope.ClassRemover = function(className,index) {
            if($('#' + index).hasClass(className)) 
                $("#" + index).removeClass(className);
        }

        $scope.addClassParcentage = function(parcentage, index) {
            //console.log(parcentage);
         //   console.log(index);
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
        function isNumeric(n) {
          return !isNaN(parseFloat(n)) && isFinite(n);
        }


        $scope.getProgressbarValue = function(diff_from_today, total_day_difference , flag, index) {
            if(isNumeric(diff_from_today) && isNumeric(total_day_difference)) {
                if(diff_from_today > 0) {
                    var parcentage = ((diff_from_today/total_day_difference)*100).toFixed(2);
                    if(flag == 3) {
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
    }]);