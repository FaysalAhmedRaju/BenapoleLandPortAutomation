var app = angular.module('selfOrTrucktorEntryApp', ['angularUtils.directives.dirPagination', 'ngTagsInput', 'customServiceModule']);
app.controller('selfOrTrucktorEntryController', function ($scope, $http, manifestService, $filter, enterKeyService) {

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
    $scope.driver_card = '--';
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
        $scope.dataLoading = true;
        $http.post("/truck/api/get-single-manifest-data", data)
            .then(function (data) {
                console.log(data.data[0]);
                console.log(data.data.length);

                if (data.data.length >= 1) { //manifest found

                    if (data.data[0].vehicle_type_flag <= 10) {//check if the manifest is self or truck
                        $scope.searchNotFound = 'This Manifest No. Is Not For Self! Please Try From Normal Truck Entry Form';
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

//get goods id and cargo_name for tag input
                        $http.get('/truck/api/get-goods-id-for-tags/' + m_no)//this api load goods name for the manifest ==have to minimize
                            .then(function (data) {
                                $scope.goods_id = data.data
                                console.log($scope.goods_id);
                            });
                    }
                    $scope.allTrucksData = data.data;
                    $scope.truckDivShow = true;
                    $scope.searchFound = "Manifest exists!";
                    $scope.searchNotFound = null;
                    $scope.totalTruck = data.data.length;
                    console.log($scope.totalTruck);
                    $scope.vehile_type_flage = data.data[0].vehicle_type_flag.toString();


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

    // Truck Chassis ----------------------------------------------------Vehicle Type Start ----------------------------------------------------------- start
    // $scope.truck_chassis = true;


//Save manifest data function

    $scope.saveData = function (form) {
        console.log($scope.vehile_type_flage);
        if (form.$valid) {

            var s = $scope.ManifestNo.split("/");//for 582/2 get 2
            var n = s[1];
            console.log(n);
            console.log(isNaN(n));

            if (isNaN(n)) {
                $scope.searchNotFound = 'This manifest is not valid for Self';
                return;
            }


            $scope.savingData = true;
            $scope.savingSuccess = '';
            $scope.savingErro = '';
            $scope.SuccessMsg = '';

            var goods_array = [];
            var new_goods_array = [];
            angular.forEach($scope.goods_id, function (v, k) {
                // console.log(v.id==undefined)
                if (v.id == undefined) {
                    new_goods_array.push(v.cargo_name)
                } else {
                    goods_array.push(v.id)
                }
            })
            var all_goods_id = goods_array.join();//this returns comma separated value
            var all_new_goods_name = new_goods_array//this return array
            console.log(all_goods_id);
            console.log(all_new_goods_name);


            var data = {
                truck_type: $scope.truck_type,
                truck_no: $scope.truck_no,
                // receive_datetime: $scope.receive_datetime,
                vehicle_type_flag: $scope.vehile_type_flage,
                t_posted_yard_shed: $scope.t_posted_yard_shed,
                goods_id: all_goods_id,
                new_goods: all_new_goods_name,
                manifest: $scope.ManifestNo,
                self_flag: 1,//as this is from self entry form
                driver_card: $scope.driver_card,
                driver_name: $scope.driver_name,
                weightment_flag: $scope.weightment_flag,
                truckentry_datetime: $scope.truckentry_datetime
                // manifest_date: $scope.manifest_date + " " + h + ":" + m + ":" + s
            }
            console.log($scope.truckentry_datetime);
            console.log(data);


            $http.post("/truck/api/save-truck-entry-data", data)
                .then(function (data) {

                    console.log(data)
                    //return;
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
                    $("#success").show().fadeTo(1500, 500).slideUp(500, function () {
                        $("#success").slideUp(1000);
                    });
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
        //$scope.chassis_id = i.chassis_id;
        //  console.log($scope.chassis_id);
        $scope.e.manifest_Id = i.m_id;
        $scope.truck_type = i.truck_type;
        $scope.truck_no = i.truck_no;

//get goods id and cargo_name for tag input

        $http.get('/truck/api/get-goods-id-for-tags/' + i.manifest)
            .then(function (data) {
                $scope.goods_id = data.data;
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
        console.log(i);
        console.log(i.driver_card);
        $scope.driver_card = i.driver_card;
        console.log($scope.driver_card);
        $scope.driver_name = i.driver_name;

        // console.log(i.receive_datetime);
        //  $scope.receive_datetime = i.receive_datetime;
        $scope.weightment_flag = i.weightment_flag.toString();
        $scope.vehile_type_flage = i.vehicle_type_flag.toString();
        console.log(i.vehicle_type_flag);

        // Truck Chassis ----------------------------------------------------Vehicle Type Start ----------------------------------------------------------- start
        // $scope.truck_chassis = true;

        console.log('vehicle flag: ' + $scope.vehile_type_flage);

        //--------------------------------------------------------------------------------------------Vehicle Type end --------------------------------

        $scope.idSelectedRow = i.t_id;

        $scope.savingSuccess = null;
        $scope.savingErro = null;

        /* $scope.disbleManifestNoInpForEditMode = true;*///disabled manifestno inpiut when in edit mode
        var truckentry_datetime = i.truckentry_datetime.split(" ");
        $scope.truckentry_datetime = truckentry_datetime[0];


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
                }
                else {
                    goods_array.push(v.id)
                }
            })
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
                // receive_datetime: $scope.receive_datetime,
                vehicle_type_flag: $scope.vehile_type_flage,
                t_posted_yard_shed: $scope.t_posted_yard_shed,
                manifest: $scope.ManifestNo,
                driver_card: $scope.driver_card,
                driver_name: $scope.driver_name,
                weightment_flag: $scope.weightment_flag,
                // receive_datetime: $scope.receive_datetime,
                truckentry_datetime: $scope.truckentry_datetime,
                //shed_yard_weight_id : $scope.shed_yard_weight_id


            }
            console.log(data);

            $scope.updatingData = true;
            $http.put("/truck/api/update-truck-entry-data", data)
                .then(function (data) {

                    console.log(data);
                    console.log(data);
                    if (data.status == 203) {
                        $scope.errorMsg = true;
                        $scope.errorMsgTxt = data.data.error;
                        $('#error').show().delay(2000).slideUp(2000);
                        return;
                    }
                    $scope.manf_id = data.data.manifest_no_updated ? data.data.manifest_no_updated: $scope.ManifestNo;
                    $scope.getSingleManifest($scope.manf_id);

                    $scope.successMsg = true;
                    $('#success').show().delay(2000).slideUp(2000);
                    $scope.successMsgTxt = data.data.message;

                    $scope.idSelectedRow = 0;
                    $scope.blank();
                    $scope.submitted = false;
                    $scope.updateBtn = false;
                    $scope.searchNotFound = '';
                    $scope.truckDivShow = false;


                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
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
        //  console.log(i);

        //$scope.chassis_id = i.chassis_id;

        $scope.d.truck_no = i.truck_no;
        $scope.d.truck_type = i.truck_type;
        $scope.d.t_id = i.t_id;
        $scope.d.ManifestNo = i.manifest;
        $scope.idSelectedRow = i.t_id;
        //console.log($scope.chassis_id);
        console.log($scope.d.t_id);

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
                $('#deleteSuccess').show().delay(2000).slideUp(2000);

                setTimeout(function () {
                    $("#deleteManifestConfirm").modal('hide');
                    $scope.idSelectedRow = 0;
                }, 6000)


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


    }

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



