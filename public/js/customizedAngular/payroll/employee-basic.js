angular.module('HomeRentalAllowanceApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('HomeRentalAllowanceCtrl', function ($scope, $http,enterKeyService) {

        //Fixed Facilities and Deduction Start
        $scope.btnSaveFixed = true;
        $scope.btnUpdateFixed = false;
        $scope.employeeBasicTable = true;
        $scope.grade_list = grade_list;
        console.log($scope.grade_list);
        $scope.home_rent_show = false;

        $http.get("/accounts/salary/designation/api/get-employees-information") //Employee dropdown list shown...need employee_id
            .then(function (data) {
               console.log(data)
                $scope.getEmployeesInfoData=data.data;
              console.log($scope.getEmployeesInfoData);
            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
        }).finally(function () {
        });

        //getGradeBasic Start------------------
        $scope.getGradeBasic = function (grade) {
            console.log(grade);
            console.log($scope.scale_year);
            if($scope.scale_year != undefined){
               // console.log("now i am execute")
                $http.get("/accounts/salary/employee-basic/api/get-grade-basic-data/" + grade +"/"+$scope.scale_year)
                    .then(function (data) {
                        console.log(data);

                        $scope.gradeBasicData = data.data;

                    }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }

                }).finally(function () {

                })
            }

        }
        //getGradeBasic End------------------


        $scope.getHomeRent = function (grade_basic) {
            console.log(grade_basic);
            console.log($scope.scale_year);
           if(grade_basic != undefined){
               console.log(grade_basic);
               $http.get("/accounts/salary/employee-basic/api/get-house-rent-data/" + grade_basic)
                   .then(function (data) {
                       console.log(data.data);
                       console.log(data.data[0]);//homeRent
                       console.log(data.data[1]);//basic

                       $scope.homeRentData = data.data[0][0];

                       console.log($scope.homeRentData);
                       $scope.basic_salary = data.data[1][0].basic;
                       $scope.dhaka_metro_politon_area_rate = data.data[0][0].dhaka_metro_politon_area_rate;
                       $scope.dhaka_metro_politon_area_limit = data.data[0][0].dhaka_metro_politon_area_limit;
                       $scope.expensive_area_rate = data.data[0][0].expensive_area_rate;
                       $scope.expensive_area_limit = data.data[0][0].expensive_area_limit;
                       $scope.other_area_rate = data.data[0][0].other_area_rate;
                       $scope.other_area_limit = data.data[0][0].other_area_limit;
                       $scope.houseRentID = data.data[0][0].id;



                   }).catch(function (r) {
                   console.log(r)
                   if (r.status == 401) {
                       $.growl.error({message: r.data});
                   } else {
                       $.growl.error({message: "It has Some Error!"});
                   }

               }).finally(function () {

               })
           }

        }

        $scope.getHomeArea = function (employeeArea) {
            console.log(employeeArea);

           // console.log( $scope.getEmployeesInfoData);
            $scope.employeeHouse_area_flag=null;
            $scope.employee_photo = null;
            angular.forEach($scope.getEmployeesInfoData, function(itm){
               // console.log($scope.employeeHouse_area_flag)

                if(itm.id == employeeArea){
                    //console.log("i am in"+itm.house_area_flag)
                    $scope.employeeHouse_area_flag = itm.house_area_flag;
                    $scope.employee_photo = itm.photo;
                }


            });
            console.log($scope.employee_photo);
            console.log($scope.employeeHouse_area_flag);
            if($scope.employeeHouse_area_flag != undefined){
                if($scope.employeeHouse_area_flag == 0){

                    $scope.home_rent_show = false;

                }else {
                    if($scope.employeeHouse_area_flag == 1){ //Dhaka Metro Politon Area
                        console.log( $scope.basic_salary);
                        console.log($scope.dhaka_metro_politon_area_rate);
                        console.log($scope.dhaka_metro_politon_area_limit)

                        $scope.total_home_rent = ($scope.basic_salary * $scope.dhaka_metro_politon_area_rate) / 100;
                        if($scope.total_home_rent > $scope.dhaka_metro_politon_area_limit){
                            $scope.final_home_rent = $scope.total_home_rent;
                        }else {
                            $scope.final_home_rent = $scope.dhaka_metro_politon_area_limit;
                        }
                        console.log($scope.final_home_rent)
                        $scope.home_rent_show = true;


                    }else if($scope.employeeHouse_area_flag == 2){ //Expensive Area
                        console.log( $scope.basic_salary);
                        console.log($scope.expensive_area_rate);
                        console.log($scope.expensive_area_limit);

                        $scope.total_home_rent = ($scope.basic_salary * $scope.expensive_area_rate) / 100;

                        if($scope.total_home_rent > $scope.expensive_area_limit){
                            $scope.final_home_rent = $scope.total_home_rent;
                        }else {
                            $scope.final_home_rent = $scope.expensive_area_limit;
                        }
                        console.log($scope.final_home_rent)
                        $scope.home_rent_show = true;



                    }else if($scope.employeeHouse_area_flag == 3) { //Other Area
                        console.log($scope.basic_salary);
                        console.log($scope.other_area_rate);
                        console.log($scope.other_area_limit)

                        $scope.total_home_rent = ($scope.basic_salary * $scope.other_area_rate) / 100;

                        if($scope.total_home_rent > $scope.other_area_limit){
                            $scope.final_home_rent = $scope.total_home_rent;
                        }else {
                            $scope.final_home_rent = $scope.other_area_limit;
                        }
                        console.log($scope.final_home_rent)
                        $scope.home_rent_show = true;

                    }else {
                        $scope.home_rent_show = false;
                        $scope.final_home_rent = null;
                    }
                }

            }






        }




        $http.get("/accounts/salary/employee-basic/api/get-scale-year-data")
            .then(function(data) {
                console.log(data);
                $scope.scale_yearData = data.data;

            }).catch(function(r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
        }).finally(function() {

        });





        // var min = new Date().getFullYear()-5;
        // var	max = min + 10;
        // $scope.years = [];
        // var j=0;
        // for (var i = min; i<=max; i++){
        //     $scope.years[j++] = {value: i, text: i};
        // }

        $scope.blankFixed = function() {
            $scope.fixed_id = null;
            $scope.house_rent = null;
            $scope.education = null;
            $scope.medical = null;
            $scope.tiffin = null;
            $scope.gpf = null;
            $scope.revenue = null;
            $scope.scale_year = null;
        }

        enterKeyService.enterKey('#FixedFacilitiesAndDeduction input ,#FixedFacilitiesAndDeduction button')
        enterKeyService.enterKey('#MonthlyDedudction input ,#MonthlyDedudction button')


        $scope.getAllData = function() {
            $http.get("/accounts/salary/employee-basic/api/get-all-employee-basic-data")
                .then(function(data){
                    console.log(data.data);

                    $scope.homeAllowanceData = data.data;


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
        $scope.getAllData();

        $scope.validationFixed = function() {
            if($scope.FixedFacilitiesAndDeduction.$invalid) {
                $scope.submittedFixed = true;
                return false;
            } else {
                $scope.submittedFixed = false;
                return true;
            }
        }





        $scope.MonthlyDeductionDisabled = true;


        $scope.btnSaveMonthlyDeduction = true;
        $scope.btnUpdateMonthlyDeduction = false;
        $scope.select = 'id';
        var date, d, m, y;






        $scope.blank = function() {

            $scope.employeeBasicID = null;
           // $scope.home_rent = null;
            $scope.scale_year = null;
            $scope.Employee = null;
            $scope.grade_basic = null;
            $scope.grade = null;
            $scope.final_home_rent = null;
            $scope.employee_photo = null;

        }



        $scope.validationEmployeeBasic = function() {
            if($scope.houseRentAllowance.$invalid) {
                $scope.submittedHouseRentalAllowance = true;
                return false;
            } else {
                $scope.submittedHouseRentalAllowance = false;
                return true;
            }
        }

        $scope.saveEmployeeBasic = function() {
            //$scope.final_home_rent = null;
            console.log($scope.final_home_rent);
            // if(($scope.final_home_rent == undefined) || ($scope.final_home_rent == null)){
            //     $scope.savingErrorHouseAllowance = 'Insert Home Rent';
            //     $("#savingErrorHouseAllowance").show().delay(5000).slideUp(1000);
            //     return;
            // }

            if($scope.validationEmployeeBasic()==false) {
                return;
            }
            $scope.dataLoadingMonthlyDeduction = true;
            var data = {
                employeeBasicID : $scope.employeeBasicID,
                grade : $scope.grade,
                grade_basic : $scope.grade_basic,
                Employee : $scope.Employee,
                scale_year : $scope.scale_year,
                home_rent : $scope.final_home_rent,
                houseRentID :  $scope.houseRentID
            }
            console.log(data);

            $http.post("/accounts/salary/employee-basic/api/employee-basic-save-data", data)
                .then(function(data){

                    console.log(data.status);

                    console.log(data.data);
                    if(data.data == 'Updated'){
                        $scope.savingSuccessHouseAllowance = 'Updated successfully.'
                        $("#savingSuccessHouseAllowance").show().delay(5000).slideUp(1000);
                        $scope.blank();
                        $scope.home_rent_show = false;
                        $scope.btnSaveMonthlyDeduction = true;
                        $scope.btnUpdateMonthlyDeduction = false;
                    }else if(data.data == 'Success') {
                        $scope.savingSuccessHouseAllowance = 'Saved successfully.'
                        $("#savingSuccessHouseAllowance").show().delay(5000).slideUp(1000);
                        $scope.blank();
                        $scope.home_rent_show = false;
                        $scope.btnSaveMonthlyDeduction = true;
                        $scope.btnUpdateMonthlyDeduction = false;
                    }else if(data.data == 'DuplicateSave') {
                        $scope.savingErrorHouseAllowance = 'Duplicate Can Not Entry.'
                        $("#savingErrorHouseAllowance").show().delay(5000).slideUp(1000);
                        $scope.btnSaveMonthlyDeduction = true;
                        $scope.btnUpdateMonthlyDeduction = false;
                    }else {
                        $scope.savingErrorHouseAllowance = 'Duplicate Can Not Entry.'
                        $("#savingErrorHouseAllowance").show().delay(5000).slideUp(1000);
                        $scope.btnSaveMonthlyDeduction = false;
                        $scope.btnUpdateMonthlyDeduction = true;
                    }


                    $scope.getAllData();



                }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingErrorHouseAllowance = 'Something went wrong.'
                $("#savingErrorHouseAllowance").show().delay(5000).slideUp(1000);
            }).finally(function(){
                $scope.dataLoadingMonthlyDeduction = false;
            })
        }

        $scope.pressUpdateBtn = function(allowance) {
            console.log(allowance);
            $scope.employeeBasicID = allowance.id;
            $scope.final_home_rent = allowance.house_rent;
            console.log($scope.final_home_rent)
            if($scope.final_home_rent){
                $scope.home_rent_show = true;
            }
            console.log($scope.home_rent_area)



            $scope.scale_year = allowance.scale_year;
            $scope.Employee = allowance.employee_id;
            $scope.grade_basic = allowance.grade_basics_id;
            $scope.grade = allowance.grade_id;


            $scope.getGradeBasic($scope.grade);
            $scope.getHomeRent($scope.grade_basic);
            $scope.btnSaveMonthlyDeduction = false;
            $scope.btnUpdateMonthlyDeduction = true;


        }


        $scope.pressDeleteBtn = function (allowance) {
            console.log(allowance)


            var id =  allowance.id;


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
                    $scope.deleteBasic(result, id);
                }
            }).css({
                'text-align':'center',
                'top':'0',
                'bottom': '0',
                'left': '0',
                'right': '0',
                'margin': 'auto'
            });


            $scope.deleteBasic = function(result, id){

                if(result == true) {
                    $http.delete("/accounts/salary/employee-basic/api/delete-employee-basic-data/"+id)
                        .then(function(data){
                            console.log(data.data);
                            $scope.savingSuccessHouseAllowance = 'Deleted successfully.';
                            $("#savingSuccessHouseAllowance").show().fadeTo(6500, 500).slideUp(500, function () {
                                $("#savingSuccessHouseAllowance").slideUp(7000);
                            });
                        }).catch(function(r){
                        console.log(r)
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        } else {
                            $.growl.error({message: "It has Some Error!"});
                        }
                        $scope.savingErrorHouseAllowance = 'Something went wrong.';
                        $("#savingErrorHouseAllowance").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#savingErrorHouseAllowance").slideUp(7000);
                        });
                    }).finally(function(){
                        $scope.getAllData();
                    })

                }else {
                    return false;
                }
            }
        }

    }).directive('autocomplete', ['autocomplete-keys', '$window', '$timeout', function(Keys, $window, $timeout) {
    return {
        template: '<input type="text" id="mm" class="autocomplete-input" placeholder="{{placeHolder}}"' +
        'ng-class="inputClass"' +
        'ng-model="searchTerm"' +
        'ng-keydown="keyDown($event)"' +
        'ng-blur="onBlur()" />' +

        '<div class="autocomplete-options-container">' +
        '<div class="autocomplete-options-dropdown" ng-if="showOptions">' +
        '<div class="autocomplete-option" ng-if="!hasMatches">' +
        '<span style="color:red;">No Matches Found</span>' +
        '</div>' +

        '<ul class="autocomplete-options-list">' +
        '<li class="autocomplete-option" ng-class="{selected: isOptionSelected(option)}" ' +
        'ng-style="{width: optionWidth}"' +
        'ng-repeat="option in matchingOptions"' +
        'ng-mouseenter="onOptionHover(option)"' +
        'ng-mousedown="selectOption(option)"' +
        'ng-if="!noMatches">' +
        '<span>{{option[displayProperty]}}</span>' +
        '</li>' +
        '</ul>' +
        '</div>' +
        '</div>',
        restrict: 'E',
        scope: {
            options: '=',
            onSelect: '=',
            displayProperty: '@',
            inputClass: '@',
            clearInput: '@',
            placeHolder: '@'
        },
        controller: function($scope){
            $scope.searchTerm = '';
            $scope.highlightedOption = null;
            $scope.showOptions = false;
            $scope.matchingOptions = [];
            $scope.hasMatches = false;
            $scope.selectedOption = null;

            $scope.isOptionSelected = function(option) {
                return option === $scope.highlightedOption;
            };

            $scope.processSearchTerm = function(term) {
                // console.log('ch-ch-ch-changin');
                if (term.length > 0) {
                    if ($scope.selectedOption) {
                        if (term != $scope.selectedOption[$scope.displayProperty]) {
                            $scope.selectedOption = null;
                        } else {
                            $scope.closeAndClear();
                            return;
                        }
                    }

                    var matchingOptions = $scope.findMatchingOptions(term);
                    $scope.matchingOptions = matchingOptions;
                    if (!$scope.matchingOptions.indexOf($scope.highlightedOption) != -1) {
                        $scope.clearHighlight();
                    }
                    $scope.hasMatches = matchingOptions.length > 0;
                    $scope.showOptions = true;
                } else {
                    $scope.closeAndClear();
                }
            };

            $scope.findMatchingOptions = function(term) {
                return $scope.options.filter(function(option) {
                    var searchProperty = option[$scope.displayProperty];
                    if (searchProperty) {
                        var lowerCaseOption = searchProperty.toLowerCase();
                        var lowerCaseTerm = term.toLowerCase();
                        return lowerCaseOption.indexOf(lowerCaseTerm) != -1;
                    }
                    return false;
                });
            };

            $scope.findExactMatchingOptions = function (term) {


                return $scope.options.filter(function(option) {
                    var lowerCaseOption = option[$scope.displayProperty].toLowerCase();
                    var lowerCaseTerm = term.toLowerCase();
                    return lowerCaseOption == lowerCaseTerm;
                });
            };

            $scope.keyDown = function(e) {
                switch(e.which) {
                    case Keys.upArrow:
                        e.preventDefault();
                        if ($scope.showOptions) {
                            $scope.highlightPrevious();
                        }
                        break;
                    case Keys.downArrow:
                        e.preventDefault();
                        if ($scope.showOptions) {
                            $scope.highlightNext();
                        } else {
                            $scope.showOptions = true;
                            if ($scope.selectedOption) {
                                $scope.highlightedOption = $scope.selectedOption;
                            }
                        }
                        break;
                    case Keys.enter:
                        e.preventDefault();
                        if ($scope.highlightedOption) {
                            $scope.selectOption($scope.highlightedOption);
                        } else {
                            var exactMatches = $scope.findExactMatchingOptions($scope.searchTerm);
                            if (exactMatches[0]) {
                                $scope.selectOption(exactMatches[0]);
                            }
                        }
                        break;
                    case Keys.escape:
                        $scope.closeAndClear();
                        break;
                }
            };

            $scope.$watch('searchTerm', function (term) {

                $scope.processSearchTerm(term);

            });

            $scope.highlightNext = function() {
                if (!$scope.highlightedOption) {
                    $scope.highlightedOption = $scope.matchingOptions[0];
                } else {
                    var currentIndex = $scope.currentOptionIndex();
                    var nextIndex = currentIndex + 1 == $scope.matchingOptions.length ? 0 : currentIndex + 1;
                    $scope.highlightedOption = $scope.matchingOptions[nextIndex];
                }
            };

            $scope.highlightPrevious = function() {
                if (!$scope.highlightedOption) {
                    $scope.highlightedOption = $scope.matchingOptions[$scope.matchingOptions.length - 1];
                } else {
                    var currentIndex = $scope.currentOptionIndex();
                    var previousIndex = currentIndex == 0 ? $scope.matchingOptions.length - 1 : currentIndex - 1;
                    $scope.highlightedOption = $scope.matchingOptions[previousIndex];
                }
            };

            $scope.onOptionHover = function(option) {
                $scope.highlightedOption = option;
            };

            $scope.$on('simple-autocomplete:clearInput', function() {
                $scope.searchTerm = '';
            });

            $scope.clearHighlight = function() {
                $scope.highlightedOption = null;
            };

            $scope.closeAndClear = function() {
                $scope.showOptions = false;
                $scope.clearHighlight();
            };

            $scope.selectOption = function(option) {

                $scope.selectedOption = option;
                $scope.onSelect(option);

                if ($scope.clearInput != 'False' && $scope.clearInput != 'false') {
                    $scope.searchTerm = '';
                } else {
                    $scope.searchTerm = option[$scope.displayProperty];
                }

                $scope.closeAndClear();
            };

            $scope.onBlur = function() {
                $scope.closeAndClear();
            };

            $scope.currentOptionIndex = function() {
                return $scope.matchingOptions.indexOf($scope.highlightedOption);
            };
        },
        link: function(scope, elem, attrs) {
            scope.optionWidth = '400px';
            var inputElement = elem.children('.autocomplete-input')[0];

            scope.setOptionWidth = function() {
                // console.log(inputElement.offsetWidth);
                $timeout(function() {
                    var pixelWidth = inputElement.offsetWidth > 400 ? 400 : inputElement.offsetWidth - 2;
                    scope.optionWidth = pixelWidth + 'px';
                });
            };

            angular.element(document).ready(function() {
                scope.setOptionWidth();
            });

            angular.element($window).bind('resize', function() {
                scope.setOptionWidth();
            });
        }
    };
}]).factory('autocomplete-keys', function () {
    return {
        upArrow: 38,
        downArrow: 40,
        enter: 13,
        escape: 27
    };
}).filter('numberFilter', function () {
    return function (val) {
        var number;
        if(val==0){
            return number='';
        } else {
            return number = val;
        }
        return number = '';
    }
});