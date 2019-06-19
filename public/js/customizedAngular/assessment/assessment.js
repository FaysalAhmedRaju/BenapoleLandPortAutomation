angular.module('assessmentApp', ['angularUtils.directives.dirPagination', 'ngAnimate', 'ngTagsInput', 'customServiceModule'])
    .controller('assessmentCtrl', function ($scope, $http, $timeout, $filter, manifestService, amountToTextService) {

        $scope.cnfNameDisable = true;
        $scope.Math = window.Math;
        //new Manifest Added Start - 6/8/17
        $scope.role_name = role_name;
        $scope.role_id = role_id;
        console.log($scope.role_name);
        $scope.keyBoard = function (event) {
            console.log(event);
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
            console.log($scope.keyboardFlag)
        }

        $scope.$watch('searchText', function () {
            $scope.searchText = manifestService.addYearWithManifest($scope.searchText, $scope.keyboardFlag);
            console.log($scope.searchText);
        });
        $scope.$watch('searchText', function (val) {

            $scope.searchText = $filter('uppercase')(val);

        }, true);

//Global Variable
        $scope.tariff_year = null;
        $scope.partial_status = null;
        $scope.partial_number_list = [];
        //$scope.partial_number_list = null;
        $scope.transshipment = false;
        $scope.assessmentSavePage = true;
        $scope.manifestFound = false;
        $scope.assessmentApproved = false;
        $scope.local_transport_type = null;

        $scope.TotalAmount = 0;
        $scope.Manifest_id = 0;
        $scope.ManifestNo = 0;
        $scope.bassisOfCharge = 0;
        $scope.self_flag=null;
        $scope.gross_weight = 0;
        $scope.weighbridge_weight = 0;

        $scope.chargableTonForWarehouse = 0;
        $scope.shed_or_yard = null;
        $scope.receive_flag = 0;
        $scope.listOfWarning = [];

//warehouse======================
        $scope.WarehouseReceiveWeight = 0;
        $scope.TotalWarehouseCharge = 0
        $scope.receive_date = null;
        $scope.item_wise_shed_details = null;
        $scope.item_wise_yard_details = null;

//Handling Charge============
        //--offload-------------
        $scope.OffloadLabour = 0
        $scope.OffLoadingEquip = 0
        $scope.OffloadLabourCharge = 0
        $scope.OffLoadingEquipCharge = 0
        $scope.TotalForOffloadLabour = 0
        $scope.TotalForOffloadEquip = 0
        $scope.unloadingShifting = false;

        //---load-----------
        $scope.loadLabour = 0;
        $scope.loadingEquip = 0;
        $scope.loadLabourCharge = 0;
        $scope.loadingEquipCharge = 0;
        $scope.TotalForloadLabour = 0;
        $scope.TotalForloadEquip = 0;
        $scope.loadingShifting = false;
        $scope.totalUnloadCharge = 0;
        $scope.totalloadCharge = 0;

        //Hading Modal
        $scope.OffloadLabourModal = 0;
        $scope.OffloadLabourChargeModal = 0;
        $scope.TotalForOffloadLabourModal = 0;
        $scope.OffLoadingEquipModal = 0;
        $scope.OffLoadingEquipChargeModal = 0;
        $scope.unloadingShiftingModal = false;
        $scope.TotalForOffloadEquipModal = 0;

        $scope.loadLabourModal = 0;
        $scope.loadLabourChargeModal = 0;
        $scope.TotalForloadLabourModal = 0;
        $scope.loadingEquipModal = 0;
        $scope.loadingEquipChargeModal = 0;
        $scope.loadShiftingModal = false;
        $scope.TotalForloadEquipModal = 0;

        $scope.totalUnloadChargeOld = 0;
        $scope.totalUnloadChargeNew = 0;
        $scope.totalLoadChargeOld = 0;
        $scope.totalLoadChargeNew = 0;
        $scope.customomizedHandlingCharge = 0;
//Entrance Fee

        $scope.entrance_fee_local = 0;
        $scope.entrance_fee_foreign = 0;
        $scope.totalForeignTruckEntranceFee = 0;
        $scope.previousTotalForeignTruck = 0;
        $scope.totalLocalTruckEntranceFee = 0;
        $scope.totalForeignTruck = 0;
        $scope.totalLocalVan = 0;


//Carpenter charge===================
        $scope.carpenterChargesOpenClose = 0;
        $scope.carpenterChargesRepair = 0;
        $scope.carpenterPackages = 0;
        $scope.carpenterRepairPackages = 0;

        $scope.totalcarpenterChargesOpenClose = 0;
        $scope.totalcarpenterChargesRepair = 0;
//Holiday' charge==========
        $scope.foreign_holiday_charge = 0;
        $scope.holidayTotalForeignTruck = 0;
        $scope.holidayTotalLocalTruck = 0;


        $scope.TotalForeignHolidayCharge = 0
        $scope.TotalLocalHolidayCharge = 0

        //Night' charge==========
        $scope.nightTotalForeignTruck = 0
        //$scope.nightTotalLocalTruck = 0

        $scope.TotalForeignNightCharge = 0;
        // $scope.TotalLocalNightCharge = 0;
        //$scope.Night_charges=0;

        //haltage charge========
        $scope.haltagesForeignScaleWeight = 0;
        $scope.haltagesForeignReceiveWeight = 0;
        $scope.foreign_haltage_charge = 0;
        $scope.local_haltage_charge = 0;
        $scope.haltagesForeignTruck = null;
        $scope.haltagesTotalForeignTruck = 0
        $scope.haltagesTotalLocalTruck = 0;
        $scope.haltagesTotalDayLocalTruck = 0;
        $scope.haltagesTotalDayForeignTruck = 0;
        $scope.HaltageCharge = 0;
        $scope.TotalHaltageForeignCharge = 0;
        $scope.TotalHaltageLocalCharge = 0;

        //Weigment Charge
        $scope.weightment_measurement_charges = 0;
        $scope.weightmentChargesForeign = 0;
        $scope.weightmentChargesForeign = 0;
        $scope.weightmentChargesLocal = 0;

//document Charge
        $scope.documentCharges = 0;
        console.log($scope.documentCharges);

        //--------------delivery requisition

        $scope.transportVanMust = true;
        $scope.transportTruckMust = false;

        //Truck To Truck Flag
        $scope.truckToTruckFlag = 0;


//====================Manifest search==========================
        $scope.cleanPage = function() {
            $scope.partial_number_list = [];
            $scope.partial_status = null;
            blank();
        }

        $scope.get_partial = function (manifest_no, status) {
            console.log(status);
            $scope.manifestSearch(manifest_no, status);
        };

        $scope.manifestSearch = function (text, partial_status = null) {
            //  console.log('oiid');
            console.log(partial_status);
            $scope.insertSuccessMsg = false;
            if ($scope.form.$valid) {

                //  if(text) {

                blank();
                $scope.saveAttemptWithoutManifest = false;
                $scope.errorDuringCheckingManifest = false;
                $scope.customError = null;
                $scope.Manifest_id = null;
                $scope.TotalAmount = 0;
                $scope.dataLoading = true;
                $scope.assessmentApproved = false;

                var data = {
                    mani_no: text,
                    partial_status: partial_status

                }
                $http.post("/assessment/api/check-manifest-for-assessment-and-all-charges-and-partial-list", data)
                    .then(function (data) {
                        console.log(data);
                        console.log(data.status);
                        if (data.status == 203) {//unauthorized user
                            $scope.customError = data.data.message;
                            $scope.dataLoading = false;
                            $scope.previouAssValue = false;
                            return;
                        }

                        console.log('Manifest Details:');
                        console.log(data.data[0][0]);
                        console.log('All Charges:');
                        console.log(data.data[1]);
                        $scope.manifestDetails = data.data[0][0];
                        $scope.allCharges = data.data[1];
                        $scope.partial_number = data.data[2][0].max_partial_number;
                        //$scope.partial_number_list = [];
                        console.log(partial_status);
                        console.log('Partial Number:');
                        console.log($scope.partial_number);
                        console.log($scope.partial_number_list);
                        $scope.tariff_year = $scope.allCharges.length > 0 ? $scope.allCharges[0].charges_year : null;
                        console.log('Tariff Year' + $scope.tariff_year);
                        if($scope.tariff_year == null) {
                            $.growl.error({message: 'No Tariff Found'});
                            return;
                        }
                        for (var x = 0; x < $scope.partial_number; x++) {
                            $scope.partial_number_list[x] = x+1;
                        }
                        console.log($scope.partial_status);
                        if($scope.partial_status == null) {
                            $scope.partial_status = $scope.partial_number_list[$scope.partial_number-1];
                        }
                        console.log($scope.partial_status);
                        console.log($scope.manifestDetails.manifest_no);
                        
                        $scope.manifestFound = true;

                        //get Assessment  heading
                        $scope.Manifest_id = $scope.manifestDetails.manifest_id;
                        $scope.ManifestNo = $scope.manifestDetails.manifest_no;
                        $scope.Mani_date = $scope.manifestDetails.manifest_date;
                        $scope.Bill_No = $scope.manifestDetails.bill_entry_no;
                        $scope.Bill_date = $scope.manifestDetails.bill_entry_date;
                        $scope.Custome_release_No = $scope.manifestDetails.custom_realise_order_No;
                        $scope.Custome_release_Date = $scope.manifestDetails.custom_realise_order_date;
                        $scope.Consignee = $scope.manifestDetails.importer;
                        $scope.consignee_vat_flag = $scope.manifestDetails.importer_vat_flag;
                        $scope.Consignor = $scope.manifestDetails.exporter;
                        $scope.package_no = $scope.manifestDetails.package_no;
                        $scope.package_type = $scope.manifestDetails.package_type;
                        $scope.local_transport_type = $scope.manifestDetails.local_transport_type;
                        $scope.loadShifting = $scope.manifestDetails.load_shifting == 1 ? true : false;
                        $scope.unloadShifting = $scope.manifestDetails.unload_shifting ? true : false;
                        $scope.totalItems = $scope.manifestDetails.totalItems;
                        $scope.description_of_goods = $scope.manifestDetails.description_of_goods;
                        $scope.bassisOfCharge = Math.round($scope.manifestDetails.chargeable_weight);
                        $scope.chargeable_weight = Math.ceil($scope.manifestDetails.chargeable_weight / 1000);
                        $scope.CnF_Agent = $scope.manifestDetails.cnf_name;
                        $scope.posted_yard_shed = $scope.manifestDetails.posted_yard_shed;
                        $scope.self_flag = $scope.manifestDetails.self_flag;
                        $scope.truckToTruckFlag = $scope.manifestDetails.truck_to_truck_flag;
                        $scope.receive_flag = $scope.manifestDetails.receive_flag;

                        $scope.gross_weight = Math.ceil($scope.manifestDetails.gweight/1000);
                        $scope.weighbridge_weight = Math.ceil($scope.manifestDetails.total_weighbridge_weight/1000);

                        if ($scope.manifestDetails.previous_ass_value == null) { //Assessment Not Done
                            $scope.previouAssValue = false;
                        } else {
                            if($scope.consignee_vat_flag == 0) {
                                $scope.previousAssementValue = ((( parseFloat(Math.ceil($scope.manifestDetails.previous_ass_value)) * 15 ) / 100)
                            + parseFloat(Math.ceil($scope.manifestDetails.previous_ass_value)));
                            } else {
                               $scope.previousAssementValue =  Math.ceil($scope.manifestDetails.previous_ass_value);
                            }
                            
                            $scope.previouAssValue = true;
                        }

                        console.log('load-shift: ' + $scope.loadShifting +' '+ 'unload-shift: '+ $scope.unloadShifting)

                        if (!$scope.Consignee) {
                            $scope.listOfWarning.push('No Vat ID Found!')
                        }
                        if (!$scope.Consignor) {
                            $scope.listOfWarning.push('No Consignor Found!')
                        }
                        if (!$scope.description_of_goods) {
                            $scope.listOfWarning.push('No Item Selected!')
                        }
                        if (!$scope.posted_yard_shed && $scope.truckToTruckFlag == 0) {
                            $scope.listOfWarning.push('No Shed/Yard Selected!')
                        }
                        console.log($scope.partial_status);
                        $scope.AssessmentData(text, $scope.partial_status);
                    }).catch(function (r) {
                        console.log(r)
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        }
                        
                        $scope.errorDuringCheckingManifest = true;
                        $scope.errorDuringCheckingManifestTxt = 'Internal Server Error';
                        $scope.previouAssValue = false;
                        $scope.dataLoading = false;
                        console.log('err');
                }).finally(function () {
                })
            }
        };

        /*$scope.addDays = function(stringDate, days) {
            var date = new Date(stringDate);
            date.setDate(date.getDate() + parseInt(days-1));
            return $filter('dateShort')(date, 'dd-MM-yyyy');
        }*/

        $scope.AssessmentData = function (text, partial_status = null) {//text

            var data = {
                mani_no: text,
                partial_status: partial_status

            };
            //==========WareHouse Charge====================
            $http.post("/assessment/api/get-warehouse-data-for-assessment", data)
                .then(function (data) {
                    console.log('WareHouse Details:');
                    console.log(data.data);
                    console.log($scope.TotalAmount);

                    var warehouse = data.data;

                    $scope.receive_date = warehouse.receive_date;
                    $scope.deliver_date = warehouse.delivery_date;
                    $scope.free_items = warehouse.free_items;
                    $scope.items_warehouse_rent = warehouse.warehouse_rent_for_items;

                    $scope.item_wise_shed_details = warehouse.item_wise_shed_details;
                    $scope.item_wise_yard_details = warehouse.item_wise_yard_details;
                    $scope.TotalWarehouseCharge = 0;
                    if($scope.item_wise_shed_details.length > 0) {
                        angular.forEach($scope.item_wise_shed_details, function (v, k) {
                            $scope.TotalWarehouseCharge += v.total_charge;
                        })
                    }
                    if($scope.item_wise_yard_details) {
                        angular.forEach($scope.item_wise_yard_details, function (v, k) {
                            $scope.TotalWarehouseCharge += v.total_charge;
                        })
                    }
                    if($scope.TotalWarehouseCharge > 0) {
                        $scope.WareHouseRentDay = 1;
                    } else {
                        $scope.WareHouseRentDay = 0;
                    }
                    console.log('TotalWareHouseCharge' +  $scope.TotalWarehouseCharge);

                    console.log($scope.TotalWarehouseCharge);
                    $scope.TotalAmount += $scope.TotalWarehouseCharge;
                    console.log($scope.TotalAmount);
                    /*
                     $scope.WarehouseChargeforPercentage = $scope.TotalWarehouseCharge;
                     $scope.TotalAmount += $scope.totalFirstSlabCharge;
                     $scope.TotalAmount += $scope.totalSecondSlabCharge;
                     $scope.TotalAmount += $scope.totalThirdSlabCharge;*/
                    $scope.amountInWord($scope.TotalAmount);

                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                }).finally(function () {

                })


            //==========Handling Charge====================
            $http.post("/assessment/api/get-handling-charge-for-assesment", data)
                .then(function (data) {
                    $scope.handling = data.data[0];
                    console.log('Handling:');
                    console.log($scope.handling);
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 32) {
                            $scope.OffloadLabourCharge = v.rate_of_charges;
                        }
                        if (v.charge_id == 36) {
                            $scope.OffLoadingEquipCharge = v.rate_of_charges;
                            $scope.loadingEquipCharge = v.rate_of_charges;
                        }
                        if (v.charge_id == 34) {
                            $scope.loadLabourCharge = v.rate_of_charges;
                        }
                    })
                    if (!$scope.OffloadLabourCharge || !$scope.OffLoadingEquipCharge) {
                        $scope.listOfWarning.push('No Offload Labour/Equip Charge Found!')
                    }
                    if (!$scope.loadLabourCharge || !$scope.loadingEquipCharge) {
                        $scope.listOfWarning.push('No Load Labour/Equip Charge Found!')
                    }
                    //offLoading charge-
                    $scope.maxWeight = $scope.chargeable_weight;
                    $scope.totalWeight = $scope.handling.labor_unload + $scope.handling.equip_unload;

                    console.log($scope.maxWeight);
                    console.log($scope.totalWeight);
                    console.log($scope.self_flag);
                    if($scope.partial_status == 1 && $scope.receive_flag == 1) {
                        if($scope.maxWeight > $scope.totalWeight && $scope.self_flag == 0) {
                            if ($scope.handling.labor_unload && $scope.handling.equip_unload) {
                                $scope.oneWeight = Math.ceil($scope.maxWeight / 2);
                                $scope.OffloadLabour = $scope.oneWeight;
                                $scope.OffLoadingEquip = $scope.oneWeight;
                            } else {
                                if ($scope.handling.labor_unload) {
                                    $scope.OffloadLabour = $scope.maxWeight;
                                } else {
                                    $scope.OffLoadingEquip = $scope.maxWeight;
                                }
                            }
                        } else if($scope.self_flag == 1) { // self
                            $scope.OffloadLabour = 0;
                            $scope.OffLoadingEquip = 0;
                        } else {
                            $scope.OffloadLabour = $scope.handling.labor_unload;
                            $scope.OffLoadingEquip = $scope.handling.equip_unload;
                        }
                        console.log('unloadShifting:');
                        console.log($scope.unloadShifting);
                        $scope.TotalForOffloadLabour = ($scope.OffloadLabourCharge * $scope.OffloadLabour );
                        $scope.TotalForOffloadEquip = ($scope.OffLoadingEquipCharge *
                        ($scope.unloadShifting ? 2 : 1) * $scope.OffLoadingEquip ); 
                    } else { //partial status > 1
                        $scope.OffloadLabour = 0;
                        $scope.OffLoadingEquip = 0;
                        $scope.TotalForOffloadLabour = 0;
                        $scope.TotalForOffloadEquip = 0;
                        $scope.unloadShifting = false;

                    }
                    
                    $scope.totalUnloadCharge = $scope.TotalForOffloadLabour + $scope.TotalForOffloadEquip;
                    $scope.TotalAmount += $scope.totalUnloadCharge;

                    //loading
                    if($scope.partial_status == 1) {
                        if ($scope.handling.labor_load > 0) {
                            $scope.loadLabour = $scope.handling.labor_load;
                            $scope.TotalForloadLabour = ($scope.loadLabourCharge * $scope.loadLabour);
                        }
                        if ($scope.handling.equip_load > 0) {
                            $scope.loadingEquip = $scope.handling.equip_load;
                            $scope.TotalForloadEquip = ($scope.loadingEquipCharge * $scope.loadingEquip *
                            ($scope.loadShifting ? 2 : 1));
                        }
                    } else {
                        $scope.loadLabour = 0;
                        $scope.TotalForloadLabour = 0;
                        $scope.loadingEquip = 0;
                        $scope.TotalForloadEquip = 0;
                        $scope.loadShifting = false;

                    }

                    //Truck To Truck
                    if($scope.truckToTruckFlag == 1) {
                        $scope.OffloadLabour = $scope.handling.labor_unload;
                        $scope.OffLoadingEquip = $scope.handling.equip_unload;
                        $scope.loadLabour = $scope.handling.labor_load;
                        $scope.loadingEquip = $scope.handling.equip_load;

                        $scope.TotalForOffloadLabour = ($scope.OffloadLabourCharge * $scope.OffloadLabour );
                        $scope.TotalForOffloadEquip = ($scope.OffLoadingEquipCharge *
                            ($scope.unloadShifting ? 2 : 1) * $scope.OffLoadingEquip );
                        $scope.TotalForloadLabour = ($scope.loadLabourCharge * $scope.loadLabour);
                        $scope.TotalForloadEquip = ($scope.loadingEquipCharge * $scope.loadingEquip *
                            ($scope.loadShifting ? 2 : 1));
                    }
                    $scope.totalloadCharge = $scope.TotalForloadLabour + $scope.TotalForloadEquip;
                    $scope.TotalAmount += $scope.totalloadCharge;
                    $scope.amountInWord($scope.TotalAmount);
                }).catch(function () {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                }).finally(function () {

                })

            //==========Other Dues====================
            $http.post("/assessment/api/get-other-dues-for-assessment", data)
                .then(function (data) {
                    console.log(data);
                    console.log('EntranceCarpenterWeighmentCharge:');
                    console.log(data.data[0][0]);
                    $scope.entranceCarpenterWeighmentCharge = data.data[0][0];
                    console.log('HaltageCharge-Foreign:')
                    console.log(data.data[1]);
                    $scope.haltagesForeignTruck = $scope.partial_status == 1 ? data.data[1] : null;
                    console.log('HaltageCharge-Local:')
                    console.log(data.data[2][0]);
                    $scope.haltagesLocalTruck = data.data[2][0];
                    console.log('NightCharge:');
                    console.log(data.data[3]);
                    $scope.nightChargeForeign = data.data[3].length > 0 ? data.data[3][0] : null;
                    console.log('DocumentCharges:');
                    console.log(data.data[4]);
                    $scope.documentDetails = data.data[4];
                    console.log('HolidayForeign:');
                    $scope.holidayForeignTruck = data.data[5];
                    console.log($scope.holidayForeignTruck);
                    console.log('HolidayLocal:');
                    $scope.holidayLocalTruck = data.data[6];
                    console.log($scope.holidayLocalTruck);
                    //Entrance Fee
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 2) {
                            $scope.entrance_fee_truck = v.rate_of_charges
                        }
                        if (v.charge_id == 6) {
                            $scope.entrance_fee_van = v.rate_of_charges
                        }
                    });
                    $scope.entrance_fee_foreign = $scope.entrance_fee_truck;
                    $scope.entrance_fee_local = $scope.entrance_fee_truck;
                    if (!$scope.entrance_fee_foreign || !$scope.entrance_fee_local) {
                        $scope.listOfWarning.push('No Entrance Fee Found!')
                    }
                    if($scope.partial_status == 1 ) {
                        $scope.totalForeignTruck = $scope.entranceCarpenterWeighmentCharge.foreign_truck;
                    } else {
                       $scope.totalForeignTruck = 0; 
                    }
                    $scope.previousTotalForeignTruck = $scope.totalForeignTruck;
                    $scope.totalLocalTruck = $scope.entranceCarpenterWeighmentCharge.transport_truck;
                    $scope.totalLocalVan = $scope.entranceCarpenterWeighmentCharge.transport_van;

                    $scope.totalForeignTruckEntranceFee = $scope.totalForeignTruck * $scope.entrance_fee_foreign;
                    $scope.totalLocalTruckEntranceFee = $scope.totalLocalTruck * $scope.entrance_fee_local;
                    $scope.totalLocalVanEntranceFee = $scope.totalLocalVan * $scope.entrance_fee_van;

                    $scope.TotalAmount += $scope.totalForeignTruckEntranceFee;
                    $scope.TotalAmount += $scope.totalLocalTruckEntranceFee;
                    $scope.TotalAmount += $scope.totalLocalVanEntranceFee;
                    console.log('after entrance fee ' + $scope.TotalAmount);

                    //Carpenter Charges
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 8) {
                            $scope.carpenterChargesOpenClose = v.rate_of_charges
                        }
                        // if (v.charge_id == 10) {
                        //     $scope.carpenterChargesRepair = v.rate_of_charges
                        // }
                    })
                    if (!$scope.carpenterChargesOpenClose) {
                        $scope.listOfWarning.push('No Carpenter Opening Charge Found!')
                    }
                    // if(!$scope.carpenterChargesRepair) {
                    //         $scope.listOfWarning.push('No Carpenter Repairing Charge Found!')
                    // }
                    $scope.carpenterPackages = $scope.entranceCarpenterWeighmentCharge.carpenter_packages;
                    $scope.totalcarpenterChargesOpenClose = $scope.carpenterPackages
                        * $scope.carpenterChargesOpenClose;
                    $scope.TotalAmount += $scope.totalcarpenterChargesOpenClose;
                    // $scope.carpenterRepairPackages = $scope.entranceCarpenterWeighmentCharge.carpenter_repair_packages;
                    // $scope.totalcarpenterChargesRepair = $scope.carpenterRepairPackages 
                    //                                         * $scope.carpenterChargesRepair;
                    // $scope.TotalAmount += $scope.totalcarpenterChargesRepair;

                    //Weighment measurement  Charges
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 12) {
                            $scope.weightment_measurement_charges = v.rate_of_charges
                        }
                    });
                    if (!$scope.weightment_measurement_charges) {
                        $scope.listOfWarning.push('No Weighment Charge Found!')
                    }
                    if($scope.partial_status == 1 ) {
                        $scope.totalForeignTruckForWeighment = $scope.entranceCarpenterWeighmentCharge.foreign_truck;
                        $scope.weightmentChargesForeign = $scope.weightment_measurement_charges * $scope.totalForeignTruckForWeighment * 2;
                    } else {
                        $scope.totalForeignTruckForWeighment = 0;
                        $scope.weightmentChargesForeign = 0;
                    }
                    
                    $scope.TotalAmount += $scope.weightmentChargesForeign;

                    $scope.local_truck_weighment = $scope.entranceCarpenterWeighmentCharge.local_truck_weighment;
                    $scope.weightmentChargesLocal = $scope.weightment_measurement_charges *
                        $scope.local_truck_weighment * 2;
                    $scope.TotalAmount += $scope.weightmentChargesLocal;
                    console.log('after handling and EntranceCarpenterWeighmentCharge= ' + $scope.TotalAmount);

                    //Haltage Foreign Truck
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 20) {
                            $scope.foreign_haltage_charge = v.rate_of_charges;
                            $scope.local_haltage_charge = v.rate_of_charges;
                        }
                    });
                    if (!$scope.foreign_haltage_charge || !$scope.local_haltage_charge) {
                        $scope.listOfWarning.push('No Haltage Charge Found!')
                    }
                    //Foreign
                    $scope.haltagesForeignScaleWeight = 0;
                    $scope.haltagesForeignReceiveWeight = 0;
                    if($scope.partial_status ==1) {
                        angular.forEach($scope.haltagesForeignTruck, function (v, k) {
                            if (v.haltage_days > 0 && v.holtage_charge_flag == 0) {
                                $scope.haltagesTotalForeignTruck += 1;
                                $scope.TotalHaltageForeignCharge += (v.haltage_days * $scope.foreign_haltage_charge);
                                $scope.haltagesTotalDayForeignTruck += v.haltage_days;
                            }
                            $scope.haltagesForeignScaleWeight += v.tweight_wbridge != null ? parseFloat(v.tweight_wbridge) : 0;
                            $scope.haltagesForeignReceiveWeight += v.receive_weight != null ? parseFloat(v.receive_weight) : 0;
                        });
                    } else {
                        $scope.haltagesTotalForeignTruck = 0;
                        $scope.TotalHaltageForeignCharge = 0;
                        $scope.haltagesTotalDayForeignTruck = 0;
                    }
                    //Local
                    if($scope.haltagesLocalTruck.local_haltage > 0) {
                        console.log($scope.totalLocalTruck);
                        $scope.haltagesTotalLocalTruck = $scope.totalLocalTruck;
                        $scope.haltagesTotalDayLocalTruck = $scope.haltagesLocalTruck.local_haltage;
                        $scope.TotalHaltageLocalCharge = $scope.haltagesTotalLocalTruck*$scope.haltagesTotalDayLocalTruck*$scope.local_haltage_charge;
                    } else {
                        $scope.haltagesTotalLocalTruck = 0;
                        $scope.haltagesTotalDayLocalTruck = 0;
                        $scope.TotalHaltageLocalCharge = 0;

                    }
                    console.log($scope.TotalHaltageForeignCharge);
                    console.log($scope.TotalHaltageLocalCharge)
                    $scope.TotalAmount += $scope.TotalHaltageLocalCharge;
                    $scope.TotalAmount += $scope.TotalHaltageForeignCharge;
                    console.log('after haltage= ' + $scope.TotalAmount);

                    //Night Charge
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 16) {
                            $scope.rate_of_night_charge = v.rate_of_charges
                        }
                    });
                    if (!$scope.rate_of_night_charge) {
                        $scope.listOfWarning.push('No Night Charge Found!')
                    }
                    if($scope.partial_status == 1) {
                        $scope.nightTotalForeignTruck = $scope.nightChargeForeign != null ? $scope.nightChargeForeign.total_foreign_truck_night : 0;
                    } else {
                        $scope.nightTotalForeignTruck = 0;
                    }
                    
                    $scope.TotalForeignNightCharge = $scope.rate_of_night_charge * $scope.nightTotalForeignTruck;
                    $scope.TotalAmount += $scope.TotalForeignNightCharge;
                    console.log('after GetNightChargesForAssesment= ' + $scope.TotalAmount);
                    //Document Charge
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 18) {
                            $scope.documentCharges = v.rate_of_charges
                        }
                    });
                    console.log($scope.documentCharges);

                    if (!$scope.documentCharges) {
                        $scope.listOfWarning.push('No Document Charge Found!')
                    }
                    if ($scope.documentDetails != 'notFound') {
                        $scope.numberOfDocuments = $scope.documentDetails[0].number_of_document;
                        $scope.totalDocumentCharges = (parseFloat($scope.documentCharges) * $scope.numberOfDocuments );
                        
                    } else {
                        $scope.numberOfDocuments = 0;
                        $scope.totalDocumentCharges = 0;
                    }
                    $scope.TotalAmount += $scope.totalDocumentCharges;
                    console.log('after DocumentChargesForAssesment= ' + $scope.TotalAmount);
                    //Holiday Charge
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 14) {
                            $scope.foreign_holiday_charge = v.rate_of_charges;
                            $scope.local_holiday_charge = v.rate_of_charges;
                        }
                    })
                    if (!$scope.foreign_holiday_charge || !$scope.local_holiday_charge) {
                        $scope.listOfWarning.push('No Holiday Charge Found!')
                    }
                    //--Foreign
                    if($scope.partial_status == 1) {
                        if ($scope.holidayForeignTruck.length > 0) {
                            console.log('foreign');
                            console.log($scope.TotalAmount);
                            $scope.holidayTotalForeignTruck = 0;
                            $scope.TotalForeignHolidayCharge = 0;
                            $scope.holidayTotalForeignTruck = $scope.holidayForeignTruck.length;
                            angular.forEach($scope.holidayForeignTruck, function (v, k) {
                                $scope.TotalForeignHolidayCharge += 1 * $scope.foreign_holiday_charge;
                            })
                        } else {
                            $scope.holidayTotalForeignTruck = 0;
                            $scope.TotalForeignHolidayCharge = 0; 
                        }
                    } else {
                        $scope.holidayTotalForeignTruck = 0;
                        $scope.TotalForeignHolidayCharge = 0;
                    }
                    $scope.TotalAmount += $scope.TotalForeignHolidayCharge;
                    //--Local
                    if($scope.holidayLocalTruck.length > 0) {
                        console.log('local');
                        $scope.holidayTotalLocalTruck = $scope.holidayLocalTruck.length;
                        if ($scope.holidayLocalTruck[0].holiday != 0) {
                            $scope.TotalLocalHolidayCharge = 1 * $scope.local_holiday_charge;
                        } else {
                            $scope.TotalLocalHolidayCharge = 1 * $scope.local_holiday_charge;
                        }
                        
                    } else {
                      $scope.holidayTotalLocalTruck = 0;
                      $scope.TotalLocalHolidayCharge = 0;  
                    }
                    console.log($scope.TotalLocalHolidayCharge);
                    $scope.TotalAmount += $scope.TotalLocalHolidayCharge;
                    console.log('afterholiday= ' + $scope.TotalAmount);
                    $scope.amountInWord($scope.TotalAmount);
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            }).finally(function () {
                $scope.dataLoading = false;
            })

        };//END manifestSearch()

        $scope.amountInWord = function (totalAmount) {
            console.log(totalAmount);
            if($scope.consignee_vat_flag == 0) {
                $scope.grand_total = Math.ceil(Math.ceil(totalAmount) * 15 / 100 + Math.ceil(totalAmount));
            } else {
               $scope.grand_total =  Math.ceil(totalAmount);
            }
            console.log($scope.grand_total);
            $scope.inword = amountToTextService.amountToText($scope.grand_total) + " Taka Only";
        };

        $scope.entranceFeeChangeFlag = 0;
        $scope.changeNumberOfForeignTruckFee = function(foreignTruck) {
            if($scope.partial_status > 1 || foreignTruck == null) {
                return;
            }
            console.log('Before Change Entrance Fee' + $scope.TotalAmount);
            $scope.TotalAmount -= $scope.totalForeignTruckEntranceFee;
            if (!$scope.entrance_fee_foreign) {
                $scope.listOfWarning.push('No Entrance Fee Found!')
            }
            if($scope.partial_status == 1 ) {
                $scope.totalForeignTruck = foreignTruck;
            } else {
                $scope.totalForeignTruck = 0;
            }

            $scope.totalForeignTruckEntranceFee = $scope.totalForeignTruck * $scope.entrance_fee_foreign;
            $scope.TotalAmount += $scope.totalForeignTruckEntranceFee;
            $scope.amountInWord($scope.TotalAmount);
            $scope.totalForeignTruck != $scope.previousTotalForeignTruck ? $.growl.notice({message: "Foreign Truck Number Changes!"}) : 0;
            console.log('After Change Entrance Fee' + $scope.TotalAmount);
            $scope.entranceFeeChangeFlag = $scope.totalForeignTruck == $scope.previousTotalForeignTruck ? 0 : 1;
        }

        $scope.getHandlingWeight = function() {
            $scope.OffloadLabourModal = $scope.OffloadLabour;
            $scope.OffloadLabourChargeModal = $scope.OffloadLabourCharge;
            $scope.TotalForOffloadLabourModal = $scope.TotalForOffloadLabour;
            $scope.OffLoadingEquipModal = $scope.OffLoadingEquip;
            $scope.OffLoadingEquipChargeModal = $scope.OffLoadingEquipCharge;
            $scope.unloadingShiftingModal = $scope.unloadShifting;
            $scope.TotalForOffloadEquipModal = $scope.TotalForOffloadEquip;

            $scope.loadLabourModal = $scope.loadLabour;
            $scope.loadLabourChargeModal = $scope.loadLabourCharge;
            $scope.TotalForloadLabourModal = $scope.TotalForloadLabour;
            $scope.loadingEquipModal = $scope.loadingEquip;
            $scope.loadingEquipChargeModal = $scope.loadingEquipCharge;
            $scope.loadShiftingModal = $scope.loadShifting;
            $scope.TotalForloadEquipModal = $scope.TotalForloadEquip;
        }

        $scope.getChangedWeight = function() {
            $scope.totalUnloadTon = $scope.OffloadLabourModal + $scope.OffLoadingEquipModal;
            $scope.totalloadTon = $scope.loadLabourModal + $scope.loadingEquipModal;
            //console.log($scope.totalUnloadTon > $scope.chargeable_weight);
            //console.log($scope.totalUnloadTon);
            $scope.TotalForOffloadLabourModal = 0;
            $scope.TotalForOffloadEquipModal = 0;
            $scope.TotalForloadLabourModal = 0;
            $scope.TotalForloadEquipModal = 0;

            // if(($scope.totalUnloadTon > $scope.chargeable_weight) || 
            //     ($scope.totalloadTon > $scope.chargeable_weight)) {
            //     $.growl.error({message: "You Can't Change More Then Maximum Weight!"});
            //     return;
            // }

            if($scope.OffloadLabourModal > 0) {
                $scope.TotalForOffloadLabourModal = (parseFloat($scope.OffloadLabourModal)*
                            parseFloat($scope.OffloadLabourChargeModal)).toFixed(2);
            }
            if($scope.OffLoadingEquipModal > 0) {
                $scope.TotalForOffloadEquipModal = (parseFloat($scope.OffLoadingEquipChargeModal) *
                        parseFloat($scope.unloadingShiftingModal ? 2 : 1) * 
                        parseFloat($scope.OffLoadingEquipModal)).toFixed(2);
            }
            if($scope.loadLabourModal > 0) {
                $scope.TotalForloadLabourModal = (parseFloat($scope.loadLabourModal).toFixed(2)*
                    parseFloat($scope.loadLabourChargeModal)).toFixed(2);
            }
            if($scope.loadingEquipModal > 0) {
                $scope.TotalForloadEquipModal = (parseFloat($scope.loadingEquipChargeModal) * 
                    parseFloat($scope.loadingEquipModal) * 
                    parseFloat($scope.loadShiftingModal ? 2 : 1)).toFixed(2);
            }
        }

        $scope.assigntoHandlingCharge = function() {
            $scope.totalUnloadChargeOld = (parseFloat($scope.TotalForOffloadLabour) + parseFloat($scope.TotalForOffloadEquip)).toFixed(2);
            $scope.totalUnloadChargeNew = (parseFloat($scope.TotalForOffloadLabourModal) + parseFloat($scope.TotalForOffloadEquipModal)).toFixed(2);
            $scope.totalLoadChargeOld = (parseFloat($scope.TotalForloadLabour) + parseFloat($scope.TotalForloadEquip)).toFixed(2);
            $scope.totalLoadChargeNew = (parseFloat($scope.TotalForloadLabourModal) + parseFloat($scope.TotalForloadEquipModal)).toFixed(2);

            if(($scope.totalUnloadChargeOld != $scope.totalUnloadChargeNew) 
                || ($scope.totalLoadChargeOld != $scope.totalLoadChargeNew)) {
                console.log($scope.TotalAmount);
                if($scope.TotalForOffloadLabour != $scope.TotalForOffloadLabourModal) {
                     console.log('TotalForOffloadLabour ' + $scope.TotalAmount);
                   $scope.TotalAmount = (parseFloat($scope.TotalAmount) - parseFloat($scope.TotalForOffloadLabour)).toFixed(2);
                   $scope.TotalAmount = (parseFloat($scope.TotalAmount) + parseFloat($scope.TotalForOffloadLabourModal)).toFixed(2);
                   console.log('TotalForOffloadLabour changed ' + $scope.TotalAmount);
                }
                
                if($scope.TotalForOffloadEquip != $scope.TotalForOffloadEquipModal) {
                    console.log('TotalForOffloadEquip ' + $scope.TotalAmount);
                    $scope.TotalAmount = (parseFloat($scope.TotalAmount) - parseFloat($scope.TotalForOffloadEquip)).toFixed(2);
                    $scope.TotalAmount = (parseFloat($scope.TotalAmount) + parseFloat($scope.TotalForOffloadEquipModal)).toFixed(2);
                    console.log('TotalForOffloadEquip changed ' + $scope.TotalAmount);
                }
                if($scope.TotalForloadLabour != $scope.TotalForloadLabourModal) {
                    console.log('TotalForloadLabour ' +$scope.TotalAmount);
                    $scope.TotalAmount = (parseFloat($scope.TotalAmount) - parseFloat($scope.TotalForloadLabour)).toFixed(2);
                    $scope.TotalAmount = (parseFloat($scope.TotalAmount) + parseFloat($scope.TotalForloadLabourModal)).toFixed(2);
                    console.log('TotalForloadLabour changed ' +$scope.TotalAmount);
                }
                if($scope.TotalForloadEquip != $scope.TotalForloadEquipModal) {
                    console.log('TotalForloadEquipModal ' +$scope.TotalAmount);
                    console.log($scope.TotalForloadEquip + " " + $scope.TotalForloadEquipModal);
                    $scope.TotalAmount = (parseFloat($scope.TotalAmount) - parseFloat($scope.TotalForloadEquip)).toFixed(2);
                    //console.log((parseFloat($scope.TotalAmount) + parseFloat($scope.TotalForloadEquipModal)).toFixed(2));
                    $scope.TotalAmount = (parseFloat($scope.TotalAmount) + parseFloat($scope.TotalForloadEquipModal)).toFixed(2);
                    console.log('TotalForloadEquipModal changed ' +$scope.TotalAmount);
                }
                console.log($scope.TotalAmount);
                $scope.amountInWord($scope.TotalAmount);
                $scope.customomizedHandlingCharge = 1;
            } else {
                $scope.customomizedHandlingCharge = 0;
            }

            $scope.OffloadLabour = $scope.OffloadLabourModal;
            $scope.OffloadLabourCharge = $scope.OffloadLabourChargeModal;
            $scope.TotalForOffloadLabour =  $scope.TotalForOffloadLabourModal;
            $scope.OffLoadingEquip = $scope.OffLoadingEquipModal;
            $scope.OffLoadingEquipCharge = $scope.OffLoadingEquipChargeModal;
            $scope.unloadShifting = $scope.unloadingShiftingModal;
            $scope.TotalForOffloadEquip = $scope.TotalForOffloadEquipModal;

            $scope.loadLabour = $scope.loadLabourModal;
            $scope.loadLabourCharge = $scope.loadLabourChargeModal;
            $scope.TotalForloadLabour = $scope.TotalForloadLabourModal;
            $scope.loadingEquip = $scope.loadingEquipModal;
            $scope.loadingEquipCharge = $scope.loadingEquipChargeModal;
            $scope.loadShifting = $scope.loadShiftingModal;
            $scope.TotalForloadEquip = $scope.TotalForloadEquipModal;
        }


        //=====Haltage Charge change option

        $scope.getTrucksForHaltageChargeChange = function () {
            if($scope.partial_status != 1) {
                return;
            }

            if (!$scope.Manifest_id) {
                return;
            }

            $scope.trucks_loading_for_changes_haltage = true;
            $scope.trucks_loading_for_changes_haltage_error = false;
            $scope.allTrucksData = null;
            $http.get("/assessment/api/get-foreign-trucks-details-data/"+$scope.Manifest_id)
                .then(function (data) {
                    console.log(data);
                    $scope.allTrucksData = data.data;
                }).catch(function (r) {
                    console.log(r);
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    }
                    $scope.trucks_loading_for_changes_haltage_error = true;
                }).finally(function () {
                    $scope.trucks_loading_for_changes_haltage = false;
                })


        }


        $scope.changeHaltageFlagStatus = function (id, status) {
            console.log(id)

            $scope.changing_haltage_charge_flag = true;

            var data = {
                truck_id: id,
                status: status
            }
            $http.post("/assessment/api/change-haltage-charge-flag-for-foreign-truck", data)

                .then(function (data) {
                    console.log(data);

                    $scope.haltage_charge_success_div = true;

                    $("#haltage-charge-success").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#haltage-charge-success").slideUp(5000);
                    });

                    $scope.haltage_charge_changes_success = data.data.updated;
                    $scope.getTrucksForHaltageChargeChange();
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

                    $scope.haltage_charge_error_div = true;
                    $scope.haltage_charge_changes_error_txt = 'Something Went Wrong!';

                    $("#haltage-charge-error").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#haltage-charge-error").slideUp(7000);
                    });
                }).finally(function () {
                    $scope.changing_haltage_charge_flag = false;

                })
        }


        //saveAssessment=======================================

        $scope.saveAssessment = function () {

            $scope.savingData = true;
            $scope.assSaveError = false;
            console.log($scope.Manifest_id);
            if ($scope.Manifest_id == null) {
                $scope.saveAttemptWithoutManifest = true;
                $scope.savingData = false;
                $("#saveError").delay(3000).slideUp(4000);

                return;
            }
            // if($scope.item_wise_yard_details.length == 0 && $scope.yardWeight != null) {
            //     $scope.savingData = false;
            //     $scope.assSaveError = true;
            //     $scope.assSaveErrorMsgTxt = "Yard receive found. But no item Added!";
            //     $("#assSaveError").show().delay(5000).slideUp(4000);
            //     return;
            // }
            
            //var d = new Date($scope.receive_date);
            //var date = d.format("dd-mm-yyyy");
            //var dt12=$filter($scope.receive_date)(new Date(),'yyyy-MM-dd');
            $scope.rcvdateFiltered = $scope.receive_date != null ? $filter('dateShort')($scope.receive_date, 'dd-MM-yyyy') : 'Not Received';
            //$scope.freeEndDayFiltered = $scope.freeEndDay != null ? $filter('dateShort')($scope.freeEndDay, 'dd-MM-yyyy') : ($scope.truckToTruckFlag==1 ? 'Truck To Truck' : null);
            $scope.deliverDateFiltered = $filter('dateShort')($scope.deliver_date, 'dd-MM-yyyy');
            //$scope.warehouseChargeStartDayFiltered = $scope.WarehouseChargeStartDay != null ? $filter('dateShort')($scope.WarehouseChargeStartDay, 'dd-MM-yyyy') : 'Truck To Truck';

            var data = {
                mani_no : $scope.searchText,
                Mani_id: $scope.Manifest_id,
                partial_status: $scope.partial_status,
                tariff_year: $scope.tariff_year,
                vat_flag: $scope.consignee_vat_flag == 0 ? 1 : 0,
                truck_to_truck_flag: $scope.truckToTruckFlag,
                chargableTonForWarehouse: $scope.chargableTonForWarehouse,
                WareHouseRentDay: $scope.WareHouseRentDay,
                TotalWarehouseCharge: $scope.TotalWarehouseCharge,

                //Warehouse Rent dd-MM-yyyy hh:mm:ss a
                dateOfUnloading: $filter('dateShort')($scope.receive_date, 'dd-MM-yyyy HH:mm:ss'),
                //freePeriod: $scope.partial_status == 1 ? (($scope.rcvdateFiltered != null && $scope.freeEndDayFiltered != null) ? $scope.rcvdateFiltered + " - " + $scope.freeEndDayFiltered + " = FT" : null) : null,
                //rentDuePeriod: $scope.warehouseChargeStartDayFiltered + " - " + $scope.deliverDateFiltered + " = " + $scope.WareHouseRentDay,
                weight: $scope.bassisOfCharge,
                goodDescription: $scope.description_of_goods,
                noOfPkg: parseInt($scope.package_no),//+ " " + $scope.package_type,
                deliver_date: $scope.deliver_date,
                //totalLocalTruck : $scope.totalLocalTruck,
                self_flag:$scope.self_flag,

                //Handling charge
                //--offload-------------
                OffloadLabour: $scope.OffloadLabour,
                offloadShifting: $scope.unloadShifting,
                OffLoadingEquip: $scope.OffLoadingEquip,
                OffloadLabourCharge: $scope.OffloadLabourCharge,
                OffLoadingEquipCharge: $scope.OffLoadingEquipCharge,
                TotalForOffloadLabour: $scope.TotalForOffloadLabour,
                TotalForOffloadEquip: $scope.TotalForOffloadEquip,
                //---load-----------
                loadLabour: $scope.loadLabour,
                loadingEquip: $scope.loadingEquip,
                loadShifting: $scope.loadShifting,
                loadLabourCharge: $scope.loadLabourCharge,
                loadingEquipCharge: $scope.loadingEquipCharge,
                TotalForloadLabour: $scope.TotalForloadLabour,
                TotalForloadEquip: $scope.TotalForloadEquip,

                //Entrance Fee
                //Foreign -----
                entrance_fee_local: $scope.entrance_fee_local,
                entrance_fee_foreign: $scope.entrance_fee_foreign,
                entrance_fee_van: $scope.entrance_fee_van,
                entranceFeeChangeFlag : $scope.entranceFeeChangeFlag,

                entranceTotalForeignTruck: $scope.totalForeignTruck,
                entranceTotalLocalTruck: $scope.totalLocalTruck,
                entranceTotalLocalVan: $scope.totalLocalVan,

                totalForeignTruckEntranceFee: $scope.totalForeignTruckEntranceFee,
                totalLocalTruckEntranceFee: $scope.totalLocalTruckEntranceFee,
                totalLocalVanEntranceFee: $scope.totalLocalVanEntranceFee,


                //carpenter Charge
                carpenterChargesOpenClose: $scope.carpenterChargesOpenClose,
                carpenterChargesRepair: $scope.carpenterChargesRepair,
                carpenterPackages: $scope.carpenterPackages,
                carpenterRepairPackages: $scope.carpenterRepairPackages,

                totalcarpenterChargesOpenClose: $scope.totalcarpenterChargesOpenClose,
                totalcarpenterChargesRepair: $scope.totalcarpenterChargesRepair,

                //===Holiday Charge
                foreign_holiday_charge: $scope.foreign_holiday_charge,
                local_holiday_charge: $scope.foreign_holiday_charge,

                holidayTotalForeignTruck: $scope.holidayTotalForeignTruck,
                holidayTotalLocalTruck: $scope.holidayTotalLocalTruck,

                TotalForeignHolidayCharge: $scope.TotalForeignHolidayCharge,
                TotalLocalHolidayCharge: $scope.TotalLocalHolidayCharge,

                //Night Charge
                nightTotalForeignTruck: $scope.nightTotalForeignTruck,
                //  nightTotalLocalTruck: $scope.nightTotalLocalTruck,
                rate_of_night_charge: $scope.rate_of_night_charge,
                TotalForeignNightCharge: $scope.TotalForeignNightCharge,
                // TotalLocalNightCharge: $scope.TotalLocalNightCharge,

                //Haltage charge====
                foreign_haltage_charge: $scope.foreign_haltage_charge,
                local_haltage_charge: $scope.local_haltage_charge,

                haltagesTotalForeignTruck: $scope.haltagesTotalForeignTruck,
                haltagesTotalLocalTruck: $scope.haltagesTotalLocalTruck,
                TotalHaltageForeignCharge: $scope.TotalHaltageForeignCharge,

                TotalHaltageLocalCharge: $scope.TotalHaltageLocalCharge,
                haltagesTotalDayLocalTruck: $scope.haltagesTotalDayLocalTruck,
                haltagesTotalDayForeignTruck: $scope.haltagesTotalDayForeignTruck,

                //documentCharges
                totalDocumentCharges: $scope.totalDocumentCharges,
                numberOfDocuments: $scope.numberOfDocuments,
                documentCharges: $scope.documentCharges,
                //Weigment Charge
                weightment_measurement_charges: $scope.weightment_measurement_charges,
                weightmentChargesForeign: $scope.weightmentChargesForeign,
                totalForeignTruck : $scope.totalForeignTruckForWeighment,
                local_truck_weighment: $scope.local_truck_weighment,
                weightmentChargesLocal: $scope.weightmentChargesLocal,
                customomizedHandlingCharge: $scope.customomizedHandlingCharge

                

                /*item_wise_charge:$scope.item_wise_charge,

                 itmType:$scope.item_type,

                 slabDayOne:$scope.firstSlabDay,
                 slabDayTwo:$scope.secondSlabDay,
                 slabDayThree:$scope.thirdSlabDay,

                 slabDayThree:$scope.thirdSlabDay,
                 slabDayThree:$scope.thirdSlabDay,
                 slabDayThree:$scope.thirdSlabDay,*/

            }

            console.log(data)
            // return;

            $http.post("/assessment/api/save-assesment-data", data)

                .then(function (data) {
                    console.log(data);

                    $scope.insertSuccessMsg = true;
                    $("#saveSuccess").show().delay(3000).slideUp(4000);


                }).catch(function (r) {
                    console.log(r);
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else if(r.status == 403) {
                        $scope.assSaveError = true;
                        $scope.assSaveErrorMsgTxt = r.data.errorText;
                        $("#assSaveError").show().delay(5000).slideUp(4000);
                    } else {
                        $scope.assSaveError = true;
                        $scope.assSaveErrorMsgTxt = 'Something Went Wrong.';
                        $("#assSaveError").show().delay(5000).slideUp(4000);
                    }


                    console.log($scope.assSaveError)

                }).finally(function () {

                    $scope.savingData = false;
                })


        }


        var blank = function () {

            //manifest details
            $scope.tariff_year = null;
            $scope.listOfWarning = [];
            $scope.Mani_date = null;
            $scope.Bill_No = null;
            $scope.Bill_date = null;
            $scope.Custome_release_No = null;
            $scope.Custome_release_Date = null;
            $scope.Consignee = null;
            $scope.consignee_vat_flag = null;
            $scope.Consignor = null;
            $scope.package_no = null;
            $scope.package_type = null;
            $scope.totalItems = null;
            $scope.description_of_goods = null;
            $scope.bassisOfCharge = null;
            $scope.chargeable_weight = 0;
            $scope.self_flag=null;
            $scope.chargeable_weight = 0;
            $scope.gross_weight = 0;
            $scope.weighbridge_weight = 0;
            $scope.receive_flag = 0;


            $scope.CnF_Agent = null;
            $scope.posted_yard_shed = null;
            $scope.shed_or_yard = null;

//not fpound error
            $scope.MNotFound = false;
            $scope.AssessmentFound = false;
            $scope.previouAssValue = false;

//Global Variable

            $scope.TotalAmount = 0;
            $scope.Manifest_id = 0;
            $scope.chargableTonForWarehouse = 0;

//warehouse======================
            $scope.WarehouseReceiveWeight = 0;
            $scope.WareHouseRentDay = 0;
            $scope.TotalWarehouseCharge = 0
            $scope.receive_date = null;

            $scope.item_wise_shed_details = null;
            $scope.item_wise_yard_details = null;

//Handling Charge============
            $scope.handling = null;
            //--offload-------------
            $scope.OffloadLabour = 0
            $scope.OffLoadingEquip = 0
            $scope.OffloadLabourCharge = 0
            $scope.OffLoadingEquipCharge = 0
            $scope.TotalForOffloadLabour = 0
            $scope.TotalForOffloadEquip = 0
            $scope.unloadingShifting = false;

            //---load-----------
            $scope.loadLabour = 0
            $scope.loadingEquip = 0
            $scope.loadLabourCharge = 0
            $scope.loadingEquipCharge = 0
            $scope.TotalForloadLabour = 0
            $scope.TotalForloadEquip = 0
            $scope.loadShifting = false;

            //Hading Modal
            $scope.OffloadLabourModal = 0;
            $scope.OffloadLabourChargeModal = 0;
            $scope.TotalForOffloadLabourModal = 0;
            $scope.OffLoadingEquipModal = 0;
            $scope.OffLoadingEquipChargeModal = 0;
            $scope.unloadingShiftingModal = false;
            $scope.TotalForOffloadEquipModal = 0;
            $scope.totalUnloadCharge = 0;
            $scope.totalloadCharge = 0;

            $scope.loadLabourModal = 0;
            $scope.loadLabourChargeModal = 0;
            $scope.TotalForloadLabourModal = 0;
            $scope.loadingEquipModal = 0;
            $scope.loadingEquipChargeModal = 0;
            $scope.loadShiftingModal = false;
            $scope.TotalForloadEquipModal = 0;

            $scope.totalUnloadChargeOld = 0;
            $scope.totalUnloadChargeNew = 0;
            $scope.totalLoadChargeOld = 0;
            $scope.totalLoadChargeNew = 0;
            $scope.customomizedHandlingCharge = 0;

//Entrance Fee
            $scope.totalForeignTruck = 0;
            $scope.entranceFee = 0;
            $scope.totalForeignTruckEntranceFee = 0;
            $scope.previousTotalForeignTruck = 0;
            $scope.totalLocalTruckEntranceFee = 0;
            $scope.totalLocalVanEntranceFee = 0;
            $scope.totalLocalTruck = 0;
            $scope.totalLocalVan = 0;
            $scope.entrance_fee_truck = 0;
            $scope.entrance_fee_van = 0;


//Carpenter charge===================
            $scope.carpenterChargesOpenClose = 0;
            $scope.carpenterChargesRepair = 0;
            $scope.carpenterPackages = 0;
            $scope.carpenterRepairPackages = 0;

            $scope.totalcarpenterChargesOpenClose = 0;
            $scope.totalcarpenterChargesRepair = 0;
//Holiday' charge==========

            $scope.holidayTotalForeignTruck = 0;
            $scope.holidayTotalLocalTruck = 0;


            $scope.TotalForeignHolidayCharge = 0;
            $scope.TotalLocalHolidayCharge = 0;

            //Night' charge==========
            $scope.nightTotalForeignTruck = 0
            //$scope.nightTotalLocalTruck = 0


            $scope.TotalForeignNightCharge = 0;
            //$scope.TotalLocalNightCharge = 0;
            //$scope.Night_charges=0;

            //haltage charge========
            $scope.haltagesForeignScaleWeight = 0;
            $scope.haltagesForeignReceiveWeight = 0;
            $scope.foreign_haltage_charge = 0;
            $scope.local_haltage_charge = 0;
            $scope.haltagesForeignTruck = null;
            $scope.haltagesTotalForeignTruck = 0;
            $scope.haltagesTotalLocalTruck = 0;
            $scope.HaltageCharge = 0;
            $scope.TotalHaltageForeignCharge = 0;
            $scope.TotalHaltageLocalCharge = 0;
            $scope.haltagesTotalDayLocalTruck = 0;
            $scope.haltagesTotalDayForeignTruck = 0;
            $scope.local_truck_weighment = 0;
            //Weigment Charge
            $scope.weightment_measurement_charges = 0;
            $scope.weightmentChargesForeign = 0;
            $scope.weightmentChargesLocal = 0;

            //  document Charges
            $scope.documentCharges = 0;
            $scope.numberOfDocuments = 0;
            $scope.TotalAmount = 0;
            $scope.grand_total = 0;

            $scope.truckToTruckFlag = 0;
            $scope.entranceFeeChangeFlag = 0;
        };


//multi items selection option==================================================ITEM==========================

        $scope.item_type = '1'; //set a value initialy


        $scope.loadItems = function ($query) {
            // An arrays of strings here will also be converted into an
            // array of objects

            // console.log($query)
            return $http.get('/assessment/api/get-items-list-data/' + $query)
                .then(function (response) {
                    console.log(response)
                    var item_names = response.data;
                    return item_names.filter(function (v) {
                        return v.Description
                        //return v.cargo_name.toLowerCase().indexOf($query.toLowerCase()) != -1;
                    });
                }).catch(function (r) {

                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }

                }).finally(function () {


                });

        };

        $scope.shedYardShow = false;
        $scope.getSelectItemsShow = function (m_id) {
            $scope.goods_charge_div = false;
            $scope.allItemsData = null;
            $scope.countItemList = 0;
            $http.get("/assessment/api/get-all-items-data/" + m_id)

                .then(function (data) {
                    console.log(data.data)

                    if (data.status == 203) {
                        var i = data.data[0];
                        console.log(i.chargeable_weight)
                        var item_araay = new Array();
                        item_araay.id = i.id; //Not Found When Item Inserted
                        item_araay.Description = i.cargo_name; //Not Found When Item Inserted
                        item_object = [];
                        item_object.push(item_araay);

                        //$scope.item_quantity = i.chargeable_weight ? parseFloat(i.chargeable_weight):parseFloat(i.gweight);
                        //$scope.item_type = '4';

                        $scope.item_search_id = item_object;
                        return;
                    }

                    $scope.saveSuccessItems = false;
                    $scope.updateSuccess = false;
                    $scope.allItemsData = data.data;
                    $scope.countItemList = data.data.length;
                    //console.log($scope.countItemList);

                    //  $scope.updateBtnItems = false;

                    console.log($scope.allItemsData)

                }).catch(function (r) {

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {

            })
            $http.get("/assessment/api/get-cargo-details-data/", {params: {manifest_no:$scope.ManifestNo}}) //get Goods for dropdown list
                .then(function (data) {
                    console.log('Tariff Goods:');
                    console.log(data.data);
                    $scope.tariff_goods_details = data.data;
                }).catch(function (r) {

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {


            });

            $http.get("/assessment/api/get-manifest-all-weights/"+ m_id)
                .then(function(data) {
                    console.log(data.data);
                $scope.grossWeight = data.data[0].gweight;
                $scope.netWeight = data.data[0].net_weight;
                $scope.shedWeight = data.data[0].shed_weight != 0 ? data.data[0].shed_weight : null;
                $scope.yardWeight = data.data[0].yard_weight != 0 ? data.data[0].yard_weight : null;

                //console.log($scope.countItemList);
                if ($scope.countItemList == 0) {
                    $scope.shedYardShow = false;
                    if ($scope.shedWeight != null) {
                        $scope.item_quantity = parseFloat($scope.shedWeight);
                        $scope.yard_shed = '1';
                        $scope.item_type = '4';
                    } else if($scope.yardWeight != null) {
                        $scope.item_quantity = parseFloat($scope.yardWeight);
                        $scope.yard_shed = '0';
                        $scope.item_type = '4';
                    } else if($scope.self_flag == 1) {
                        $scope.shedYardShow = false;
                        $scope.yard_shed = '0';
                        $scope.item_type = '4'
                    } else {
                        $scope.yard_shed = '1';
                        $scope.item_type = '4'
                    }
                } else if ($scope.countItemList == 1) {
                    $scope.shedYardShow = false;
                    if ($scope.yardWeight != null) {
                        $scope.item_quantity = parseFloat($scope.yardWeight);
                        $scope.yard_shed = '0';
                        $scope.item_type = '4';
                        console.log($scope.allItemsData);
                        // var item_araay = new Array();
                        // item_araay.id = $scope.allItemsData[0].item_Code_id;
                        // item_araay.Description = $scope.allItemsData[0].Description;
                        // item_object = [];
                        // item_object.push(item_araay);
                        // $scope.item_search_id = item_object;
                        // $scope.item_Code_id = $scope.allItemsData[0].item_Code_id;//14
                        // $scope.goods_id = $scope.allItemsData[0].goods_id;

                    } else if($scope.self_flag == 1) {
                        $scope.shedYardShow = false;
                        $scope.yard_shed = '0';
                        $scope.item_type = '4'
                    }  else {
                        $scope.yard_shed = '1';
                        $scope.item_type = '4';
                    }
                } else if ($scope.countItemList == 2) {
                    $scope.shedYardShow = true;
                    if($scope.self_flag == 1) {
                        $scope.shedYardShow = false;
                        $scope.yard_shed = '0';
                        $scope.item_type = '4'
                    }
                } else {
                    $scope.shedYardShow = true;
                    if($scope.shedWeight != null) {
                        $scope.yard_shed = '1';
                        $scope.item_type = '4';
                    } else if($scope.yardWeight != null) {
                       $scope.yard_shed = '0';
                       $scope.item_type = '4';
                    } else if($scope.self_flag == 1) {
                        $scope.shedYardShow = false;
                        $scope.yard_shed = '0';
                        $scope.item_type = '4';
                    } else {
                       $scope.yard_shed = '1';
                    }
                    $scope.item_quantity = null;
                    
                }
            }).catch(function (r) {

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {


            });
        };


        //   closeGoodChargeModal
        $scope.getGoodsId = function (id) {
            $("#GoodsSecondModal").modal('hide');
            $scope.tariff_good_id = id;
            $scope.getGoodsCharge(id, $scope.yard_shed)

        }


        $scope.selectItemsShow = function () {//when modal button click for input multiope goods
            if($scope.receive_flag == 0) {
                $.growl.warning({message: "Manifest is not received at Shed/Yard."});
                //return 0;
            }

            $scope.getSelectItemsShow($scope.Manifest_id);

            $scope.updateSuccess = false;
            $scope.yard_shed = '1';
            $scope.multiItemFormSubmit = false;

            console.log($scope.yard_shed);

            $scope.updateBtnItems = false;
            itemBlank();
        }
        $scope.goods_charge_div = false;

        $scope.getGoodsCharge = function (tariff_good_id) {//show charge for goods when selecting item
            console.log('ok');
            var shed_yard = $scope.yard_shed;
            $scope.goods_charge_div = true;
            //console.log(tariff_good_id);
            if (shed_yard == 0) {//0 means yard
                console.log(shed_yard)
                angular.forEach($scope.tariff_goods_details, function (v, k) {
                    if (v.tariff_id == tariff_good_id) {
                        $scope.tariff_good = v;
                        $scope.charge = v.yard_charge;
                    }
                })
            }
            if (shed_yard == 1) {//1 means shed
                console.log(shed_yard)
                angular.forEach($scope.tariff_goods_details, function (v, k) {
                    if (v.tariff_id == tariff_good_id) {
                        $scope.tariff_good = v;
                        $scope.charge = v.shed_charge;
                    }
                })
            }
            //console.log($scope.tariff_good);
            //console.log($scope.charge);
            if (tariff_good_id == null) $scope.goods_charge_div = false;
        }


        $scope.addItems = function (form) {
            if($scope.receive_flag == 0) {
                $.growl.warning({message: "Manifest is not received at Shed/Yard."});
                $scope.itemErrorMsg = true;
                $scope.itemErrorMsgTxt = "Manifest is not received at Shed/Yard.";
                $("#itemError").show().fadeTo(1500, 500).slideUp(1500, function () {
                    $("#itemError").slideUp(1000);
                });
                return 0;
            }
            if (form.$valid) {//if addItems form is valid

                if ($scope.Manifest_id == 0 || $scope.Manifest_id == null) {//if want to add item without serachig manifest
                    $scope.itemErrorMsg = true;
                    $scope.itemErrorMsgTxt = "Please Search Manifest First!";
                    $("#itemError").show().fadeTo(1500, 500).slideUp(1500, function () {
                        $("#itemError").slideUp(1000);
                    });
                    return;
                }

                $scope.new_item = '';
                $scope.item_Code_id = null;
                angular.forEach($scope.item_search_id, function (v, k) {
                    if (v.Code == undefined) {

                        $scope.new_item = $filter('capitalize')(v.Description);

                    }
                    else {
                        $scope.item_Code_id = v.id;
                    }
                })

                console.log(checkDuplicate())
                // if (checkDuplicate() == true) {
                //     $scope.itemErrorMsg = true;
                //     $scope.itemErrorMsgTxt = "Can't add an item twice!"
                //     $("#itemError").show().fadeTo(1500, 500).slideUp(1500, function () {
                //         $("#itemError").slideUp(1000);
                //     });
                //     return;
                // }
                // if($scope.countItemList == 2) {
                //     $scope.itemErrorMsg = true;
                //     $scope.itemErrorMsgTxt = "Can't add more than two item!"
                //     $("#itemError").show().fadeTo(1500, 500).slideUp(1500, function () {
                //         $("#itemError").slideUp(1000);
                //     });
                //     return;
                // }

                var data = {
                    item_Code_id: $scope.item_Code_id,
                    new_item: $scope.new_item,
                    yard_shed: $scope.yard_shed,
                    item_type: $scope.item_type,
                    item_quantity: $scope.item_quantity,
                    manf_id: $scope.Manifest_id,
                    tariff_good_id: $scope.tariff_good_id,
                    dangerous: $scope.dangerous
                }

                console.log(data)

//return
                $scope.savingMultiItem = true;
                console.log($scope.Manifest_id)
                $http.post("/assessment/api/save-items-data", data)
                    .then(function (data) {
                        console.log(data)
                        if(data.status == 207) {
                            $scope.itemErrorMsg = true;
                            $scope.itemErrorMsgTxt = data.data.receive_error;
                            $('#itemError').show().delay(5000).slideUp(1000);
                            return;
                        }
                        $scope.itemSuccessMsg = true;
                        $scope.itemSuccessMsgTxt = 'Saved ';
                        $("#itemSuccess").show().fadeTo(1500, 500).slideUp(500, function () {
                            $("#itemSuccess").slideUp(1000);
                        });
                        $scope.getSelectItemsShow($scope.Manifest_id);
                        itemBlank();

                    }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                    $scope.itemErrorMsg = true;
                    $scope.itemErrorMsgTxt = 'Something went wrong';
                    $('#itemError').show().delay(5000).slselectItemsShowideUp(1000);
                }).finally(function () {
                    $scope.savingMultiItem = false;
                })
            }
            else {
                $scope.multiItemFormSubmit = true;
                return;
            }

        }


        $scope.ediItem = function (i) {
            if($scope.receive_flag == 0) {
                $.growl.warning({message: "Manifest is not received at Shed/Yard."});
                $scope.itemErrorMsg = true;
                $scope.itemErrorMsgTxt = "Manifest is not received at Shed/Yard.";
                $("#itemError").show().fadeTo(1500, 500).slideUp(1500, function () {
                    $("#itemError").slideUp(1000);
                });
                return 0;
            }
            console.log(i)

            var item_araay = new Array();
            item_araay.id = i.item_Code_id;
            item_araay.Description = i.Description;
            item_object = [];
            item_object.push(item_araay);

            $scope.item_search_id = item_object;
            $scope.item_Code_id = i.item_Code_id;//14
            $scope.cache_item_Code_id = i.item_Code_id;//14

            $scope.yard_shed = i.yard_shed.toString();
            $scope.dangerous = i.dangerous.toString();
            $scope.item_type = i.item_type;
            $scope.item_quantity = parseInt(i.item_quantity);
            $scope.it_id = i.it_id;//sl
            $scope.tariff_good_id = i.tariff_good_id;


            $scope.saveSuccessItems = false;
            $scope.updateBtnItems = true;

        }

        $scope.updateitems = function (form) {
            if (form.$valid) {

                $scope.new_item = '';
                console.log($scope.item_search_id)
                angular.forEach($scope.item_search_id, function (v, k) {
                    if (v.id == undefined) {
                        $scope.new_item = v.Description
                        $scope.cache_item_Code_id = null;
                        $scope.item_Code_id = null;

                    }
                    else {
                        $scope.item_Code_id = v.id;
                    }
                })

                console.log($scope.new_item)
                console.log($scope.cache_item_Code_id)
                console.log($scope.item_Code_id)


                // if ($scope.item_Code_id != $scope.cache_item_Code_id) {

                //     if (checkDuplicate() == true) {
                //         $scope.itemErrorMsg = true;
                //         $scope.itemErrorMsgTxt = "Can't add an item twice!"
                //         $("#itemError").show().fadeTo(1500, 500).slideUp(1500, function () {
                //             $("#itemError").slideUp(1000);
                //         });
                //         return;
                //     }
                // }

                var data = {

                    item_Code_id: $scope.item_Code_id,
                    new_item: $scope.new_item,
                    yard_shed: $scope.yard_shed,
                    item_type: $scope.item_type,
                    item_quantity: $scope.item_quantity,
                    manf_id: $scope.Manifest_id,
                    tariff_good_id: $scope.tariff_good_id,
                    it_id: $scope.it_id,
                    dangerous: $scope.dangerous
                };
                console.log(data);
                $scope.savingMultiItem = true;
                $http.put("/assessment/api/update-items-information", data)
                    .then(function (data) {
                        if(data.status == 207) {
                            $scope.itemErrorMsg = true;
                            $scope.itemErrorMsgTxt = data.data.receive_error;
                            $('#itemError').show().delay(5000).slideUp(1000);
                            return;
                        }
                        $scope.itemSuccessMsg = true;
                        $scope.itemSuccessMsgTxt = 'Updated';
                        $("#itemSuccess").show().fadeTo(1500, 500).slideUp(500, function () {
                            $("#itemSuccess").slideUp(1000);
                        });
                        $scope.getSelectItemsShow($scope.Manifest_id);
                        $scope.updateBtnItems = false;
                        itemBlank();
                    }).catch(function (r) {

                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.itemErrorMsg = true;
                        $scope.itemErrorMsgTxt = 'Something went wrong';
                        $('#itemError').show().delay(5000).slideUp(1000);

                }).finally(function () {
                    $scope.savingMultiItem = false;
                })
            }
            else {
                $scope.multiItemFormSubmit = true;
                return;

            }
        }

        $scope.deleteItems = function (i) {
            //bd_truck_id m_id
            console.log(i.it_id)
            $scope.updateSuccess = false;
            var data = {
                item_details_id: i.it_id,
                item_Code_id: $scope.item_Code_id

            }
            $http.post("/assessment/api/delete-items", data)
                .then(function (data) {
                    $scope.itemSuccessMsg = true;
                    $scope.itemSuccessMsgTxt = 'Deleted';

                    $scope.insertSuccessMsg = true;
                    $("#itemSuccess").show().fadeTo(1500, 500).slideUp(500, function () {
                        $("#itemSuccess").slideUp(1000);
                    });
                    $scope.getSelectItemsShow($scope.Manifest_id);
                    itemBlank();

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

        var checkDuplicate = function () {
            var duplicateItem = false;
            console.log($scope.item_Code_id)
            angular.forEach($scope.allItemsData, function (v, k) {
                console.log(v.item_Code_id)
                if (v.item_Code_id == $scope.item_Code_id) {
                    duplicateItem = true;
                    console.log(v.item_Code_id + ' duplicate')
                }
            });

            return duplicateItem;

        }

        var itemBlank = function () {
            $scope.item_Code_id = '';
            $scope.item_weight = '';
            $scope.item_package = '';
            $scope.it_id = '';
            $scope.multiItemFormSubmit = false;
            $scope.goods_id = '';
            $scope.item_quantity = '';
            $scope.item_search_id = null;
        }


        $scope.showTariffData = function (shed_yard) {

            $scope.tariff_shed_yard = shed_yard.toString();
            console.log($scope.tariff_shed_yard)
        }

        $scope.getPackageNumber = function(quantity) {
            if($scope.item_type == 3) {
                $scope.item_quantity = parseFloat($scope.package_no);
            } else {
                $scope.item_quantity =  quantity;
            }
        }

//END Multi items===============


        //============START Assessment change option===================

        // $scope.changeReceivedayOption = function (time) {

        //     console.log($('#receive_datetime').val());

        //     //return
        //     if (!$scope.Manifest_id) {
        //         $scope.changeReceivedayError = true;
        //         $("#changeReceivedayError").show().delay(2000).slideUp(1000);
        //         $scope.changeReceivedayErrorMsg = "Please Search by Manifest First!";

        //         return;
        //     }

        //     var data = {
        //         receive_date: $scope.receive_date,//$('#receive_datetime').val(),
        //         Manifest_id: $scope.Manifest_id
        //     }

        //     console.log(data)

        //     $http.post("/assessment/api/change-receive-day-option", data)
        //         .then(function (r) {

        //             console.log(r.status)
        //             setTimeout(function () {
        //                 $scope.manifestSearch($scope.searchText);
        //             }, 3000);


        //             $scope.changeReceivedaySucc = true;
        //             $("#changeReceivedaySucc").show().delay(2000).slideUp(1000);


        //         }).catch(function (r) {

        //         console.log(r)
        //         if (r.status == 401) {
        //             $.growl.error({message: r.data});
        //         } else {
        //             $.growl.error({message: "It has Some Error!"});
        //         }
        //         console.log('error')

        //     }).finally(function () {

        //     })


        // }

        //-------changeBassisOfCharge option

        $scope.changeBassisOfCharge = function (charge) {
            console.log(charge)
            if (!$scope.Manifest_id) {
                $scope.changeBassisOfChargeError = true;
                $("#changeBassisOfChargeError").show().delay(2000).slideUp(1000);
                $scope.changebassisOfChargeErrorMsgTxt = "Please Search by Manifest First!";
                return;
            }

            var data = {
                bassisOfCharge: charge,//$('#receive_datetime').val(),
                manifest_id: $scope.Manifest_id
            };

            console.log(data);

            $http.post("/assessment/api/change-bassis-of-charge-option", data)
                .then(function (r) {
                    console.log(r.status);
                    setTimeout(function () {
                        $scope.manifestSearch($scope.searchText);
                    }, 3000);
                    $scope.changeBassisOfChargeSuccMsg = true;
                    $("#changeBassisOfChargeSuccMsg").show().delay(2000).slideUp(1000);
                    $scope.bassisOfChargeSuccMsgTxt = 'Successfully Changed Manifest Gross Weight!';


                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                }
                $scope.changeBassisOfChargeError = true;
                $("#changeBassisOfChargeError").show().delay(2000).slideUp(1000);
                $scope.changebassisOfChargeErrorMsgTxt = "Soe";

            }).finally(function () {

            })
        }

        //Add Documentation Charges
        $scope.document_id = null;
        $scope.$watch('number_of_document', function () {
            if ($scope.documentFlag) {
                $scope.total_documentation_charge = parseFloat($scope.number_of_document) * parseFloat($scope.documentCharges);
                //console.log(parseFloat($scope.number_of_document));
                console.log(parseFloat($scope.documentCharges));   //1633
            }
        });
        $scope.documentFlag = false;
        $scope.DocumentShow = function () {
            $scope.DocumentDataLoading = true;
            $scope.documentFlag = true;
            console.log($scope.partial_status);
            $http.get('/assessment/api/get-previous-document-details/', {
                params: {
                    manifese_id: $scope.Manifest_id,
                    partial_status: $scope.partial_status
                }
            })
                .then(function (data) {
                    console.log(data.data);
                    if (data.data.length > 0) {
                        $scope.document_name = data.data[0].document_name;
                        $scope.number_of_document = parseFloat(data.data[0].number_of_document);
                        $scope.document_id = data.data[0].id;
                    }
                }).catch(function (r) {
                console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
            }).finally(function () {
                $scope.DocumentDataLoading = false;
            })
        }


        $scope.SaveDocumentetaionDetails = function () {
            console.log(parseFloat($scope.documentCharges));   //1663
            if ($scope.DocumentForm.$invalid) {
                $scope.submitDocument = true;
                return;
            } else {
                $scope.submitDocument = false;
            }
            $scope.DocumentDataLoading = true;
            var data = {
                document_id: $scope.document_id,
                manifest_id: $scope.Manifest_id,
                document_name: $scope.document_name,
                number_of_document: $scope.number_of_document,
                partial_status: $scope.partial_status,
                document_charge: $scope.documentCharges
            }
            $http.post('/assessment/api/save-document-data', data)
                .then(function (data) {
                    $scope.savingSuccessDocumentData = 'Successfullt saved.';
                    $('#savingSuccessDocumentData').show().delay(5000).slideUp(2000);
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingErrorDocumentData = 'Something went wrong.';
                $('#savingErrorDocumentData').show().delay(5000).slideUp(2000);
            }).finally(function () {
                $scope.DocumentDataLoading = false;
            })
        }

        //Warehouse Delivery Modal
        $scope.gate_pass = true;
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();

        if(dd<10) {
            dd = '0'+dd
        }

        if(mm<10) {
            mm = '0'+mm
        }

        var today =  yyyy+ '-' + mm + '-' + dd;

        console.log(today);
        $scope.warehouseDeliveryModal = function () {

            $scope.reportByManifestBtn = true;//enable reportbtn when serach by manifest

            $scope.manifestDataLoading = true;
            $scope.manifestDataLoadingError = false;
            $scope.posted_yard_shed = null;
            $scope.updateBtn = false;
            // $('#saveManifestDataBtn').html('Save');

            $scope.showManifestInfoDiv = false;//show a div for showing manifest no and importer name to be sure

            $scope.be_no = null
            $scope.be_date = null
            //$scope.paid_tax = null
            $scope.ain_no = null
            //$scope.paid_date = null
            $scope.bd_weighment = null;
            $scope.bd_haltage = null;
            $scope.shifting_flag = null;
            $scope.cnf_name = null
            $scope.carpenter_packages = null
            $scope.carpenter_repair_packages = null
            $scope.no_del_truck = null
            $scope.allData = null;
            $scope.gate_pass_no = null;

            $scope.custom_release_order_no = null;
            $scope.custom_release_order_date = null;
            $scope.approximate_delivery_date = null;
            $scope.approximate_delivery_type = "0";

            $scope.GetManiID = null
            $scope.GetManiNo = null;
            $scope.GetManiGWeight = null;


            $scope.BdTruckTotalLoad = 0;
            $scope.ManiNweight = 0;
            $scope.LocalTruckWeight = 0;
            $scope.BdTruckNoFull = null;

            $scope.permissionError = null;
            //  $scope.dRForm.$setUntouched();
            $scope.custom_approved_date = null;
            $scope.chassis_transport = false;

           // $scope.labourWeightMust = false;
           // $scope.equipmentWeightMust = false;

            $scope.approximate_equipment_load = null;
            $scope.approximate_equipment_load = null;
            $scope.cacheLocalTransportRequestedNumber = 0;
            $scope.localTransportLength = 0;
            //$scope.paid_date=null
            $scope.allData = null;
            $scope.saveSuccess = '';
            $scope.custom_approved_date = null;
            $scope.local_transport_type = '0';


            var data = {
                mani_no: $scope.searchText
            }

            $http.post("/warehouse/api/delivery/delivery-search-by-manifest-data", data)

                .then(function (data) {
                     console.log(data);
                    if (data.status == 203) {
                        $scope.permissionError = data.data.noPermission;
                        $('#permissionError').show().delay(5000).slideUp(1000);
                        return;
                    }

                    console.log(data.data)
                    //  $scope.Request();

                    if (data.data.length >= 1) {//manifest found
                            console.log('m found')
                        if($scope.role_name == 'WareHouse' || $scope.role_name == 'Assessment'){
                            $scope.approximate_delivery_date = today;
                            $scope.delivery_date = true;

                        }else if($scope.role_name == 'Assessment Admin' || $scope.role_name == 'Maintenance'){
                            $scope.approximate_delivery_date = today;
                            $scope.delivery_date = false;
                        }
                        $scope.showManifestInfoDiv = true;

                        $scope.GetManiID = data.data[0].m_id;
                        $scope.GetManiNo = data.data[0].manifest;
                        $scope.GetManiGWeight = data.data[0].m_gweight;
                        $scope.ManiNweight = data.data[0].m_nweight;
                        $scope.ImporterName = data.data[0].importer;
                        //$scope.gate_pass_no = data.data[0].gate_pass_no;
                     //  $scope.bd_weighment = data.data[0].bd_weighment;
                      // $scope.shifting_flag = data.data[0].m_shifting_flag ? data.data[0].m_shifting_flag.toString():'0';
                        console.log(data.data[0].m_shifting_flag);
                        console.log($scope.shifting_flag);

                        $scope.custom_release_order_no = data.data[0].custom_release_order_no;
                        $scope.custom_release_order_date = data.data[0].custom_release_order_date;
                      // $scope.approximate_delivery_date = data.data[0].approximate_delivery_date;
                        $scope.approximate_delivery_date = null;
                        var checkRequisitionExist = data.data[0].delivery_req_id;
                        $scope.check_del_id = data.data[0].delivery_req_id;
                        if(checkRequisitionExist == null){
                            $scope.getNetWeightForLoadingCharge=parseFloat(data.data[0].chargeable_weight ? data.data[0].chargeable_weight :$scope.GetManiGWeight);
                        }

                       $scope.approximate_delivery_type = data.data[0].approximate_delivery_type != null ? data.data[0].approximate_delivery_type.toString() : "0";

                      // $scope.approximate_labour_load =parseFloat(data.data[0].approximate_labour_load);
                      // $scope.approximate_equipment_load = parseFloat(data.data[0].approximate_equipment_load);


                        $scope.reportByManifestBtn = false;//enable reportbtn when serach by manifest
                        $scope.searchKeyManifestNo = $scope.searchText;
                        $scope.custom_approved_date = data.data[0].custom_approved_date;
                      // $scope.local_transport_type = data.data[0].local_transport_type != null ? data.data[0].local_transport_type.toString():"0";
                      //  $scope.changeApprxTransportFlag($scope.local_transport_type);
                      // $scope.transport_truck = parseFloat(data.data[0].transport_truck);
                     //  $scope.transport_van = parseFloat(data.data[0].transport_van);
                        $scope.receive_weight = data.data[0].receive_weight;
                        $scope.weigh_bridge_net_weight = data.data[0].weigh_bridge_net_weight;
                        console.log($scope.weigh_bridge_net_weight);

                        console.log($scope.custom_approved_date);
                        console.log(data.data[0].be_no)

                        if (checkRequisitionExist == null) {//bill of entry not done

                            $scope.be_no = null;
                            $scope.gate_pass_no = null;
                            $scope.be_date = null;
                            $scope.paid_tax = null
                            $scope.ain_no = null;
                            $scope.bd_weighment = null;
                            $scope.bd_haltage = null;
                            $scope.shifting_flag = null;
                            //$scope.paid_date = null
                            $scope.cnf_name = null;
                            $scope.no_del_truck = null;
                          //  $scope.allData = data.data;
                            console.log(data.data);
                            console.log( $scope.allData);
                            $scope.shifting_flag = data.data[0].m_shifting_flag ? data.data[0].m_shifting_flag.toString():'0';
                            $scope.posted_yard_shed = data.data[0].posted_yard_shed;
                            console.log('asds');
                            //  $scope.GetManiID = null;
                            $scope.custom_release_order_no = null;
                            $scope.custom_release_order_date = null;

                            $scope.custom_approved_date = null;

                            $scope.local_transport_type = "0";
                            $scope.transport_truck = null;
                            $scope.transport_van = null;
                            $scope.changeApprxTransportFlag($scope.local_transport_type);

                            $scope.changeAapproximateDeliveryType($scope.approximate_delivery_type);
                            if($scope.role_name == 'WareHouse' || $scope.role_name == 'Assessment'){
                                $scope.approximate_delivery_date = today;
                                $scope.delivery_date = true;

                            }else if($scope.role_name == 'Assessment Admin' || $scope.role_name == 'Maintenance'){
                                $scope.approximate_delivery_date = today;
                                $scope.delivery_date = false;
                            }
                            //  $scope.Request();
                            var t = data.data[0];
                            $scope.idSelectedRow = t.t_id;
                            console.log(t);
                            $scope.GetManiID = t.m_id
                            //  console.log($scope.GetManiID);
                            $scope.GetManiNo = t.manifest;
                            $scope.ImporterName = t.importer;
                            $scope.ManiNweight = t.m_nweight;
                            $scope.receive_weight = t.receive_weight;
                            $scope.weigh_bridge_net_weight = t.weigh_bridge_net_weight;
                            console.log($scope.weigh_bridge_net_weight);
                            //it's taken from add request

                        } else { //Bill E completed then  in edit mode


                            // $('#saveManifestDataBtn').html('Update Request');

                            if($scope.role_name == 'WareHouse' || $scope.role_name == 'Assessment'){
                                $scope.approximate_delivery_date = today;
                                $scope.delivery_date = true;

                            }else if($scope.role_name == 'Assessment Admin' || $scope.role_name == 'Maintenance'){
                                $scope.approximate_delivery_date = today;
                                $scope.delivery_date = false;
                            }else {
                                $scope.approximate_delivery_date = null;
                            }
                            $scope.allData = data.data;
                            console.log($scope.allData);
                            $scope.delivery_req_id = data.data[0].delivery_req_id;
                            console.log($scope.delivery_req_id);

                            console.log('update');
                            console.log(data.data[0]);

                            $scope.be_no = data.data[0].be_no;
                            $scope.be_date = data.data[0].be_date;
                            //$scope.paid_tax = data.data[0].paid_tax;
                            $scope.ain_no = data.data[0].ain_no;
                            //  $scope.ain_no_only = data.data[0].ain_no;
                            //$scope.paid_date = data.data[0].paid_date;
                            $scope.cnf_name = data.data[0].cnf_name;
                             // $scope.bd_weighment = data.data[0].bd_weighment;
                         //  $scope.shifting_flag = data.data[0].m_shifting_flag ? data.data[0].m_shifting_flag.toString():'0';
                             $scope.shifting_flag = '0';
                            $scope.no_del_truck = data.data[0].no_del_truck;
                           //  $scope.carpenter_packages = parseFloat(data.data[0].carpenter_packages);
                           // $scope.carpenter_repair_packages = parseFloat(data.data[0].carpenter_repair_packages);
                           //  $scope.gate_pass_no = data.data[0].gate_pass_no;

                            $scope.custom_release_order_no = data.data[0].custom_release_order_no;
                            $scope.custom_release_order_date = data.data[0].custom_release_order_date;

                           // $scope.approximate_delivery_date = data.data[0].approximate_delivery_date;

                            $scope.local_transport_type = "0";
                            $scope.transport_truck = null;
                            $scope.transport_van = null;
                            $scope.changeApprxTransportFlag($scope.local_transport_type);
                            $scope.approximate_delivery_type = "4";
                            $scope.changeAapproximateDeliveryType($scope.approximate_delivery_type);
                           // $scope.approximate_delivery_type = data.data[0].approximate_delivery_type != null ? data.data[0].approximate_delivery_type.toString() : "0";
                           //  console.log($scope.labourWeightMust)
                           //  if ($scope.approximate_delivery_type == "0") {//labour
                           //      $scope.labourWeightMust = true;
                           //      $scope.equipmentWeightMust = false;
                           //  }else if($scope.approximate_delivery_type=='1'){//equ
                           //      $scope.labourWeightMust = false;
                           //      $scope.equipmentWeightMust = true;
                           //  }else if($scope.approximate_delivery_type=='2') {//both
                           //      $scope.labourWeightMust = true;
                           //      $scope.equipmentWeightMust = true;
                           //  }
                           //
                           //  console.log($scope.labourWeightMust)
                           //  console.log($scope.equipmentWeightMust)

                            $scope.posted_yard_shed = data.data[0].posted_yard_shed;
                            $scope.cacheLocalTransportRequestedNumber = $scope.no_del_truck;
                            $scope.custom_approved_date = data.data[0].custom_approved_date;
                          // $scope.local_transport_type = data.data[0].local_transport_type != null ? data.data[0].local_transport_type.toString():"0";

                           // $scope.changeApprxTransportFlag($scope.local_transport_type);
                           // $scope.transport_truck = parseFloat(data.data[0].transport_truck);
                           // $scope.transport_van = parseFloat(data.data[0].transport_van);
                            $scope.receive_weight = data.data[0].receive_weight;
                            $scope.weigh_bridge_net_weight = data.data[0].weigh_bridge_net_weight;
                            console.log( $scope.weigh_bridge_net_weight);
                            // if ($scope.local_transport_type == "2") {//self
                            //     $scope.chassis_transport = true;
                            // }
                            console.log($scope.transportTruckMust);
                            //$scope.dRForm.transport_truck.$setValidity('required', false);

                        }
                    } else {//manifest not found


                        $scope.searchTextNotFoundTxt = 'Manifest No: ' + $scope.searchText
                        $scope.manifestDataLoadingError = true;
                        // $scope.gate_pass_no = data.data[0].gate_pass_no;


                    }
                    if($scope.role_name == 'WareHouse' || $scope.role_name == 'Assessment'){
                        $scope.approximate_delivery_date = today;
                        $scope.delivery_date = true;

                    }else if($scope.role_name == 'Assessment Admin' || $scope.role_name == 'Maintenance'){
                        $scope.approximate_delivery_date = today;
                        $scope.delivery_date = false;
                    }
                    console.log($scope.custom_approved_date);

                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                    console.log('cache')
                    $scope.manifestDataLoadingError = true;



            }).finally(function () {
                // console.log('in finally');
                $scope.manifestDataLoading = false;

            })
        };

        $('#m_Importer_Name').autocomplete({
            source: "/warehouse/api/delivery/ain-no-cnf-name-data",
            minLength: 3,
            // autoFocus:true,
            // displayKey: 'Importer_Name',
            select: function (event, ui) {
                // $scope.$watch('m_Importer_Name', function (val) {
                //  $("#m_Importer_Name").val(ui.item.id)
                // }, true);
                $("#m_Importer_Name_display").val(ui.item.impoeter_name);
                // $("#Importer_Name").val(ui.item.id);

                // #display_id
                $('#m_Importer_Name').val();
                $("#only_ain_no").val(ui.item.id);
                // $("#Importer_Name").val(ui.item.id);


                // $scope.padfd = $("#m_Importer_Name").val(ui.item.id)
                //  console.log($scope.padfd);
                // $("#m_Vat_importer_NO").val(ui.item.impoeter_name);
                //console.log($("#m_Importer_Name").val(ui.item.id))
                // console.log($("#m_Importer_Name").val());
                // console.log("selected id: ",ui.item.id)
                $scope.cnf_name = ui.item.cnf_name;

                $scope.ain_no = ui.item.ain_no;
                // console.log(ui.item);
                $scope.vatId_importer_name = ui.item.id;
                // console.log( $scope.vatId_importer_name);
                // $scope.Importer_Name = ui.item.id;
                if ($scope.vatId_importer_name != null) {
                    // $scope.imp_name_from_Importer=true;
                    // $scope.vat_no_after_Vat = false;
                }
            }
        });

        console.log($scope.labourWeightMust)

    $scope.changeApprxTransportFlag = function (flag) {
        console.log(flag);
        if (flag == 0) {
            $scope.chassis_transport = false;
            $scope.transportVanMust = true;
            $scope.transportTruckMust = false;
            $scope.transport_van = null;
        } else if (flag == 1) {
            $scope.chassis_transport = false;
            $scope.transportVanMust = false;
            $scope.transportTruckMust = true;
            $scope.transport_truck = null;
        } else if(flag == 3) {
            $scope.chassis_transport = false;
            $scope.transportVanMust = false;
            $scope.transportTruckMust = false;
        }else {
            $scope.ChassisInformationForm = true;
            $scope.LocalTransportTruckForm = false;
            $scope.no_del_truck = null;
            $scope.chassis_transport = true;
            //  $scope.changeAapproximateDeliveryType('3')
            $scope.approximate_delivery_type='3';
            $scope.transportVanMust = true;
            $scope.transportTruckMust = true;
            $scope.transport_truck = null;
            $scope.transport_van = null;
        }
    }

    $scope.changeAapproximateDeliveryType = function (value) {//0->labour;1->equip;2->both; 3->self
        console.log(value);
        console.log($scope.getNetWeightForLoadingCharge);
        // console.log(checkRequisitionExist);

        if(value==0){//labout

                $scope.labourWeightMust = true;
                $scope.equipmentWeightMust = false;
                $scope.approximate_labour_load=$scope.getNetWeightForLoadingCharge;
                $scope.approximate_equipment_load = null;

        }else if (value==1){//equipment
            $scope.equipmentWeightMust = true;
            $scope.labourWeightMust = false;
            $scope.approximate_labour_load = null;
            $scope.approximate_equipment_load=$scope.getNetWeightForLoadingCharge;
        }else if(value==2){//both
            $scope.labourWeightMust = true;
            $scope.equipmentWeightMust = true;
            $scope.approximate_labour_load = $scope.getNetWeightForLoadingCharge/2;
            $scope.approximate_equipment_load=$scope.getNetWeightForLoadingCharge/2;

        }else if(value == 3){//value 3->self
            $scope.labourWeightMust = false;
            $scope.equipmentWeightMust = false;
            $scope.approximate_labour_load = null;
            $scope.approximate_equipment_load = null;

        }else {
            $scope.labourWeightMust = false;
            $scope.equipmentWeightMust = false;
            $scope.approximate_labour_load = null;
            $scope.approximate_equipment_load = null;
        }



    }


        $scope.saveDeliveryData = function (form) {

            console.log($scope.approximate_labour_load);
            console.log($scope.approximate_equipment_load);
        console.log(form.approximate_labour_load.$invalid);
        console.log($scope.labourWeightMust);
        console.log($scope.equipmentWeightMust);

        console.log('mani id -' + $scope.GetManiID);
        console.log('form invalid- ' + form.$invalid);


        if($scope.bd_weighment >  $scope.transport_truck){
            $scope.maniBEerrormsg = true;
            $scope.message = "BD Weighment Can Not More Than Transport Truck";

            $("#maniBEerrormsg").show().fadeTo(1500, 500).slideUp(500, function () {
                $("#maniBEerrormsg").slideUp(3000);
            });
            return;
        }
        console.log(form.$invalid)
        console.log($scope.labourWeightMust)
        console.log(!$scope.transportTruckMust)
        console.log(form.$invalid && $scope.labourWeightMust && !$scope.transportTruckMust)
        if (form.$invalid && $scope.labourWeightMust && !$scope.transportTruckMust) {
            $scope.submitted = true;
            return;
        }
        // if(form.$invalid){
        //     $scope.submitted = true;
        //     return;
        // }




        var data = {
            be_no: $scope.be_no,
            be_date: $scope.be_date,
            custom_release_order_no: $scope.custom_release_order_no,
            custom_release_order_date: $scope.custom_release_order_date,
            ain_no: $scope.ain_no,
            cnf_name: $scope.cnf_name,
            custom_approved_date: $scope.custom_approved_date,

            carpenter_packages: $scope.carpenter_packages,
            carpenter_repair_packages: $scope.carpenter_repair_packages,
            approximate_delivery_date: $scope.approximate_delivery_date,
            approximate_delivery_type: $scope.approximate_delivery_type,
            approximate_labour_load: $scope.approximate_labour_load,
            approximate_equipment_load: $scope.approximate_equipment_load,
            local_transport_type: $scope.local_transport_type,
            transport_truck : $scope.transport_truck,
            transport_van : $scope.transport_van,
            bd_weighment: $scope.bd_weighment,
            bd_haltage : $scope.bd_haltage,
            shifting_flag: $scope.shifting_flag,
            manifest_id: $scope.GetManiID,
            delivery_req_id:$scope.delivery_req_id

            //paid_tax:$scope.paid_tax,
            //paid_date:$scope.paid_date,
            // no_del_truck: $scope.no_del_truck,
            //like 5 -int $scope.GetManiNo
            // gate_pass_no: $scope.gate_pass_no,
        }
        console.log(data);
  //return;
        $http.post("/warehouse/api/delivery/save-delivery-request-data", data)

            .then(function (data) {

                console.log(data);
                console.log(data.status);

                if(data.status == 204){
                    $scope.maniBEerrormsg = true;
                    $scope.message = "Local Delivery Not Done!";
                    $("#maniBEerrormsg").show().fadeTo(1500, 500).slideUp(500, function () {
                        $("#maniBEerrormsg").slideUp(1000);
                    });
                }else {
                    $scope.maniBEsuccessmsg = true;
                    $scope.SuccessMessage = 'Saved!';
                    $("#maniBEsuccessmsg").show().fadeTo(1500, 500).slideUp(500, function () {
                        $("#maniBEsuccessmsg").slideUp(1000);
                    });
                }


                $scope.truckAddModalShowBtn = true;
                // $scope.saveManifestDataBtn = false;
                // $scope.cacheLocalTransportRequestedNumber = $scope.no_del_truck;//used in getbdtruckData()
                $scope.be_no = null;
                $scope.be_date = null;
                $scope.bd_weighment = null;
                $scope.bd_haltage = null;
                $scope.shifting_flag = null;
                //$scope.paid_tax=null;
                $scope.ain_no = null;
                //$scope.paid_date=null;
                $scope.cnf_name = null;
                $scope.no_del_truck = null;
                $scope.custom_release_order_no = null;
                $scope.custom_release_order_date = null;
                $scope.approximate_delivery_date = null;
                $scope.approximate_labour_load = null;
                $scope.approximate_delivery_type = null;
                $scope.custom_approved_date = null;
                $scope.local_transport_type = '0';
                $scope.transport_van = null;
                $scope.transport_truck = null;
                $scope.gate_pass_no = null;
                $scope.submitted = false;
                // $('#saveManifestDataBtn').html('Save')


               $scope.warehouseDeliveryModal();


            }).catch(function (r) {
            console.log(r.status);
            console.log(r)

            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

            $scope.maniBEerrormsg = true;
            $scope.message = "Something went wrong!";
            $("#maniBEerrormsg").show().fadeTo(1500, 500).slideUp(500, function () {
                $("#maniBEerrormsg").slideUp(3000);
            });


        }).finally(function () {

            $scope.dataLoading = false;

        })


    };

    $scope.edit = function (i) {
        console.log(i);
        $scope.updateBtn = true;
        $scope.m_id = i.m_id;
        $scope.del_req_id = i.delivery_req_id;
        $scope.bd_weighment = i.bd_weighment;
        $scope.bd_haltage = i.bd_haltage;
         $scope.shifting_flag = i.m_shifting_flag ? i.m_shifting_flag.toString():'0';
         $scope.carpenter_packages = parseFloat(i.carpenter_packages);
        $scope.carpenter_repair_packages = parseFloat(i.carpenter_repair_packages);
        $scope.gate_pass_no = i.gate_pass_no;
        $scope.approximate_delivery_date = i.approximate_delivery_date;
         $scope.getNetWeightForLoadingCharge=parseFloat(i.chargeable_weight ? i.chargeable_weight :$scope.GetManiGWeight);


         $scope.approximate_labour_load =parseFloat(i.approximate_labour_load);
         $scope.approximate_equipment_load = parseFloat(i.approximate_equipment_load);


        $scope.approximate_delivery_type = i.approximate_delivery_type != null ? i.approximate_delivery_type.toString() : "0";
        console.log($scope.labourWeightMust);
        console.log(i.approximate_labour_load);
        console.log(i.approximate_equipment_load);
        if ($scope.approximate_delivery_type == "0") {//labour

                $scope.labourWeightMust = true;
                $scope.equipmentWeightMust = false;

        }else if($scope.approximate_delivery_type=='1'){//equ
            $scope.labourWeightMust = false;
            $scope.equipmentWeightMust = true;
        }else if($scope.approximate_delivery_type=='2') {//both
            $scope.labourWeightMust = true;
            $scope.equipmentWeightMust = true;
        }else if($scope.approximate_delivery_type=='3') {
            $scope.labourWeightMust = false;
            $scope.equipmentWeightMust = false;
        } else {
            $scope.labourWeightMust = false;
            $scope.equipmentWeightMust = false;
        }

        console.log($scope.labourWeightMust)
        console.log($scope.equipmentWeightMust)
        $scope.local_transport_type = i.local_transport_type != null ? i.local_transport_type.toString():"0";

         $scope.changeApprxTransportFlag($scope.local_transport_type);
        $scope.transport_truck = parseFloat(i.transport_truck);
        $scope.transport_van = parseFloat(i.transport_van);
    }

    $scope.updateDeliveryData = function (form) {


        console.log(form.approximate_labour_load.$invalid);
        console.log($scope.labourWeightMust);
        console.log($scope.equipmentWeightMust);

        console.log('mani id -' + $scope.GetManiID);
        console.log('form invalid- ' + form.$invalid);


        if($scope.bd_weighment >  $scope.transport_truck){
            $scope.maniBEerrormsg = true;
            $scope.message = "BD Weighment Can Not More Than Transport Truck";

            $("#maniBEerrormsg").show().fadeTo(1500, 500).slideUp(500, function () {
                $("#maniBEerrormsg").slideUp(3000);
            });
            return;
        }

        console.log(form.$invalid && $scope.labourWeightMust && !$scope.transportTruckMust);
        if (form.$invalid && $scope.labourWeightMust && !$scope.transportTruckMust) {
            $scope.submitted = true;
            return;
        }
        // if(form.$invalid){
        //     $scope.submitted = true;
        //     return;
        // }


        var data = {
            be_no: $scope.be_no,
            be_date: $scope.be_date,
            custom_release_order_no: $scope.custom_release_order_no,
            custom_release_order_date: $scope.custom_release_order_date,
            ain_no: $scope.ain_no,
            cnf_name: $scope.cnf_name,
            custom_approved_date: $scope.custom_approved_date,

            carpenter_packages: $scope.carpenter_packages,
            carpenter_repair_packages: $scope.carpenter_repair_packages,
            approximate_delivery_date: $scope.approximate_delivery_date,
            approximate_delivery_type: $scope.approximate_delivery_type,
            approximate_labour_load: $scope.approximate_labour_load,
            approximate_equipment_load: $scope.approximate_equipment_load,
            local_transport_type: $scope.local_transport_type,
            transport_truck : $scope.transport_truck,
            transport_van : $scope.transport_van,
            bd_weighment: $scope.bd_weighment,
            bd_haltage : $scope.bd_haltage,
            shifting_flag: $scope.shifting_flag,
            manifest_id: $scope.m_id,
            del_req_id:  $scope.del_req_id
            //paid_tax:$scope.paid_tax,
            //paid_date:$scope.paid_date,
            // no_del_truck: $scope.no_del_truck,
            //like 5 -int $scope.GetManiNo
            // gate_pass_no: $scope.gate_pass_no,
        }
        console.log(data);

        $http.post("/warehouse/api/delivery/update-delivery-request-data", data)

            .then(function (data) {

                console.log(data);

                $scope.maniBEsuccessmsg = true;
                $scope.SuccessMessage = 'Updated !';
                $("#maniBEsuccessmsg").show().fadeTo(1500, 500).slideUp(500, function () {
                    $("#maniBEsuccessmsg").slideUp(1000);
                });
                $scope.truckAddModalShowBtn = true;
                // $scope.saveManifestDataBtn = false;
                // $scope.cacheLocalTransportRequestedNumber = $scope.no_del_truck;//used in getbdtruckData()
                $scope.updateBtn = false;
                $scope.be_no = null;
                $scope.be_date = null;
                $scope.bd_weighment = null;
                $scope.bd_haltage = null;
                $scope.shifting_flag = null;
                //$scope.paid_tax=null;
                $scope.ain_no = null;
                //$scope.paid_date=null;
                $scope.cnf_name = null;
                $scope.no_del_truck = null;
                $scope.custom_release_order_no = null;
                $scope.custom_release_order_date = null;
                $scope.approximate_delivery_date = null;
                $scope.approximate_delivery_type = null;
                $scope.approximate_labour_load = null;
                $scope.custom_approved_date = null;
                $scope.local_transport_type = '0';
                $scope.transport_van = null;
                $scope.transport_truck = null;
                $scope.submitted = false;
                $scope.gate_pass_no = null;
                // $('#saveManifestDataBtn').html('Save')


                $scope.warehouseDeliveryModal();


            }).catch(function (r) {
            console.log(r.status);
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

            $scope.maniBEerrormsg = true;
            $scope.message = "Something went wrong!";
            $("#maniBEerrormsg").show().fadeTo(1500, 500).slideUp(500, function () {
                $("#maniBEerrormsg").slideUp(3000);
            });


        }).finally(function () {
            $scope.dataLoading = false;
        })
    };


    }).filter('ceil', function () {
    return function (input) {
        return Math.ceil(input);
    };
    }).filter('stringToDate', function ($filter) {
        return function (ele, dateFormat) {
            return $filter('date')(new Date(ele), dateFormat);
        }
    })

    .filter('dateShort', function ($filter) {
        return function (ele, dateFormat) {
            return $filter('date')(new Date(ele), dateFormat);
        }
    })
    .filter('item_type', function () {
        return function (val) {

            var type;
            if (val == 1) {
                return type = 'Volumn';
            }
            else if (val == 2) {
                return type = 'Unit';
            }
            else if (val == 3) {
                return type = 'Package';
            }
            else {
                return type = 'Weight';
            }

        };
    }).filter('dangerous', function () {
        return function (val) {
            var type;
            if (val == 1) {
                return type = '200%';
            }
            else {
                return type = '';
            }
        };
    }).filter('capitalize', function () {
    return function (input, all) {
        return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }) : '';
    }
}).filter('loading', function () {

    return function (items) {
        var item = items;
        if (item == 0) {
            return item = "Labour";
        } else if(item == 1) {
            return item = "Equipment";
        } else if(item == 2) {
            return item = "Both";
        }else if(item == 3){
            return item = "Self";
        }else if(item == 4){
            return item = "None";
        }
        return item = '';
    }

}).filter('transportTypeFilter', function () {
    return function (val) {
        var type;
        if (val == 0) {
            return type = 'Truck';
        } else if (val == 1) {
            return type = 'VAN';
        } else  if(val == 2){
            return type ='Self';
        }else if (val ==  3){
            return type = 'Both';
        }
        return type = '';
    }
}).filter('shifting_flag', function () {
    return function (items) {
        var item = items;
        if (item == 0) {
            item = "NO";
        }else {
            item = "Yes";
        }
        return item;
    }
}).filter('unique', function() {
    // we will return a function which will take in a collection
    // and a keyname
    return function(collection, keyname) {
        // we define our output and keys array;
        var output = [],
            keys = [];

        // we utilize angular's foreach function
        // this takes in our original collection and an iterator function
        angular.forEach(collection, function(item) {
            // we check to see whether our object exists
            var key = item[keyname];
            // if it's not already part of our keys array
            if(keys.indexOf(key) === -1) {
                // add it to our keys array
                keys.push(key);
                // push this item to our final output array
                output.push(item);
            }
        });
        // return our array which should be devoid of
        // any duplicates
        return output;
    };
});
