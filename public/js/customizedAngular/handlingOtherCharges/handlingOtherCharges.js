angular.module('handlingOtherChargesApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('handlingOtherChargesController', function($scope, $http){

        $scope.Validation = function() {
            if($scope.handilingOtherChargeForm.$invalid) {
                $scope.submitted = true;
                return false;
            } else {
                $scope.submitted = false;
                return true;
            }
        }
        $scope.v_i = 0;
        angular.forEach(portList, function (value, key)
        {
            $scope.v_i = $scope.v_i + 1;
            $scope.port_id_list = key;

        });
        console.log($scope.v_i == 1);
        if($scope.v_i == 1){
            $scope.port_id = $scope.port_id_list.toString();
        }
        console.log($scope.port_id_list);
        // $scope.UpdateHide = false;
        // $scope.Validation_Exit = function() {
        //     if($scope.ExTruckEntryExitForm_exit.$invalid) {
        //         $scope.submitted_exit = true;
        //         return false;
        //     } else {
        //         $scope.submitted_exit = false;
        //         return true;
        //     }
        // }

        $scope.Blank = function() {
            $scope.truck_no = null;
            $scope.driver_name = null;
            $scope.entry_datetime = null;
            $scope.rate_of_charges = null;
            $scope.indian_truck_type_value = null;
            $scope.port_id = null;

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

        var min = new Date().getFullYear()-5;
        var max = min + 10;
        $scope.years = [];
        var j=0;
        for (var i = min; i<=max; i++){
            $scope.years[j++] = {value: i, text: i};
        }
        console.log($scope.years);



        $scope.Save = function() {

            if($scope.Validation() == false) {
                return;
            }
       //     console.log($scope.Charge_type);
       //     console.log($scope.charge_rate);
       //     console.log($scope.charge_year);

            var data = {
                Charge_type : $scope.Charge_type,
                port_id : $scope.port_id,
                charge_rate : $scope.charge_rate,
                charge_year : $scope.charge_year

            }
            // console.log($scope.rate_of_charges);
            console.log(data);
            // return;
            $http.post("/charges/api/tariff/save-handling-charge",data)
                .then(function(response) {

                    // console.log(response);
                    console.log(response.data);
                    if(response.data=='Duplicate'){


                        // $scope.savingSuccess = 'Kene color';
                        // $("#savingSuccess").show().delay(5000).slideUp(2000);
                        $scope.savingError = 'Sorry! Duplicate Can Not Entry';
                        $("#savingError").show().delay(5000).slideUp(2000);
                        $scope.GetAllExportTruck();
                        $scope.Blank();

                    }else {
                        $scope.savingSuccess = 'Successfully Inserted';
                        $("#savingSuccess").show().delay(5000).slideUp(2000);
                        $scope.GetAllExportTruck();
                        $scope.Blank();
                        if($scope.v_i == 1){
                            $scope.port_id = $scope.port_id_list.toString();
                        }
                    }

                    $scope.Charge_type = '';
                    $scope.charge_rate = '';
                    $scope.charge_year = '';

                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

                $scope.savingError = 'Something wnt Wrong';
                $("#savingError").show().delay(5000).slideUp(2000);
            }).finally(function () {


            });
        }

        $scope.update = function () {
            if($scope.Validation() == false) {
                return;
            }

            var data = {

                id:$scope.charge_id,
                Charge_type_id : $scope.Charge_type,
                charge_rate : $scope.charge_rate,
                charge_year :$scope.charge_year,
                port_id :$scope.port_id
            }
            // console.log($scope.truck_type);
            $http.post("/charges/api/tariff/update-handiling-others-charges",data)
                .then(function(response) {

                    console.log(response.data);
                    if(response.data == 'Duplicate'){

                        $scope.savingError = 'Sorry! Duplicate Can Not Entry';
                        $("#savingError").show().delay(5000).slideUp(2000);
                        $scope.GetAllExportTruck();
                        $scope.Blank();

                    }else {
                        $scope.savingSuccess = 'Truck Successfully Updated';
                        $("#savingSuccess").show().delay(5000).slideUp(2000);
                        $scope.Charge_type  = '';
                        $scope.charge_rate = '';
                        // $scope.UpdateHide = false;
                        $scope.charge_year = '';
                        $scope.port_id = null;
                        $scope.Blank();
                        $scope.updateBtn = false;
                        $scope.GetAllExportTruck();
                        if($scope.v_i == 1){
                            $scope.port_id = $scope.port_id_list.toString();
                        }
                    }





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
            }).finally(function () {


            });


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


        $http.get("/charges/api/tariff/all-handiling-other-charges-details")
            .then(function(data){
                console.log(data.data);

                    $scope.chargeTypeArray = data.data;
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
            // $scope.UpdateHide = true;
            $scope.updateBtn = true;
            $scope.charge_id = i.id;

            $scope.Charge_type = i.charge_id;
            $scope.charge_rate = i.rate_of_charges;
            $scope.charge_year  = i.charges_year;
            $scope.port_id = i.port_id.toString();


        }

        // $scope.entranceFeefun = function () {
        //
        //     // console.log($scope.entrance_fee);
        //
        //     console.log($scope.rate_of_charges)
        //     $scope.charge_fee = $scope.rate_of_charges;
        //
        //     $scope.message = "Charge: ";
        //
        //
        //
        // }



        $scope.delete = function (i) {

            console.log(i);

            $scope.amount_of_charge = i.rate_of_charges;
            $scope.charge_type = i.type_of_charge;
            $scope.charge_type_year = i.charges_year;
            $scope.de_id = i.id;

        }

        $scope.deleteTruck = function () {

            $http.get("/charges/api/tariff/delete-handiling-other-charges/" + $scope.de_id)
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

                console.log('error')

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {


            });
        }


        //pagination Every Page serial number......
        $scope.serial = 1;
        $scope.itemPerpage = 10;
        $scope.getPageCount = function(n){
            $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        }
        //pagination serial number   End ......


        $scope.searchDateWiseAllTrucks = function (id) {
            console.log(id);
            if(($scope.port_id_search == undefined) || ($scope.port_id_search == '')){
                $scope.port_id_search = null;
            }
            if(($scope.tariff_year_search == undefined) || ($scope.tariff_year_search == '')){
                $scope.tariff_year_search = null;
            }

            var data = {
                port_id_search : $scope.port_id_search,
                tariff_year_search : $scope.tariff_year_search

            }
            console.log(data);
            $http.post("/charges/api/tariff/date-wise-all-charge-details", data)
                .then(function (data){
                    $scope.allChargesData = data.data;
                    console.log($scope.allChargesData);
                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {


            });
        }






        $scope.GetAllExportTruck = function() {
            $http.get('/charges/api/tariff/get-all-charges-data-details')
                .then(function(data) {

                    $scope.allChargesData = data.data;

                    console.log($scope.allChargesData )

                    // var today = new Date();
                    // var Y = today.getFullYear();
                    // var M = today.getMonth()+1;
                    // var D = today.getDate();
                    //
                    // if(today.getMonth()+1 < 10)
                    //     M = "0"+M;
                    // if(today.getDate() < 10)
                    //     D = "0"+D;
                    // $scope.entry_datetime = Y+"-"+M+"-"+D;

                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            }).finally(function () {


            });
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