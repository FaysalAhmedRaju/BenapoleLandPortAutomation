angular.module('truckBusTypeApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('truckBusTypeCtrl', function($scope, $http){

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
           // $scope.vehicle_type = null;
            $scope.type_name = null;
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


        //pagination Every Page serial number......
        $scope.serial = 1;
        $scope.itemPerpage = 10;
        $scope.getPageCount = function(n){
            $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        }
        //pagination serial number   End ......



        $scope.Save = function() {
            if($scope.Validation() == false) {
                return;
            }
            var data = {
                vehicle_type : $scope.vehicle_type,
                type_name : $scope.type_name
            }
            $http.post("/export/truck/api/truck-bus-type-save-data",data)
                .then(function(response) {

                    console.log(response.data)
                    if(response.data == 'Duplicate') {

                        $scope.savingError = 'Sorry! Duplicate Vehicle Type Name Can Not Entry.';
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

       // $scope.vehicle_type = 1;

        $scope.update = function () {
            // if($scope.Validation() == false) {
            //     return;
            // }

            var data = {

                id:$scope.d_id,
                vehicle_type : $scope.vehicle_type,
                type_name : $scope.type_name


            }
            // console.log($scope.truck_type);
            $http.post("/export/truck/api/update-truck-bus-type-data",data)
                .then(function(response) {
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
            console.log(now)
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


            $http.post("/export/truck/api/exit-truck-data", data)
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

        $scope.edit = function (i) {  //ok
            console.log(i);
            $scope.updateBtn = true;
            $scope.d_id = i.id;
            $scope.type_name = i.type_name;
            // $scope.driver_name = i.driver_name;
            $scope.vehicle_type = i.vehicle_type.toString();
            //	$scope.truck_type = i.truck_type.toString();

            // $scope.haltage_day = i.haltage_day;
            // $scope.rate_of_charges = i.entrance_fee;
            // $scope.indian_truck_type_value = i.truck_type;

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
            $scope.type_name_i = i.type_name;
            $scope.de_id = i.id;

        }

        $scope.deleteTruck = function () {
            // console.log($scope.de_id)
            $http.get("/export/truck/api/delete-vehicle-type-data/" + $scope.de_id)
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

        $scope.GetAllExportTruck = function() {
            $http.get('/export/truck/api/all-vehicle-type-data/')
                .then(function(data) {
                    $scope.allExTrucks = data.data;
                    console.log($scope.allExTrucks )
                }).catch(function(r){
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
}).filter('vehicleFilter', function () {
    return function (val) {
        var vehicle;
        if(val==1){
            return vehicle='Truck';
        } else if(val ==0) {
            return vehicle='Bus';
        }
        return sex='';
    }
});