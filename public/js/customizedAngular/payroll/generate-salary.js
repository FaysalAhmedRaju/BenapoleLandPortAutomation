angular.module('GenerateSalaryApp', ['angularUtils.directives.dirPagination'])
	.controller('GenerateSalaryCtrl', function($scope, $http){
		var date, d, m,y;

		//--------------------------- autoComplete Start -----------------------

        $('#employee_name').autocomplete({
            source: "/accounts/salary/generate-salary/api/get-employee-name-details",
            minLength: 3,
            highlightItem: true,
            // autoFocus:true,
            // displayKey: 'Importer_Name',
            response: function (event, ui) {
                console.log(ui.content.length);  //how many data it will bring
                // ui.content is the array that's about to be sent to the response callback.
                // if (ui.content.length == 0) {
                //
                //
                //     $("#importerNameLabel").html('');
                //     $scope.addVat();
                // } else {
                //
                //
                // }
            },
            select: function (event, ui) {
                // event.preventDefault();
              console.log(ui.item);
              $scope.employee_name = ui.item.name;
              $("#employee_name").val(ui.item.name);
              $scope.auto_emp_id = ui.item.employee_id;

                console.log($scope.employees);
                console.log($scope.auto_emp_id);
                angular.forEach($scope.employees, function(itm){
                    console.log(itm.selected)

                    if(itm.id == $scope.auto_emp_id){
                        if(itm.selected == true){
                            itm.selected = false;
                        }else{
                            itm.selected = true;
                        }

                    }
                    console.log('Afeter: '+ itm.selected)
                    // itm.selected = toggleStatus;
                });


                return false;
            },
            change: function (event, ui) {


            },
            focus: function (event, ui) {
                console.log('facus');
                console.log(ui.item)

                if (ui != null) {
                    defaultVal = ui.item.emp_id_name;
                } else {

                }
            },
            search: function () {


            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div>" + item.emp_id_name + "<br>" + item.name + "</div>")
                .appendTo(ul);
        };

        //--------------------------- autoComplete End -----------------------


        $scope.employees = employees;
		//console.log(employees);
		var emp_ids = [0];
		$scope.toggleAll = function() {
		    var toggleStatus = $scope.isAllSelected;
		    console.log(toggleStatus);
		    angular.forEach($scope.employees, function(itm){itm.selected = toggleStatus;});
		    $scope.emp_validation = false;
		 }
		  
		$scope.optionToggled = function(){
			console.log("ng-changed clicked")
			console.log($scope.employees.every(function(itm){itm.selected}));
		    $scope.isAllSelected = $scope.employees.every(function(itm){return itm.selected;});
			console.log($scope.isAllSelected );
		    $scope.emp_validation = false;

		}
		$scope.emp_validation = false;
		$scope.submit = false;
		$scope.generateSalary = function() {
			console.log($scope.GenerateSalary.$invalid);
			//console.log(($scope.GenerateSalary.emp_id.$invalid) || ($scope.GenerateSalary.month_year.$invalid) == true);
			$scope.emp_validation = false;
			$scope.count_emp = 0; 
			angular.forEach($scope.employees, function(emp) { 
				if(emp.selected == true) {
					emp_ids.push(emp.id);
					$scope.count_emp++;
				}
			});
			console.log($scope.GenerateSalary.month_year.$invalid); //month is ok false

			console.log($scope.count_emp)

            console.log($scope.GenerateSalary.$invalid);  //not ok true
			if($scope.GenerateSalary.$invalid) {
				if($scope.count_emp == 0) {
					$scope.emp_validation = true;
                    $scope.submit = true;
                    return;
				}else {
					if ($scope.GenerateSalary.month_year.$invalid){
                        $scope.emp_validation = true;
                        $scope.submit = true;
                        return;
					}else {
                        $scope.emp_validation = false;
                        $scope.submit = false;
					}
				}

			} else {
				$scope.emp_validation = false;
				$scope.submit = false;
			}
			emp_ids.join(',');

			//return;
			$scope.dataLoading = true;
			date = new Date($scope.month_year);
            d = date.getDate();
            m = date.getMonth()+1;
            y = date.getFullYear();
            $scope.date_db_format = y+"-"+m+"-"+d;
            console.log(emp_ids);
            console.log( $scope.date_db_format);
            var data = {
            	month_year : $scope.date_db_format,
            	emp_ids : emp_ids
            }
            console.log(data);

            $http.post("/accounts/salary/generate-salary/api/get-employees-salary",data)
            	.then(function(data){
            		console.log(data);
            		if(data.data.length>0) {
            			emp_ids = [0];
            			//console.log(data.data);
            			$scope.salarySheet = true;
            			$scope.getEmployeesSalary = data.data;
            			console.log($scope.getEmployeesSalary);
            			$scope.saveSalaryDiv = true;
            		} else {
            			$scope.salarySheet = false;
            			$scope.saveSalaryDiv = false;
            		}
            		$scope.emp_validation = false;
					$scope.submit = false;
            	}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            	}).finally(function(){
            		//$scope.saveSalary();
            		$scope.dataLoading = false;
            	})
		}

		$scope.saveSalary = function() {
			$scope.savingData=true;

			var data = {
				payable_month_year : $scope.month_year,
				salaryRows : $scope.getEmployeesSalary
			}
			console.log(data)

			$http.post("/accounts/salary/generate-salary/api/save-employee-salary-data",data)
				.then(function(data){
					console.log(data.data);
					$scope.insertSuccessMsg = true;
                    $("#saveSuccess").show().delay(5000).slideUp(1000);
				}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
				}).finally(function(){
					$scope.savingData=false;
				})
		}
	}).filter('numberFilter', function(){
		return function(val) {
			var number;
			if(val==0) {
				return number = '';
			} else {
				return number = val;
			}
			return number = '';
		}
	});