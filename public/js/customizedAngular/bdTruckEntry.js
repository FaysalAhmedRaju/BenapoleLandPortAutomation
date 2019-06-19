var app = angular.module('BdtruckEntryApp', ['angularUtils.directives.dirPagination', 'ngTagsInput', 'customServiceModule']);
app.controller('bdtruckEntryController', function ($scope, $http, manifestService,$filter, enterKeyService) {
    $scope.buttonBdTruck = true;

    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();

    $scope.role_name = role_name;
    // console.log($scope.role_name);


    //capitalize the TruckType
    $scope.$watch('truck_type', function (val) {

        $scope.truck_type = $filter('uppercase')(val);

    }, true);

    $scope.$watch('driver_name', function (val) {

        $scope.driver_name = $filter('uppercase')(val);

    }, true);

    $scope.$watch('driver_card', function (val) {

        $scope.driver_card = $filter('uppercase')(val);

    }, true);

    $scope.$watch('truck_no', function (val) {

        $scope.truck_no = $filter('uppercase')(val);

    }, true);

    $scope.$watch('goods_id', function (val) {

        $scope.goods_id = $filter('uppercase')(val);

    }, true);

    $scope.$watch('manf_id', function (val) {

        $scope.manf_id = $filter('uppercase')(val);

    }, true);

    $scope.$watch('ManifestNo', function (val) {

        $scope.ManifestNo = $filter('uppercase')(val);

    }, true);




    $scope.loadGoods = function ($query) {
        // An arrays of strings here will also be converted into an
        // array of objects

        console.log($query)
        return $http.get('/truck/api/get-goods-details/' + $query)
            .then(function (response) {
                var cargo_names = response.data;
                return cargo_names.filter(function (v) {
                    return v.cargo_name
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

    $scope.log = [];
    $scope.tagAdded = function (item) {
        $scope.log.push(item.id);
        console.log($scope.log)
    };
    /*
     $scope.tagRemoved = function(item) {
     $scope.log.push(item.id);
     console.log($scope.log)
     };*/

    enterKeyService.enterKey('#truckform input ,#truckform button')

    $scope.driver_name = '--';
    $scope.getSingleManifest = function (m_no) {

        console.log(m_no)

        // $scope.goods_id = null;
        // $scope.disbleManifestNoInpForEditMode = false;//enable manifestno input when searching
        // $scope.SuccessMsg = '';
        // $scope.todaysEntryDiv = false;
        // $scope.updateBtn = false;
        // $scope.allTrucksData = null;
        // $scope.searchNotFound = "";
        // $scope.savingErro = '';
        // $scope.searchFound = null;
        // // $scope.manifest_date = null;
        // $scope.truckDivShow = false;
        // $scope.blank();
        // $scope.submitted = false;

        var data = {
            mani_no: m_no
        }

        console.log(data)
        $scope.dataLoading = true;
        $http.post("/c&f/bd-truck/api/get-bd-truck-data-details", data)
            .then(function (data) {
                console.log(data);

                if (data.data.length >= 1) { //manifest found

                    var s = m_no.split("/");//for 582/2 get 2
                    var n = s[1];
                    console.log(n)

                    if (n == data.data.length || n == 'A' || n == 'a') {//2 from manifestno == total truct data length | means can't add more truck
                        $scope.blank();
                        $scope.ManifestNo = null;
                        $scope.truckform.$setUntouched();

                    }
                    else { //can add more truck
                        $scope.ManifestNo = $scope.manf_id;
                        //   $scope.manifest_date = data.data[0].manifest_date;
                        //   $scope.goods_id=data.data[0].cargo_id;
                        //   get goods id and cargo_name for tag input
                        $http.get('/api/tags/' + m_no)
                            .then(function (data) {

                                $scope.goods_id = data.data
                                console.log(data.data)
                            });

                    }

                    $scope.allTrucksData = data.data;

                    console.log(data.data)
                    $scope.truckDivShow = true;
                    $scope.searchFound = "Manifest exists!";
                    $scope.searchNotFound = null;

                    $scope.totalTruck = data.data.length

                }
                else {
                    if(data.status == 206) {
                        $scope.searchNotFound = data.data.notAuthorized;
                        return;
                    }
                    $scope.allTrucksData = null;
                    $scope.searchNotFound = "Manifest is not found!";
                    $scope.searchFound = null;
                    $scope.ManifestNo = null;
                    // $scope.manifest_date = null;
                    $scope.goods_id = null;
                    $scope.truckDivShow = false;
                    $scope.ManifestNo = $scope.manf_id;
                }

            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            console.log('error')
            $scope.loadingerror = true;

        }).finally(function () {
            $scope.dataLoading = false;
            $scope.loadingerror = false;


        })

    };
    //===============================================truckEntry=================================================================================Here=========
    $scope.bd_driver_name="--";
    $scope.doSearch=function (/*term*/) {


        console.log($scope.manf_id)
        $scope.manifestDataLoadingError=false;

        var data={
            manf_id:$scope.manf_id
        }


        console.log(data)
        $http.post("/c&f/bd-truck/api/get-bd-truck-data-details",data)

            .then(function (data) {
                 console.log(data);

                 if(data.data.length >=1){


                     if(data.data[0].bd_truck_id != null){
                         $scope.allBdLocalData = data.data;
                         console.log($scope.allBdLocalData)
                     }

                     $scope.manif_id = data.data[0].m_id;
                     console.log($scope.manif_id)


                 }else {

                     $scope.searchTextNotFoundTxt='Manifest No: ' +$scope.manf_id
                     $scope.manifestDataLoadingError=true;

                 }





            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            $scope.manifestDataLoadingError=true;



        }).finally(function () {

            console.log('in finally');
            $scope.manifestDataLoading=false;

        })



    }

    $scope.ValidationManifestData = function(form) {
        if(form.$invalid) {
            $scope.submitted = true;
            return false;
        } else {
            $scope.submitted = false;
            return true;
        }
    }

    $http.get("/c&f/bd-truck/api/bd-truck-type-data")
        .then(function(data){
            $scope.truck_type_data = data.data;

            // console.log( $scope.truck_type_data)
            $scope.truck_type = $scope.truck_type_data[0].truck_id;
            // console.log($scope.truck_type)

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


    $scope.CnfBdLocalTrucksaveData=function (form) {


        console.log($scope.role_name);
        console.log($scope.manif_id)


        // if ($scope.GetManiID == null) {
        //
        //     console.log('cant save without searching')
        //     $scope.BdTruckManiIDBlankMsg = true;  //for showing meg and return if save btn is clicked without doing B/E.
        //     return;
        // }
        if($scope.ValidationManifestData(form) == false) {
            return 0;
        }
        // else {
            var d = new Date(); // for now
            d.getHours(); // => 9
            d.getMinutes(); // =>  30
            d.getSeconds(); // => 51


            // var deliveryDate=
            var data = {

                manf_id: /*$scope.GetManiID*/$scope.manif_id,
                bd_truck_id:$scope.bdTruckIdForUpdate,
                truck_no: $scope.bd_truck_no,
                truck_type_id: $scope.truck_type,
                driver_name: $scope.bd_driver_name,

                // labor_load: $scope.labor_load, //==null ? 0: $scope.labor_load,
                // labor_package: $scope.labor_package,
                // equip_load: $scope.equip_load,// ==null ? 0: $scope.equip_load,
                // equipment_package: $scope.equipment_package,
                // equip_name: $scope.equip_name,
                delivery_dt:$scope.delivery_dt+' '+d.getHours()+':' + d.getMinutes() + ':'+d.getSeconds()
                // weightment_flag:$scope.weightment_flag,
                // haltage_day : $scope.haltage_day

            }
            console.log(data)

            //  return;

            $http.post("/c&f/bd-truck/api/local-truck-save-data", data)

                .then(function (data) {
                    console.log(data.data);


                    if(data.data=='saved')
                    {
                        $scope.saveBdTruckSuccess=true;
                        $scope.saveBdTruckSuccessMsg='Saved';
                    }
                    else if(data.data=='updated') {
                        $scope.saveBdTruckSuccess=true;
                        $scope.saveBdTruckSuccessMsg='Updated';
                    }
                    else {
                        console.log(data.data)
                        $scope.savingBdTruckError = true;
                    }

                    $("#saveDbTruckSuccessMsg").show().fadeTo(2500, 500).slideUp(500, function () {
                        $("#saveDbTruckSuccessMsg").slideUp(1000);
                    });

                    $scope.doSearch($scope.manif_id);

                    $scope.bd_truck_no=null;
                    // $scope.truck_type = null;
                    $scope.bd_driver_name="--";
                    // $scope.labor_load=null;
                    // $scope.labor_package=null;
                    // $scope.equip_load=null;
                    // $scope.equipment_package=null;
                    // $scope.equip_name=null;
                    // $scope.delivery_dt=null;
                    // $scope.weightment_flag = '0';
                    // $scope.haltage_day = null;
                    // form.$setUntouched();
                    $('#saveBdTruckData').html('Save')
                    // $scope.bdTruckIdForUpdate=null;




                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingBdTruckError = true;

            }).finally(function () {

            })
        // }
    }



    $scope.editBdTruck=function (i) {
        /* if($scope.assesmentStatus = "Assessment Done") {
         $scope.assesmentDoneError = true;
         return;
         }*/

        console.log(i);
        $scope.saveBdTruckSuccess=false;
        $scope.savingBdTruckError=false;
        $scope.BdTruckNoFullBtnDisable=false;


        $('#saveBdTruckData').html('Update')

        var truckNOSplit = i.truck_no.split("-");


        $scope.bd_truck_no=truckNOSplit[0];


        $scope.bd_driver_name=i.driver_name;
        $scope.truck_type =i.truck_type_id;



        $scope.bdTruckIdForUpdate =i.bd_truck_id;

        console.log($scope.bdTruckIdForUpdate)



    }


    $scope.deleteBdTruck=function (i) {

        console.log(i.bd_truck_id);

        $http.get("/c&f/bd-truck/api/delete-bd-truck-entry-data/"+i.bd_truck_id)
            .then(function (data) {

                $scope.doSearch($scope.manif_id);

                $scope.bDTruckdeletesuccessmsg=true;
                $scope.savingBdTruckError=false;
                $scope.saveBdTruckSuccess=false;

                $("#bDTruckdeletesuccessmsg").show().fadeTo(1500, 500).slideUp(500, function () {
                    $("#bDTruckdeletesuccessmsg").slideUp(1000);
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

    }

    //===============================================EndTruckEntry=============================================================================Here========






    $scope.updateData = function (form) {

        console.log(form.$valid)

        if (form.$valid && $scope.goods_id != []) {




            var goods_array = [];
            var new_goods_array = [];
            angular.forEach($scope.goods_id, function (v, k) {
                // console.log(v.id==undefined)
                if (v.id == undefined) {
                    new_goods_array.push(v.cargo_name)
                }
                else {
                    goods_array.push(v.id)
                }
            })
            var all_goods_id = goods_array.join();//this returns comma separated value
            var all_new_goods_name = new_goods_array//this return array


            var data = {
                id: $scope.t_id,
                manf_id: $scope.e.manifest_Id,
                truck_type: $scope.truck_type,
                truck_no: $scope.truck_no,
                goods_id: all_goods_id,
                new_goods: all_new_goods_name,
                ManifestNo: $scope.ManifestNo,
                driver_card: $scope.driver_card,
                driver_name: $scope.driver_name,
                manifest_date: $scope.manifest_date,
                weightment_flag: $scope.weightment_flag,
                truckentry_datetime : $scope.truckentry_datetime

            }

            console.log(data);

            console.log($scope.truckentry_datetime);
            $scope.updatingData = true;
            $http.put("/truck/api/save-truck-entry-data", data)
                .then(function (data) {


                    $scope.getSingleManifest($scope.ManifestNo);

                    $scope.successMsg = true;
                    $("#success").show().fadeTo(1500, 500).slideUp(500, function () {
                        $("#success").slideUp(2000);
                    });
                    $scope.successMsgTxt = 'Updated!';
                    $scope.idSelectedRow=0;


                    $scope.blank();
                    $scope.submitted = false;
                    $scope.manf_id = $scope.ManifestNo
                    $scope.updateBtn = false;
                    $scope.searchNotFound = '';
                    $scope.truckDivShow = false;


                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.errorMsg = true;
                $("#error").show().fadeTo(1500, 500).slideUp(1500, function () {
                    $("#error").slideUp(1000);
                });
                $scope.errorMsgTxt = 'Something went wrong!';


            }).finally(function () {

                $scope.updatingData = false;

            })

        }
        else {
            $scope.submitted = true;
            return;
        }

    }


    $scope.deleteConfirm = function (i) {
        $scope.d = {};
        console.log(i);

        $scope.d.truck_no = i.truck_no;
        $scope.d.truck_type = i.truck_type
        $scope.d.t_id = i.t_id;
        $scope.d.ManifestNo = i.manifest;
        $scope.idSelectedRow = i.t_id;

    }

    $scope.deleteTruck = function () {

        $http.get("/truck/api/delete-truck-entry/" + $scope.d.t_id)
            .then(function (data) {

                console.log(data)

                $scope.getSingleManifest($scope.d.ManifestNo);

                $scope.manf_id = $scope.d.ManifestNo;


                //  console.log(s.status)
                $scope.deleteSuccessMsg = true;
                $scope.deleteSuccessMsgTxt=data.data;
                $("#deleteSuccess").show().fadeTo(1000, 500).slideUp(1500, function () {
                    $("#deleteSuccess").slideUp(2000);
                });

                setTimeout(function () {
                    $("#deleteManifestConfirm").modal('hide');
                    $scope.idSelectedRow = 0;

                }, 2500)


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


    $scope.blank = function () {

        $scope.t_id = null;
        $scope.truck_type = null;
        $scope.truck_no = null;
        $scope.goods_id = null;
        $scope.driver_card = null;
        $scope.driver_name = '--';
        $scope.manifest_date = null;
        $scope.weightment_flag = '1';


    }
    //------------------------------------Exit-------------------------------------------

    $scope.exitDetails = function (truck) {
        $scope.exit_id = truck.t_id;
        $scope.exit_manifest = truck.manifest;
        //$scope.exit_manifest_date = truck.manifest_date;
        $scope.exit_truck_no = truck.truck_type + "-" + truck.truck_no;
    }

    $scope.getOutForeignTruck = function () {
        var data = {
            truck_id: $scope.exit_id,
            out_comment: $scope.out_comment
        }
        //console.log(data);

        $http.post("/truck/api/gate-out-record", data)
            .then(function (data) {
                //console.log(data.data);
                //$scope.exitSuccessfull = true;
                $scope.out_comment = null;
                $scope.exit_id = null;
                //$scope.whenExitSuccessfull = true;
                //console.log($scope.whenExitSuccessfull);
            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            //$scope.exitError = true;
        }).finally(function () {
            $scope.getSingleManifest($scope.exit_manifest);
        })
    }

    //------------------------------------Exit-------------------------------------------

    //29-5-17 Manifest No + Year
    //7-6-17  custom service added
    $scope.keyBoard = function (event) {
        $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
    }

    $scope.$watchGroup(['manf_id', 'ManifestNo'], function () {
        $scope.manf_id = manifestService.addYearWithManifest($scope.manf_id, $scope.keyboardFlag);
        $scope.ManifestNo = manifestService.addYearWithManifest($scope.ManifestNo, $scope.keyboardFlag);
    });

});

app.filter('weightmentFilter', function () {
    return function (val) {

        var weifg;

        //console.log(tel)

        if (val == 1) {
            return weifg = 'Yes';

        }

        return 'No';
    };
});


app.directive("limitTo", [function () {
    return {
        restrict: "A",
        link: function (scope, elem, attrs) {
            var limit = parseInt(attrs.limitTo);
            angular.element(elem).on("keypress change", function (e) {
                if (this.value.length == limit) e.preventDefault();
            });
        }
    }
}]);



