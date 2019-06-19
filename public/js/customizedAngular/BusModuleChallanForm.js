angular.module('busModuleChallanFormApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('busModuleChallanFormCtrl', function($scope, $http){
        $scope.existedChallanNoShow = false;
        $scope.savingSuccess = false;
        $scope.savingError = false;
        $scope.Created_challan_list_show = true;
        $scope.Truck_List = true;
        $scope.New_Added_Truck_List = true;
        $scope.existedChallanNoShow = false;
        $scope.SaveBtn = false;
        $scope.updateBtn = true;
        $scope.Created_challan_list_show = false;
        $scope.Truck_List = true;
        $scope.New_Added_Truck_List = true;

        $scope.Validation = function() {
            if($scope.callan_Form_Challan_no.$invalid) {
                $scope.submitted = true;
                return false;
            }else {
                $scope.submitted = false;
                return true;
            }
        }

        $scope.searchChallan=function(id){
          //  console.log(id);
            $scope.dataLoading = true;
            var data = {
                export_challan_no: id
            }
          //  console.log(id);
            $http.post("/export/bus/api/get-all-bus-list-data-details", data)
                .then(function (data){
                   console.log(data.data.length);
                    $scope.dataLoading = false;
                    if(data.data.length == 0){
                        $scope.Truck_List = true;
                        $scope.existedChallanNoShow = false;
                        $scope.SaveBtn = false;
                        $scope.updateBtn = true;
                        $scope.Blank();
                    }

                    $scope.Ch_id = data.data[0].ch_id;
                  //  console.log( $scope.Ch_id)
                    $scope.newTruckSearch = data.data;                  // Challan update .................... :)
                  //  console.log($scope.newTruckSearch);
                    $scope.Truck_List = false;
                    $scope.New_Added_Truck_List = true;
                    $scope.Created_challan_list_show = true;
                    $scope.export_challan_no = $scope.searchChallanNO;

                    var data = {
                        export_challan_no: id
                    }
                    $http.post("/export/bus/api/get-details-challan-with-miscellaneous", data)
                        .then(function (data){
                          //  console.log(data.data.length);
                            if(data.data.length >= 1){

                                $scope.export_cha = data.data[0].export_challan_no;
                                $scope.miscellaneous_name = data.data[0].miscellaneous_name;
                                $scope.miscellaneous_charge =data.data[0].miscellaneous_charge ;
                                $scope.existedChallanNoShow = true;
                                $scope.SaveBtn = true;
                                $scope.updateBtn = false;

                               // console.log(data.data[0]);

                                $scope.challan_list_of_delivery_export_id = data.data[0].delivery_export_id;   // export truck list
                                    console.log($scope.challan_list_of_delivery_export_id);

                                console.log("hi upadate");
                                var truck_id_array = [];
                                var array =  $scope.challan_list_of_delivery_export_id.split(",");
                                angular.forEach(array,function (v,k) {
                                    truck_id_array.push(v)
                                })
                                $scope.challan_truck_array = truck_id_array;
                                $scope.NoofId = $scope.challan_truck_array.length;

                            }else {
                                console.log("first time save")


                                $scope.existedChallanNoShow = false;
                                $scope.SaveBtn = false;
                                $scope.updateBtn = true;

                                var truck_id_array = [];
                                $scope.challan_truck_array = truck_id_array;
                                $scope.NoofId = $scope.challan_truck_array.length;

                            }
                        })
                }).catch(function () {
                $scope.export_challan_no = $scope.searchChallanNO;
            }).finally(function () {

            })
        };

        //====================================#### checkAll ####==============================
        $scope.checkAll = function() {
            var new_truck_array = [];
            // console.log($scope.newTruckSearch);
            angular.forEach( $scope.newTruckSearch, function(ChTruck) {
                ChTruck.clicked = $scope.selectAll;
                // ChTruck.select = $scope.selectAll;
                // ChTruck.clicked = $scope.selectAll;
                //  new_truck_array.push(v)
                // $scope.sync($scope.selectAll,$scope.newTruckSearch.i );
            });
            angular.forEach( $scope.newTruckSearch, function(v,k) {
                new_truck_array.push(v)
                $scope.sync(1,new_truck_array[k]);
                //   console.log(new_truck_array[k]);
            });
            // console.log(new_truck_array[k].id);
        };


        //====================================#### sync ####==============================
        $scope.sync = function(a, ChTruck){
            //console.log("Hello")
            //    console.log(a + "ok" + ChTruck.id); // after search undefine too :)
            if(a){
                // console.log("if");
                // console.log($scope.challan_truck_array.length);
                // console.log($scope.NoofId);


                // add item
                $scope.challan_truck_array.push(ChTruck.id);

                //    console.log($scope.challan_truck_array);
            } else {
                // console.log("else");
                // console.log($scope.challan_truck_array.length);
                // console.log($scope.NoofId);


                for(var i=0 ; i <$scope.challan_truck_array.length; i++) {
                    if($scope.challan_truck_array[i] == ChTruck.id){
                        // console.log($scope.challan_truck_array[i]);
                        // console.log(ChTruck.id)
                        $scope.challan_truck_array.splice(i,1);
                    }


                }
                console.log($scope.challan_truck_array);

            }
            console.log($scope.challan_truck_array);
        };


        //====================================#### isChecked ####==============================
        $scope.isChecked = function(item){

            // console.log(item);
            // console.log($scope.NoofId);
            // console.log($scope.challan_truck_array);


            var match = false;
            for(var i=0 ; i<$scope.NoofId; i++) {
                if($scope.challan_truck_array[i] == item){
                    match = true;
                }
            }

            //  if(match == true && $scope.NoofId != null){
            //  var new_truck_array = [];
            // angular.forEach( $scope.challan_truck_array, function(v,k) {
            //     new_truck_array.push(v);
            //     $scope.sync(0,new_truck_array[k]);  // from this function array pup 1 item when call this value which if return false
            //     console.log(new_truck_array[k]);
            // });
            //
            //  }

            // console.log(match);
            return match;
        };



        $scope.selection = {

        };

        $scope.addTruck = function () {
            var truck_array = [];
            angular.forEach($scope.selection,function (v,k) {
                truck_array.push(k)
            })
            var truck_list_For_challan = truck_array.join();
            console.log(truck_array)
            angular.forEach(truck_array,function (v,k) {
                console.log(v)
            })
            $scope.newTruck = [];
            angular.forEach($scope.challanIncomTruckList,function (vl, k) {
                angular.forEach(truck_array,function (v,k) {
                    if(vl.id == v){
                        $scope.newTruck.push(vl);
                    }
                })
            })
            console.log($scope.newTruck)
        }



        //pagination Every Page serial number......
        $scope.serial = 1;
        $scope.itemPerpage = 10;
        $scope.getPageCount = function(n){
            $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        }
        //pagination serial number   End ......


        $scope.SaveChallan = function() {
            console.log($scope.challan_truck_array);
            var update_challan_id = [];
            angular.forEach($scope.challan_truck_array,function (v,k) {
                update_challan_id.push(v)
            })
            var result = update_challan_id.join();
            console.log(result);

            if(result == ''){
                $scope.savingError = true;
                $scope.savingError = 'Select At Least One Bus!';
                $("#savingError").show().delay(5000).slideUp(2000);
                return;

                //return;
            }


            console.log($scope.from_date);
            var data = {
                searchDate: $scope.from_date,
                miscellaneous_name : $scope.miscellaneous_name,
                miscellaneous_charge : $scope.miscellaneous_charge,
                delivery_export_id_bus_list : result
            }
             console.log(data)
            // console.log(delivery_export_id_bus_list)
            // return;
            $http.post("/export/bus/api/save-bus-challan-data",data)
                .then(function(data) {
                    // console.log(data);
                    // console.log(data.data);
                    if (data.status == 209) {
                        console.log(data.status)
                        $scope.savingError = true;
                        $scope.savingError = 'Challan is already exist!';
                        $("#savingError").show().delay(5000).slideUp(2000);
                        return;
                    }
                    $scope.savingSuccess = true;
                    $scope.savingSuccess = 'Challan Successfully Created';
                    $("#savingSuccess").show().delay(5000).slideUp(2000);
                    $scope.GetAllChallanList();
                    $scope.Created_challan_list_show = false;
                    console.log($scope.challan_truck_array);

                    $scope.challan_truck_array = '';
                    $scope.from_date = '';
                    $scope.selectAll = '';

                    console.log($scope.challan_truck_array);
                    $scope.Truck_List = true;
                    $scope.New_Added_Truck_List = true;
                    $scope.newTruck = null;
                    $scope.Blank();
                }).catch(function(data) {
                    console.log(data)
                $scope.savingError = true;
                $scope.savingError = 'Something Went Wrong';
                $("#savingError").show().delay(5000).slideUp(2000);
            })
        }

        $scope.UpdateChallan = function() {
            console.log($scope.challan_truck_array);
            var update_challan_id = [];
            angular.forEach($scope.challan_truck_array,function (v,k) {
                // console.log(v);
                update_challan_id.push(v)
            })
            var result = update_challan_id.join();
            console.log(result);

            if(result == ''){
                $scope.savingError = true;
                $scope.savingError = 'Select At Least One Bus!';
                $("#savingError").show().delay(5000).slideUp(2000);
                return;

                //return;
            }


            console.log($scope.from_date);

            var data = {
                searchDate: $scope.from_date,
                miscellaneous_name : $scope.miscellaneous_name,
                miscellaneous_charge : $scope.miscellaneous_charge,
                delivery_export_id_list : result
            }
            $http.post("/export/bus/api/update-bus-challan-data",data)
                .then(function(data) {

                    console.log(data);
                    console.log(data.data);


                    $scope.savingSuccessUpdate = 'Challan Successfully Updated';
                    $("#savingSuccessUpdate").show().delay(5000).slideUp(2000);
                    $scope.GetAllChallanList();
                    $scope.Blank();
                    $scope.searchChallan($scope.searchChallanNO);
                    $scope.GetAllChallanList();
                    $scope.Created_challan_list_show = false;
                    $scope.challan_truck_array = '';
                    $scope.from_date = '';
                    $scope.Truck_List = true;
                    $scope.New_Added_Truck_List = true;
                    $scope.newTruck = null;
                }).catch(function(response) {
                $scope.savingUpdateError = 'Something Went Wrong';
                $("#savingUpdateError").show().delay(5000).slideUp(2000);
            })
        }

        $scope.Blank = function() {
            $scope.export_challan_no = null;
            $scope.miscellaneous_name = null;
            $scope.miscellaneous_charge = null;
            $scope.export_challan_no = null;
        }

        $scope.createChallan = function (i) {

            $scope.New_Added_Truck_List = false;
            console.log($scope.export_challan_no)
            $http.get('/export/bus/api/get-challan-show-details-data/')
                .then(function(data) {
                    console.log(data.data);
                    $scope.challanIncomTruckList = data.data;
                }).catch(function(response) {
            })
        }

        $scope.delete = function (i) {
            console.log(i);
            $scope.Challan_id = i.id;
            $scope.export_challan_no = i.export_challan_no;

        }

        $scope.deleteTruck = function () {
            $http.get("/export/bus/api/delete-bus-challan-data/"+$scope.Challan_id)
                .then(function (data) {
                    $scope.deleteSuccessMsg = true;
                    $("#deleteSuccess").show().fadeTo(1000, 500).slideUp(1500, function () {
                        $("#deleteSuccess").slideUp(2000);
                    });
                    setTimeout(function () {
                        $("#deleteBusModuleChallan").modal('hide')
                    }, 1500)

                    $scope.GetAllChallanList();
                }).catch(function () {
                console.log('error')
            }).finally(function () {
            })
        }

        $scope.GetAllChallanList = function() {
            $http.get('/export/bus/api/get-all-bus-challan-list-data/')
                .then(function(data) {
                    $scope.allExChallanList = data.data;
                //    console.log($scope.allExChallanList)
                }).catch(function(response) {
            })
        }
        $scope.GetAllChallanList();
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