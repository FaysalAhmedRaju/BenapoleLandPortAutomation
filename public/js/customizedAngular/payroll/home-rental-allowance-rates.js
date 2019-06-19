angular.module('HomeRentalAllowanceApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('HomeRentalAllowanceCtrl', function ($scope, $http,enterKeyService) {

        //Fixed Facilities and Deduction Start
        $scope.btnSaveFixed = true;
        $scope.btnUpdateFixed = false;

        $scope.ifMaximumSalaryDisable = false;


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
          //  $scope.scale_year = null;
        }

        enterKeyService.enterKey('#FixedFacilitiesAndDeduction input ,#FixedFacilitiesAndDeduction button')
        enterKeyService.enterKey('#MonthlyDedudction input ,#MonthlyDedudction button')

        $scope.maximumSalaryCheckBox = function (maximum_salary) {

            console.log(maximum_salary);

            if(maximum_salary == true){
                $scope.ifMaximumSalaryDisable = true;
                $scope.salary_last_range = -1;

            }else {
                $scope.ifMaximumSalaryDisable = false;
            }

        }


        $scope.getHomeRentalAllowanceData = function() {
            $http.get("/accounts/salary/home-rental-allowance/api/get-home-rental-allowance-rates-data")
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
        $scope.getHomeRentalAllowanceData();

        $scope.validationFixed = function() {
            if($scope.FixedFacilitiesAndDeduction.$invalid) {
                $scope.submittedFixed = true;
                return false;
            } else {
                $scope.submittedFixed = false;
                return true;
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





        $scope.MonthlyDeductionDisabled = true;


        $scope.btnSaveMonthlyDeduction = true;
        $scope.btnUpdateMonthlyDeduction = false;
        $scope.select = 'id';
        var date, d, m, y;






        $scope.blankHomeAllowance = function() {
            $scope.homeAllowanceId = null;
            $scope.salary_first_range = null;
            $scope.salary_last_range = null;
            $scope.dhaka_metro_politon_area_rate = null;
            $scope.dhaka_metro_politon_area_limit = null;
            $scope.expensive_area_rate = null;
            $scope.expensive_area_limit = null;
            $scope.other_area_rate = null;
            $scope.other_area_limit = null;
            $scope.scale_year = null;
        }



        $scope.validationHomeRentalAllowance = function() {
            if($scope.houseRentAllowance.$invalid) {
                $scope.submittedHouseRentalAllowance = true;
                return false;
            } else {
                $scope.submittedHouseRentalAllowance = false;
                return true;
            }
        }

        $scope.saveHomeRentalAllowance = function() {
            if($scope.validationHomeRentalAllowance()==false) {
                return;
            }
            $scope.dataLoadingMonthlyDeduction = true;
            var data = {
                homeAllowanceId : $scope.homeAllowanceId,
                salary_first_range : $scope.salary_first_range,
                salary_last_range : $scope.salary_last_range,
                dhaka_metro_politon_area_rate : $scope.dhaka_metro_politon_area_rate,
                dhaka_metro_politon_area_limit : $scope.dhaka_metro_politon_area_limit,
                expensive_area_rate : $scope.expensive_area_rate,
                expensive_area_limit : $scope.expensive_area_limit,
                other_area_rate : $scope.other_area_rate,
                other_area_limit : $scope.other_area_limit,
                scale_year : $scope.scale_year
            }
            console.log(data);

            $http.post("/accounts/salary/home-rental-allowance/api/save-home-rental-allowance-rates", data)
                .then(function(data){

                    console.log(data.status);

                    console.log(data.data);
                    if(data.data == 'Updated'){
                        $scope.savingSuccessHouseAllowance = 'Updated successfully.'
                        $("#savingSuccessHouseAllowance").show().delay(5000).slideUp(1000);
                        $scope.blankHomeAllowance();
                        $scope.maximum_salary = false;
                        $scope.btnSaveMonthlyDeduction = true;
                        $scope.btnUpdateMonthlyDeduction = false;
                        $scope.ifMaximumSalaryDisable = false;
                    }else if(data.data == 'Success') {
                        $scope.savingSuccessHouseAllowance = 'Saved successfully.'
                        $("#savingSuccessHouseAllowance").show().delay(5000).slideUp(1000);
                        $scope.blankHomeAllowance();
                        $scope.maximum_salary = false;
                        $scope.btnSaveMonthlyDeduction = true;
                        $scope.btnUpdateMonthlyDeduction = false;
                        $scope.ifMaximumSalaryDisable = false;
                    }else if(data.data == 'DuplicateSave') {
                        $scope.savingErrorHouseAllowance = 'Duplicate Can Not Entry.'
                        $("#savingErrorHouseAllowance").show().delay(5000).slideUp(1000);
                        $scope.btnSaveMonthlyDeduction = true;
                        $scope.btnUpdateMonthlyDeduction = false;
                        $scope.ifMaximumSalaryDisable = false;
                    }else {
                        $scope.savingErrorHouseAllowance = 'Duplicate Can Not Entry.'
                        $("#savingErrorHouseAllowance").show().delay(5000).slideUp(1000);
                        $scope.btnSaveMonthlyDeduction = false;
                        $scope.btnUpdateMonthlyDeduction = true;
                        $scope.ifMaximumSalaryDisable = false;
                    }

                   // $scope.getEmployeeMonthlyDeduction($scope.employeeId);
                    $scope.getHomeRentalAllowanceData();



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

            if(allowance.last_range == -1){

                $scope.ifMaximumSalaryDisable = true;
                $scope.maximum_salary = true;

            }else {
                $scope.maximum_salary = false;
                $scope.ifMaximumSalaryDisable = false;
            }
            $scope.homeAllowanceId = allowance.id;
            $scope.salary_first_range = parseFloat(allowance.first_range);
            $scope.salary_last_range = allowance.last_range != 0 ? parseFloat(allowance.last_range) : null;
            $scope.dhaka_metro_politon_area_rate = allowance.dhaka_metro_politon_area_rate != 0 ? parseFloat(allowance.dhaka_metro_politon_area_rate) : null;
            $scope.dhaka_metro_politon_area_limit = allowance.dhaka_metro_politon_area_limit != 0 ? parseFloat(allowance.dhaka_metro_politon_area_limit) : null;
            $scope.expensive_area_rate = allowance.expensive_area_rate != 0 ? parseFloat(allowance.expensive_area_rate) : null;
            $scope.expensive_area_limit = allowance.expensive_area_limit != 0 ? parseFloat(allowance.expensive_area_limit) : null;
            $scope.other_area_rate = allowance.other_area_rate != 0 ? parseFloat(allowance.other_area_rate) : null;
            $scope.other_area_limit = allowance.other_area_limit != 0 ? parseFloat(allowance.other_area_limit) : null;
            $scope.scale_year = allowance.scale_year;
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
                    $scope.deleteHomeRent(result, id);
                }
            }).css({
                'text-align':'center',
                'top':'0',
                'bottom': '0',
                'left': '0',
                'right': '0',
                'margin': 'auto'
            });


            $scope.deleteHomeRent = function(result, id){

                if(result == true) {
                    $http.delete("/accounts/salary/home-rental-allowance/api/delete-home-rental-allowance-rates/"+id)
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
                        $scope.getHomeRentalAllowanceData();
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
}).filter('numberToText', function () {
    return function (val) {
        var text = val;
        if(val==0){
            return text='Start';
        } else if(val == -1) {
            return text='Highest';
        } else {
            return text;
        }
        return text;
    }
});