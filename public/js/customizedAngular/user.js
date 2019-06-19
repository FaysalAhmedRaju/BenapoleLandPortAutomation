angular.module('UserEntryApp', ['angularUtils.directives.dirPagination', 'customServiceModule'])
    .controller('UserEntryController', function ($scope, $http, enterKeyService) {

        enterKeyService.enterKey('#userEntryForm input ,#userEntryForm button')

        $scope.btnSave = true;
        $scope.btnUpdate = false;
        $scope.passwordValidation = true;
        $scope.usernameChanges = "";
        $scope.userNameExist = false;
        $scope.empNotFoundError = false;

        // $http.post("/api/getPortForUserJson")
        //     .then(function(data){
        //         $scope.allPortData = data.data;
        //     })

        $http.post("/user/api/user/get-role-for-user")
            .then(function (data) {
                $scope.allRoleData = data.data
            }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });

        // $http.post("/api/getOrgTypeForUserJson")
        //           .then(function(data){
        //                   $scope.allOrgTypeData = data.data;
        //           })

        

        // $http.post("/user/api/user/get-organization-for-user")
        //     .then(function (data) {
        //         $scope.allOrgData = data.data;
        //     }).catch(function (r) {

        //     console.log(r)
        //     if (r.status == 401) {
        //         $.growl.error({message: r.data});
        //     } else {
        //         $.growl.error({message: "It has Some Error!"});
        //     }

        // }).finally(function () {


        // });

        $scope.$watch('role_id', function (val) {
            $scope.roleWarehouse = false;
            $scope.showScaleOption = false;
            if ($scope.role_id == 6) {
                $scope.showScaleOption = true;
            } else if (val == 8 || val == 12) {
                $scope.roleWarehouse = true;
            } else {
                $scope.scale = '';
                $('select[name=shedYards]').val(null);
                $('.selectpicker').selectpicker('refresh');
                $scope.shedYards = null;
            }
        });

        $('#employee_name_or_id').autocomplete({
            source: function(request, response) {
                    $.getJSON("/user/api/user/get-employee-details", 
                        { user_type: $scope.user_type,
                          employee_name_or_id: $scope.employee_name_or_id
                        }, response);
                  },
            minLength: 2,
            highlightItem: true,
            // autoFocus:true,
            // displayKey: 'Importer_Name',
            response: function (event, ui) {
                console.log(ui);
                if(ui.content.length == 0) {
                    $scope.empNotFoundError = true;
                } else {
                    $scope.empNotFoundError = false;
                }
            },
            select: function (event, ui) {
                console.log('select');
                console.log(ui);
                event.preventDefault();
                console.log(ui.item);
                $scope.employee_name_or_id = ui.item.name;
                $("#employee_name_or_id").val(ui.item.name);
                $scope.assignEmployeeInfo(ui.item);
                return false;
            },
            change: function (event, ui) {
                console.log('change');
                console.log(ui);
                if (ui.item == null) {
                    $scope.empNotFoundError = true;
                    //$scope.setEmployeeInfoblank();
                } else {
                    $scope.empNotFoundError = false;
                    $scope.assignEmployeeInfo(ui.item);
                }
            },
            focus: function (event, ui) {
                console.log('focus');
                console.log(ui);
                if (ui != null) {
                    $scope.empNotFoundError = false;
                    //$scope.assignEmployeeInfo(ui.item);
                } else {
                    $scope.empNotFoundError = true;
                    $scope.setEmployeeInfoblank();
                }
            },
            search: function () {
                console.log('search');
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div>" + (item.emp_id != null ? item.emp_id + "<br>" : item.organization + "<br>") 
                                        + item.name + "</div>")
                .appendTo(ul);
        };

        $("#photo").attr("src","/img/noImg.jpg");
        $scope.assignEmployeeInfo = function(emp) {
            console.log(emp);
            $scope.emp_id = emp.id;
            $scope.name = emp.name;
            $scope.designation = emp.designation;
            $scope.father_name = emp.father_name;
            $scope.mother_name = emp.mother_name;
            $scope.mobile = emp.mobile;
            $scope.email = emp.email;
            $scope.date_of_birth = emp.date_of_birth;
            $scope.national_id = emp.national_id;
            $scope.organization = emp.organization;
            $scope.photo = emp.photo;
            if($scope.photo != null) {
                $("#photo").attr("src","/"+$scope.photo);
            } else {
                $("#photo").attr("src","/img/noImg.jpg");
            } 
        }

        $scope.setEmployeeInfoblank = function() {
            $scope.emp_id = null;
            $scope.name = null;
            $scope.designation = null;
            $scope.father_name = null;
            $scope.mother_name = null;
            $scope.mobile = null;
            $scope.email = null;
            $scope.date_of_birth = null;
            $scope.national_id = null;
            $scope.organization = null;
            $scope.photo = null;
            $("#photo").attr("src","/img/noImg.jpg");
        }

        $scope.checkDuplicateUsername = function (username) {
            if(username.localeCompare($scope.usernameChanges) != 0) {
                $http.get("/user/api/user/check-user-name/" + username)
                    .then(function (data) {
                        if (data.data[0].exist > 0) {
                            $scope.userNameExist = true;
                        } else {
                            $scope.userNameExist = false;
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
        }

        $scope.save = function () {
            console.log($scope.validation());
            if ($scope.validation() == false) {
                return;
            }
            $scope.dataLoading = true;
            var data = new FormData();
            data.append('emp_id', $scope.emp_id);
            data.append('user_type', $scope.user_type);
            data.append('name', $scope.name);
            data.append('username', $scope.user_name);
            data.append('role_id', $scope.role_id);
            data.append('email', $scope.email);
            data.append('password', $scope.pass_word);
            data.append('photo', $scope.photo);
            data.append('mobile', $scope.mobile);
            data.append('scale', $scope.scale);
            data.append('shedYards', $scope.shedYards);
            data.append('user_status', $scope.user_status);
            data.append('office_order', $scope.office_order);
            data.append('port_id', $scope.port_id);
            console.log(data);
            $http.post("/user/api/user/save-user-data", data, {
                withCredentials: true,
                headers: {'Content-Type': undefined},
                transformRequest: angular.identity
            }).then(function (data) {
                console.log(data)
                //return;
                $scope.userNameExist = false;
                if (data.status == 201) {
                    $scope.userNameExist = true;
                    $scope.dataLoading = false;
                    return;
                }

                $scope.savingSuccess = "User Entry Saved Successfully.";
                $("#savingSuccess").show().delay(5000).slideUp(1000);
                $scope.allUserList($scope.user_type, 1);
                $scope.blank();
                $scope.dataLoading = false;
            }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingError = "Something Went Wrong.";
                $("#savingError").show().delay(5000).slideUp(1000);
            }).finally(function () {

            })
        }

        $scope.validation = function () {
            var f = 0;
            if($scope.userEntryForm.$invalid) {
                console.log('userEntryFormInvalid');
                f = 1;
            }

            if($scope.userNameExist == true) {
                console.log('userNameExist');
                f = 2;
            }

            if($scope.empNotFoundError == true) {
                console.log('empNotFoundError');
                f = 3;
            }

            if (f >= 1 && f <= 3) {
                $scope.submitted = true;
                return false;
            } else {
                $scope.submitted = false;
                return true;
            }
        }

        $scope.blank = function () {
            $scope.setEmployeeInfoblank();

            $('.selectpicker').val([]);
            $('.selectpicker').trigger('change.abs.preserveSelected');
            $('.selectpicker').selectpicker('refresh');
            $scope.employee_name_or_id = null;
            $("#employee_name_or_id").val(null);
            $scope.role_id = null;
            $scope.user_name = null;
            $scope.pass_word = null;
            $scope.usernameChanges = null;
            $scope.status = '1';
            $scope.scale = '';
            $scope.id = null;
        }

        $scope.user_type_search = 'port';
        $scope.allUserList = function (user_type, f = 0) {
            console.log(user_type);
            if(f == 1) {
                $scope.user_type_search = $scope.user_type;
            }
            var data = {
                user_type : user_type
            }
            $http.post("/user/api/user/get-all-user", data)
                .then(function (data) {
                    $scope.allUser = data.data;
                    console.log($scope.allUser);
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
        $scope.allUserList($scope.user_type_search);

        $scope.editUser = function (user) {

            $scope.btnSave = false;
            $scope.btnUpdate = true;
            $scope.passwordShow = true;
            $scope.passwordValidation = false;
            $scope.userNameExist = false;

            console.log(user);
            $scope.getEmployeeDetails(user);
            $scope.id = user.id;
            $scope.selectedStyle = user.id;
            
            $scope.user_type = user.user_type.toString();
            $scope.role_id =user.role_id;
            $scope.user_name = user.username;
            $scope.usernameChanges = user.username;
            $scope.user_status = user.user_status.toString();
            $scope.pass_word = null;



            $('selectpicker').val([]);
            $('selectpicker').trigger('change.abs.preserveSelected');
            $('selectpicker').selectpicker('refresh');

            if(user.shed_yard_ids != null) {
                var shed_yard_array = user.shed_yard_ids.split(',');
                $('select[name=shedYards]').val(shed_yard_array);
                $('.selectpicker').selectpicker('refresh');
                $scope.shedYards = shed_yard_array;
            }

            if(user.port_ids != null) {
                var port_array = user.port_ids.split(',');
                $('select[name=port_id]').val(port_array);
                $('.selectpicker').selectpicker('refresh');
                $scope.port_id = port_array;
            }

            if(user.scale_id != null) {
                $scope.scale = user.scale_id.toString();
            }
        }

        $scope.getEmployeeDetails = function(user) {
            $scope.employee_name_or_id = null;
            $("#employee_name_or_id").val(null);
            $scope.setEmployeeInfoblank();
            var data = {
                user_type : user.user_type,
                port_employee_id : user.port_employee_id,
                cnf_employee_id : user.cnf_employee_id,
                custom_employee_id : user.custom_employee_id
            }
           console.log(data);
           $http({
                url: "/user/api/user/get-employee-details", 
                method: "GET",
                params: data
             }).then(function(data) {
                console.log(data);
                if(data.data.length > 0) {
                    $scope.employee_name_or_id = data.data[0].name;
                    $("#employee_name_or_id").val(data.data[0].name);
                    $scope.assignEmployeeInfo(data.data[0]);
                } else {
                   $.growl.warning({message: "Please Select Employee!"}); 
                }
             }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
             });
        }
        

        $scope.update = function () {
            if ($scope.validation() == false) {
                return;
            }
            $scope.dataLoading = true;
            var data = new FormData();
            data.append('id', $scope.id);
            data.append('emp_id', $scope.emp_id);
            data.append('user_type', $scope.user_type);
            data.append('name', $scope.name);
            data.append('username', $scope.user_name);
            data.append('password', $scope.pass_word);
            data.append('role_id', $scope.role_id);
            data.append('email', $scope.email);
            data.append('photo', $scope.photo);
            data.append('mobile', $scope.mobile);
            data.append('scale', $scope.scale);
            data.append('shedYards', $scope.shedYards);
            data.append('office_order', $scope.office_order);
            data.append('user_status', $scope.user_status);
            data.append('port_id', $scope.port_id);
            console.log(data);
            $http.post("/user/api/user/update-user-data", data, {
                withCredentials: true,
                headers: {'Content-Type': undefined},
                transformRequest: angular.identity
            }).then(function (data) {
                console.log(data);
                //return;
                if (data.status == 201) {
                    $scope.userNameExist = true;
                    $scope.dataLoading = false;
                    return;
                } else {
                    $scope.userNameExist = false;
                }
                $scope.savingSuccess = "User Entry Updated Successfully";
                $("#savingSuccess").show().delay(5000).slideUp(1000, function () {
                    $scope.selectedStyle = 0;
                });
                $scope.blank();
                $scope.btnSave = true;
                $scope.btnUpdate = false;
                $scope.allUserList($scope.user_type, 1);
                $scope.dataLoading = false;
                $scope.passwordShow = false;
                $scope.passwordValidation = true;
            }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                }

                $scope.savingError = "Something Went Wrong.";
                $("#savingError").show().delay(5000).slideUp(1000, function () {
                    $scope.selectedStyle = 0;
                });
            }).finally(function () {
            })
        }

        $scope.cngUserType = function(user_type) {
            $scope.blank();
            $scope.employee_name_or_id = null;
            $("#employee_name_or_id").val(null);
        }

        $scope.pressDetailsBtn = function(user) {
            $scope.employee_name_or_id = null;
            $("#employee_name_or_id").val(null);
            var data = {
                user_type : user.user_type,
                port_employee_id : user.port_employee_id,
                cnf_employee_id : user.cnf_employee_id,
                custom_employee_id : user.custom_employee_id
            }

            $scope.office_orders = user.office_orders;
            console.log( $scope.office_orders);
           // console.log(data);
           $http({
                url: "/user/api/user/get-employee-details", 
                method: "GET",
                params: data
             }).then(function(data) {
                    console.log(data.data);
                    if(data.data.length > 0) {
                    var userDetails = data.data[0];
                    $scope.userFullName = userDetails.name;
                    $scope.userdesignation = userDetails.designation;
                    $scope.userorg_name = userDetails.organization;
                    $scope.userfather_name = userDetails.father_name;
                    $scope.usermother_name = userDetails.mother_name;
                    $scope.userdate_of_birth = userDetails.date_of_birth;
                    $scope.usernid_no = userDetails.national_id;
                } else {
                    $.growl.warning({message: "No Data Found! Please Input!"});
                }
             }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
             });
        }

        $scope.pressDeleteBtn = function (user) {
            var confirmation = confirm("Do You Want To Delete This Data?");
            //console.log(confirmation);
            if (confirmation) {
                var data = {
                    id: user.id
                }
                $http.post("/user/api/user/delete-user-data", data)
                    .then(function (data) {
                        console.log(data);
                        $scope.savingSuccess = "User Deleted Successfully.";
                        $("#savingSuccess").show().delay(5000).slideUp(1000);
                    }).catch(function (r) {
                        console.log(r)
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        }
                        $scope.savingError = "Something Went Wrong.";
                        $("#savingError").show().delay(5000).slideUp(1000);
                }).finally(function () {
                    $scope.allUserList($scope.user_type_search);
                })
            } else {
                return false;
            }
        }


        //pagination Every Page serial number......
        $scope.serial = 1;
        $scope.itemPerpage = 15;
        $scope.getPageCount = function(n){
            $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        }
        //pagination serial number   End ......

        
        

        // ======================== Autocomplete for Designation start =========================

        // var designationList =[];
        // $http.get("/user/api/user/get-designation-data")
        //     .then(function (data) {
        //         console.log(data);
        //         console.log(data.data[0].designation)

        //         angular.forEach(data.data,function (v,k) {
        //             designationList.push(v.designation);
        //             //  console.log(v.package_type);

        //         })
        //         console.log(designationList);

        //     }).catch(function (r) {
        //     console.log(r)
        //     if (r.status == 401) {
        //         $.growl.error({message: r.data});
        //     } else {
        //         $.growl.error({message: "It has Some Error!"});
        //     }
        // });


        // $("#designation").autocomplete({
        //     source: function (request, response) {
        //         console.log(designationList);
        //         var result = $.ui.autocomplete.filter(designationList, request.term);
        //         //$("#add").toggle($.inArray(request.term, result) < 0);
        //         response(result);
        //         console.log(result);
        //     }
        // });



        // ======================== Autocomplete for Designation end ===========================

        $scope.onSelect = function (selection) {
            console.log(selection);
            $scope.org_id = selection.id;
            $scope.searchTerm = selection.id;
            $scope.org_type_id = selection.org_type_id;
            //$scope.orgName = selection.org_name;
            //$scope.allEmpByCnf(selection.id);
        }

        

        $scope.skipValues = function (org_type_id) {
            return function (value, index, array) {
                if (org_type_id != 4) {
                    return $scope.allRoleData;
                } else {
                    return $scope.allRoleData.indexOf(value) === 4;
                }
            }
        }

        // //Weighbridge Scale Start
        // $scope.scaleOptions = [
        //     {value: 1, text: 'Scale No 1'},
        //     {value: 2, text: 'Scale No 2'},
        //     {value: 3, text: 'Scale No 3'},
        //     {value: 4, text: 'Scale No 4'},
        //     {value: 5, text: 'Scale No 5'},
        //     {value: 6, text: 'Scale No 6'}
        // ];
        // $scope.scale = $scope.scaleOptions[0].value;
        $scope.roleWarehouse = true;


    }).directive('fileModel', ['$parse', function ($parse) {
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
}]).directive('autocomplete', ['autocomplete-keys', '$window', '$timeout', function (Keys, $window, $timeout) {
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
}]).factory('autocomplete-keys', function () {
    return {
        upArrow: 38,
        downArrow: 40,
        enter: 13,
        escape: 27
    };
});