var app = angular.module('truckToTruckDeliveryRequestApp', ['angularUtils.directives.dirPagination', 'customServiceModule']);

app.controller('truckToTruckDeliveryRequestCtrl', function ($scope, $http, $filter, manifestService, enterKeyService) {
    $scope.gate_pass = true;

    $scope.cnfNameDisable = true;
    $scope.ChassisInformationForm = false;
    $scope.LocalTransportTruckForm = true;
    // $scope.local_transport_type_flag = 0;

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }


    $scope.driver_name = "--";
    //for role work
    $scope.role_name = role_name;
    $scope.disableWhenTranshipment = false;
    if ($scope.role_name == 'TransShipment') {
        $scope.disableWhenTranshipment = true;
    }
    console.log($scope.role_name);
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

    console.log(today); //2018-09-11  //09/08/2018
    if($scope.role_name == 'WareHouse'){
        $scope.approximate_delivery_date = today;
        $scope.delivery_date = true;

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

    $scope.$watch('driver_name', function (val) {

        $scope.driver_name = $filter('uppercase')(val);

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


    //New Manifest Start
    $scope.keyBoard = function (event) {
        $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
    }

    $scope.$watch('searchText', function () {
        $scope.searchText = manifestService.addYearWithManifest($scope.searchText, $scope.keyboardFlag, $scope.searchBy);
    });

    //New Manifest End

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

    $scope.searchBy = "ManifestNo";
    $scope.setSearchByPlaceholder = 'Select option First'
    $scope.searchFld = true;
    $scope.getBtnActiveBySearch = true;
    $scope.buttonBdTruck = true;
    $scope.truckAddModalShowBtn = false;
    // $scope.saveManifestDataBtn = true;
    //GLOBAL variable===============get when search BY
    $scope.searchTextNotFoundTxt = null;
    $scope.GetManiID = null;//catch manifest id for bd truck entry modal form
    $scope.GetManiNo = null;
    $scope.GetManiGWeight = null;
    $scope.ManiNweight = null;
    $scope.LocalTruckWeight = null;
    $scope.ImporterName = null;
    $scope.cacheLocalTransportRequestedNumber = null;
    $scope.BdTruckTotalLoad = 0;
    $scope.labourWeightMust = true;
    $scope.equipmentWeightMust = false;
    $scope.transportVanMust = true;
    $scope.transportTruckMust = false;
    //==================REPORT PDF==============
    $scope.reportByManifestBtn = true;


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

//=============================================================================DoSearch==
    $scope.doSearch = function (/*term*/) {
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

        $scope.errorMessage = null;
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

        $http.post("/warehouse/truck-to-truck/api/delivery-request/search-by-manifest-data", data)

            .then(function (data) {
                 console.log(data);
                if (data.status == 203) {
                    $scope.errorMessage = data.data.errorMessage;
                    $('#errorMessage').show().delay(5000).slideUp(1000);
                    return;
                }

                console.log(data.data)
                //  $scope.Request();

                if (data.data.length >= 1) {//manifest found
                        console.log('m found')
                    if($scope.role_name == 'WareHouse'){
                        $scope.approximate_delivery_date = today;
                        $scope.delivery_date = true;
                    }
                    $scope.showManifestInfoDiv = true;

                    $scope.GetManiID = data.data[0].m_id;
                    $scope.GetManiNo = data.data[0].manifest;
                    $scope.GetManiGWeight = data.data[0].m_gweight;
                    $scope.ManiNweight = data.data[0].m_nweight;
                    $scope.ImporterName = data.data[0].importer;
                    $scope.gate_pass_no = data.data[0].gate_pass_no;
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
                        if($scope.role_name == 'WareHouse'){
                            $scope.approximate_delivery_date = today;
                            $scope.delivery_date = true;
                        }else {

                            $scope.approximate_delivery_date = null;
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

                        if($scope.role_name == 'WareHouse'){
                            $scope.approximate_delivery_date = today;
                            $scope.delivery_date = true;
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
                if($scope.role_name == 'WareHouse'){
                    $scope.approximate_delivery_date = today;
                    $scope.delivery_date = true;

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

    }//===============================================================================doSearch End============
     console.log($scope.labourWeightMust)

    $scope.changeApprxTransportFlag = function (flag) {
        console.log(flag);
        if (flag == 0) {
            $scope.chassis_transport = false;
            $scope.transportVanMust = true;
            $scope.transportTruckMust = false;
            $scope.transport_van = null;
            $scope.transport_truck = $scope.getTruckNumber();
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

    $scope.getTruckNumber = function() {
        if($scope.weigh_bridge_net_weight > 0) {
            const perTruckWeight = 20000;
            var getNumberOfTruck = $scope.weigh_bridge_net_weight/perTruckWeight;
            return Math.ceil(getNumberOfTruck);
        } else {
            return 0;
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

    //====for transshipment-if manifest not is from reveive form

    console.log($("#manifest_no_fetch").val());
    var manifest_by_fetching = $("#manifest_no_fetch").val();

    if (manifest_by_fetching != '//') {
        $scope.searchText = manifest_by_fetching;
        $scope.doSearch();
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
                $scope.savingErrorBin = respose.data.duplicate;
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
            delivery_req_id:$scope.delivery_req_id,
            truck_to_truck_flag : 1
        }
        console.log(data);
  //return;
        $http.post("/warehouse/truck-to-truck/api/delivery-request/save-delivery-request-data", data)

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
                $scope.submitted = false;
                $scope.gate_pass_no = null;
                // $('#saveManifestDataBtn').html('Save')


                $scope.doSearch($scope.searchBy)


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
            del_req_id:  $scope.del_req_id,
            truck_to_truck_flag : 1
        }
        console.log(data);

        $http.post("/warehouse/truck-to-truck/api/delivery-request/update-delivery-request-data", data)

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


                $scope.doSearch($scope.searchBy)


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

//=--------=-MODAL Section------------addTruckModalBtn MODAL------------------------------

    $scope.addTruckModalBtn = function () { //button for showing modal for bd truck=Add Truck
        console.log($scope.GetManiID);
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
        $scope.haltage_day = null;
        $scope.transport_type = $scope.local_transport_type.toString();
        $scope.bdTruckForm.$setUntouched();

        $scope.LocalTransportFlag($scope.transport_type)

    }

    $scope.checkAssessmentStatus = function (manifest) {
        var data = {
            manifest: manifest
        }
        $http.post("/warehouse/api/delivery/check-assessment-status", data)
            .then(function (data) {
                if (data.data.length > 0) {
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


    //================================Delivery Request Code END===================================================================

//Section-2.================================Local Transport Modal Coding START==========================================================


    $scope.LocalTransportFlag = function (flag) {
        console.log('local flag: ' + flag);
        console.log('mani id: ' + $scope.GetManiID);
        console.log($scope.delivery_dt)
        console.log($scope.cacheLocalTransportRequestedNumber);

        $scope.localTrnsportGlobalError = false;
        $scope.localTrnsportGlobalNotification = false;

        if ($scope.cacheLocalTransportRequestedNumber == 0 && flag != 2) {//total local tronsport provided in request
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = 'Your Given Local Transport Number is 0';
        }


        //set forms as normal
        $scope.submittedBDTruck = false;
        $scope.selfChForm = false;

        if (flag == 0) {//means truck
            $scope.ChassisInformationForm = false;
            $scope.LocalTransportTruckForm = true;
            //if ($scope.GetManiID != null) {
            //$scope.checkAssessmentStatus($scope.GetManiID);
            $scope.getBdTruckData($scope.GetManiID);// Function call with 1 param
            //  }
            $scope.getBdTruckTypeData();//get bd truck type
            $scope.getUndeliveredChassisListByManifest($scope.GetManiID);

        } else if (flag == 1) {//van
            $scope.ChassisInformationForm = false;
            $scope.LocalTransportTruckForm = true;
            $scope.getBdTruckData($scope.GetManiID)
            $scope.getBdTruckTypeData();//get bd truck
            $scope.getUndeliveredChassisListByManifest($scope.GetManiID);

        } else {//self
            $scope.ChassisInformationForm = true;
            $scope.LocalTransportTruckForm = false;

            $scope.getUndeliveredChassisListByManifest($scope.GetManiID);
            $scope.getSelfDeliveredChassisListByManifest($scope.GetManiID);
        }

    }
    //=========================Truck / Van delivery with or without on truck (chassis/trucktor on these)===============================================

    $scope.getBdTruckTypeData = function () {//truck type loadinf
        $http.get("/warehouse/api/delivery/tuck-details-data")
            .then(function (data) {
                $scope.truck_type_data = data.data;

                console.log($scope.truck_type_data)
                $scope.truck_type = $scope.truck_type_data[0].truck_id;
                console.log($scope.truck_type)

                // console.log($scope.indian_truck_type);
                // console.log($scope.indian_truck_type[1].truck_id);
                // $scope.indian_truck_type_value = $scope.indian_truck_type[1].truck_id;

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


    $scope.getBdTruckData = function (mId) {
        $scope.BdTruckTotalLoad = 0;
        $scope.BdTruckTotalPac = 0;
        $scope.bdTrucksdataLoading = true;
        $scope.localTransportLength = 0;
        console.log(mId)

        $('#saveBdTruckData').html('Save')
        $http.get("/api/getNetWeightAndDeliveryDate/" + mId)
            .then(function (data) {
                console.log(data.data[0]);
                console.log(data.data[0].max_weight);
                $scope.ManiNweight = data.data[0].max_weight;
                if ($scope.role_name == 'TransShipment') {
                    $scope.manifestPackageNo = data.data[0].package_no;
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
        $http.get("/warehouse/api/delivery/get-local-transport-data/" + mId)

            .then(function (data) {

                console.log(data.data.length)
                console.log(data.data);

                if (data.data.length < 1) {//if bd truck is empty

                    $scope.LocalTruckWeight = $filter('ceil')(($scope.ManiNweight) / $scope.cacheLocalTransportRequestedNumber);
                    console.log($scope.LocalTruckWeight);
                    if ($scope.cacheLocalTransportRequestedNumber == null) {
                        return;
                    }
                    if ($scope.role_name == 'C&F') {
                        $scope.labor_load = 0;
                        $scope.equip_load = 0;
                    } else {
                   //     $scope.labor_load = $filter('ceil')((($scope.ManiNweight) / $scope.cacheLocalTransportRequestedNumber) / 2);
                  //      $scope.equip_load = $filter('ceil')((($scope.ManiNweight) / $scope.cacheLocalTransportRequestedNumber) / 2);
                    }

                    $scope.localTrnsportGlobalNotification = true;
                    $scope.localTrnsportGlobalNotificationTxt = 'You can input ' + $scope.cacheLocalTransportRequestedNumber + ' Trucks';
                    $('#localTrnsportGlobalNotification').show().delay(20000).slideUp(2000);

                    $scope.allBdTrucksData = [];


                }
                else {


                    /*$scope.equip_name =data.data[0].equip_name*/

                    $scope.allBdTrucksData = data.data;

                    //Calculate bd Truck Total Load

                    angular.forEach(data.data, function (v, k) {
                        if ($scope.role_name == 'TransShipment') {
                            $scope.BdTruckTotalLoad += parseFloat(isNumeric(v.labor_load) ? v.labor_load : 0);
                            $scope.BdTruckTotalPac += parseFloat(isNumeric(v.labor_package) ? v.labor_package : 0);
                        } else {
                            $scope.BdTruckTotalLoad += parseFloat(isNumeric(v.labor_load) ? v.labor_load : 0);
                            $scope.BdTruckTotalLoad += parseFloat(isNumeric(v.equip_load) ? v.equip_load : 0);
                        }

                    });
                    $scope.BdTruckTotalLoadBackUp = $scope.BdTruckTotalLoad;
                    console.log($scope.BdTruckTotalLoadBackUp);
                    if ($scope.role_name == 'TransShipment') {
                        $scope.BdTruckTotalPacBackup = $scope.BdTruckTotalPac;
                    }
                    var no_del_truck = data.data[0].no_del_truck;
                    console.log(no_del_truck);
                    var length = data.data.length;
                    console.log(length);
                    $scope.localTransportLength = data.data.length;

                    $scope.LocalTruckWeight = $filter('ceil')(($scope.ManiNweight - $scope.BdTruckTotalLoad) / (no_del_truck - length));
                    console.log($scope.LocalTruckWeight);
                    if ($scope.role_name == 'TransShipment') {
                        $scope.labor_package = $filter('ceil')(($scope.manifestPackageNo - $scope.BdTruckTotalPac) / (no_del_truck - length));
                        $scope.perPackageWeight = $filter('ceil')($scope.ManiNweight / $scope.manifestPackageNo);
                        // $scope.labor_load = $filter('ceil')($scope.labor_package * $scope.perPackageWeight);
                        $scope.labor_load= $filter('ceil')(($scope.ManiNweight-$scope.BdTruckTotalLoad)/(no_del_truck - length));
                    } else if ($scope.role_name == 'C&F') {
                        $scope.labor_load = 0;
                        $scope.equip_load = 0;

                    } else {
                     //   $scope.labor_load = $filter('ceil')((($scope.ManiNweight - $scope.BdTruckTotalLoad) / (no_del_truck - length)) / 2);
                    //    $scope.equip_load = $filter('ceil')((($scope.ManiNweight - $scope.BdTruckTotalLoad) / (no_del_truck - length)) / 2);
                    }


                    if (no_del_truck == length) {
                        console.log('got')

                        $scope.localTrnsportGlobalError = true;
                        $scope.localTrnsportGlobalErrorTxt = 'Your requested BD Transport Number is full';
                        $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
                        $scope.BdTruckNoFullBtnDisable = true;

                    }
                    else {
                        $scope.localTrnsportGlobalNotification = true;
                        $scope.localTrnsportGlobalNotificationTxt = 'You can input ' + (no_del_truck - length) + ' Trucks';
                        $('#localTrnsportGlobalNotification').show().delay(20000).slideUp(2000);
                        $scope.BdTruckNoFullBtnDisable = false;

                    }

                    $scope.GetManiID = data.data[0].m_id;
                    $scope.GetManiNo = data.data[0].manifest;


                }
            }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
        }).finally(function () {

            $scope.bdTrucksdataLoading = false;
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
        if (isNumeric($scope.LocalTruckWeight) && isNumeric($scope.labor_load) && $scope.role_name != 'TransShipment') {
            //console.log($scope.LocalTruckWeight - $scope.labor_load);
            $scope.equip_load_check = $scope.LocalTruckWeight - $scope.labor_load;
            console.log($scope.equip_load_check);
        }
        if ($scope.equip_load_check <= 0) {
            $scope.equip_load = null;
        }
    }

    $scope.getLabourWeight = function () {
        if ($scope.role_name == 'TransShipment') {
            if (isNumeric($scope.labor_package) && isNumeric($scope.perPackageWeight)) {
               $scope.labor_load = $scope.perPackageWeight * $scope.labor_package;
            }
        } else {
            if (isNumeric($scope.LocalTruckWeight) && isNumeric($scope.equip_load)) {
                $scope.labor_load_check = $scope.LocalTruckWeight - $scope.equip_load;
                console.log($scope.labor_load_check);
            }
            if ($scope.labor_load_check <= 0) {
                $scope.labor_load = null;
            }
        }
    }

    $scope.LoadOption = function (v) {

        console.log(v)

        if (v == 0)$scope.equip_name = null;
    }


    console.log($scope.role_name);

    if ($scope.role_name == 'C&F') {//c&F Form Hide
        $scope.cnfModuleFormHide = true;
    }

    $scope.BdTruckValidation = function (form) {


        var f = 0;
        if ($scope.role_name == 'TransShipment') {
            var totalPac = ($scope.labor_package != null ? $scope.labor_package : 0);
        } else {
            var totalWeight = ($scope.labor_load != null ? $scope.labor_load : 0) +
                ($scope.equip_load != null ? $scope.equip_load : 0);

            console.log($scope.totalWeight);
        }

        if ($scope.role_name == 'C&F') {
            $scope.submittedBDTruck = false;
        } else {
            if (form.$invalid) {
                $scope.submittedBDTruck = true;
                f = 1;
            } else {
                $scope.submittedBDTruck = false;
            }
        }


        /* if ($scope.BdTruckNoFullBtnDisable == true) {
         $scope.BDTruckFull = true;

         f = 2;
         } else {
         $scope.BDTruckFull = false;
         }*/
        if ($scope.role_name == 'TransShipment') {
            if ($scope.manifestPackageNo < ($scope.BdTruckTotalPac + totalPac)) {
                $scope.packError = true;
                f = 3;
            } else {
                $scope.packError = false;
            }
        } else {
            console.log($scope.ManiNweight < ($scope.BdTruckTotalLoad + totalWeight));
            console.log("else");
            if ($scope.ManiNweight < ($scope.BdTruckTotalLoad + totalWeight)) {
                $scope.netWeightError = true;
                f = 3;
                console.log("else if");
            } else {
                $scope.netWeightError = false;
                console.log("else else");
            }
        }
        if (f >= 1 && f <= 3) {
            return false;
        } else {
            return true;
        }
    }
    $scope.onVehicleTransportId = {};

    $scope.saveBdTruckData = function (form) {
        // if($scope.assesmentStatus == "Assessment Done") {
        //     $scope.assesmentDoneError = true;
        //     return;
        // }


        // return;
        if ($scope.GetManiID == null) {
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = 'Please insert B/E First!';
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            return;
        }

        console.log($scope.bdTruckIdForUpdate);
        if (($scope.cacheLocalTransportRequestedNumber <= $scope.localTransportLength) && !$scope.bdTruckIdForUpdate) {
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = 'Your requested BD Transport Number is full';
            $('#localTrnsportGlobalError').show().delay(5000).slideUp(2000);
            return;
        }


        if ($scope.netWeightError && !$scope.BDTruckFull) {
            $scope.localTrnsportGlobalError = true;
            $scope.localTrnsportGlobalErrorTxt = "Can't input more than net weight!";
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

            var onVehicleTransportId_array = [];
            angular.forEach($scope.onVehicleTransportId, function (selected, id) {
                if (selected) {
                    console.log(id);
                    onVehicleTransportId_array.push(id)
                }
            });
            var all_onVehicleTransportIds = onVehicleTransportId_array.join();
            console.log(all_onVehicleTransportIds);


            var data = {

                manf_id: $scope.GetManiID,
                bd_truck_id: $scope.bdTruckIdForUpdate,
                truck_no: $scope.bd_truck_no,
                onVehicleTransportId: $scope.onVehicleTransportId,

                truck_type_id: $scope.truck_type,
                all_onVehicleTransportIds: all_onVehicleTransportIds,
                driver_name: $scope.driver_name,
                labor_load: $scope.labor_load, //==null ? 0: $scope.labor_load,
                labor_package: $scope.labor_package,
                equip_load: $scope.equip_load,// ==null ? 0: $scope.equip_load,
                equipment_package: $scope.equipment_package,
                equip_name: $scope.equip_name,
                delivery_dt: $scope.delivery_dt,
                weightment_flag: $scope.weightment_flag,
                haltage_day: $scope.haltage_day,
                transport_type: $scope.transport_type

            }
            console.log(data);
            console.log($scope.labor_load);
            console.log( $scope.equip_load);
            //  return;

            $http.post("/warehouse/api/delivery/delivery-save-local-transport-data", data)                             // check this link

                .then(function (data) {
                    console.log(data);
                    // return
                    if (data.status == 200) {//saved
                        $scope.localTransportSuccess = true;
                        $scope.localTransportSuccessMsgTxt = 'Successfully Saved';
                    }
                    else if (data.status == 201) {//'updated'
                        $scope.localTransportSuccess = true;
                        $scope.localTransportSuccessMsgTxt = 'Successfully Updated';
                    }
                    else {
                        console.log(data.data)
                        $scope.localTransportError = true;
                        $scope.localTransportErrorMsgTxt = 'Something Went Wrong!';
                        $('#localTransportError').show().delay(5000).slideUp(2000);
                    }

                    $('#localTransportSuccess').show().delay(5000).slideUp(2000);

                    $scope.getBdTruckData($scope.GetManiID);
                    $scope.addTruckModalBtn()

                    $scope.bd_truck_no = null;
                    // $scope.truck_type = null;
                    $scope.driver_name = "--";
                    $scope.labor_load = null;
                    $scope.labor_package = null;
                    $scope.equip_load = null;
                    $scope.equipment_package = null;
                    $scope.equip_name = null;
                    //  $scope.delivery_dt = null;
                    $scope.weightment_flag = '0';
                    $scope.haltage_day = null;
                    form.$setUntouched();
                    $('#saveBdTruckData').html('Save')
                    $scope.bdTruckIdForUpdate = null;


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
        /* if($scope.assesmentStatus = "Assessment Done") {
         $scope.assesmentDoneError = true;
         return;
         }*/
        console.log(i);

        var all_ids = i.chassis_ids_on_vehicle;

        $http.get("/warehouse/api/delivery/chassis-list-for-local-transport/" + i.bd_truck_id)

            .then(function (data) {
                console.log(data.data)
                $scope.undelivered_chassis = data.data;
                $scope.onVehicleTransportId = '4';

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

        //$scope.undelivered_chassis = data.data;


        $scope.saveBdTruckSuccess = false;
        $scope.savingBdTruckError = false;
        $scope.BdTruckNoFullBtnDisable = false;

        //console.log(i.labor_load + i.equip_load);
        //console.log("BD truck Load Backup"+$scope.BdTruckTotalLoadBackUp);
        if ($scope.role_name == 'TransShipment') {
            $scope.BdTruckTotalLoad = $scope.BdTruckTotalLoadBackUp - (isNumeric(i.labor_load) ? i.labor_load : 0);
            $scope.BdTruckTotalPac = $scope.BdTruckTotalPacBackup - (isNumeric(i.labor_package) ? i.labor_package : 0);
        } else {
            $scope.BdTruckTotalLoad = $scope.BdTruckTotalLoadBackUp - ((isNumeric(i.labor_load) ? i.labor_load : 0) + (isNumeric(i.equip_load) ? i.equip_load : 0));
        }

        //console.log("BD truck Load :" + $scope.BdTruckTotalLoad);

        $('#saveBdTruckData').html('Update')

        var truckNOSplit = i.truck_no.split("-");


        $scope.bd_truck_no = truckNOSplit[0];


        $scope.driver_name = i.driver_name;
        $scope.truck_type = i.truck_type_id;


        $scope.labor_load = i.labor_load;
        $scope.labor_package = parseInt(i.labor_package);
        $scope.equip_load = i.equip_load;
        $scope.equipment_package = parseInt(i.equipment_package);
        $scope.haltage_day = i.haltage_day != 0 ? parseInt(i.haltage_day) : "";
        $scope.weightment_flag = i.weightment_flag ? i.weightment_flag.toString() : '0';

        $scope.loading_unit = i.loading_unit
        $scope.equip_name = i.equip_name
        $scope.delivery_dt = i.delivery_req_dt

        $scope.bdTruckIdForUpdate = i.bd_truck_id;


    }

    $scope.deleteBdTruck = function (i) {
        //bd_truck_id m_id
        console.log(i.bd_truck_id)

        $http.get("/warehouse/api/delivery/delete-local-transport-data/" + i.bd_truck_id)
            .then(function (data) {

                $scope.getBdTruckData($scope.GetManiID);
                $scope.LocalTransportFlag('0');
                $scope.bDTruckdeletesuccessmsg = true;
                $scope.savingBdTruckError = false;
                $scope.saveBdTruckSuccess = false;

                $('#bDTruckdeletesuccessmsg').show().delay(5000).slideUp(2000);


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

        }).finally(function () {


        });
    }


//3.----section3----------------------------------------Self Chassis delivery coding  START--------------------------------

    $scope.getUndeliveredChassisListByManifest = function (mani_id) {
        $http.get("/warehouse/api/delivery/undelivered-chassis-list-by-manifest/" + mani_id)

            .then(function (data) {
                console.log(data.data)
                $scope.undelivered_chassis = data.data;


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
        $scope.selfTransportFormSubmitted = false;
        console.log(form.$invalid)
        if (form.$invalid) {
            $scope.selfTransportFormSubmitted = true;
        } else {
            data = {
                selfTransportId: $scope.selfTransportId,
                selfTransportDriverName: $scope.selfTransportDriverName,
                selfTransportDriverCard: $scope.selfTransportDriverCard,
                delivery_req_date: $scope.delivery_dt,
            }
            console.log(data)
            $http.post("/warehouse/api/delivery/save-self-transport-data", data)
                .then(function (data) {
                    console.log(data)

                    $scope.selfTransportSuccess = true;
                    $scope.saveChSuccessMsgTxt = 'Successfully Save Data!'
                    $("#selfTransportSuccess").show().fadeTo(1500, 500).slideUp(500, function () {
                        $("#selfTransportSuccess").slideUp(1000);
                    });
                    $scope.selfTransportFormBlank();
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
});