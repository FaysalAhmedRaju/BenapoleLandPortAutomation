angular.module('BusModuleEntryFormApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('BusModuleEntryFormCtrl', function($scope, $http){

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
            $scope.bus_no = null;
            $scope.entry_datetime_bus = null;
            $scope.rate_of_charges = null;
            $scope.haltage_day = null;
            $scope.bus_type_name = null;
            $scope.show_haltage_day = false;
            $scope.charge_fee = '';
            $scope.message = '';
            $scope.truck_type = '0';
        }

        $scope.haltage_day_select = function(){
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
                bus_no : $scope.bus_no,
                entry_datetime_bus : $scope.entry_datetime_bus,
                haltage_day : $scope.haltage_day,
                rate_of_charges : $scope.rate_of_charges,
                bus_type : $scope.bus_type_name
            }
            // console.log($scope.rate_of_charges);
            // console.log(data);
            // return;

            // console.log( $scope.rate_of_charges)
            $http.post("/export/bus/api/save-bus-entry-data",data)
                .then(function(response) {


                    console.log(response);

                    if(response.data == 'Duplicate'){


                        $scope.savingError = 'Sorry! Duplicate Bus No. Can Not Entry.';
                        $("#savingError").show().delay(5000).slideUp(2000);
                        $scope.GetAllExportTruck();
                        $scope.Blank();

                    }
                    else {

                        $scope.savingSuccess = 'Bus Successfully Inserted';
                        $("#savingSuccess").show().delay(5000).slideUp(2000);
                        $scope.GetAllExportTruck();
                        $scope.Blank();

                    }




                }).catch(function(response) {
                    console.log(response);

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
                bus_no : $scope.bus_no,
                // driver_name : $scope.driver_name,
                entry_datetime_bus : $scope.entry_datetime_bus,
                haltage_day : $scope.haltage_day,
                rate_of_charges : $scope.rate_of_charges,
                bus_type : $scope.bus_type_name

            }
            // console.log($scope.truck_type);
            $http.post("/export/bus/api/update-bus-data",data)
                .then(function(response) {
                    console.log(response);

                    $scope.savingSuccess = 'Bus Successfully Updated';
                    $("#savingSuccess").show().delay(5000).slideUp(2000);
                    $scope.Blank();
                    $scope.updateBtn = false;
                    $scope.GetAllExportTruck();
                }).catch(function(response) {

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
            $scope.diff = Date($scope.exit_datetime) - Date($scope.exTruckEntryDatetime);
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


            $http.post("/export/bus/api/update-exit-bus-data", data)
                .then(function (data) {
                    $scope.exit_datetime = null;
                    $scope.id= null;

                    $scope.savigSuccessCombo = true;
                    $scope.savingSuccess_combo = 'Successfully Exited';
                    // $("#savingSuccessCombo").show().delay(5000).slideUp(2000);
                    $("#savingSuccessCombo").show().fadeTo(1000, 500).slideUp(1500, function () {
                        $("#savingSuccessCombo").slideUp(2000);
                    });
                    setTimeout(function () {
                        $("#ExExitModal").modal('hide')

                    }, 1500)

                }).catch(function () {
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


        $scope.edit = function (i) {

            $scope.updateBtn = true;
            $scope.d_id = i.id;
            $scope.bus_type_name = i.truck_bus_type;
            $scope.bus_no = i.truck_bus_no;
            $scope.entry_datetime_bus = i.entry_datetime;
            // $scope.haltage_day = i.haltage_day;
            $scope.rate_of_charges = i.entrance_fee;


        }




        $scope.delete = function (i) {

            // console.log(i);

            $scope.de_driver_name = i.driver_name;
            $scope.de_truck_no = i.truck_bus_no;
            $scope.de_id = i.id;

        }

        $scope.deleteTruck = function () {

            $http.get("/export/bus/api/delete-bus-entry-data/" + $scope.de_id)
                .then(function (data) {

                    $scope.deleteSuccessMsg = true;
                    $("#deleteSuccess").show().fadeTo(1000, 500).slideUp(1500, function () {
                        $("#deleteSuccess").slideUp(2000);
                    });

                    $scope.GetAllExportTruck();

                    setTimeout(function () {
                        $("#deleteManifestConfirm").modal('hide')

                    }, 1500)


                }).catch(function () {

                console.log('error')

            }).finally(function () {


            })
        }


        $scope.searchDateWiseAllBuses = function (id) {
            //console.log(id);

            var data = {
                from_date_buses: id
            }

            $http.post("/export/bus/api/get-all-bus-entry-data", data)
                .then(function (data){

                    $scope.allExTrucks = data.data;
                    console.log($scope.allExTrucks)


                }).catch(function(response) {

            })
        }




        $scope.GetAllExportTruck = function() {
            $http.get('/export/bus/api/get-all-export-bus-data/')
                .then(function(data) {

                    $scope.allExTrucks = data.data;
                    //php artisan config:clear  console.log($scope.allExTrucks)




                    var today = new Date();
                    var Y = today.getFullYear();
                    var M = today.getMonth()+1;
                    var D = today.getDate();

                    if(today.getMonth()+1 < 10)
                        M = "0"+M;
                    if(today.getDate() < 10)
                        D = "0"+D;
                    $scope.entry_datetime_bus = Y+"-"+M+"-"+D;


                    $scope.rate_of_charges = $scope.entrance_fee[0].rate_of_charges;

                    $scope.charge_fee = $scope.entrance_fee[0].rate_of_charges;

                    $scope.message = "Charge: ";



                }).catch(function(response) {

            })
        }
        $scope.GetAllExportTruck();



        // ==================== only  Bus Code=====================

        $http.get("/export/bus/api/bus-type-data-details")
            .then(function(data){
                    // console.log(data.data);
                    $scope.allBusTypeData = data.data;
                }
            )




        $http.get("/export/bus/api/entrance-fee-for-bus-entry")
            .then(function(data){
                    // console.log(data.data);
                    $scope.entrance_fee=data.data;


                    $scope.rate_of_charges = $scope.entrance_fee[0].rate_of_charges;

                    $scope.charge_fee = $scope.entrance_fee[0].rate_of_charges;

                    $scope.message = "Charge: ";



                }
            )

        $scope.entranceFeefun = function(){
            console.log($scope.rate_of_charges)
            $scope.charge_fee = $scope.rate_of_charges;
            $scope.message = "Charge: ";
        }



        // ===================== End Bus Code====================








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