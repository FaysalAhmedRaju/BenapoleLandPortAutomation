angular.module('AssessmentDetailsApp', ['angularUtils.directives.dirPagination', 'ngAnimate', 'ngTagsInput', 'customServiceModule'])
    .controller('AssessmentDetailsCtrl', function ($scope, $http) {

        $scope.Done = function () {
            $scope.savingData = true;
            $http.get("/transshipment/assessment-admin/api/assessment/done-assessment/" + manifest_id + "/" + assessment_id + "/" + partial_status)
                .then(function (data) {
                    console.log(data.data)
                    $scope.insertSuccessMsg = true;
                    $('#saveSuccess').show().delay(3000).slideUp(1000);
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.insertErrorMsg = true;
                $('#saveError').show().delay(3000).slideUp(1000);
                $scope.insertErrorMsgTxt = 'Something Went Wrong!';
            }).finally(function () {
                $scope.savingData = false;
                $scope.CheckAssessmentDone();
            });
        }

        //$scope.showDoneButton = false;
        //$scope.showAlreadyDone = false; 
        $scope.CheckAssessmentDone = function() {
            $http.get("/transshipment/assessment-admin/api/assessment/check-assessment-done/" + assessment_id)
                .then(function(data) {
                    if(data.data[0].done == 0) {
                        $scope.showDoneButton = true;
                        $scope.showAlreadyDone = false;
                    } else if(data.data[0].done == 1) {
                        $scope.showAlreadyDone = true;
                        $scope.showDoneButton = false;
                    }
                    console.log($scope.showAlreadyDone);
                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                }).finally(function() {

                });
        }
        $scope.CheckAssessmentDone();
    })
    .controller('assessmentCtrl', function ($scope, $http, $timeout, $filter, manifestService, amountToTextService) {

        $scope.cnfNameDisable = true;
        $scope.Math = window.Math;
        //new Manifest Added Start - 6/8/17
        $scope.role_name = role_name;
        $scope.role_id = role_id;
        console.log($scope.role_name);
        $scope.keyBoard = function (event) {
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
        }

        $scope.$watch('searchText', function () {
            $scope.searchText = manifestService.addYearWithManifest($scope.searchText, $scope.keyboardFlag);
        });
        $scope.$watch('searchText', function (val) {

            $scope.searchText = $filter('uppercase')(val);

        }, true);

//Global Variable

        $scope.partial_status = partial_status;
        $scope.partial_number_list = [];
        $scope.transshipment = false;
        $scope.assessmentSavePage = true;
        $scope.manifestFound = false;
        $scope.assessmentApproved = false;
        $scope.local_transport_type = null;

        $scope.TotalAmount = 0;
        $scope.Manifest_id = 0;
        $scope.ManifestNo = 0;
        $scope.bassisOfCharge = 0;

        $scope.chargableTonForWarehouse = 0;
        $scope.shed_or_yard = null;
        $scope.listOfWarning = [];

//warehouse======================
        $scope.WarehouseReceiveWeight = 0;
        $scope.WareHouseRentDay = 0;
        $scope.TotalWarehouseCharge = 0
        $scope.WarehouseChargeforPercentage = 0

//Handling Charge============
        //--offload-------------
        $scope.OffloadLabour = 0
        $scope.OffLoadingEquip = 0
        $scope.OffloadLabourCharge = 0
        $scope.OffLoadingEquipCharge = 0
        $scope.TotalForOffloadLabour = 0
        $scope.TotalForOffloadEquip = 0

        //---load-----------
        $scope.loadLabour = 0;
        $scope.loadingEquip = 0;
        $scope.loadLabourCharge = 0;
        $scope.loadingEquipCharge = 0;
        $scope.TotalForloadLabour = 0;
        $scope.TotalForloadEquip = 0;
        $scope.loadingShifting = false;
//Entrance Fee

        $scope.entrance_fee_local = 0;
        $scope.entrance_fee_foreign = 0;
        $scope.totalForeignTruckEntranceFee = 0;
        $scope.totalLocalTruckEntranceFee = 0;
        $scope.totalForeignTruck = 0;
        $scope.totalLocalTruck = 0;
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

        $scope.foreign_haltage_charge = 0;
        $scope.local_haltage_charge = 0;

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


//====================Manifest search==========================

        $scope.get_partial = function (manifest_no, status) {
            console.log(status);
            $scope.manifestSearch(manifest_no, status);
        }

        $scope.manifestSearch = function (text, partial_status = null) {
            $scope.insertSuccessMsg = false;
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
            $http.post("/transshipment/api/assessment/check-manifest-all-charges-partial-list", data)
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
                    console.log(partial_status);
                    console.log('Partial Number:');
                    console.log($scope.partial_number);
                    console.log($scope.partial_number_list);
                    for (var x = 0; x < $scope.partial_number; x++) {
                        $scope.partial_number_list[x] = x+1;
                    }
                    console.log($scope.partial_status);
                    if($scope.partial_status == null) {
                        $scope.partial_status = $scope.partial_number_list[$scope.partial_number-1];
                    }
                    console.log($scope.partial_status);
                    console.log($scope.partial_number_list);
                    
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
                    $scope.perishable = $scope.manifestDetails.perishable_flag ? true : false;

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

                    if (!$scope.Consignee) {
                        $scope.listOfWarning.push('No Vat ID Found!')
                    }
                    if (!$scope.Consignor) {
                        $scope.listOfWarning.push('No Consignor Found!')
                    }
                    if (!$scope.description_of_goods) {
                        $scope.listOfWarning.push('No Item Selected!')
                    }
                    if (!$scope.posted_yard_shed) {
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
                    $scope.AssessmentFound = false;
                    $scope.previouAssValue = false;
                    $scope.dataLoading = false;
                }).finally(function () {
                });


        };

        $scope.AssessmentData = function (text, partial_status = null) {
            var data = {
                mani_no: text,
                partial_status: partial_status

            };
            //==========WareHouse Charge====================
            $http.post("/transshipment/api/assessment/get-warehouse-details", data)
                .then(function (data) {
                    console.log('WareHouse Details:');
                    console.log(data.data);
                    console.log($scope.TotalAmount);

                    var warehouse = data.data;

                    $scope.WareHouseRentDay = warehouse.warehouse_rent_day;
                    $scope.receive_date = warehouse.receive_date;
                    $scope.freeEndDay = warehouse.free_end_day;
                    $scope.WarehouseChargeStartDay = warehouse.charge_start_day;
                    $scope.deliver_date = warehouse.delivery_date;
                    //$scope.item_wise_shed_charge = warehouse.item_wise_shed_details_charge;
                    $scope.item_wise_yard_charge = warehouse.item_wise_yard_details_charge;

                    //div show hide for item wise assessment
                    $scope.ShowFirstSlab = false;
                    $scope.ShowSecondSlab = false;
                    $scope.ShowThirdSlab = false;


                    $scope.firstSlabDay = 0;
                    $scope.secondSlabDay = 0;
                    $scope.thirdSlabDay = 0;
                    $scope.TotalWarehouseCharge = 0;

                    console.log($scope.item_wise_yard_charge);
                    //console.log($scope.item_wise_shed_charge);

                    console.log($scope.WareHouseRentDay);
                    $scope.TotalWarehouseCharge = 0;
                    if ($scope.WareHouseRentDay > 0 && $scope.WareHouseRentDay <= 21) {
                        $scope.ShowFirstSlab = true;
                        $scope.ShowSecondSlab = false;
                        $scope.ShowThirdSlab = false;

                        $scope.firstSlabDay = $scope.WareHouseRentDay;
                        $scope.secondSlabDay = 0;
                        $scope.thirdSlabDay = 0;

                        //Total Charge warehouse charge===
                        if ($scope.item_wise_yard_charge) {
                            angular.forEach($scope.item_wise_yard_charge, function (v, k) {
                                if (v.dangerous == '1') {
                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.firstSlabDay * 2 * v.first_slab));
                                }
                                else {

                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.firstSlabDay * v.first_slab));
                                }
                            })
                        }
                    } else if ($scope.WareHouseRentDay >= 22 && $scope.WareHouseRentDay <= 50) {
                        $scope.ShowFirstSlab = false;
                        $scope.ShowSecondSlab = true;
                        $scope.ShowThirdSlab = false;

                        $scope.firstSlabDay = 21;
                        $scope.secondSlabDay = $scope.WareHouseRentDay - 21;
                        $scope.thirdSlabDay = 0;


                        //Total Charge warehouse charge===
                        if ($scope.item_wise_yard_charge) {
                            angular.forEach($scope.item_wise_yard_charge, function (v, k) {
                                if (v.dangerous == '1') {
                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.firstSlabDay * 2 * v.first_slab));
                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.secondSlabDay * 2 * v.second_slab));
                                }
                                else {

                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.firstSlabDay * v.first_slab));
                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.secondSlabDay * v.second_slab));
                                }

                            })
                        }

                    }
                    else if ($scope.WareHouseRentDay >= 51) {
                        $scope.ShowFirstSlab = false;
                        $scope.ShowSecondSlab = false;
                        $scope.ShowThirdSlab = true;

                        $scope.firstSlabDay = 21;
                        $scope.secondSlabDay = 29;
                        $scope.thirdSlabDay = ($scope.WareHouseRentDay - 21 - 29);


                        //Total Charge warehouse charge===
                        if ($scope.item_wise_yard_charge) {

                            angular.forEach($scope.item_wise_yard_charge, function (v, k) {

                                if (v.dangerous == '1') {
                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.firstSlabDay * 2 * v.first_slab));
                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.secondSlabDay * 2 * v.second_slab));
                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.thirdSlabDay * 2 * v.third_slab));
                                }
                                else {

                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.firstSlabDay * v.first_slab));
                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.secondSlabDay * v.second_slab));
                                    $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.thirdSlabDay * v.third_slab));
                                }
                            })
                        }
                    }
                    else {
                        $scope.ShowFirstSlab = false;
                        $scope.ShowSecondSlab = false;
                        $scope.ShowThirdSlab = false;
                    }
                    $scope.TotalAmount += Math.ceil($scope.TotalWarehouseCharge);
                    $scope.amountInWord($scope.TotalAmount);

                })
                .catch(function () {

                })
                .finally(function () {

                });

            //==========Handling Charge====================
            $http.post("/transshipment/api/assessment/get-handling-charges", data)
                .then(function (data) {
                    $scope.handling = data.data[0];
                    console.log($scope.handling)
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 32) {
                            $scope.OffloadLabourCharge = parseFloat(v.rate_of_charges);
                        }
                        if (v.charge_id == 36) {
                            $scope.OffLoadingEquipCharge = v.rate_of_charges;
                            $scope.loadingEquipCharge = v.rate_of_charges;
                        }
                        if (v.charge_id == 34) {
                            $scope.loadLabourCharge = parseFloat(v.rate_of_charges);
                        }
                    })
                    if (!$scope.OffloadLabourCharge || !$scope.OffLoadingEquipCharge) {
                        $scope.listOfWarning.push('No Offload Labour/Equip Charge Found!')
                    }
                    if (!$scope.loadLabourCharge || !$scope.loadingEquipCharge) {
                        $scope.listOfWarning.push('No Load Labour/Equip Charge Found!')
                    }

                    if($scope.perishable) { //value will be shown in normal loading unloading place
                        if ($scope.handling.labor_load > 0) {
                            $scope.loadLabour = $scope.handling.labor_load;
                            $scope.TotalForloadLabour = ($scope.loadLabourCharge * $scope.loadLabour).toFixed(2);
                            
                        } else {
                            $scope.loadLabour = 0;
                            $scope.TotalForloadLabour = 0;
                        }

                        $scope.TotalAmount += parseFloat($scope.TotalForloadLabour);
                        console.log($scope.TotalForloadLabour);
                        console.log($scope.TotalAmount);

                        if($scope.handling.equip_load > 0) {
                            $scope.loadingEquip = $scope.handling.equip_load;
                            $scope.TotalForloadEquip = ($scope.loadingEquipCharge * $scope.loadingEquip).toFixed(2);
                            if ($scope.loadShifting) {
                                $scope.loadingShifting = true;
                                $scope.TotalForloadEquip = ($scope.loadingEquipCharge * $scope.loadingEquip * 2).toFixed(2);
                            }
                        } else {
                            $scope.loadingEquip = 0;
                            $scope.TotalForloadEquip = 0;
                        }
                        $scope.TotalAmount += parseFloat($scope.TotalForloadEquip);
                    } else {//not parishable
                        //---------unLoading
                        if($scope.partial_status == 1) {
                            $scope.OffloadLabour = $scope.chargeable_weight; // maximum weight between labour and gross weight
                            $scope.OffLoadingEquip = $scope.handling.equip_unload;
                            if ($scope.OffloadLabour > 0) {
                                $scope.TotalForOffloadLabour = ($scope.OffloadLabourCharge * $scope.OffloadLabour);
                            } else {
                                $scope.TotalForOffloadLabour = 0;
                            }
                            if($scope.OffLoadingEquip > 0) {
                                $scope.TotalForOffloadEquip = ($scope.OffLoadingEquipCharge * ($scope.shifting_flag ? 2 : 1) * $scope.OffLoadingEquip );
                            } else {
                                $scope.TotalForOffloadEquip = 0;
                            }
                        } else {
                            $scope.OffloadLabour = 0;
                            $scope.OffLoadingEquip = 0;
                            $scope.TotalForOffloadLabour = 0;
                            $scope.TotalForOffloadEquip = 0;
                        }
                        $scope.TotalAmount += parseFloat($scope.TotalForOffloadLabour);
                        $scope.TotalAmount += parseFloat($scope.TotalForOffloadEquip);

                        //---------loading
                        if($scope.partial_status == 1) {
                            if($scope.handling.labor_load > 0) {
                                $scope.loadLabour = $scope.handling.labor_load;
                                $scope.TotalForloadLabour = ($scope.loadLabourCharge * $scope.loadLabour);
                            } else {
                               $scope.loadLabour = 0;
                               $scope.TotalForloadLabour = 0; 
                            }
                            
                            if($scope.handling.equip_load > 0) {
                                $scope.loadingEquip = $scope.handling.equip_load;
                                $scope.TotalForloadEquip = ($scope.loadingEquipCharge * $scope.loadingEquip);
                                if($scope.loadShifting) {
                                    $scope.loadingShifting = true;
                                    $scope.TotalForloadEquip = ($scope.loadingEquipCharge * $scope.loadingEquip * 2);
                                } 
                            } else {
                                $scope.loadingEquip = 0;
                                $scope.TotalForloadEquip = 0; 
                            }
                            
                        } else {
                            $scope.loadLabour = 0;
                            $scope.TotalForloadLabour = 0;
                            $scope.loadingEquip = 0;
                            $scope.TotalForloadEquip = 0;
                        }
                        $scope.TotalAmount += parseFloat($scope.TotalForloadEquip);
                        $scope.TotalAmount += parseFloat($scope.TotalForloadLabour);

                    }
                    console.log($scope.TotalAmount)
                    $scope.amountInWord($scope.TotalAmount);

                    console.log($scope.TotalAmount)
                }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    }
                }).finally(function () {

                });

//=============================Other Dues=================================              
            $http.post("/transshipment/api/assessment/get-other-dues-charges", data)
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
                    })
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
                    $scope.totalLocalTruck = $scope.entranceCarpenterWeighmentCharge.transport_truck;
                    $scope.totalLocalVan = $scope.entranceCarpenterWeighmentCharge.transport_van;

                    $scope.totalForeignTruckEntranceFee = ($scope.totalForeignTruck * $scope.entrance_fee_foreign).toFixed(2);
                    $scope.totalLocalTruckEntranceFee = ($scope.totalLocalTruck * $scope.entrance_fee_local).toFixed(2);
                    $scope.totalLocalVanEntranceFee = ($scope.totalLocalVan * $scope.entrance_fee_van).toFixed(2);

                    $scope.TotalAmount += parseFloat($scope.totalForeignTruckEntranceFee);
                    $scope.TotalAmount += parseFloat($scope.totalLocalTruckEntranceFee);
                    $scope.TotalAmount += parseFloat($scope.totalLocalVanEntranceFee);
                    console.log('after entrance fee ' + $scope.TotalAmount);
                    console.log(parseFloat($scope.TotalAmount));
                    console.log(parseFloat($scope.totalForeignTruckEntranceFee));

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
                    $scope.TotalAmount += parseFloat($scope.totalcarpenterChargesOpenClose);
                    // $scope.carpenterRepairPackages = $scope.entranceCarpenterWeighmentCharge.carpenter_repair_packages;
                    // $scope.totalcarpenterChargesRepair = $scope.carpenterRepairPackages 
                    //                                         * $scope.carpenterChargesRepair;
                    // $scope.TotalAmount += $scope.totalcarpenterChargesRepair;

                    //Weighment measurement  Charges
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 12) {
                            $scope.weightment_measurement_charges = v.rate_of_charges
                        }
                    })
                    if (!$scope.weightment_measurement_charges) {
                        $scope.listOfWarning.push('No Weighment Charge Found!')
                    }

                    if($scope.partial_status == 1) {
                        $scope.weightmentChargesForeign = ($scope.weightment_measurement_charges * $scope.totalForeignTruck * 2).toFixed(2);
                    } else {
                        $scope.weightmentChargesForeign = 0;
                    }
                   
                    $scope.TotalAmount += parseFloat($scope.weightmentChargesForeign);

                    $scope.local_truck_weighment = $scope.entranceCarpenterWeighmentCharge.local_truck_weighment;
                    $scope.weightmentChargesLocal = ($scope.weightment_measurement_charges * $scope.local_truck_weighment * 2).toFixed(2);
                    $scope.TotalAmount += parseFloat($scope.weightmentChargesLocal);
                    console.log('after handling and EntranceCarpenterWeighmentCharge= ' + $scope.TotalAmount);

                    //Haltage Foreign Truck
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 20) {
                            $scope.foreign_haltage_charge = v.rate_of_charges
                            $scope.local_haltage_charge = v.rate_of_charges;
                        }
                    });
                    if (!$scope.foreign_haltage_charge) {
                        $scope.listOfWarning.push('No Haltage Charge Found!')
                    }
                    $scope.haltagesForeignScaleWeight = 0;
                    $scope.haltagesForeignReceiveWeight = 0;
                    if($scope.partial_status ==1) {
                        angular.forEach($scope.haltagesForeignTruck, function (v, k) {
                            if (v.haltage_days > 0 && v.holtage_charge_flag == 0) {
                                $scope.haltagesTotalForeignTruck += 1;
                                $scope.TotalHaltageForeignCharge += (v.haltage_days * $scope.foreign_haltage_charge).toFixed(2);
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
                    console.log($scope.TotalHaltageLocalCharge);
                    $scope.TotalAmount += parseFloat($scope.TotalHaltageLocalCharge);
                    $scope.TotalAmount += parseFloat($scope.TotalHaltageForeignCharge);
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
                        $scope.nightTotalForeignTruck = $scope.nightChargeForeign != null ? $scope.nightChargeForeign.total_foreign_truck_night : null;
                    } else {
                        $scope.nightTotalForeignTruck = 0;;
                    }
                    
                    $scope.TotalForeignNightCharge = ($scope.rate_of_night_charge * $scope.nightTotalForeignTruck).toFixed(2);
                    $scope.TotalAmount += parseFloat($scope.TotalForeignNightCharge);
                    console.log('after GetNightChargesForAssesment= ' + $scope.TotalAmount);
                    //Document Charge
                    angular.forEach($scope.allCharges, function (v, k) {
                        if (v.charge_id == 18) {
                            $scope.documentCharges = v.rate_of_charges
                        }
                    });
                    if (!$scope.documentCharges) {
                        $scope.listOfWarning.push('No Document Charge Found!')
                    }
                    if ($scope.documentDetails != 'notFound') {
                        $scope.numberOfDocuments = $scope.documentDetails[0].number_of_document;
                        $scope.totalDocumentCharges = (parseFloat($scope.documentCharges) * $scope.numberOfDocuments ).toFixed(2);
                    } else {
                        $scope.numberOfDocuments = 0;
                        $scope.totalDocumentCharges = 0;
                    }
                    $scope.TotalAmount += parseFloat($scope.totalDocumentCharges);
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

                            });
                        }  else {
                            $scope.holidayTotalForeignTruck = 0;
                            $scope.TotalForeignHolidayCharge = 0; 
                        }
                    } else {
                        $scope.holidayTotalForeignTruck = 0;
                        $scope.TotalForeignHolidayCharge = 0;
                    }
                    $scope.TotalAmount += parseFloat($scope.TotalForeignHolidayCharge);
                    //--Local
                    if($scope.holidayLocalTruck.length > 0) {
                        $scope.holidayTotalLocalTruck = $scope.holidayLocalTruck.length;
                        if ($scope.holidayLocalTruck[0].holiday != 0) {
                            $scope.TotalLocalHolidayCharge = (1 * $scope.local_holiday_charge);
                        } else {
                            $scope.TotalLocalHolidayCharge = (1 * $scope.local_holiday_charge);
                        }
                    } else {
                        $scope.holidayTotalLocalTruck = 0;
                        $scope.TotalLocalHolidayCharge = 0;  
                    }
                    $scope.TotalAmount += parseFloat($scope.TotalLocalHolidayCharge);
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
            $scope.inword = amountToTextService.amountToText($scope.grand_total) + " Taka Only";
        };


        var mani = $("#dd").val();
        console.log(mani);

        console.log($scope.searchTextAssAdmin)
        if(mani) {
            $scope.manifestSearch(mani, partial_status);
        }

        //=====Haltage Charge change option

        $scope.getTrucksForHaltageChargeChange = function () {

            if (!$scope.Manifest_id) {
                return
            }

            $scope.trucks_loading_for_changes_haltage = true;
            $scope.trucks_loading_for_changes_haltage_error = false;
            $scope.allTrucksData = null;

            $http.get("/api/get-foreign-trucks/" + $scope.Manifest_id)
                .then(function (data) {
                    console.log(data);
                    $scope.allTrucksData = data.data;
                }).catch(function (ex) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                    console.log(ex);
                    $scope.trucks_loading_for_changes_haltage_error = true;
                }).finally(function () {
                    $scope.trucks_loading_for_changes_haltage = false;
                });


        }


        $scope.changeHaltageFlagStatus = function (id, status) {
            console.log(id)

            $scope.changing_haltage_charge_flag = true;

            var data = {
                truck_id: id,
                status: status
            }

            $http.post("api/change-haltage-charge-flag-for-foreign-truck", data)

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
                    console.log(r);
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

            console.log($scope.Manifest_id);
            if ($scope.Manifest_id == null) {
                $scope.saveAttemptWithoutManifest = true;
                $scope.savingData = false;
                $("#saveError").delay(3000).slideUp(4000);

                return;
            }
            //var d = new Date($scope.receive_date);
            //var date = d.format("dd-mm-yyyy");
            //var dt12=$filter($scope.receive_date)(new Date(),'yyyy-MM-dd');
            $scope.rcv_date = $filter('dateShort')($scope.receive_date, 'yyyy-MM-dd');

            var data = {
                Mani_id: $scope.Manifest_id,
                chargableTonForWarehouse: $scope.chargableTonForWarehouse,
                WareHouseRentDay: $scope.WareHouseRentDay,
                TotalWarehouseCharge: $scope.TotalWarehouseCharge,

                //Warehouse Rent dd-MM-yyyy hh:mm:ss a
                dateOfUnloading: $filter('dateShort')($scope.receive_date, 'dd-MM-yyyy hh:mm:ss a'),
                freePeriod: $scope.rcv_date + " - " + $scope.freeEndDay + " = FT",
                rentDuePeriod: $scope.WarehouseChargeStartDay + "-" + $scope.deliver_date + " = " + $scope.WareHouseRentDay,
                weight: $scope.bassisOfCharge,
                goodDescription: $scope.description_of_goods,
                noOfPkg: $scope.package_no + " " + $scope.package_type,
                deliver_date: $scope.deliver_date,
                //totalLocalTruck : $scope.totalLocalTruck,


                //Handling charge
                //--offload-------------
                OffloadLabour: $scope.OffloadLabour,
                offloadShifting: $scope.shifting_flag,
                OffLoadingEquip: $scope.OffLoadingEquip,
                OffloadLabourCharge: $scope.OffloadLabourCharge,
                OffLoadingEquipCharge: $scope.OffLoadingEquipCharge,
                TotalForOffloadLabour: $scope.TotalForOffloadLabour,
                TotalForOffloadEquip: $scope.TotalForOffloadEquip,
                //---load-----------
                loadLabour: $scope.loadLabour,
                loadingEquip: $scope.loadingEquip,
                loadLabourCharge: $scope.loadLabourCharge,
                loadingEquipCharge: $scope.loadingEquipCharge,
                TotalForloadLabour: $scope.TotalForloadLabour,
                TotalForloadEquip: $scope.TotalForloadEquip,
                loadingShifting: $scope.loadingShifting,

                //Entrance Fee
                //Foreign -----
                entrance_fee_local: $scope.entrance_fee_local,
                entrance_fee_foreign: $scope.entrance_fee_foreign,

                totalForeignTruck: $scope.totalForeignTruck,
                entranceTotalLocalTruck: $scope.totalLocalTruck,
                totalForeignTruckEntranceFee: $scope.totalForeignTruckEntranceFee,
                totalLocalTruckEntranceFee: $scope.totalLocalTruckEntranceFee,


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
                local_truck_weighment: $scope.local_truck_weighment,
                weightmentChargesLocal: $scope.weightmentChargesLocal,
                partial_status: 0,

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

            $http.post("/api/SaveAssesmentData", data)

                .then(function (data) {

                    console.log(data);

                    $scope.insertSuccessMsg = true;

                    $("#saveSuccess").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#saveSuccess").slideUp(7000);
                    });

                    // $("#aa").show().fadeTo(500, 500).slideUp(3000, function () {
                    //     $("#aa").slideUp(3000);
                    // });

                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

                }).finally(function () {

                    $scope.savingData = false;
                })


        }


        var blank = function () {

            //manifest details
            $scope.partial_number_list = null;
            $scope.listOfWarning = [];
            $scope.Mani_date = null;
            $scope.Bill_No = null;
            $scope.Bill_date = null;
            $scope.Custome_release_No = null;
            $scope.Custome_release_Date = null;
            $scope.Consignee = null;
            $scope.Consignor = null;
            $scope.package_no = null;
            $scope.package_type = null;
            $scope.totalItems = null;
            $scope.description_of_goods = null;
            $scope.bassisOfCharge = null;
            $scope.chargeable_weight = null;


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

            $scope.ShowFirstSlab = false;
            $scope.ShowSecondSlab = false;
            $scope.ShowThirdSlab = false;


            $scope.firstSlabDay = 0;
            $scope.secondSlabDay = 0;
            $scope.thirdSlabDay = 0;
            $scope.item_wise_charge = null;

//Handling Charge============
            //--offload-------------
            $scope.OffloadLabour = 0
            $scope.OffLoadingEquip = 0
            $scope.OffloadLabourCharge = 0
            $scope.OffLoadingEquipCharge = 0
            $scope.TotalForOffloadLabour = 0
            $scope.TotalForOffloadEquip = 0

            //---load-----------
            $scope.loadLabour = 0
            $scope.loadingEquip = 0
            $scope.loadLabourCharge = 0
            $scope.loadingEquipCharge = 0
            $scope.TotalForloadLabour = 0
            $scope.TotalForloadEquip = 0
            $scope.loadingShifting = false;
//Entrance Fee

            $scope.entranceFee = 0;
            $scope.totalForeignTruckEntranceFee = 0;
            $scope.totalLocalTruckEntranceFee = 0;


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


            $scope.TotalForeignHolidayCharge = 0
            $scope.TotalLocalHolidayCharge = 0

            //Night' charge==========
            $scope.nightTotalForeignTruck = 0
            //$scope.nightTotalLocalTruck = 0


            $scope.TotalForeignNightCharge = 0;
            //$scope.TotalLocalNightCharge = 0;
            //$scope.Night_charges=0;

            //haltage charge========

            $scope.haltagesTotalForeignTruck = 0;
            $scope.haltagesTotalLocalTruck = 0;
            $scope.HaltageCharge = 0;
            $scope.TotalHaltageForeignCharge = 0;
            $scope.TotalHaltageLocalCharge = 0;
            $scope.haltagesTotalDayLocalTruck = 0;
            $scope.haltagesTotalDayForeignTruck = 0;
            //Weigment Charge
            $scope.weightment_measurement_charges = 0;
            $scope.weightmentChargesForeign = 0;
            $scope.weightmentChargesLocal = 0;

            //  document Charges
            $scope.documentCharges = 0;

        };


//multi items selection option==================================================ITEM==========================

        $scope.item_type = '1'; //set a value initialy


        $scope.loadItems = function ($query) {
            // An arrays of strings here will also be converted into an
            // array of objects

            // console.log($query)
            return $http.get('/api/GetItemList/' + $query)
                .then(function (response) {
                    //  console.log(response)
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


        $scope.getSelectItemsShow = function (m_id) {
            $scope.goods_charge_div = false;
            $scope.allItemsData = null;
            $http.get("/api/getallItemsData/" + m_id)   //get all data of items

                .then(function (data) {
                    console.log(data.data)

                    if (data.status == 203) {
                        var i = data.data[0];
                        console.log(i.chargeable_weight)
                        var item_araay = new Array();
                        item_araay.id = i.id;
                        item_araay.Description = i.cargo_name;
                        item_object = [];
                        item_object.push(item_araay);

                        $scope.item_quantity = i.chargeable_weight ? parseFloat(i.chargeable_weight) : parseFloat(i.gweight);
                        $scope.item_type = '4';

                        $scope.item_search_id = item_object;
                        return;
                    }

                    $scope.saveSuccessItems = false;
                    $scope.updateSuccess = false;
                    $scope.allItemsData = data.data;
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
            $http.get("/api/GetElevenCargoDetails/", {
                params: {
                    shed_or_yard: $scope.shed_or_yard,
                    manifest_no: $scope.ManifestNo
                }
            }) //get Goods for dropdown list
                .then(function (data) {
                    $scope.eleven_cargo_details = data.data;

                    console.log(data.data)

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
            $scope.goods_id = id;
            $scope.getGoodsCharge(id)

        }


        $scope.selectItemsShow = function () {//when modal button click for input multiope goods

            $scope.getSelectItemsShow($scope.Manifest_id);

            $scope.updateSuccess = false;
            $scope.multiItemFormSubmit = false;

            $scope.updateBtnItems = false;
            itemBlank();
        }
        $scope.goods_charge_div = false;

        $scope.getGoodsCharge = function (goods_id) {//show charge for goods whel slecting item

            $scope.goods_charge_div = true;
            if ($scope.shed_or_yard == 0) {//0 means yard
                $scope.goods_first_slab = $scope.eleven_cargo_details
                angular.forEach($scope.eleven_cargo_details, function (v, k) {
                    if (v.id == goods_id) {
                        $scope.goods_first_slab = v.yard_first_slab;
                        $scope.goods_second_slab = v.yard_second_slab;
                        $scope.goods_third_slab = v.yard_third_slab;
                    }
                })
            }
            if ($scope.shed_or_yard == 1) {//1 means shed
                angular.forEach($scope.eleven_cargo_details, function (v, k) {
                    if (v.id == goods_id) {
                        $scope.goods_first_slab = v.Shed_first_slab;
                        $scope.goods_second_slab = v.Shed_second_slab;
                        $scope.goods_third_slab = v.Shed_third_slab;
                    }
                })
            }

            if (goods_id == null) $scope.goods_charge_div = false;
        }


        $scope.addItems = function (form) {

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
                if (checkDuplicate() == true) {
                    $scope.itemErrorMsg = true;
                    $scope.itemErrorMsgTxt = "Can't add an item twice!"
                    $("#itemError").show().fadeTo(1500, 500).slideUp(1500, function () {
                        $("#itemError").slideUp(1000);
                    });
                    return;
                }

                var data = {
                    item_Code_id: $scope.item_Code_id,
                    new_item: $scope.new_item,
                    item_type: $scope.item_type,
                    item_quantity: $scope.item_quantity,
                    manf_id: $scope.Manifest_id,
                    goods_id: $scope.goods_id,
                    dangerous: $scope.dangerous
                }

                console.log(data)

//return
                $scope.savingMultiItem = true;
                console.log($scope.Manifest_id)
                $http.post("/api/saveItemsForAssessment", data)              //function 7
                    .then(function (data) {
                        console.log(data)
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
                    $scope.sav = 'Something went worng!';
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
            console.log(i)

            var item_araay = new Array();
            item_araay.id = i.item_Code_id;
            item_araay.Description = i.Description;
            item_object = [];
            item_object.push(item_araay);

            $scope.item_search_id = item_object;
            $scope.item_Code_id = i.item_Code_id;//14
            $scope.cache_item_Code_id = i.item_Code_id;//14


            $scope.dangerous = i.dangerous.toString();
            $scope.item_type = i.item_type;
            $scope.item_quantity = parseInt(i.item_quantity);
            $scope.it_id = i.it_id;//sl
            $scope.goods_id = i.goods_id


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


                if ($scope.item_Code_id != $scope.cache_item_Code_id) {

                    if (checkDuplicate() == true) {
                        $scope.itemErrorMsg = true;
                        $scope.itemErrorMsgTxt = "Can't add an item twice!"
                        $("#itemError").show().fadeTo(1500, 500).slideUp(1500, function () {
                            $("#itemError").slideUp(1000);
                        });
                        return;
                    }
                }

                var data = {

                    item_Code_id: $scope.item_Code_id,
                    new_item: $scope.new_item,
                    item_type: $scope.item_type,
                    item_quantity: $scope.item_quantity,
                    manf_id: $scope.Manifest_id,
                    goods_id: $scope.goods_id,
                    it_id: $scope.it_id,
                    dangerous: $scope.dangerous
                };
                console.log(data);

                $http.put("/api/updateItemInfo", data)                                     //function 10
                    .then(function (data) {
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
                    console.log('error')

                }).finally(function () {

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
                item_details_id: i.it_id
            }
            $http.post("/api/deleteItemsList", data)                  //function 9
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

        var checkDuplicate = function () {//
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

//END Multi items===============


        //============START Assessment change option===================

        $scope.changeReceivedayOption = function (time) {

            console.log($('#receive_datetime').val());

            //return
            if (!$scope.Manifest_id) {
                $scope.changeReceivedayError = true;
                $("#changeReceivedayError").show().delay(2000).slideUp(1000);
                $scope.changeReceivedayErrorMsg = "Please Search by Manifest First!";

                return;
            }

            var data = {
                receive_date: $scope.receive_date,//$('#receive_datetime').val(),
                Manifest_id: $scope.Manifest_id
            }

            console.log(data)

            $http.post("/api/changeReceivedayOption", data)                                     //function 10
                .then(function (r) {

                    console.log(r.status)
                    setTimeout(function () {
                        $scope.manifestSearch($scope.searchText);
                    }, 3000);


                    $scope.changeReceivedaySucc = true;
                    $("#changeReceivedaySucc").show().delay(2000).slideUp(1000);


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

            $http.post("/api/changeBassisOfChargeOption", data)                                     //function 10
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
                } else {
                    $.growl.error({message: "It has Some Error!"});
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
                //console.log(parseFloat($scope.documentCharges));
            }
        });
        $scope.documentFlag = false;
        $scope.DocumentShow = function () {
            $scope.DocumentDataLoading = true;
            $scope.documentFlag = true;
            console.log($scope.partial_status);
            $http.get('/api/getPreviousDocumentDetails/', {
                params: {
                    manifese_id: $scope.Manifest_id,
                    partial_status: $scope.partial_status
                }
            }).then(function (data) {
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
                partial_status: 0
            }
            $http.post('/api/saveDocumentData', data)
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
        $scope.warehouseDeliveryModal = function () {
            var data =
            {
                manf_id: $scope.searchText

            }
            $http.post("/api/SerachByManifest", data)
                .then(function (data) {
                    console.log(data.data);
                    console.log(data.data.length);
                    if (data.data.length > 0) {

                        var check_be_no = data.data[0].be_no;
                        console.log(check_be_no);
                        if (check_be_no == null) {
                            console.log('Hello save')

                            $('#saveManifestDataBtn').html('Save');
                            $scope.GetManiNo = null;
                            $scope.ImporterName = null;
                            $scope.be_no = null;
                            $scope.be_date = null;
                            $scope.ain_no = null;
                            $scope.cnf_name = null;
                            $scope.no_del_truck = null;
                            $scope.carpenter_packages = null;
                            $scope.carpenter_repair_packages = null;
                            // $scope.gate_pass_no = null;
                            $scope.custom_release_order_no = null;
                            $scope.custom_release_order_date = null;
                            $scope.approximate_delivery_date = null;
                            $scope.approximate_delivery_type = "0";
                            $scope.local_transport_type = "0";


                        } else {
                            console.log('Hello Update')

                            $('#saveManifestDataBtn').html('Update');
                            $scope.GetManiNo = data.data[0].manifest;
                            $scope.ImporterName = data.data[0].importer;
                            $scope.be_no = data.data[0].be_no;
                            $scope.be_date = data.data[0].be_date;
                            $scope.ain_no = data.data[0].ain_no;
                            $scope.cnf_name = data.data[0].cnf_name;
                            $scope.no_del_truck = data.data[0].no_del_truck;
                            $scope.carpenter_packages = parseInt(data.data[0].carpenter_packages);
                            $scope.carpenter_repair_packages = parseInt(data.data[0].carpenter_repair_packages);
                            // $scope.gate_pass_no = data.data[0].gate_pass_no;
                            $scope.custom_release_order_no = data.data[0].custom_release_order_no;
                            $scope.custom_release_order_date = data.data[0].custom_release_order_date;
                            $scope.approximate_delivery_date = data.data[0].approximate_delivery_date;
                            $scope.approximate_delivery_type = data.data[0].approximate_delivery_type != null ? data.data[0].approximate_delivery_type.toString() : "0";
                            $scope.local_transport_type = data.data[0].local_transport_type != null ? data.data[0].local_transport_type.toString() : "0";

                        }

                    } else {

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

        $('#m_Importer_Name').autocomplete({
            source: "/api/autocompleteAinCnfName",
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

        $scope.ValidationManifestData = function (form) {
            if (form.$invalid) {
                $scope.submitted = true;
                return false;
            } else {
                $scope.submitted = false;
                return true;
            }
        };

        $scope.SaveManifestData = function (form) {
            if ($scope.ValidationManifestData(form) == false) {
                return 0;
            }

            console.log($scope.Manifest_id);
            console.log($scope.searchText);
            $scope.dataLoading = true;
            var data = {
                be_no: $scope.be_no,
                be_date: $scope.be_date,
                ain_no: $scope.ain_no,
                cnf_name: $scope.cnf_name,
                no_del_truck: $scope.no_del_truck,
                carpenter_packages: $scope.carpenter_packages,
                carpenter_repair_packages: $scope.carpenter_repair_packages,
                manifest_id: $scope.Manifest_id, //like 5 -int $scope.GetManiNo
                // gate_pass_no: $scope.gate_pass_no,
                custom_release_order_no: $scope.custom_release_order_no,
                custom_release_order_date: $scope.custom_release_order_date,
                approximate_delivery_date: $scope.approximate_delivery_date,
                approximate_delivery_type: $scope.approximate_delivery_type,
                // custom_approved_date : $scope.custom_approved_date,
                local_transport_type: $scope.local_transport_type
            }
            console.log(data);
            $http.post("/api/saveDeliveryRequestData", data)
                .then(function (data) {

                    console.log($scope.searchText);
                    console.log('save/update')
                    // $scope.warehouseDeliveryModal($scope.searchText);
                    $scope.maniBEsuccessmsg = true;
                    $("#maniBEsuccessmsg").show().fadeTo(1500, 500).slideUp(500, function () {
                        $("#maniBEsuccessmsg").slideUp(1000);
                    });
                    $scope.warehouseDeliveryModal($scope.searchText);
                    $scope.truckAddModalShowBtn = true;
                    $scope.saveManifestDataBtn = false;
                    $scope.CacheBdTruckNo = $scope.no_del_truck;//used in getbdtruckData()
                    $scope.be_no = null;
                    $scope.be_date = null;
                    $scope.ain_no = null;
                    $scope.cnf_name = null;
                    $scope.no_del_truck = null;
                    $scope.custom_release_order_no = null;
                    $scope.custom_release_order_date = null;
                    $scope.approximate_delivery_date = null;
                    $scope.approximate_delivery_type = "0";
                    $scope.custom_approved_date = null;
                    $scope.local_transport_type = "0";
                    $scope.carpenter_packages = null;
                    $scope.carpenter_repair_packages = null;
                    // $scope.Manifest_id = null;

                    form.$setUntouched();
                    $scope.warehouseDeliveryModal($scope.searchText);
                    // $('#saveManifestDataBtn').html('Save');
                    // console.log($scope.searchText);


                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                // console.log(r.status);
                // console.log('wrong');
                //     $scope.maniBEerrormsg = true;
                //     $("#maniBEerrormsg").show().fadeTo(1500, 500).slideUp(500, function () {
                //         $("#maniBEerrormsg").slideUp(3000);
                //     });

            }).finally(function () {
                $scope.dataLoading = false;
            })
        }

        $scope.getTransportFlag = function () {
            if ($scope.local_transport_type == 0) {
                $scope.local_transport_type_flag = 0;
            } else {
                $scope.local_transport_type_flag = 1;
            }
        }


    }).filter('ceil', function () {
    return function (input) {
        return Math.ceil(input);
    };
})

    .filter('stringToDate', function ($filter) {
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
    })
    .filter('dangerous', function () {
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
});