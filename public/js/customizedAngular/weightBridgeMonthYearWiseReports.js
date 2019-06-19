angular.module('weightBrightAllReportsApp', ['angularUtils.directives.dirPagination', 'customServiceModule'])
    .controller('WeightBridgeAllReportsCtrl', function ($scope, $http, enterKeyService,manifestService,$filter) {


        $scope.subHeadSearch = null;
        $scope.voucherID = null;
        $scope.$watch('vouchar_no', function (val) {
            $scope.vouchar_no = $filter('uppercase')(val);

        }, true);

        $scope.$watch('voucherNo', function (val) {
            $scope.voucherNo = $filter('uppercase')(val);
            $scope.voucherNo = manifestService.addYearWithVoucher($scope.voucherNo, $scope.keyboardFlag);


        }, true);

        $scope.keyBoard = function (event) {
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);

            // console.log(event)
        }



        enterKeyService.enterKey('#expenEntryForm input ,#expenEntryForm button')
        $http.get("/accounts/expenditure/api/get-all-expenditure-sub-head")//get subhead for autocomplete
            .then(function (data) {

                $scope.allExpenditureSubHeadData = data.data;

            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }


        }).finally(function () {

        });




        $scope.showExpenseLimitAlert=function () {


            $http.get("/accounts/expenditure/api/show-expense-limit-alert")//get showExpenseLimitAlert
                .then(function (data) {

                    console.log(data.data[0])

                    $scope.yearlyLimit=data.data[0].yearly_limit;
                    $scope.monthlyLimit=data.data[0].monthly_limit;
                    $scope.yearlyExpense=data.data[0].current_expense_year;
                    $scope.monthlyExpense=data.data[0].current_expense_month;


                    $scope.yearlyLimitlimitProgressBarWidth=Math.floor (($scope.yearlyExpense/$scope.yearlyLimit)*100);
                    $scope.monthlyLimitlimitProgressBarWidth=Math.floor (($scope.monthlyExpense/$scope.monthlyLimit)*100);

                    //console.log(Math.floor (($scope.yearlyExpense/$scope.yearlyLimit)*100))
                    $scope.changeProgressbarClass($scope.yearlyLimitlimitProgressBarWidth,'yearLimit');
                    $scope.changeProgressbarClass($scope.monthlyLimitlimitProgressBarWidth,'monthLimit');


                    //  console.log($scope.monthlyLimitlimitProgressBarWidth)

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

        $scope.showExpenseLimitAlert();



        $scope.changeProgressbarClass=function (currentPercent,id) {
            // console.log(currentPercent)

            if(currentPercent>=0 && currentPercent<80) {//like: 44% or 50%
                $("#"+ id + " div div").removeClass('progress-bar-warning')

                $("#"+ id + " div div").addClass('progress-bar-success')

            }

            else if(currentPercent>=80 && currentPercent<95){//like: 44% or 50%

                // $('#yearLimit div div').removeClass('progress-bar-success')
                $("#"+ id + " div div").removeClass('progress-bar-success')
                $("#"+ id + " div div").removeClass('progress-bar-danger')

                $("#"+ id + " div div").addClass('progress-bar-warning')

            }
            else if(currentPercent>=95){

                $("#"+ id + " div div").removeClass('progress-bar-warning')

                $("#"+ id + " div div").addClass('progress-bar-danger')
            }

        }


        $scope.getVoucherDetails = function (voucher) {


            $scope.updateBtnExpn = false;
            $scope.voucherSearching = true;

            $scope.subHeadSearch = null;
            $scope.idSelectedRow = 0;

            $("#sub_head_id_autocomplete").val('');
            $scope.amount = '';


            $http.get("/accounts/expenditure/api/get-voucher-details/" + voucher)
                .then(function (r) {
                    // console.log(r);

                    if (r.status == 204)//no voucher found
                    {
                        console.log('204');


                        $scope.vouchar_no = voucher;
                        $scope.vouchar_date = null;
                        $scope.subHeadSearch = '';

                        $scope.searchNotFound = 'No Voucher Found!';
                        $scope.allExpendituresData = null;
                        return;
                    }
                    else {
                        if (r.data[0].vouchar_id != null)//voucher found and  expenditure  found
                        {
                            // console.log(r.data[0])

                            $scope.vouchar_no = r.data[0].vouchar_no;
                            $scope.vouchar_date = r.data[0].vouchar_date;
                            $scope.vouchar_no = r.data[0].vouchar_no;

                            $scope.getAllExpenditures(voucher);
                            $scope.searchNotFound = '';


                        }
                    }

                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {
                $scope.voucherSearching = false;
            })

        }


        $scope.saveExpenditure = function (form) {

            console.log(form.$valid);

            console.log($scope.subHeadSearch)
            if (form.$valid) {


                if (checkDuplicate() == true) {
                    $scope.expenErrorMsg = true;
                    $scope.expenErrorMsgTxt = 'The Particular alrady Added!';
                    $("#expenError").show().fadeTo(1500, 500).slideUp(1500, function () {
                        $("#expenError").slideUp(2000);
                    });
                    return;
                }
                $scope.expenSaving = true;
                var data = {
                    vouchar_no: $scope.vouchar_no,
                    sub_head_id: $scope.subHeadSearch,
                    amount: $scope.amount,
                    vouchar_date: $scope.vouchar_date
                }
                /*console.log(data);

                 return*/
                $http.post("/accounts/expenditure/api/save-expenditure/", data)
                    .then(function (data) {
                        console.log(data);
                        if (data.status == 201) {
                            $scope.expenSuccessMsg = true;
                            $scope.expenSuccessMsgTxt = 'Added';
                            $("#expenSuccess").show().fadeTo(1500, 500).slideUp(1500, function () {
                                $("#expenSuccess").slideUp(2000);
                            });

                            //   $scope.vouchar_no='';
                            $scope.vouchar_date = '';
                            $scope.voucherUpdateBtn = false;
                            $scope.submittedExpen = false;

                            $scope.getVoucherDetails($scope.vouchar_no);
                            $scope.showExpenseLimitAlert();

                        }
                        else if (data.status == 204) {
                            $scope.expenErrorMsg = true;
                            $scope.expenErrorMsgTxt = 'The Particular alrady Added!';
                            $("#expenError").show().fadeTo(1500, 500).slideUp(1500, function () {
                                $("#expenError").slideUp(2000);
                            });
                        }


                    }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                    $scope.expenErrorMsg = true;
                    $scope.expenErrorMsgTxt = 'Something went wrong.';
                    $("#expenError").show().fadeTo(1500, 500).slideUp(1500, function () {
                        $("#expenError").slideUp(2000);
                    });
                }).finally(function () {
                    $scope.expenSaving = false;
                })

            }
            else {
                $scope.submittedExpen = true;
            }

        }


        $http.get("/accounts/expenditure/api/get-all-expenditure-sub-head")
            .then(function (data) {

                $scope.allExpenditureSubHeadData = data.data;

            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {

        });


        $scope.getAllExpenditures = function (voucher_no) {

            $scope.expenDataLoading = true;

            $http.get("/accounts/expenditure/api/get-all-expenditures/" + voucher_no)
                .then(function (r) {
                    //   console.log(r.data)
                    if (r.data.length <= 0) {
                        $scope.noExpenditureDataFound = true;
                        $scope.noExpenditureDataFoundTxt = 'No Data Found';
                        $scope.allExpendituresData = null;

                    }
                    else {
                        $scope.noExpenditureDataFound = false;
                        $scope.allExpendituresData = r.data;
                    }


                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.loadingerror = true;


            }).finally(function () {
                $scope.expenDataLoading = false;
            });
        }

        $scope.onSelectSubHead = function (selection) {
            console.log(selection.id);
            $scope.subHeadSearch = selection.id;
            //$scope.orgName = selection.org_name;
        }


        $scope.editExpense = function (i) {

            $scope.updateBtnExpn = true;
            console.log(i)
            $scope.vouchar_no = i.vouchar_no;
            $scope.subHeadSearch = i.sub_head_id
            $scope.subhead_id_check_duplicate = i.sub_head_id
            $("#sub_head_id_autocomplete").val(i.acc_sub_head);
            $scope.amount = parseFloat(i.amount);
            $scope.vouchar_date = i.vouchar_date;

            $scope.idSelectedRow = i.ex_id;
            $scope.ex_id_edit = i.ex_id;

            $scope.voucher_id = i.voucher_id


        }


        $http.get("/accounts/expenditure/api/get-source-wise-report-data")
            .then(function (data) {
                console.log(data.data);
                $scope.headTable = true;
                $scope.allHeadData = data.data;
            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
        }).finally(function () {

        })

        $http.get("/accounts/expenditure/api/sub-head-wise-report-data")
            .then(function (data) {
                //console.log(data.data);
                $scope.headTable = true;
                $scope.DataSubHeadWise = data.data;
            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
        }).finally(function () {

        })


        $http.get("/accounts/expenditure/api/sub-head-wise-report-data")
            .then(function (data) {
                // console.log(data.data);
                // $scope.headTable = true;
                $scope.monthlySubHeadWisedata = data.data;
            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
        }).finally(function () {

        })


        $http.get("/accounts/expenditure/api/only-monthly-sub-head-wise-report-data")
            .then(function (data) {
                // console.log(data.data);
                // $scope.headTable = true;
                $scope.monthlydata = data.data;
            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
        }).finally(function () {

        })


        $scope.updateExpense = function (form) {


            console.log(form.$valid);

            console.log($scope.subHeadSearch)
            if (form.$valid) {

                if ($scope.subHeadSearch != $scope.subhead_id_check_duplicate) {

                    if (checkDuplicate() == true) {

                        $scope.expenErrorMsg = true;
                        $scope.expenErrorMsgTxt = 'The Particular alrady Added!';
                        $("#expenError").show().fadeTo(1500, 500).slideUp(1500, function () {
                            $("#expenError").slideUp(2000);
                        });
                        return;
                    }
                }
                $scope.dataLoading = true;
                var data = {
                    vouchar_no: $scope.vouchar_no,
                    sub_head_id: $scope.subHeadSearch,
                    amount: $scope.amount,
                    vouchar_date: $scope.vouchar_date,
                    voucher_id: $scope.voucher_id
                }
                console.log(data);
                console.log($scope.voucher_id);


                $http.put("/accounts/expenditure/api/update-expenditure-data/" + $scope.ex_id_edit, data)
                    .then(function (r) {
                        console.log(r);

                        if (r.status == 200) {
                            $scope.expenSuccessMsg = true;
                            $scope.expenSuccessMsgTxt = 'Updated';
                            $("#expenSuccess").show().fadeTo(1500, 500).slideUp(500, function () {
                                $("#expenSuccess").slideUp(2000);
                            });
                            $scope.showExpenseLimitAlert();
                            //  $scope.vouchar_no='';
                            $scope.vouchar_date = '';
                            $scope.updateBtnExpn = false;

                            $scope.idSelectedRow = 0;

                            $scope.getVoucherDetails($scope.vouchar_no);
                        }
                        else {

                            $scope.expenErrorMsg = true;
                            $scope.expenErrorMsgTxt = 'Something went wrong.';
                            $("#expenError").show().fadeTo(1500, 500).slideUp(500, function () {
                                $("#expenError").slideUp(2000);
                            });
                        }

                    }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                    $scope.expenErrorMsg = true;
                    $scope.expenErrorMsgTxt = 'Something went wrong.';
                    $("#expenError").show().fadeTo(1500, 500).slideUp(500, function () {
                        $("#expenError").slideUp(2000);
                    });
                }).finally(function () {
                    $scope.dataLoading = false;
                })

            }
            else {
                $scope.submittedExpen = true;
            }

        }


        $scope.deleteExpenseConfirm = function (i) {
            console.log(i)
            $scope.ex_id_delete = i.ex_id;
            $scope.acc_sub_head_delete = i.acc_sub_head;
            $scope.vouchar_no_delete = i.vouchar_no;
        }


        $scope.deleteExpenditure = function () {
            $scope.expenDeleting = true;

            $http.get("/accounts/expenditure/api/delete-expenditure-data/" + $scope.ex_id_delete)
                .then(function (r) {
                    console.log(r);

                    if (r.status == 200) {
                        $scope.deleteSuccessExpenseMsg = true;
                        $scope.deleteSuccessExpenseMsgTxt = 'Deleted';
                        $("#deleteSuccessExpense").show().fadeTo(1500, 500).slideUp(500, function () {
                            $("#deleteSuccessExpense").slideUp(2000);
                        });

                        $scope.showExpenseLimitAlert();
                        $scope.getVoucherDetails($scope.vouchar_no_delete);
                    }


                    else {

                        $scope.deleteErrorExpenseMsg = true;
                        $("#deleteErrorExpense").show().fadeTo(1500, 500).slideUp(500, function () {
                            $("#deleteErrorExpense").slideUp(2000);
                        });
                    }

                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.deleteErrorExpenseMsg = true;
                $("#deleteErrorExpense").show().fadeTo(1500, 500).slideUp(500, function () {
                    $("#deleteErrorExpense").slideUp(2000);
                });

            }).finally(function () {
                $scope.expenDeleting = false;
            })

        }


//check dublicacy--------------
        var checkDuplicate = function () {
            var duplicateExpenditure = false;
            angular.forEach($scope.allExpendituresData, function (v, k) {

                if (v.sub_head_id == $scope.subHeadSearch) {
                    duplicateExpenditure = true;
                }

            });

            return duplicateExpenditure;

        }


    }).directive('autocomplete', ['autocomplete-keys', '$window', '$timeout', function (Keys, $window, $timeout) {
    return {
        template: '<input type="text" id="sub_head_id_autocomplete" class="autocomplete-input" placeholder="{{placeHolder}}"' +
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
        controller: function ($scope) {
            $scope.searchTerm = '';
            $scope.highlightedOption = null;
            $scope.showOptions = false;
            $scope.matchingOptions = [];
            $scope.hasMatches = false;
            $scope.selectedOption = null;

            $scope.isOptionSelected = function (option) {
                return option === $scope.highlightedOption;
            };

            $scope.processSearchTerm = function (term) {
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

            $scope.findMatchingOptions = function (term) {
                return $scope.options.filter(function (option) {
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


                return $scope.options.filter(function (option) {
                    var lowerCaseOption = option[$scope.displayProperty].toLowerCase();
                    var lowerCaseTerm = term.toLowerCase();
                    return lowerCaseOption == lowerCaseTerm;
                });
            };

            $scope.keyDown = function (e) {
                switch (e.which) {
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

            $scope.highlightNext = function () {
                if (!$scope.highlightedOption) {
                    $scope.highlightedOption = $scope.matchingOptions[0];
                } else {
                    var currentIndex = $scope.currentOptionIndex();
                    var nextIndex = currentIndex + 1 == $scope.matchingOptions.length ? 0 : currentIndex + 1;
                    $scope.highlightedOption = $scope.matchingOptions[nextIndex];
                }
            };

            $scope.highlightPrevious = function () {
                if (!$scope.highlightedOption) {
                    $scope.highlightedOption = $scope.matchingOptions[$scope.matchingOptions.length - 1];
                } else {
                    var currentIndex = $scope.currentOptionIndex();
                    var previousIndex = currentIndex == 0 ? $scope.matchingOptions.length - 1 : currentIndex - 1;
                    $scope.highlightedOption = $scope.matchingOptions[previousIndex];
                }
            };

            $scope.onOptionHover = function (option) {
                $scope.highlightedOption = option;
            };

            $scope.$on('simple-autocomplete:clearInput', function () {
                $scope.searchTerm = '';
            });

            $scope.clearHighlight = function () {
                $scope.highlightedOption = null;
            };

            $scope.closeAndClear = function () {
                $scope.showOptions = false;
                $scope.clearHighlight();
            };

            $scope.selectOption = function (option) {

                $scope.selectedOption = option;
                $scope.onSelect(option);

                if ($scope.clearInput != 'False' && $scope.clearInput != 'false') {
                    $scope.searchTerm = '';
                } else {
                    $scope.searchTerm = option[$scope.displayProperty];
                }

                $scope.closeAndClear();
            };

            $scope.onBlur = function () {
                $scope.closeAndClear();
            };

            $scope.currentOptionIndex = function () {
                return $scope.matchingOptions.indexOf($scope.highlightedOption);
            };
        },
        link: function (scope, elem, attrs) {
            scope.optionWidth = '400px';
            var inputElement = elem.children('.autocomplete-input')[0];

            scope.setOptionWidth = function () {
                // console.log(inputElement.offsetWidth);
                $timeout(function () {
                    var pixelWidth = inputElement.offsetWidth > 400 ? 400 : inputElement.offsetWidth - 2;
                    scope.optionWidth = pixelWidth + 'px';
                });
            };

            angular.element(document).ready(function () {
                scope.setOptionWidth();
            });

            angular.element($window).bind('resize', function () {
                scope.setOptionWidth();
            });
        }
    };
}]).directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;

            element.bind('change', function () {
                scope.$apply(function () {
                    modelSetter(scope, element[0].files[0]);
                });
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
})
    .filter('dateShort', function ($filter) {
        return function (ele, dateFormat) {
            return $filter('date')(new Date(ele), dateFormat);
        }
    })