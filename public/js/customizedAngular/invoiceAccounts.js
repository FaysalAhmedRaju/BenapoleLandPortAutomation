angular.module('InvoiceApp', ['angularUtils.directives.dirPagination','customServiceModule'])
	.controller('InvoiceCtrl', function($http, $scope, manifestService) {

		//Manifest Like 222/3/2017 Add ?? 11/6/2017
		$scope.keyBoard = function(event) {
			$scope.keyboardFlag = manifestService.getKeyboardStatus(event);
		}

		$scope.$watch('searchText', function(){
			$scope.searchText = manifestService.addYearWithManifest($scope.searchText, $scope.keyboardFlag);
		});

		//Manifest Like 222/3/2017 Add ?? 11/6/2017

		var manifestNo = null;
		var totalAmmount = 0;
		$scope.manifestSearch = function(manifest) {
			$scope.dataLoading = true;
			manifestNo = manifest;
			$http.get("/api/getManifestDetailsForAccounts/"+manifest)
				.then(function(data){

					console.log(data);
					console.log(data.data.manifestReport.length);
					if(data.data.manifestReport.length > 0)
						$scope.ShowManifestDetails(data.data.manifestReport);
					//data.data.manifestReport.lenght > 0 ? $scope.ShowManifestDetails(data.data.manifestReport) : null;
					if(data.data.warehouse.length > 0)
						$scope.WarehouseCharge(data.data.warehouse);
					if(data.data.goodsNameTotalPkgMaxNet.length > 0)
						$scope.GoodsDetails(data.data.goodsNameTotalPkgMaxNet);
					if(data.data.foreignTruck.length > 0)
						$scope.ForeignTruckCharge(data.data.foreignTruck);
					if(data.data.localTruck.length > 0)
						$scope.LocalTruckCharge(data.data.localTruck);
					if(data.data.carpenterChargesOpenningOrClosing.length > 0)
						$scope.CarpenterOpenningOrClosingCharge(data.data.carpenterChargesOpenningOrClosing);
					if(data.data.carpenterChargesRepair.length > 0)
						$scope.CarpenterChargesRepairCharge(data.data.carpenterChargesRepair);
					if(data.data.holidayChargesFT.length > 0)
						$scope.HolidayChargesFTCharge(data.data.holidayChargesFT);
					if(data.data.holidayChargesLT.length > 0)
						$scope.HolidayChargesLTCharge(data.data.holidayChargesLT);
					if(data.data.nightChargesFT.length > 0)
						$scope.NightChargesFTCharge(data.data.nightChargesFT);
					if(data.data.nightChargesLT.length > 0)
						$scope.NightChargesLTCharge(data.data.nightChargesLT);
					if(data.data.holtageChargesFT.length > 0)
						$scope.HoltageChargesFTCharge(data.data.holtageChargesFT);
					//if(data.data.holtageChargesLT.length > 0)
						//$scope.HoltageChargesLTCharge(data.data.holtageChargesLT);
					if(data.data.documentationCharges.length > 0)
						$scope.DocumentationCharge(data.data.documentationCharges);
					if(data.data.weighmentChargesFT.length > 0)
						$scope.WeighmentChargesFTCharge(data.data.weighmentChargesFT);
					if(data.data.weighmentChargesLT.length > 0)
						$scope.WeighmentChargesLTCharge(data.data.weighmentChargesLT);
					if(data.data.offLoadingLabour.length > 0)
						$scope.OffLoadingLabourCharge(data.data.offLoadingLabour);
					if(data.data.offLoadingEquipment.length > 0)
						$scope.OffLoadingEquipmentCharge(data.data.offLoadingEquipment);
					if(data.data.loadingLabour.length > 0)
						$scope.LoadingLabourCharge(data.data.loadingLabour);
					if(data.data.loadingEquip.length > 0)
						$scope.LoadingEquipCharge(data.data.loadingEquip);
					if(data.data.totalAmount.length > 0)
						$scope.totalAmountFromDB(data.data.totalAmount);
				}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
				}).finally(function(){

				})
		}

		$scope.ShowManifestDetails = function(manifestDetails) {

			$scope.consignee = manifestDetails[0].consignee; //C&F
			$scope.manif_id = manifestDetails[0].id;
			$scope.ChallanNO = manifestDetails[0].challan_no;
			$scope.manifest = manifestDetails[0].manifest;
			$scope.manifestDate = manifestDetails[0].manifest_date;
			$scope.billOfEntryNo = manifestDetails[0].bill_of_entry_no;
			$scope.billOfEntryDate = manifestDetails[0].bill_of_entry_date;
			$scope.consigner = manifestDetails[0].consigner; //Exporter
			$scope.consignerAddress = manifestDetails[0].consigner; //Exporter address now is assigned as exporter Name
			$scope.postedYardShed = manifestDetails[0].posted_yard_shed; //For Last Foreign Truck
            $scope.today = new Date();

		}

		$scope.WarehouseCharge = function(warehouse) {
			$scope.FirstSlabDay = warehouse.FirstSlabDay != 0 ? warehouse.FirstSlabDay : null;
			$scope.FirstSlabCharge = warehouse.FirstSlabCharge != 0 ? warehouse.FirstSlabCharge : null;
			$scope.SecondSlabDay = warehouse.SecondSlabDay != 0 ? warehouse.SecondSlabDay : null;
			$scope.SecondSlabCharge = warehouse.SecondSlabCharge != 0 ? warehouse.SecondSlabCharge : null;
			$scope.thirdSlabDay = warehouse.thirdSlabDay != 0 ? warehouse.thirdSlabDay : null;
			$scope.ThirdSlabCharge = warehouse.ThirdSlabCharge  != 0 ? warehouse.ThirdSlabCharge : null;
			$scope.ReceiveWeight = warehouse.ReceiveWeight;

			$scope.totalFirstSlab = warehouse.FirstSlabDay != 0 ? 
					(warehouse.FirstSlabDay*warehouse.FirstSlabCharge*warehouse.ReceiveWeight).toFixed(2) : 0;
			if($scope.totalFirstSlab != 0) {
				var firstSlabTkOrPs = $scope.totalFirstSlab.split(".");
				$scope.firstSlabTk = firstSlabTkOrPs[0];
				$scope.firstSlabPs = firstSlabTkOrPs[1];
			} else {
				$scope.firstSlabTk = null;
				$scope.firstSlabPs = null;
			}

			$scope.totalSecondSlab = warehouse.SecondSlabDay != 0 ?
					(warehouse.SecondSlabDay*warehouse.SecondSlabCharge*warehouse.ReceiveWeight).toFixed(2) : 0;
			if($scope.totalSecondSlab != 0) {
				var secondSlabTkOrPs = $scope.totalSecondSlab.split(".");
				$scope.secondSlabTk = secondSlabTkOrPs[0];
				$scope.secondSlabPs = secondSlabTkOrPs[1];
			} else {
				$scope.secondSlabTk = null;
				$scope.secondSlabPs = null;
			}

			$scope.totalTrirdSlab = warehouse.thirdSlabDay != 0 ?
					(warehouse.thirdSlabDay*warehouse.ThirdSlabCharge*warehouse.ReceiveWeight).toFixed(2) : 0;
			if($scope.totalTrirdSlab != 0) {
				var thirdSlabTkOrPs = $scope.totalTrirdSlab.split(".");
				$scope.thirdSlabTk = thirdSlabTkOrPs[0];
				$scope.thirdSlabPs = thirdSlabTkOrPs[1];
			} else {
				$scope.thirdSlabTk = null;
				$scope.thirdSlabPs = null;
			}
			$scope.totalWareHouse = $scope.totalFirstSlab + $scope.totalSecondSlab + $scope.totalTrirdSlab;
			totalAmmount += $scope.totalWareHouse;


		}

		$scope.GoodsDetails = function(goods) {
			$scope.goodsName = goods[0].description_of_goods;
			$scope.pkg = goods[0].package_no;
			$scope.weight = goods[0].max_Net_Weight;
		}

		$scope.ForeignTruckCharge = function(foreignTruck) {
			$scope.totalFT = foreignTruck[0].unit != 0 ? foreignTruck[0].unit : null;
			$scope.FTcharge = $scope.totalFT != null ?  foreignTruck[0].charge_per_unit : null;
			$scope.FTSubHead = foreignTruck[0].sub_head_id;
			if(foreignTruck[0].tcharge != 0) {
				var FTTkorPs = foreignTruck[0].tcharge.split(".");
				$scope.FTTaka = FTTkorPs[0];
				$scope.FTPs = FTTkorPs[1];
				$scope.totalFTCharge = foreignTruck[0].tcharge;
				totalAmmount += foreignTruck[0].tcharge;
			} else {
				$scope.FTTaka = null;
				$scope.FTPs = null;
				$scope.totalFTCharge = 0;
			}

		}

		$scope.LocalTruckCharge = function(localTruck) {
			$scope.totalLT = localTruck[0].unit != 0 ? localTruck[0].unit :  null;
			$scope.LTcharge = $scope.totalLT != null ?  localTruck[0].charge_per_unit : null;
			$scope.LTSubHead = localTruck[0].sub_head_id;
			if(localTruck[0].tcharge != 0) {
				var LTTkorPs = localTruck[0].tcharge.split(".");
				$scope.LTTaka = LTTkorPs[0];
				$scope.LTPs = LTTkorPs[1];
				$scope.totalLTCharge = localTruck[0].tcharge;
				totalAmmount += localTruck[0].tcharge;
			} else {
				$scope.LTTaka = null;
				$scope.LTPs = null;
				$scope.totalLTCharge = 0;
			}
		}

		$scope.CarpenterOpenningOrClosingCharge = function(CCOpenningOrClosing) {
			$scope.totalCCOpeningOrClosing = CCOpenningOrClosing[0].unit != 0 ? CCOpenningOrClosing[0].unit : null;
			$scope.CCOpenningOrCLosingCharge = $scope.totalCCOpeningOrClosing != null ?  
					CCOpenningOrClosing[0].charge_per_unit : null;
			$scope.CCOpeningOrClosingSubHead = CCOpenningOrClosing[0].sub_head_id;
			if(CCOpenningOrClosing[0].tcharge != 0) {
				var CCOpenningOrClosingTkorPs = CCOpenningOrClosing[0].tcharge.split(".");
				$scope.CCOpenningOrClosingTk = CCOpenningOrClosingTkorPs[0];
				$scope.CCOpenningOrClosingPs = CCOpenningOrClosingTkorPs[1];
				$scope.totalCCOpenOrCloseCharge = CCOpenningOrClosingTkorPs[0].tcharge;
				totalAmmount += CCOpenningOrClosingTkorPs[0].tcharge;
			} else {
				$scope.CCOpenningOrClosingTk = null;
				$scope.CCOpenningOrClosingPs = null;
				$scope.totalCCOpenOrCloseCharge = 0;
			}

		}

		$scope.CarpenterChargesRepairCharge = function(CCRepair) {
			$scope.totalCCRepair = CCRepair[0].unit !=0 ? CCRepair[0].unit : null;
			$scope.CCRepairCharge = $scope.totalCCRepair != null ?  
					CCRepair[0].charge_per_unit : null;
			$scope.CCRepairSubHead = CCRepair[0].sub_head_id;
			if(CCRepair[0].tcharge != 0) {
				var CCRepairTkorPs = CCRepair[0].tcharge.split(".");
				$scope.CCRepairTk = CCRepairTkorPs[0];
				$scope.CCRepairPs = CCRepairTkorPs[1];
				$scope.totalCCRepairCharge = CCRepair[0].tcharge;
				totalAmmount += CCRepair[0].tcharge;
			} else {
				$scope.CCRepairTk = null;
				$scope.CCRepairPs = null;
				$scope.totalCCRepairCharge = 0;
			}
		}

		$scope.HolidayChargesFTCharge = function(holidayFT) {
			$scope.totalHolidayFT = holidayFT[0].unit != 0 ? holidayFT[0].unit : null;
			$scope.holidayChargeFT = $scope.totalHolidayFT != null ?  
					holidayFT[0].charge_per_unit : null;
			$scope.holidayFTSubHead = holidayFT[0].sub_head_id;
			if(holidayFT[0].tcharge != 0) {
				var holidayFTTkorPs = holidayFT[0].tcharge.split(".");
				$scope.holidayFTTk = holidayFTTkorPs[0];
				$scope.holidayFTPs = holidayFTTkorPs[1];
				$scope.totalholidayFTCharge = holidayFT[0].tcharge;
				totalAmmount += holidayFT[0].tcharge;
			} else {
				$scope.holidayFTTk = null;
				$scope.holidayFTPs = null;
				$scope.totalholidayFTCharge = 0;
			}
		}

		$scope.HolidayChargesLTCharge = function(holidayLT) {
			$scope.totalHolidayLT = holidayLT[0].unit !=0 ? holidayLT[0].unit : null;
			$scope.holidayChargeLT = $scope.totalHolidayLT != null ?  
					holidayLT[0].charge_per_unit : null;
			$scope.holidayLTSubHead = holidayLT[0].sub_head_id;
			if(holidayLT[0].tcharge != 0) {
				var holidayLTTkorPs = holidayLT[0].tcharge.split(".");
				$scope.holidayLTTk = holidayLTTkorPs[0];
				$scope.holidayLTPs = holidayLTTkorPs[1];
				$scope.totalholidayLTCharge = holidayLT[0].tcharge;
				totalAmmount += holidayLT[0].tcharge;
			} else {
				$scope.holidayLTTk = null;
				$scope.holidayLTPs = null;
				$scope.totalholidayLTCharge = 0;
			}
		}

		$scope.NightChargesFTCharge = function(nightFT) {
			$scope.totalNightFT = nightFT[0].unit != 0 ? nightFT[0].unit : null;
			$scope.nightChargeFT = $scope.totalNightFT != null ?  
					nightFT[0].charge_per_unit : null;
			$scope.nightFTSubHead = nightFT[0].sub_head_id;
			if(nightFT[0].tcharge != 0) {
				var nightFTTkorPs = nightFT[0].tcharge.split(".");
				$scope.nightFTTk = nightFTTkorPs[0];
				$scope.nightFTPs = nightFTTkorPs[1];
				$scope.totalNightFTCharge = nightFT[0].tcharge;
				totalAmmount += nightFT[0].tcharge;
			} else {
				$scope.nightFTTk = null;
				$scope.nightFTPs = null;
				$scope.totalNightFTCharge = 0;
			}
		}

		$scope.NightChargesLTCharge = function(nightLT) {
			$scope.totalNightLT = nightLT[0].unit != 0 ? nightLT[0].unit : null;
			$scope.nightChargeLT = $scope.totalNightLT != null ?  
					nightLT[0].charge_per_unit : null;
			$scope.nightLTSubHead = nightLT[0].sub_head_id;
			if(nightLT[0].tcharge != 0) {
				var nightLTTkorPs = nightLT[0].tcharge.split(".");
				$scope.nightLTTk = nightLTTkorPs[0];
				$scope.nightLTPs = nightLTTkorPs[1];
				$scope.totalNightLTCharge = nightLT[0].tcharge;
				totalAmmount += nightLT[0].tcharge;
			} else {
				$scope.nightLTTk = null;
				$scope.nightLTPs = null;
				$scope.totalNightLTCharge = 0;
			}


		}

		$scope.HoltageChargesFTCharge = function(holtageFT) {
			$scope.totalHoltageFT = holtageFT[0].unit != 0 ? holtageFT[0].unit : null;
			$scope.holtageChargeFT = $scope.totalHoltageFT != null ?  
					holtageFT[0].charge_per_unit : null;
			if($scope.totalHoltageFT != null) {
				var holtageOther = holtageFT[0].other_unit.split(".");
				$scope.holtageOtherFT = holtageOther[0];
			} else {
				$scope.holtageOtherFT = null;
			}
			$scope.holtageFTSubHead = holtageFT[0].sub_head_id;
			if(holtageFT[0].tcharge != 0) {
				var holtageFTTkorPs = holtageFT[0].tcharge.split(".");
				$scope.holtageFTTk = holtageFTTkorPs[0];
				$scope.holtageFTPs = holtageFTTkorPs[1];
				$scope.totalHoltageFTCharge = holtageFT[0].tcharge;
				totalAmmount += holtageFT[0].tcharge;
			} else {
				$scope.holtageFTTk = null;
				$scope.holtageFTPs = null;
				$scope.totalHoltageFTCharge = 0;
			}


		}

	/*	$scope.HoltageChargesLTCharge = function(holtageLT) {
			console.log(holtageLT);

			$scope.totalHoltageLT = holtageLT[0].unit != 0 ? holtageLT[0].unit : null;
			$scope.holtageChargeLT = $scope.totalHoltageLT != null ? holtageLT[0].charge_per_unit : null;
			if($scope.totalHoltageLT != null) {
				var holtageOther = holtageLT[0].other_unit.split(".");
				$scope.holtageOtherLT = holtageOther[0];
			} else {
				$scope.holtageOtherLT = null;
			}
			$scope.holtageLTSubHead = holtageLT[0].sub_head_id;
			if(holtageLT[0].tcharge != 0) {
				var holtageLTTkorPs = holtageLT[0].tcharge.split(".");
				$scope.holtageLTTk = holtageLTTkorPs[0];
				$scope.holtageLTPs = holtageLTTkorPs[1];
				$scope.totalHoltageLTCharge = holtageLT[0].tcharge;
				totalAmmount += holtageLT[0].tcharge;
			} else {
				$scope.holtageLTTk = null;
				$scope.holtageLTPs = null;
				$scope.totalHoltageLTCharge = 0;
			}


		}
*/
		$scope.WeighmentChargesFTCharge = function(weighmentFT) {


			$scope.totalWeightmentFT = weighmentFT[0].unit != 0 ? weighmentFT[0].unit : null;
			$scope.weightmentChargeFT = $scope.totalWeightmentFT != null ?  
					weighmentFT[0].charge_per_unit : null;
			$scope.weighmentFTSubHead = weighmentFT[0].sub_head_id;
			if(weighmentFT[0].tcharge != 0) {
				var weighmentFTTkorPs = weighmentFT[0].tcharge.split(".");
				$scope.weighmentFTTk = weighmentFTTkorPs[0];
				$scope.weighmentFTPs = weighmentFTTkorPs[1];
				$scope.totalWeighmentFTCharge = weighmentFT[0].tcharge;
				totalAmmount += weighmentFT[0].tcharge;
			} else {
				$scope.weighmentFTTk = null;
				$scope.weighmentFTPs = null;
				$scope.totalWeighmentFTCharge = 0;
			}
		}

		$scope.WeighmentChargesLTCharge = function(weighmentLT) {

			console.log(weighmentLT)
			$scope.totalWeightmentLT = weighmentLT[0].unit != 0 ? weighmentLT[0].unit : null;
			$scope.weightmentChargeLT = $scope.totalWeightmentLT != null ?  
					weighmentLT[0].charge_per_unit : null;
			$scope.weighmentLTSubHead = weighmentLT[0].sub_head_id;
			if(weighmentLT[0].tcharge != 0) {
				var weighmentLTTkorPs = weighmentLT[0].tcharge.split(".");
				$scope.weighmentLTTk = weighmentLTTkorPs[0];
				$scope.weighmentLTPs = weighmentLTTkorPs[1];
				$scope.totalWeighmentLTCharge = weighmentLT[0].tcharge;
				totalAmmount += weighmentLT[0].tcharge;
			} else {
				$scope.weighmentLTTk = null;
				$scope.weighmentLTPs = null;
				$scope.totalWeighmentLTCharge = 0;
			}
		}

		$scope.OffLoadingLabourCharge = function(offLoadingLabour) {
			$scope.totaloffLoadingLabour = offLoadingLabour[0].unit != 0 ? offLoadingLabour[0].unit : null;
			$scope.offLoadingLabourCharge = $scope.totaloffLoadingLabour != null ?  
					offLoadingLabour[0].charge_per_unit : null;
			$scope.offLoadingLabourSubHead = offLoadingLabour[0].sub_head_id;
			if(offLoadingLabour[0].tcharge != 0) {
				var offLoadingLabourTkorPs = offLoadingLabour[0].tcharge.split(".");
				$scope.offLoadingLabourTk = offLoadingLabourTkorPs[0];
				$scope.offLoadingLabourPs = offLoadingLabourTkorPs[1];
				$scope.totaloffLoadingLabourCharge = offLoadingLabour[0].tcharge;
				totalAmmount += offLoadingLabour[0].tcharge;
			} else {
				$scope.offLoadingLabourTk = null;
				$scope.offLoadingLabourPs = null;
				$scope.totaloffLoadingLabourCharge = 0;
			}
		}

		$scope.OffLoadingEquipmentCharge = function(offLoadingEquipment) {
			$scope.totaloffLoadingEquipment = offLoadingEquipment[0].unit != 0 ? offLoadingEquipment[0].unit : null;
			$scope.offLoadingEquipmentCharge = $scope.totaloffLoadingEquipment != null ?  
					offLoadingEquipment[0].charge_per_unit : null;
			$scope.offLoadingEquipmentSubHead = offLoadingEquipment[0].sub_head_id;
			if(offLoadingEquipment[0].tcharge != 0) {
				var offLoadingEquipmentTkorPs = offLoadingEquipment[0].tcharge.split(".");
				$scope.offLoadingEquipmentTk = offLoadingEquipmentTkorPs[0];
				$scope.offLoadingEquipmentPs = offLoadingEquipmentTkorPs[1];
				$scope.totaloffLoadingEquipmentCharge = offLoadingEquipment[0].tcharge;
				totalAmmount += offLoadingEquipment[0].tcharge;
			} else {
				$scope.offLoadingEquipmentTk = null;
				$scope.offLoadingEquipmentPs = null;
				$scope.totaloffLoadingEquipmentCharge = 0;
			}
		}

		$scope.LoadingLabourCharge = function(loadingLabour) {
			$scope.totalLoadingLabour = loadingLabour[0].unit != 0 ? loadingLabour[0].unit : null;
			$scope.loadingLabourCharge = $scope.totalLoadingLabour != null ?  
					loadingLabour[0].charge_per_unit : null;
			$scope.loadingLabourSubHead = loadingLabour[0].sub_head_id;
			if(loadingLabour[0].tcharge != 0) {
				var loadingLabourTkorPs = loadingLabour[0].tcharge.split(".");
				$scope.loadingLabourTk = loadingLabourTkorPs[0];
				$scope.loadingLabourPs = loadingLabourTkorPs[1];
				$scope.totalLoadingLabourCharge = loadingLabour[0].tcharge;
				totalAmmount += loadingLabour[0].tcharge;
			} else {
				$scope.loadingLabourTk = null;
				$scope.loadingLabourPs = null;
				$scope.totalLoadingLabourCharge = 0;
			}
		}

		$scope.LoadingEquipCharge = function(loadingEquip) {
			$scope.totalLoadingEquipment = loadingEquip[0].unit != 0 ? loadingEquip[0].unit : null;
			$scope.loadingEquipmentCharge = $scope.totalLoadingEquipment != null ?  
					loadingEquip[0].charge_per_unit : null;
			$scope.loadingEquipmentSubHead = loadingEquip[0].sub_head_id;
			if(loadingEquip[0].tcharge != 0) {
				var loadingEquipmentTkorPs = loadingEquip[0].tcharge.split(".");
				$scope.loadingEquipmentTk = loadingEquipmentTkorPs[0];
				$scope.loadingEquipmentPs = loadingEquipmentTkorPs[1];
				$scope.totalLoadingEquipmentCharge = loadingEquip[0].tcharge;
				totalAmmount += loadingEquip[0].tcharge;
			} else {
				$scope.loadingEquipmentTk = null;
				$scope.loadingEquipmentPs = null;
				$scope.totalLoadingEquipmentCharge = 0;
			}
		}

		function toWords(amount)
        {
            var words = new Array();
            words[0] = '';
            words[1] = 'One';
            words[2] = 'Two';
            words[3] = 'Three';
            words[4] = 'Four';
            words[5] = 'Five';
            words[6] = 'Six';
            words[7] = 'Seven';
            words[8] = 'Eight';
            words[9] = 'Nine';
            words[10] = 'Ten';
            words[11] = 'Eleven';
            words[12] = 'Twelve';
            words[13] = 'Thirteen';
            words[14] = 'Fourteen';
            words[15] = 'Fifteen';
            words[16] = 'Sixteen';
            words[17] = 'Seventeen';
            words[18] = 'Eighteen';
            words[19] = 'Nineteen';
            words[20] = 'Twenty';
            words[30] = 'Thirty';
            words[40] = 'Forty';
            words[50] = 'Fifty';
            words[60] = 'Sixty';
            words[70] = 'Seventy';
            words[80] = 'Eighty';
            words[90] = 'Ninety';
            amount = amount.toString();
            var atemp = amount.split(".");
            var number = atemp[0].split(",").join("");
            var n_length = number.length;
            var words_string = "";
            if (n_length <= 9) {
                var n_array = new Array(0, 0, 0, 0, 0, 0, 0, 0, 0);
                var received_n_array = new Array();
                for (var i = 0; i < n_length; i++) {
                    received_n_array[i] = number.substr(i, 1);
                }
                for (var i = 9 - n_length, j = 0; i < 9; i++, j++) {
                    n_array[i] = received_n_array[j];
                }
                for (var i = 0, j = 1; i < 9; i++, j++) {
                    if (i == 0 || i == 2 || i == 4 || i == 7) {
                        if (n_array[i] == 1) {
                            n_array[j] = 10 + parseInt(n_array[j]);
                            n_array[i] = 0;
                        }
                    }
                }
                value = "";
                for (var i = 0; i < 9; i++) {
                    if (i == 0 || i == 2 || i == 4 || i == 7) {
                        value = n_array[i] * 10;
                    } else {
                        value = n_array[i];
                    }
                    if (value != 0) {
                        words_string += words[value] + " ";
                    }
                    if ((i == 1 && value != 0) || (i == 0 && value != 0 && n_array[i + 1] == 0)) {
                        words_string += "Crores ";
                    }
                    if ((i == 3 && value != 0) || (i == 2 && value != 0 && n_array[i + 1] == 0)) {
                        words_string += "Lakhs ";
                    }
                    if ((i == 5 && value != 0) || (i == 4 && value != 0 && n_array[i + 1] == 0)) {
                        words_string += "Thousand ";
                    }
                    if (i == 6 && value != 0 && (n_array[i + 1] != 0 && n_array[i + 2] != 0)) {
                        words_string += "Hundred and ";
                    } else if (i == 6 && value != 0) {
                        words_string += "Hundred ";
                    }
                }
                words_string = words_string.split("  ").join(" ");
            }
            return words_string;
        }

		$scope.totalAmountFromDB = function(totalAmount) {
			$scope.totalAmmountFromDB = totalAmount[0].totalAmount;
			var totalAmmountFromDBTkPs = $scope.totalAmmountFromDB.split(".");
			$scope.totalAmmountFromDBTk = totalAmmountFromDBTkPs[0];
			$scope.totalAmmountFromDBPs = totalAmmountFromDBTkPs[1];
			$scope.vat = ((15/100)*$scope.totalAmmountFromDB).toFixed(2);
			var vatTkPs = $scope.vat.split(".");
			$scope.vatTk = vatTkPs[0];
			$scope.vatPs = vatTkPs[1];
			$scope.totalAmmountWithVat = Math.ceil(parseFloat($scope.totalAmmountFromDB) + parseFloat($scope.vat));

			$scope.words = toWords($scope.totalAmmountWithVat);
			$scope.dataLoading = false;
			console.log($scope.dataLoading);
		}

		$scope.saveChallan = function() {
			console.log("dasd");
			$http.get("/api/saveChallanForAccounts/"+$scope.manif_id)
				.then(function(data){
					//console.log(data);
					$scope.insertSuccessMsg = true;
                    $("#saveSuccess").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#saveSuccess").slideUp(7000);
                    });
				}).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
				}).finally(function(){
					$scope.savingData=false ;
				})
		}
	
	});