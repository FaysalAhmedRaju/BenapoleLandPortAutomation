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

        $scope.$watch('recive_comment_yard', function (val) {

            $scope.recive_comment_yard = $filter('uppercase')(val);

        }, true);

        $scope.$watch('recive_comment_shed', function (val) {

            $scope.recive_comment_shed = $filter('uppercase')(val);

        }, true);

        $scope.$watch('equip_name_yard', function (val) {

            $scope.equip_name_yard = $filter('uppercase')(val);

        }, true);

        $scope.$watch('equip_name_shed', function (val) {

            $scope.equip_name_shed = $filter('uppercase')(val);

        }, true);


        $scope.show = true;
        $scope.savingData = false;
        //$scope.shedYardMultiple = true;
        $scope.ModalShow = true;
        $scope.ReceiveDatetimeDisable = true;
        $scope.truckInfo = true;
        $scope.serachField = true;
        $scope.reportByManifestBtn = true;
        $scope.showonly = true;
        $scope.transshipment = null;
        $scope.button = true;
        $scope.AfterSearchShow = false;

        //graphical view global variable
        $scope.truck_id = null;
        $scope.posted_yard_shed = null;
        $scope.graph_data = null;
        $scope.row = null;
        $scope.column = null;
        $scope.WeightMoreThanReceive = '';
        // $scope.shedYardModelMessage = '';


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
        };

        enterKeyService.enterKey('#wareHouseform input ,#wareHouseform button');


        var eqip_name = [
            'FORK LIFT',
            'CRANE'
        ];

        $("#equip_name_yard").autocomplete({
            source: function (request, response) {
                console.log(eqip_name);
                var result = $.ui.autocomplete.filter(eqip_name, request.term);
                //$("#add").toggle($.inArray(request.term, result) < 0);
                response(result);
            }
        });

        $("#equip_name_shed").autocomplete({
            source: function (request, response) {
                console.log(eqip_name);
                var result = $.ui.autocomplete.filter(eqip_name, request.term);
                //$("#add").toggle($.inArray(request.term, result) < 0);
                response(result);
            }
        });

        //pagination Every Page serial number......
        // $scope.serial = 1;
        // $scope.itemPerpage = 5;
        // $scope.getPageCount = function(n){
        //     $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        // }
        //pagination serial number   End ......

        //After Pressing Enter and found by searchKey
        $scope.truckNoOrManifestWiseSearch = function () {
            //to make blanck for graphicaal view
            $scope.posted_yard_shed = null;
            $scope.graph_data = null;
            $scope.receive_weight = null;
            $scope.weightMessage = '';


            //console.log($scope.selection.singleSelect);
            var letters = /^[A-Za-z]+$/;
            if ($scope.searchKey.match(letters)) {
                if ($scope.selection.singleSelect == 'truckNo') {
                    $scope.errorType = 'The Truck Number is Invalid.';
                } else if ($scope.selection.singleSelect == 'manifestNo') {
                    $scope.errorType = 'The Manifest Number is Invalid.';
                } else {
                    $scope.errorType = 'Invalid Input. Please Select Search By Options.';
                    $('#errorType').show().delay(5000).slideUp(1000);
                }
                $scope.table = false;
            } else {
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
                }
                console.log(data);
                //var ManifestNo = $scope.ManifestNo;
                $scope.totalReceiveWeight = 0;
                $scope.totalReceivePkg = 0;
                $scope.totalUnloadLaborWeight = 0;
                $scope.totalUnloadLaborPkg = 0;
                $scope.totalUnloadEquipmetWeight = 0;
                $scope.totalUnloadEqupmentPkg = 0;
                $http.post("/warehouse/api/receive/search-truck-details-data", data)
                    .then(function (data) {
                        console.log(data.data);
                        if(data.status == 203) {
                            $scope.errorType = data.data.error;
                            $('#errorType').show().delay(5000).slideUp(1000);
                            return;
                        }

                        if(data.data.length > 0) {
                            $scope.table = true;
                            $scope.allTrucksData = data.data;

                            $scope.AfterSearchShow = true;
                            angular.forEach($scope.allTrucksData, function (v, k) {
                                if (v.receive_weight == null) {
                                    $scope.totalReceiveWeight += v.tweight_wbridge != null ? parseFloat(v.tweight_wbridge) : 0;
                                } else {
                                    $scope.totalReceiveWeight += v.receive_weight != null ? parseFloat(v.receive_weight) : 0;
                                }
                                $scope.totalReceivePkg += v.receive_package != null ? parseFloat(v.receive_package) : 0;
                                $scope.totalUnloadLaborWeight += v.total_labor_weight != null ? parseFloat(v.total_labor_weight) : 0;
                                $scope.totalUnloadLaborPkg += v.total_labor_pkg != null ? parseFloat(v.total_labor_pkg) : 0;
                                $scope.totalUnloadEquipmetWeight += v.total_equip_weight != null ? parseFloat(v.total_equip_weight) : 0;
                                $scope.totalUnloadEqupmentPkg += v.total_equip_pkg != null ? parseFloat(v.total_equip_pkg) : 0;
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
                            } else {
                                $scope.errorType = 'Something Went Wrong!';
                                $('#errorType').show().delay(5000).slideUp(1000);
                            }
                            $scope.table = false;
                        }
                    }).catch(function (r) {
                    console.log(r);
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    }
                    $scope.errorType = 'Something Went Wrong!';
                }).finally(function () {
                    $scope.savingData = false;
                })
            }
        };

        $scope.location_type = '1';
        //After Clicking Posting Yard Entry Button
        $scope.getValues = function (truck) {
            $scope.vehicle_type_flag = truck.vehicle_type_flag;
            if (truck.vehicle_type_flag >= 11) {
                $scope.savingError = "Self Can Not Receive!";
                $('#savingError').show().delay(5000).slideUp(1000);
            } else {
                $scope.truck_id = truck.id;     //to use in graphicla view
                console.log('Truck ID: ' + $scope.truck_id);
               // $.growl.warning({ title: "Notice",message: "Please Wait!"});
                $scope.manifest = truck.manifest;
                $scope.manifest_id = truck.manf_id;
                $scope.test_truck_type = truck.truck_type;
                $scope.test_truck_no = truck.truck_no;
                $scope.posted_yard_shed = truck.posted_yard_shed;     // for Graphical view
                $scope.allocatedShedYard = truck.posted_yard_shed;
                console.log($scope.allocatedShedYard);
                $http.get("/warehouse/api/receive/get-yard-graph-details/" + truck.posted_yard_shed)
                    .then(function (r) {
                        $scope.graph_data = r.data
                    }).catch(function (r) {

                    console.log(r);
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }

                }).finally(function () {


                });
                $scope.selectedShed = truck.yard_id + truck.row + truck.column;
                $scope.row = truck.row;
                $scope.column = truck.column;
                $scope.savingSuccess = false;
                $scope.savingError = false;
                $scope.manifestInfo = false;
                $scope.truckInfo = true;
                $scope.show = false;
                $scope.ModalShow = false;
                //$scope.showonly = true;
                $scope.truck_no = truck.truck_no;
                $scope.truck_type = truck.truck_type;
                $scope.goods_id = truck.goods_id;
                $scope.selectedStyle = truck.id;
                $scope.id = truck.id;
                var data = {
                    goods_id: $scope.goods_id
                };
                $http.post("/warehouse/api/receive/get-goods-details-data", data)
                    .then(function (data) {
                        $scope.goodsData = data.data;
                        console.log($scope.goodsData);
                      //  $.growl.notice({ title: "Notice",message: "Goods Loaded!"});
                    }).catch(function (r) {
                        console.log(r);
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        } else {
                            $.growl.error({message: "It has Some Error!"});
                        }

                }).finally(function () {


                });
                if (truck.holtage_charge_flag != null) {
                    $scope.holtage_charge_flag = truck.holtage_charge_flag.toString();
                }
                if (truck.receive_package != null) {
                    $scope.receive_package = truck.receive_package;
                } else {
                    $scope.receive_package = null;
                }
                console.log(truck.gweight_wbridge);
                $scope.gweight_wbridge = truck.gweight_wbridge;
                var manifest_middle_string = truck.manifest.split('/');
                if ((truck.gweight_wbridge == null || truck.receive_weight == null || truck.tweight_wbridge == null) || isNaN(manifest_middle_string[1])) {
                    if (truck.gweight_wbridge == null && truck.tweight_wbridge == null) {
                        $scope.receiveWeightFlagWhenNoWeightBridge = true;
                        $scope.weightMessage = 'Weightbridge Entry/Exit Not Done.';
                    } else if (truck.tweight_wbridge == null) {
                        $scope.receiveWeightFlagWhenNoWeightBridge = true;
                        $scope.weightMessage = 'Weightbridge Exit Not Done.';
                    }
                } else {
                    //$scope.showonly = true;
                    if (truck.receive_weight == null) {
                        $scope.receive_weight = truck.tweight_wbridge != null ? parseFloat(truck.tweight_wbridge) : null;
                    } else {
                        $scope.receive_weight = truck.receive_weight != null ? parseFloat(truck.receive_weight) : null;
                    }

                }
                $scope.receiveWeightFlagWhenNoWeightBridge = true;
                    //$scope.showonly = false;
                console.log('Mani ID' + truck.manf_id);
                console.log('Truck ID' + $scope.truck_id);
                if(truck.receive_weight == null) {
                    $http.get('/warehouse/api/receive/get-manifest-gross-weight-for-receive/' + truck.manf_id + '/' + $scope.truck_id)
                        .then(function (data) {
                            console.log(data.data[0]);
                            // $.growl.notice({ title: "Notice",message: "Manifest Gross Weight Loaded!"});
                            if (data.data.length == 1) {
                                let rcv_weight = null;
                                if ((data.data[0].tweight_wbridge == null) || (data.data[0].tweight_wbridge == 0)) {
                                    $scope.gweight = data.data[0].gweight;
                                    let gwight = data.data[0].gweight;
                                    let dividedBy = $scope.dataLength;
                                    rcv_weight = (gwight / dividedBy).toFixed(2);
                                    console.log($scope.dataLength);
                                } else {
                                    let twight = data.data[0].tweight_wbridge;
                                    console.log(twight);
                                    rcv_weight = twight;
                                }
                                console.log(typeof (rcv_weight));
                                $scope.receive_weight = parseFloat(rcv_weight); //$scope.gweight / $scope.dataLength;
                            } else {
                                $scope.receive_weight = 0;
                            }
                        }).catch(function (r) {
                        console.log(r);
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        }
                        $scope.savingError = 'Something went wrong!';
                        $('#savingError').show().delay(5000).slideUp(1000);

                    }).finally(function () {

                    });
                }
                //$scope.showonly = truck.gweight_wbridge == null ? false : true;
                $scope.receiveWeightFlagWhenNoWeightBridge = (truck.gweight_wbridge == null || truck.tweight_wbridge == null) ? true : false;
                $scope.receive_created_at = truck.receive_created_at;
                $scope.vehicle_type_flag = truck.vehicle_type_flag;
                if ($scope.vehicle_type_flag == 2 || $scope.vehicle_type_flag == 3) {
                    $scope.showChasismodal = true;
                } else {
                    $scope.showChasismodal = false;
                }
                $http.get('/warehouse/api/receive/all-chassis-details-data/' + $scope.truck_id)
                    .then(function (data) {
                        $scope.allChassisDetails = data.data;
                        console.log($scope.allChassisDetails);
                        if($scope.allChassisDetails.length>0){
                          //  $.growl.notice({ title: "Notice",message: "Chassis Data Loaded!"});
                        }
                    }).catch(function (r) {
                        console.log(r);
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        } else {
                            $.growl.error({message: "It has Some Error!"});
                        }
                    }).finally(function () {
                    });
                //$scope.location_type = '1';
                $scope.changeShedYardView($scope.location_type);
            }
        };

        $scope.changeShedYardView = function(location_type) {
            if(location_type == 1) {
                $scope.shedView = true;
                $scope.yardView = false;
                $scope.truck_id != null ? $scope.getShedData() : null;
            } else {
                $scope.shedView = false;
                $scope.yardView = true;
                $scope.truck_id != null ? $scope.getYardData() : null;
            }
        };
        $scope.changeShedYardView(1);

        $scope.getShedData = function() {
            $scope.savingData = true;
            $http.get('/warehouse/api/receive/get-shed-data/' + $scope.truck_id)
                .then(function(data) {
                    if(data.data.length > 0) {
                       // $.growl.notice({ title: "Notice",message: "Shed Data Loaded!"});
                        $scope.shedData = data.data[0];
                        console.log($scope.shedData);
                        $scope.shed_yard_weight_id = $scope.shedData.id;
                        $scope.labor_package_shed = $scope.shedData.unload_labor_package;
                        $scope.labor_unload_shed = parseFloat($scope.shedData.unload_labor_weight);
                        $scope.equipment_package_shed = $scope.shedData.unload_equipment_package;
                        $scope.equip_unload_shed = parseFloat($scope.shedData.unload_equip_weight);
                        $scope.equip_name_shed = $scope.shedData.unload_equip_name;
                        $scope.recive_comment_shed = $scope.shedData.unload_comment;
                        $scope.shifting_flag_shed = $scope.shedData.unload_shifting_flag.toString();
                        $scope.posted_shed = $scope.shedData.unload_yard_shed.toString();
                    } else {
                        $scope.shed_yard_weight_id = null;
                        $scope.labor_package_shed = null;
                        $scope.labor_unload_shed = null;
                        $scope.equipment_package_shed = null;
                        $scope.equip_unload_shed = null;
                        $scope.equip_name_shed = null;
                        $scope.recive_comment_shed = null;
                        $scope.shifting_flag_shed = '0';
                    }
                }).catch(function(r) {
                    console.log(r);
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }

            }). finally(function() {
                $scope.savingData = false;
            })
        };

        $scope.getYardData = function () {
            $scope.savingData = true;
            $http.get('/warehouse/api/receive/get-yard-data/' + $scope.truck_id)
                .then(function (data) {
                    console.log(data.data);
                    if (data.data.length > 0) {
                        $.growl.notice({ title: "Notice",message: "Yard Data Loaded!"});
                        $scope.yardData = data.data[0];
                        $scope.shed_yard_weight_id = $scope.yardData.id;
                        $scope.labor_package_yard = $scope.yardData.unload_labor_package;
                        $scope.labor_unload_yard = parseFloat($scope.yardData.unload_labor_weight);
                        $scope.equipment_package_yard = $scope.yardData.unload_equipment_package;
                        $scope.equip_unload_yard = parseFloat($scope.yardData.unload_equip_weight);
                        $scope.equip_name_yard = $scope.yardData.unload_equip_name;
                        $scope.posted_yard = $scope.yardData.shed_yard_id.toString();
                        $scope.shifting_flag_yard = $scope.yardData.unload_shifting_flag.toString();
                        $scope.recive_comment_yard = $scope.yardData.unload_comment;
                    } else {
                        $scope.shed_yard_weight_id = null;
                        $scope.labor_package_yard = null;
                        $scope.labor_unload_yard = null;
                        $scope.equipment_package_yard = null;
                        $scope.equip_unload_yard = null;
                        $scope.equip_name_yard = null;
                        $scope.shifting_flag_yard = '0';
                        $scope.recive_comment_yard = null;
                    }

                }).catch(function (r) {
                    console.log(r);
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                }).finally(function () {
                    $scope.savingData = false;
                });

        };

        $scope.saveShedData = function(shedForm) {
            $scope.savingData = true;
            if ($scope.vehicle_type_flag == 2 || $scope.vehicle_type_flag == 3) {
                $scope.savingError = "Please Choose Location Type Yard and Fillup Information.";
                $('#savingError').show().delay(5000).slideUp(1000);
                $scope.savingData = false;
                return;
            }

            if($scope.allTrucksData[0].posted_yard_shed_name == null){
                $scope.savingError = "Shed/Yard Not Assigned";
                $('#savingError').show().delay(5000).slideUp(1000);
                $scope.savingData = false;
                return;
            }
            console.log(shedForm.$invalid);
            if(shedForm.$invalid && !($scope.labor_unload_shed || $scope.equip_unload_shed)) {
                $scope.submitShedForm = true;
                $scope.savingData = false;
                return;
            } else {
                $scope.submitShedForm = false;
            }
            $scope.sumOfEquipLabor = $scope.equip_unload_shed != null ? parseFloat($scope.equip_unload_shed) : 0 +
                $scope.labor_unload_shed != null ? parseFloat($scope.labor_unload_shed) : 0;
            if ($scope.sumOfEquipLabor > $scope.receive_weight) {
                $scope.savingError = "Weight Can Not More Than Receive Weight!";
                $('#savingError').show().delay(5000).slideUp(1000);
                $scope.savingData = false;
                return;
            }

            var data = {
                truck_id: $scope.truck_id,
                receive_weight: $scope.receive_weight,
                receive_package: $scope.receive_package,
                holtage_charge_flag: $scope.holtage_charge_flag,
                gweight_wbridge: $scope.gweight_wbridge,
                receive_created_at: $scope.receive_created_at,
                allocatedShedYard: $scope.allocatedShedYard,

                shed_yard_weight_id: $scope.shed_yard_weight_id,
                labor_package_shed: $scope.labor_package_shed,
                labor_unload_shed: $scope.labor_unload_shed,
                equipment_package_shed: $scope.equipment_package_shed,
                equip_unload_shed: $scope.equip_unload_shed,
                equip_name_shed: $scope.equip_name_shed,
                recive_comment_shed: $scope.recive_comment_shed,
                shifting_flag_shed: $scope.shifting_flag_shed,
                posted_shed: $scope.posted_shed,

                //graph details
                posted_yard_shed: $scope.posted_yard_shed,
                row: $scope.row,
                column: $scope.column
            };

            $http.post("/warehouse/api/receive/save-shed-data", data)
                .then(function (data) {
                    console.log(data);
                    if (data.status == 203) {
                        $scope.savingError = data.data.error;
                        $('#savingError').show().delay(5000).slideUp(1000);
                        return;
                    }
                    $scope.savingSuccess = 'WareHouse Entry Saved Successfully!';
                    $('#savingSuccess').show().delay(5000).slideUp(1000, function () {
                        $scope.selectedStyle = 0;
                    });
                    $scope.Blank();
                    $scope.truckNoOrManifestWiseSearch();
                }).catch(function (r) {
                console.log(r);
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

        };

        $scope.clearShedData = function() {
            bootbox.confirm({
                message: "Do you want to clear shed receive?",
                backdrop: true,
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    $scope.deleteShedData(result);
                }
            }).css({
                'text-align':'center',
                'top':'0',
                'bottom': '0',
                'left': '0',
                'right': '0',
                'margin': 'auto'
            });
        }

        $scope.deleteShedData = function(result) {
            if(result == true) {
                $scope.savingData = true;
                if($scope.shed_yard_weight_id != null) {
                    var data = {
                        shed_yard_weight_id : $scope.shed_yard_weight_id,
                        truck_id : $scope.truck_id,
                        allocatedShedYard: $scope.allocatedShedYard,
                        posted_shed: $scope.posted_shed,
                        gweight_wbridge: $scope.gweight_wbridge
                    }
                    $http.post("/warehouse/api/receive/delete-shed-data", data)
                        .then(function(data){
                            console.log(data);
                            if (data.status == 203) {
                                $scope.savingError = data.data.error;
                                $('#savingError').show().delay(5000).slideUp(1000);
                                return;
                            }
                            if(data.data == 'Deleted') {
                                $scope.savingError = "Shed Receive Cleared";
                                $('#savingError').show().delay(5000).slideUp(1000);
                            }
                        }).catch(function(r){
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
                    }).finally(function(){
                        $scope.savingData = false;
                        $scope.Blank();
                        $scope.truckNoOrManifestWiseSearch();
                    })
                }
            } else {
                return false;
            }
        }


        $scope.saveYardData = function (yardForm) {
            $scope.savingData = true;
            if($scope.allTrucksData[0].posted_yard_shed_name == null){
                $scope.savingError = "Shed/Yard Not Assigned";
                $('#savingError').show().delay(5000).slideUp(1000);
                $scope.savingData = false;
                return;
            }
            if(yardForm.$invalid && !($scope.labor_unload_yard || $scope.equip_unload_yard)) {
                $scope.submitYardForm = true;
                $scope.savingData = false;
                return;
            } else {
                $scope.submitYardForm = false;
            }
            $scope.sumOfEquipLabor = $scope.equip_unload_yard != null ? parseFloat($scope.equip_unload_yard) : 0 +
                $scope.labor_unload_yard != null ? parseFloat($scope.labor_unload_yard) : 0;
            console.log($scope.sumOfEquipLabor);
            if ($scope.sumOfEquipLabor > $scope.receive_weight) {
                $scope.savingError = "Weight Can Not More Than Receive Weight!";
                $('#savingError').show().delay(5000).slideUp(1000);
                $scope.savingData = false;
                return;
            }

            if ($scope.allChassisDetails.length < 1 && $scope.vehicle_type_flag == 2) {
                $scope.savingError = "Chassis(Chassis On Truck) Missing!";
                $('#savingError').show().delay(5000).slideUp(1000);
                $scope.savingData = false;
                return;
            }
            if ($scope.allChassisDetails.length < 1 && $scope.vehicle_type_flag == 3) {
                $scope.savingError = "Trucktor(Trucktor On Truck) Missing!";
                $('#savingError').show().delay(5000).slideUp(1000);
                $scope.savingData = false;
                return;
            }
            var data = {
                truck_id: $scope.truck_id,
                receive_weight: $scope.receive_weight,
                receive_package: $scope.receive_package,
                holtage_charge_flag: $scope.holtage_charge_flag,
                gweight_wbridge: $scope.gweight_wbridge,
                receive_created_at: $scope.receive_created_at,
                allocatedShedYard: $scope.allocatedShedYard,

                shed_yard_weight_id: $scope.shed_yard_weight_id,
                labor_package_yard: $scope.labor_package_yard,
                labor_unload_yard: $scope.labor_unload_yard,
                equipment_package_yard: $scope.equipment_package_yard,
                equip_unload_yard: $scope.equip_unload_yard,
                equip_name_yard: $scope.equip_name_yard,
                recive_comment_yard: $scope.recive_comment_yard,
                shifting_flag_yard: $scope.shifting_flag_yard,
                posted_yard: $scope.posted_yard,

                //graph details
                posted_yard_shed: $scope.posted_yard_shed,
                row: $scope.row,
                column: $scope.column
            };

            $http.post("/warehouse/api/receive/save-yard-data", data)
                .then(function (data) {
                    console.log(data);
                    if (data.status == 203) {
                        $scope.savingError = data.data.error;
                        $('#savingError').show().delay(5000).slideUp(1000);
                        return;
                    }
                    $scope.savingSuccess = 'WareHouse Entry Saved Successfully!';
                    $('#savingSuccess').show().delay(5000).slideUp(1000, function () {
                        $scope.selectedStyle = 0;
                    });
                    $scope.Blank();
                    $scope.truckNoOrManifestWiseSearch();
                }).catch(function (r) {
                console.log(r);
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                }
                $scope.savingError = 'Something Went Wrong!';
                $('#savingError').show().delay(5000).slideUp(1000, function () {
                    $scope.selectedStyle = 0;
                });
            }).finally(function () {
                $scope.savingData = false;
            })

        };

        $scope.clearYardData = function() {
            bootbox.confirm({
                message: "Do you want to clear yard receive?",
                backdrop: true,
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    $scope.deleteYardData(result);
                }
            }).css({
                'text-align':'center',
                'top':'0',
                'bottom': '0',
                'left': '0',
                'right': '0',
                'margin': 'auto'
            });
        }

        $scope.deleteYardData = function(result) {
            if(result == true) {
                $scope.savingData = true;
                if($scope.shed_yard_weight_id != null) {
                    var data = {
                        shed_yard_weight_id : $scope.shed_yard_weight_id,
                        truck_id : $scope.truck_id,
                        allocatedShedYard: $scope.allocatedShedYard,
                        posted_yard: $scope.posted_yard
                       /* gweight_wbridge: $scope.gweight_wbridge*/
                    }
                    console.log(data);
                    $http.post("/warehouse/api/receive/delete-yard-data", data)
                        .then(function(data){
                            console.log(data);
                            if (data.status == 203) {
                                $scope.savingError = data.data.error;
                                $('#savingError').show().delay(5000).slideUp(1000);
                                return;
                            }
                            if(data.data == 'Deleted') {
                                $scope.savingError = "Yard Receive Cleared";
                                $('#savingError').show().delay(5000).slideUp(1000);
                            }
                        }).catch(function(r){
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
                    }).finally(function(){
                        $scope.savingData = false;
                        $scope.Blank();
                        $scope.truckNoOrManifestWiseSearch();
                    })
                }
            } else {
                return false;
            }
        }

        $scope.Blank = function() {
            $scope.truck_id = null;
            $scope.receive_weight = null;
            $scope.receive_package = null;
            $scope.recive_comment = null;
            $scope.holtage_charge_flag = '0';
            $scope.gweight_wbridge = null;
            $scope.receive_created_at = null;

            $scope.shed_yard_weight_id = null;

            //Shed
            $scope.labor_unload_shed = null;
            $scope.labor_package_shed = null;
            $scope.equip_unload_shed = null;
            $scope.equipment_package_shed = null;
            $scope.equip_name_shed = null;
            $scope.recive_comment_shed = null;
            $scope.shifting_flag_shed = '0';

            //Yard
            $scope.labor_package_yard = null;
            $scope.labor_unload_yard = null;
            $scope.equipment_package_yard = null;
            $scope.equip_unload_yard = null;
            $scope.equip_name_yard = null;
            $scope.recive_comment_yard = null;
            $scope.shifting_flag_yard = '0';

            //$scope.location_type = '1';

            //graph
            $scope.selectedShed = null;
            $scope.posted_yard_shed = null;
            $scope.row = null;
            $scope.column = null;


            $scope.message_1 = null;
            $scope.message_2 = null;
            $scope.yard_count_no = null;
            $scope.shed_count_no = null;
            $scope.messagePartOne = null;
            $scope.messagePartTwo = null;

            $scope.weightMessage = null;
            $scope.sumOfEquipLabor = null;

            $scope.show = true;
            $scope.receiveWeightFlagWhenNoWeightBridge = false;
            
            $scope.submitYardForm = false;
            $scope.submitShedForm = false
        };
        $scope.Blank();

        //---------------------------------------- chassis details function -------------------------
        $scope.chassisDetailsFunction = function () {
            console.log($scope.truck_id);

            console.log($scope.manifest);
            console.log($scope.truck_no);

            $scope.updateBtn = false;
            $http.get('/warehouse/api/receive/all-chassis-details-data/' + $scope.truck_id)
                .then(function (data) {
                        // console.log(data.data);
                        $scope.allChassisDetails = data.data;
                        console.log($scope.allChassisDetails);
                }).catch(function (r) {
                    console.log(r);
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                }).finally(function () {

                });
        };
        
        $scope.edit = function (chassis) {
            console.log(chassis);
            $scope.updateBtn = true;
            $scope.chassis_type = chassis.chassis_type;
            $scope.chassis_no = chassis.chassis_no;
            $scope.chassis_id = chassis.id;
            $scope.truck_id = chassis.truck_id;
        };

        $scope.saveChasisData = function (chassisForm) {
            if (chassisForm.$invalid) {
                $scope.submitChassisForm = true;
                return;
            } else {
                $scope.submitChassisForm = false;
            }
            console.log($scope.chassis_id);
            console.log($scope.id);
            console.log($scope.manifest_id);

            var data = {
                chassis_id: $scope.chassis_id,
                truck_id: $scope.id,
                chassis_type: $scope.chassis_type,
                chassis_no: $scope.chassis_no,
                manifest_id: $scope.manifest_id
            };
            console.log(data);
            $http.post("/warehouse/api/receive/save-chassis-data", data)
                .then(function (data) {
                    $scope.chassis_type = null;
                    $scope.chassis_no = null;
                    $scope.updateBtn = false;
                    $scope.chassis_id = null;

                    $scope.chassisDetailsFunction();
                    $scope.savingChasisSuccess = 'Chassis Entry Saved Successfully!';
                    $('#savingChasisSuccess').show().delay(5000).slideUp(1000);
                }).catch(function (r) {
                console.log(r);
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingChasisError = 'Something went wrong!';
                $('#savingChasisError').show().delay(5000).slideUp(1000);
            }).finally(function () {

            })
        };

        $scope.delete = function (chassis) {

            console.log(chassis);

            $scope.chassis_type = chassis.chassis_type;
            $scope.chassis_no = chassis.chassis_no;
            $scope.chassis_id = chassis.id;
            $scope.truck_id = chassis.truck_id;

        };


        $scope.deleteChassis = function () {

            console.log($scope.chassis_id);
            console.log($scope.chassis_type);
            console.log($scope.chassis_no);

            $http.get("/warehouse/api/receive/delete-chassis/" + $scope.chassis_id)
                .then(function (data) {

                    $scope.deleteSuccessMsg = true;
                    $("#deleteSuccess").show().fadeTo(1000, 500).slideUp(1500, function () {
                        $("#deleteSuccess").slideUp(2000);
                    });

                    $scope.chassisDetailsFunction();

                    setTimeout(function () {
                        $("#deleteManifestConfirm").modal('hide')

                    }, 1500)


                }).catch(function (r) {

                console.log('error');
                console.log(r);
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {


            })


        };

        //---------------------------------- chassis all end --------------------

        $scope.YardNOForLevelNO = function () {
            var data = {
                yard_no: $scope.posted_shed
            };
            console.log($scope.posted_shed);
            $http.post("/warehouse/api/receive/count-current-date-wise-shed-yard-no", data)
                .then(function (data) {
                    console.log(data.data);
                    $scope.message_1 = "This is";
                    $scope.message_2 = "no.";
                    $scope.shed_count_no = data.data[0].yard_level_no;
                }).catch(function (r) {
                console.log(r);
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


        $scope.shedYardWeightCount = function () {
            var data = {
                yard_no: $scope.posted_yard
            };

            $http.post("/warehouse/api/receive/shed-yard-weight-count", data)
                .then(function (data) {
                    console.log(data.data);
                    $scope.messagePartOne = "This is";
                    $scope.messagePartTwo = "no.";
                    $scope.yard_count_no = data.data[0].yard_level_no;
                }).catch(function (r) {
                console.log(r);
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

        //New Manifest Work [START]
        //service added 7-6-2017

        $scope.keyBoard = function (event) {
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
        };

        $scope.$watch('searchKey', function () {
            $scope.searchKey = manifestService.addYearWithManifest($scope.searchKey, $scope.keyboardFlag, $scope.selection.singleSelect);
        });

        //New Manifest Work [END]


        // Graphical View =============================================START=======================================


        $scope.rows = [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10
        ];

        $scope.columns = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F'
        ];

        /*
         $http.get("api/getYardList")

         .then(function (r) {
         console.log(r.data)

         $scope.yardList = r.data

         });*/

//when select yard from dropdown
        /*$scope.getGraphicalView=function (yard_id) {

         if (yard_id!=undefined)
         {
         console.log(yard_id);
         $scope.yardSelected=true;
         }
         else {
         $scope.yardSelected=false;


         }

         }*/


        //  $scope.truck_id=100;

        /*   $http.get("api/getYardGraphDetails/"+$scope.truck_id)

         .then(function (r) {

         console.log(r.data)
         $scope.weights = r.data

         })*/


        // $http.get("/api/Yard_wereHouseRecevingDetailsJson")                   //function 5
        //     .then(function (data) {
        //             // console.log(data.data);

        //             $scope.allYardData = data.data;
        //         }
        //     )
        $scope.getWeight = function (row, column) {


            //  console.log($scope.weights)
            var record = _.find($scope.graph_data, {
                row: row,
                column: column
            });

            // Was a record found with the row and column?
            if (record) {

                $scope.weight_in_cell = record.weight;
                // If so return its weight.
                return record.weight;
            }


        };


        /*  $http.get("api/getYardGraphDetails")

         .then(function (r) {
         console.log(r.data)

         $scope.weights = r.data

         })
         */

        $scope.onCellselect = function (row, column, weight) {

            console.log(row + column);

            $scope.selectedShed = $scope.posted_yard_shed + row + column;
            $scope.row = row;
            $scope.column = column;


            $('#graphView').modal('hide');


        };


        // Graphical View ==========================END=================
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