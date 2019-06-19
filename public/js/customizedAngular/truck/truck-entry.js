var app = angular.module('truckEntryApp', ['angularUtils.directives.dirPagination', 'ngTagsInput', 'customServiceModule']);
app.controller('truckEntryController', function ($scope, $http, manifestService, $filter, enterKeyService) {

    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();

    $scope.role_name = role_name;
    console.log($scope.role_name);

    // $scope.truckTypeIdNgShow = true; // truck type will he hide
    // $("#truckTypeId").html('');   // truck type will be hide


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


    $scope.$watch('truck_package', function (val) {

        $scope.truck_package = $filter('uppercase')(val);

    }, true);


    $scope.loadGoods = function ($query) {
        // An arrays of strings here will also be converted into an
        // array of objects
        console.log($query)
        return $http.get('/truck/api/get-goods-details/' + $query)
            .then(function (response) {
                console.log(response);
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
            })

    };

    $scope.log = [];
    $scope.tagAdded = function (item) {
        console.log(item);
        $scope.log.push(item.id);
        console.log($scope.log)
    };
     $scope.tagRemoved = function(item) {
         console.log($scope.log);
     $scope.log.push(item.id);
     console.log($scope.log)
     };

    enterKeyService.enterKey('#truckform input ,#truckform button')

    $scope.driver_name = '--';
    $scope.getSingleManifest = function (m_no) {

        console.log(m_no)

        $scope.goods_id = null;
        $scope.disbleManifestNoInpForEditMode = false;//enable manifestno input when searching
        $scope.SuccessMsg = '';
        $scope.todaysEntryDiv = false;
        $scope.updateBtn = false;
        $scope.allTrucksData = null;
        $scope.searchNotFound = "";
        $scope.savingErro = '';
        $scope.searchFound = null;
        // $scope.manifest_date = null;
        $scope.truckDivShow = false;
        $scope.blank();
        $scope.submitted = false;

        // raju 2-27-2018 start
        //  $scope.vehile_type_flage = '1';
        // $scope.yard_shed = false;
        // raju 2-27-2018 end
        var data = {
            mani_no: m_no
        }
        console.log(data);
        $scope.dataLoading = true;
        $http.post("/truck/api/get-single-manifest-data", data)
            .then(function (data) {
                console.log(data.data[0]);
                console.log(data.data.length);

                if (data.data.length >= 1) { //manifest found

                    if (data.data[0].vehicle_type_flag >= 11) {//check if the manifest is self or truck
                        $scope.searchNotFound = 'This Manifest No. Is For Self! Please Try From Self Entry Form';
                        return;
                    }
                    var s = m_no.split("/");//for 582/2 get 2
                    var n = s[1];
                    console.log(n)
                    if (n == data.data.length || n == 'A' || n == 'a') {//2 from manifestno == total truct data length | means can't add more truck
                        $scope.blank();
                        $scope.ManifestNo = null;
                        $scope.truckform.$setUntouched();

                    } else { //can add more truck
                        $scope.ManifestNo = $scope.manf_id;

                        $http.get('/truck/api/get-goods-id-for-tags/' + m_no)
                            .then(function (data) {
                                console.log(data);
                                console.log(data.data);
                                $scope.goods_id = data.data;
                            });
                    }
                    $scope.allTrucksData = data.data;
                    console.log($scope.allTrucksData.length);
                    $scope.truckDivShow = true;
                    $scope.searchFound = "Manifest exists!";
                    $scope.searchNotFound = null;
                    $scope.totalTruck = $scope.allTrucksData.length;
                    $scope.vehile_type_flage = $scope.allTrucksData[0].vehicle_type_flag.toString();
                    $scope.country_id = $scope.allTrucksData[0].country_id ? $scope.allTrucksData[0].country_id.toString():'0';

                } else {
                    if (data.status == 206) {
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
                    console.log($scope.vehile_type_flage);
                    if ($scope.vehile_type_flage == 11) {
                        $scope.driver_card = "--";
                    }

                }

            }).catch(function (r) {

            console.log(r)
            $scope.loadingerror = true;
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {
            $scope.dataLoading = false;
            $scope.loadingerror = false;

        })
    };

//Save manifest data function
    $scope.saveData = function (form) {
        console.log(form);
        console.log($scope.goods_id);
        console.log(form.$valid)
        if (form.$valid) {
            $scope.savingData = true;
            $scope.savingSuccess = '';
            $scope.savingErro = '';
            $scope.SuccessMsg = '';

            var goods_array = [];
            var new_goods_array = [];
            angular.forEach($scope.goods_id, function (v, k) {
                if (v.id == undefined) {
                    new_goods_array.push(v.cargo_name)
                } else {
                    goods_array.push(v.id)
                }
            })
            var all_goods_id = goods_array.join();//this returns comma separated value
            var all_new_goods_name = new_goods_array;//this return array
            console.log(all_goods_id);
            console.log(all_new_goods_name);


            var data = {
                truck_type: $scope.truck_type,
                truck_no: $scope.truck_no,
                // receive_datetime: $scope.receive_datetime,
                vehicle_type_flag: $scope.vehile_type_flage,
                country_id: $scope.country_id,
                goods_id: all_goods_id,
                new_goods: all_new_goods_name,
                manifest: $scope.ManifestNo,
                self_flag: ($scope.vehile_type_flage == 2 || $scope.vehile_type_flage == 3) ? 2 : 0,
                driver_card: $scope.driver_card,
                driver_name: $scope.driver_name,
                weightment_flag: $scope.weightment_flag,
                truckentry_datetime: $scope.truckentry_datetime,
                truck_weight: $scope.truck_weight,
                truck_package: $scope.truck_package
            }
            console.log(data);
            //return;

            $http.post("/truck/api/save-truck-entry-data", data)
                .then(function (data) {
                    console.log(data);
                    if(data.status == 203) {
                        $scope.errorMsg = true;
                        $scope.errorMsgTxt = data.data.error;
                        $('#error').show().delay(2000).slideUp(2000);
                        return;
                    }
                    $scope.blank();
                    $scope.submitted = false;
                    $scope.getSingleManifest($scope.ManifestNo);
                    $scope.manf_id = $scope.ManifestNo;
                    $scope.successMsg = true;
                    $('#success').show().delay(2000).slideUp(2000);
                    $scope.successMsgTxt = 'Saved!';
                }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else if(r.status == 402){
                        $scope.errorMsg = true;
                        $('#error').show().delay(2000).slideUp(2000);
                        $scope.errorMsgTxt = r.data.error;
                        $scope.blank();
                        $scope.ManifestNo = null;
                        $scope.truckform.$setUntouched();
                        return;
                    }
                    $scope.errorMsg = true;
                    $('#error').show().delay(2000).slideUp(2000);
                    $scope.errorMsgTxt = 'Something went wrong!';
                }).finally(function () {
                    $scope.savingData = false;
                })

        }
        else {
            $scope.submitted = true;
            return;
        }

    }


    $scope.edit = function (i) {
        console.log(i);
        $scope.submitted = false;
        $scope.SuccessMsg = '';
        $scope.e = {};
        $scope.updateBtn = true;
        $scope.m_id = i.m_id;
        console.log($scope.m_id)
        $scope.t_id = i.t_id;
        $scope.e.manifest_Id = i.m_id;
        $scope.truck_type = i.truck_type;
        $scope.truck_no = i.truck_no;
        $scope.country_id= i.country_id ? i.country_id.toString():'0';


        //get goods id and cargo_name for tag input

        $http.get('/truck/api/get-goods-id-for-tags/' + i.manifest)
            .then(function (data) {
                console.log(data);
                console.log(data.data);
                $scope.goods_id = data.data;
                console.log($scope.goods_id);
            }).catch(function (r) {
            console.log(r)

            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });

        // $scope.goods_id=i.cargo_id;
        $scope.ManifestNo = i.manifest;
        $scope.driver_card = i.driver_card;
        $scope.driver_name = i.driver_name;
        $scope.weightment_flag = i.weightment_flag.toString();
        $scope.vehile_type_flage = i.vehicle_type_flag.toString();
        $scope.idSelectedRow = i.t_id;
        $scope.truck_package = i.truck_package;
        $scope.truck_weight = parseFloat(i.truck_weight);
        $scope.savingSuccess = null;
        $scope.savingErro = null;

        $scope.truckentry_datetime = i.truckentry_datetime;
        // console.log(i.truckentry_datetime);
        // var truckentry_datetime = i.truckentry_datetime.split(" ");
        // $scope.truckentry_datetime = truckentry_datetime[0];
        //
        // console.log($scope.truckentry_datetime);


    }


    $scope.updateData = function (form) {

        console.log(form.$valid)
        if (form.$valid && $scope.goods_id != []) {
            var goods_array = [];
            var new_goods_array = [];
            angular.forEach($scope.goods_id, function (v, k) {
                // console.log(v.id==undefined)
                if (v.id == undefined) {
                    new_goods_array.push(v.cargo_name)
                } else {
                    goods_array.push(v.id)
                }
            });

            var all_goods_id = goods_array.join();//this returns comma separated value
            var all_new_goods_name = new_goods_array//this return array

            console.log(all_goods_id);
            console.log(all_new_goods_name);

            var data = {
                truck_id: $scope.t_id,
                manifest_id: $scope.e.manifest_Id,
                truck_type: $scope.truck_type,
                truck_no: $scope.truck_no,
                goods_id: all_goods_id,
                new_goods: all_new_goods_name,
                vehicle_type_flag: $scope.vehile_type_flage,
                country_id: $scope.country_id,
                manifest: $scope.ManifestNo,
                driver_card: $scope.driver_card,
                driver_name: $scope.driver_name,
                weightment_flag: $scope.weightment_flag,
                truckentry_datetime: $scope.truckentry_datetime,
                truck_weight : $scope.truck_weight,
                truck_package : $scope.truck_package

            }
            console.log(data);
            $scope.updatingData = true;
            $http.put("/truck/api/update-truck-entry-data", data)
                .then(function (data) {
                    console.log(data);
                    if (data.status == 203) {
                        $scope.errorMsg = true;
                        $scope.errorMsgTxt = data.data.error;
                        $('#error').show().delay(2000).slideUp(2000);
                        return;
                    }
                    $scope.manf_id = data.data.manifest_no_updated ? data.data.manifest_no_updated : $scope.ManifestNo;
                    $scope.getSingleManifest($scope.manf_id);
                    $scope.successMsg = true;
                    $('#success').show().delay(2000).slideUp(2000);
                    $scope.successMsgTxt = data.data.message;
                    $scope.idSelectedRow = 0;
                    $scope.submitted = false;
                    $scope.updateBtn = false;
                    $scope.searchNotFound = '';
                    $scope.truckDivShow = false;

                    $scope.blank();

                }).catch(function (r) {
                console.log(r)

                if (r.status == 401) {
                    $.growl.error({message: r.data});
                }
                $scope.errorMsg = true;
                $('#error').show().delay(2000).slideUp(2000);
                $scope.errorMsgTxt = 'Something went wrong!';
            }).finally(function () {
                $scope.updatingData = false;

            })

        } else {
            $scope.submitted = true;
        }

    };


    $scope.deleteConfirm = function (i) {
        $scope.d = {};
        $scope.d.truck_no = i.truck_no;
        $scope.d.truck_type = i.truck_type;
        $scope.d.t_id = i.t_id;
        $scope.d.ManifestNo = i.manifest;
        $scope.idSelectedRow = i.t_id;
        console.log(i);

    }

    $scope.deleteTruck = function () {
        console.log($scope.d.t_id);
        $http.get("/truck/api/delete-truck-entry/" + $scope.d.t_id)
            .then(function (data) {
                console.log(data)
                $scope.manf_id = $scope.d.ManifestNo;
                $scope.getSingleManifest($scope.manf_id);

                $scope.deleteSuccessMsg = true;
                $scope.deleteSuccessMsgTxt = data.data.message;
                $('#deleteSuccess').show().delay(6000).slideUp(2000);

               /* setTimeout(function () {
                    $("#deleteManifestConfirm").modal('hide');
                    $scope.idSelectedRow = 0;
                }, 6000)*/


            }).catch(function (r) {
            console.log(r)

            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
            $scope.deleteSuccessMsg = true;
            $('#deleteSuccess').show().delay(2000).slideUp(2000);
            $scope.errorMsgTxt = data.r.message;

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
        $scope.truckentry_datetime = null;
        $scope.truck_package = null;
        $scope.truck_weight = null;


    };

    //------------------------------------Exit-------------------------------------------

    $scope.exitDetails = function (truck) {
        $scope.exit_id = truck.t_id;
        $scope.m_id = truck.m_id;
        console.log($scope.m_id);
        $scope.exit_manifest = truck.manifest;
        //$scope.exit_manifest_date = truck.manifest_date;
        $scope.exit_truck_no = truck.truck_type + "-" + truck.truck_no;
    }

    $scope.getOutForeignTruck = function () {
        var data = {
            truck_id: $scope.exit_id,
            out_comment: $scope.out_comment
        }
        console.log(data);

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
app.filter('stringToDate', function ($filter) {
    return function (ele, dateFormat) {
        return $filter('date')(new Date(ele), dateFormat);
    }
});



