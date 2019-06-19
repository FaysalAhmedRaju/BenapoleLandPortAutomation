angular.module('tariffApp', ['angularUtils.directives.dirPagination','customServiceModule'])
    .controller('tariffController', function($http, $scope,$filter,enterKeyService){

        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        $scope.port_id_search = port_id_current.toString();
        console.log($scope.port_id_search);

        $scope.v_i = 0;
        angular.forEach(portList, function (value, key)
        {
            $scope.v_i = $scope.v_i + 1;
            $scope.port_id_list = key;

        });
        console.log($scope.v_i == 1);
        if($scope.v_i == 1){
           $scope.port_id = $scope.port_id_list.toString();
        }
        console.log($scope.port_id_list);

        var today = new Date();
        var Y = today.getFullYear();
        var M = today.getMonth()+1;
        var D = today.getDate();

        if(today.getMonth()+1 < 10)
            M = "0"+M;
        if(today.getDate() < 10)
            D = "0"+D;
        $scope.tariff_year_search = Y;
   // console.log($scope.entry_datetime);
        //
        // $scope.port_id_current
        //pagination Every Page serial number......
        $scope.serial = 1;
        $scope.itemPerpage = 10;
        $scope.getPageCount = function(n){
            $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        }
        //pagination serial number   End ......

        $scope.submitted = false;
       // $scope.free_time_flag = "0";

        $scope.$watch('goods_name', function (val) {

            $scope.goods_name = $filter('uppercase')(val);

        }, true);

        $scope.getTariffGoodsChargesData = function(searchValue) {
            $scope.submitted = false;

            if(($scope.port_id_search == undefined) || ($scope.port_id_search == '')){
                $scope.port_id_search = null;
            }
            if(($scope.tariff_year_search == undefined) || ($scope.tariff_year_search == '')){
                $scope.tariff_year_search = null;
            }

            $http.get("/charges/tariff-goods/api/tariff/get-all-tariff-goods-data/" + $scope.port_id_search +"/"+$scope.tariff_year_search)
                .then(function(data) {
                    console.log(data.data);
                    if(data.data.length > 0){
                        $scope.allTariffData = data.data;
                        if($scope.v_i == 1){
                            $scope.port_id = $scope.port_id_list.toString();
                        }
                    }else {
                        $scope.noGoodsError='No Tariff Goods Found!';
                        $("#noGoodsError").show().fadeTo(1500, 300).slideUp(300, function () {
                            $("#noGoodsError").slideUp(1000);
                        });
                        $scope.allTariffData = data.data;
                    }




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
        $scope.getTariffGoodsChargesData();
        enterKeyService.enterKey('#TariffGoodsForm input ,#TariffGoodsForm button')
        $scope.SaveUpdateBtn = false;
        $scope.SaveBonusBtn = true;
        $scope.editBonous =function (value) {
                        console.log(value);
                        $scope.SaveUpdateBtn = true;
                        $scope.SaveBonusBtn = false;
                        $scope.submitted = false;
                        $scope.goods_year = value.year,
                        $scope.port_id =  value.port_id.toString(),
                        $scope.goods_name = value.particulars,
                        $scope.basis_charge = value.basis_of_charges,
                        $scope.description   = value.description,
                        $scope.tariff_goods_id = value.id,
                        $scope.free_time_flag = value.flag.toString(),
                        $scope.free_time_id = value.free_id
        }


        var min = new Date().getFullYear()-5;
        var max = min + 10;
        $scope.years = [];
        var j=0;
        for (var i = min; i<=max; i++){
            $scope.years[j++] = {value: i, text: i};
        }
        console.log($scope.years);



        $scope.Save = function (form) {
            if (form.$valid && $scope.goods_name != '' && $scope.basis_charge != '' && ($scope.goods_name != undefined) && ($scope.basis_charge != undefined)) {
            var data={
                goods_year : $scope.goods_year,
                port_id : $scope.port_id,
                goods_name : $scope.goods_name,
                basis_charge : $scope.basis_charge,
                description : $scope.description,
                free_time_flag : $scope.free_time_flag
            }
            console.log(data);

            $http.post("/charges/tariff-goods/api/tariff/save-goods-data",data)
                .then(function (data) {
                    console.log(data);
                    if(data.data == 'Duplicate'){

                        $scope.bonusdError='Duplicate Can Not Entry!';
                        $("#bonusdError").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#bonusdError").slideUp(7000);
                        });


                    }else {

                        $scope.SuccessBonus='Saved Successfully.';
                        $("#SuccessBonus").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#SuccessBonus").slideUp(7000);
                        });

                        $scope.tariff_year_search = $scope.goods_year;
                        $scope.port_id_search = $scope.port_id;
                        $scope.goods_year = null;
                        $scope.port_id = null;
                        $scope.goods_name = null;
                        $scope.basis_charge = null;
                        $scope.description = null;
                        $scope.submitted = false;
                        $scope.free_time_flag = '0';
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

            }else {
                $scope.submitted = true;
                return;
            }

        }



        $scope.updateBonus = function (form) {
            console.log($scope.goods_name)
            console.log($scope.basis_charge)
            console.log($scope.submitted)
            console.log(form.$valid);

            if(form.$valid && $scope.goods_name != '' && $scope.basis_charge != '' ) {

            var data={
                goods_year : $scope.goods_year,
                port_id : $scope.port_id,
                goods_name : $scope.goods_name,
                basis_charge : $scope.basis_charge,
                description : $scope.description,
                id         :  $scope.tariff_goods_id,
                free_id :  $scope.free_time_id,
                free_time_flag : $scope.free_time_flag
            }
            console.log(data);

            $http.put("/charges/tariff-goods/api/tariff/update-goods-data",data)
                .then(function(data) {

                    if(data.data == 'Duplicate'){

                        $scope.bonusdError='Already Exists Can Not Update!';
                        $("#bonusdError").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#bonusdError").slideUp(7000);
                        });

                    }else {
                        $scope.SaveUpdateBtn = false;
                        $scope.SaveBonusBtn = true;
                        $scope.SuccessIncreaseUpdate = 'Update successfully.';
                        $("#SuccessIncreaseUpdate").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#SuccessIncreaseUpdate").slideUp(7000);
                        });
                        $scope.tariff_year_search = $scope.goods_year;
                        $scope.port_id_search = $scope.port_id;
                        $scope.goods_year = null;
                        $scope.port_id = null;
                        $scope.goods_name = null;
                        $scope.basis_charge = null;
                        $scope.description = null;
                        $scope.free_time_flag = '0';
                        $scope.submitted = false;
                    }

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

            }else {
                $scope.submitted = true;
                return;
            }
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
                $http.delete("/charges/tariff-goods/api/tariff/delete-goods-data/"+id)
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
            return text='On Word';
        } else {
            return text;
        }
        return text;
    }
}).filter('freeTimeFlagValue', function () {
    return function (val) {
        var text = val;
        if(val == 0){
        // <i style="color: lightgreen;" ng-if="i.free_flag == 1" class="fa fa-check fa-lg" aria-hidden="true"></i>
        //         <i style="color: lightblue;" ng-if="i.free_flag == 0" class="fa fa-close fa-lg" aria-hidden="true"></i>
            return text='No';
        } else {
            return text='Yes';
        }
        return text;
    }
});