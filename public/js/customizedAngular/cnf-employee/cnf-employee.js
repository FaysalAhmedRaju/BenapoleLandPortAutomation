angular.module('cnfEmployeeApp', ['angularUtils.directives.dirPagination','customServiceModule'])
	.controller('cnfEmployeeCtrl', function($scope, $http,enterKeyService){

		$http.get("/c&f/api/c&f/get-all-cnf-organization")
			.then(function(data){
				$scope.allCnfOrgData = data.data;
				$scope.org_id = data.data[0].id;
                console.log($scope.org_id);
				//$scope.allEmpByCnf($scope.org_id);
			}).catch(function(r){
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function(){

        })


        $scope.btnSave = true;
        $scope.btnUpdate = false;
		// $scope.save = function() {
		// 	var data = {
		// 		org_id : $scope.org_id,
		// 		emp_id : $scope.emp_id,
		// 		emp_name : $scope.emp_name,
		// 		address : $scope.address,
		// 		national_id : $scope.national_id,
		// 		date_of_birth : $scope.date_of_birth,
		// 		designation : $scope.designation,
		// 		phone_no : $scope.phone_no,
		// 		email : $scope.email,
		// 		mobile : $scope.mobile
		// 	}

        // $('#cnf_name_autocomplete').autocomplete({
        //     source: "/warehouse/api/delivery/ain-no-cnf-name-data",
        //     minLength: 3,
        //     // autoFocus:true,
        //     // displayKey: 'Importer_Name',
        //     select: function (event, ui) {
        //         // $scope.$watch('m_Importer_Name', function (val) {
        //         //  $("#m_Importer_Name").val(ui.item.id)
        //         // }, true);
        //
        //         $("#m_Importer_Name_display").val(ui.item.impoeter_name);
        //         // $("#Importer_Name").val(ui.item.id);
        //
        //         // #display_id
        //         $('#m_Importer_Name').val();
        //         $("#only_ain_no").val(ui.item.id);
        //         // $("#Importer_Name").val(ui.item.id);
        //
        //         // $scope.padfd = $("#m_Importer_Name").val(ui.item.id)
        //         //  console.log($scope.padfd);
        //         // $("#m_Vat_importer_NO").val(ui.item.impoeter_name);
        //         //console.log($("#m_Importer_Name").val(ui.item.id))
        //         // console.log($("#m_Importer_Name").val());
        //         // console.log("selected id: ",ui.item.id)
        //         $scope.cnf_name = ui.item.cnf_name;
        //         $scope.ain_no = ui.item.ain_no;
        //         console.log(ui.item);
        //         $scope.vatId_importer_name = ui.item.id;
        //         // console.log( $scope.vatId_importer_name);
        //         // $scope.Importer_Name = ui.item.id;
        //         if ($scope.vatId_importer_name != null) {
        //             // $scope.imp_name_from_Importer=true;
        //             // $scope.vat_no_after_Vat = false;
        //         }
        //     }
        // });

        $('#m_vat_id').autocomplete({
            source: "/c&f/api/c&f/get-c&f-details-data-autocomplete",
            minLength: 3,
            highlightItem: true,
            // autoFocus:true,
            // displayKey: 'Importer_Name',
            response: function (event, ui) {
                console.log(ui.content.length);
                // ui.content is the array that's about to be sent to the response callback.
                // if (ui.content.length == 0) {
                //     $scope.importerNameInput = true;
                //
                //     //$("#vat-not-found").text("No Vat No Found");
                //     $("#importerNameLabel").html('');
                //     $scope.addVat();
                // } else {
                //     $scope.importerNameInput = false;
                //     $("#vat-not-found").empty();
                // }
            },
            select: function (event, ui) {
                // event.preventDefault();
                console.log(ui.item);
                $scope.m_vat_id = ui.item.cnf_name;
                $("#m_vat_id").val(ui.item.cnf_name);

                $scope.cnf_details_id = ui.item.id;
                $scope.importerNameInput = false;
                $("#importerNameLabel").html(ui.item.ain_no);
                console.log($scope.m_vat_id);
                console.log(ui.item.cnf_details_id);
                console.log(ui.item.id);
                console.log($scope.cnf_details_id);
                $scope.allEmpByCnf($scope.cnf_details_id);
                return false;
            },
            change: function (event, ui) {
                $scope.m_vat_id = ui.item.cnf_name;
                if (ui.item == null) {
                    //console.log('no match');
                    //$("#m_vat_id").val('');
                    $("#importerNameLabel").html('');
                    $("#m_vat_id").focus();
                    //$scope.m_vat_id = null;
                    $scope.vatreg_id = $("#m_vat_id").val();
                    $("#vat-not-found").empty();
                    $scope.importerNameInput = true;
                }
            },
            focus: function (event, ui) {
                $scope.m_vat_id = ui.item.cnf_name;
                console.log('facus');
                // if (ui != null) {
                //     defaultVal = ui.item.cnf_name;
                //     $scope.importerNameInput = false;
                // } else {
                //     $scope.importerNameInput = true;
                // }
            },
            search: function () {
                // console.log('8');

            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div>" + item.ain_no + "<br>" + item.cnf_name + "</div>")
                .appendTo(ul);
        };

        enterKeyService.enterKey('#cnfEmployeeForm input ,#cnfEmployeeForm button')

		// 	$http.post("/api/postCnfEmployee/", data)
		// 		.then(function(data){
		// 			//console.log(data);
		// 			$scope.savingSuccess = "Employee added Sucessfully.";
		// 			$("#savingSuccess").delay(5000).slideUp(1000);
		// 			$scope.org_id_show = $scope.org_id;
		// 			$scope.allEmpByCnf($scope.org_id);
		// 			$scope.blank();
		// 		}).catch(function(data){
		// 			$scope.savingError = "Something went Wrong";
		// 			$("#savingError").delay(5000).slideUp(1000);
		// 		}).finally(function(data){
		// 			//$scope.allEmpByCnf($scope.org_id);
		// 		})
		// }
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

            if($scope.cnfEmployeeForm.$invalid) {
                $scope.submitted = true;
                f = 3;
            } else {
                $scope.submitted = false;
            }
            if(f>=1 && f<=3) {
                return false;
            } else {
                return true;
            }
        }

        $scope.save = function() {
            if($scope.validation()== false) {
                return;
            }
            $scope.dataLoading = true;
            var data = new FormData();
            data.append('cnf_detail_id', $scope.cnf_details_id);
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

            $http.post("/c&f/api/c&f/save-cnf-employee-data", data, {
              withCredentials: true,
              headers: {'Content-Type': undefined },
              transformRequest: angular.identity
            }).then(function successCallback(response) {
                //console.log(response.data);
                $scope.dataLoading = false;
                $scope.savingSuccess = "Employee added Sucessfully.";
                $("#savingSuccess").show().delay(5000).slideUp(1000);
                // $scope.org_id_show = $scope.org_id;
                $scope.allEmpByCnf($scope.cnf_details_id);
                $scope.blank();
            }, function errorCallback(r) {
                console.log(r)
                if(r.status == 401) {
                    $.growl.error({message: r.data});
                }
                $scope.dataLoading = false;
                $scope.savingError = "Something went Wrong";
                $("#savingError").show().delay(5000).slideUp(1000);
            }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function(){

            });
        }

		$scope.allEmpByCnf = function(id) {
            // $("#photo").attr("src",null);
			$http.get("/c&f/api/c&f/get-all-employee-by-cnf/"+id)
				.then(function(data){
					if(data.data.length > 0) {
						//console.log(data.data);
						$scope.allEmployeeByOrg = data.data;
						$scope.employeeByOrgShow = true;
                        $scope.orgNameForShow = $scope.orgName;
                        console.log($scope.allEmployeeByOrg);
					} else {
						$scope.employeeByOrgShow = false;
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


		$scope.blank = function() {
            //$scope.photo = null;
			//$scope.org_id = null;
			$scope.emp_id = null;
			$scope.emp_name = null;
			$scope.address = null;
			$scope.national_id = null;
			$scope.date_of_birth = null;
			$scope.designation = null;
			$scope.phone_no = null;
			$scope.national_id_photo = null;
			$scope.email = null;
			$scope.mobile = null;
			$scope.name = null;
			$scope.m_vat_id = null;
			$scope.cnf_details_id = null;
            angular.element("input[type='file']").val(null);
            //$('#photo').val(null);


		}

		$scope.onSelect = function(selection) {
			//console.log(selection.id);
            $scope.org_id = selection.id;
            $scope.orgName = selection.org_name;
            $scope.allEmpByCnf();
		}

        // $scope.onSelectSearch = function(selection) {
        //     $scope.allEmpByCnf(selection.id);
        // }

        $scope.pressUpdateBtn = function(employeeByOrg) {
		    console.log(employeeByOrg);

            $scope.btnSave = false;
            $scope.btnUpdate = true;
            $scope.m_vat_id = employeeByOrg.cnf_name;
            $scope.cnf_details_id = employeeByOrg.details_id;
            $scope.employee_id = employeeByOrg.emp_id;
            $scope.selectedStyle = employeeByOrg.emp_id;



            $scope.name = employeeByOrg.name;
            $scope.address = employeeByOrg.cnf_address;
            $scope.national_id = employeeByOrg.national_id;
            $scope.date_of_birth = employeeByOrg.date_of_birth;
            $scope.designation = employeeByOrg.designation;
            $scope.phone_no = employeeByOrg.phone_no;
            $scope.email = employeeByOrg.cnf_email;
            $scope.mobile = employeeByOrg.cnf_mobile;
            $scope.photo_link = employeeByOrg.photo;
            $scope.national_id_photo_link = employeeByOrg.nid_photo;
            //console.log($scope.phone_no);
        }



        $scope.update = function () {
            if($scope.validation()== false) {
                return;
            }
            $scope.dataLoading = true;
            var data = new FormData();
            //console.log($scope.phone_no);
            data.append('id',  $scope.employee_id);
            data.append('photo', $scope.photo);
            data.append('photo_link', $scope.photo_link);
            data.append('national_id_photo', $scope.national_id_photo);
            data.append('national_id_photo_link', $scope.national_id_photo_link);
            data.append('cnf_detail_id', $scope.cnf_details_id);
            data.append('name', $scope.name);
            data.append('address', $scope.address);
            data.append('national_id', $scope.national_id);
            data.append('date_of_birth', $scope.date_of_birth);
            data.append('designation', $scope.designation);
            data.append('phone_no', $scope.phone_no);
            data.append('email', $scope.email);
            data.append('mobile', $scope.mobile);


            $http.post("/c&f/api/c&f/update-cnf-employee", data, {
              withCredentials: true,
              headers: {'Content-Type': undefined },
              transformRequest: angular.identity
            }).then(function successCallback(response) {
                console.log(response);
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
                $scope.allEmpByCnf($scope.cnf_details_id);
                $scope.blank();



            }, function errorCallback(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                }
                $scope.dataLoading = false;
                $scope.savingError = "Something went Wrong";
                $("#savingError").show().delay(5000).slideUp(1000, function(){
                    $scope.selectedStyle = 0;
                });
            }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function(){

            });
        }

        $scope.pressDeleteBtn = function(employeeByOrg) {
            console.log(employeeByOrg.emp_id);
            var id = employeeByOrg.emp_id;
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
            //console.log(result);
            //console.log(id);
            if(result == true) {
                $http.delete("/c&f/api/c&f/delete-employee-data/"+id)
                    .then(function(data){
                        console.log(data);
                        console.log(data.data);
                        if(data.data == 'deny'){
                            $scope.savingError = "Delete From User First!";
                            $("#savingError").show().delay(5000).slideUp(1000);
                        }else {
                            $scope.savingSuccess = "Employee deleted Sucessfully.";
                            $("#savingSuccess").show().delay(5000).slideUp(1000);
                            $scope.allEmpByCnf(id);
                        }

                    }).catch(function(){
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        } else {
                            $.growl.error({message: "It has Some Error!"});
                        }
                        $scope.savingError = "Something went Wrong";
                        $("#savingError").show().delay(5000).slideUp(1000);
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