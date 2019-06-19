angular.module('assessmentApp', ['ngAnimate', 'customServiceModule'])
    .controller('assessmentCtrl', function ($scope, $http, $timeout, $filter, manifestService, amountToTextService) {


        $scope.Math = window.Math;
        //new Manifest Added Start - 6/8/17
        $scope.role_name = role_name;
        $scope.role_id = role_id;
        //console.log($scope.role_name);
        $scope.keyBoard = function (event) {
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
        }

        $scope.$watch('searchText', function () {
            $scope.searchText = manifestService.addYearWithManifest($scope.searchText, $scope.keyboardFlag);
        });
        $scope.$watch('searchText', function (val) {

            $scope.searchText = $filter('uppercase')(val);

        }, true);



//partial assessment variable

        $scope.manifes_no=$("#mani_no").val();
        $scope.partial_status=$("#partial_status").val();

        console.log($scope.manifes_no+' partial status- '+$scope.partial_status);
//Global Variable
        $scope.transshipment = false;
        $scope.assessmentSavePage = true;
        $scope.manifestFound = false;
        $scope.assessmentApproved = false;

        $scope.TotalAmount = 0;
        $scope.Manifest_id = 0;
        $scope.ManifestNo = 0;
        $scope.bassisOfCharge = 0;

        $scope.chargableTonForWarehouse = 0;
        $scope.shed_or_yard = null;
        $scope.listOfWarning = [];


        //   warehouse======================
        $scope.WarehouseReceiveWeight = 0;
        $scope.WareHouseRentDay = 0;
        $scope.TotalWarehouseCharge = 0
        $scope.WarehouseChargeforPercentage = 0

        //Weigment Charge
        $scope.weightment_measurement_charges = 0;
        $scope.weightmentChargesForeign = 0;
        $scope.weightmentChargesForeign = 0;
        $scope.weightmentChargesLocal = 0;

        $scope.entranceFee = 0;
        $scope.totalForeignTruckEntranceFee = 0;
        $scope.totalLocalTruckEntranceFee = 0;

        //document Charge
        $scope.documentCharges = 0;


//====================Manifest search==========================

        $scope.manifestSearch = function (text) {



                $scope.saveAttemptWithoutManifest = false;
                $scope.errorDuringCheckingManifest = false;
                $scope.permissionError = null;
                $scope.Manifest_id = null;
                $scope.TotalAmount = 0;
                $scope.dataLoading = true;
                $scope.assessmentApproved = false;

                var data = {
                    mani_no: text,
                    partial_status:$scope.partial_status,
                    delivery_dt:$scope.partial_delivery_dt

                }
                $http.post("/assessment/api/assessment/partial/check-manifest", data)

                    .then(function (data) {
                        console.log(data.status);
                        console.log(data.data[0])
                        if (data.status == 203) {//unauthorized user
                            $scope.permissionError = data.data.noPermission;
                            $scope.dataLoading = false;
                            $scope.previouAssValue = false;
                            return;
                        }

                        if (data.status == 204)//not found in record
                        {
                            $scope.MNotFound = true;
                            $scope.AssessmentFound = false;
                            $scope.previouAssValue = false;
                            $scope.dataLoading = false;
                            return;
                        }
                        if (data.status == 205)//not allowed for partial
                        {
                            $scope.errorDuringCheckingManifest = true;
                            $scope.errorDuringCheckingManifestTxt='Not allowed for partial'
                            $scope.AssessmentFound = false;
                            $scope.previouAssValue = false;
                            $scope.dataLoading = false;

                        }
                        else if (data.status == 206)//not allowed for partial
                        {
                            $scope.errorDuringCheckingManifest = true;
                            //$scope.errorDuringCheckingManifestTxt='You Can Do partial'
                            $scope.AssessmentFound = false;
                            $scope.previouAssValue = false;
                            $scope.dataLoading = false;
                            if(data.data['get_pre_max_par_dt'])
                            {
                                $scope.errorDuringCheckingManifestTxt=data.data['notfound']+"--"+data.data['get_pre_max_par_dt'];
                            }
                            else
                            {
                                $scope.errorDuringCheckingManifestTxt=data.data['notfound']
                            }
                            

                        }

                        else {
                            if(!data.data[0].description_of_goods){
                                $scope.listOfWarning.push('No Item Selected!')
                            }
                            if(!data.data[0].posted_yard_shed){
                                $scope.listOfWarning.push('No Shed/Yard Selected!')
                            }

                            if (data.data[0].previous_ass_value == null)//assessment not done
                            {
                                $scope.previouAssValue = false;
                            }
                            else {
                                $scope.previousAssementValue = ((( parseFloat(Math.ceil(data.data[0].previous_ass_value)) * 15 ) / 100) + parseFloat(Math.ceil(data.data[0].previous_ass_value)));
                                $scope.previouAssValue = true;
                            }

                            $scope.manifestFound = true;
                            $scope.transshipment = data.data[0].transshipment_flag ? true : false;

                            console.log($scope.transshipment);

                            //get Assessment  heading
                            $scope.Manifest_id = data.data[0].manifest_id;
                            $scope.ManifestNo = data.data[0].manifest_no;
                            $scope.Mani_date = data.data[0].manifest_date;
                            $scope.Bill_No = data.data[0].bill_entry_no;
                            $scope.Bill_date = data.data[0].bill_entry_date;
                            $scope.Custome_release_No = data.data[0].custom_realise_order_No;
                            $scope.Custome_release_Date = data.data[0].custom_realise_order_date;
                            $scope.Consignee = data.data[0].importer;
                            $scope.Consignor = data.data[0].exporter;
                            // $scope.package_no = data.data[0].package_no;
                            $scope.package_type = data.data[0].package_type;

                            if (!$scope.Consignee) {
                                $scope.listOfWarning.push('No Vat ID Found!')
                            }
                            if (!$scope.Consignor) {
                                $scope.listOfWarning.push('No Consignor Found!')
                            }

                            $scope.totalItems = data.data[0].totalItems;

                            $scope.description_of_goods = data.data[0].description_of_goods;
                            /*$scope.bassisOfCharge = Math.round(data.data[0].chargeable_weight);
                            $scope.chargeable_weight = Math.ceil(data.data[0].chargeable_weight / 1000);*/

                            //  $scope.bassisOfCharge = Math.ceil(data.data[0].chargeable_weight / 1000);


                            $scope.CnF_Agent = data.data[0].cnf_name;
                            $scope.posted_yard_shed = data.data[0].posted_yard_shed;
                            $scope.shed_or_yard = data.data[0].yard_shed;

                            //  console.log($scope.Shed_Yard)

                            $scope.MNotFound = false;

                            $scope.AssessmentData(text);

                            $scope.AssessmentFound = true;
                        }


                    }).catch(function (r) {

                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        console.log('error');
                        //console.log(ex);
                        $scope.errorDuringCheckingManifest = true;
                        $scope.errorDuringCheckingManifestTxt = 'Internal Server Error';
                        $scope.AssessmentFound = false;
                        $scope.previouAssValue = false;
                        $scope.dataLoading = false;


                    }).finally(function () {


                    })

        };
        $scope.manifestSearch($scope.manifes_no);

        $scope.getPartialData=function(partial_delivery_dt){
            console.log(partial_delivery_dt);
            $scope.manifestSearch($scope.manifes_no);
        }


        $scope.AssessmentData = function (text) {

            var data = {
                mani_no: text,
                partial_status:$scope.partial_status,
                delivery_dt:$scope.partial_delivery_dt

            };
            console.log(data)
            //==========WareHouse Charge====================
            $http.post("/assessment/api/assessment/partial/all-partial-details", data)//it also returns all charge and document details

                .then(function (data) {
                    console.log(data.data);

                    $scope.bassisOfCharge =Math.round(data.data.remaining_weight_package[0].balance_weight);
                    $scope.chargeable_weight = Math.ceil(data.data.remaining_weight_package[0].balance_weight / 1000);
                    $scope.package_no = data.data.remaining_weight_package[0].bal_pkg;

                    //Document Details==================

                    $scope.documentCharges =data.data.allCharges[8].rate_of_charges;

                    $scope.numberOfDocuments = data.data.docunemt_details[0].number_of_document;
                    $scope.totalDocumentCharges =(parseFloat($scope.documentCharges) * $scope.numberOfDocuments );
                    $scope.TotalAmount +=(parseFloat($scope.documentCharges) * $scope.numberOfDocuments );

                    //Truck Balance Details==================

                    angular.forEach(data.data.allCharges,function (v,k) {
                        if(v.charge_id==2){
                            $scope.entrance_fee_truck=v.rate_of_charges
                        }
                        if(v.charge_id==6){
                            console.log(v.name_of_charge);
                            $scope.entrance_fee_van =v.rate_of_charges
                        }
                    })
                    $scope.entrance_fee_foreign = $scope.entrance_fee_truck;

                    if(data.data.getPartialTruckBalance[0].man_transport_type==1)
                    {
                        $scope.entrance_fee_local = $scope.entrance_fee_van;
                        $scope.local_transport = '(Van)';
                    }
                    else
                    {
                        $scope.entrance_fee_local = $scope.entrance_fee_truck;
                        $scope.local_transport = '(Truck)';
                    }
                    
                    //$scope.entrance_fee_foreign = $scope.entrance_fee_truck;

                   // $scope.numberOfExtraTuck = data.data.getPartialTruckBalance[0].remaining_truck;
                    $scope.totalLocalTruck = data.data.getPartialTruckBalance[0].remaining_truck;

                    //$scope.totalTruckCharges =(parseFloat($scope.transportEntranceCharges) * $scope.numberOfExtraTuck );
                    $scope.totalLocalTruckEntranceFee =(parseFloat($scope.entrance_fee_local) * $scope.totalLocalTruck );
                    $scope.TotalAmount += $scope.totalLocalTruckEntranceFee;
                    //console.log("TRCH : "+$scope.transportEntranceCharges+" EXTR : "+$scope.numberOfExtraTuck+" TOT : "+$scope.totalTruckCharges);
                    //$scope.TotalAmount +=(parseFloat($scope.documentCharges) * $scope.numberOfDocuments );




                    //=========Warehouse details=================
                    console.log($scope.documentCharges);
                    var warehouse = data.data;
                    $scope.WareHouseRentDay = warehouse.WareHouseRentDay;

                    $scope.Goods_id = warehouse.goods_id;
                    $scope.Shed_yard = warehouse.posted_yard_shed;
                    $scope.receive_date = warehouse.receive_date
                    $scope.freeEndDay = warehouse.FreeEndDate

                    $scope.WarehouseChargeStartDay = warehouse.ChargeStartDay
                    $scope.deliver_date = warehouse.deliver_date


                    //div show hide for item wise assessment
                    $scope.ShowFirstSlab = false;
                    $scope.ShowSecondSlab = false;
                    $scope.ShowThirdSlab = false;


                    $scope.firstSlabDay = 0;
                    $scope.secondSlabDay = 0;
                    $scope.thirdSlabDay = 0;

                    //item_wise_charge--------------------
                    $scope.item_wise_charge = data.data.item_wise_charge;
                    $scope.TotalWarehouseCharge = 0;
                    console.log($scope.item_wise_charge);

                    console.log($scope.WareHouseRentDay)

                    if ($scope.WareHouseRentDay > 0 && $scope.WareHouseRentDay <= 21) {
                        console.log($scope.WareHouseRentDay)
                        $scope.ShowFirstSlab = true;
                        $scope.ShowSecondSlab = false;
                        $scope.ShowThirdSlab = false;

                        $scope.firstSlabDay = $scope.WareHouseRentDay;
                        $scope.secondSlabDay = 0;
                        $scope.thirdSlabDay = 0;

                        //Total Charge warehouse charge===
                        angular.forEach($scope.item_wise_charge, function (v, k) {


                            if (v.dangerous == '1') {
                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.firstSlabDay * 2 * v.first_slab));
                            }
                            else {

                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.firstSlabDay * v.first_slab));
                            }
                        })

                    }
                    else if ($scope.WareHouseRentDay >= 22 && $scope.WareHouseRentDay <= 50) {
                        console.log('2nd')
                        $scope.ShowFirstSlab = false;
                        $scope.ShowSecondSlab = true;
                        $scope.ShowThirdSlab = false;

                        $scope.firstSlabDay = 21;
                        $scope.secondSlabDay = $scope.WareHouseRentDay - 21;
                        $scope.thirdSlabDay = 0;


                        //Total Charge warehouse charge===
                        angular.forEach($scope.item_wise_charge, function (v, k) {

                            console.log('21-50')

                            if (v.dangerous == '1') {
                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.firstSlabDay * 2 * v.first_slab));
                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.secondSlabDay * 2 * v.second_slab));
                            }
                            else {

                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.firstSlabDay * v.first_slab));
                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.secondSlabDay * v.second_slab));
                            }

                        })

                    }
                    else if ($scope.WareHouseRentDay >= 51) {
                        console.log('3rd')
                        $scope.ShowFirstSlab = false;
                        $scope.ShowSecondSlab = false;
                        $scope.ShowThirdSlab = true;

                        $scope.firstSlabDay = 21;
                        $scope.secondSlabDay = 29;
                        $scope.thirdSlabDay = ($scope.WareHouseRentDay - 21 - 29)


                        //Total Charge warehouse charge===
                        angular.forEach($scope.item_wise_charge, function (v, k) {

                            if (v.dangerous == '1') {
                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.firstSlabDay * 2 * v.first_slab));
                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.secondSlabDay * 2 * v.second_slab));
                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.thirdSlabDay * 2 * v.third_slab));
                            }
                            else {

                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.firstSlabDay * v.first_slab));
                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.secondSlabDay * v.second_slab));
                                $scope.TotalWarehouseCharge += Math.ceil(($scope.chargeable_weight * $scope.thirdSlabDay * v.third_slab));
                            }

                        })

                    }
                    else {
                        $scope.ShowFirstSlab = false;
                        $scope.ShowSecondSlab = false;
                        $scope.ShowThirdSlab = false;
                    }

                    $scope.TotalAmount += Math.ceil($scope.TotalWarehouseCharge);
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
        };

        $scope.amountInWord = function (totalAmount) {
            console.log(totalAmount);
            $scope.grand_total=Math.ceil(Math.ceil(totalAmount) * 15 / 100 + Math.ceil(totalAmount));
            $scope.inword = amountToTextService.amountToText(Math.ceil(Math.ceil(totalAmount) * 15 / 100 + Math.ceil(totalAmount))) + " Taka Only";
        };


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

            var data = {
                Mani_id: $scope.Manifest_id,
                chargableTonForWarehouse: $scope.chargableTonForWarehouse,
                WareHouseRentDay: $scope.WareHouseRentDay,
                TotalWarehouseCharge: $scope.TotalWarehouseCharge,
                delivery_dt:$scope.partial_delivery_dt,

                //Warehouse Rent dd-MM-yyyy hh:mm:ss a
                dateOfUnloading: $filter('dateShort')($scope.receive_date, 'dd-MM-yyyy hh:mm:ss a'),
                freePeriod: $scope.rcv_date + " - " + $scope.freeEndDay + " = FT",
                rentDuePeriod: $filter('dateShort')($scope.WarehouseChargeStartDay, 'dd-MM-yyyy') + " - " + $filter('dateShort')($scope.deliver_date, 'dd-MM-yyyy') + " = " + $scope.WareHouseRentDay,
                weight: $scope.bassisOfCharge,
                goodDescription: $scope.description_of_goods,
                noOfPkg: $scope.package_no + " " + $scope.package_type,
                deliver_date: $scope.deliver_date,
                //totalLocalTruck : $scope.totalLocalTruck,

                //Entrance Fee
                //Foreign -----
                entrance_fee_local: $scope.entrance_fee_local,
                entrance_fee_foreign: $scope.entrance_fee_foreign,
                //entranceFee: $scope.transportEntranceCharges, 
                totalForeignTruck: $scope.totalForeignTruck,
                entranceTotalLocalTruck: $scope.totalLocalTruck,
                totalForeignTruckEntranceFee: $scope.totalForeignTruckEntranceFee,
                totalLocalTruckEntranceFee: $scope.totalLocalTruckEntranceFee,


                //documentCharges
                totalDocumentCharges: $scope.totalDocumentCharges,
                numberOfDocuments: $scope.numberOfDocuments,
                documentCharges: $scope.documentCharges,
                //Weigment Charge
                weightment_measurement_charges: $scope.weightment_measurement_charges,
                weightmentChargesForeign: $scope.weightmentChargesForeign,
                local_truck_weighment: $scope.local_truck_weighment,
                weightmentChargesLocal: $scope.weightmentChargesLocal,
                partial_status:$scope.partial_status


            }
            console.log(data);

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
//Entrance Fee

            $scope.entrance_fee_local = 0;
            $scope.entrance_fee_foreign = 0;
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
            $http.get('/api/getPreviousDocumentDetails/', {params :{manifese_id: $scope.Manifest_id ,partial_status:$scope.partial_status}})
                .then(function (data) {
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
//console.log($scope.DocumentForm.$invalid);
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
                partial_status:$scope.partial_status
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


    })


    .filter('ceil', function () {
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
