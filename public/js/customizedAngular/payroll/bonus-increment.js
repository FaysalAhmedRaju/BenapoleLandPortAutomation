angular.module('BonusAndIncrementApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('BonusAndIncrementController', function($http, $scope,enterKeyService){
        $scope.incrementDisabled = true;

        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        $scope.getBonusData = function() {
            $http.get("/accounts/salary/bonous-increment/api/get-bonus-data")
                .then(function(data) {
                    console.log(data);
                    if(data.data.length>0) {
                       // console.log(data);
                        $scope.BonusTable = true;
                        $scope.allBonusData = data.data;
                        // $scope.head_id = data.data[0].id;
                        // $scope.getSubHead(head_id)
                    }else {
                        $scope.BonusTable = false;
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
        };
        $scope.getBonusData();

        enterKeyService.enterKey('#BonusForm input ,#BonusForm button')
        enterKeyService.enterKey('#IncrementForm input ,#IncrementForm button')


        $scope.SaveUpdateBtn = false;
        $scope.SaveBonusBtn = true;

        $scope.editBonous =function (value) {

            date = new Date(value.date);
            formatedYearMonth = monthNames[date.getMonth()] +" "+ date.getFullYear();
            // employee.month_year | date : "MMMM yy"

            $scope.SaveUpdateBtn = true;
            $scope.SaveBonusBtn = false;

            $scope.Employee_bonus = value.e_id;
            $scope.type_name = value.type;

            $scope.bonus_data = formatedYearMonth;

            // $scope.basic_salary = parseFloat(DegEmployee.basic);
            $scope.Amount_salary =parseFloat(value.amount);
            $scope.id = value.bonus_id;
            console.log($scope.id)


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





        $http.get("/accounts/salary/designation/api/get-select-year")
            .then(function(data) {
                //$scope.salary_year = data.data[0].scale_year;
                $scope.scale_year = data.data[0].salary;
                $scope.scaleYear = true;
                //console.log($scope.salary_year);
                // console.log( $scope.scale_year_data);

            }).catch(function(r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
        }).finally(function() {

        });



        $http.get("/accounts/salary/designation/api/get-employees-information")
            .then(function (data){
                $scope.getEmployeesInfoDataForBonus=data.data;

            }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });

        $http.get("/accounts/salary/bonous-increment/api/get-employee-increment-information")
            .then(function (data){
                $scope.getEmpInfoIncrement=data.data;

            }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });
//-------------------------------------------------------------------------------------------------------------------------------------------------------save Bonus------------
        $scope.SaveBonus = function () {


            if($scope.validationBonus()==false)
            {
                return;
            }

            date = new Date($scope.bonus_data);
            d = date.getDate();
            m = date.getMonth()+1;
            y = date.getFullYear();

            var data={
                Employee_bonus:$scope.Employee_bonus,
                type_name:$scope.type_name,
                // month_year : y + "-" + m + "-" + d,
                bonus_data: y + "-" + m + "-" + d,
                Amount_salary:$scope.Amount_salary
            }
             console.log(data)
            $http.post("/accounts/salary/bonous-increment/api/save-bonus-data",data)
                .then(function (data) {
                     //console.log(data.data);
                    $scope.SuccessBonus='Saved Successfully.';
                    $("#SuccessBonus").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#SuccessBonus").slideUp(7000);
                    });
                    $scope.Employee_bonus = null;
                    $scope.type_name = null;
                    $scope.bonus_data = null;
                    $scope.Amount_salary = null;
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.bonusdError='Something wet worng!';
                $("#bonusdError").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#bonusdError").slideUp(7000);
                });
            }).finally(function () {
                $scope.getBonusData();
            })

        }

        $scope.validationBonus = function() {

            if($scope.BonusForm.$invalid) {

                $scope.submittedFixedBonus = true;
                return false;

            } else {

                $scope.submittedFixedBonus = false;
                return true;

            }

        }

        $scope.validationIncrement = function() {

            if($scope.IncrementForm.$invalid) {

                $scope.submittedFixedIncrement = true;
                return false;

            } else {

                $scope.submittedFixedIncrement = false;
                return true;

            }

        }



        $scope.updateBonus = function (value) {
            if($scope.validationBonus()==false)
            {
                return;
            }

            date = new Date($scope.bonus_data);
            d = date.getDate();
            m = date.getMonth()+1;
            y = date.getFullYear();
            var data={
                Employee_bonus:$scope.Employee_bonus,
                type_name: $scope.type_name,
                // bonus_data:$scope.bonus_data,
                bonus_data:y + "-" + m + "-" + d,
                Amount_salary:$scope.Amount_salary,
                id:$scope.id

            }
            console.log(data);
            $http.put("/accounts/salary/bonous-increment/api/update-bonous-data",data)
                .then(function(data) {
                    $scope.SaveUpdateBtn = false;
                    $scope.SaveBonusBtn = true;
                    $scope.SuccessIncreaseUpdate = 'Update successfully.';
                    $("#SuccessIncreaseUpdate").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#SuccessIncreaseUpdate").slideUp(7000);
                    });

                    $scope.Employee_bonus = null;
                    $scope.type_name = null;
                    $scope.bonus_data = null;
                    $scope.Amount_salary = null;



                }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.ErrorIncreaseUpdate = 'Something went wrong.';
                $("#ErrorIncreaseUpdate").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#ErrorIncreaseUpdate").slideUp(7000);
                });
            }).finally(function(){
                $scope.getBonusData();
            })
        }
        $scope.BonusSavebtn = true;
        $scope.BonusUpdatebtn = false;

        $scope.editIncrement = function(value) {

            date = new Date(value.date);
            formatedYearMonth = monthNames[date.getMonth()] +" "+ date.getFullYear();

            $scope.incrementUpdatebtn = true;
            $scope.incrementSavebtn = false;

            $scope.employee_increment = value.incre_emp_id;
            $scope.type_name_increment = value.type;
            $scope.Amount_salary_increment = parseFloat(value.amount);
            $scope.increment_date = formatedYearMonth;
            $scope.id = value.increment_id;

        }

        // $scope.incrementUpdatebtn = false;
        $scope.incrementSavebtn = true;



//-----------------------------------------------------------------------------------------------------------------------------------------------------------save Increment-------



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


        $scope.deleteHead = function(result, id, headName) {
            //console.log(result);
            //console.log(id);
            if(result == true) {
                $http.delete("/accounts/api/delete-head-data/"+id)
                    .then(function(data){
                        // console.log(data.data);
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


        $scope.deleteBonus = function (value) {
                var id = value.bonus_id;
            // var designationName = designation.designation;
            // console.log(designationName);
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

                    $scope.deleteOnlyBonus(result, id);

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

        $scope.deleteOnlyBonus = function(result, id) {
            if(result == true) {
                $http.delete("/accounts/salary/bonous-increment/api/delete-bonus-data/"+id)
                    .then(function(data){
                        // console.log(data.data);
                        // if(data.data == 'DesignationExist') {
                        //     $scope.savingSuccess = '"'+designationName+'" Exist in Employee Designation. Delete them first.';
                        //     $("#savingSuccess").show().fadeTo(6500, 500).slideUp(500, function () {
                        //         $("#savingSuccess").slideUp(7000);
                        //     });
                        //
                        // } else
                        if(data.data == 'Deleted') {

                            $scope.SuccessBonus = 'Deleted successfully.';
                            $("#SuccessBonus").show().fadeTo(6500, 500).slideUp(500, function () {
                                $("#SuccessBonus").slideUp(7000);
                            });
                        }
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                    $scope.bonusdError = 'Something went wrong.';
                    $("#bonusdError").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#bonusdError").slideUp(7000);
                    });

                }).finally(function(){

                    $scope.getBonusData();

                })
            }else {
                return false;
            }
        }


    });