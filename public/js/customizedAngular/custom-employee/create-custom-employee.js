angular.module('customEmployeeApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('customEmployeeCtrl', function($scope, $http,enterKeyService){



        $scope.btnSave = true;
        $scope.btnUpdate = false;



        enterKeyService.enterKey('#customEmployeeForm input ,#customEmployeeForm button')


        $scope.validation = function() {

            var f = 0;

            if($scope.photo != null) {
                var pattern = /^image\/(jpe?g|png|gif|bmp)$/g;
                if($scope.photo.size/1024 > 2048) {
                    $scope.photo_validation = 'Photo size must be in 2MB';
                    $('#photo').val('');
                    // angular.element("input[type='file']").val(null);
                    f = 1;
                } else if(!pattern.test($scope.photo.type)) {
                    $scope.photo_validation = 'Invalid image file type';
                    $('#photo').val('');
                    // angular.element("input[type='file']").val(null);
                    f = 1;
                } else {
                    $scope.photo_validation = false;
                }
            } else {
                $scope.photo_validation = false;
            }


            if($scope.national_id_photo != null) {
                var pattern = /^image\/(jpe?g|png|gif|bmp)$/g;
                if($scope.national_id_photo.size/1024 > 2048) {
                    $scope.national_id_photo_error = 'NID Photo size must be in 2MB';
                    $('#national_id_photo').val('');
                    // angular.element("input[type='file']").val(null);
                    f = 2;
                } else if(!pattern.test($scope.national_id_photo.type)) {
                    $scope.national_id_photo_error = 'Invalid image file type';
                    $('#national_id_photo').val('');
                    // angular.element("input[type='file']").val(null);
                    f = 2;
                } else {
                    $scope.national_id_photo_error = false;
                }
            } else {
                if($scope.national_id_photo_link == null) {
                    $scope.national_id_photo_error = 'National ID Photo is required';
                    f = 2;
                }

            }

            if($scope.customEmployeeForm.$invalid) {
                $scope.submitted = true;
                f = 3;
            } else {
                $scope.submitted = false;
            }
            if(f>=2 && f<=3) {
                return false;
            } else {
                return true;
            }
        }

        $scope.save = function() {
            console.log($scope.validation());
            if($scope.validation()== false) {
                return;
            }
            $scope.dataLoading = true;
            var data = new FormData();
            data.append('employee_type', $scope.employee_type);
            data.append('organization_name', $scope.organization_name);
            data.append('name', $scope.name);
            data.append('address', $scope.address);
            data.append('national_id', $scope.national_id);
            data.append('date_of_birth', $scope.date_of_birth);
            data.append('designation', $scope.designation);
            data.append('phone_no', $scope.phone_no);
            data.append('email', $scope.email);
            data.append('mobile', $scope.mobile);
            data.append('photo', $scope.photo);
            data.append('national_id_photo', $scope.national_id_photo);
            // data.append('cnf_detail_id', $scope.cnf_details_id);
            // console.log(data.append());
            console.log(data);
            $http.post("/user/api/custom-employee/save-custom-employee-data", data, {
                withCredentials: true,
                headers: {'Content-Type': undefined },
                transformRequest: angular.identity
            }).then(function successCallback(response) {
                console.log(response.data);
                //return;
                $scope.dataLoading = false;
                $scope.savingSuccess = "Employee added Sucessfully.";
                $("#savingSuccess").show().delay(5000).slideUp(1000);
                // $scope.org_id_show = $scope.org_id;
               // $scope.allEmpByCnf($scope.cnf_details_id);
                $scope.allCustomEmployeeList($scope.employee_type, 1);
                $scope.blank();
            }, function errorCallback(response) {
                console.log(response);
                $scope.dataLoading = false;
                $scope.savingError = "Something went Wrong";
                $("#savingError").show().delay(5000).slideUp(1000);
            }).catch(function(r){
                if(r.status == 401) {
                    $.growl.error({message: r.data});

                } else {
                    $.growl.error({message: "It has Some Error!"});

                }

            }).finally(function(){

            });
        }




        $scope.emp_type_search = 'head_office';
        $scope.allCustomEmployeeList = function (emp_type, f = 0) {
            console.log(emp_type);
            if(f == 1) {
                $scope.emp_type_search = $scope.employee_type;
            }
            var data = {
                emp_type : emp_type
            }
            $http.post("/user/api/custom-employee/get-all-custom-employee", data)
                .then(function (data) {
                    console.log(data.data);
                    $scope.allCustomEmployee = data.data;
                    console.log($scope.allCustomEmployee);
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
        $scope.allCustomEmployeeList($scope.emp_type_search);


        $scope.blank = function() {
            //$scope.photo = null;
            //$scope.org_id = null;
            $scope.organization_name = null;
            $scope.name = null;
            $scope.designation = null;
            $scope.date_of_birth = null;
            $scope.national_id = null;
            $scope.phone_no = null;
            $scope.national_id_photo = null;
            $scope.email = null;
            $scope.mobile = null;
            $scope.address = null;

            angular.element("input[type='file']").val(null);
            //$('#photo').val(null);


        }



        $scope.pressUpdateBtn = function(customEmployeeData) {
            console.log(customEmployeeData);
            $scope.btnSave = false;
            $scope.btnUpdate = true;
            $scope.employee_type = customEmployeeData.employee_type;
            $scope.organization_name = customEmployeeData.organization;
            $scope.name = customEmployeeData.name;
            $scope.designation = customEmployeeData.designation;
            $scope.date_of_birth = customEmployeeData.date_of_birth;
            $scope.national_id = customEmployeeData.national_id;
            $scope.mobile = customEmployeeData.mobile;
            $scope.phone_no = customEmployeeData.phone_no;
            $scope.email = customEmployeeData.email;
            $scope.address = customEmployeeData.address;
            $scope.photo_link = customEmployeeData.photo;
            $scope.national_id_photo_link = customEmployeeData.nid_photo;
            $scope.custom_employee_id = customEmployeeData.id;
            $scope.selectedStyle = customEmployeeData.id;

            //console.log($scope.phone_no);
        }



        $scope.update = function () {
            if($scope.validation()== false) {
                return;
            }
            $scope.dataLoading = true;
            var data = new FormData();
            //console.log($scope.national_id);
            data.append('id',  $scope.custom_employee_id);
            data.append('employee_type', $scope.employee_type);
            data.append('organization', $scope.organization_name);
            data.append('name', $scope.name);
            data.append('designation', $scope.designation);
            data.append('date_of_birth', $scope.date_of_birth);
            data.append('national_id', $scope.national_id);
            data.append('mobile', $scope.mobile);
            data.append('phone_no', $scope.phone_no);
            data.append('email', $scope.email);
            data.append('address', $scope.address);
            data.append('photo', $scope.photo);
            data.append('photo_link', $scope.photo_link);
            data.append('national_id_photo', $scope.national_id_photo);
            data.append('national_id_photo_link', $scope.national_id_photo_link);

            $http.post("/user/api/custom-employee/update-custom-employee-data", data, {
                withCredentials: true,
                headers: {'Content-Type': undefined },
                transformRequest: angular.identity
            }).then(function successCallback(response) {
                //console.log(response);
                $scope.dataLoading = false;
                $scope.savingSuccess = "Employee update successfully.";
                $("#savingSuccess").show().delay(5000).slideUp(1000, function(){
                    $scope.selectedStyle = 0;
                });
                // $scope.org_id_show = $scope.org_id;
                $scope.btnSave = true;
                $scope.btnUpdate = false;
                $scope.photo_link = null;
                $scope.national_id_photo_link = null;
              //  $scope.allEmpByCnf($scope.cnf_details_id);
                $scope.blank();
                $scope.allCustomEmployeeList($scope.employee_type, 1);


            }, function errorCallback(response) {
                $scope.dataLoading = false;
                $scope.savingError = "Something went Wrong";
                $("#savingError").show().delay(5000).slideUp(1000, function(){
                    $scope.selectedStyle = 0;
                });
            }).catch(function(r){
                if(r.status == 401) {
                    $.growl.error({message: r.data});

                } else {
                    $.growl.error({message: "It has Some Error!"});

                }

            }).finally(function(){

            });
        }

        $scope.pressDeleteBtn = function(customEmployeeData) {
            console.log(customEmployeeData.id);
            var id = customEmployeeData.id;
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
                    $scope.deleteEmployee(result, id);
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

        $scope.deleteEmployee = function(result, id) {
            if(result == true) {
                $http.delete("/user/api/custom-employee/delete-custom-employee-data/"+id)
                    .then(function(data){
                        console.log(data);
                        console.log(data.data);
                            if(data.data == 'deny'){
                                $scope.savingError = "Delete From User First!";
                                $("#savingError").show().delay(5000).slideUp(1000);
                            } else {
                                $scope.savingSuccess = "Employee deleted Sucessfully.";
                                $("#savingSuccess").show().delay(5000).slideUp(1000);
                            }

                    }).catch(function(r){
                    if(r.status == 401) {
                        $.growl.error({message: r.data});

                    } else {
                        $.growl.error({message: "It has Some Error!"});

                    }
                    $scope.savingError = "Something went Wrong";
                    $("#savingError").show().delay(5000).slideUp(1000);
                }).finally(function(){
                    $scope.allCustomEmployeeList($scope.emp_type_search);
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
}]).directive('fileModel', ['$parse', function ($parse) {
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
}]).factory('autocomplete-keys', function () {
    return {
        upArrow: 38,
        downArrow: 40,
        enter: 13,
        escape: 27
    };
});