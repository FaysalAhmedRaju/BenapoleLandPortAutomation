var app = angular.module('deliveryRequestApp', ['angularUtils.directives.dirPagination', 'customServiceModule']);

app.controller('deliveryRequestCtrl', function ($scope, $http, $filter, manifestService, enterKeyService) {

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    //capitalize Delivery Request Form
    $scope.$watch('searchText', function (val) {

        $scope.searchText = $filter('uppercase')(val);

    }, true);

    $scope.$watch('be_no', function (val) {

        $scope.be_no = $filter('uppercase')(val);

    }, true);

    $scope.$watch('be_date', function (val) {

        $scope.be_date = $filter('uppercase')(val);

    }, true);

    $scope.$watch('ain_no', function (val) {

        $scope.ain_no = $filter('uppercase')(val);

    }, true);

    $scope.$watch('cnf_name', function (val) {

        $scope.cnf_name = $filter('uppercase')(val);

    }, true);

    $scope.$watch('no_del_truck', function (val) {

        $scope.no_del_truck = $filter('uppercase')(val);

    }, true);

    $scope.$watch('carpenter_packages', function (val) {

        $scope.carpenter_packages = $filter('uppercase')(val);

    }, true);


    $scope.$watch('carpenter_repair_packages', function (val) {

        $scope.carpenter_repair_packages = $filter('uppercase')(val);

    }, true);

    $scope.$watch('bd_truck_no', function (val) {

        $scope.bd_truck_no = $filter('uppercase')(val);

    }, true);

    $scope.$watch('bd_driver_name', function (val) {

        $scope.bd_driver_name = $filter('uppercase')(val);

    }, true);

    $scope.$watch('labor_load', function (val) {

        $scope.labor_load = $filter('uppercase')(val);

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

    $scope.req_partial_number_list = [];
    //New Manifest Start
    $scope.keyBoard = function (event) {
        $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
    }
    $scope.$watch('searchText', function () {
        $scope.searchText = manifestService.addYearWithManifest($scope.searchText, $scope.keyboardFlag, $scope.searchBy);
    });


    //==================================== Auto Complete Task ===================
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
            console.log(ui.item);
            $scope.vatId_importer_name = ui.item.id;
            // console.log( $scope.vatId_importer_name);
            // $scope.Importer_Name = ui.item.id;
            if ($scope.vatId_importer_name != null) {
                // $scope.imp_name_from_Importer=true;
                // $scope.vat_no_after_Vat = false;
            }
        }
    });


    enterKeyService.enterKey('#dRForm input ,#dRForm button')
    enterKeyService.enterKey('#bdTruckForm input ,#bdTruckForm button')
    $scope.setFocusOnInput = function (term) { //set focus and Placeholder

        if (term == '') {
            $scope.setSearchByPlaceholder = 'Select option First';
            $scope.searchFld = true;
            return;
        }
        $("#searchText").focus();
        $scope.setSearchByPlaceholder = 'Enter ' + term;
        $scope.searchFld = false;
    }


    //=============Global Variable----------------------------
    $scope.gate_pass = true;

    $scope.cnfNameDisable = true;
    $scope.ChassisInformationForm = false;
    $scope.LocalTransportTruckForm = true;
    // $scope.local_transport_type_flag = 0;

    $scope.bd_driver_name = "--";

    $scope.role_name = role_name;

    $scope.disableWhenTranshipment = false;


    $scope.searchBy = "ManifestNo";
    $scope.setSearchByPlaceholder = 'Select option First';
    $scope.searchFld = true;
    $scope.getBtnActiveBySearch = true;
    $scope.buttonBdTruck = true;
    $scope.truckAddModalShowBtn = false;
    $scope.saveManifestDataBtn = true;
    //GLOBAL variable===============get when search BY
    $scope.searchTextNotFoundTxt = null;
    $scope.GetManiID = null;//catch manifest id for bd truck entry modal form
    $scope.GetManiNo = null;
    $scope.GetManiGWeight = null;
    $scope.ManiNweight = 0;
    $scope.billableWeight = 0;
    $scope.loadablePackage = 0;

    $scope.ImporterName = null;
    $scope.cacheLocalTransportRequestedNumber = null;
    $scope.BdTruckTotalLoad = 0;
    $scope.totalLoadedWeight = 0;
    $scope.labourWeightMust = false;
    $scope.equipmentWeightMust = false;
    //==================REPORT PDF==============
    $scope.reportByManifestBtn = true;

    $scope.truck_to_truck_flag = 0;


//=============================================================================DoSearch==
    $scope.doSearch = function (/*term*/) {
        $scope.reportByManifestBtn = true;//enable reportbtn when serach by manifest
        $scope.submitted = false;

        $scope.manifestDataLoading = true;
        $scope.manifestDataLoadingError = false;
        $scope.posted_yard_shed = null;
        $('#saveManifestDataBtn').html('Save');

        $scope.showManifestInfoDiv = false;//show a div for showing manifest no and importer name to be sure

        $scope.be_no = null
        $scope.be_date = null
        //$scope.paid_tax = null
        $scope.ain_no = null
        //$scope.paid_date = null
        $scope.cnf_name = null
        $scope.carpenter_packages = null
        $scope.carpenter_repair_packages = null
        $scope.no_del_truck = null
        $scope.allData = null;
        $scope.gate_pass_no = null;
        $scope.bd_weighment = null;

        $scope.custom_release_order_no = null;
        $scope.custom_release_order_date = null;
        $scope.approximate_delivery_date = null;
        $scope.approximate_delivery_type = "0";

        $scope.GetManiID = null
        $scope.GetManiNo = null;
        $scope.GetManiGWeight = null;
        $scope.getNetWeightForLoadingCharge = null;

        $scope.billableWeight = 0;
        $scope.loadablePackage = 0;


        $scope.BdTruckTotalLoad = 0;
        $scope.totalLoadedWeight = 0;
        $scope.ManiNweight = 0;
        $scope.BdTruckNoFull = null;

        $scope.permissionError = null;
        //  $scope.dRForm.$setUntouched();
        $scope.custom_approved_date = null;
        $scope.chassis_transport = false;

        $scope.approximate_labour_load = null;
        $scope.approximate_equipment_load = null;
        $scope.cacheLocalTransportRequestedNumber = 0;
        $scope.localTransportLength = 0;
        $scope.labourWeightMust = true;
        $scope.equipmentWeightMust = false;
        $scope.transportVanMust = true;
        $scope.transportTruckMust = false;

        $scope.truck_to_truck_flag = "0";


        var data = {
            manf_id: $scope.searchText
        }

        $http.post("/transshipment/api/warehouse/delivery/serach-by-manifest", data)

            .then(function (data) {
                // console.log(data);
                if (data.status == 203) {
                    $scope.permissionError = data.data.noPermission;
                    $('#permissionError').show().delay(5000).slideUp(1000);
                    return;
                }

                console.log(data.data)
                //  $scope.Request();

                if (data.data.length >= 1) {//manifest found
                    console.log(data.data);

                    $scope.showManifestInfoDiv = true;

                    $scope.GetManiID = data.data[0].m_id;
                    $scope.GetManiNo = data.data[0].manifest;
                    $scope.GetManiGWeight = data.data[0].m_gweight;
                    $scope.ManiNweight = data.data[0].m_nweight;
                    $scope.ImporterName = data.data[0].importer;
                   // $scope.gate_pass_no = data.data[0].gate_pass_no;
                    $scope.posted_yard_shed = data.data[0].posted_yard_shed;
                    $scope.receive_weight = data.data[0].receive_weight;
                    $scope.weigh_bridge_net_weight = data.data[0].weigh_bridge_net_weight;

                    $scope.custom_release_order_no = data.data[0].custom_release_order_no;
                    $scope.custom_release_order_date = data.data[0].custom_release_order_date;
                    $scope.approximate_delivery_date = data.data[0].approximate_delivery_date;

                    $scope.getNetWeightForLoadingCharge = parseFloat(data.data[0].chargeable_weight ? data.data[0].chargeable_weight : $scope.GetManiGWeight);
                    $scope.approximate_delivery_type = data.data[0].approximate_delivery_type != null ? data.data[0].approximate_delivery_type.toString() : "0";

                    $scope.approximate_labour_load = parseFloat(data.data[0].approximate_labour_load);
                   // $scope.approximate_equipment_load = parseFloat(data.data[0].approximate_equipment_load);

                 //   $scope.billableWeight = $scope.approximate_labour_load + $scope.approximate_equipment_load;
                    $scope.loadablePackage = parseFloat(data.data[0].loadable_package);
                  //  $scope.bd_weighment = parseFloat(data.data[0].bd_weighment);


                    $scope.reportByManifestBtn = false;//enable reportbtn when serach by manifest
                    $scope.searchKeyManifestNo = $scope.searchText;
                    // $scope.allData = data.data;
                    // console.log($scope.allData);
                    $scope.custom_approved_date = data.data[0].custom_approved_date;
                    $scope.local_transport_type = data.data[0].local_transport_type != null ? data.data[0].local_transport_type.toString():"0";
                    $scope.changeApprxTransportFlag($scope.local_transport_type);
                    //$scope.transport_truck = parseFloat(data.data[0].transport_truck);
                    //$scope.transport_van = parseFloat(data.data[0].transport_van);
                    //$scope.truck_to_truck_flag =

                    // var checkifBeDone = data.data[0].be_no;
                    var checkRequisitionExist = data.data[0].delivery_req_id;

                    console.log(checkRequisitionExist);
                    if (checkRequisitionExist == null) {//check if bill of entry completed or not with the manifestno--- null means not completed

                        $scope.be_no = null;
                        // $scope.gate_pass_no = null;
                        $scope.be_date = null;
                        $scope.paid_tax = null
                        $scope.ain_no = null;
                        //$scope.paid_date = null
                        $scope.cnf_name = null;
                        //$scope.no_del_truck = null;
                       // $scope.allData = data.data;
                       // $scope.posted_yard_shed = 'Tranship-Yard 31';
                        //  $scope.GetManiID = null;
                        $scope.custom_release_order_no = null;
                        $scope.custom_release_order_date = null;
                        $scope.approximate_delivery_date = null;
                        $scope.custom_approved_date = null;
                        $scope.local_transport_type = "0";
                        $scope.perishable_flag   = "1";
                        $scope.bd_weighment = null;
                        $scope.transport_truck = null;
                        $scope.transport_van = null;
                        $scope.truck_to_truck_flag = "0";

                        $scope.changeAapproximateDeliveryType($scope.approximate_delivery_type);
                        $scope.changeApprxTransportFlag($scope.local_transport_type);

                        //  $scope.Request();
                        var t = data.data[0];
                        $scope.idSelectedRow = t.t_id;
                        console.log(t);
                        $scope.GetManiID = t.m_id
                        //  console.log($scope.GetManiID);
                        $scope.GetManiNo = t.manifest;
                        $scope.ImporterName = t.importer;
                        $scope.ManiNweight = t.m_nweight;
                        //it's taken from add request

                    } else { //Bill E completed then  in edit mode


                        // $('#saveManifestDataBtn').html('Update');


                        $scope.allData = data.data;

                        console.log('update');
                        console.log(data.data[0]);

                        $scope.be_no = data.data[0].be_no;
                        $scope.be_date = data.data[0].be_date;
                        //$scope.paid_tax = data.data[0].paid_tax;
                        $scope.ain_no = data.data[0].ain_no;
                        //  $scope.ain_no_only = data.data[0].ain_no;
                        //$scope.paid_date = data.data[0].paid_date;
                        $scope.cnf_name = data.data[0].cnf_name;

                        $scope.no_del_truck = data.data[0].no_del_truck;
                        // $scope.carpenter_packages = data.data[0].carpenter_packages;
                        // $scope.carpenter_repair_packages = data.data[0].carpenter_repair_packages;
                      //  $scope.gate_pass_no = data.data[0].gate_pass_no;
                      //  $scope.bd_weighment = parseFloat(data.data[0].bd_weighment);
                        $scope.custom_release_order_no = data.data[0].custom_release_order_no;
                        $scope.custom_release_order_date = data.data[0].custom_release_order_date;

                        // $scope.approximate_delivery_date = data.data[0].approximate_delivery_date;
                       // $scope.approximate_delivery_type = data.data[0].approximate_delivery_type != null ? data.data[0].approximate_delivery_type.toString() : "1";
                        $scope.approximate_labour_load = parseFloat(data.data[0].approximate_labour_load);
                    //    $scope.approximate_equipment_load = parseFloat(data.data[0].approximate_equipment_load);
                        $scope.approximate_delivery_type = "3";
                        $scope.changeAapproximateDeliveryType($scope.approximate_delivery_type);
                        // if ($scope.approximate_delivery_type == "0") {//labour
                        //     $scope.labourWeightMust = true;
                        //     $scope.equipmentWeightMust = false;
                        // } else if ($scope.approximate_delivery_type == '1') {//equ
                        //     $scope.labourWeightMust = false;
                        //     $scope.equipmentWeightMust = true;
                        // } else if ($scope.approximate_delivery_type == '2') {//both
                        //     $scope.labourWeightMust = true;
                        //     $scope.equipmentWeightMust = true;
                        // }

                        // $scope.posted_yard_shed = 'Tranship-Yard 31';
                        $scope.custom_approved_date = data.data[0].custom_approved_date;
                        $scope.local_transport_type = data.data[0].local_transport_type ? data.data[0].local_transport_type.toString() : "0";
                        // console.log(data.data[0].perishable_flag);
                        // $scope.perishable_flag = data.data[0].perishable_flag == null ? '1' : data.data[0].perishable_flag.toString();
                        $scope.changeApprxTransportFlag($scope.local_transport_type);
                      //  $scope.transport_truck = parseFloat(data.data[0].transport_truck);
                     //   $scope.transport_van = parseFloat(data.data[0].transport_van);
                     //    $scope.cacheLocalTransportRequestedNumber = isNumeric($scope.transport_truck) ? $scope.transport_truck : 0
                     //                    + isNumeric($scope.transport_van) ? $scope.transport_van : 0;


                    }


                } else {//manifest not found


                    $scope.searchTextNotFoundTxt = 'Manifest No: ' + $scope.searchText
                    $scope.manifestDataLoadingError = true;

                }

            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            console.log('catch in del req')

            $scope.manifestDataLoadingError = true;
            $scope.be_no = null
            $scope.be_date = null
            //$scope.paid_tax=null
            $scope.ain_no = null
            //$scope.paid_date=null
            $scope.cnf_name = null
            $scope.no_del_truck = null
            $scope.gate_pass_no = null;
            $scope.GetManiID = null
            $scope.allData = null
            $scope.saveSuccess = '';
            $scope.custom_approved_date = null;
            $scope.local_transport_type = '0';
            $scope.bd_weighment = null;

        }).finally(function () {
            // console.log('in finally');
            $scope.manifestDataLoading = false;

        })

    }//===============================================================================doSearch End============


    //---for transshipment-if manifest not is from reveive form-------------------

    console.log($("#manifest_no_fetch").val());
    var manifest_by_fetching = $("#manifest_no_fetch").val();

    if (manifest_by_fetching != '//') {
        $scope.searchText = manifest_by_fetching;
        $scope.doSearch();
    }


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
        } else if(flag == 2) {
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
        console.log(value)

        if (value == 0) {//labout
            $scope.labourWeightMust = true;
            $scope.equipmentWeightMust = false;
            $scope.approximate_labour_load = $scope.getNetWeightForLoadingCharge;
            $scope.approximate_equipment_load = null;

        } else if (value == 1) {//equipment
            $scope.equipmentWeightMust = true;
            $scope.labourWeightMust = false;
            $scope.approximate_labour_load = null;
            $scope.approximate_equipment_load = $scope.getNetWeightForLoadingCharge;
        } else if (value == 2) {//both
            $scope.labourWeightMust = true;
            $scope.equipmentWeightMust = true;
            $scope.approximate_labour_load = $scope.getNetWeightForLoadingCharge / 2;
            $scope.approximate_equipment_load = $scope.getNetWeightForLoadingCharge / 2;

        } else {//value 3->self (not needed for transshipem)
            $scope.labourWeightMust = false;
            $scope.equipmentWeightMust = false;
            $scope.approximate_labour_load = null;
            $scope.approximate_equipment_load = null;

        }
        console.log($scope.approximate_labour_load)
        console.log($scope.approximate_equipment_load)


    }


    $scope.BlankBin = function () {
        $scope.ain_no = null;
        $scope.cnfName = null;

    }


    $scope.Request = function (t) {

        $scope.idSelectedRow = t.t_id;
        console.log(t);
        $scope.GetManiID = t.m_id

        $scope.showManifestInfoDiv = true;

        $scope.GetManiNo = t.manifest;
        $scope.ImporterName = t.importer;
        $scope.ManiNweight = t.m_nweight;

    }

    $scope.ValidationManifestData = function (form) {
        if (form.$invalid) {
            $scope.submitted = true;
            return false;
        } else {
            $scope.submitted = false;
            return true;
        }
    }


    $scope.ValidationBin = function () {
        // if($scope.exist == true) {
        //         $scope.submitted = true;
        //         return false;
        // }

        if ($scope.importerForm.$invalid) {
            $scope.submittedAin = true;
            return false;
        } else {
            $scope.submittedAin = false;
            return true;
        }
    }

// C&F Name and AIN  Add...
    $scope.SaveAin = function () {
        if ($scope.ValidationBin() == false) {
            return;
        }
        //return;
        $scope.dataLoadingBin = true;
        var data = {
            ain_no: $scope.ain_no_f,
            cnf_name: $scope.cnfName_f

        }

        $http.post("/warehouse/api/delivery/save-cnf-name-ain-data", data)
            .then(function (data) {

                console.log(data);
                $scope.savingSuccessBin = 'AIN No and C&F Name saved successfully.';
                $('#savingSuccessBin').show().delay(5000).slideUp(1000, function () {
                    $('#addImporter').modal('hide');
                });
                $scope.BlankBin();
                $scope.ain_no_f = '';
                $scope.cnfName_f = '';
            }).catch(function (r) {
            console.log(r);

            if (r.status == 401) {
                $.growl.error({message: r.data});
                $scope.savingErrorBin = r.data.duplicate;
                $('#savingErrorBin').show().delay(5000).slideUp(1000);
                return;
            }else {
                $.growl.error({message: "It has Some Error!"});
                $scope.savingErrorBin = 'Something went wrong.';
                $('#savingErrorBin').show().delay(5000).slideUp(1000);
            }

        }).finally(function () {

            $scope.dataLoadingBin = false;

        })
    }

    $scope.saveDeliveryData = function (form) {

        console.log('mani id -' + $scope.GetManiID);
        console.log('form - ' + form.$invalid);


        console.log($scope.labourWeightMust);
        console.log($scope.equipmentWeightMust);
        console.log($scope.transportTruckMust);
        console.log(form.$invalid)
        console.log($scope.local_transport_type);

        if(form.$invalid && !$scope.transportTruckMust){
            $scope.submitted = true;
            return;
        }

        if (form.$invalid && $scope.labourWeightMust && !$scope.transportTruckMust) {
            $scope.submitted = true;
            return;
        }

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
            perishable_flag: $scope.perishable_flag,
            bd_weighment: $scope.bd_weighment,
            bd_haltage : $scope.bd_haltage,
            manifest_id: $scope.GetManiID, //like 5 -int $scope.GetManiNo
            del_req_id:  $scope.del_req_id,
            truck_to_truck_flag: $scope.truck_to_truck_flag
            //paid_tax:$scope.paid_tax,
            // gate_pass_no: $scope.gate_pass_no,
            //paid_date:$scope.paid_date,
        }

        console.log(data);
        // return
        $http.post("/transshipment/api/warehouse/delivery/save-delivery-request-data", data)

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
                $scope.saveManifestDataBtn = false;
                // $scope.cacheLocalTransportRequestedNumber = $scope.no_del_truck;//used in getbdtruckData()
                $scope.be_no = null;
                $scope.be_date = null;
                //$scope.paid_tax=null;
                $scope.ain_no = null;
                //$scope.paid_date=null;
                $scope.cnf_name = null;
                $scope.bd_haltage = null;
                $scope.custom_release_order_no = null;
                $scope.custom_release_order_date = null;
                $scope.approximate_delivery_date = null;
                $scope.approximate_delivery_type = null;
                $scope.custom_approved_date = null;
                $scope.local_transport_type = '0';
                $scope.perishable_flag = '1';
                $scope.truck_to_truck_flag = '0';
                $scope.bd_weighment = null;
                $scope.transport_van = null;
                $scope.transport_truck = null;
                $scope.del_req_id = null;
                //  form.$setUntouched();
                $scope.submitted = true;
                $('#saveManifestDataBtn').html('Save')


                $scope.doSearch($scope.searchBy)


            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            console.log(r.status)
            $scope.maniBEerrormsg = true;
            $scope.message = "Something went wrong!";
            $("#maniBEerrormsg").show().fadeTo(1500, 500).slideUp(500, function () {
                $("#maniBEerrormsg").slideUp(3000);
            });


        }).finally(function () {

            $scope.dataLoading = false;

        })


    };

    $scope.get_requisition_status = function (manifest_no, status) {
        console.log(status);
        console.log(manifest_no);
      //  $scope.doSearch(manifest_no, status);
        $scope.addTruckModalBtn();
    };
    //================================Delivery Request Code END===================================================================

    $scope.searchFunctionReloadData = function () {
        $scope.doSearch($scope.GetManiID);

    }

//=--------=-MODAL Section------------addTruckModalBtn MODAL-------------------------addTruckModalBtn MODAL-======================

    $scope.addTruckModalBtn = function () { //button for showing modal for bd truck=Add Truck
        console.log($scope.GetManiID);

        $scope.delivery_item = [];
        $scope.delivery_dt = $scope.approximate_delivery_date;
        $scope.BdTruckManiIDBlankMsg = false;
        $scope.saveBdTruckSuccess = false;
        $scope.bdTruckIdForUpdate = null;
        $scope.ManifestIdModal = $scope.GetManiID;// like 5
        $scope.ManifestNoSearchModal = $scope.GetManiNo;// like 458/2
        $scope.GweightForBdTruckModal = $scope.GetManiGWeight;
        $scope.NweightForBdTruckModal = $scope.GetManiNWeight;
        $scope.bd_truck_no = null;
        $scope.driver_name = "--";
        $scope.labor_load = null;
        $scope.equip_name = null;
        $scope.equip_load = null;
        $scope.billableWeight = 0;
        $scope.totalLoadedWeight = 0;
        $scope.totalLoadedWeightBackUp = 0;
        $scope.totalLoadedPackageBackUp = 0;
        $scope.loadablePackage = 0;
        $scope.totalLoadedPackage = 0;
        $scope.equip_load_add = 0;
        $scope.labor_load_add = 0;
        $scope.itemWeightTotal_add = 0;

        $scope.labor_package_add = 0;
        $scope.equipment_package_add = 0;
        $scope.itemPackageTotal_add = 0;
        // $scope.haltage_day = null;
        // $scope.transport_type = $scope.local_transport_type.toString();
        $scope.bdTruckForm.$setUntouched();

        // $scope.LocalTransportFlag($scope.transport_type)

           console.log($scope.req_partial_status);
           if($scope.req_partial_status){
               req_partial_status = $scope.req_partial_status;
           }else{
               req_partial_status = null;
           }

        var data = {
            mani_no: $scope.GetManiNo,
            partial_status: req_partial_status,
        }
        console.log(data);
        $http.post("/transshipment/api/warehouse/delivery/local-delivery-data", data)
            .then(function (data) {
                 console.log(data.data);

                if (data.status == 203) {
                    console.log('in 203');
                    $scope.localTrnsportGlobalNotification = true;
                    $scope.localTrnsportGlobalNotificationTxt = data.data.noPermission;
                    $('#localTrnsportGlobalNotification').show().delay(5000).slideUp(1000);
                    return;
                }


                if (data.data.length >= 1) {//manifest found

                    $scope.approximate_labour_load = parseFloat(data.data[0].approximate_labour_load);
                    $scope.approximate_equipment_load = parseFloat(data.data[0].approximate_equipment_load);

                    $scope.billableWeight = $scope.approximate_labour_load + $scope.approximate_equipment_load;

                    $scope.loadablePackage = data.data[0].loadable_package;
                    console.log( $scope.loadablePackage);

                    $scope.req_id = data.data[0].dr_id;
                    console.log($scope.req_id);
                    $scope.transport_type = data.data[0].local_transport_type.toString();
                    console.log($scope.transport_type);
                    $scope.LocalTransportFlag($scope.transport_type)

                    $scope.total_partial_status = data.data[0].total_partial_status;
                    console.log($scope.total_partial_status);

                    for (var x = 0; x < $scope.total_partial_status; x++) {
                        $scope.req_partial_number_list[x] = x+1;
                    }
                    console.log($scope.req_partial_number_list);

                    if($scope.req_partial_status == null) {
                        $scope.req_partial_status = $scope.req_partial_number_list[$scope.total_partial_status-1];
                    }

                    $scope.requestedLocalTruck = data.data[0].transport_truck;
                    $scope.requestedLocalVan = data.data[0].transport_van;
                    $scope.delivery_requisition_id = data.data[0].dr_id;

                    $scope.dr_partial_status = data.data[0].dr_partial_status;
                    console.log($scope.dr_partial_status);



                }else {//manifest not found

                    // $scope.localTrnsportGlobalNotification = true;
                    // $scope.localTrnsportGlobalNotificationTxt = 'Manifest Not Found!';
                    // $('#permissionError').show().delay(5000).slideUp(1000);

                }




            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            console.log('catch in del req')






        }).finally(function () {
            // console.log('in finally');
            $scope.manifestDataLoading = false;

        })
    }

    $scope.checkAssessmentStatus = function (manifest) {
        var data = {
            manifest: manifest
        }
        $http.post("/warehouse/api/delivery/check-assessment-status", data)
            .then(function (data) {
                if (data.data.localTransportLength > 0) {
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


//Section-2.================================Local Transport Modal Coding START==========================================================


    $scope.LocalTransportFlag = function (flag) {
        console.log('local flag: ' + flag);
        console.log('mani id: ' + $scope.GetManiID);
        console.log($scope.delivery_dt)
        console.log($scope.cacheLocalTransportRequestedNumber);

        $scope.localTrnsportGlobalError = false;
        $scope.localTrnsportGlobalNotification = false;

        // if ($scope.cacheLocalTransportRequestedNumber == 0 && flag != 2) {//total local tronsport provided in request
        //     $scope.localTrnsportGlobalError = true;
        //     $scope.localTrnsportGlobalErrorTxt = 'Your Given Local Transport Number is 0';
        // }


        //set forms as normal
        $scope.submittedBDTruck = false;
        $scope.selfChForm = false;
        $scope.LocalTransportVanForm = false;
        $scope.LocalTransportTruckForm = false;
        $scope.bd_truck_no=null;
        if (flag == 0) {//means truck
            $scope.ChassisInformationForm = false;
            $scope.LocalTransportTruckForm = true;

            $scope.getBdTruckData($scope.GetManiID,$scope.req_id);// get delivered truck list

            $scope.getBdTruckTypeData($scope.GetManiID);//get bd truck type


        } else if (flag == 1) {//van
            console.log(flag);
            $scope.LocalTransportVanForm = true;
           // $scope.getBdTruckData($scope.GetManiID,$scope.req_id);// get delivered truck list
            $scope.getBdTruckTypeData($scope.GetManiID);//get bd truck
            $scope.bd_truck_no='00';
            $scope.getLocalVanData($scope.GetManiID,$scope.req_id)


        } else {//self

        }

    }
    //=========================Truck / Van delivery with or without on truck (chassis/trucktor on these)===============================================//

    $scope.getBdTruckTypeData = function (manifest) {//truck type loadinf
        console.log(manifest)
        $http.get("/warehouse/api/delivery/tuck-details-data/"+ manifest)
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

                // $scope.truck_type_data = data.data;
                // $scope.truck_type =$scope.cachedTruckTypeId ? $scope.cachedTruckTypeId: $scope.truck_type_data[0].truck_id;
                // console.log($scope.truck_type)
                //
                // // console.log($scope.indian_truck_type);
                // // console.log($scope.indian_truck_type[1].truck_id);
                // // $scope.indian_truck_type_value = $scope.indian_truck_type[1].truck_id;

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


    $scope.getBdTruckData = function (mId,req_id) {
        $scope.BdTruckTotalLoad = 0;
        $scope.totalLoadedWeight = 0;
        $scope.BdTruckTotalPac = 0;
        $scope.totalLoadedPackage = 0;
        $scope.bdTrucksdataLoading = true;
        $scope.localTransportLength = 0;
        $scope.allBdTrucksData = [];
        $('#saveBdTruckData').html('Save')
        console.log($scope.bdTrucksdataLoading)

        if (mId == null) {
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = 'No Manifest ID Found!';
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            $scope.bdTrucksdataLoading = false;
            return
        }

        $http.get("/transshipment/api/warehouse/delivery/local-transport/get-delivered-local-transport-data/" + mId +"/"+ req_id)
            .then(function (data) {

                console.log(data.data.length)

                if (data.data.length < 1) {//if bd truck is empty

                    if ($scope.requestedLocalTruck == null) {
                        $scope.localTrnsportGlobalError = true;
                        $scope.localTrnsportGlobalErrorTxt = 'No  Truck Is Requested!';
                        $('.localTrnsportGlobalError').show().delay(20000).slideUp(2000);
                        return;
                    }

                    $scope.getLoadedDetails(mId);
                    $scope.localTrnsportGlobalNotification = true;
                    $scope.localTrnsportGlobalNotificationTxt = 'You can input ' + $scope.requestedLocalTruck + ' Trucks/Vans';
                    $('#localTrnsportGlobalNotification').show().delay(20000).slideUp(2000);

                    $scope.allBdTrucksData = [];
                    console.log($scope.billableWeight);
                    console.log($scope.loadablePackage);


                }
                else {
                    console.log('have previous bd trans data')

                    $scope.allBdTrucksData = data.data;
                    console.log($scope.allBdTrucksData);

                    $scope.totalLoadedWeight= $scope.allBdTrucksData[0].total_loaded_weight;
                    $scope.totalLoadedPackage = $scope.allBdTrucksData[0].total_loaded_package;

                    $scope.totalLoadedWeightBackUp = $scope.totalLoadedWeight;
                    $scope.totalLoadedPackageBackUp = $scope.totalLoadedPackage;

                    $scope.BdTruckTotalLoadBackUp = $scope.totalLoadedWeight;
                    $scope.BdTruckTotalPacBackup = $scope.totalLoadedPackage;


                    var length = data.data.length;
                    $scope.localTransportLength = data.data.length;

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
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {
            console.log($scope.allBdTrucksData)

            $scope.bdTrucksdataLoading = false;
        })
    }


    $scope.getLocalVanData = function (mId,req_id) {
        $scope.BdTruckTotalLoad = 0;
        $scope.totalLoadedWeight = 0;
        $scope.bdTruckTotalPackage = 0;
        $scope.BdTruckTotalPac = 0;
        $scope.totalLoadedPackage =0;
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

                    $scope.totalLoadedWeightBackUp = $scope.totalLoadedWeight;
                    $scope.totalLoadedPackageBackUp = $scope.totalLoadedPackage;

                    console.log($scope.totalLoadedPackageBackUp);

                    var length = data.data.length;
                    $scope.localVanLength = data.data.length;

                    $scope.LocalTruckWeight = $filter('ceil')(($scope.billableWeight - $scope.totalLoadedWeight) / ($scope.requestedLocalTransport - length));

                    if ($scope.requestedLocalVan == length) {
                        $scope.localTrnsportGlobalError = true;
                        $scope.localTrnsportGlobalErrorTxt = 'Your requested Local Van Number is full';
                        $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);

                    }
                    else {
                        $scope.localTrnsportGlobalNotification = true;
                        $scope.localTrnsportGlobalNotificationTxt = 'You can input ' + ($scope.requestedLocalVan - length) + ' Van';
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


    var eqip_name = [
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


    $scope.getEquipmentWeight = function () {

        if ($scope.equip_load <= 0) {
            $scope.equip_load = null;
        }
    }

    $scope.getLabourWeight = function () {
        console.log(isNumeric($scope.perPackageWeight))

        if (( $scope.cacheLocalTransportRequestedNumber - $scope.localTransportLength) == 1) {

        }else {
            $scope.labor_load = $filter('ceil')($scope.perPackageWeight * $scope.labor_package);
        }
    }

    $scope.BdTruckValidation = function (form) {
        var f = 0;

        var totalPac = ($scope.labor_package != null ? $scope.labor_package : 0);

        if (form.$invalid) {
            $scope.submittedBDTruck = true;
            f = 1;
        } else {
            $scope.submittedBDTruck = false;
        }

        if ($scope.manifestPackageNo < ($scope.totalLoadedPackage + totalPac)) {
            $scope.packError = true;
            f = 3;
        } else {
            $scope.packError = false;
        }

        if (f >= 1 && f <= 3) {
            return false;
        } else {
            return true;
        }
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

    $scope.onVehicleTransportId = {};
    $scope.delivery_item = [];

    $scope.saveBdTruckData = function (form) {

        $scope.countCheckBox = 0;
        angular.forEach($scope.delivery_item, function (v, k) {
            // console.log(v.checkbox);
            if(v.checkbox == true){
                $scope.countCheckBox ++ ;
            }

        })
        // console.log($scope.countCheckBox);
        if($scope.countCheckBox == 0){
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = 'Please Select Item';
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            return;

        }

        if ($scope.GetManiID == null) {
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = 'Please insert B/E First!';
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            return;
        }
        console.log($scope.bdTruckIdForUpdate);
        if (($scope.requestedLocalTruck <= $scope.localTransportLength) && !$scope.bdTruckIdForUpdate  && $scope.transport_type=='0') {//check requrested number and current number of local transport
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = 'Your requested Local Truck/Van is full';
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
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

        console.log($scope.billableWeight);
        console.log($scope.totalLoadedWeight);
        console.log(($scope.equip_load_add + $scope.labor_load_add + $scope.itemWeightTotal_add));
        console.log(($scope.billableWeight - $scope.totalLoadedWeight))
        if ((($scope.billableWeight - $scope.totalLoadedWeight) < ($scope.equip_load_add + $scope.labor_load_add))) {
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = "Can't Deliver more than Weight Loadable!";
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            return;
        }

        if ((($scope.billableWeight - $scope.totalLoadedWeight) < ($scope.itemWeightTotal_add))) {
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = "Can't Deliver more than Weight!";
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            return;
        }

        console.log(($scope.labor_package_add + $scope.equipment_package_add + $scope.itemPackageTotal_add));
        if ((($scope.loadablePackage - $scope.totalLoadedPackage) < ($scope.labor_package_add + $scope.equipment_package_add))) {
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = "Can't Deliver more than Loadable Packages!";
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            return;
        }

        if ((($scope.loadablePackage - $scope.totalLoadedPackage) < ($scope.itemPackageTotal_add))) {
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = "Can't Deliver more than Package!";
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            return;
        }

                //return;
        if ($scope.labor_load && $scope.labor_load < 0) {
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = "Please Check Load Weight";
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            return;
        }
        if ($scope.equip_load && $scope.equip_load < 0) {
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = "Please Check Load Weight";
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            return;
        }


        if ($scope.BdTruckValidation(form) == false) {
            console.log('form invalid');
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = 'Your Request Is Not Valid';
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            return;
        }
        else {
            $scope.savingLocalTransportData = true;
            $scope.cachedTruckTypeId=$scope.truck_type;

            var data = {
                truck_no: $scope.bd_truck_no,
                truck_type_id: $scope.truck_type,
                delivery_requisition_id:$scope.delivery_requisition_id,
                manf_id: $scope.GetManiID,
                transport_type: $scope.transport_type,
                driver_name: $scope.driver_name,
                labor_load: $scope.labor_load, //==null ? 0: $scope.labor_load,
                labor_package: $scope.labor_package,
                equip_load: $scope.equip_load,// ==null ? 0: $scope.equip_load,
                equipment_package: $scope.equipment_package,
                equip_name: $scope.equip_name,
                delivery_dt: $scope.delivery_dt,
                bd_truck_id: $scope.bdTruckIdForUpdate,
                onVehicleTransportId: $scope.onVehicleTransportId,
                delivery_item_list: $scope.delivery_item,
                dr_partial_status: $scope.dr_partial_status
            }

            console.log($scope.transport_type);
            if($scope.transport_type == '1'){               //van
                data = {
                    manf_id: $scope.GetManiID,
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
                    delivery_dt: $scope.delivery_dt,
                    transport_type: $scope.transport_type,
                    delivery_item_list: $scope.delivery_item,
                    dr_partial_status: $scope.dr_partial_status

                }
            }

            console.log(data)
           // return;
            $http.post("/transshipment/api/warehouse/delivery/save-local-transport-data", data)

                .then(function (data) {
                    console.log(data);
                    // return
                    if (data.status == 200) {//saved


                        if($scope.transport_type==1){
                            $scope.localTransportSuccessVan = true;
                            $scope.localTransportSuccessMsgTxt = 'Successfully Saved';
                            $('#localTransportSuccessVan').show().delay(5000).slideUp(2000);
                        }
                        $scope.localTransportSuccess = true;
                        $scope.localTransportSuccessMsgTxt = 'Successfully Saved';
                        $('#localTransportSuccess').show().delay(5000).slideUp(2000);
                    }

                    if(data.status == 201) {//'updated'
                        if($scope.transport_type==1){
                            $scope.localTransportSuccessVan = true;
                            $scope.localTransportSuccessMsgTxt = 'Successfully Updated';
                            $('#localTransportSuccessVan').show().delay(5000).slideUp(2000);
                        }
                        $scope.localTransportSuccess = true;
                        $scope.localTransportSuccessMsgTxt = 'Successfully Updated';
                        $('#localTransportSuccess').show().delay(5000).slideUp(2000);
                    }
                    // else {
                    //     console.log(data.data)
                    //     $scope.localTransportError = true;
                    //     $scope.localTransportErrorMsgTxt = 'Something Went Wrong!';
                    //     $('#localTransportError').show().delay(5000).slideUp(2000);
                    // }
                    //


                  //  $scope.getBdTruckData($scope.GetManiID);
                    console.log($scope.transport_type);
                    console.log($scope.GetManiID);
                    console.log($scope.req_id);
                    if($scope.transport_type==0){
                        $scope.getBdTruckData($scope.GetManiID,$scope.req_id);
                        $scope.getBdTruckTypeData( $scope.GetManiID);
                    }
                    if($scope.transport_type==1){//van
                        $scope.bd_truck_no='00';
                        $scope.getLocalVanData($scope.GetManiID,$scope.req_id);
                        $scope.getBdTruckTypeData( $scope.GetManiID);
                    }
                    // $scope.LocalTransportFlag($scope.transport_type?$scope.transport_type:'0')


                    $scope.bd_truck_no = null;

                    // $scope.truck_type = null;
                    $scope.driver_name = "--";
                    $scope.labor_load = null;
                    $scope.labor_package = null;
                    $scope.equip_load = null;
                    $scope.equipment_package = null;
                    $scope.equip_name = null;
                    $scope.delivery_item = [];
                    $scope.bdTruckIdForUpdate = null;
                    console.log($scope.delivery_item)
                    //  $scope.delivery_dt = null;
                    //$scope.weightment_flag = '0';
                    // $scope.haltage_day = null;
                    form.$setUntouched();
                    $('#saveBdTruckData').html('Save')
                    // $scope.bdTruckIdForUpdate = null;
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
        $('#saveBdTruckData').html('Update')
        console.log(i);
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






        $scope.saveBdTruckSuccess = false;
        $scope.savingBdTruckError = false;
        $scope.BdTruckNoFullBtnDisable = false;

      //  $scope.BdTruckTotalLoad = $scope.BdTruckTotalLoadBackUp - (isNumeric(i.labor_load) ? i.labor_load : 0);
      //  $scope.BdTruckTotalPac = $scope.BdTruckTotalPacBackup - (isNumeric(i.labor_package) ? i.labor_package : 0);

        $scope.totalLoadedWeight = $scope.totalLoadedWeightBackUp - ((isNumeric(i.labor_load) ? i.labor_load : 0) + (isNumeric(i.equip_load) ? i.equip_load : 0));
        $scope.totalLoadedPackage = $scope.totalLoadedPackageBackUp  - ((isNumeric(i.labor_package) ? parseFloat(i.labor_package) : 0) + (isNumeric(i.equipment_package) ? parseFloat(i.equipment_package) : 0));

        console.log($scope.totalLoadedWeight);
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
        $scope.loading_unit = i.loading_unit;
        $scope.equip_name = i.equip_name;
        console.log(i.delivery_req_dt);
        $scope.delivery_dt = i.delivery_req_dt
        console.log(i.bd_truck_id);
        $scope.bdTruckIdForUpdate = i.bd_truck_id;


    }

    $scope.deleteBdTruck = function (i) {
        //bd_truck_id m_id
        console.log(i.bd_truck_id)

        $http.get("/transshipment/api/warehouse/delivery/local-transport/delete/" + i.bd_truck_id)
            .then(function (data) {
              //  $scope.LocalTransportFlag('0');
               // $scope.LocalTransportFlag($scope.transport_type?$scope.transport_type:'0')
                console.log(data);
                if (data.status == 200) {//saved

                    $scope.localTrnsportGlobalSuccess = true;
                    $scope.localTrnsportGlobalSuccessTxt = 'Successfully Deleted!';
                    $('#localTrnsportGlobalSuccess').show().delay(5000).slideUp(2000);

                     }


                console.log($scope.transport_type)
                if($scope.transport_type == 0){
                    $scope.getBdTruckData($scope.GetManiID,$scope.req_id);
                }
                if($scope.transport_type == 1){
                    $scope.getLocalVanData($scope.GetManiID,$scope.req_id);
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
    $scope.edit = function (i) {
        $('#saveManifestDataBtn').html('Update');
        console.log(i);
        // $scope.updateBtn = true;

        $scope.m_id = i.m_id;
        $scope.del_req_id = i.delivery_req_id;
        $scope.bd_weighment = i.bd_weighment;
        $scope.bd_haltage = i.bd_haltage;
        $scope.perishable_flag = i.perishable_flag ? i.perishable_flag.toString():'0';
        $scope.carpenter_packages = parseFloat(i.carpenter_packages);
        $scope.carpenter_repair_packages = parseFloat(i.carpenter_repair_packages);
        $scope.gate_pass_no = i.gate_pass_no;
        $scope.approximate_delivery_date = i.approximate_delivery_date;
        $scope.getNetWeightForLoadingCharge=parseFloat(i.chargeable_weight ? i.chargeable_weight :$scope.GetManiGWeight);


        $scope.approximate_labour_load =parseFloat(i.approximate_labour_load);
        $scope.approximate_equipment_load = parseFloat(i.approximate_equipment_load);
        $scope.truck_to_truck_flag = i.truck_to_truck_flag ? i.truck_to_truck_flag.toString():'0';


        $scope.approximate_delivery_type = i.approximate_delivery_type != null ? i.approximate_delivery_type.toString() : "0";
        console.log($scope.labourWeightMust);
        console.log(i.approximate_labour_load);
        console.log(i.approximate_equipment_load);
        console.log($scope.approximate_delivery_type);
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
                console.log(response.data);
                if (response.data == 'Duplicate') {
                    $scope.savingError = 'Sorry! Duplicate Vehicle Type Name Can Not Entry.';
                    $("#savingError").show().delay(5000).slideUp(2000);

                } else {
                    $scope.savingSuccess = 'Truck Type Successfully Inserted';
                    $("#savingSuccess").show().delay(5000).slideUp(2000);
                    $scope.type_name = null;
                    $scope.bdTruckTypeFormInvalid = false;

                }

            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            $scope.savingError = 'Something wnt Wrong';
            $("#savingError").show().delay(5000).slideUp(2000);

        })
    }


}).filter('loading', function () {

    return function (items) {
        var item = items;
        if (item == 0) {
            item = "Labour";
        }
        else if(item == 1) {
            item = "Equipment";
        }else if(item == 2){
            item = "Both";
        }else {
            item = "None";
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
}).filter('perishable_flag', function () {
    return function (items) {
        var item = items;
        if (item == 0) {
            item = "NonPerishable";
        }else {
            item = "Perishable";
        }
        return item;
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
}).filter('truckToTruckFilter', function () {
    return function (val) {
        var type;
        if (val == 0) {
            return type = 'No';
        } else if (val == 1) {
            return type = 'Yes';
        }
        return type = '';
    }
});