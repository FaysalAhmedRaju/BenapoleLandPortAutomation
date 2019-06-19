angular.module('ExTruckEntryExitApp', ['angularUtils.directives.dirPagination','customServiceModule'])
	.controller('ExTruckEntryExitCtrl', function($scope, $http){
		
		$scope.Validation = function() {
			if($scope.ExTruckEntryExitForm.$invalid) {
				$scope.submitted = true;
				return false;
			} else {
				$scope.submitted = false;
				return true;
			}
		}

        $scope.Validation_Exit = function() {
            if($scope.ExTruckEntryExitForm_exit.$invalid) {
                $scope.submitted_exit = true;
                return false;
            } else {
                $scope.submitted_exit = false;
                return true;
            }
        }

		$scope.Blank = function() {
			$scope.truck_no = null;
			$scope.driver_name = null;
			$scope.entry_datetime = null;
			$scope.rate_of_charges = null;
            $scope.indian_truck_type_value = null;

			$scope.haltage_day = null;
			$scope.show_haltage_day = false;
            $scope.charge_fee = '';
            $scope.message = '';
			$scope.truck_type = '0';
		}

		$scope.haltage_day_select = function () {

		    if($scope.truck_type == 1){

                $scope.show_haltage_day = true;
            }else {
                $scope.show_haltage_day = false;
            }



        }



		$scope.Save = function() {



			if($scope.Validation() == false) {
				return;
			}
			var data = {
				truck_no : $scope.truck_no,
				// driver_name : $scope.driver_name,
				entry_datetime : $scope.entry_datetime,
                //truck_type : $scope.truck_type,
                haltage_day : $scope.haltage_day,
                entrance_fee : $scope.rate_of_charges,
                truck_type : $scope.indian_truck_type_value
			}
            // console.log($scope.rate_of_charges);
			// console.log(data);
			// return;
			$http.post("/export/truck/api/save-entry-data",data)
				.then(function(response) {

                    console.log(response);
                    console.log(response.data);
                   if(response.data=='Duplicate'){


                       // $scope.savingSuccess = 'Kene color';
                       // $("#savingSuccess").show().delay(5000).slideUp(2000);
                       $scope.savingError = 'Sorry! Duplicate Truck No. Can Not Entry';
                       $("#savingError").show().delay(5000).slideUp(2000);
                       $scope.GetAllExportTruck();
                       $scope.Blank();

                   }else {
                       $scope.savingSuccess = 'Truck Successfully Inserted';
                       $("#savingSuccess").show().delay(5000).slideUp(2000);
                       $scope.GetAllExportTruck();
                       $scope.Blank();
                   }



				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.savingError = 'Something wnt Wrong';
					$("#savingError").show().delay(5000).slideUp(2000);
				})
		}

		$scope.update = function () {
            if($scope.Validation() == false) {
                return;
            }

			var data = {

				id:$scope.d_id,
                truck_no : $scope.truck_no,
                // driver_name : $scope.driver_name,
                entry_datetime : $scope.entry_datetime,
                haltage_day : $scope.haltage_day,
                entrance_fee : $scope.rate_of_charges,
                truck_type : $scope.indian_truck_type_value

			}
			// console.log($scope.truck_type);
            $http.post("/export/truck/api/update-entry-data",data)
                .then(function(response) {

                    console.log(response);

                    $scope.savingSuccess = 'Truck Successfully Updated';
                    $("#savingSuccess").show().delay(5000).slideUp(2000);
                    $scope.Blank();
                    $scope.updateBtn = false;
                    $scope.GetAllExportTruck();
                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingError = 'Something Went Wrong';
                $("#savingError").show().delay(5000).slideUp(2000);
                $scope.updateBtn = false;
            })


        }


        $scope.ExitTruck = function () {

            if($scope.Validation_Exit() == false) {
                $scope.savingError = 'Exit Datetime is required';
                $("#savingError").show().delay(5000).slideUp(2000);
                return;
            }

            // console.log( $scope.exTruckEntryDatetime);
            // console.log($scope.exit_datetime);
                // var difference = $scope.exit_datetime - $scope.exTruckEntryDatetime;
               $scope.diff = Date($scope.exit_datetime) - Date($scope.exTruckEntryDatetime);


            // var now = moment();

            var now  = $scope.exTruckEntryDatetime;
           // console.log(now)
            var then = $scope.exit_datetime;
            console.log(then)

            var d1 = new Date(now);
            var d2 = new Date(then);
            var miliseconds = d2-d1;
            var seconds = miliseconds/1000;
            var minutes = seconds/60;
            var hours = minutes/60;
            var days = hours/24;
            console.log(days)
                if(days <= 0)
                {
                    $scope.ErrorCombo = true;
                    $scope.savingError_combo = 'Please Insert Correct Datetime';
                    $("#savingErrorCombo").show().fadeTo(1000, 500).slideUp(1500, function () {
                        $("#savingErrorCombo").slideUp(2000);
                    });
                    setTimeout(function () {
                        $("#ExExitModal").modal('hide')

                    }, 1500)
                    // $("#savingErrorCombo").show().delay(5000).slideUp(2000);
                    return;
                }

            var data = {
                id:  $scope.id,
                exit_datetime: $scope.exit_datetime
            }


            $http.post("/api/postExitTruck", data)
                .then(function (data) {
                    $scope.exit_datetime = null;
                    $scope.id= null;

                    // $scope.deleteSuccessMsg = true;
                    // $("#deleteSuccess").show().fadeTo(1000, 500).slideUp(1500, function () {
                    //     $("#deleteSuccess").slideUp(2000);
                    // });
                    //
                    // setTimeout(function () {
                    //     $("#ExExitModal").modal('hide')
                    //
                    // }, 1500)

                    $scope.savigSuccessCombo = true;
                    $scope.savingSuccess_combo = 'Successfully Exited';
                    // $("#savingSuccessCombo").show().delay(5000).slideUp(2000);
                    $("#savingSuccessCombo").show().fadeTo(1000, 500).slideUp(1500, function () {
                        $("#savingSuccessCombo").slideUp(2000);
                    });
                    setTimeout(function () {
                        $("#ExExitModal").modal('hide')

                    }, 1500)

                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                    $scope.ErrorCombo = true;
                $scope.savingError_combo = 'Something Went Wrong';
                // $("#savingErrorCombo").show().delay(5000).slideUp(2000);
                $("#savingErrorCombo").show().fadeTo(1000, 500).slideUp(1500, function () {
                    $("#savingErrorCombo").slideUp(2000);
                });

                setTimeout(function () {
                    $("#ExExitModal").modal('hide')

                }, 1500)

            }).finally(function () {

                $scope.GetAllExportTruck();
            })
        }

        $http.get("/export/truck/api/entrance-fee-data")
            .then(function(data){
                    // console.log(data.data);
                    $scope.entrance_fee=data.data;
                  //  console.log($scope.entrance_fee[0].rate_of_charges)

                $scope.rate_of_charges = $scope.entrance_fee[0].rate_of_charges;

                $scope.charge_fee = $scope.entrance_fee[0].rate_of_charges;

                $scope.message = "Charge: ";
                }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });


        $http.get("/export/truck/api/tuck-type-data")
            .then(function(data){
                    $scope.indian_truck_type = data.data;
                    // console.log($scope.indian_truck_type);
                    // console.log($scope.indian_truck_type[1].truck_id);
                    // $scope.indian_truck_type_value = $scope.indian_truck_type[1].truck_id;
                }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });

        // $scope.ExExitDetails = function(exTruck) {
        //     $scope.id = exTruck.id;
        //     $scope.exTruck_no = exTruck.truck_no;
        //     $scope.exDriver_name = exTruck.driver_name;
        //     $scope.exTruckEntryDatetime = exTruck.entry_datetime;
        //     $scope.exit_datetime = exTruck.exit_datetime;
        // }

        $scope.edit = function (i) {
            // console.log(i);
            $scope.updateBtn = true;
			$scope.d_id = i.id;
            $scope.truck_no = i.truck_bus_no;
            // $scope.driver_name = i.driver_name;
            $scope.entry_datetime = i.entry_datetime;
		    //$scope.truck_type = i.truck_type.toString();
            $scope.haltage_day = i.haltage_day;
            $scope.rate_of_charges = i.entrance_fee;
            $scope.indian_truck_type_value = i.truck_bus_type;
            //  console.log( $scope.indian_truck_type)
		}

        $scope.entranceFeefun = function () {

		    // console.log($scope.entrance_fee);

		    console.log($scope.rate_of_charges)
            $scope.charge_fee = $scope.rate_of_charges;

		    $scope.message = "Charge: ";



        }



		$scope.delete = function (i) {

			 console.log(i);

			$scope.de_driver_name = i.driver_name;
			$scope.de_truck_no = i.truck_bus_no;
			$scope.de_id = i.id;

        }

        $scope.deleteTruck = function () {

            $http.get("/export/truck/api/delete-entry-data/" + $scope.de_id)
                .then(function (data) {

                    $scope.deleteSuccessMsg = true;
                    $("#deleteSuccess").show().fadeTo(1000, 500).slideUp(1500, function () {
                        $("#deleteSuccess").slideUp(2000);
                    });

                    $scope.GetAllExportTruck();

                    setTimeout(function () {
                        $("#deleteManifestConfirm").modal('hide')

                    }, 1500)


                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                console.log('error')

            }).finally(function () {


            })
        }


        //pagination Every Page serial number......
        $scope.serial = 1;
		$scope.itemPerpage = 10;
		$scope.getPageCount = function(n){
		    $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        }
        //pagination serial number   End ......


        $scope.searchDateWiseAllTrucks = function (id) {
            //console.log(id);

            var data = {
                from_date_Truck: id
            }

            $http.post("/export/truck/api/date-wise-all-trucks-data", data)
                .then(function (data){

                    $scope.allExTrucks = data.data;
                    console.log($scope.allExTrucks )


                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            })
        }






        $scope.GetAllExportTruck = function() {
			$http.get('/export/truck/api/get-all-truck-details/')
				.then(function(data) {

					$scope.allExTrucks = data.data;
					console.log($scope.allExTrucks )
                    $scope.rate_of_charges = $scope.entrance_fee[0].rate_of_charges;
                    $scope.charge_fee = $scope.entrance_fee[0].rate_of_charges;
                    $scope.message = "Charge: ";

                    var today = new Date();
                    var Y = today.getFullYear();
                    var M = today.getMonth()+1;
                    var D = today.getDate();

                    if(today.getMonth()+1 < 10)
                        M = "0"+M;
                    if(today.getDate() < 10)
                        D = "0"+D;
                    $scope.entry_datetime = Y+"-"+M+"-"+D;

				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
				})
		}
		$scope.GetAllExportTruck();


	}).filter('truckTypeFilter', function () {
    return function (val) {
        var truck;
        if(val==0){
            return truck='Local';
        } else if(val==1) {
            return truck='Foreign';
        }
    }
});