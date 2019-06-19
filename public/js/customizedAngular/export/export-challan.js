angular.module('ExportChallanApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('ExportChallanCtrl', function($scope, $http){
        $scope.existedChallanNoShow = false;
        $scope.savingSuccess = false;
        $scope.savingError = false;
        // $scope.aftersearchShow = false;
        $scope.Created_challan_list_show = true;
        $scope.Truck_List = true;
        $scope.New_Added_Truck_List = true;

        $scope.existedChallanNoShow = false;
        $scope.SaveBtn = false;

        $scope.updateBtn = true;
        // $scope.ChallanList = null;

        $scope.Created_challan_list_show = false;

        // $scope.searchChallan();
        // $scope.GetAllExportTruck();
        // $scope.createChallan();
        // $scope.GetAllChallanList();

        $scope.Truck_List = true;
        $scope.New_Added_Truck_List = true;

        //---------------- select Multiple items code------------

        
        $scope.singleCheck = function () {

            $scope.countSingle = 0;
            $scope.check =0;
            angular.forEach($scope.number_list,function (value,key) {
                $scope.countSingle++;
                if (value == 1){
                    $scope.check++;
                }

            });

            if($scope.check > 0){
                $scope.checkAtlest = false;
            }else {
                $scope.checkAtlest = false;
            }

            if($scope.countSingle == $scope.$scope.check){
                $scope.checkAll = true;
            }else {
                $scope.checkAll = false;
            }
            
        }




        $scope.Validation = function() {
            if($scope.callan_Form_Challan_no.$invalid) {
                $scope.submitted = true;
                return false;
            } else {
                $scope.submitted = false;
                return true;
            }
        }


        $scope.searchChallan = function (id) {                      //===================== search Challan ==========================//
            //console.log(id);
            $scope.dataLoading = true;
            var data = {
                export_challan_no: id
            }
           // console.log(id);
            $http.post("/export/truck/api/challan-details-data", data)
                .then(function (data){
                     //console.log(data);
                    $scope.dataLoading = false;
                     if(data.data.length == 0){
                         $scope.Truck_List = true;
                         $scope.existedChallanNoShow = false;
                       //  console.log(data.data)
                         $scope.SaveBtn = false;
                         $scope.updateBtn = true;
                         $scope.Blank();
                     }
                     $scope.Ch_id = data.data[0].ch_id;
                    // console.log( $scope.Ch_id)
                    // $scope.aftersearchShow = false;
                    $scope.newTruckSearch = data.data;
                     //console.log(data.data);
                //    console.log($scope.newTruckSearch);
                    $scope.Truck_List = false;
                    $scope.New_Added_Truck_List = true;
                    $scope.Created_challan_list_show = true;

                    $scope.export_challan_no = $scope.searchChallanNO;
                    var data = {
                        export_challan_no: id
                    }
                    $http.post("/export/truck/api/challan-details-data-miscellaneous", data)
                        .then(function (data){
                          console.log(data);
                            if(data.data.length >= 1){
                                $scope.export_cha = data.data[0].export_challan_no;
                                $scope.miscellaneous_name = data.data[0].miscellaneous_name;
                                $scope.miscellaneous_charge =data.data[0].miscellaneous_charge ;
                                $scope.existedChallanNoShow = true;
                                // $scope.SaveBtn = true;
                                // $scope.updateBtn = false;
                                $scope.SaveBtn = true;
                                $scope.updateBtn = false;
                                $scope.challan_list_of_delivery_export_id = data.data[0].delivery_export_id;   // export truck list
                                console.log(data.data[0].delivery_export_id);
                                console.log(data.data);
                                if (data.data[0].delivery_export_id == ''){

                                    console.log("hi");
                                    // console.log($scope.challan_list_of_delivery_export_id);
                                    var truck_id_array = [];

                                    console.log(truck_id_array);
                                    $scope.challan_truck_array = truck_id_array;    //---------------------------- testing array is here ----------------------
                                    console.log($scope.challan_truck_array);
                                    $scope.NoofId = $scope.challan_truck_array.length;


                                }else {
                                    console.log("hi upadate");
                                    var truck_id_array = [];
                                    var array =  $scope.challan_list_of_delivery_export_id.split(",");
                                    console.log(array);
                                    // truck_id_array =  $scope.challan_list_of_delivery_export_id;
                                    angular.forEach(array,function (v,k) {
                                        // console.log(v)
                                        truck_id_array.push(v)
                                    })
                                    //var truck_list_For_challan = truck_id_array.join();


                                  //  console.log(truck_id_array);
                                    $scope.challan_truck_array = truck_id_array;    //---------------------------- testing array is here ----------------------
                             //       console.log( $scope.challan_truck_array);   // challan truck list id's
                                    $scope.NoofId = $scope.challan_truck_array.length;
                              //      console.log($scope.NoofId);
                                }

                                $scope.challan_list_of_delivery_export_id ;

                               // console.log(truck_list_For_challan);
                               // var  truck
                              //$scope.selection = {};
                              // $scope.selection =['2064'];
                              //  $scope.selection =  $scope.challan_list_of_delivery_export_id;
                              //   console.log($scope.selection)
                            }else {
                               console.log("update Error Check") //  it's save time works not update time
                                $scope.existedChallanNoShow = false;
                                //$scope.export_challan_no = data.data[0].export_challan_no;
                                $scope.SaveBtn = false;
                                $scope.updateBtn = true;


                                $scope.challan_list_of_delivery_export_id ;
                               // console.log($scope.challan_list_of_delivery_export_id);
                                var truck_id_array = [];
                                // var array =  $scope.challan_list_of_delivery_export_id;
                              //  console.log(array);
                                // truck_id_array =  $scope.challan_list_of_delivery_export_id;
                                // angular.forEach(array,function (v,k) {
                                //     // console.log(v)
                                //     truck_id_array.push(v)
                                // })
                                //var truck_list_For_challan = truck_id_array.join();


                               // console.log(truck_id_array);
                                $scope.challan_truck_array = truck_id_array;    //---------------------------- testing array is here ----------------------
                              //  console.log($scope.challan_truck_array);
                                $scope.NoofId = $scope.challan_truck_array.length;
                              //  console.log($scope.NoofId);
                            }
                        })
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                }
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

            console.log(item);
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



        // $scope.data = [
        //     {
        //         "id": "2062",
        //         "data": "one",
        //     },
        //
        //     {
        //         "id": "2064",
        //         "data": "three",
        //     },
        // ];






      //   var TruckListArray = [3905,3906,3907,3908];
      //  // console.log(TruckListArray);
      //   $scope.challan = { TruckList: [] };
      //
      // //  $scope.challan.TruckList = TruckListArray[0].split(",") ;
      //  // $scope.challan.TruckList = 3904;
      //   console.log($scope.challan.TruckList);



        // $scope.truckIdChecked = function () {
        //     return true;
        //     // console.log($scope.selection);
        //     // console.log($scope.challan_list_of_delivery_export_id);
        //
        // };





        $scope.selection = {
        };



        $scope.addTruck = function () {
            var truck_array = [];
            console.log($scope.selection);
            angular.forEach($scope.selection,function (v,k) {
                truck_array.push(k)
            })
            var truck_list_For_challan = truck_array.join();
            console.log(truck_array); //truck array
            angular.forEach(truck_array,function (v,k) {
                console.log(v)
            })
            $scope.newTruck = [];
            angular.forEach($scope.challanIncomTruckList,function (vl, k) {
                angular.forEach(truck_array,function (v,k) {
                    // console.log(v)
                    if(vl.id == v){
                        $scope.newTruck.push(vl);
                    }
                })
            })
            console.log($scope.newTruck)
        }


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
                    $scope.savingError = 'Select At Least One Truck!';
                    $("#savingError").show().delay(5000).slideUp(2000);
                    return;

                    //return;
                }

           // console.log($scope.from_date);
            // if($scope.Validation() == false) {
            //     return;
            // }




          //   var Up_challan_array = [];
          // //  console.log(Up_challan_array)
          //   console.log($scope.selection);
          //   angular.forEach($scope.selection,function (v,k) {
          //       Up_challan_array.push(k)
          //   })
          //   var Updated_truck_id = Up_challan_array.join();
          //   console.log(Updated_truck_id);


            // if(Up_challan_array.length == 0){
            //
            //     $scope.savingError = 'Add Truck is required';
            //     $("#savingError").show().delay(5000).slideUp(2000);
            //     return;
            // }
            // console.log($scope.miscellaneous_name)
            // console.log($scope.miscellaneous_charge)
            // console.log($scope.export_challan_no)



            var data = {

                // export_challan_no : $scope.export_challan_no,
                searchDate: $scope.from_date,
                miscellaneous_name : $scope.miscellaneous_name,
                miscellaneous_charge : $scope.miscellaneous_charge,
                delivery_export_id_truck_list : result
            }
          //   return;
            $http.post("/export/truck/api/save-challan-data",data)
                .then(function(data) {
                    console.log(data.data);
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

                    // $scope.searchChallan();
                    // $scope.GetAllExportTruck();
                   // $scope.createChallan();
                    // $scope.GetAllChallanList();

                    $scope.Truck_List = true;
                    $scope.New_Added_Truck_List = true;

                    $scope.newTruck = null;

                    $scope.Blank();
                    $scope.from_date = '';
                    $scope.selectAll = '';

                    console.log($scope.challan_truck_array)

                    $scope.challan_truck_array = '';

                    console.log($scope.challan_truck_array)

                   // $scope.searchChallan($scope.searchChallanNO);

                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingError = true;
                $scope.savingError = 'Something Went Wrong';
                $("#savingError").show().delay(5000).slideUp(2000);
            })
        }


        //pagination Every Page serial number......
        $scope.serial = 1;
        $scope.itemPerpage = 10;
        $scope.getPageCount = function(n){
            $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        }
        //pagination serial number   End ......




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
                $scope.savingError = 'Select At Least One Truck!';
                $("#savingError").show().delay(5000).slideUp(2000);
                return;

                //return;
            }



          //   var Up_challan_array = [];
          //   //  console.log(Up_challan_array)
          // //  console.log($scope.selection);
          //   angular.forEach($scope.selection,function (v,k) {
          //       Up_challan_array.push(k)
          //   })
          //
          //   var Updated_truck_id = Up_challan_array.join();
          //
          //  console.log(Updated_truck_id);


            var data = {

                // export_challan_no : $scope.export_challan_no,
                searchDate: $scope.from_date,
                miscellaneous_name : $scope.miscellaneous_name,
                miscellaneous_charge : $scope.miscellaneous_charge,
                delivery_export_id_truck_list : result
            }


            $http.post("/export/truck/api/update-challan-data",data)
                .then(function(data) {
                    console.log(data);
                    $scope.savingSuccessUpdate = 'Challan Successfully Updated';
                    $("#savingSuccessUpdate").show().delay(5000).slideUp(2000);
                    $scope.GetAllChallanList();
                    $scope.Blank();
                    $scope.searchChallan($scope.searchChallanNO);
                    $scope.GetAllChallanList();
                    $scope.Created_challan_list_show = false;
                    $scope.Truck_List = true;
                    $scope.New_Added_Truck_List = true;
                    $scope.newTruck = null;
                    $scope.from_date = '';
                    $scope.selectAll = '';
                    $scope.challan_truck_array = '';

                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                    console.log(r)
                $scope.savingUpdateError = 'Something Went Wrong';
                $("#savingUpdateError").show().delay(5000).slideUp(2000);
            })


        }



        $scope.Blank = function() {

            $scope.export_challan_no = null;
            $scope.miscellaneous_name = null;
            $scope.miscellaneous_charge = null;
            $scope.export_challan_no = null;
            $scope.selectAll = '';

            // $scope.truck_type = '0';
        }

        // $scope.ExExitDetails = function(exTruck) {
        //     $scope.id = exTruck.id;
        //     $scope.exTruck_no = exTruck.truck_no;
        //     $scope.exDriver_name = exTruck.driver_name;
        //     $scope.exTruckEntryDatetime = exTruck.entry_datetime;
        //     $scope.exit_datetime = exTruck.exit_datetime;
        // }

        $scope.createChallan = function (i) {
            // $scope.updateBtn = true;
            console.log(i)
            $scope.New_Added_Truck_List = false;
            //
            // $scope.export_challan_no_edit = i.export_challan_no;
            // $scope.challan_id = i.id;
            // $scope.export_challan_no = $scope.i;
            console.log($scope.export_challan_no)
            // $scope.edit_miscellaneous_charge = i.miscellaneous_charge;
            // $scope.edit_miscellaneous_name = i.miscellaneous_name;
            // $http.get('/api/getChallanUpdate/'+i)
            //     .then(function(data) {
            //         console.log(data.data);
            //         $scope.CompleteChallan = data.data;
            //
            //     }).catch(function(response) {
            //
            // })
            $http.get('/export/truck/api/get-challan-show-data/')
                .then(function(data) {
                    console.log(data.data);
                    $scope.challanIncomTruckList = data.data;

                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            })

        }

        $scope.delete = function (i) {

            console.log(i);
            $scope.Challan_id = i.id;
            $scope.export_challan_no = i.export_challan_no;
console.log($scope.Challan_id );
console.log($scope.export_challan_no);
            // $scope.export_challan_no = i.export_challan_no;
            //
            // $scope.truck_no = i.truck_no;
            // $scope.truck_id = i.id;

        }

        $scope.deleteFinalChallanFunc = function () {

            console.log($scope.Challan_id);

            $http.get("/export/truck/api/delete-challan-data/"+$scope.Challan_id)
                .then(function (data) {

                    $scope.deleteSuccessMsg = true;
                    $("#deleteSuccess").show().fadeTo(1000, 500).slideUp(1500, function () {
                        $("#deleteSuccess").slideUp(2000);
                    });

                    // $scope.GetAllExportTruck();
                    // $scope.GetAllChallanList();

                    setTimeout(function () {
                        $("#deleteChallan").modal('hide')

                    }, 1500)

                    $scope.GetAllChallanList();


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


        // $scope.GetAllExportTruck = function() {
        //     $http.get('/api/getAllExTruckForChallan/')
        //         .then(function(data) {
        //             //console.log(data.data);
        //             $scope.allExTrucks = data.data;
        //              // console.log($scope.allExTrucks[0].id)
        //
        //
        //         }).catch(function(response) {
        //
        //     })
        // }
        // $scope.GetAllExportTruck();



        $scope.GetAllChallanList = function() {
            $http.get('/export/truck/api/get-all-challan-list-data/')
                .then(function(data) {
                //    console.log(data.data);
                    $scope.allExChallanList = data.data;
                     console.log($scope.allExChallanList)


                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
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