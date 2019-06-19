angular.module('AssessmentVerificationApp',['angularUtils.directives.dirPagination', 'customServiceModule'])
	.controller('AssessmentVerificationCtrl', function($scope,$http,$filter,manifestService,amountToTextService){


        $scope.Math = window.Math;

        //capitalize Assessment verification
        $scope.$watch('searchText', function (val) {

            $scope.searchText = $filter('uppercase')(val);

        }, true);

		$scope.serachField = true;
        $scope.selection =  null;

        $scope.select = function() {
            if($scope.selection=='manifestNo') {
            	$scope.placeHolder = 'Enter Manifest No';
                $scope.serachField = false;
            } else if($scope.selection=='importerNo'){
                $scope.placeHolder = 'Enter Importer No';
                $scope.serachField = false;
            } else if($scope.selection=='billNo'){
                $scope.placeHolder = 'Enter Bill No';
                $scope.serachField = false;
            } else {
                $scope.placeHolder = null;
                $scope.serachField = true;
            }
        }

        //manifest Like 888/2/2017 start
        $scope.keyBoard = function(event) {
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
        }

        $scope.$watch('searchText', function(){
            $scope.searchText = manifestService.addYearWithManifest($scope.searchText, $scope.keyboardFlag, $scope.selection);
        });
        //manifest Like 888/2/2017 end
		
		$scope.manifestOrImporterOrBillSearch = function(searchText) {
            $scope.assessment=false;
				var data = {
					searchBy : $scope.selection,
					searchKey : searchText
				}
				//console.log(data);
			$http.post("/api/getManifestDetailsForAssessmentVerification", data)
				.then(function (data) {
					//console.log(data.data);
                    if(data.status == 203) {
                        $scope.notFoundError = data.data.noPermission;
                        return;
                    }
                    
					if(data.data.length>0) {
						$scope.allManifestData = data.data;
						$scope.table = true;
					}
					else {
						$scope.notFoundError = "Assessment Pending.";
					}
				}).catch(function() {
					$scope.notFoundError = "Something went wrong";
				}).finally(function() {
			})
		}





		// $scope.selected = function(manifest) {
		// 	$scope.selectedStyle = manifest.id;
		// }

		$scope.details = function(manifest) {

		    console.log('ok')
			$scope.dataLoading = true;
			$scope.manifestForCaption = manifest;
			var data = {
				manifest : manifest
			}
			$http.post("/api/getManifestDetailsWithTruckForAssessmentVerification", data)
				.then(function (data) {
					//console.log(data.data);
					$scope.manifestDetails = data.data.manifestDetails;
					$scope.allForeignTruck = data.data.foreignTruck;
					$scope.allLocalTruck = data.data.localTruck;
					$scope.assVarificationOrApprove=true;
					//console.log($scope.manifestDetails);
					//console.log($scope.allLocalTruck);

                    $scope.manifestSearch(manifest);
                    $scope.assessment=true;
                    $scope.assessmentVerificationForm=true;



				}).catch(function() {
					$scope.notFoundError = "Something went wrong";
				}).finally(function() {

			})
		}



//Manifest search


//====================Manifest search==========================

        $scope.manifestSearch = function (text) {

            blank();
            $scope.Manifest_id = null;
            $scope.TotalAmount = 0;
            $scope.dataLoading = true;
            $scope.assessmentApproved=false;

            var data = {
                mani_No: text
            }
            $http.post("/api/CheckManifestForAssessmentAss", data)

                .then(function (data) {
                    console.log(data.data[0])


                    if (data.data[0] != undefined)//
                    {
                        if (data.data[0].previous_ass_value == null)//assessment not done
                        {
                            $scope.previouAssValue = false;
                        }
                        else {

                            $scope.previousAssementValue = ((( parseFloat(Math.ceil(data.data[0].previous_ass_value)) * 15 ) / 100) + parseFloat(Math.ceil(data.data[0].previous_ass_value)));
                            $scope.previouAssValue = true;
                            if(data.data[0].approved != null)//assessment approved and cant edit
                            {
                                $scope.assessmentApproved=true;

                            }
                            else {
                                $scope.assessmentApproved=false;
                            }

                        }

                        $scope.manifestFound=true;

                        //get Assessment  heading
                        $scope.Manifest_id = data.data[0].manifest_id
                        $scope.ManifestNo = data.data[0].manifest_no;
                        $scope.Mani_date = data.data[0].manifest_date;
                        $scope.Bill_No = data.data[0].bill_entry_no;
                        $scope.Bill_date = data.data[0].bill_entry_date;
                        $scope.Custome_release_No = data.data[0].custom_realise_order_No;
                        $scope.Custome_release_Date = data.data[0].custom_realise_order_date;
                        $scope.Consignee = data.data[0].importer;
                        $scope.Consignor = data.data[0].exporter;
                        $scope.package_no = data.data[0].package_no;
                        $scope.package_type=data.data[0].package_type;


                        $scope.totalItems = data.data[0].totalItems

                        $scope.description_of_goods = data.data[0].description_of_goods;
                        $scope.chargableWeight = data.data[0].chargeable_weight;
                        $scope.chargableTon=Math.ceil(data.data[0].chargeable_weight/1000);


                        $scope.CnF_Agent = data.data[0].cnf_name;
                        $scope.posted_yard_shed = data.data[0].posted_yard_shed;
                        $scope.shed_or_yard=data.data[0].yard_shed;

                        //  console.log($scope.Shed_Yard)

                        $scope.MNotFound = false;

                        $scope.AssessmentData(text);

                        $scope.AssessmentFound = true;
                    }

                    else {
                        $scope.MNotFound = true;
                        $scope.AssessmentFound = false;
                        $scope.previouAssValue = false;
                        $scope.dataLoading = false;
                    }

                })
                .catch(function () {
                    console.log('err')

                })
                .finally(function () {


                })


        }


        $scope.AssessmentData = function (text) {

            var data = {
                mani_No: text
            }


            //==========WareHouse Charge====================
            $http.post("/api/GetWarehouseForAssesment", data)

                .then(function (data) {
                    console.log(data.data)

                    var warehouse = data.data;
                    $scope.WareHouseRentDay = warehouse.WareHouseRent;

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
                    $scope.TotalWarehouseCharge = 0

                    console.log($scope.item_wise_charge)


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
                                $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.firstSlabDay * 2 * v.first_slab));
                            }
                            else {

                                $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.firstSlabDay * v.first_slab));
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
                                $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.firstSlabDay * 2 * v.first_slab));
                                $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.secondSlabDay * 2 * v.second_slab));
                            }
                            else {

                                $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.firstSlabDay * v.first_slab));
                                $scope.TotalWarehouseCharge += Math.ceil((v.item_quantity * $scope.secondSlabDay * v.second_slab));
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
                    else {
                        $scope.ShowFirstSlab = false;
                        $scope.ShowSecondSlab = false;
                        $scope.ShowThirdSlab = false;
                    }

                    $scope.TotalAmount += Math.ceil($scope.TotalWarehouseCharge);
                    /*
                     $scope.WarehouseChargeforPercentage = $scope.TotalWarehouseCharge;
                     $scope.TotalAmount += $scope.totalFirstSlabCharge;
                     $scope.TotalAmount += $scope.totalSecondSlabCharge;
                     $scope.TotalAmount += $scope.totalThirdSlabCharge;*/


                })
                .catch(function () {

                })
                .finally(function () {

                })


            //==========Handling Charge====================
            $http.post("/assessment/api/get-handling-and-some-other-dues", data)

                .then(function (data) {
                    console.log(data.data)

                    //===============================
                    //Transshipment Charge\
                    $scope.role_name=role_name;
                    if(role_name=='TransShipment'){
                        $scope.transshipment=true;
                        $scope.parishable=data.data[1][0].perishable_flag;

                        console.log('TransShipment');
                        if($scope.parishable==1){//Both unload and load charge will be
                            //---------unLoading
                            $scope.OffloadLabour = data.data[0][0].labor_unload;
                            $scope.chargeable_weight = Math.ceil(data.data[0][0].chargeable_weight / 1000);
                            $scope.OffloadLabourCharge = data.data[0][0].offloading_manual_charges;
                            $scope.TotalForOffloadLabour = ($scope.OffloadLabourCharge * $scope.chargeable_weight);
                            $scope.TotalAmount += $scope.TotalForOffloadLabour;
                            //---------loading
                            $scope.loadLabour = data.data[0][0].labor_load;
                            $scope.loadLabourCharge = data.data[0][0].offloading_manual_charges;
                            $scope.TotalForloadLabour = ($scope.loadLabourCharge * $scope.chargeable_weight);
                            console.log($scope.TotalForloadLabour)
                            $scope.TotalAmount += $scope.TotalForloadLabour;

                        }
                        else{//not parishable
                            $scope.loadLabour = data.data[0][0].labor_load;
                            $scope.chargeable_weight = Math.ceil(data.data[0][0].chargeable_weight / 1000);
                            $scope.loadLabourCharge = data.data[0][0].offloading_manual_charges;
                            $scope.TotalForloadLabour = ($scope.loadLabourCharge * $scope.chargeable_weight);
                            $scope.TotalAmount += $scope.TotalForloadLabour;

                        }
                    }
                    else {//not transhipment

                        console.log('no transhipmemn');

                        //offLoading charge-not transhipment
                        $scope.OffloadLabour = data.data[0][0].labor_unload;
                        $scope.OffLoadingEquip = data.data[0][0].equip_unload;
                        $scope.shifting_flag = data.data[0][0].shifting_flag;
                        $scope.approximate_delivery_type = data.data[0][0].approximate_delivery_type;
                        $scope.chargeable_weight = Math.ceil(data.data[0][0].chargeable_weight / 1000);

                        $scope.OffloadLabourCharge = data.data[0][0].offloading_manual_charges;
                        $scope.OffLoadingEquipCharge = data.data[0][0].offloading_equipment_charges;


                        $scope.TotalForOffloadLabour = ($scope.OffloadLabourCharge * $scope.OffloadLabour );
                        $scope.TotalForOffloadEquip = ($scope.OffLoadingEquipCharge * ($scope.shifting_flag ? 2 : 1) * $scope.OffLoadingEquip );

                        $scope.TotalAmount += $scope.TotalForOffloadLabour;
                        $scope.TotalAmount += $scope.TotalForOffloadEquip;

                        //Loading charge-not transhipment
                        /* $scope.loadLabour = data.data[0].labor_load;
                         $scope.loadingEquip = data.data[0].equip_load;


                         $scope.loadLabourCharge = data.data[0].offloading_manual_charges;
                         $scope.loadingEquipCharge = data.data[0].offloading_equipment_charges;


                         $scope.TotalForloadLabour = ( $scope.loadLabourCharge * $scope.loadLabour)
                         $scope.TotalForloadEquip = ( $scope.loadingEquipCharge * $scope.loadingEquip)

                         $scope.TotalAmount += $scope.TotalForloadLabour;
                         $scope.TotalAmount += $scope.TotalForloadEquip;*/

                        $scope.loadLabourCharge = data.data[0][0].offloading_manual_charges;
                        $scope.loadingEquipCharge = data.data[0][0].offloading_equipment_charges;

                        if ($scope.approximate_delivery_type == 0) {
                            console.log('labour')

                            $scope.TotalForloadLabour = ($scope.loadLabourCharge * $scope.chargeable_weight)
                            $scope.TotalAmount += $scope.TotalForloadLabour;
                        }

                        else if ($scope.approximate_delivery_type == 1) {
                            console.log('equipment');

                            $scope.TotalForloadEquip = ($scope.loadingEquipCharge * $scope.chargeable_weight);
                            $scope.TotalAmount += $scope.TotalForloadEquip;
                        }
                        else {

                        }


                    }


//Entrance fee

                    $scope.entranceFee = data.data[0][0].entrance_fee
                    $scope.totalForeignTruck = data.data[0][0].foreign_truck;
                    $scope.totalLocalTruck = data.data[0][0].local_truck;

                    $scope.totalForeignTruckEntranceFee = $scope.totalForeignTruck * $scope.entranceFee;
                    $scope.totalLocalTruckEntranceFee = $scope.totalLocalTruck * $scope.entranceFee;

                    $scope.TotalAmount += $scope.totalForeignTruckEntranceFee;
                    $scope.TotalAmount += $scope.totalLocalTruckEntranceFee;

//Carpenter Charges

                    $scope.carpenterChargesOpenClose = data.data[0][0].carpenter_charges_opening;
                    $scope.carpenterChargesRepair = data.data[0][0].carpenter_charges_repairing;
                    $scope.carpenterPackages = data.data[0][0].carpenter_packages;
                    $scope.carpenterRepairPackages = data.data[0][0].carpenter_repair_packages;

                    $scope.totalcarpenterChargesOpenClose = $scope.carpenterPackages * $scope.carpenterChargesOpenClose;
                    $scope.totalcarpenterChargesRepair = $scope.carpenterRepairPackages * $scope.carpenterChargesRepair;

                    $scope.TotalAmount += $scope.totalcarpenterChargesOpenClose;
                    $scope.TotalAmount += $scope.totalcarpenterChargesRepair;

                    //Document Charge
                    console.log(data.data[2][0]);
                    $scope.documentCharges = data.data[0][0].document_charges;
                    if(data.data[2][0]!='n'){

                        console.log(data.data[2][0]);

                        $scope.documentCharges = data.data[2][0].document_charges;
                        $scope.numberOfDocuments = data.data[2][0].number_of_document;
                        $scope.totalDocumentCharges =(parseFloat($scope.documentCharges) * $scope.numberOfDocuments );
                        $scope.TotalAmount +=(parseFloat($scope.documentCharges) * $scope.numberOfDocuments );


                        /*  var currentTime = new Date();
                         var delivery_date = new Date(data.data[0][0].old_approximate_delivery_date);

                         console.log(delivery_date);
                         var hasDocumentCharge = currentTime.getTime() > delivery_date.getTime();
                         console.log(hasDocumentCharge);
                         if (hasDocumentCharge) {
                         $scope.documentCharges = data.data[0][0].document_charges;
                         $scope.TotalAmount += parseFloat($scope.documentCharges);

                         console.log($scope.TotalAmount)
                         }*/

                    }

//Weighment measurement  Charges

                    $scope.weightment_measurement_charges = data.data[0][0].weightment_measurement_charges;

                    $scope.local_truck_weighment = data.data[0][0].local_truck_weighment;


                    $scope.weightmentChargesForeign = $scope.weightment_measurement_charges * $scope.totalForeignTruck * 2;
                    $scope.weightmentChargesLocal = $scope.weightment_measurement_charges * $scope.local_truck_weighment * 2;

                    $scope.TotalAmount += $scope.weightmentChargesForeign;
                    $scope.TotalAmount += $scope.weightmentChargesLocal;
                    console.log($scope.TotalAmount)

                })
                .catch(function () {

                })
                .finally(function () {

                })


//==================Haltage charges=================================
            $http.post("/api/GetHaltageChargesForAssesment", data)

                .then(function (data) {

                    console.log(data.data)

                    $scope.haltagesForeignTruck = data.data[0]
                    $scope.haltagesLocalTruck = data.data[1]
                    console.log(data.data[1]);
                    // $scope.haltagesTotalForeignTruck = data.data[0].length
                    //$scope.haltagesTotalLocalTruck = data.data[1].length

                    //=================calculate haltage charge

                    angular.forEach(data.data[0], function (v, k) {

                        if (v.haltage_days && v.holtage_charge_flag==0) {
                            $scope.haltagesTotalForeignTruck += 1;
                            console.log($scope.haltagesTotalForeignTruck);

                            $scope.TotalHaltageForeignCharge += (v.haltage_days * v.rate_of_charges);
                            $scope.haltagesTotalDayForeignTruck += v.haltage_days;

                        }
                    });
                    angular.forEach(data.data[1], function (v, k) {

                        if (v.haltage_day>0){
                            $scope.TotalHaltageLocalCharge += (v.haltage_day * v.rate_of_charges);
                            $scope.haltagesTotalDayLocalTruck += v.haltage_day;
                            $scope.haltagesTotalLocalTruck+=1;
                        }

                    })

                    $scope.TotalAmount += $scope.TotalHaltageForeignCharge;
                    $scope.TotalAmount += $scope.TotalHaltageLocalCharge;


                })
                .catch(function () {

                })
                .finally(function () {

                })

//==================Night Charges=================================
            $http.post("/api/GetNightChargesForAssesment", data)

                .then(function (data) {
                    console.log(data.data[0].total_foreign_truck_night)
                    // $scope.nightForeignTruck = data.data[0]
                    // $scope.nightLocalTruck = data.data[1]
                    if (data.data[0].total_foreign_truck_night != null) {

                        console.log(data.data[0].rate_of_night_charge)
                        $scope.nightTotalForeignTruck = data.data[0].total_foreign_truck_night
                        $scope.rate_of_night_charge = data.data[0].rate_of_night_charge;
                        $scope.TotalForeignNightCharge = $scope.rate_of_night_charge * 1;

                        $scope.TotalAmount += $scope.TotalForeignNightCharge;
                        console.log($scope.TotalAmount)
                    }
                    //    $scope.nightTotalLocalTruck = data.data[1].length

                    //$scope.Night_charges=data.data[0].Night_charges || data.data[1].Night_charges
                    //calculate Night charge


                    /*   angular.forEach(data.data[0], function (v, k) {

                     $scope.TotalForeignNightCharge += (1 * v.Night_charges)

                     })*/

                    /* angular.forEach(data.data[1], function (v, k) {

                     $scope.TotalLocalNightCharge += (1 * v.Night_charges)

                     })*/
                    //  console.log($scope.TotalLocalNightCharge)


                    //  $scope.TotalAmount += $scope.TotalLocalNightCharge;

                })
                .catch(function () {

                })
                .finally(function () {

                })


//=====================For Holtage for each Truck===================

            $http.post("/api/GetHolidayChargesForAssesment", data)

                .then(function (data) {
                    console.log(data.data)
                    $scope.holidayForeignTruck=null;
                    $scope.holidayLocalTruck=null;

                    //calculate Holiday charge
                    if (data.data[0].length>0) {
                        console.log('foreign')
                        console.log($scope.TotalAmount)
                        $scope.holidayForeignTruck = data.data[0];
                        $scope.holidayTotalForeignTruck = data.data[0].length;

                        angular.forEach(data.data[0], function (v, k) {
                            $scope.TotalForeignHolidayCharge += (1 * v.holiday_Charge)

                        })
                        $scope.TotalAmount += $scope.TotalForeignHolidayCharge;
                    }

                    if (data.data[1].length>0) {
                        console.log('local')
                        $scope.holidayLocalTruck = data.data[1];
                        $scope.holidayTotalLocalTruck = data.data[1].length;

                        angular.forEach(data.data[1], function (v, k) {
                            $scope.TotalLocalHolidayCharge += (1 * v.holiday_Charge)
                        })
                        $scope.TotalAmount += $scope.TotalLocalHolidayCharge;

                    }


                    console.log($scope.TotalAmount)
                    $scope.TotalAmount = Math.ceil($scope.TotalAmount)
                    console.log($scope.TotalAmount)
                    //$('#totalInWord').html(toWords(Math.ceil($scope.TotalAmount * 15 / 100 + $scope.TotalAmount)) + " Taka Only");
                    $('#totalInWord').html(amountToTextService.amountToText(Math.ceil($scope.TotalAmount * 15 / 100 + $scope.TotalAmount)) + " Taka Only");


                })
                .catch(function () {

                })
                .finally(function () {
                    $scope.dataLoading = false;
                })


        };//END manifestSearch()




        var blank = function () {

//Global Variable

            $scope.TotalAmount = 0;
            $scope.Manifest_id = 0;
            $scope.chargableTon = 0;
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
            $scope.nightTotalLocalTruck = 0


            $scope.TotalForeignNightCharge = 0;
            $scope.TotalLocalNightCharge = 0;
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

        };

//===============================================Varification
        $scope.verify = function() {
			var data = {
				manifestNo : $scope.manifestForCaption, //from details function
				verify_comm : $scope.verify_comm
			}
			$http.post("/api/verifyAssessmentVerification", data)
				.then(function(data){
					console.log(data);
					$scope.savingSuccess = "Assessment verification completed for manifest No "+ $scope.manifestForCaption;
					$scope.show = true;
					$scope.verify_comm = null;
				}).catch(function(){
					$scope.savingError = "Something went wrong."
				}).finally(function(){

				})
		}

		$scope.reject = function() {
			var data = {
				manifestNo : $scope.manifestForCaption, //from details function
				verify_comm : $scope.verify_comm
			}
			$http.post("/api/rejectAssessmentVerification", data)
				.then(function(data){
					console.log(data);
					//$scope.savingSuccess = "Assessment verification rejected for manifest No "+ manifestNo;
					$scope.savingError = "Assessment verification rejected for manifest No "+ $scope.manifestForCaption;
					$scope.show = true;
					$scope.verify_comm = null;
				}).catch(function(){
					$scope.savingError = "Something went wrong."
				}).finally(function(){

				})
		}

        $scope.clear = function(){
            //console.log(searchText);
            $scope.notFoundError = false;
            $scope.table = false;
            $scope.manifestTable = false;
            $scope.foreignTruckTable = false;
            $scope.localTruckTable = false;
            $scope.assessment = false;
            $scope.assessmentVerificationForm = false;
        }

        $scope.getStyle = function(value) {
            if(value == "2")
                return {'color':'red'};
            if(value == "0")
                return {'color':'blue'};
            if(value == "1")
                return {'color':'green'};
        }

	}).filter('offOrloadingFilter', function () {
        return function (val) {
            var offOrloading;
            if(val==1){
               return offOrloading='Equipment';
            } else if(val ==0) {
                return offOrloading='Labour';
            }
            return offOrloading='';
        }
    }).filter('ceil', function() {
    	return function(input) {
        	return Math.ceil(input);
    	}
	}).filter('verificationFilter', function () {
        return function (val) {
            var verification;
            if(val == 0){
               return verification='Not Verified';
            } else if(val == 1) {
                return verification='Verified';
            } else if(val == 2) {
                return verification='Rejected';
            }
            return verification='';
        }
    })
    .filter('stringToDate',function ($filter){
        return function (ele,dateFormat){
            return $filter('date')(new Date(ele),dateFormat);
        }
    })

    .filter('dateShort',function ($filter){
        return function (ele,dateFormat){
            return $filter('date')(new Date(ele),dateFormat);
        }
    })
