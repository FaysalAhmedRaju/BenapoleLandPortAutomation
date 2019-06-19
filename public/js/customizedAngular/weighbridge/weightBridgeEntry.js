angular.module('weightBridgeEntryApp',['angularUtils.directives.dirPagination', 'customServiceModule'])
    .controller('weightBridgeEntryController', function($scope, $http, $filter, manifestService,enterKeyService) {

        $scope.show = true;
        $scope.ButtonIN = true;
        $scope.showWhenTrWeightFound = true; //TR WEIGHT AND NET WEIGHT EDIT DISABLED
        $scope.serachBytruck = false;
        $scope.wbrdge_time1_notChange = null;
        $scope.wbrdge_time2 = null;
        $scope.wbrdge_time2_notChange = null;

        $scope.searchKey = 'manifestNo';
        $scope.searchKeyHolder = $scope.searchKey;
        $scope.placeHolder = 'Enter Manifest No';
        $scope.select = function() {
            if($scope.searchKey=='manifestNo'){
                $scope.placeHolder = 'Enter Manifest No';
            } else if($scope.searchKey=='truckTypeNo'){
                $scope.placeHolder = 'Enter Truck Type-No';
            } else {
                $scope.placeHolder = null;
            }
        }

        $scope.CountTrucksEntryExitFunc=function () {

            $http.get("/weighbridge/api/count-trucks-todays-entry-exit")  //get showExpenseLimitAlert
                .then(function (data) {

                   //   console.log(data)

                    $scope.entry_truck = data.data[0].entry_truck;
                    $scope.exit_truck = data.data[0].exit_truck;

                   // console.log($scope.entry_truck)
                   // console.log($scope.exit_truck)

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
        $scope.CountTrucksEntryExitFunc();






        $scope.searchManifestOrTruck = function(searchKey, searchField) {
            $scope.table = false;
            $scope.total_gweight_wbridge=0;
            $scope.total_tr_weight=0;
            $scope.total_tweight_wbridge=0;


            var data = {
                searchKey : searchKey,
                searchField : searchField
            }
            console.log(data);
            $http.post("/weighbridge/api/search-manifest-or-truck-data",data)
                .then(function (data) {
                    console.log(data);
                    $scope.CountTrucksEntryExitFunc();
                    if(searchKey=='manifestNo') {
                        if(data.data.length > 0) {
                        $scope.serachBytruck = false;
                        $scope.manifest = data.data[0].manifest;
                       // console.log($scope.manifest);
                        $scope.table = true;
                        $scope.allTrucksData = data.data;

                            //calculate total
                            angular.forEach($scope.allTrucksData,function (v,k) {
                                if (v.gweight_wbridge){
                                    $scope.total_gweight_wbridge+=parseFloat(v.gweight_wbridge);
                                    $scope.total_tr_weight+=parseFloat(v.tr_weight);
                                    $scope.total_tweight_wbridge+=parseFloat(v.tweight_wbridge);
                                }

                            });

                        $scope.Error = false;
                        } else {
                            $scope.Error = 'This Manifest number is not assigned as weightbridge.';
                            $('#Error').show().delay(5000).slideUp(1000);
                            $scope.table = false;
                            //console.log($scope.ManifestError);
                        }
                        $scope.showDiv = true;
                    } else {
                        if(data.data.truckData.length > 0) {
                            console.log(data.data.truckData);
                            $scope.serachBytruck = true;
                            $scope.show = false;
                            $scope.savingSuccess = false;
                            $scope.savingError = false;
                            $scope.manifest = data.data.truckData[0].manifest;
                            console.log($scope.manifest);
                            $scope.truck_no = data.data.truckData[0].truck_no;
                            $scope.truck_type = data.data.truckData[0].truck_type;
                            $scope.id = data.data.truckData[0].id;
                            $scope.goods = data.data.truckData[0].goods;
                            if(data.data.truckData[0].gweight_wbridge != null && data.data.truckData[0].wbrdge_time1 != null) {
                                $scope.gweight_wbridge = parseFloat(data.data.truckData[0].gweight_wbridge);
                                $scope.gweight_wbridge_view = parseFloat(data.data.truckData[0].gweight_wbridge);
                                var wbrdge_time1 = data.data.truckData[0].wbrdge_time1.split(" ");
                                $scope.wbrdge_time1 = wbrdge_time1[0];
                                $scope.wbrdge_time1_notChange = wbrdge_time1[1];
                            } else {
                                $scope.gweight_wbridge = null;
                                var today = new Date();
                                var Y = today.getFullYear();
                                var M = today.getMonth()+1;
                                var D = today.getDate();

                                if(today.getMonth()+1 < 10)
                                    M = "0"+M;
                                if(today.getDate() < 10)
                                    D = "0"+D;
                                $scope.wbrdge_time1 = Y+"-"+M+"-"+D; 
                            }

                            // if(data.data.truckData[0].wbrdge_time2 != null) {
                            //     var wbrdge_time2 = data.data.truckData[0].wbrdge_time2.split(" ");
                            //     $scope.wbrdge_time2 = wbrdge_time2[0];
                            //     $scope.wbrdge_time2_notChange = wbrdge_time2[1];
                            // } else {
                            //     $scope.wbrdge_time2 = null;
                            //     $scope.wbrdge_time2_notChange = null;
                            // }

                            if(data.data.trWeight.length > 0 && data.data.truckData[0].tr_weight == null) {
                                $scope.tr_weight_from_Entry = parseFloat(data.data.trWeight[0].tr_weight);
                                $scope.whenTrWeightFound = true;
                            } else {
                                $scope.tr_weight_from_Entry = null;
                                $scope.whenTrWeightFound = false;
                            }

                            if(data.data.truckData[0].tr_weight != null && data.data.truckData[0].tweight_wbridge != null) {
                                $scope.tr_weight_from_Entry = parseFloat(data.data.truckData[0].tr_weight);
                                $scope.tweight_wbridge = parseFloat(data.data.truckData[0].tweight_wbridge);
                                $scope.whenTrWeightFound = true; 
                            } else {
                                $scope.tr_weight_from_Entry = null;
                                $scope.tweight_wbridge = null;
                                $scope.whenTrWeightFound = false;
                            }
                            if(data.data.truckData[0].tr_weight != null) {
                                $scope.tr_weight = parseFloat(data.data.truckData[0].tr_weight);
                                $scope.tweight_wbridge = parseFloat(data.data.truckData[0].tweight_wbridge);
                                if(data.data.truckData[0].wbrdge_time2 != null) {
                                    var wbrdge_time2 = data.data.truckData[0].wbrdge_time2.split(" ");
                                    $scope.wbrdge_time2 = wbrdge_time2[0];
                                } else {
                                    var today = new Date();
                                    var Y = today.getFullYear();
                                    var M = today.getMonth()+1;
                                    var D = today.getDate();
                                    if(today.getMonth()+1 < 10)
                                        M = "0"+M;
                                    if(today.getDate() < 10)
                                        D = "0"+D;
                                    $scope.wbrdge_time2 = Y+"-"+M+"-"+D;   
                                }
                            } else {
                                $scope.tr_weight = null;
                                $scope.tweight_wbridge = null;
                                var today = new Date();
                                var Y = today.getFullYear();
                                var M = today.getMonth()+1;
                                var D = today.getDate();
                                if(today.getMonth()+1 < 10)
                                    M = "0"+M;
                                if(today.getDate() < 10)
                                    D = "0"+D;
                                $scope.wbrdge_time2 = Y+"-"+M+"-"+D; 
                            }
                        } else {
                            $scope.manifest = null;
                            $scope.Error = 'This Truck is not assigned as weightbridge.';
                            $('#Error').show().delay(5000).slideUp(1000);
                        }
                    }
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                        $scope.Error = 'Something Went Wrong!';
                        $('#Error').show().delay(5000).slideUp(1000);

                }).finally(function () {
                        $scope.savingData = false;
            })
        }


        enterKeyService.enterKey('#WeightBridgeIN input ,#WeightBridgeIN button');
        enterKeyService.enterKey('#WeightBridgeExit input ,#WeightBridgeExit button');


        //After Clicking Weightbridge Entry Button
        $scope.update = function(truck) {
            $scope.savingSuccess = false;
            $scope.savingError = false;
            $scope.savingSuccessEntry = false;
            $scope.savingError = false;
            $scope.show = false;
            //$scope.label = true;
            $scope.truck_no = truck.truck_no;
            $scope.truck_type = truck.truck_type;
            $scope.goods_id = truck.goods_id;
            $scope.id = truck.id;
            $scope.selectedStyle = truck.id;

            if(truck.gweight_wbridge != null && truck.wbrdge_time1 != null) {
                $scope.gweight_wbridge = parseFloat(truck.gweight_wbridge);
                $scope.gweight_wbridge_view = parseFloat(truck.gweight_wbridge);
                var wbrdge_time1 = truck.wbrdge_time1.split(" ");
                $scope.wbrdge_time1 = wbrdge_time1[0];
                // $scope.wbrdge_time1_notChange = wbrdge_time1[1];
            } else {
                $scope.gweight_wbridge = null;
                var today = new Date();
                var Y = today.getFullYear();
                var M = today.getMonth()+1;
                var D = today.getDate();

                if(today.getMonth()+1 < 10)
                    M = "0"+M;
                if(today.getDate() < 10)
                    D = "0"+D;
                $scope.wbrdge_time1 = Y+"-"+M+"-"+D; 
            }
            if(truck.tr_weight != null && truck.tweight_wbridge != null) {
                $scope.tweight_wbridge = parseFloat(truck.tweight_wbridge);
            } else {
                $scope.tweight_wbridge = null;
            }

            //weightbridge Exit
            if(truck.tr_weight != null) {
                $scope.tr_weight = parseFloat(truck.tr_weight);
                $scope.tweight_wbridge = parseFloat(truck.tweight_wbridge);
                if(truck.wbrdge_time2 != null) {
                    var wbrdge_time2 = truck.wbrdge_time2.split(" ");
                    $scope.wbrdge_time2 = wbrdge_time2[0];
                } else {
                    var today = new Date();
                    var Y = today.getFullYear();
                    var M = today.getMonth()+1;
                    var D = today.getDate();
                    if(today.getMonth()+1 < 10)
                        M = "0"+M;
                    if(today.getDate() < 10)
                        D = "0"+D;
                    $scope.wbrdge_time2 = Y+"-"+M+"-"+D;   
                }
            } else {
                $scope.tr_weight = null;
                $scope.tweight_wbridge = null;
                var today = new Date();
                var Y = today.getFullYear();
                var M = today.getMonth()+1;
                var D = today.getDate();
                if(today.getMonth()+1 < 10)
                    M = "0"+M;
                if(today.getDate() < 10)
                    D = "0"+D;
                $scope.wbrdge_time2 = Y+"-"+M+"-"+D; 
            }

            // if(truck.wbrdge_time2 != null) {
            //     var wbrdge_time2 = truck.wbrdge_time2.split(" ");
            //     $scope.wbrdge_time2 = wbrdge_time2[0];
            //     $scope.wbrdge_time2_notChange = wbrdge_time2[1];
            // } else {
            //     $scope.wbrdge_time2 = null;
            //     $scope.wbrdge_time2_notChange = null;
            // }

            var dataTruck = {
                truck_no : $scope.truck_no,
                truck_type : $scope.truck_type
            }
            $http.post("/weighbridge/api/get-tear-weight-data",dataTruck)       //Get previous truck weight
                                                //and weightbridge gross weight was posted or not for id
                .then(function (data) {
                    $scope.CountTrucksEntryExitFunc();
                   //console.log(data.data);
                    if(data.data.length>0) {
                        $scope.tr_weight_from_Entry = parseFloat(data.data[0].tr_weight);
                        $scope.whenTrWeightFound = true;
                    } else {
                        $scope.tr_weight_from_Entry = null;
                        $scope.whenTrWeightFound = false;
                    }
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            }).finally(function () {

            })

            var data = {
                goods_id : $scope.goods_id
            }
            $http.post("/weighbridge/api/get-goods-name-data",data)
                .then(function (data) {
                    $scope.goods = data.data[0].cargo_name;
                    //console.log($scope.goodsData[0].cargo_description);
                }).catch(function (r) {

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {


            });
            $scope.wbridg_created_at1 = truck.wbridg_created_at1;
            $scope.wbridg_created_at2 = truck.wbridg_created_at2;
        }




        //After Clicking Save Button
        $scope.saveEntry = function() {
            if($scope.gweight_wbridge == null || $scope.wbrdge_time1 == null) {
                if($scope.gweight_wbridge == null) {
                    $scope.gweight_wbridge_required = true;
                } else {
                    $scope.gweight_wbridge_required = false;  
                }
                if ($scope.wbrdge_time1 == null) {
                    $scope.wbrdge_time1_required = true;
                } else {
                    $scope.wbrdge_time1_required =false;
                }
                return 0;
            } else {
                $scope.gweight_wbridge_required = false;
                $scope.wbrdge_time1_required =false;
            }
        $scope.serachBytruck = false;
        var today = new Date();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
            if($scope.tr_weight_from_Entry != null) {
                var data = {
                        manifest : $scope.manifest,
                        id : $scope.id,
                        gweight_wbridge : $scope.gweight_wbridge,
                        wbrdge_time1 : $scope.wbrdge_time1 +" "+ h+":"+m+":"+s,
                        tr_weight : $scope.tr_weight_from_Entry,
                        tweight_wbridge : $scope.tweight_wbridge,
						wbrdge_time2 : $scope.wbrdge_time1 +" "+ h+":"+m+":"+s,
                        wbridg_created_at1 : $scope.wbridg_created_at1
                    }
                 console.log(data);
                // return;
                $http.post("/weighbridge/api/save-entry-data-with-tear-weight-net-weight", data)
                    .then(function (data) {
                        $scope.CountTrucksEntryExitFunc();
                        //console.log(data.data);
                        $scope.savingSuccessEntry='Weightbridge Entry Saved Successfully!';
                        $('#savingSuccessEntry').show().delay(5000).slideUp(1000);
                        $scope.gweight_wbridge = null;
                        $scope.wbrdge_time1 = null;
                        $scope.tr_weight = null;
                        $scope.tr_weight_from_Entry = null;
                        $scope.tweight_wbridge = null;
                        $scope.wbrdge_time2 = null;
                        $scope.whenTrWeightFound = false; //tr and net weight not show
                        $scope.wbridg_created_at1 = null;
                        $scope.show = true;
                        $scope.searchManifestOrTruck($scope.searchKeyHolder,$scope.manifest);
                    }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }

                    $scope.savingErrorEntry = 'Something Went Wrong!';
                        $('#savingErrorEntry').show().delay(5000).slideUp(1000);
                    }).finally(function () {
                        $scope.savingData = false;
                    })
            } else {
                var data = {
                        manifest : $scope.manifest,
                        id : $scope.id,
                        gweight_wbridge : $scope.gweight_wbridge,
                        wbrdge_time1 : $scope.wbrdge_time1 +" "+h+":"+m+":"+s,
                        wbridg_created_at1 : $scope.wbridg_created_at1
                    }
                console.log(data);
               // return;
            $http.post("/weighbridge/api/save-entry-data-with-gross-weight", data)
                .then(function (data) {
                    //console.log(data.data);
                    $scope.savingSuccessEntry='Weightbridge Entry Saved Successfully!';
                    $('#savingSuccessEntry').show().delay(5000).slideUp(1000);
                    $scope.gweight_wbridge = null;
                    $scope.wbrdge_time1 = null;
                    $scope.tr_weight = null;
                    $scope.tr_weight_from_Entry = null;
                    $scope.wbrdge_time2 = null;
                    $scope.tweight_wbridge = null;
                    $scope.wbridg_created_at1 = null;
                    $scope.show = true;
                    $scope.searchManifestOrTruck($scope.searchKeyHolder,$scope.manifest);
                }).catch(function (r) {
                console.log(r)
                    $scope.savingErrorEntry = 'Something Went Wrong!';
                    $('#savingErrorEntry').show().delay(5000).slideUp(1000);
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                }).finally(function () {
                    $scope.savingData = false;
                })
            }

            $scope.selectedStyle = $scope.id;
        }
        //Get Net Weight
        $scope.getNetweightEntry = function() {
            if($scope.gweight_wbridge != null && $scope.tr_weight_from_Entry !=null)
                $scope.tweight_wbridge = $scope.gweight_wbridge - $scope.tr_weight_from_Entry;
        }

        //Weightbridge Exit
        $scope.show_tweight_wbridge = true;
        $scope.ButtonExit = true;
        $scope.saveExit = function() {
            if($scope.gweight_wbridge==null) {
                bootbox.dialog({
                    message : "Weightbridge Entry is not completed."
                }).css({
                    'text-align':'center',
                    'top':'0',
                    'bottom': '0',
                    'left': '0',
                    'right': '0',
                    'margin': 'auto'
                });
                return false;
            }
            if($scope.tr_weight == null || $scope.wbrdge_time2 == null) {
                if($scope.tr_weight == null) {
                    $scope.tr_weight_required = true;
                } else {
                    $scope.tr_weight_required = false;  
                }
                if ($scope.wbrdge_time2 == null) {
                    $scope.wbrdge_time2_required = true;
                } else {
                    $scope.wbrdge_time2_required =false;
                }
                return 0;
            } else {
                $scope.tr_weight_required = false;
                $scope.wbrdge_time2_required =false;
            }
            $scope.serachBytruck = false;

            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            var data = {
                    manifest : $scope.manifest,
                    id : $scope.id,
                    tr_weight : $scope.tr_weight,
                    tweight_wbridge : $scope.tweight_wbridge,
                    wbrdge_time2 : $scope.wbrdge_time2 +" "+h+":"+m+":"+s,
                    wbridg_created_at2 : $scope.wbridg_created_at2
                }
            console.log(data);
            // return;
            $http.post("/weighbridge/api/save-exit-data", data)
                .then(function (data) {
                    //console.log(data.data);
                    $scope.savingSuccess='Weightbridge Exit Saved Successfully!';
                    $('#savingSuccess').show().delay(5000).slideUp(1000);
                    $scope.gweight_wbridge = null;
                    $scope.wbrdge_time1 = null;
                    $scope.tr_weight = null;
                    $scope.tr_weight_from_Entry = null;
                    $scope.wbrdge_time2 = null;
                    $scope.tweight_wbridge = null;
                    $scope.wbridg_created_at2 = null;
                    $scope.show = true;
                    $scope.searchManifestOrTruck($scope.searchKeyHolder,$scope.manifest);
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                    $scope.savingError = 'Something Went Wrong!';
                    $('#savingError').show().delay(5000).slideUp(1000);
                }).finally(function () {
                    $scope.savingData = false;
                })
                $scope.selectedStyle = $scope.id;
        }

        $scope.getNetweight = function() {
            if($scope.gweight_wbridge != null && $scope.tr_weight !=null)
                $scope.tweight_wbridge = $scope.gweight_wbridge - $scope.tr_weight;
        }

        //28-5-17   ======== For Manifest Input
        //service added 7-6-2017

        $scope.keyBoard = function(event) {
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
        }

        $scope.$watch('searchField',function() {
            $scope.searchField = manifestService.addYearWithManifest($scope.searchField, $scope.keyboardFlag,$scope.searchKey);
        });

        //Capitalize
        $scope.$watch('searchField', function (val) {
            $scope.searchField = $filter('uppercase')(val);
        }, true);
        
    });