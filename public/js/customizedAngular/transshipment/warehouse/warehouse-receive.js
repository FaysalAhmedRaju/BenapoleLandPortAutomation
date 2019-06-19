angular.module('WareHouseEntryApp', ['angularUtils.directives.dirPagination', 'customServiceModule'])
    .controller('WareHouseEntryController', function ($scope, $http, $filter, manifestService, enterKeyService) {
        //Role Wise Work

        $scope.role_name = role_name;
        $scope.disableWhenTransshipment = false;
        if ($scope.role_name == 'TransShipment') {
            $scope.disableWhenTransshipment = true;
        }
        //capitalize the WereHouse Receiving
        $scope.receiveWeightFlagWhenNoWeightBridge = false;
        $scope.$watch('searchKey', function (val) {

            $scope.searchKey = $filter('uppercase')(val);

        }, true);
        $scope.$watch('receive_weight', function (val) {

            $scope.receive_weight = $filter('uppercase')(val);

        }, true);

        $scope.$watch('receive_package', function (val) {

            $scope.receive_package = $filter('uppercase')(val);

        }, true);
        $scope.$watch('recive_comment', function (val) {

            $scope.recive_comment = $filter('uppercase')(val);

        }, true);
        $scope.$watch('receive_datetime', function (val) {

            $scope.receive_datetime = $filter('uppercase')(val);

        }, true);
        $scope.$watch('labor_unload', function (val) {

            $scope.labor_unload = $filter('uppercase')(val);

        }, true);
        $scope.$watch('labor_package', function (val) {

            $scope.labor_package = $filter('uppercase')(val);

        }, true);

        $scope.$watch('equip_unload', function (val) {

            $scope.equip_unload = $filter('uppercase')(val);

        }, true);

        $scope.$watch('equip_name', function (val) {

            $scope.equip_name = $filter('uppercase')(val);

        }, true);

        $scope.$watch('equipment_package', function (val) {

            $scope.equipment_package = $filter('uppercase')(val);

        }, true);
        $scope.$watch('equipment_package', function (val) {

            $scope.equipment_package = $filter('uppercase')(val);

        }, true);


        $scope.show = true;
        $scope.ReceiveDatetimeDisable = true;
        $scope.truckInfo = true;
        $scope.serachField = true;
        $scope.reportByManifestBtn = true;
        $scope.showonly = true;
        $scope.transshipment = null;
        $scope.button = true;

        //graphical view global variable
        $scope.truck_id = null;
        $scope.posted_yard_shed = null;
        $scope.graph_data = null;
        $scope.row = null;
        $scope.column = null;


        //sreachby selection
        $scope.selection = {
            singleSelect: null
        };
        $scope.placeHolder = 'Enter Manifest No';
        //console.log($scope.selection.singleSelect);
        $scope.select = function () {
            if ($scope.selection.singleSelect == 'truckNo') {
                $scope.placeHolder = 'Enter Truck No';
                $scope.serachField = false;
                $scope.reportByManifestBtn = true;
            } else if ($scope.selection.singleSelect == 'manifestNo') {
                $scope.placeHolder = 'Enter Manifest No';
                $scope.serachField = false;
                $scope.reportByManifestBtn = false;
            } else if ($scope.selection.singleSelect == 'yardNo') {
                $scope.placeHolder = 'Enter Yard No';
                $scope.serachField = false;
                $scope.reportByManifestBtn = true;
            } else {
                $scope.placeHolder = null;
                $scope.serachField = true;
                $scope.reportByManifestBtn = true;
            }
        }

        enterKeyService.enterKey('#wareHouseform input ,#wareHouseform button');




         var eqip_name=[
         'Fork Lift',
         'Crane'
         ];

        $("#equip_name").autocomplete({
            source: function (request, response) {
                console.log(eqip_name);
                var result = $.ui.autocomplete.filter(eqip_name, request.term);
                //$("#add").toggle($.inArray(request.term, result) < 0);
                response(result);
            }
        });

        //pagination Every Page serial number......
        $scope.serial = 1;
        $scope.itemPerpage = 5;
        $scope.getPageCount = function(n){
            $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        }
        //pagination serial number   End ......
        $scope.Refresh = function() {
            $scope.truck_id = null;
            $scope.receive_weight = null;
            $scope.receive_package = null;
            $scope.recive_comment = null;
            $scope.receive_datetime = null;
            //$scope.receive_by = truck.receive_by;
            $scope.labor_unload = null;
            $scope.labor_package = null;
            $scope.equip_unload = null;
            $scope.equip_name = null;
            $scope.equipment_package = null;
            $scope.shifting_flag = '0';
            $scope.gweight_wbridge = null;
            //graph
            $scope.selectedShed = null;
            //ranshipment
            $scope.holtage_charge_flag = '1';
            $scope.receive_created_at = null;
            $scope.message_1 = null;
            $scope.message_2 = null;
            $scope.yard_count_no = null;

            $scope.show = true;
            $scope.ReceiveDatetimeDisable = true;
            $scope.showonly = true;
            $scope.receiveWeightFlagWhenNoWeightBridge = false;
            $('#saveBtn').html('Save');
            $scope.selectedStyle = 0;

                    
        }

        //After Pressing Enter and found by searchKey
        $scope.truckNoOrManifestOrYardSearch = function () {

//to make blanck for graphicaal view
            $scope.posted_yard_shed = null;
            $scope.graph_data = null;
            $scope.receive_weight = null;
            $scope.Refresh();


            //console.log($scope.selection.singleSelect);
            var letters = /^[A-Za-z]+$/;
            if ($scope.searchKey.match(letters)) {
                if ($scope.selection.singleSelect == 'truckNo') {
                    $scope.errorType = 'The Truck Number is Invalid.';
                } else if ($scope.selection.singleSelect == 'manifestNo') {
                    $scope.errorType = 'The Manifest Number is Invalid.';
                } else if ($scope.selection.singleSelect == 'yardNo') {
                    $scope.errorType = 'The Yard No is Invalid.';
                } else {
                    $scope.errorType = 'Invalid Input. Please Select Search By Options.';
                    $('#errorType').show().delay(5000).slideUp(1000);
                }
                $scope.table = false;
            } else {
                //console.log($scope.truck_no);
                if ($scope.selection.singleSelect == 'truckNo') {
                    var data = {
                        truck_no: $scope.searchKey,
                        search_by: 'truckNo'
                    }
                } else if ($scope.selection.singleSelect == 'manifestNo') {
                    var data = {
                        manf_id: $scope.searchKey,
                        search_by: 'manifestNo'
                    }
                } else if ($scope.selection.singleSelect == 'yardNo') {
                    var data = {
                        posted_yard_shed: $scope.searchKey,
                        search_by: 'yardNo'
                    }
                }
                console.log(data);
                //var ManifestNo = $scope.ManifestNo;
                $scope.totalReceiveWeight = 0;
                $scope.totalReceivePkg = 0;
                $scope.totalUnloadLaborWeight = 0;
                $scope.totalUnloadLaborPkg = 0;
                $scope.totalUnloadEquipmetWeight = 0;
                $scope.totalUnloadEqupmentPkg = 0;
                $http.post("/transshipment/api/warehouse/receive/get-all-trucks-for-receive", data)
                    .then(function (data) {
                        console.log(data.data);
                        if (data.status == 203){
                            $scope.errorType = data.data.noPermission;
                            $('#errorType').show().delay(5000).slideUp(1000);
                            return;
                        }

                        if (data.data.length > 0) {
                            $scope.transshipment = data.data[0].transshipment_flag;

                            $scope.table = true;
                            $scope.allTrucksData = data.data;

                            angular.forEach($scope.allTrucksData, function(v,k) {
                                if(v.receive_weight == null) {
                                    $scope.totalReceiveWeight += parseFloat(v.tweight_wbridge);
                                } else {
                                    $scope.totalReceiveWeight += parseFloat(v.receive_weight);
                                }
                                $scope.totalReceivePkg += parseFloat(v.receive_package);
                                $scope.totalUnloadLaborWeight += parseFloat(v.unload_labor_weight);
                                $scope.totalUnloadLaborPkg += parseFloat(v.unload_labor_package);
                                $scope.totalUnloadEquipmetWeight += parseFloat(v.unload_equip_weight);
                                $scope.totalUnloadEqupmentPkg += parseFloat(v.unload_equipment_package);
                            });
                            
                            $scope.errorType = false;
                            if ($scope.selection.singleSelect == 'manifestNo') { //When Search On Manifest show details
                                $scope.manifestInfo = true;
                                $scope.truckInfo = false;
                                $scope.dataLength = data.data.length;
                            } else {
                                $scope.manifestInfo = false;
                                $scope.truckInfo = true;
                                $scope.dataLength = 0;
                            }
                        } else {
                            if ($scope.selection.singleSelect == 'truckNo') {
                                $scope.errorType = 'The Truck Number is not registered.';
                                $('#errorType').show().delay(5000).slideUp(1000);
                            } else if ($scope.selection.singleSelect == 'manifestNo') {
                                $scope.errorType = 'The Manifest Number is not registered.';
                                $('#errorType').show().delay(5000).slideUp(1000);
                            } else if ($scope.selection.singleSelect == 'yardNo') {
                                $scope.errorType = 'The Yard is already full.';
                                $('#errorType').show().delay(5000).slideUp(1000);
                            } else {
                                $scope.errorType = 'Something Went Wrong!';
                                $('#errorType').show().delay(5000).slideUp(1000);
                            }
                            $scope.table = false;
                            //console.log($scope.ManifestError);
                        }
                        //console.log(data);
                        //$scope.showDiv = true;
                    }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                    $scope.errorType = 'Something Went Wrong!';
                }).finally(function () {
                    $scope.savingData = false;
                })
            }
        };




        //After Clicking Posting Yard Entry Button
        $scope.update = function (truck) {

            if (truck.vehicle_type_flag == 11) {
                $scope.savingError = "Chassis(Self) Can Not Receive!";
                $('#savingError').show().delay(5000).slideUp(1000);
                return;
            }
            console.log(truck);
            $scope.truck_id = truck.id;     //to use in graphicla view
            $scope.manifest = truck.manifest;
            $scope.manifest_id = truck.manf_id;
            $scope.shed_yard_weight_id = truck.shed_yard_weight_id;

            $scope.posted_yard_shed = truck.posted_yard_shed;     // for Graphical view  // very important line Plz check this line :))))

            
            console.log($scope.posted_yard_shed);

            $scope.selectedShed = truck.yard_id + truck.row + truck.column;         // very important line plz check this line
            $scope.row = truck.row;
            $scope.column = truck.column;


            $scope.savingSuccess = false;
            $scope.savingError = false;
            $scope.manifestInfo = false;
            $scope.truckInfo = true;

            $scope.show = false;
            $scope.ReceiveDatetimeDisable = false;
            $scope.gatepassField = true;
            $scope.showonly = true;
            $scope.whenLaborUnloadTyping = false;
            //$scope.label = true;
            $scope.truck_no = truck.truck_no;


            $scope.truck_type = truck.truck_type;
            $scope.goods_id = truck.goods_id;
            $scope.selectedStyle = truck.id;
            $scope.id = truck.id;
            $scope.shifting_flag = truck.unload_shifting_flag ? truck.unload_shifting_flag.toString() : '0';

            if(truck.holtage_charge_flag !=null){
                $scope.holtage_charge_flag = truck.holtage_charge_flag.toString();
            }
            //$scope.manf_id = truck.manf_id;
            var data = {
                goods_id: $scope.goods_id
            }
            $http.post("/warehouse/api/receive/get-goods-details-data", data)
                .then(function (data) {
                    $scope.goodsData = data.data;
                    //console.log($scope.goodsData);
                }).catch(function (r) {

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {


            })
            if (truck.receive_package != null && truck.unload_receive_datetime != null) {

                $scope.receive_package = truck.receive_package;
                $scope.recive_comment = truck.unload_comment;
                $scope.receive_datetime = truck.unload_receive_datetime;
                $scope.labor_unload = parseFloat(truck.unload_labor_weight);
                $scope.labor_package = truck.unload_labor_package;
                $scope.equip_unload = parseFloat(truck.unload_equip_weight);
                $scope.equip_name = truck.unload_equip_name;
                $scope.equipment_package = truck.unload_equipment_package;
            } else {
                //$scope.posted_yard_shed = null;
                $scope.receive_weight = null;
                $scope.receive_package = null;
                $scope.recive_comment = null;
                $scope.receive_datetime = null;
                //$scope.receive_by = truck.receive_by;
                $scope.labor_unload = null;
                $scope.labor_package = null;
                $scope.equip_unload = null;
                $scope.equip_name = null;
                $scope.equipment_package = null;

            }
            console.log($scope.transshipment);
            console.log(truck.gweight_wbridge);


            $scope.gweight_wbridge = truck.gweight_wbridge;

            var manifest_middle_string=truck.manifest.split('/');

            console.log(isNaN(manifest_middle_string[1]));

            if ((truck.gweight_wbridge == null && truck.receive_weight == null) || isNaN(manifest_middle_string[1])){
                $scope.receiveWeightFlagWhenNoWeightBridge = true;
                $scope.showonly = false;
                $http.get('/transshipment/api/warehouse/receive/get-manifest-gross-weight-for-receive/' + truck.manf_id)
                    .then(function (data) {
                        console.log(data.data[0]);
                        if (data.data.length == 1) {
                            $scope.gweight =  data.data[0].gweight;
                            $scope.receive_weight = $scope.gweight / $scope.dataLength;
                        } else {
                            $scope.receive_weight = 0;
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
            } else {
                console.log('else');
                $scope.showonly = true;
                if (truck.receive_weight == null) {
                    $scope.receive_weight = truck.tweight_wbridge != null ? parseFloat(truck.tweight_wbridge) : null;
                } else {
                    $scope.receive_weight = truck.receive_weight != null ? parseFloat(truck.receive_weight) : null;
                }

            }

            $scope.showonly = truck.gweight_wbridge == null ? false : true;
            $scope.receiveWeightFlagWhenNoWeightBridge = truck.gweight_wbridge == null ? true : false;
            if(!$scope.transshipment){
                $scope.showonly=false;
            }
            $scope.receive_created_at = truck.receive_created_at;
            $scope.chassis_on_truck_flag = truck.chassis_on_truck_flag;
            if($scope.chassis_on_truck_flag == 1) {
                $scope.showChasismodal = true;
            } else {
                $scope.showChasismodal = false;
            }
            if($scope.unload_receive_datetime != null) {
                $('#saveBtn').html('Update');
            } else {
                $('#saveBtn').html('Save');
            }
        };



        //After Clicking Save Button
        $scope.save = function () {
            if ($scope.receive_package == null ){
                if ($scope.receive_package == null) {
                    $scope.receive_package_required = true;
                } else {
                    $scope.receive_package_required = false;
                }
                return false;
            } else {
                $scope.receive_package_required = false;
            }
            var data = {
                id: $scope.id,
                receive_weight: $scope.receive_weight,
                receive_package: $scope.receive_package,
                recive_comment: $scope.recive_comment,
                receive_datetime: $scope.receive_datetime,
                labor_unload: $scope.labor_unload,
                labor_package: $scope.labor_package,
                equip_unload: $scope.equip_unload,
                equip_name: $scope.equip_name,
                equipment_package: $scope.equipment_package,
                shifting_flag: $scope.shifting_flag,
                shed_yard_weight_id : $scope.shed_yard_weight_id,

                //graph details
                truck_id: $scope.truck_id,
                posted_yard_shed: $scope.posted_yard_shed,
                row: $scope.row,
                column: $scope.column,
                //for transhipmet
                holtage_charge_flag: $scope.holtage_charge_flag,
                gweight_wbridge: $scope.gweight_wbridge,
                receive_created_at: $scope.receive_created_at
            }

            console.log(data);
            $http.post("/transshipment/api/warehouse/receive/save-truck-receive-data", data)
                .then(function (data) {
                    console.log(data);
                    if(data.status == 203) {
                       $scope.savingError = data.data.posting_error;
                        $('#savingError').show().delay(5000).slideUp(1000);
                        return;
                    }
                    $scope.savingSuccess = 'WareHouse Entry Saved Successfully!';
                    $('#savingSuccess').show().delay(5000).slideUp(1000, function () {
                        $scope.selectedStyle = 0;
                    });
                    //$scope.posted_yard_shed = null;
                    $scope.receive_weight = null;
                    $scope.receive_package = null;
                    $scope.recive_comment = null;
                    $scope.receive_datetime = null;
                    //$scope.receive_by = truck.receive_by;
                    $scope.labor_unload = null;
                    $scope.labor_package = null;
                    $scope.equip_unload = null;
                    $scope.equip_name = null;
                    $scope.equipment_package = null;
                    $scope.shifting_flag = '0';
                    $scope.gweight_wbridge = null;
                    //graph
                    $scope.selectedShed = null;
                    //ranshipment
                    $scope.holtage_charge_flag = '1';
                    $scope.receive_created_at = null;
                    //$scope.t_posted_yard_shed = null;
                    $scope.message_1 = null;
                    $scope.message_2 = null;
                    $scope.yard_count_no = null;

                    $scope.show = true;
                    $scope.ReceiveDatetimeDisable = true;
                    $scope.showonly = true;
                    $scope.receiveWeightFlagWhenNoWeightBridge = false;
                    //$scope.label = false;
                    $scope.truckNoOrManifestOrYardSearch();
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

                $scope.savingError = 'Something Went Wrong!';
                $('#savingError').show().delay(5000).slideUp(1000, function () {
                    $scope.selectedStyle = 0;
                });
            }).finally(function () {
                $scope.savingData = false;
            })
        }

        $scope.getEquipmentUnload = function () {
                $scope.diff = $scope.receive_weight - $scope.labor_unload;
                $scope.equip_unload = $scope.diff != 0 ? $scope.diff : null;
                $scope.whenLaborUnloadTyping = true;

        }

        //New Manifest Work [START]
        //service added 7-6-2017

        $scope.keyBoard = function (event) {
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
        }

        $scope.$watch('searchKey', function () {
            $scope.searchKey = manifestService.addYearWithManifest($scope.searchKey, $scope.keyboardFlag, $scope.selection.singleSelect);
        });

        //New Manifest Work [END]


        $scope.YardNOForLevelNO = function () {
            var data = {
                yard_no: $scope.t_posted_yard_shed
            }
            console.log($scope.t_posted_yard_shed);
            $http.post("/warehouse/api/receive/count-current-date-wise-shed-yard-no", data)
                .then(function (data) {
                    console.log(data.data);
                    $scope.message_1 = "This is";
                    $scope.message_2 = "no.";
                    $scope.yard_count_no = data.data[0].yard_level_no;
                }).catch(function (r) {

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                // $scope.savingErro='Something wet worng!';
            }).finally(function () {
                // $scope.savingData=false;

            })
        };




    }).filter('carpenterFilter', function () {
    return function (val) {
        var carpenter;
        if (val == 1) {
            return carpenter = 'Yes';
        } else if (val == 0) {
            return carpenter = 'No';
        }
        return carpenter = '';
    }
}).filter('offloadingFilter', function () {
    return function (val) {
        var offloading;
        if (val == 1) {
            return offloading = 'Equipment';
        } else if (val == 0) {
            return offloading = 'Labour';
        }
        return offloading = '';
    }
});