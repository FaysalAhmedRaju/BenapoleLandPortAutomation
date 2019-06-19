angular.module('FacilitiesAndDeductionApp', ['angularUtils.directives.dirPagination','customServiceModule'])
	.controller('FacilitiesAndDeductionCtrl', function ($scope, $http,enterKeyService) {

        //Fixed Facilities and Deduction Start
		$scope.btnSaveFixed = true;
		$scope.btnUpdateFixed = false;

        // var min = new Date().getFullYear()-5;
        // var	max = min + 10;
        // $scope.years = [];
        // var j=0;
        // for (var i = min; i<=max; i++){
        //    $scope.years[j++] = {value: i, text: i};
        // }

        $scope.blankFixed = function() {
        	$scope.fixed_id = null;
			$scope.education = null;
			$scope.medical = null;
			$scope.tiffin = null;
            $scope.washing = null;
			$scope.gpf = null;
			$scope.revenue = null;
			$scope.scale_year = null;
			$scope.transport = null;
        }

        enterKeyService.enterKey('#FixedFacilitiesAndDeduction input ,#FixedFacilitiesAndDeduction button')
        enterKeyService.enterKey('#MonthlyDedudction input ,#MonthlyDedudction button')


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


        $scope.getFixed = function() {
			$http.get("/accounts/salary/facilities-deduction/api/get-fixed-facilities-and-deduction-data")
				.then(function(data){
					//console.log(data.data);
					if(data.data.length>0) {
						$scope.fixedFacilitiesAndDeductions = data.data;
						$scope.fixedShow = true;
					} else {
						$scope.fixedShow = false;
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
		$scope.getFixed();

		$scope.validationFixed = function() {
			if($scope.FixedFacilitiesAndDeduction.$invalid) {
				$scope.submittedFixed = true;
				return false;
			} else {
				$scope.submittedFixed = false;
				return true;
			}
		}

        $scope.saveFixed = function() {
        	if($scope.validationFixed() == false) {
        		return;
        	}
        	$scope.dataLoadingFixed = true;
			var data = {

				education : $scope.education,
				medical : $scope.medical,
				tiffin : $scope.tiffin,
                washing : $scope.washing,
                transport : $scope.transport,
				gpf : $scope.gpf,
				revenue : $scope.revenue,
				scale_year : $scope.scale_year
			}
			console.log(data);
			$http.post("/accounts/salary/facilities-deduction/api/save-fixed-facilities-and-deduction-data",data)
				.then(function(data){
					console.log(data);
					$scope.blankFixed();
					$scope.getFixed();
					$scope.savingSuccessFixed = 'Saved successfully.'
					$("#savingSuccessFixed").show().delay(5000).slideUp(1000);
				}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.savingErrorFixed = 'Something went wrong.'
					$("#savingErrorFixed").show().delay(5000).slideUp(1000);
				}).finally(function(){
					$scope.dataLoadingFixed = false;
				})
		}

		$scope.pressUpdateBtnFixed = function(fixed) {
			$scope.btnSaveFixed = false;
			$scope.btnUpdateFixed = true;
			$scope.fixed_id = fixed.id;
			$scope.education = parseFloat(fixed.education);
			$scope.medical = parseFloat(fixed.medical);
			$scope.tiffin = parseFloat(fixed.tiffin);
            $scope.washing = parseFloat(fixed.washing);
			$scope.gpf = parseFloat(fixed.gpf);
			$scope.revenue = parseFloat(fixed.revenue);
			$scope.scale_year = fixed.scale_year;
			$scope.transport = parseFloat(fixed.transport);
		}

		$scope.updateFixed = function() {
			if($scope.validationFixed() == false) {
        		return;
        	}
			$scope.dataLoadingFixed = true;
			var data = {
				id : $scope.fixed_id,
				education : $scope.education,
				medical : $scope.medical,
				tiffin : $scope.tiffin,
                washing : $scope.washing,
				gpf : $scope.gpf,
				revenue : $scope.revenue,
				scale_year : $scope.scale_year,
                transport : $scope.transport
			}
			console.log(data);
			$http.put("/accounts/salary/facilities-deduction/api/update-fixed-facilities-and-deduction",data)
				.then(function(data){
					console.log(data);
					$scope.blankFixed();
					$scope.getFixed();
					$scope.savingSuccessFixed = 'Updated successfully.'
					$("#savingSuccessFixed").show().delay(5000).slideUp(1000);
					$scope.btnSaveFixed = true;
					$scope.btnUpdateFixed = false;
				}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.savingErrorFixed = 'Something went wrong.'
					$("#savingErrorFixed").show().delay(5000).slideUp(1000);
					$scope.blankFixed();
					$scope.btnSaveFixed = true;
					$scope.btnUpdateFixed = false;
				}).finally(function(){
					$scope.dataLoadingFixed = false;
				})
		}
		$scope.MonthlyDeductionDisabled = true;
		$scope.pressDeleteBtnFixed = function(fixed) {
			var id = fixed.id;
            var scaleYear = fixed.scale_year;
            bootbox.confirm({
                message: "Do you want to Delete <b>" + scaleYear + "'s</b> fixed facilities and deduction?",
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
                    $scope.deleteFixed(result, id, scaleYear);
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

		$scope.deleteFixed = function(result, id, scaleYear) {
			if(result == true) {
                $http.delete("/accounts/salary/facilities-deduction/api/delete-fixed-facilities-and-deductions/"+id)
                    .then(function(data){
                        $scope.savingSuccessFixed = "'" + scaleYear + "' fixed facilities and deduction deleted successfully.";
                        $("#savingSuccessFixed").show().delay(5000).slideUp(1000);
                        $scope.getFixed();
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingErrorFixed = "Something Went Wrong.";
                        $("#savingErrorFixed").show().delay(5000).slideUp(1000);
                    }).finally(function(){

                    })
            } else {
                return false;
            }
		}


        //Fixed Facilities and Deduction End
        //Monthly Deduction Start

        $scope.btnSaveMonthlyDeduction = true;
        $scope.btnUpdateMonthlyDeduction = false;
		$scope.select = 'id';
        var date, d, m, y;
        var monthNames = ["January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December"
                            ];
        $http.get("/accounts/salary/facilities-deduction/monthly-deduction/api/get-all-valid-employees")
            .then(function(data){
                console.log(data.data);
                $scope.allValidEmployees = data.data;
            })

        $scope.clear = function() {
            $scope.employeeId = null;   //DB Table auto increment id
            $scope.empName = null;
            $scope.empDesignation = null;
            $scope.empMobileNumber = null;
            $scope.showEmployeeImg = null;
            $scope.empID = null;
            $scope.monthlyDeductionTable = false;
            $scope.allVEmployeesMonthlyDeduction = null;
        }

        $scope.onSelect = function(employee) {
            console.log(employee);
            $scope.employeeId = employee.id;
            $scope.empName = employee.name;
            $scope.empDesignation = employee.designation;
            $scope.showEmployeeImg = employee.photo;
            $scope.empMobileNumber = employee.mobile;
            $scope.empID = employee.emp_id;
            $scope.getEmployeeMonthlyDeduction($scope.employeeId);
        }
        //Monthly Deduction End
        $scope.blankMonthlyDeduction = function() {
            $scope.id = null;
            $scope.water = null;
            $scope.generator = null;
            $scope.electricity = null;
            $scope.previous_due = null;
            $scope.transport_month = null;
            $scope.month_year = null;
            date = d = m = y = null;

        }

        $scope.getEmployeeMonthlyDeduction = function(employee_id) {
            $http.get("/accounts/salary/facilities-deduction/monthly-deduction/api/get-employee-monthly-deduction/"+employee_id)
                .then(function(data){
                    if(data.data.length>0) {
                        $scope.monthlyDeductionTable = true;
                        $scope.allVEmployeesMonthlyDeduction = data.data;
                    } else {
                        $scope.monthlyDeductionTable = false;
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

        $scope.validationMonthlyDeduction = function() {
            if($scope.MonthlyDeduction.$invalid) {
                $scope.submittedMonDeduction = true;
                return false;
            } else {
                $scope.submittedMonDeduction = false;
                return true;
            }
        }

		$scope.saveMonthlyDeduction = function() {
            if($scope.employeeId == null) {
                $scope.savingErrorMonDeduction = 'Please select employee.'
                $("#savingErrorMonDeduction").show().delay(5000).slideUp(1000);
                return;
            }
            if($scope.validationMonthlyDeduction()==false) {
                return;
            }
            $scope.dataLoadingMonthlyDeduction = true;
            date = new Date($scope.month_year);
            d = date.getDate();
            m = date.getMonth()+1;
            y = date.getFullYear();
			var data = {
                employee_id : $scope.employeeId,
                water : $scope.water != null ? $scope.water : 0,
                generator : $scope.generator != null ? $scope.generator : 0,
                electricity : $scope.electricity != null ? $scope.electricity : 0,
                previous_due : $scope.previous_due != null ? $scope.previous_due : 0,
                transport_month : $scope.transport_month != null ? $scope.transport_month : 0,
                month_year : y + "-" + m + "-" + d 
            }
            $http.post("/accounts/salary/facilities-deduction/monthly-deduction/api/save-monthly-deduction", data)
                .then(function(data){
                    console.log(data);
                    if(data.data == 'restriction'){
                        $scope.savingErrorMonDeduction = 'This Deduction Can Not Entry';
                        $("#savingErrorMonDeduction").show().delay(5000).slideUp(1000);
                    }else {
                        $scope.getEmployeeMonthlyDeduction($scope.employeeId);
                        $scope.blankMonthlyDeduction();
                        $scope.savingSuccessMonDeduction = 'Monthly deduction saved successfully.'
                        $("#savingSuccessMonDeduction").show().delay(5000).slideUp(1000);
                    }

                }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                    $scope.savingErrorMonDeduction = 'Something went wrong.'
                    $("#savingErrorMonDeduction").show().delay(5000).slideUp(1000);
                }).finally(function(){
                    $scope.dataLoadingMonthlyDeduction = false;
                })
		}

        $scope.pressUpdateBtnMonthlyDeduction = function(employee) {
            $scope.id = employee.id;
            $scope.water = employee.water != 0 ? parseFloat(employee.water) : null;
            $scope.generator = employee.generator != 0 ? parseFloat(employee.generator) : null;
            $scope.electricity = employee.electricity != 0 ? parseFloat(employee.electricity) : null;
            $scope.previous_due = employee.previous_due != 0 ? parseFloat(employee.previous_due) : null;
            $scope.transport_month = employee.transport != 0 ? parseFloat(employee.transport) : null;
            date = new Date(employee.month_year);
            $scope.month_year = monthNames[date.getMonth()] +" "+ date.getFullYear();
            $scope.btnSaveMonthlyDeduction = false;
            $scope.btnUpdateMonthlyDeduction = true;
        }

        $scope.updateMonthlyDeduction = function() {
            if($scope.employeeId == null) {
                $scope.savingErrorMonDeduction = 'Please select employee.'
                $("#savingErrorMonDeduction").show().delay(5000).slideUp(1000);
                return;
            }
            if($scope.validationMonthlyDeduction()==false) {
                return;
            }
            $scope.dataLoadingMonthlyDeduction = true;
            date = new Date($scope.month_year);
            d = date.getDate();
            m = date.getMonth()+1;
            y = date.getFullYear();
            var data = {
                id : $scope.id,
                employee_id : $scope.employeeId,
                water : $scope.water != null ? $scope.water : 0,
                generator : $scope.generator != null ? $scope.generator : 0,
                electricity : $scope.electricity != null ? $scope.electricity : 0,
                previous_due : $scope.previous_due != null ? $scope.previous_due : 0,
                transport_month : $scope.transport_month != null ? $scope.transport_month : 0,
                month_year : y + "-" + m + "-" + d 
            }
            console.log(data);
            $http.put("/accounts/salary/facilities-deduction/monthly-deduction/api/update-monthly-deduction",data)
                .then(function(data){
                    //console.log(data.data);
                    if(data.data == 'restriction'){
                        $scope.savingErrorMonDeduction = 'This Deduction Can Not Entry';
                        $("#savingErrorMonDeduction").show().delay(5000).slideUp(1000);
                    }else {
                        $scope.getEmployeeMonthlyDeduction($scope.employeeId);
                        $scope.blankMonthlyDeduction();
                        $scope.savingSuccessMonDeduction = 'Monthly deduction updated successfully.'
                        $("#savingSuccessMonDeduction").show().delay(5000).slideUp(1000);
                        $scope.dataLoadingMonthlyDeduction = false;
                        $scope.btnSaveMonthlyDeduction = true;
                        $scope.btnUpdateMonthlyDeduction = false;
                    }

                }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                    $scope.savingErrorMonDeduction = 'Something went wrong.'
                    $("#savingErrorMonDeduction").show().delay(5000).slideUp(1000);
                }).finally(function(){

                })
        }

        $scope.pressDeleteBtnMonthlyDeduction = function(employee) {
            var id = employee.id;
            var yearMonth = employee.month_year;
            date = new Date(yearMonth);
            formatedYearMonth = monthNames[date.getMonth()] +" "+ date.getFullYear();
            bootbox.confirm({
                message: "Do you want to Delete <b>" + formatedYearMonth + "'s</b>  deduction?",
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
                    $scope.deleteMonthlyDeduction(result, id, formatedYearMonth);
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

        $scope.deleteMonthlyDeduction = function(result, id, formatedYearMonth) {
            if(result == true) {
                $http.delete("/accounts/salary/facilities-deduction/monthly-deduction/api/delete-monthly-deduction/"+id)
                    .then(function(data){
                        $scope.savingSuccessMonDeduction = "'" + formatedYearMonth + "' deductions deleted successfully.";
                        $("#savingSuccessMonDeduction").show().delay(5000).slideUp(1000);
                        $scope.getEmployeeMonthlyDeduction($scope.employeeId);
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingErrorMonDeduction = "Something Went Wrong.";
                        $("#savingErrorMonDeduction").show().delay(5000).slideUp(1000);
                    }).finally(function(){

                    })
            } else {
                return false;
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