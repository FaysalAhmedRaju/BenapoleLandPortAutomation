angular.module('weightBridgeEntryApp',['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('weightBridgeEntryController', function($scope, $http, $filter, manifestService,enterKeyService) {

        $scope.show = true;
        $scope.show_tweight_wbridge = true;
        $scope.ButtonExit = true;
        $scope.serachBytruck = false;

        $scope.searchKey = 'manifestNo';
        $scope.searchKeyHolder = $scope.searchKey;
        $scope.placeHolder = 'Enter Manifest No';
        $scope.select = function() {
            if($scope.searchKey=='manifestNo'){
                $scope.placeHolder = 'Enter Manifest No';
            } else if($scope.searchKey=='truckNo'){
                $scope.placeHolder = 'Enter Truck No';
            } else {
                $scope.placeHolder = null;
            }
        }

        $scope.searchManifestOrTruck = function(searchKey, searchField) {
            $scope.table = false;
            var data = {
                searchKey : searchKey,
                searchField : searchField
            }
            $http.post("/api/searchManifestOrTruck",data)
                .then(function (data) {
                    console.log(data);
                    if(searchKey=='manifestNo') {
                        if(data.data.length > 0) {
                        $scope.serachBytruck = false;
                        $scope.manifest = data.data[0].manifest;
                        $scope.table = true;
                        $scope.allTrucksData = data;
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
                            $scope.truck_no = data.data.truckData[0].truck_no;
                            $scope.truck_type = data.data.truckData[0].truck_type;
                            $scope.id = data.data.truckData[0].id;
                            $scope.goods = data.data.truckData[0].goods;
                            $scope.gweight_wbridge = data.data.truckData[0].gweight_wbridge;
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
            });
        }


        enterKeyService.enterKey('#WeightBridgeExit input ,#WeightBridgeExit button')


        //After Clicking Weightbridge Entry Button
        $scope.update = function(truck) {
            $scope.savingSuccess = false;
            $scope.savingError = false;
            $scope.gweight_wbridge = truck.gweight_wbridge;
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
            $scope.show = false;
            //$scope.label = true;
            $scope.truck_no = truck.truck_no;
            $scope.truck_type = truck.truck_type;
            $scope.goods_id = truck.goods_id;
            $scope.id = truck.id;
            $scope.selectedStyle = truck.id;

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
            // var dataTruck = {
            //     truck_no : $scope.truck_no
            // }
            // $http.post("/api/getTrWeight",dataTruck)       //Get previous truck weight
            //                                     //and weightbridge gross weight was posted or not for id
            //     .then(function (data) {
            //        //console.log(data.data);
            //         if(data.data.length>0) {
            //             $scope.tr_weight = parseFloat(data.data[0].tr_weight);
            //             $scope.whenTrWeightFound = true;
            //         } else {
            //             $scope.tr_weight = null;
            //             $scope.whenTrWeightFound = false;
            //         }
            //     })

            var data = {
                goods_id : $scope.goods_id
            }
            $http.post("/api/getGoodsNameJson",data)
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
        }
        //After Clicking Save Button
        $scope.save = function() {
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
                    id : $scope.id,
                    tr_weight : $scope.tr_weight,
                    tweight_wbridge : $scope.tweight_wbridge,
                    wbrdge_time2 : $scope.wbrdge_time2 +" "+h+":"+m+":"+s
                }
            //console.log(data);
            $http.post("/api/postWeightBridgeExitJson", data)
                .then(function (data) {
                    //console.log(data.data);
                    $scope.savingSuccess='Weightbridge Exit Saved Successfully!';
                    $('#savingSuccess').show().delay(5000).slideUp(1000);
                    $scope.tr_weight = null;
                    $scope.wbrdge_time2 = null;
                    $scope.tweight_wbridge = null;
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
        
        //29-5-17   ======== For Manifest Input
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