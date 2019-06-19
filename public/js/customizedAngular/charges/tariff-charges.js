angular.module('tariffApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('tariffController', function($http, $scope,enterKeyService){

        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        $scope.port_id_search = port_id_current.toString();
        // $scope.v_i = 0;
        // angular.forEach(portList, function (value, key)
        // {
        //     $scope.v_i = $scope.v_i + 1;
        //     $scope.port_id_list = key;
        //
        // });
        // console.log($scope.v_i == 1);
        // if($scope.v_i == 1){
        //     $scope.port_id = $scope.port_id_list.toString();
        // }
        // console.log($scope.port_id_list);

        var today = new Date();
        var Y = today.getFullYear();
        var M = today.getMonth()+1;
        var D = today.getDate();

        if(today.getMonth()+1 < 10)
            M = "0"+M;
        if(today.getDate() < 10)
            D = "0"+D;
        $scope.tariff_year_search = Y;


        $scope.ifLimitlessDayDisable = false;
        $scope.slabDisableUpdate = false;
        $http.get("/charges/tariff/api/get-charge-year-data")
            .then(function(data) {
                console.log(data);
                $scope.yearData = data.data;


            }).catch(function(r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }
        }).finally(function() {

        });
        //pagination Every Page serial number......
        $scope.serial = 1;
        $scope.itemPerpage = 10;
        $scope.getPageCount = function(n){
            $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        }
        //pagination serial number   End ......

        $scope.chargeYearGoods = function (values) {
            console.log(values);
            console.log($scope.port_id);
            console.log($scope.tariff_year);
            if(($scope.port_id == undefined) || ($scope.port_id == '')){
                $scope.port_id = null;
            }
                $http.get("/charges/tariff/api/get-tariff-goods-data/" + $scope.port_id +"/"+$scope.tariff_year)
                    .then(function (data) {
                        console.log(data);

                        $scope.getallGoodsData = data.data;

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
        $scope.getTariffGoodsChargesData = function(searchValue) {

            console.log($scope.port_id_search);
            console.log($scope.tariff_year_search);
            if(($scope.port_id_search == undefined) || ($scope.port_id_search == '')){
                $scope.port_id_search = null;
            }
            if(($scope.tariff_year_search == undefined) || ($scope.tariff_year_search == '')){
                $scope.tariff_year_search = null;
            }
            console.log($scope.port_id_search);
            console.log($scope.tariff_year_search);
            $http.get("/charges/tariff/api/tariff/get-all-tariff-data/" + $scope.port_id_search +"/"+$scope.tariff_year_search)
                .then(function(data) {

                    console.log(data.data);
                    if(data.data.length > 0){
                        $scope.allTariffData = data.data;
                        // if($scope.v_i == 1){
                        //     $scope.port_id = $scope.port_id_list.toString();
                        // }

                    }else {
                        $scope.allTariffData = data.data;
                        $scope.chargeError='No Data Found!';
                        $("#chargeError").show().fadeTo(1500, 300).slideUp(300, function () {
                            $("#chargeError").slideUp(1000);
                        });

                    }
                    // if(data.data.length>0) {

                        // $scope.BonusTable = true;


                    // }else {
                    //     // $scope.BonusTable = false;
                    // }

                }).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            }).finally(function() {

            })
        };

        $scope.limitlessDayCheckBox = function (limitless_day) {

            console.log(limitless_day);

            if(limitless_day == true){
                $scope.ifLimitlessDayDisable = true;
                $scope.end_day = -1;

            }else {
                $scope.ifLimitlessDayDisable = false;
            }

        }



        $scope.getTariffGoodsChargesData();

        enterKeyService.enterKey('#TariffForm input ,#TariffForm button')




        $scope.SaveUpdateBtn = false;
        $scope.SaveBonusBtn = true;

        $scope.editBonous =function (value) {

            if(value.to == -1){

                $scope.ifLimitlessDayDisable = true;
                $scope.limitless_day = true;

            }else {
                $scope.ifLimitlessDayDisable = false;
                $scope.limitless_day = false;

            }

            console.log(value);
            $scope.SaveUpdateBtn = true;
            $scope.SaveBonusBtn = false;
            $scope.slabDisableUpdate = true;
            $scope.tariff_year = value.tariff_goods_year;
            $scope.port_id = value.port_id.toString();
            $scope.chargeYearGoods();
            $scope.tariff_goods = value.tariff_good_id,
            $scope.slab_position = value.slab,
            $scope.start_day = value.from,
            $scope.end_day = value.to,
            $scope.shed_charge = parseFloat(value.shed_charge),
            $scope.yard_charge = parseFloat(value.yard_charge),
            $scope.tariff_id = value.id
        }


    var min = new Date().getFullYear()-5;
    var max = min + 10;
    $scope.years = [];
    var j=0;
    for (var i = min; i<=max; i++){
        $scope.years[j++] = {value: i, text: i};
    }
    console.log($scope.years);

        $scope.validationFunction = function() {

            if($scope.TariffForm.$invalid) {

                $scope.submittedTariffForm = true;
                return false;

            } else {

                $scope.submittedTariffForm = false;
                return true;

            }

        }

//----------------------------------------------------save Bonus------------------------------------------------------------------------------//
        $scope.SaveBonus = function () {

            if($scope.validationFunction()==false)
            {
                return;
            }

            var data={
                tariff_goods: $scope.tariff_goods,
                slab_position: $scope.slab_position,
                start_day: $scope.start_day,
                end_day: $scope.end_day,
                shed_charge: $scope.shed_charge,
                yard_charge: $scope.yard_charge

            }
            console.log(data)
            $http.post("/charges/tariff/api/tariff/save-tariff-data",data)
                .then(function (data) {
                    console.log(data);
                    if(data.data == 'saved'){
                        $scope.SuccessBonus='Saved Successfully.';
                        $("#SuccessBonus").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#SuccessBonus").slideUp(7000);
                        });
                        $scope.tariff_year_search = $scope.tariff_year;
                        $scope.port_id_search =  $scope.port_id;
                        $scope.ifLimitlessDayDisable = false;
                        $scope.limitless_day = false;
                        $scope.tariff_goods = null;
                        $scope.slab_position = null;
                        $scope.start_day = null;
                        $scope.end_day = null;
                        $scope.shed_charge = null;
                        $scope.yard_charge = null;
                    }else {
                        $scope.bonusdError='wrong Slab Can Not Entry!';
                        $("#bonusdError").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#bonusdError").slideUp(7000);
                        });

                    }


                }).catch(function (r) {

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.bonusdError='Something wet worng!';
                $("#bonusdError").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#bonusdError").slideUp(7000);
                });
            }).finally(function () {
                $scope.getTariffGoodsChargesData();
            })



        }



        $scope.updateBonus = function (value) {
            if($scope.validationFunction()==false)
            {
                return;
            }

            var data={
                tariff_goods:$scope.tariff_goods,
                slab_position: $scope.slab_position,
                start_day: $scope.start_day,
                end_day: $scope.end_day,
                shed_charge: $scope.shed_charge,
                yard_charge: $scope.yard_charge,
                id:$scope.tariff_id
            }
            console.log(data);
            $http.put("/charges/tariff/api/tariff/update-tariff-data",data)
                .then(function(data) {
                    $scope.SaveUpdateBtn = false;
                    $scope.SaveBonusBtn = true;
                    $scope.SuccessIncreaseUpdate = 'Update successfully.';
                    $("#SuccessIncreaseUpdate").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#SuccessIncreaseUpdate").slideUp(7000);
                    });
                    $scope.ifLimitlessDayDisable = false;
                    $scope.limitless_day = false;
                    $scope.slabDisableUpdate = false;
                    $scope.tariff_year_search = $scope.tariff_year;
                    $scope.port_id_search =  $scope.port_id;
                    $scope.tariff_goods = null;
                    $scope.slab_position = null;
                    $scope.start_day = null;
                    $scope.end_day = null;
                    $scope.shed_charge = null;
                    $scope.yard_charge = null;



                }).catch(function(r){

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.ErrorIncreaseUpdate = 'Something went wrong.';
                $("#ErrorIncreaseUpdate").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#ErrorIncreaseUpdate").slideUp(7000);
                });
            }).finally(function(){
                $scope.getTariffGoodsChargesData();
            })
        }



        $scope.deleteBonus = function (value) {
            console.log(value);
            var id = value.id;
            console.log(id);
            bootbox.confirm({
                message: "Do you want to delete?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {

                    $scope.deleteOnlyBonus(result, id);

                }
            }).css({
                'text-align':'center',
                'top':'0',
                'bottom': '0',
                'left': '0',
                'right': '0',
                'margin': 'auto'
            });
        }

        $scope.deleteOnlyBonus = function(result, id) {
            if(result == true) {
                $http.delete("/charges/tariff/api/tariff/delete-tariff-data/"+id)
                    .then(function(data){

                        if(data.data == 'Deleted') {

                            $scope.SuccessBonus = 'Deleted successfully.';
                            $("#SuccessBonus").show().fadeTo(6500, 500).slideUp(500, function () {
                                $("#SuccessBonus").slideUp(7000);
                            });
                        }
                    }).catch(function(r){
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                    $scope.bonusdError = 'Something went wrong.';
                    $("#bonusdError").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#bonusdError").slideUp(7000);
                    });

                }).finally(function(){

                    $scope.getTariffGoodsChargesData();

                })
            }else {
                return false;
            }
        }


    }).filter('numberToText', function () {
    return function (val) {
        var text = val;
        if(val == -1){
            return text='OnWord';
        } else {
            return text;
        }
        return text;
    }
});