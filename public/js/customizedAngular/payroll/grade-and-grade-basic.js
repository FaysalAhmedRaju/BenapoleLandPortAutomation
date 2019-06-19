angular.module('gradeApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('GradeController', function($http, $scope,enterKeyService){
        $scope.grade_list = grade_list;
        console.log($scope.grade_list);

        var min = new Date().getFullYear()-5;
        var	max = min + 10;
        $scope.years = [];
        var j=0;
        for (var i = min; i<=max; i++){
            $scope.years[j++] = {value: i, text: i};
        }

        $scope.getGradeDataList = function() {
            $http.get("/accounts/salary/grade-basic/api/get-all-grade-data-details")
                .then(function(data) {
                    console.log(data);
                    if(data.data.length>0) {
                        $scope.headTable = true;
                        $scope.allDegsignationData = data.data;
                        $scope.head_id = data.data[0].id;
                        $scope.getGradeBasicDataList($scope.head_id)
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
        $scope.getGradeDataList();




        // $http.get("/accounts/salary/designation/api/get-select-year")
        //     .then(function(data) {
        //         //$scope.salary_year = data.data[0].scale_year;
        //         $scope.scale_year = data.data[0].salary;
        //         $scope.scaleYear = true;
        //         //console.log($scope.salary_year);
        //         console.log( $scope.scale_year_data);
        //
        //     }).catch(function(r) {
        //     console.log(r)
        //     if (r.status == 401) {
        //         $.growl.error({message: r.data});
        //     } else {
        //         $.growl.error({message: "It has Some Error!"});
        //     }
        // }).finally(function() {
        //
        // });

        enterKeyService.enterKey('#Employeeform input ,#Employeeform button')
        enterKeyService.enterKey('#GradeForm input ,#GradeForm button')


        $scope.HeadAddBtn = true;
        $scope.HeadEditBtn = false;

        $scope.saveUpdateGrade = function(grade_name) {
            if($scope.validationGrade()==false)
            {
                return;
            }
            console.log($scope.grade_id);
            // if($scope.grade_id == undefined){
            //     $scope.grade_id = null;
            //
            // }
            var data = {
                grade_id : $scope.grade_id,
                grade_name : grade_name
            }
            console.log(data)
            $http.post("/accounts/salary/grade-basic/api/save-grade-data",data)
                .then(function(data) {
                    console.log(data);
                    console.log(data.data);

                    if(data.data == 'Success'){
                        $scope.savingSuccess = 'Save successfully.';
                        $("#savingSuccess").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#savingSuccess").slideUp(7000);
                        });

                        $scope.grade_name = null;
                        $scope.grade_id = null;
                    }else {
                           $scope.HeadAddBtn = true;
                           $scope.HeadEditBtn = false;
                          $scope.savingSuccessUpdate = 'Update successfully.';
                           $("#savingSuccessUpdate").show().fadeTo(6500, 500).slideUp(500, function () {
                              $("#savingSuccessUpdate").slideUp(7000);
                           });

                        $scope.grade_name = null;
                        $scope.grade_id = null;
                    }


                }).catch(function(r){
                $scope.savingError = 'Something went wrong.';
                $("#savingError").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#savingError").slideUp(7000);
                });
            }).finally(function(){
                $scope.getGradeDataList();
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

                // console.log( $scope.allGoodsDataCnf);
            }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });


        $scope.saveUpdateBasicGrade = function () {

            if($scope.validationEmployeeDesignation()==false)
            {
                return;
            }

            var data={
                grade_basic_id : $scope.g_basic_id,
                grade : $scope.grade,
                basic_level : $scope.basic_level,
                basic_salary : $scope.basic_salary != null ? $scope.basic_salary : 0,
                scale_year : $scope.scale_year
            }
            console.log(data)
            $http.post("/accounts/salary/grade-basic/api/save-update-grade-basic-data",data)
                .then(function (data) {
                    console.log(data);

                    if(data.data == 'Success'){
                        $scope.saveDegnationSuccess='Saved Successfully.';
                        $("#saveDegnationSuccess").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#saveDegnationSuccess").slideUp(7000);
                        });
                        $scope.blankGradeBasic();

                    }else {
                        $scope.updateBtn = false;
                        $scope.saveDegnationSuccess='Update Successfully.';
                        $("#saveDegnationSuccess").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#saveDegnationSuccess").slideUp(7000);
                        });
                        $scope.blankGradeBasic();
                    }




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
                $scope.getGradeBasicDataList();

            })
        }

        $scope.validationGrade = function() {

            if($scope.GradeForm.$invalid) {

                $scope.submittedGrade = true;
                return false;

            } else {

                $scope.submittedGrade = false;
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
            console.log(value);
            $scope.HeadAddBtn = false;
            $scope.HeadEditBtn = true;
            $scope.grade_id = value.id;
            $scope.grade_name = value.grade_name;

        }


        $scope.editDegEmployee =function (gradeBasic) {
            console.log(gradeBasic);

            $scope.updateBtn = true;
            $scope.g_basic_id = gradeBasic.g_basic_id;
            $scope.grade = gradeBasic.g_id;
            $scope.basic_level = gradeBasic.level;
            $scope.basic_salary = parseFloat(gradeBasic.basic);
            $scope.scale_year = gradeBasic.scale_year;


            console.log($scope.g_basic_id);

        }
        $scope.blankGradeBasic = function() {

            $scope.grade_basics_id = null;
            $scope.basic_salary = null;
            $scope.grade = null;
            $scope.basic_level = null;
            $scope.scale_year = null;
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
                    $scope.getGradeDataList();
                })

            } else {
                return false;
            }

        }

        //=======================SUB-HEAD=========================
        $scope.subHeadAddBtn = true;
        $scope.subHeadEditBtn = false;

        // $scope.subHeadTable = false;

        $scope.getGradeBasicDataList = function() {
            $http.get("/accounts/salary/grade-basic/api/get-all-grade-basic-data")
                .then(function(data){
                    console.log(data);
                    if(data.data.length>0) {

                        // $scope.subHeadTable = false;
                        $scope.allDesignationEmployeeData = data.data;

                    } else {
                        // $scope.subHeadTable = false;
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
        $scope.getGradeBasicDataList();

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
                $scope.getGradeBasicDataList($scope.head_id);
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
                $scope.getGradeBasicDataList($scope.head_id);
            })
        }


        $scope.deleteGradeBasic = function (GradeBasic) {


            var id =  GradeBasic.g_basic_id;


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
                    $scope.deleteGradeBasicData(result, id);
                }
            }).css({
                'text-align':'center',
                'top':'0',
                'bottom': '0',
                'left': '0',
                'right': '0',
                'margin': 'auto'
            });


            $scope.deleteGradeBasicData = function(result, id){

                if(result == true) {
                    $http.delete("/accounts/salary/grade-basic/api/delete-grade-basic-data/"+id)
                        .then(function(data){
                            console.log(data.data);
                            $scope.saveDegnationSuccess = 'Deleted successfully.';
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
                        $scope.getGradeBasicDataList();
                    })

                }else {
                    return false;
                }
            }
        }

        $scope.deleteGrade = function (value) {

            var id = value.id;
            var grade_name = value.grade_name;

            console.log(grade_name);
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

                    $scope.deleteOnlyGrade(result, id, grade_name);

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

        $scope.deleteOnlyGrade = function(result, id, grade_name) {
            if(result == true) {
                $http.delete("/accounts/salary/grade-basic/api/delete-grade-data/"+id)
                    .then(function(data){
                        console.log(data.data);
                       if(data.data == 'Deleted') {

                            $scope.savingSuccess = 'Grade "'+grade_name+'" deleted successfully.';
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
                    $scope.getGradeDataList();
                })
            }else {
                return false;
            }
        }
    });