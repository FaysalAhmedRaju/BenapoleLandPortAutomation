angular.module('CompletedChallanApp', ['angularUtils.directives.dirPagination'])
    .controller('CompletedChallanCtrl', function($scope, $http,$filter){


        var today = new Date();
        var Y = today.getFullYear();
        var M = today.getMonth()+1;
        var D = today.getDate();
        if(today.getMonth()+1 < 10)
            M = "0"+M;
        if(today.getDate() < 10)
            D = "0"+D;
        $scope.date = Y+"-"+M+"-"+D;




            $http.get("/export-admin/api/get-all-incomplete-challan-list/")
                .then(function(data){
                    if(data.data.length > 0 ) {
                        $scope.allInCompletedList = data.data;

                    } else {

                    }
                }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            }).finally(function(){

            })


        //pagination Every Page serial number......
        $scope.serial = 1;
        $scope.itemPerpage = 15;
        $scope.getPageCount = function(n){
            $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        }
        //pagination serial number   End ......




        $scope.ChallanDetails = function (i) {
                console.log(i);
                $scope.ChallanNo = i.export_challan_no;
                $scope.ChallanDate = i.challan_date;
                $scope.VehicleType = i.truck_bus_flag  ;
               // $scope.VehicleType = i.truck_bus_flag;
               $scope.Total_Amount = i.total_amount;
               $scope.CreatedDateTime = i.create_datetime;
               $scope.CreatedBy  = i.create_by;
               $scope.miscellaneous_name = i.miscellaneous_name;
               $scope.miscellaneous_charge = i.miscellaneous_charge;
               $scope.Challan_id = i.id;


        }

        $scope.doneChallan = function () {


                console.log($scope.ChallanNo);
                console.log($scope.VehicleType);
                console.log($scope.Challan_id);



                var data = {
                    ChallanNo : $scope.ChallanNo,
                    VehicleType_flag : $scope.VehicleType,
                    Challan_id  : $scope.Challan_id,
                    Total_Amount : $scope.Total_Amount
                }
                $http.post("/export-admin/api/save-export-admin-challan",data)
                    .then(function(response) {

                        console.log(response.data)


                        setTimeout(function () {
                            $("#DoneChallanFormModal").modal('hide')

                        }, 2500)

                        $scope.SuccessMsg = true;
                        $("#SuccessdoneChallan").show().fadeTo(1000, 500).slideUp(1500, function () {
                            $("#SuccessdoneChallan").slideUp(1000);
                        });





                        $http.get("/export-admin/api/get-all-incomplete-challan-list/")
                            .then(function(data){
                                if(data.data.length > 0 ) {
                                    $scope.allInCompletedList = data.data;

                                } else {

                                }
                            }).catch(function(r){
                            console.log(r)
                            if (r.status == 401) {
                                $.growl.error({message: r.data});
                            } else {
                                $.growl.error({message: "It has Some Error!"});
                            }
                        }).finally(function(){

                        })


                    }).catch(function(r) {

                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }

                }).finally(function () {


                });



        }



    }).filter('stringToDate', function ($filter) {
    return function (ele, dateFormat) {
        return $filter('date')(new Date(ele), dateFormat);
    }
}).filter('dateShort', function ($filter) {

    console.log(ele)
    return function (ele, dateFormat) {
        return $filter('date')(new Date(ele), dateFormat);
    }
}).filter('vehicleFilter', function () {
    return function (val) {
        var vehicle;
        if(val==1){
            return vehicle='Truck';
        } else if(val ==0) {
            return vehicle='Bus';
        }
        return sex='';
    }
});