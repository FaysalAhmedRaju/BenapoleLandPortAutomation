angular.module('truckDeliveryEntryApp',['angularUtils.directives.dirPagination', 'customServiceModule'])
	.controller('truckDeliveryEntryController', function($scope, $http, $filter, manifestService,enterKeyService) {

        //capitalize Truck Delivery Entry Form
        $scope.$watch('searchKey', function (val) {

            $scope.searchKey = $filter('uppercase')(val);

        }, true);
        $scope.$watch('loading_unit', function (val) {

            $scope.loading_unit = $filter('uppercase')(val);

        }, true);

        $scope.$watch('package', function (val) {

            $scope.package = $filter('uppercase')(val);

        }, true);

        $scope.$watch('delivery_dt', function (val) {

            $scope.delivery_dt = $filter('uppercase')(val);

        }, true);

        $scope.$watch('labor_load', function (val) {

            $scope.labor_load = $filter('uppercase')(val);

        }, true);
        $scope.$watch('labor_package', function (val) {

            $scope.labor_package = $filter('uppercase')(val);

        }, true);
        $scope.$watch('equip_load', function (val) {

            $scope.equip_load = $filter('uppercase')(val);

        }, true);

        $scope.$watch('equip_name', function (val) {

            $scope.equip_name = $filter('uppercase')(val);

        }, true);
        $scope.$watch('equipment_package', function (val) {

            $scope.equipment_package = $filter('uppercase')(val);

        }, true);


		$scope.show = true;
        $scope.reportManifest =true;
        $scope.assesmentStatus =false;
        $scope.ButtonSave = true;
        $scope.serachField = true;
        //sreachby selection
         $scope.selection = {
                    singleSelect: null,
        };
        //console.log($scope.selection.singleSelect);
        $scope.select = function() {
            if($scope.selection.singleSelect=='truckNo') {
                $scope.placeHolder = 'Enter BD Truck No';
                $scope.serachField = false;
                $scope.reportManifest =true;
            } else if($scope.selection.singleSelect=='manifestNo'){
                $scope.placeHolder = 'Enter Manifest No';
                $scope.serachField = false;
                $scope.reportManifest =false;
            }/* else if($scope.selection.singleSelect=='yardNo'){
                $scope.placeHolder = 'Enter Yard No';
                $scope.serachField = false;
            }*/ else {
                $scope.placeHolder = null;
                $scope.serachField = true;
                $scope.reportManifest =true;
            }
        }

        enterKeyService.enterKey('#truckdeliveryEntryForm input ,#truckdeliveryEntryForm button')

  		//After Pressing Enter and found by searchKey 
        $scope.truckNoOrManifestSearch = function() {
            //console.log($scope.selection.singleSelect);
         	// var letters = /^[A-Za-z]+$/;
         	// if($scope.searchKey.match(letters)) {
                // if($scope.selection.singleSelect == 'truckNo') {
                //     $scope.errorType = 'The Truck Number is Invalid.';
                // } else if($scope.selection.singleSelect == 'manifestNo') {
                //     $scope.errorType = 'The Manifest Number is Invalid.';
                // }/* else if($scope.selection.singleSelect == 'yardNo') {
                //     $scope.errorType = 'The Yard No is Invalid.';
                // }*/else {
                //     $scope.errorType = 'Invalid Input. Please Select Search By Options.';
                // }
	          $scope.table = false;
		   // } else {
                //console.log($scope.truck_no);
                if($scope.selection.singleSelect == 'truckNo') {
    	         	var data = {
    	         		truck_no : $scope.searchKey,
                        search_by : 'truckNo'
    	         	}
                } else if($scope.selection.singleSelect == 'manifestNo') {
                    var data = {
                        manifest : $scope.searchKey,
                        search_by : 'manifestNo'
                    }
                    $scope.checkAssessmentStatus($scope.searchKey);
                }
	         	$http.post("/api/searchByTruckNoOrManifestNoJsonReturn",data)
	            	.then(function (data) {
	            		//console.log(data);
	            		//console.log(data.data.length);
	            		if(data.data.bdTruck.length > 0) {
	            			$scope.divTable = true;
                            $scope.indianTrucksTable = false;
	                		$scope.allBdTrucksData = data.data.bdTruck;
                            $scope.allIndianTrucksData = data.data.indianTruck;
	                		$scope.errorType = false;
                            //console.log($scope.allBdTrucksData);
                            $scope.totalLodingUnit = function() {
                                var total = 0;
                                for(var i=0; i < $scope.allBdTrucksData.length; i++) {
                                    //console.log($scope.allBdTrucksData);
                                    var LodingUnit = parseFloat($scope.allBdTrucksData[i].loading_unit);
                                    if(!isNaN(LodingUnit)) {
                                        total += LodingUnit;
                                    }
                                }
                                //console.log(total);
                                return total.toFixed(2);
                            }
                            //$scope.totalGrossWeight();

                            if($scope.allIndianTrucksData != null) {
                                //console.log($scope.allIndianTrucksData);
                                $scope.indianTrucksTable = true;
                                $scope.totalNetWeight = function() {  //Weightbridge Net Weight
                                    var total = 0;
                                    for(var i=0; i < $scope.allIndianTrucksData.length; i++) {
                                        //console.log($scope.allBdTrucksData);
                                        var NetWeight = parseFloat($scope.allIndianTrucksData[i].tweight_wbridge);
                                        if(!isNaN(NetWeight)) {
                                            total += NetWeight;
                                        }
                                    }
                                    //console.log(total);
                                    return total.toFixed(2);
                                }
                            }
	                	} else {
                            if($scope.selection.singleSelect == 'truckNo') {
	                		    $scope.errorType = 'The Truck Number is not registered.';
                                $('#errorType').show().delay(5000).slideUp(1000);
                            } else if($scope.selection.singleSelect == 'manifestNo') {
                                $scope.errorType = 'The Manifest Number is not registered.';
                                $('#errorType').show().delay(5000).slideUp(1000);
                            }/* else if($scope.selection.singleSelect == 'yardNo') {
                                $scope.errorType = 'The Yard is already full.';
                            }*/
	                		$scope.divTable = false;
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
                            $('#errorType').show().delay(5000).slideUp(1000);
            		}).finally(function () {
                			$scope.savingData = false;
                })
			//}
        }

        //After Clicking Posting Yard Entry Button
        $scope.update = function(bdtruck) {
            $scope.savingSuccess = false;
            $scope.savingError = false;
            if($scope.assesmentStatus!='Assessment Done') {
                //var message = $scope.assesmentStatus;
                bootbox.dialog({
                    message : $scope.assesmentStatus
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
        	$scope.truck_no = bdtruck.truck_no;
            $scope.id = bdtruck.id;
            //$scope.truck_type = bdtruck.truck_type;
            $scope.selectedStyle = bdtruck.id;
            // if(/*bdtruck.gweight != null && */bdtruck.package != null 
            //     && bdtruck.delivery_dt != null /*&& bdtruck.approve_dt != null */ 
            //     && bdtruck.loading_unit != null
            //     /*|| bdtruck.labor_load != null || bdtruck.equip_name != null 
            //     || bdtruck.equip_load != null*/) {
            //     //$scope.posted_yard_shed = truck.posted_yard_shed;
            //     //$scope.gweight = parseFloat(bdtruck.gweight);
            //     $scope.package = bdtruck.package;
            //     if(bdtruck.delivery_dt != null /*&& bdtruck.approve_dt != null*/) {
            //         var delivery_dt = bdtruck.delivery_dt.split(" ");
            //         $scope.delivery_dt = delivery_dt[0];
            //         //var approve_dt = bdtruck.approve_dt.split(" ");
            //         //$scope.approve_dt = approve_dt[0];
            //     }   
            //     //$scope.receive_by = truck.receive_by;
            //     // $scope.labor_load = parseFloat(bdtruck.labor_load);
            //     $scope.equip_name = bdtruck.equip_name;
            //     $scope.loading_flag = bdtruck.loading_flag.toString();
            //     $scope.loading_unit = parseFloat(bdtruck.loading_unit);
            //     // $scope.equip_load = parseFloat(bdtruck.equip_load);
            // } else {
            //     //$scope.posted_yard_shed = null;
            //     //$scope.gweight = null;
            //     $scope.package = null;
            //     $scope.delivery_dt = null;
            //     //$scope.approve_dt = null;
            //     //$scope.receive_by = truck.receive_by;
            //     $scope.labor_load = null;
            //     $scope.equip_name = null;
            //     $scope.equip_load = null;
            //     $scope.loading_flag = 0;
            //     $scope.loading_unit = null;
            // }
            if(bdtruck.loading_unit != null) {
                $scope.loading_unit = parseFloat(bdtruck.loading_unit);
            } else {
               $scope.loading_unit = null; 
            }
            if(bdtruck.package != null) {
                $scope.package = bdtruck.package;
            } else {
                $scope.package = null;
            }
            if (bdtruck.delivery_dt != null) {
                //var delivery_dt = bdtruck.delivery_dt.split(" ");
                //$scope.delivery_dt = delivery_dt[0];
                $scope.delivery_dt = bdtruck.delivery_dt;
            } else {
                $scope.delivery_dt = null;
            }
            // if($scope.loading_flag !=null) {
            //     $scope.loading_flag = bdtruck.loading_flag.toString();
            // } else {
            //     $scope.loading_flag = "0";
            // } 
            if(bdtruck.labor_load != null) {
                $scope.labor_load = parseFloat(bdtruck.labor_load);
            } else {
                $scope.labor_load = null;
            }
            if(bdtruck.labor_package != null) {
                $scope.labor_package = bdtruck.labor_package;
            } else {
                $scope.labor_package = null;
            }
            if(bdtruck.equip_load != null) {
                $scope.equip_load = parseFloat(bdtruck.equip_load);
            } else {
                $scope.equip_load = null;
            }
            if(bdtruck.equip_name != null) {
                $scope.equip_name = bdtruck.equip_name;
            } else {
                $scope.equip_name = null;
            }
            //bdtruck.equipment_package != null ? $scope.equipment_package = bdtruck.equipment_package 
                                           // : $scope.equipment_package = null;
            $scope.equipment_package = bdtruck.equipment_package != null ? bdtruck.equipment_package : null;
            if(bdtruck.equip_name != null) {
                $scope.equip_name = bdtruck.equip_name;
            } else {
                $scope.equip_name = null;
            }
        }

        //After Clicking Save Button
        $scope.save = function() {
            if(/*$scope.gweight == null || */$scope.package == null || 
                $scope.delivery_dt == null || /*$scope.approve_dt == null || */$scope.loading_unit == null
                /*$scope.labor_load == null ||$scope.equip_name == "" || 
                $scope.equip_load == null*/) {
                /*if($scope.posted_yard_shed == "") {
                    $scope.posted_yard_shed_required = true;
                }*/
                // if($scope.gweight == null) {
                //     $scope.gweight_required = true;
                // } else {
                //     $scope.gweight_required = false;
                // }
                if($scope.package == null) {
                   $scope.package_required = true;
                } else {
                    $scope.package_required = false;
                }
                if($scope.delivery_dt == null) {
                   $scope.delivery_dt_required = true;
                } else {
                    $scope.delivery_dt_required = false;
                }
                // if($scope.approve_dt == null) {
                //    $scope.approve_dt_required = true;
                // } else {
                //     $scope.approve_dt_required = false;
                // }
                /*if($scope.labor_load == null) {
                   $scope.labor_load_required = true;
                }
                if($scope.equip_name == "") {
                   $scope.equip_name_required = true;
                }
                if($scope.equip_load == null) {
                   $scope.equip_load_required = true;
                }*/
                if($scope.loading_unit == null) {
                    $scope.loading_unit_required = true;
                } else {
                    $scope.loading_unit_required = false;
                }
                return false;
            } else {
                //$scope.posted_yard_shed_required = false;
                //$scope.gweight_required = false;
                $scope.package_required = false;
                $scope.delivery_dt_required = false;
                //$scope.approve_dt_required = false;
                // $scope.labor_load_required = false;
                $scope.equip_name_required = false;
                // $scope.equip_load_required = false;
                $scope.loading_unit_required = false;
            }
            // if($scope.loading_flag==1 && $scope.equip_name == null) {
            //     $scope.equip_name_required = true;
            //     return false;
            // } else {
            //     $scope.equip_name_required = false;
            // }
        	// var today = new Date();
        	// var h = today.getHours();
        	// var m = today.getMinutes();
        	// var s = today.getSeconds();
            //console.log(isNaN($scope.receive_weight));
            // if($scope.loading_flag == 1) {
            // 	var data = {
            // 				id : $scope.id,
            //                 //gweight : $scope.gweight,
            //                 package : $scope.package,
            //                 delivery_dt : $scope.delivery_dt, //+" "+h+":"+m+":"+s,
            //                 //approve_dt : $scope.approve_dt +" "+h+":"+m+":"+s,
            //                 // labor_load : $scope.labor_load,
            // 				// equip_load : $scope.equip_load
            //                 loading_unit : $scope.loading_unit,
            //                 loading_flag : $scope.loading_flag,
            //                 equip_name : $scope.equip_name	
            // 	}
            // } else {
            //     var data = {
            //                 id : $scope.id,
            //                 //gweight : $scope.gweight,
            //                 package : $scope.package,
            //                 delivery_dt : $scope.delivery_dt, //+" "+h+":"+m+":"+s,
            //                 //approve_dt : $scope.approve_dt +" "+h+":"+m+":"+s,
            //                 // labor_load : $scope.labor_load,
            //                 // equip_load : $scope.equip_load
            //                 loading_unit : $scope.loading_unit,
            //                 loading_flag : $scope.loading_flag,
            //                 equip_name : null 
            //     }
            // }
        	//console.log(data);
            var data = {
                    id : $scope.id,
                    //gweight : $scope.gweight,
                    loading_unit : $scope.loading_unit,
                    package : $scope.package,
                    delivery_dt : $scope.delivery_dt, //+" "+h+":"+m+":"+s,
                    //approve_dt : $scope.approve_dt +" "+h+":"+m+":"+s,
                    labor_load : $scope.labor_load,
                    labor_package : $scope.labor_package,
                    equip_load : $scope.equip_load,
                    equip_name : $scope.equip_name,
                    equipment_package : $scope.equipment_package
                    //loading_flag : $scope.loading_flag,
                }
        	$http.post("/api/truckDeliveryEntryJson", data)
                .then(function (data) {
              		//console.log(data);
                    $scope.savingSuccess='Truck Delivery Entry Saved Successfully!';
                    $('#savingSuccess').show().delay(5000).slideUp(1000);
                    //$scope.gweight = null;
                    $scope.loading_unit = null;
                    $scope.package = null;
                    $scope.delivery_dt = null;
                    //$scope.approve_dt = null;
                    $scope.labor_load = null;
                    $scope.labor_package = null;
                    $scope.equip_load = null;
                    $scope.equip_name = null;
                    $scope.equipment_package = null;
                    //$scope.loading_flag = null;

                	$scope.show = true;
                    //$scope.label = false;
                    $scope.truckNoOrManifestSearch();
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
        }

        $scope.checkAssessmentStatus = function(manifest) {
            var data = {
                manifest : manifest
            }
            $http.post("/api/checkAssessmentStatus",data)
                .then(function(data){
                    if(data.data.length>0) {
                        $scope.assesmentStatus = data.data[0].assessmet_status;
                        //console.log($scope.assesmentStatus);
                    } else {
                        $scope.assesmentStatus = "Need Assessment";
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
        }

        //New Manifest Work [START]
        //service added 7-6-2017

        $scope.keyBoard = function(event) {
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
        }

        $scope.$watch('searchKey',function() {
            $scope.searchKey = manifestService.addYearWithManifest($scope.searchKey, $scope.keyboardFlag, $scope.selection.singleSelect);
        });
        
        //New Manifest Work [END]

        // $scope.handlingLabourOrEquipment= function() {
        //     //console.log($scope.handling);
        //     if($scope.loading_flag==1) {
        //         $scope.whenEquipment = true;
        //     } else {
        //         $scope.whenEquipment =false;
        //     }
        // }
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
    });