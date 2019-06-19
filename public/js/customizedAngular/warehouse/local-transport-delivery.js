var app = angular.module('deliveryApp', ['angularUtils.directives.dirPagination', 'customServiceModule']);

app.controller('deliveryCtrl', function ($scope, $http, $filter, manifestService, enterKeyService) {

    //Global Variable---------------

    //for role work
    $scope.role_name = role_name;
    $scope.manifest_no_fetch = $("#manifest_no_fetch").val();
    console.log($scope.manifest_no_fetch);

    $scope.cnfNameDisable = true;
    $scope.ChassisInformationForm = false;
    $scope.LocalTransportTruckForm = true;
    $scope.driver_name = "--";

   // $scope.req_partial_status = null;
    $scope.req_partial_number_list = [];


    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    //capitalize  Form field value
    $scope.$watch('searchText', function (val) {

        $scope.searchText = $filter('uppercase')(val);

    }, true);


    $scope.$watch('driver_name', function (val) {

        $scope.driver_name = $filter('uppercase')(val);

    }, true);


    $scope.$watch('labor_package', function (val) {

        $scope.labor_package = $filter('uppercase')(val);

    }, true);
    $scope.$watch('labor_package', function (val) {

        $scope.labor_package = $filter('uppercase')(val);

    }, true);
    $scope.$watch('equip_load', function (val) {

        $scope.equip_load = $filter('uppercase')(val);

    }, true);

    $scope.$watch('equipment_package', function (val) {

        $scope.equipment_package = $filter('uppercase')(val);

    }, true);
    $scope.$watch('equip_name', function (val) {

        $scope.equip_name = $filter('uppercase')(val);

    }, true);


    //New Manifest Start
    $scope.keyBoard = function (event) {
        $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
    }

    $scope.$watch('searchText', function () {
        $scope.searchText = manifestService.addYearWithManifest($scope.searchText, $scope.keyboardFlag, $scope.searchBy);
    });

    $scope.selfItemList = true;
    $scope.searchFld = true;
    $scope.getBtnActiveBySearch = true;
    $scope.buttonBdTruck = true;
    $scope.buttonSelfBd = true;
    $scope.truckAddModalShowBtn = false;
    $scope.saveManifestDataBtn = true;
    //GLOBAL variable===============get when search BY
    $scope.searchTextNotFoundTxt = null;
    $scope.getManiNo = null;
    $scope.getManiGWeight = null;
    $scope.getManiNweight = null;
    $scope.importerName = null;
    $scope.requestedLocalTransport = null;
    $scope.bdTruckTotalLoad = 0;
    $scope.localTransportSuccess = false;
    $scope.localTransportLength = 0;
    $scope.totalLoadedTruck = 0;
    $scope.totalLoadedVan = 0;

    $scope.billableWeight = 0;
    $scope.totalLoadedWeight = 0;
    $scope.totalLoadedPackage = 0;
    $scope.totalLoadedWeightBackUp = 0;
    $scope.totalLoadedPackageBackUp = 0;
    $scope.item_disable = true;
    $scope.previous_delivery_date = null;

    var today = new Date();
    var Y = today.getFullYear();
    var M = today.getMonth()+1;
    var D = today.getDate();

    if(today.getMonth()+1 < 10)
        M = "0"+M;
    if(today.getDate() < 10)
        D = "0"+D;
    $scope.today = Y+"-"+M+"-"+D;

    $scope.blankValueBeforeSearch = function () {
        console.log('called blank function');
        $scope.reportByManifestBtn = true;//enable reportbtn when serach by manifest
        $scope.searching = true;
        $scope.showBEInfoDiv = false;//show a div for showing manifest no and importer name to be sure

        $scope.be_no = null;
        $scope.labor_load = null;
        $scope.labor_package = null;
        $scope.equip_load = null;
        $scope.equipment_package = null;
        $scope.delivery_dt = null;
        $scope.equip_name = null;
        $scope.bd_truck_no = null;
        $scope.be_date = null;
        $scope.ain_no = null;
        $scope.cnf_name = null;
        $scope.buttonBdTruck = true;
        $scope.bdTruckIdForUpdate = null;
        $scope.carpenter_packages = null;
        $scope.carpenter_repair_packages = null;
        $scope.gate_pass_no = null;
        $scope.loadablePackage = 0;
        $scope.custom_release_order_no = null;
        $scope.custom_release_order_date = null;
        $scope.approximate_delivery_date = null;
        $scope.approximate_delivery_type = "1";
        $scope.localTransportSuccess = false;
        $scope.getManiID = null
        $scope.getManiNo = null;
        $scope.getManiGWeight = null;
        $scope.getManiNWeight = null;
        $scope.BdTruckTotalLoad = 0;
        $scope.LocalTruckWeight = 0;
        $scope.BdTruckNoFull = null;
        $scope.delivery_item = [];
        $scope.localTransportError = false;
        $scope.custom_approved_date = null;
        $scope.requestedLocalTransport = 0;
        $scope.localTransportLength = 0;
        $scope.totalLoadedTruck = 0;
        $scope.totalLoadedVan = 0;
        $scope.equip_load_add = 0;
        $scope.labor_load_add = 0;
        $scope.itemWeightTotal_add = 0;

        $scope.billableWeight = 0;
        $scope.totalLoadedWeight = 0;
        $scope.totalLoadedPackage = 0;
        $scope.totalLoadedWeightBackUp = 0;
        $scope.totalLoadedPackageBackUp = 0;

        $scope.localTrnsportGlobalError = false;
        $scope.localTrnsportGlobalNotification = false;
    }


    enterKeyService.enterKey('#bdTruckForm input ,#bdTruckForm button')

    $scope.get_requisition_status = function (manifest_no, status) {
        console.log(status);
        console.log(manifest_no);
        $scope.doSearch(manifest_no, status);
    };

//=============================================================================DoSearch=================================//=================================================//
    $scope.doSearch = function (searchText, req_partial_status=null) {
        console.log(searchText);
        console.log(req_partial_status);
        console.log($scope.req_partial_status);
        if($scope.req_partial_status){
            req_partial_status = $scope.req_partial_status;
        }
        $scope.blankValueBeforeSearch();

        var data = {
            mani_no: searchText,
            partial_status: req_partial_status
        }
        console.log(data);
        $http.post("/warehouse/api/delivery/delivery-local-transport-get-bill-of-entry-data", data)
            .then(function (data) {
                console.log(data.data);


                if (data.status == 203) {
                    console.log('in 203');
                    $scope.localTrnsportGlobalNotification = true;
                    $scope.localTrnsportGlobalNotificationTxt = data.data.noPermission;
                    $('#permissionError').show().delay(5000).slideUp(1000);
                    return;
                }

                if (data.data.length >= 1) {//manifest found
                    $scope.data = data.data[0];
                    console.log(data.data[0]);

                    $scope.gate_pass_no = $scope.data.gate_pass_no;
                    if ($scope.gate_pass_no == null) {
                        $scope.localTrnsportGlobalNotification = true;
                        $scope.localTrnsportGlobalNotificationTxt = "Gate Pass Is Not Found!";
                        $('.localTrnsportGlobalNotification').show().delay(5000).slideUp(1000);
                        // return;
                    }
                    $scope.dr_partial_status = $scope.data.dr_partial_status;
                    console.log($scope.dr_partial_status);
                    $scope.transport_type = $scope.data.local_transport_type.toString();
                    $scope.tran_type_check_self = $scope.data.local_transport_type.toString();
                    $scope.self_flag_from_manifest = $scope.data.self_flag.toString();
                    $scope.transport_type_check_self = $scope.data.local_transport_type.toString();
                    console.log( $scope.self_flag_from_manifest);
                    console.log($scope.transport_type)
                    console.log($scope.data.local_transport_type);
                    $scope.getManiID = $scope.data.m_id;
                    $scope.req_id = $scope.data.dr_id;
                    console.log($scope.req_id);
                    $scope.getManiNo = $scope.data.manifest;
                    $scope.getManiGWeight = $scope.data.m_gweight;
                    $scope.getManiNWeight = $scope.data.m_nweight;
                    $scope.getImporterName = $scope.data.importer;
                    $scope.requestedLocalTransport = $scope.data.total_transport_requested;
                    $scope.requestedLocalTruck = $scope.data.transport_truck;
                    $scope.requestedLocalVan = $scope.data.transport_van;


                    $scope.be_no = $scope.data.be_no;
                    $scope.be_date = $scope.data.be_date;
                    $scope.ain_no = $scope.data.ain_no;
                    $scope.cnf_name = $scope.data.cnf_name;
                    $scope.carpenter_packages = $scope.data.carpenter_packages;
                    $scope.carpenter_repair_packages = $scope.data.carpenter_repair_packages;
                    $scope.gate_pass_no = $scope.data.gate_pass_no;

                    $scope.total_partial_status = $scope.data.total_partial_status;
                    console.log($scope.total_partial_status);

                    for (var x = 0; x < $scope.total_partial_status; x++) {
                        $scope.req_partial_number_list[x] = x+1;
                    }
                    console.log($scope.req_partial_number_list);

                    if($scope.req_partial_status == null) {
                        $scope.req_partial_status = $scope.req_partial_number_list[$scope.total_partial_status-1];
                    }

                    console.log($scope.req_partial_status);
                    console.log($scope.req_partial_number_list);
                    $scope.custom_release_order_no = $scope.data.custom_release_order_no;
                    $scope.custom_release_order_date = $scope.data.custom_release_order_date;
                    $scope.approximate_delivery_date = $scope.data.approximate_delivery_date;
                    $scope.approximate_delivery_type = $scope.data.approximate_delivery_type != null ? $scope.data.approximate_delivery_type.toString() : "0";
                    $scope.approximate_labour_load = parseFloat($scope.data.approximate_labour_load);
                    $scope.approximate_equipment_load = parseFloat($scope.data.approximate_equipment_load);

                    $scope.billableWeight = $scope.approximate_labour_load + $scope.approximate_equipment_load;
                    console.log($scope.billableWeight);
                    $scope.loadablePackage = $scope.data.loadable_package;
                    console.log( $scope.loadablePackage);

                    $scope.custom_approved_date = $scope.data.custom_approved_date;
                    $scope.delivery_requisition_id = $scope.data.dr_id;
                    //fucntion call section
                    console.log($scope.transport_type)
                    $scope.LocalTransportFlag($scope.transport_type)
                    console.log($scope.delivery_requisition_id);



                } else {//manifest not found
                    $scope.localTrnsportGlobalNotification = true;
                    $scope.localTrnsportGlobalNotificationTxt = 'Manifest Not Found!';
                    $('#permissionError').show().delay(5000).slideUp(1000);
                }

            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            console.log('cache')

        }).finally(function () {

            $scope.searching = false;

        })

    }//===============================================================================doSearch End============


    //---for transshipment-if manifest not is from reveive form-------------------

    if ($scope.manifest_no_fetch != '//') {
        $scope.searchText = $scope.manifest_no_fetch;
        $scope.doSearch($scope.searchText);
    }


    $scope.LocalTransportFlag = function (flag) {
        $scope.localTransportSuccess = false;
        //console.log(flag);
        console.log('local flag: ' + flag);
        console.log('mani id: ' + $scope.getManiID);
        console.log($scope.approximate_delivery_date)
        console.log($scope.requestedLocalTransport);
        $scope.onVehicleTransportId = [];

        $scope.delivery_dt = $scope.approximate_delivery_date;

        if ($scope.requestedLocalTransport == 0 && flag != 2) {//total local tronsport provided in request
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = 'Your Given Local Transport Number is 0';
        }
        $scope.ChassisInformationForm = false;
        $scope.LocalTransportVanForm = false;
        $scope.LocalTransportTruckForm = false;
        $scope.bd_truck_no=null;

        if (flag == 0) {//means truck
            console.log($scope.transport_type);
            $scope.LocalTransportTruckForm = true;
            console.log($scope.getManiID);
            console.log($scope.req_id);
            $scope.getBdTruckData($scope.getManiID,$scope.req_id);// Function call with 1 param

            $scope.getBdTruckTypeData( $scope.getManiID);//get bd truck type
            $scope.getUndeliveredChassisListByManifest($scope.getManiID);

        } else if (flag ==1) {//van
            console.log("van");
            $scope.getBdTruckTypeData( $scope.getManiID);//get bd truck type
            $scope.LocalTransportVanForm = true;
            $scope.bd_truck_no='00';
            $scope.getLocalVanData($scope.getManiID,$scope.req_id)
        } else if (flag ==2) {//self
            console.log($scope.transport_type);
            console.log($scope.tran_type_check_self);

            $scope.ChassisInformationForm = true;
            $scope.LocalTransportTruckForm = false;
            $scope.selfItemList = false;
            if( $scope.tran_type_check_self == 2){
                $scope.getBdTruckTypeData( $scope.getManiID);
                $scope.selfItemList = true;
            }


            $scope.getUndeliveredChassisListByManifest($scope.getManiID);
            $scope.getSelfDeliveredChassisListByManifest($scope.getManiID);

        } else if(flag ==3) {//both
            $scope.LocalTransportTruckForm = true;
            $scope.getBdTruckData($scope.getManiID,$scope.req_id);// Function call with 1 param
            $scope.transport_type='0'
            $scope.getBdTruckTypeData( $scope.getManiID);//get bd truck type
            $scope.getUndeliveredChassisListByManifest($scope.getManiID);
        } else {

        }

    }
    //=========================Truck / Van delivery with or without on truck (chassis/trucktor on these)===============================================
    // $scope.delivery_item = [];
    $scope.getBdTruckTypeData = function (manifest) {//truck type loadinf
        console.log(manifest)
        $http.get("/warehouse/api/delivery/tuck-details-data/" + manifest)
            .then(function (data) {
                console.log('truck list data');
                console.log(data.data[0]);
                console.log(data.data[1]);
                $scope.item_delivery_list = data.data[1];
                console.log($scope.item_delivery_list);

                angular.forEach($scope.item_delivery_list, function (v, k) {
                    console.log(v);
                    console.log(k);
                            // $scope.delivery_item[k].checkbox = true;

                });



                // $scope.delivery_item = [];
                $scope.truck_type_data = data.data[0];
                console.log($scope.truck_type_data[0]);
              //  console.log($scope.truck_type_data)
                $scope.truck_type =$scope.cachedTruckTypeId ? $scope.cachedTruckTypeId: $scope.truck_type_data[0].truck_id;
                console.log($scope.truck_type)
            }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.statusText});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });
    }

    $scope.getBdTruckData = function (mId,req_id) {
        console.log(mId);
        console.log(req_id);
        $scope.BdTruckTotalLoad = 0;
        $scope.bdTruckTotalPackage = 0;
        $scope.BdTruckTotalPac = 0;
        $scope.bdTrucksdataLoading = true;
        $scope.localTransportLength = 0;
        $scope.allBdTrucksData = [];
        // $scope.localTransportSuccess = false;

        $http.get("/warehouse/api/delivery/get-local-transport-data/" + mId +"/"+ req_id)

            .then(function (data) {
                console.log(data.data)
                if(data.data.length == 0){
                    $scope.totalLoadedWeight= 0;
                    $scope.totalLoadedPackage = 0;
                }

                if (data.data.length < 1) {//if bd truck is empty
                   // $scope.LocalTruckWeight = $filter('ceil')(($scope.billableWeight) / $scope.requestedLocalTransport);

                    $scope.loadedDetails = data.data;


                    if ($scope.requestedLocalTruck == null) {
                        $scope.localTrnsportGlobalNotification = true;
                        $scope.localTrnsportGlobalNotificationTxt = 'No  Truck Is Requested!';
                        $('.localTrnsportGlobalNotification').show().delay(20000).slideUp(2000);
                        return;
                    }

                    $scope.getLoadedDetails(mId);
                    $scope.localTrnsportGlobalNotification = true;
                    $scope.localTrnsportGlobalNotificationTxt = 'You can input ' + $scope.requestedLocalTruck + ' Trucks';
                    $('.localTrnsportGlobalNotification').show().delay(20000).slideUp(2000);

                }else {
                    $scope.allBdTrucksData = data.data;
                    console.log($scope.allBdTrucksData);
                    $scope.totalLoadedWeight= $scope.allBdTrucksData[0].total_loaded_weight;
                    $scope.totalLoadedPackage = $scope.allBdTrucksData[0].total_loaded_package;
                    //Calculate bd Truck Total Load
                    /*angular.forEach(data.data, function (v, k) {
                        $scope.BdTruckTotalLoad += parseFloat(isNumeric(v.labor_load) ? v.labor_load : 0);
                        $scope.BdTruckTotalLoad += parseFloat(isNumeric(v.equip_load) ? v.equip_load : 0);
                        $scope.bdTruckTotalPackage += parseFloat(isNumeric(v.labor_package) ? v.labor_package : 0);
                        $scope.bdTruckTotalPackage += parseFloat(isNumeric(v.equipment_package) ? v.equipment_package : 0);

                    })*/;
                    $scope.totalLoadedWeightBackUp = $scope.totalLoadedWeight;
                    $scope.totalLoadedPackageBackUp = $scope.totalLoadedPackage;
                    console.log($scope.totalLoadedPackageBackUp);
                    var length = data.data.length;
                    $scope.localTransportLength = data.data.length;

                   // $scope.LocalTruckWeight = $filter('ceil')(($scope.billableWeight - $scope.totalLoadedWeight ) / ($scope.requestedLocalTransport - length));

                    if ($scope.requestedLocalTruck == length) {
                        console.log(length);
                        $scope.localTrnsportGlobalError = true;
                        $scope.localTrnsportGlobalErrorTxt = 'Your requested Local Truck is full';
                        $('.localTrnsportGlobalError').show().delay(5000).slideUp(2000);

                    }else {
                        $scope.localTrnsportGlobalNotification = true;
                        $scope.localTrnsportGlobalNotificationTxt = 'You can input ' + ($scope.requestedLocalTruck - length) + ' Trucks';
                        $('.localTrnsportGlobalNotification').show().delay(20000).slideUp(2000);
                        $scope.BdTruckNoFullBtnDisable = false;
                    }
                }
            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.statusText});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
        }).finally(function () {

            $scope.bdTrucksdataLoading = false;
        })
    }


    $scope.getLocalVanData = function (mId,req_id) {
        $scope.BdTruckTotalLoad = 0;
        $scope.bdTruckTotalPackage = 0;
        $scope.BdTruckTotalPac = 0;
        $scope.localVanDataLoading = true;
        $scope.localVanLength = 0;
        console.log(mId)
        $scope.allBdTrucksData = [];
        // $scope.localTransportSuccess = false;

        $http.get("/warehouse/api/delivery/local-transport-get-local-van-data/" + mId +"/"+ req_id)

            .then(function (data) {
                console.log(data.data)
                if(data.data.length == 0){
                    $scope.totalLoadedWeight= 0;
                    $scope.totalLoadedPackage = 0;
                }
                if (data.data.length < 1) {//if bd truck is empty
                    $scope.LocalTruckWeight = $filter('ceil')(($scope.billableWeight) / $scope.requestedLocalVan);
                    if ($scope.requestedLocalVan == null) {
                        $scope.localTrnsportGlobalNotification = true;
                        $scope.localTrnsportGlobalNotificationTxt = 'No Van Is Requested';
                        $('.localTrnsportGlobalNotification').show().delay(20000).slideUp(2000);
                       return;
                    }
                    $scope.getLoadedDetails(mId);
                    $scope.localTrnsportGlobalNotification = true;
                    $scope.localTrnsportGlobalNotificationTxt = 'You can input ' + $scope.requestedLocalVan + ' Van';
                    $('.localTrnsportGlobalNotification').show().delay(20000).slideUp(2000);


                }
                else {
                    $scope.localVanData = data.data;
                    console.log($scope.localVanData);
                    $scope.totalLoadedWeight= $scope.localVanData[0].total_loaded_weight;
                    $scope.totalLoadedPackage = $scope.localVanData[0].total_loaded_package;
                    //Calculate bd Truck Total Load
                 /*   angular.forEach(data.data, function (v, k) {
                        $scope.BdTruckTotalLoad += parseFloat(isNumeric(v.labor_load) ? v.labor_load : 0);
                        $scope.BdTruckTotalLoad += parseFloat(isNumeric(v.equip_load) ? v.equip_load : 0);
                        $scope.bdTruckTotalPackage += parseFloat(isNumeric(v.labor_package) ? v.labor_package : 0);
                        $scope.bdTruckTotalPackage += parseFloat(isNumeric(v.equipment_package) ? v.equipment_package : 0);

                    });*/


                    $scope.totalLoadedWeightBackUp = $scope.totalLoadedWeight;
                    $scope.totalLoadedPackageBackUp = $scope.totalLoadedPackage;

                    console.log($scope.totalLoadedPackageBackUp);

                    var length = data.data.length;
                    $scope.localVanLength = data.data.length;

                    $scope.LocalTruckWeight = $filter('ceil')(($scope.billableWeight - $scope.BdTruckTotalLoad) / ($scope.requestedLocalTransport - length));

                    /*  $scope.labor_load = $filter('ceil')((($scope.billableWeight - $scope.BdTruckTotalLoad) / ($scope.requestedLocalTransport - length)) / 2);
                     $scope.equip_load = $filter('ceil')((($scope.billableWeight - $scope.BdTruckTotalLoad) / ($scope.requestedLocalTransport - length)) / 2);
                     */
                    if ($scope.requestedLocalVan == length) {
                        $scope.localTrnsportGlobalError = true;
                        $scope.localTrnsportGlobalErrorTxt = 'Your requested Local Van Number is full';
                        $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);

                    }
                    else {
                        $scope.localTrnsportGlobalNotification = true;
                        $scope.localTrnsportGlobalNotificationTxt = 'You can input ' + ($scope.requestedLocalVan - length) + ' Trucks';
                        $('.localTrnsportGlobalNotification').show().delay(20000).slideUp(2000);
                        $scope.BdTruckNoFullBtnDisable = false;
                    }
                }
            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {

            $scope.localVanDataLoading = false;
        })
    }



    //get loaded details


    $scope.getLoadedDetails = function (mani_id) {//truck type loadinf
        $http.get("/warehouse/api/delivery/local-transport-get-total-loaded-details/"+mani_id)
            .then(function (data) {
                  console.log(data)
                $scope.loadedDetails = data.data;

                $scope.totalLoadedTruck = $scope.loadedDetails[0].total_truck;
                $scope.totalLoadedVan = $scope.loadedDetails[0].total_truck;

                $scope.totalLoadedWeight =  $scope.loadedDetails[0].total_loaded_weight;
                console.log($scope.totalLoadedWeight);
                $scope.totalLoadedPackage =  $scope.loadedDetails[0].total_loaded_package;
            }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.statusText});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
        }).finally(function () {

        })
    }


    $scope.checkAll = function (item) {
        console.log(item);

        $item_id = item.id;

        console.log($item_id);
        console.log($scope.delivery_item);
        $scope.item_disable = false;
        // $scope.delivery_item.push($item_id)
        // // $scope.delivery_item = [];
        // console.log(delivery_item);
    }


    var eqip_name = [
        'Fork Lift',
        'Crane'
    ];
    $(".equip_name").autocomplete({
        source: function (request, response) {
            console.log(eqip_name);
            var result = $.ui.autocomplete.filter(eqip_name, request.term);
            //$("#add").toggle($.inArray(request.term, result) < 0);
            response(result);
        }
    });

    $scope.getEquipmentWeight = function () {
        if (isNumeric($scope.LocalTruckWeight) && isNumeric($scope.labor_load)) {
            $scope.equip_load = $scope.LocalTruckWeight - $scope.labor_load;

        }
    }

    $scope.getLabourWeight = function () {

        if (isNumeric($scope.LocalTruckWeight) && isNumeric($scope.equip_load)) {
            $scope.labor_load = $scope.LocalTruckWeight - $scope.equip_load;
        }
        if ($scope.labor_load <= 0) {
            $scope.labor_load = null;
        }

    }


    $scope.onVehicleTransportId = [];

    $scope.delivery_item_wight = [];
    $scope.delivery_item_package = [];
    $scope.delivery_item = [];


    $scope.savelocalTransData = function (form) {
        // if($scope.assesmentStatus == "Assessment Done") {
        //     $scope.assesmentDoneError = true;
        //     return;
        // }
        // $scope.delivery_item = [];
        if($scope.transport_type_check_self == 2){
            // $scope.submittedLocalTransportBtn = true;
            $scope.localTransportError = true;
            $scope.localTransportErrorMsgTxt = 'Your Request Is Not Valid';
            $('#localTransportError').show().delay(5000).slideUp(2000);
            return;
        }

        // console.log($scope.onVehicleTransportId);
        // console.log($scope.self_flag_from_manifest);
        // console.log($scope.tran_type_check_self);
        // console.log($scope.onVehicleTransportId.length == 0)
        if(($scope.self_flag_from_manifest == 1) && ($scope.tran_type_check_self == 0)){
            if($scope.onVehicleTransportId.length == 0){
                $scope.localTransportError = true;
                $scope.localTransportErrorMsgTxt = 'Please Select Chassis';
                $('#localTransportError').show().delay(5000).slideUp(2000);
                return;
            }

        }
        console.log($scope.delivery_dt);
        console.log($scope.today);
        if(new Date($scope.today) > new Date($scope.delivery_dt)) {
            $scope.localTransportError = true;
            $scope.localTransportErrorMsgTxt = 'You Can\'t Input Transport, The Manifest Already Delivered At ' + $scope.delivery_dt;
            $('#localTransportError').show().delay(5000).slideUp(2000);
            return;
        }
        //return;
        console.log($scope.delivery_item);
        console.log( $scope.transport_type_check_self );//from requisition
        console.log($scope.transport_type);
        console.log($scope.tran_type_check_self);
        console.log( $scope.self_flag_from_manifest);




        $scope.countCheckBox = 0;
        angular.forEach($scope.delivery_item, function (v, k) {
           // console.log(v.checkbox);
            if(v.checkbox == true){
                $scope.countCheckBox ++ ;
            }

        })
       // console.log($scope.countCheckBox);
        if($scope.countCheckBox == 0){
            $scope.localTransportError = true;
            $scope.localTransportErrorMsgTxt = 'Please Select Item';
            $('#localTransportError').show().delay(5000).slideUp(2000);
            return;
        }

        $scope.submittedLocalTransportBtn = false;

        if ($scope.getManiID == null) {
            $scope.localTransportError = true;
            $scope.localTransportErrorMsgTxt = 'Please Search With Manifest First!';
            $('#localTransportError').show().delay(5000).slideUp(2000);
            return;
        }

        if( $scope.self_flag_from_manifest == 1){
            form.$invalid = false;

        }else {

                console.log($scope.requestedLocalTruck);
                console.log($scope.localTransportLength);
                console.log($scope.bdTruckIdForUpdate);
                console.log($scope.transport_type);

            if (($scope.requestedLocalTruck <= $scope.localTransportLength) && !$scope.bdTruckIdForUpdate && $scope.transport_type=='0') {
                $scope.localTransportError = true;
                $scope.localTransportErrorMsgTxt = 'Your requested Local Truck/Van is full';
                $('.localTransportError').show().delay(5000).slideUp(2000);
                return;
            }
            if (($scope.requestedLocalVan <= $scope.localVanLength) && !$scope.bdTruckIdForUpdate && $scope.transport_type=='1') {
                $scope.localTransportError = true;
                $scope.localTransportErrorMsgTxt = 'Your requested Local Truck/Van is full';
                $('.localTransportError').show().delay(5000).slideUp(2000);
                return;
            }

            var  itemWeightTotal = 0;
            var  itemPackageTotal = 0;
            angular.forEach($scope.delivery_item, function (v, k) {
                console.log(v);
                if(v.loadable_weight != undefined){
                    itemWeightTotal = itemWeightTotal + parseInt(v.loadable_weight);
                }
                if(v.loadable_package != undefined){
                    itemPackageTotal = itemPackageTotal + parseInt(v.loadable_package);
                }
            })
            $scope.itemWeight = itemWeightTotal;
            $scope.itemPackage = itemPackageTotal;
            $scope.equip_load_add = 0;
            $scope.labor_load_add = 0;
            $scope.itemWeightTotal_add = 0;

            $scope.labor_package_add = 0;
            $scope.equipment_package_add = 0;
            $scope.itemPackageTotal_add = 0;


            if($scope.equip_load != null || $scope.equip_load != undefined){
                $scope.equip_load_add = $scope.equip_load;
            }

            if($scope.labor_load != null || $scope.labor_load != undefined){
               $scope.labor_load_add = $scope.labor_load;
            }

            if(itemWeightTotal != null || itemWeightTotal != undefined){
                $scope.itemWeightTotal_add = itemWeightTotal;
            }

            if($scope.labor_package != null || $scope.labor_package != undefined){
                $scope.labor_package_add = $scope.labor_package;
            }

            if($scope.equipment_package != null || $scope.equipment_package != undefined){
                $scope.equipment_package_add = $scope.equipment_package;
            }

            if(itemPackageTotal != null || itemPackageTotal != undefined){
                $scope.itemPackageTotal_add = itemPackageTotal;
            }


            if ((($scope.billableWeight - $scope.totalLoadedWeight) < ($scope.equip_load_add + $scope.labor_load_add))) {
                $scope.localTransportError = true;
                $scope.localTransportErrorMsgTxt = "Can't Deliver more than Weight Loadable!";
                $('#localTransportError').show().delay(5000).slideUp(2000);
                return;
            }



            if ((($scope.billableWeight - $scope.totalLoadedWeight) < ( $scope.itemWeightTotal_add))) {
                $scope.localTransportError = true;
                $scope.localTransportErrorMsgTxt = "Can't Deliver more than Weight!";
                $('#localTransportError').show().delay(5000).slideUp(2000);
                return;
            }


            if ((($scope.loadablePackage - $scope.totalLoadedPackage) < ($scope.labor_package_add + $scope.equipment_package_add + $scope.itemPackageTotal_add))) {
                $scope.localTransportError = true;
                $scope.localTransportErrorMsgTxt = "Can't Deliver more than Loadable Packages!";
                $('#localTransportError').show().delay(5000).slideUp(2000);
                return;
            }


            if ((($scope.loadablePackage - $scope.totalLoadedPackage) < ($scope.itemPackageTotal_add))) {
                $scope.localTransportError = true;
                $scope.localTransportErrorMsgTxt = "Can't Deliver more than Package!";
                $('#localTransportError').show().delay(5000).slideUp(2000);
                return;
            }


            if ($scope.labor_load && $scope.labor_load < 0) {
                $scope.localTransportError = true;
                $scope.localTransportErrorMsgTxt = "Please Check Load Weight";
                $('#localTransportError').show().delay(5000).slideUp(2000);
                return;
            }
            if ($scope.equip_load && $scope.equip_load < 0) {
                $scope.localTransportError = true;
                $scope.localTransportErrorMsgTxt = "Please Check Load Weight";
                $('#localTransportError').show().delay(5000).slideUp(2000);
                return;
            }
        }


        if($scope.transport_type=='1'){
            console.log("van");
            $scope.truck_type='00';
            $scope.truck_type = null;
            if($scope.labor_load || $scope.equip_load != null){
                form.$invalid = false;
            }

        }
        console.log('if');
        console.log(form.$invalid);
        if (form.$invalid) {
            console.log('if');
            $scope.submittedLocalTransportBtn = true;
            $scope.localTransportError = true;
            $scope.localTransportErrorMsgTxt = 'Your Request Is Not Valid';
            $('#localTransportError').show().delay(5000).slideUp(2000);

        } else {
            console.log('else');
            $scope.savingLocalTransportData = true;
            var onVehicleTransportId_array = [];
            angular.forEach($scope.onVehicleTransportId, function (selected, id) {
                if (selected) {
                    console.log(id);
                    onVehicleTransportId_array.push(id)
                }
            });
            var all_onVehicleTransportIds = onVehicleTransportId_array.join();
            console.log(all_onVehicleTransportIds);

            console.log($scope.dr_partial_status);
            $scope.cachedTruckTypeId=$scope.truck_type;
            console.log($scope.bdTruckIdForUpdate);
            var data = {
                truck_no: $scope.bd_truck_no,
                truck_type_id: $scope.truck_type,
                delivery_requisition_id:$scope.delivery_requisition_id,
                manf_id: $scope.getManiID,
                transport_type: $scope.transport_type,
                driver_name: $scope.driver_name,
                labor_load: $scope.labor_load, //==null ? 0: $scope.labor_load,
                labor_package: $scope.labor_package,
                equip_load: $scope.equip_load,// ==null ? 0: $scope.equip_load,
                equipment_package: $scope.equipment_package,
                equip_name: $scope.equip_name,
                delivery_dt: $scope.previous_delivery_date == null ? $scope.today : $scope.previous_delivery_date,
                // haltage_day: $scope.haltage_day,

                bd_truck_id: $scope.bdTruckIdForUpdate,
                onVehicleTransportId: $scope.onVehicleTransportId,
                all_onVehicleTransportIds: all_onVehicleTransportIds,
                delivery_item_list: $scope.delivery_item,
                dr_partial_status: $scope.dr_partial_status

            }
            console.log($scope.transport_type);
            if($scope.transport_type == '1'){//van
                 data = {
                    manf_id: $scope.getManiID,
                    bd_truck_id: $scope.bdTruckIdForUpdate,
                    truck_no: $scope.bd_truck_no,
                    onVehicleTransportId: null,
                     delivery_requisition_id:$scope.delivery_requisition_id,
                    truck_type_id: null,
                    all_onVehicleTransportIds: null,
                    driver_name: $scope.driver_name,
                    labor_load: $scope.labor_load,
                    labor_package: $scope.labor_package,
                    equip_load: $scope.equip_load,
                    equipment_package: $scope.equipment_package,
                    equip_name: $scope.equip_name,
                    delivery_dt: $scope.previous_delivery_date == null ? $scope.today : $scope.previous_delivery_date,
                    // haltage_day: $scope.haltage_day,
                    transport_type: $scope.transport_type,
                     delivery_item_list: $scope.delivery_item,
                     dr_partial_status: $scope.dr_partial_status

                }
            }

            console.log(data)
            // return;
            $http.post("/warehouse/api/delivery/delivery-save-local-transport-data", data)

                .then(function (data) {
                    console.log(data);
                    $('.localTransportError').hide();
                    console.log(data.status);

                    if (data.status == 200) {//saved
                        $scope.localTransportSuccess = true;
                        $scope.localTransportSuccessMsgTxt = 'Successfully Saved';
                        $("#localTransportSuccess").show().delay(5000).slideUp(2000);
                    }
                    else if (data.status == 201) {//'updated'
                        $scope.localTransportSuccess = true;
                        $scope.localTransportSuccessMsgTxt = 'Successfully Updated';

                        $('#localTransportSuccess').show().delay(5000).slideUp(2000);
                    }
                    else {
                        console.log(data.data)
                        $scope.localTransportError = true;
                        $scope.localTransportErrorMsgTxt = 'Something Went Wrong!';
                        $('#localTransportError').show().delay(5000).slideUp(2000);
                        return;
                    }
                   //  $('.localTransportSuccess').show().delay(5000).slideUp(2000);
                   // $scope.localTransportSuccess = false;
                    $scope.bd_truck_no = null;
                    $scope.driver_name = "--";
                    $scope.labor_load = null;
                    $scope.labor_package = null;
                    $scope.equip_load = null;
                    $scope.equipment_package = null;
                    $scope.equip_name = null;
                    $scope.equip_load_add = 0;
                    $scope.labor_load_add = 0;
                    $scope.itemWeightTotal_add = 0;
                    //  $scope.delivery_dt = null;
                    //$scope.weightment_flag = '0';
                    // $scope.haltage_day = null;
                    $scope.previous_delivery_date = null;

                    $scope.bdTruckIdForUpdate = null;
                    $scope.submittedLocalTransportBtn = false;
                    $scope.onVehicleTransportId = [];
                    $scope.delivery_item = [];
                    console.log($scope.delivery_item)
                    console.log($scope.transport_type);
                    if($scope.transport_type==0){
                        $scope.getBdTruckData($scope.getManiID,$scope.req_id);
                        $scope.getBdTruckTypeData( $scope.getManiID);
                        $scope.getUndeliveredChassisListByManifest($scope.getManiID);
                    }
                    if($scope.transport_type==1){//van
                        $scope.bd_truck_no='00';
                        $scope.getLocalVanData($scope.getManiID,$scope.req_id);
                        $scope.getBdTruckTypeData( $scope.getManiID);
                    }
                    $scope.truck_type=$scope.cachedTruckTypeId;

                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.localTransportError = true;
                $scope.localTransportErrorMsgTxt = 'Something Went Wrong!';
                $('#localTransportError').show().delay(5000).slideUp(2000);

            }).finally(function () {
                $scope.savingLocalTransportData = false;
            })
        }
    }


    $scope.editLocalTransport = function (i) {

        console.log($scope.delivery_item);
        console.log(i);
        console.log(i.item_delivery)
        console.log($scope.item_delivery_list);

        var item_array_for_checked = null;

        item_array_for_checked = [];
        item_array_for_checked = i.item_delivery;

        angular.forEach($scope.item_delivery_list, function (all, k) {
            console.log(all);
            angular.forEach(item_array_for_checked, function (sel, j) {
                if(all.id == sel.id) {
                   $scope.delivery_item[k].checkbox = true;
                   // $scope.delivery_item[k].id = sel.id;
                   $scope.delivery_item[k].loadable_weight = sel.loadable_weight;
                    $scope.delivery_item[k].loadable_package = sel.loadable_package;
                }

            });

        });
         item_array_for_checked = null;
        var all_ids = i.chassis_ids_on_vehicle;

        console.log(i.chassis_ids_on_vehicle);
        var array=null;
        if (all_ids)  array = all_ids.split(',');
        console.log(array);


        if (i.chassis_on_this_vehicle > 0) {
            $http.get("/warehouse/api/delivery/chassis-list-for-local-transport/" + i.bd_truck_id)

                .then(function (data) {
                    console.log(data.data);
                    $scope.undelivered_chassis = data.data;
                    console.log($scope.undelivered_chassis);
                    angular.forEach(array, function (v, k) {
                        console.log(v)
                        console.log(k)
                        $scope.onVehicleTransportId[v] = true//all_ids.split(',').map(Number);
                    })
                    console.log($scope.onVehicleTransportId);

                    //   $scope.onVehicleTransportId=i.chassis_ids_on_vehicle.split(",")
                    // console.log(i.chassis_ids_on_vehicle.split(","));

                }).catch(function (r) {

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {


            })
        }

        $scope.saveBdTruckSuccess = false;
        $scope.savingBdTruckError = false;
        $scope.BdTruckNoFullBtnDisable = false;

        $scope.totalLoadedWeight = $scope.totalLoadedWeightBackUp - ((isNumeric(i.labor_load) ? i.labor_load : 0) + (isNumeric(i.equip_load) ? i.equip_load : 0));
        $scope.totalLoadedPackage = $scope.totalLoadedPackageBackUp  - ((isNumeric(i.labor_package) ? parseFloat(i.labor_package) : 0) + (isNumeric(i.equipment_package) ? parseFloat(i.equipment_package) : 0));
        console.log($scope.totalLoadedPackage);

        console.log(i.truck_no);
        if(i.truck_no != null){
            var truckNOSplit = i.truck_no.split("-");

            $scope.bd_truck_no = truckNOSplit[0];
        }



        $scope.driver_name = i.driver_name;
        $scope.truck_type = i.truck_type_id;


        $scope.labor_load = i.labor_load;
        $scope.labor_package = parseInt(i.labor_package);
        $scope.equip_load = i.equip_load;
        $scope.equipment_package = parseInt(i.equipment_package);

        // $scope.haltage_day = i.haltage_day != 0 ? parseInt(i.haltage_day) : "";
        //$scope.weightment_flag = i.weightment_flag ? i.weightment_flag.toString() : '0';

        $scope.loading_unit = i.loading_unit;
        $scope.equip_name = i.equip_name;
        console.log(i.delivery_req_dt);
        $scope.delivery_dt = i.delivery_req_dt;
        $scope.previous_delivery_date = i.delivery_req_dt;
        console.log(i.bd_truck_id);
        $scope.bdTruckIdForUpdate = i.bd_truck_id;


    }


    $scope.editSelfTransportDelivery = function (i) {
        $scope.bdSelfIdForUpdate = true;
        $scope.chassis_edit = true;
        console.log(i);
        $scope.update_chassis_del_id = i.id;
        console.log($scope.update_chassis_del_id);
        // $scope.selfTransportId = i.chassis_no;
        // console.log(i.chassis_details_id);
        // console.log(i.chassis_no);
        // console.log(i.chassis_type);
        $scope.delivery_dt = i.delivery_dt;
        $scope.previous_delivery_date = i.delivery_dt;
        $scope.selfTransportDriverName = i.driver_name;
        $scope.selfTransportDriverCard = i.driver_card;

        console.log($scope.delivery_item);

        console.log(i.item_delivery)
        console.log($scope.item_delivery_list);

        var item_array_for_checked = null;

        item_array_for_checked = [];

        item_array_for_checked = i.item_delivery;

        console.log(item_array_for_checked);

        angular.forEach($scope.item_delivery_list, function (all, k) {
            console.log(all);
            angular.forEach(item_array_for_checked, function (sel, j) {
                if(all.id == sel.id) {
                    $scope.delivery_item[k].checkbox = true;

                }

            });

        });

    }

    $scope.chassis_edit = false;


    $scope.deleteLocalTransportConfirm = function (i) {
        //bd_truck_id m_id
        console.log(i.bd_truck_id)

       // return

        $http.get("/warehouse/api/delivery/delete-local-transport-data/" + i.bd_truck_id)
            .then(function (data) {
                console.log(data);
                $scope.localTransportSuccess = true;
                $scope.localTransportSuccessMsgTxt = 'Successfully Deleted';
                $("#localTransportSuccess").show().delay(5000).slideUp(2000);

                // $scope.bDTruckdeletesuccessmsg = true;
                // $scope.savingBdTruckError = false;
                // $scope.saveBdTruckSuccess = false;
                //
                // $('#bDTruckdeletesuccessmsg').show().delay(5000).slideUp(2000);

                console.log($scope.transport_type)
                if($scope.transport_type == 0){
                    $scope.getBdTruckData($scope.getManiID,$scope.req_id);
                }
                if($scope.transport_type == 1){
                    $scope.getLocalVanData($scope.getManiID,$scope.req_id);
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


    //BD truck type Entry-------------------

    $scope.saveBdTruckType = function (form) {
        console.log(form.$invalid);
        if (form.$invalid) {
            $scope.bdTruckTypeFormInvalid = true;
            return;
        }
        var data = {
            vehicle_type: 1,
            type_name: $scope.type_name
        };
        $http.post("/export/truck/api/truck-bus-type-save-data", data)
            .then(function (response) {
                console.log(response);
                if (response.data == 'Duplicate') {
                    $scope.savingError = 'Sorry! Duplicate Vehicle Type Name Can Not Entry.';
                    $("#savingError").show().delay(5000).slideUp(2000);

                } else {
                    $scope.savingSuccess = 'Truck Type Successfully Inserted';
                    $("#savingSuccess").show().delay(5000).slideUp(2000);
                    $scope.type_name = null;
                    $scope.bdTruckTypeFormInvalid = false;
                    $scope.getBdTruckTypeData();

                }

            }).catch(function (r) {
            $scope.savingError = 'Something wnt Wrong';
            $("#savingError").show().delay(5000).slideUp(2000);
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        })
    }


//3.----section3----------------------------------------Self Chassis delivery coding  START--------------------------------

    $scope.getUndeliveredChassisListByManifest = function (mani_id) {
        $scope.undelivered_chassis = [];
        $http.get("/warehouse/api/delivery/undelivered-chassis-list-by-manifest/" + mani_id)

            .then(function (data) {
                console.log(data.data)
                $scope.undelivered_chassis = data.data;


            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.statusText});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        })

    }

    $scope.getSelfDeliveredChassisListByManifest = function (mani_id) {
        $scope.chassisSelfDataLoading = true
        $scope.selfDliveredChassisList = [];
        $scope.errorWhileSelfDeliveredDataLoading = false;
        $http.get("/warehouse/api/delivery/delivery-get-self-delivered-chassis-list-manifest/" + mani_id)
            .then(function (data) {
                console.log(data.data)
                $scope.selfDliveredChassisList = data.data;

            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            $scope.errorWhileSelfDeliveredDataLoading = true;

        }).finally(function () {

            $scope.chassisSelfDataLoading = false


        })
    };


    $scope.saveSelfTransportData = function (form) {
        console.log($scope.delivery_item);
        angular.forEach($scope.delivery_item, function (v, k) {
            console.log(v.checkbox);
            if(v.checkbox == true){
                $scope.countCheckBox ++ ;
            }

        })
        console.log($scope.countCheckBox);
        if($scope.countCheckBox == 0 || $scope.countCheckBox == undefined){
            $scope.selfTransportErrorItem = true;
            $scope.selfTransportErrorItem = 'Please Select Item';
            $('#selfTransportErrorItem').show().delay(5000).slideUp(2000);
            return;

        }
        if($scope.update_chassis_del_id != null){
            form.$invalid = false;
        }
        if(new Date($scope.today) > new Date($scope.delivery_dt)) {
            $scope.selfTransportError = true;
            $scope.localTransportErrorMsgTxt = 'You Can\'t Input Transport, The Manifest Already Delivered At ' + $scope.delivery_dt;
            $('#selfTransportError').show().delay(5000).slideUp(2000);
            return;
        }
        $scope.selfTransportFormSubmitted = false;
        console.log(form.$invalid)
        if (form.$invalid) {
            $scope.selfTransportFormSubmitted = true;
        } else {
            data = {
                selfTransportId: $scope.selfTransportId,
                manf_id: $scope.getManiID,
                selfTransportDriverName: $scope.selfTransportDriverName,
                selfTransportDriverCard: $scope.selfTransportDriverCard,
                delivery_req_date: $scope.previous_delivery_date == null ? $scope.today : $scope.previous_delivery_date,
                delivery_item_list: $scope.delivery_item,
                delivery_requisition_id : $scope.delivery_requisition_id,
                update_chassis_del_id : $scope.update_chassis_del_id,
                dr_partial_status: $scope.dr_partial_status
            }
            console.log(data)
          // return;
            $http.post("/warehouse/api/delivery/save-self-transport-data", data)
                .then(function (data) {
                    console.log(data)

                    $scope.selfTransportSuccess = true;
                    $scope.saveChSuccessMsgTxt = 'Successfully Save Data!'
                    $("#selfTransportSuccess").show().fadeTo(1500, 500).slideUp(500, function () {
                        $("#selfTransportSuccess").slideUp(1000);
                    });
                    $scope.selfTransportFormBlank();
                    $scope.LocalTransportFlag('2');
                    $scope.selfTransportDriverName = null;
                    $scope.selfTransportDriverCard = null;
                    $scope.previous_delivery_date = null;
                    $scope.delivery_item = [];
                    console.log($scope.delivery_item);
                    $scope.bdSelfIdForUpdate = false;
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                    $scope.selfTransportError = true;
                    $scope.selfTransportError = 'Something went worng!';
                    $("#selfTransportError").show().delay(5000).slideUp(2000);
                }



            }).finally(function () {

            })
        }

    }

    $scope.deleteSelfTransportDelivery = function (id) {
        console.log(id)

        $http.get("/warehouse/api/delivery/delete-self-transport-delivery/" + id)
            .then(function (data) {
                console.log(data)
                $scope.selfTransportSuccess = true;
                $scope.saveChSuccessMsgTxt = 'Successfully Removed Data!';
                $("#selfTransportSuccess").show().delay(5000).slideUp(2000);

                $scope.LocalTransportFlag('2')
            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            $scope.selfTransportError = true;
            $("#selfTransportError").show().delay(5000).slideUp(2000);


        }).finally(function () {

        })
    }


    $scope.selfTransportFormBlank = function () {

        $scope.selfTransportId = []
        $scope.selfTransportDriverName = '';
        $scope.selfTransportDriverCard = '';

    }


}).filter('loading', function () {

    return function (items) {
        var item = items;
        if (item == 0) {
            item = "Labour";
        }
        else {
            item = "Equipment";
        }
        return item;
    }

}).filter('ceil', function () {
    return function (input) {
        return Math.ceil(input);
    };
}).filter('stringToDate', function ($filter) {
    return function (ele, dateFormat) {
        return $filter('date')(new Date(ele), dateFormat);
    }
}).filter('transportTypeFilter', function () {
    return function (val) {
        var type;
        if (val == 1) {
            return type = 'VAN';
        } else if (val == 0) {
            return type = 'Truck';
        }
        return type = '';
    }
}).filter('itemType', function () {
    return function (val) {
        var type;
        if (val == 1) {
            return type = 'Volumn';
        } else if (val == 2) {
            return type = 'Unit';
        } else if (val == 3) {
            return type = 'Package';
        } else if (val == 4) {
            return type = 'Weight';
        }
        return type = '';
    }
});