angular.module('DesignationEmployeeApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('DesignationEmployeeController', function($http, $scope,enterKeyService){
        // console.log("ok")
        // $scope.saveSuccessDesignation = false;
        // $scope.grade_list = grade_list;
        // console.log($scope.grade_list);

        $scope.getHead = function() {
            $http.get("/accounts/salary/designation/api/get-all-designation-details")
                .then(function(data) {
                  //  console.log(data);
                    if(data.data.length>0) {
                        $scope.headTable = true;
                        $scope.allDegsignationData = data.data;
                        $scope.head_id = data.data[0].id;
                        $scope.getSubHead($scope.head_id)
                    }else {
                        $scope.headTable = false;
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
        $scope.getHead();




        enterKeyService.enterKey('#Employeeform input ,#Employeeform button')
        enterKeyService.enterKey('#DesignationForm input ,#DesignationForm button')


        $scope.HeadAddBtn = true;
        $scope.HeadEditBtn = false;

        $scope.saveDeg = function(deg_name) {
            if($scope.validationDesignation()==false)
            {
                return;
            }
            var data = {
                deg_name : deg_name
            }
            console.log(data)
            $http.post("/accounts/salary/designation/api/save-designation-data",data)
                .then(function(data) {
                    $scope.deg_name = null;
                    $scope.savingSuccess = 'Save successfully.';
                    $("#savingSuccess").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#savingSuccess").slideUp(7000);
                    });
                }).catch(function(r){
                $scope.savingError = 'Something went wrong.';
                $("#savingError").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#savingError").slideUp(7000);
                });
            }).finally(function(){
                $scope.getHead();
            })
        }

        // var min = new Date().getFullYear()-5;
        // var max = min + 10;
        // $scope.years = [];
        // var j=0;
        // for (var i = min; i<=max; i++){
        //     $scope.years[j++] = {value: i, text: i};
        // }
        // console.log($scope.years);


        $http.get("/accounts/salary/designation/api/get-employees-information")
            .then(function (data) {
                // console.log(data)

                $scope.getEmployeesInfoData=data.data;

                //console.log( $scope.getEmployeesInfoData);
            }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });


        $scope.EmpDegSave=function () {

            if($scope.validationEmployeeDesignation()==false)
            {
                return;
            }

            var data={
                designation:$scope.designation,
                Employee:$scope.Employee
            }
             console.log(data)
            $http.post("/accounts/salary/designation/api/save-employee-designation-data",data)
                .then(function (data) {

                    $scope.saveDegnationSuccess='Saved Successfully.';
                    $("#saveDegnationSuccess").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#saveDegnationSuccess").slideUp(7000);
                    });
                    $scope.blankEmployeeDesignation();

                  // $scope.saveSuccessDesignation = true;
                 //   $scope.search( $scope.m_manifest);

                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.saveDegnationError='Something wet worng!';
                $("#saveDegnationError").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#saveDegnationError").slideUp(7000);
                });


            }).finally(function () {

                $scope.savingData=false;
                $scope.getSubHead();

            })
        }

        $scope.validationDesignation = function() {

            if($scope.DesignationForm.$invalid) {

                $scope.submittedFixedDeg = true;
                return false;

            } else {

                $scope.submittedFixedDeg = false;
                return true;

            }

        }

        $scope.validationEmployeeDesignation = function() {

            if($scope.Employeeform.$invalid) {

                $scope.submittedFixed = true;
                return false;

            } else {

                $scope.submittedFixed = false;
                return true;

            }

        }



        // $scope.saveSuccessDesignation = false;

        $scope.editBtn = function(value) {

            $scope.HeadAddBtn = false;
            $scope.HeadEditBtn = true;
            $scope.id = value.id;
            $scope.deg_name = value.designation;

        }


        $scope.editDegEmployee =function (DegEmployee) {

            $scope.updateBtn = true;
            $scope.emp_deg_id = DegEmployee.emp_deg_id;
            $scope.designation = DegEmployee.desig_id;
            $scope.Employee = DegEmployee.employee_id;


            console.log($scope.emp_deg_id);

        }
        $scope.blankEmployeeDesignation = function() {
            $scope.emp_deg_id = null;
            $scope.designation = null;
            $scope.Employee = null;
            $scope.empCurrentDesignation = null;
            $scope.previouse_designation = null;
            $scope.show_employee_img = null;
        }

        $scope.updateDegEmployee = function () {


            if($scope.validationEmployeeDesignation()==false)
            {
                return;
            }

            var data={
                designation:  $scope.designation ,
                Employee:  $scope.Employee ,
                emp_deg_id: $scope.emp_deg_id

                     }
            console.log(data);
            $http.put("/accounts/salary/designation/api/update-employee-designation-data",data)
                .then(function(data) {
                    $scope.updateBtn = false;
                    $scope.blankEmployeeDesignation();
                    $scope.savingSuccessDegUpdate = 'Update successfully.';
                    $("#savingSuccessDegUpdate").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#savingSuccessDegUpdate").slideUp(7000);
                    });
                }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingErrorDegUpdate = 'Something went wrong.';
                $("#savingErrorDegUpdate").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#savingErrorDegUpdate").slideUp(7000);
                });
            }).finally(function(){
                // $scope.getHead();
                $scope.getSubHead();
            })

        }

        $scope.editDeg = function(deg_name) {

            if($scope.validationDesignation()==false)
            {
                return;
            }

            var data = {
                id : $scope.id,
                designation : deg_name
            }

            $http.put("/accounts/salary/designation/api/update-designation-data",data)
                .then(function(data) {
                    $scope.deg_name = null;
                    $scope.HeadAddBtn = true;
                    $scope.HeadEditBtn = false;
                    $scope.savingSuccessUpdate = 'Update successfully.';
                    $("#savingSuccessUpdate").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#savingSuccessUpdate").slideUp(7000);
                    });

                }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingErrorUpdate = 'Something went wrong.';
                $("#savingErrorUpdate").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#savingErrorUpdate").slideUp(7000);
                });

            }).finally(function(){

                $scope.getHead();

            })

        }



        $scope.deleteHead = function(result, id, headName) {

            //console.log(result);
            //console.log(id);
            if(result == true) {

                $http.delete("/accounts/api/delete-head-data/"+id)
                    .then(function(data){
                        console.log(data.data);
                        if(data.data == 'subHeadExist') {
                            $scope.savingErrorHead = '"'+headName+'" has Sub-head. Delete them first.';
                            $("#savingErrorHead").show().fadeTo(6500, 500).slideUp(500, function () {
                                $("#savingErrorHead").slideUp(7000);
                            });
                        } else if(data.data == 'Deleted') {
                            $scope.savingSuccessHead = 'Head "'+headName+'" deleted successfully.';
                            $("#savingSuccessHead").show().fadeTo(6500, 500).slideUp(500, function () {
                                $("#savingSuccessHead").slideUp(7000);
                            });
                        }
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingErrorHead = 'Something went wrong.';
                        $("#savingErrorHead").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#savingErrorHead").slideUp(7000);
                        });
                    }).finally(function(){
                        $scope.getHead();
                    })

            } else {
                return false;
            }

        }

        //=======================SUB-HEAD=========================
        $scope.subHeadAddBtn = true;
        $scope.subHeadEditBtn = false;

        // $scope.subHeadTable = true;

        $scope.getSubHead = function() {
            $http.get("/accounts/salary/designation/api/get-designation-employee-information")
                .then(function(data){
                    // console.log(data.data);
                    if(data.data.length>0) {

                        $scope.subHeadTable = true;
                        $scope.allDesignationEmployeeData = data.data;
                       console.log($scope.allDesignationEmployeeData);

                    } else {
                        $scope.subHeadTable = false;
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
        $scope.getSubHead();

        $scope.getEmployeeDesignation = function (Employee) {
            console.log(Employee);
             console.log($scope.allDesignationEmployeeData);

            angular.forEach($scope.allDesignationEmployeeData, function(value, key){

                if(value.e_id == Employee){
                    $scope.empCurrentDesignation = value.designation;
                    $scope.show_employee_img= value.photo;
                }

            });
            $scope.previouse_designation = 'Previous Designation:';
            console.log($scope.empCurrentDesignation);


        }

        $scope.postSubHead = function() {
            var data = {
                head_id : $scope.head_id,
                acc_sub_head : $scope.acc_sub_head
            }
            $http.post("/accounts/api/save-sub-head-data", data)
                .then(function(data){
                    $scope.acc_sub_head = null;
                    $scope.savingSuccessSubHead = 'Sub-Head added successfully.';
                    $("#savingSuccessSubHead").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#savingSuccessSubHead").slideUp(7000);
                    });
                }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingErrorSubHead = 'Something went wrong.';
                $("#savingErrorSubHead").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#savingErrorSubHead").slideUp(7000);
                });
            }).finally(function(){
                $scope.getSubHead($scope.head_id);
            })
        }

        $scope.editSubHeadBtn = function(subHead) {
            $scope.subHeadAddBtn = false;
            $scope.subHeadEditBtn = true;
            $scope.acc_sub_head_id = subHead.id;
            $scope.acc_sub_head = subHead.acc_sub_head;
            $scope.head_id = subHead.head_id;
        }

        $scope.editSubHead = function() {
            var data = {
                id : $scope.acc_sub_head_id,
                head_id : $scope.head_id,
                acc_sub_head : $scope.acc_sub_head
            }

            $http.put("/accounts/api/edit-sub-head-data",data)
                .then(function(data) {
                    //console.log(data);
                    $scope.acc_sub_head = null;
                    $scope.subHeadAddBtn = true;
                    $scope.subHeadEditBtn = false;
                    $scope.savingSuccessSubHead = 'Sub-Head edited successfully.';
                    $("#savingSuccessSubHead").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#savingSuccessSubHead").slideUp(7000);
                    });
                }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingErrorSubHead = 'Something went wrong.';
                $("#savingErrorSubHead").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#savingErrorSubHead").slideUp(7000);
                });
            }).finally(function(){
                $scope.getSubHead($scope.head_id);
            })
        }


        $scope.deleteDegEmployee = function (DegEmployee) {


            var id =  DegEmployee.emp_deg_id;
            var  designationName = DegEmployee.designation;

            console.log(id);
            console.log(designationName);

            bootbox.confirm({
                message: "Do you want to delete?",
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
                    $scope.deleteEmployeeDesignation(result, id, designationName);
                }
            }).css({
                'text-align':'center',
                'top':'0',
                'bottom': '0',
                'left': '0',
                'right': '0',
                'margin': 'auto'
            });


            $scope.deleteEmployeeDesignation = function(result, id, designationName){

                if(result == true) {
                    $http.delete("/accounts/salary/designation/api/delete-employee-designation/"+id)
                        .then(function(data){
                            //console.log(data.data);
                            $scope.saveDegnationSuccess = 'Designation "'+designationName+'" deleted successfully.';
                            $("#saveDegnationSuccess").show().fadeTo(6500, 500).slideUp(500, function () {
                                $("#saveDegnationSuccess").slideUp(7000);
                            });
                        }).catch(function(r){
                        console.log(r)
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        } else {
                            $.growl.error({message: "It has Some Error!"});
                        }
                        $scope.saveDegnationError = 'Something went wrong.';
                        $("#saveDegnationError").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#saveDegnationError").slideUp(7000);
                        });
                    }).finally(function(){
                        $scope.getSubHead();
                    })

                }else {
                    return false;
                }
            }
        }

        $scope.deleteDegnation = function (designation) {

            var id = designation.id;
            var designationName = designation.designation;

            console.log(designationName);
            console.log(id);

            bootbox.confirm({
                message: "Do you want to delete?",
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

                    $scope.deleteOnlyDesignation(result, id, designationName);

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

        $scope.deleteOnlyDesignation = function(result, id, designationName) {
            if(result == true) {
                $http.delete("/accounts/salary/designation/api/delete-designation/"+id)
                    .then(function(data){
                        console.log(data.data);
                        if(data.data == 'DesignationExist') {
                            $scope.savingSuccess = '"'+designationName+'" Exist in Employee Designation. Delete them first.';
                            $("#savingSuccess").show().fadeTo(6500, 500).slideUp(500, function () {
                                $("#savingSuccess").slideUp(7000);
                            });

                        } else if(data.data == 'Deleted') {

                            $scope.savingSuccess = 'Designation "'+designationName+'" deleted successfully.';
                            $("#savingSuccess").show().fadeTo(6500, 500).slideUp(500, function () {
                                $("#savingSuccess").slideUp(7000);
                            });
                        }
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                    $scope.savingError = 'Something went wrong.';
                    $("#savingError").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#savingError").slideUp(7000);
                    });
                }).finally(function(){
                    $scope.getHead();
                })
            }else {
                return false;
            }
        }
    });