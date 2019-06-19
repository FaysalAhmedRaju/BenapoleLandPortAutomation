angular.module('cnfApp', ['customServiceModule'])

    .controller('CnfPanelController', function($scope,$http,enterKeyService,manifestService) {

        $scope.search = function(id) {
         
            var data = {
                ManifestNo : id
            }

            $http.post("/c&f/api/search-manifest-data",data)
                .then(function (data) {
                  
                    var leng=data.data.length;
                    if (leng >= 1)
                    {
                        var  s = id.split("/");
                        var  n = s[1];
                        if (n == data.data.length)
                        {
                            $scope.vatname=true;
                            $scope.m_manifest= $scope.ManifestNo;
                            $scope.m_manifest_date = data.data[0].m_manifest_date;
                            $scope.m_marks_no = data.data[0].m_marks_no;
                            $scope.m_good_id = data.data[0].m_good_id;
                            $scope.m_gweight=data.data[0].m_gweight;
                            $scope.m_nweight=data.data[0].m_nweight;
                            $scope.m_package_no=data.data[0].m_package_no;
                            $scope.m_package_type=data.data[0].m_package_type;
                            $scope.m_cnf_value=data.data[0].m_cnf_value;
                            $scope.m_exporter_name_addr=data.data[0].m_exporter_name_addr;
                            $scope.m_vat_id=data.data[0].m_vat_id;
                            $scope.m_vat_name=data.data[0].importer;
                            $scope.m_lc_no=data.data[0].m_lc_no;
                            $scope.m_lc_date=data.data[0].m_lc_date;
                            $scope.m_ind_be_no=data.data[0].m_ind_be_no;
                            $scope.m_ind_be_date=data.data[0].m_ind_be_date;

                            $scope.manifestFull = "Sorry! Truck Can't Add With This Manifest ID";


                            $scope.t_gweight = null;
                            $scope.t_nweight = null;
                            $scope.t_truck_type = null;
                            $scope.t_truck_no = null;
                            $scope.t_driver_name = null;
                            $scope.t_driver_card = null;
                           // $scope.t_weightment_flag = null;

                        }
                        else {

                            $scope.vatname=true;
                            $scope.m_manifest= $scope.ManifestNo;
                            $scope.m_manifest_date = data.data[0].m_manifest_date;
                            $scope.m_marks_no = data.data[0].m_marks_no;
                            $scope.m_good_id = data.data[0].m_good_id;
                            $scope.m_gweight=data.data[0].m_gweight;
                            $scope.m_nweight=data.data[0].m_nweight;
                            $scope.m_package_no=data.data[0].m_package_no;
                            $scope.m_package_type=data.data[0].m_package_type;
                            $scope.m_cnf_value=data.data[0].m_cnf_value;
                            $scope.m_exporter_name_addr=data.data[0].m_exporter_name_addr;
                            $scope.m_vat_id=data.data[0].m_vat_id;
                            $scope.m_lc_no=data.data[0].m_lc_no;
                            $scope.m_lc_date=data.data[0].m_lc_date;
                            $scope.m_ind_be_no=data.data[0].m_ind_be_no;
                            $scope.m_ind_be_date=data.data[0].m_ind_be_date;
                            $scope.t_weightment_flag=data.data[0].t_weightment_flag;




                            $scope.t_gweight = null;
                            $scope.t_nweight = null;
                            $scope.t_truck_type = null;
                            $scope.t_truck_no = null;
                            $scope.t_driver_name = null;
                            $scope.t_driver_card = null;
                            //$scope.t_weightment_flag = null;

                        }
                        $scope.table=true;
                        $scope.allManifestData=data.data;
                        $scope.truckDivShow = true;
                        $scope.totalTruck=data.data.length;
                        $scope.updateBtn=false;
                        $scope.savingSuccess ="";
                        $scope.updateSuccessMsg = "";
                        $scope.truckDivShowColor = {
                            "color": "Green",
                            "font-weight": "bold"
                        }
                        $scope.searchFound="Manifest exists!";
                        $scope.searchNotFound=null;

                    }
                    else
                    {
                        $scope.allManifestData=null;
                        $scope.searchFound=null;
                        $scope.searchNotFound="Manifest is not found!";
                        $scope.truckDivShow=false;
                        $scope.table=false;
                        $scope.m_manifest=null;
                        $scope.m_manifest_date=null;
                        $scope.m_marks_no=null;
                        $scope.m_good_id=null;
                        $scope.m_gweight=null;
                        $scope.m_nweight=null;
                        $scope.m_package_no=null;
                        $scope.m_package_type=null;
                        $scope.m_cnf_value=null;
                        $scope.m_exporter_name_addr=null;
                        $scope.m_vat_id=null;
                        $scope.m_lc_no=null;
                        $scope.m_lc_date=null;
                        $scope.m_ind_be_no=null;
                        $scope.m_ind_be_date=null;
                        $scope.m_manifest=$scope.ManifestNo;
                        $scope.updateBtn=false;
                    }

                }).catch(function () {
                console.log('error')

            }).finally(function () {

            })

        }

        $http.get("/c&f/api/vats-details-data")
            .then(function (data){

                $scope.allVatsData=data.data;

            })

        $http.get("/c&f/api/goods-details-data")
            .then(function (data) {
                console.log(data)

                $scope.allGoodsDataCnf=data.data;

                // console.log( $scope.allGoodsDataCnf);
            });

        enterKeyService.enterKey('#cnfform input ,#cnfform button')



   
        $scope.save=function () {

            if($scope.validationCNF()==false)
            {
                return;
            }


            var data = {
                m_manifest: $scope.m_manifest,
                m_manifest_date: $scope.m_manifest_date,
                m_marks_no: $scope.m_marks_no,
                m_good_id: $scope.m_good_id,
                m_gweight: $scope.m_gweight,
                m_nweight: $scope.m_nweight,
                m_package_no: $scope.m_package_no,
                m_package_type: $scope.m_package_type,
                m_cnf_value: $scope.m_cnf_value,
                m_exporter_name_addr: $scope.m_exporter_name_addr,
                m_vat_id: $scope.m_vat_id,
                m_lc_no: $scope.m_lc_no,
                m_lc_date: $scope.m_lc_date,
                m_ind_be_no: $scope.m_ind_be_no,
                m_ind_be_date: $scope.m_ind_be_date,
                t_id: $scope.t_id,
                t_truck_type: $scope.t_truck_type,
                t_truck_no: $scope.t_truck_no,
                t_driver_card: $scope.t_driver_card,
                t_driver_name: $scope.t_driver_name,
                t_gweight: $scope.t_gweight,
                t_nweight: $scope.t_nweight,
                t_weightment_flag:$scope.t_weightment_flag
            }

            $http.post("/c&f/api/save-posting-data",data)
                .then(function (data) {
                    console.log(data)
                    $scope.savingSuccess = 'Saved Successfully!';
                    $scope.search( $scope.m_manifest);
                    $scope.t_truck_type =null;
                    $scope.t_truck_no =null;
                    $scope.t_driver_card =null;
                    $scope.t_driver_name =null;
                    $scope.t_gweight =null;
                    $scope.t_nweight =null;
                }).catch(function () {

                $scope.savingErro='Something went Wrong!';

            }).finally(function () {

                $scope.savingData=false;

            })


        }



        $scope.getVatsData=function () {

            var data = {
                BIN : $scope.m_vat_id
            }
            $http.post("/c&f/api/get-cnf-vat-details",data)
                .then(function (data) {

                   // console.log(data.data[0].NAME);
                    $scope.m_vat_name=data.data[0].NAME

                }).catch(function () {

                $scope.savingErro='Something wet worng!';

            }).finally(function () {

                $scope.savingData=false;

            })

        }

        $scope.edit=function(mani)
        {

            $scope.updateBtn=true;
            $scope.selectedStyle = mani.t_id;
            $scope.m_manifest=mani.m_manifest;
            $scope.m_manifest_date = mani.m_manifest_date;
            $scope.m_marks_no = mani.m_marks_no;
            $scope.m_good_id = mani.m_good_id;
            $scope.m_gweight= mani.m_gweight;
            $scope.m_nweight= mani.m_nweight;
            $scope.m_package_no= mani.m_package_no;
            $scope.m_package_type=mani.m_package_type;
            $scope.m_cnf_value=mani.m_cnf_value;
            $scope.m_exporter_name_addr=mani.m_exporter_name_addr;
            $scope.m_vat_id=mani.m_vat_id;
            $scope.m_lc_no=mani.m_lc_no;
            $scope.m_lc_date=mani.m_lc_date;
            $scope.m_ind_be_no=mani.m_ind_be_no;
            $scope.m_ind_be_date=mani.m_ind_be_date;
            $scope.t_gweight = mani.t_gweight;
            $scope.t_nweight = mani.t_nweight;


            $scope.t_truck_type = mani.t_truck_type;
            $scope.t_truck_no = mani.t_truck_no;
            $scope.t_driver_name = mani.t_driver_name;
            $scope.t_driver_card = mani.t_driver_card;
            $scope.t_weightment_flag = mani.t_weightment_flag;

          //  $scope.manif_posted_btn_disable=false;

            $scope.t_id =mani.t_id;
            $scope.label=true;
            $scope.savingSuccess = null;
            $scope.savingErro = null;

        }

        $scope.validationCNF = function() {

            if($scope.cnfform.$invalid) {

                $scope.submittedCnfForm = true;
                return false;

            } else {

                $scope.submittedCnfForm = false;
                return true;

            }

        }
        // $scope.validationUpdateTruck = function() {
        //
        //     if($scope.cnfform.$invalid) {
        //
        //         $scope.submittedCnfFormTruck = true;
        //         return false;
        //
        //     } else {
        //
        //         $scope.submittedCnfFormTruck = false;
        //         return true;
        //
        //     }
        //
        // }
        $scope.update = function (form)
        {
            if($scope.validationCNF()==false)
            {
                return;
            }


            var  data =
                {
                    m_manifest: $scope.m_manifest,
                    t_id: $scope.t_id,
                    m_manifest: $scope.m_manifest,
                    m_manifest_date: $scope.m_manifest_date,
                    m_marks_no: $scope.m_marks_no,
                    m_good_id: $scope.m_good_id,
                    m_gweight: $scope.m_gweight,
                    m_nweight: $scope.m_nweight,
                    m_package_no: $scope.m_package_no,
                    m_package_type: $scope.m_package_type,
                    m_cnf_value: $scope.m_cnf_value,
                    m_exporter_name_addr: $scope.m_exporter_name_addr,
                    m_vat_id: $scope.m_vat_id,
                    m_lc_no: $scope.m_lc_no,
                    m_lc_date: $scope.m_lc_date,
                    m_ind_be_no: $scope.m_ind_be_no,
                    m_ind_be_date: $scope.m_ind_be_date,

                    t_truck_type: $scope.t_truck_type,
                    t_truck_no: $scope.t_truck_no,
                    t_driver_card: $scope.t_driver_card,
                    t_driver_name: $scope.t_driver_name,
                    t_gweight: $scope.t_gweight,
                    t_nweight: $scope.t_nweight,
                    t_weightment_flag: $scope.t_weightment_flag,

                }
                        console.log(data);
            $http.put("/c&f/api/update-manifest-posting-data", data)
                .then(function (data) {

                    $scope.search( $scope.m_manifest);
                    $scope.updateSuccessMsg = 'Updated Successfully!';
                    $scope.updateBtn=false;
                   // $scope.manif_posted_btn_disable=false;
                    $scope.t_truck_type =null;
                    $scope.t_truck_no =null;
                    $scope.t_driver_card =null;
                    $scope.t_driver_name =null;
                    $scope.t_gweight =null;
                    $scope.t_nweight =null;

                }).catch(function () {

                console.log('error')

            }).finally(function () {

            })


        }

        $scope.deteleConfirm = function (i) {

            $scope.m_manifest = i.m_manifest;
            $scope.t = i.t_id;
            console.log($scope.t);
            // console.log($scope.m_manifest);
            $scope.t_truck_type = i.t_truck_type;
            $scope.t_truck_no = i.t_truck_no;
        }

        $scope.deleteTruck = function () {

            $http.delete("/c&f/api/delete-truck-entry-data/"+$scope.t)
                .then(function (data) {
                    $scope.search( $scope.m_manifest);
                    $scope.m_manifest = $scope.ManifestNo;
                    $scope.t_truck_type = null;
                    $scope.t_truck_no = null;
                    //  console.log(s.status)
                    $scope.deleteSuccessMsg=true;
                    $("#deleteSuccess").show().fadeTo(1000, 500).slideUp(500, function () {
                        $("#deleteSuccess").slideUp(2000);
                    });
                    setTimeout(function () {
                        $("#deleteTrucEntryModal").modal('hide')
                    }, 1500)
                }).catch(function () {

                console.log('error')

            }).finally(function () {

            })
        }

        //manifest Like 324/2/2017 added - 11-6-17
        $scope.keyBoard = function(event) {
            $scope.keyBoardFlag = manifestService.getKeyboardStatus(event);
        }

        $scope.$watchGroup(['ManifestNo','m_manifest'], function(){
            $scope.ManifestNo = manifestService.addYearWithManifest($scope.ManifestNo, $scope.keyBoardFlag);
            $scope.m_manifest = manifestService.addYearWithManifest($scope.m_manifest, $scope.keyBoardFlag);
        });
        //manifest Like 324/2/2017 added - 11-6-17



    });