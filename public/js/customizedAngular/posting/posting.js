angular.module('manifestApp', ['ngTagsInput', 'ngTagsInput', 'customServiceModule'])
    .controller('manifestPostingController', function ($scope, $http, $filter, manifestService, enterKeyService) {


        $scope.$watch('ManifestNo', function (val) {

            $scope.ManifestNo = $filter('uppercase')(val);

        }, true);
        $scope.$watch('m_ind_be_no', function (val) {

            $scope.m_ind_be_no = $filter('uppercase')(val);

        }, true);


        $scope.$watch('m_manifest', function (val) {

            $scope.m_manifest = $filter('uppercase')(val);

        }, true);

        $scope.$watch('m_manifest_date', function (val) {

            $scope.m_manifest_date = $filter('uppercase')(val);

        }, true);

        $scope.$watch('m_marks_no', function (val) {

            $scope.m_marks_no = $filter('uppercase')(val);

        }, true);

        $scope.$watch('m_good_id', function (val) {

            $scope.m_good_id = $filter('uppercase')(val);

        }, true);

        $scope.$watch('m_gweight', function (val) {

            $scope.m_gweight = $filter('uppercase')(val);

        }, true);

        $scope.$watch('m_nweight', function (val) {

            $scope.m_nweight = $filter('uppercase')(val);

        }, true);

        $scope.$watch('m_package_no', function (val) {

            $scope.m_package_no = $filter('uppercase')(val);

        }, true);

        $scope.$watch('m_package_type', function (val) {

            $scope.m_package_type = $filter('uppercase')(val);

        }, true);

        $scope.$watch('m_cnf_value', function (val) {

            $scope.m_cnf_value = $filter('uppercase')(val);

        }, true);

        $scope.$watch('m_vat_id', function (val) {

            $scope.m_vat_id = $filter('uppercase')(val);

        }, true);

        $scope.$watch('Importer_Name', function (val) {

            $scope.Importer_Name = $filter('uppercase')(val);

        }, true);


        $scope.$watch('m_exporter_name_addr', function (val) {

            $scope.m_exporter_name_addr = $filter('uppercase')(val);

        }, true);
        $scope.$watch('m_lc_no', function (val) {

            $scope.m_lc_no = $filter('uppercase')(val);

        }, true);

        $scope.$watch('m_lc_date', function (val) {

            $scope.m_lc_date = $filter('uppercase')(val);

        }, true);
        $scope.$watch('m_lc_date', function (val) {

            $scope.m_lc_date = $filter('uppercase')(val);

        }, true);

        $scope.$watch('remark', function (val) {
            $scope.remark = $filter('uppercase')(val);
        }, true);

        //vat ig fetch  start
        if(role_id == 12) {
            console.log("transshiment");
            $('.selectpicker').val([]);
            $('.selectpicker').trigger('change.abs.preserveSelected');
            $('.selectpicker').selectpicker('refresh');
            $('select[name=t_posted_yard_shed]').val(55);
            $('.selectpicker').trigger('change.abs.preserveSelected');
            $('.selectpicker').selectpicker('refresh');
            $scope.t_posted_yard_shed = 55;

        }

        $('#m_vat_id').autocomplete({
            source: "/posting/api/get-vat-name-details",
            minLength: 5,
            highlightItem: true,
            // autoFocus:true,
            // displayKey: 'Importer_Name',
            response: function (event, ui) {
                console.log(ui.content.length);
                // ui.content is the array that's about to be sent to the response callback.
                if (ui.content.length == 0) {
                    $scope.importerNameInput = true;

                    //$("#vat-not-found").text("No Vat No Found");
                    $("#importerNameLabel").html('');
                    $scope.addVat();
                } else {
                    $scope.importerNameInput = false;
                    $("#vat-not-found").empty();
                }
            },
            select: function (event, ui) {
                // event.preventDefault();
                console.log(ui.item);
                $scope.m_vat_id = ui.item.value;
                $("#m_vat_id").val(ui.item.value);
               // $scope.m_vat_id = ui.item.id;
                $scope.vatreg_id = ui.item.vatreg_id;
                $scope.importerNameInput = false;
                $("#importerNameLabel").html(ui.item.desc);
                console.log($scope.m_vat_id);
                return false;
            },
            change: function (event, ui) {

                if (ui.item == null) {
                    //console.log('no match');
                    //$("#m_vat_id").val('');
                    $("#importerNameLabel").html('');
                    $("#m_vat_id").focus();
                    //$scope.m_vat_id = null;
                    $scope.vatreg_id = $("#m_vat_id").val();
                    $("#vat-not-found").empty();
                    $scope.importerNameInput = true;
                }
            },
            focus: function (event, ui) {
                console.log('facus');
                if (ui != null) {
                    defaultVal = ui.item.label;
                    $scope.importerNameInput = false;
                } else {
                    $scope.importerNameInput = true;
                }
            },
            search: function () {
               // console.log('8');

            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div>" + item.label + "<br>" + item.desc + "</div>")
                .appendTo(ul);
        };

        $scope.addVat = function() {
            $scope.VatNo = $("#m_vat_id").val();
            $scope.importerNameInput = true;
            //console.log($scope.VatNo);
        }

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
        //goods edit end

        //package type start
        var packages=[];
        $http.get("/posting/api/get-package-type")
            .then(function (data) {
                console.log(data);
                console.log(data.data[0].package_type)

               // packages=data.data;

                angular.forEach(data.data,function (v,k) {
                    packages.push(v.package_type);
                  //  console.log(v.package_type);

                })
                //console.log(packages);

            }).catch(function (r) {
            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


            });
       /* var packages1=[
            'PKGS',
            'PICES',
            'ROLLS',
            'CARTOON',
            'DRUMS',
            'UNITS'
        ];*/

            $("#m_package_type").autocomplete({
                source: function (request, response) {
                    console.log(packages);
                    var result = $.ui.autocomplete.filter(packages, request.term);
                    //$("#add").toggle($.inArray(request.term, result) < 0);
                    response(result);
                    console.log(result);

                }
            });

           /* $("#add").on("click", function () {
                packages.push($("#m_package_type").val());
                $(this).hide();
            });*/


        //package type end

        $scope.showWhenUpdatebtnClick = true;
        $scope.hidemanifestWhenUpdatebtnClick = false;
        $scope.updateBtnItems = false;
        $scope.manifest_info = false;

        $scope.importer_name_false = false;
        $scope.vat_show_false = true;

        // console.log($scope.vat_no)
        $scope.test_id = 0;
        $scope.vat_idd = $scope.vatId_importer_name;

        // $scope.m_manifest=0;
        // $scope.Manifest_ID=0;
        var flag = 1;
        $scope.getItem = function (i) {
            $http.get('/posting/api/get-goods-name/' + i)
                .then(function (response) {
                    // $scope.goodsName=
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
        };

        $scope.tagRemoved = function (item) {
            $scope.log.push('Removed: ' + item.id);
        };

        enterKeyService.enterKey('#postingform input ,#postingform button')

        // console.log($scope.m_vat_id)
        // console.log($scope.vat_no)
        // console.log($scope.m_vat_id)
        $scope.hideVatNOorImporterName = function () {

            // console.log($scope.vat_no)

            if ($scope.vat_no == 0) {

                // console.log($scope.vat_no)


                $scope.importer_name_false = true;
                $scope.vat_show_false = false;
                $scope.imp_name_from_Importer = true;
                $scope.vat_no_after_Vat = false;


                // if($scope.vat_no==0){
                //
                //         $scope.importer_name_false = false;
                //         $scope.vat_show_false = true;
                // }

                //  $scope.vatId_importer_name ="";
                // $scope.ImorterName = "";
                // if($scope.ImorterName != null){
                //
                // }
                // console.log($scope.m_vat_id)
                // if( $scope.vatId_importer_name  == undefined){
                //
                //     $scope.importer_name_false = false;
                //     $scope.vat_show_false = true;
                //
                // }


            }
            else {
                $scope.importer_name_false = false;
                $scope.vat_show_false = true;
                $scope.imp_name_from_Importer = false;
                $scope.vat_no_after_Vat = true;
                $scope.m_Importer_Name = null;

                $('#m_Importer_Name').val('');
                // $scope.m_vat_id = "";
                // $scope.m_vat_name ="";
            }

            //    console.log($scope.test_id)

        }

        // if($scope.test_id == 0){
        //     $scope.importer_name_false =false;
        //     $scope.vat_show_false = true;
        //     $scope.imp_name_from_Importer=false;
        //     $scope.vat_no_after_Vat = true;
        // }

        console.log($scope.test_id)


        // $scope.importer_name_false =false;
        // $scope.vat_show_false = true;
        // $scope.imp_name_from_Importer=false;
        // $scope.vat_no_after_Vat = true;

        // $scope.importer_name_false = false;
        // console.log($scope.m_Importer_Name)
        // console.log($scope.m_vat_id)
        //
        // $scope.vatFunction = function () {
        //
        //     console.log($scope.m_Importer_Name)
        //     console.log($scope.m_vat_id)
        //
        // }


        $scope.WeightEmty = "";
        $scope.WeightDone = "";
        $scope.searchNotFound = "";
//---------------------------------------------------------Start search Function----------------------------------------
        $scope.search = function (id) {
            $scope.importerNameInput = false;
            $scope.searchNotFound = "";
            // $scope.hidemanifestWhenUpdatebtnClick=false;
            $scope.showWhenUpdatebtnClick = true;
            $scope.selectedTruckNoShowDiv = false;
            $scope.imp_name_from_Importer = false;
            $scope.manifest_info = false;
            $scope.vat_no = "1";
            $scope.vat_show_false = true;
            $scope.importer_name_false = false;
            $scope.vat_no_after_Vat = true;
            $scope.WeightEmty = "";
            $scope.WeightDone = "";
            $scope.SuccessMsg = '';

            $scope.yard_count_no = '';
            $scope.message_1 = '';
            $scope.message_2 = '';
            $scope.manifest_created_time = null;

            $scope.cnf_posted_flag = 0;
            $scope.showBlank();
            // $scope.saveSuccessManifiest = false;
            var data = {

                //   ManifestNo : id  //here id parameter takes the value of Manifest value 581/6 and id value is assaign
                ManifestNo: id

            }
            $scope.dataLoading = true;
            $http.post("/posting/api/search-single-manifest-data", data)

                .then(function (data) {
                    console.log(data.data[0])

                    if (data.data.length == 0) {

                        $scope.notFound = "Manifest not Found!";
                        $scope.blank();

                        $scope.Manifest_ID = null;
                        $scope.searchNotFound = "Manifest not Found!";
                        $scope.m_manifest = null;
                        $scope.m_good_id = null;
                        $scope.m_manifest_date = null;
                        $scope.m_gweight = null;
                        $scope.m_nweight = null;
                        $scope.m_marks_no = null;
                        $scope.m_package_no = null;
                        $scope.m_package_type = null;
                        $scope.m_cnf_value = null;
                        $scope.m_exporter_name_addr = null;
                        $scope.m_vat_id = null;
                        $scope.m_vat_name = null;
                        $scope.m_lc_no = null;
                        $scope.m_lc_date = null;
                        $scope.m_ind_be_no = null;
                        $scope.m_ind_be_date = null;
                        $scope.t_gweight_wbridge = null;

                        $scope.remark = null;
                        //$scope.t_posted_yard_shed = null;
                        $scope.manifest_created_time = null;
                        $scope.table = false;
                        $scope.textOfmanifest = false;
                        $scope.WeightEmty = "";
                        $scope.WeightDone = "";
                        $scope.dataLoading = false;
                        return;

                    } else {//manifest found

                        $http.get('/truck/api/get-goods-id-for-tags/' + id)
                            .then(function (data) {
                               $scope.goods_id = data.data
                                console.log(data.data)
                            }).catch(function (r) {

                            console.log(r)
                            if (r.status == 401) {
                                $.growl.error({message: r.data});
                            } else {
                                $.growl.error({message: "It has Some Error!"});
                            }

                        }).finally(function () {


                        });


                        $scope.show = true;
                        console.log(data.data[0].posted_yard_shed);
                        if(data.data[0].posted_yard_shed != null) {
                            $('.selectpicker').val([]);
                            $('.selectpicker').trigger('change.abs.preserveSelected');
                            $('.selectpicker').selectpicker('refresh');
                            var array = data.data[0].posted_yard_shed.split(',');
                            console.log(array)
                            $('select[name=t_posted_yard_shed]').val(array);
                            $('.selectpicker').trigger('change.abs.preserveSelected');
                            $('.selectpicker').selectpicker('refresh');
                            $scope.t_posted_yard_shed = array;
                        }



                        $scope.Manifest_ID = data.data[0].m_id;
                        $scope.m_manifest = data.data[0].m_manifest;
                        $scope.m_manifest_date = data.data[0].m_manifest_date;
                        $scope.m_package_no = data.data[0].m_package_no;
                        $scope.m_cnf_value = data.data[0].m_cnf_value;
                        $scope.m_gweight = data.data[0].m_gweight;
                        $scope.m_good_id = data.data[0].cargo_name;
                        $scope.m_vat_id = data.data[0].m_vat_id;
                        $scope.vatreg_id=data.data[0].vatreg_id;
                        //$scope.t_posted_yard_shed = data.data[0].posted_yard_shed;
                        $scope.m_nweight = data.data[0].m_nweight;
                        $scope.m_marks_no = data.data[0].m_marks_no;
                        $scope.driver_card = data.data[0].driver_card;
                        $scope.driver_name = data.data[0].driver_name;

                        $scope.m_package_type = data.data[0].m_package_type;

                        $scope.m_exporter_name_addr = data.data[0].m_exporter_name_addr;

                        $("#importerNameLabel").html(data.data[0].importer);
                        $scope.m_lc_no = data.data[0].m_lc_no;
                        $scope.m_lc_date = data.data[0].m_lc_date;
                        $scope.m_ind_be_no = data.data[0].m_ind_be_no;
                        $scope.m_ind_be_date = data.data[0].m_ind_be_date;
                        $scope.t_gweight_wbridge = data.data[0].t_gweight_wbridge;
                        $scope.remark = data.data[0].posting_remark;


                        $scope.driver_card = data.data[0].driver_card;
                        $scope.driver_name = data.data[0].driver_name;
                        $scope.manifest_created_time = data.data[0].manifest_created_time;
                        // console.log($scope.t_posted_yard_shed)
                        $scope.WeightTruckEmty = '';
                        $scope.WeightTruckDone = '';

                        $scope.saveSuccessManifiest = false;
                        if ($scope.t_gweight_wbridge == null) {
                            $scope.WeightEmty = "Weight Bridge Not Done";
                        } else {
                            $scope.WeightDone = "Weight Bridge Done";
                        }
                        $scope.cnf_posted_flag = data.data[0].cnf_posted_flag;
                        $scope.manifest_posted_done_flag = data.data[0].manifest_posted_done_flag;
                        $scope.org_name = data.data[0].org_name;
                        //          }
                        //          else {           //manifest must be  done
                        // $scope.manif_posted_btn_disable = true;
                        //
                        // $scope.manifest=data.data[0].manifest;
                        // $scope.goods_id=data.data[0].goods_id;
                        // $scope.manifest_date=data.data[0].manifest_date;
                        //
                        // $scope.gweight=null;
                        // $scope.nweight=null;
                        // $scope.package_no=null;
                        // $scope.package_type=null
                        // $scope.cnf_value=null;
                        // $scope.exporter_name_addr=null;
                        // $scope.vat_id=null;
                        // $scope.lc_no=null;
                        // $scope.lc_date=null;
                        // $scope.ind_be_no=null;
                        // $scope.ind_be_date=null;
                        //     }

                        //console.log( $scope.Manifest_ID);
                    }

                    $scope.table = true;
                    $scope.textOfmanifest = true;

                    $scope.allManifestData = data.data;
                    console.log($scope.allManifestData);
                    //$scope.showDiv = true;
                    //  $scope.totalTruck = data.data.length;
                    //  console.log($scope.totalTruck)
                    $scope.dataLoading = false;
                }).catch(function (r) {

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {


            });
            /* $http.post("/api/getTrucksWithEmptyYard",data)
             .then(function (data){
             // console.log(data.data)
             $scope.allTrucks=data;
             })
             */
        }
        //----------------------------------------------------End Search Function Here----------------------------------
        $scope.dataLoading = false;


        // $http.get("/api/PortsJson")
        //     .then(function (data){
        //         console.log(data.data)
        //         $scope.allPortsData=data.data;
        //
        //         })

        $http.get("/posting/api/get-vats-data-details")                   //function 2
            .then(function (data) {
                // console.log(data.data)
                $scope.allVatsData = data.data;

            }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });


        /* $scope.getVatsData=function () {
         var data = {
         BIN : $scope.m_vat_id
         }

         $http.post("/api/getVatDetails",data)       //function 4
         .then(function (data) {
         // console.log(data.data[0].NAME);
         $scope.m_vat_name=data.data[0].NAME
         }).catch(function () {
         // $scope.savingErro='Something wet worng!';
         }).finally(function () {
         $scope.savingData=false;

         })
         }
         */
        $scope.YardNOForLevelNO = function () {
            var data = {
                yard_no: $scope.t_posted_yard_shed
            }
            console.log($scope.t_posted_yard_shed);
            $http.post("/truck/api/count-current-date-yard-no", data)
                .then(function (data) {
                    console.log(data.data);
                    $scope.message_1 = "This is";
                    $scope.message_2 = "no.";
                    $scope.yard_count_no = data.data[0].yard_level_no;
                }).catch(function (r) {
                // $scope.savingErro='Something wet worng!';

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            }).finally(function () {
                // $scope.savingData=false;

            })
        };


        $scope.getImporterData = function () {
            var data = {
                NAME: $scope.m_Importer_Name
            };
            $http.post("/api/getVatName", data)
                .then(function (data) {
                    console.log(data.data);
                    console.log(data.data[0].NAME);
                    console.log(data.data[0].BIN);
                    $scope.m_vat_name = data.data[0].NAME
                    $scope.m_Vat_NO = data.data[0].BIN;
                }).catch(function (r) {
                // $scope.savingErro='Something wet worng!';
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            }).finally(function () {
                $scope.savingData = false;

            })
        }


        //-------------------------------------Posting Yard Name is Here------------------------------------

        // $http.get("/api/YardDetailsJson")
        //     .then(function (data) {
        //             console.log(data.data);

        //             $scope.allYardData = data.data;
        //         }
        //     )


//-------------------------------- Saveing Function is started From Here -----------------------------------------------
        $scope.save = function (postingform) {
            console.log($scope.vatreg_id);
            console.log($scope.t_posted_yard_shed);
             console.log($(".selectpicker").val());
            if($scope.importerNameInput == true) {
                console.log($scope.importerNameLabelinput);
            }
            //return;
            console.log(postingform.$invalid);
            console.log($scope.m_package_type);

            if (postingform.$invalid || !$scope.vatreg_id) {
                $scope.submittedPostingForm = true;
                return;
            } else {
                $scope.submittedPostingForm = false;
            }

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
            console.log($scope.t_posted_yard_shed);
            //return;
            var data = {
                m_id: $scope.Manifest_ID,
                m_manifest: $scope.m_manifest,
                m_manifest_date: $scope.m_manifest_date,
                m_marks_no: $scope.m_marks_no,
                goods_id: all_goods_id,
                new_goods: all_new_goods_name,
                m_gweight: $scope.m_gweight,
                m_nweight: $scope.m_nweight,
                m_package_no: $scope.m_package_no,
                m_package_type: $scope.m_package_type,
                m_cnf_value: $scope.m_cnf_value,
                m_exporter_name_addr: $scope.m_exporter_name_addr,
                vatreg_id: $scope.vatreg_id,
                m_lc_no: $scope.m_lc_no,
                m_lc_date: $scope.m_lc_date,
                m_ind_be_no: $scope.m_ind_be_no,
                m_ind_be_date: $scope.m_ind_be_date,
                truck_no: $scope.truckNoEdit,
                t_posted_yard_shed: $scope.t_posted_yard_shed,
                t_gweight: $scope.t_gweight,
                t_nweight: $scope.t_nweight,
                posting_remark: $scope.remark,
                manifest_created_time : $scope.manifest_created_time,
                importerNameLabelinput : $scope.importerNameLabelinput,
                cnf_posted_flag : $scope.cnf_posted_flag
            }
            console.log(data);
            $http.post("/posting/api/save-manifest-posting-data", data)
                .then(function (data) {
                    console.log(data)
                    //return;
                    $scope.saveSuccessManifiest = true;
                    //$scope.t_posted_yard_shed = null;
                    $scope.t_gweight = null;
                    $scope.t_nweight = null;
                    if (data.status == 205) {
                        $scope.errorItemMsg = true;
                        $scope.ItemsChectMsg = 'BIN Number Already Exist!';
                        $("#error").show().fadeTo(1500, 500).slideUp(1500, function () {
                            $("#error").slideUp(2000);
                        });
                        //  console.log("okk");
                        return;
                    }
                    if (data.data[1] == 201) {
                        $scope.errorItemMsg = true;
                        $scope.ItemsChectMsg = 'Please Select Items First!';
                        $("#error").show().fadeTo(1500, 500).slideUp(1500, function () {
                            $("#error").slideUp(2000);
                        });
                        //  console.log("okk");

                        return;
                    }
                    if (data.status == 209) {

                        console.log(data.status);
                        $scope.errorMsg = true;
                        $scope.errorMsgTxt = 'New Goods is already exist!';
                        $("#errorMsg").show().fadeTo(2500, 500).slideUp(500, function () {
                            $("#errorMsg").slideUp(1000);
                        });
                        return;
                    }

                    if (data.status == 203) {
                        
                        $scope.errorMsg = true;
                        $scope.errorMsgTxt = data.data.not_allowed;
                        $("#errorMsg").show().fadeTo(2500, 500).slideUp(500, function () {
                            $("#errorMsg").slideUp(1000);
                        });
                        return;
                    }
                    if (data.status == 210) {
                        $scope.errorMsg = true;
                        $scope.errorMsgTxt = data.data.yard_shed_error;
                        $("#errorMsg").show().fadeTo(2500, 500).slideUp(500, function () {
                            $("#errorMsg").slideUp(1000);
                        });
                        return;
                    }


                    $scope.successMsg = true;
                    $("#success").show().fadeTo(1500, 500).slideUp(500, function () {
                        $("#success").slideUp(1000);
                    });
                    $scope.successMsgTxt = 'Saved';
                    $scope.m_mani_id = $scope.m_manifest;
                    $scope.message = "";
                    $scope.vat_no = '1';


                    $scope.blank();
                    $scope.manifest_info = true;
                    var data = {

                        ManifestNo: $scope.m_mani_id

                    }
                    $http.post("/posting/api/search-single-manifest-data", data)
                        .then(function (data) {
                            console.log(data.data)
                            // $scope.Manifest_ID = data.data[0].m_id;
                            $scope.m_manifest_show = data.data[0].m_manifest;
                            $scope.m_manifest_date_show = data.data[0].m_manifest_date;
                            $scope.m_package_no_show = data.data[0].m_package_no;
                            $scope.m_cnf_value_show = data.data[0].m_cnf_value;
                            $scope.m_gweight_show = data.data[0].m_gweight;
                            // $scope.m_good_id=data.data[0].cargo_name;
                            // $scope.m_vat_id=data.data[0].m_vat_id;
                            $scope.t_posted_yard_shed_show = data.data[0].yard_shed_name;
                            // $scope.m_nweight=data.data[0].m_nweight;
                            // $scope.m_marks_no=data.data[0].m_marks_no;
                            // $scope.driver_card=data.data[0].driver_card;
                            // $scope.driver_name=data.data[0].driver_name;
                            // $scope.m_package_type=data.data[0].m_package_type;
                            // $scope.m_exporter_name_addr=data.data[0].m_exporter_name_addr;
                            $scope.m_vat_name_show = data.data[0].importer;
                            // $scope.m_lc_no=data.data[0].m_lc_no;
                            // $scope.m_lc_date=data.data[0].m_lc_date;
                            // $scope.m_ind_be_no=data.data[0].m_ind_be_no;
                            // $scope.m_ind_be_date=data.data[0].m_ind_be_date;
                            // $scope.t_gweight_wbridge =data.data[0].t_gweight_wbridge;
                            // $scope.remark = data.data[0].posting_remark;
                            // $scope.driver_card=data.data[0].driver_card;
                            // $scope.driver_name=data.data[0].driver_name;

                            $scope.table = true;
                            $scope.textOfmanifest = true;
                            $scope.allManifestData = data.data;

                            // $scope.dataLoading = false;
                            // console.log( $scope.allManifestData)
                        }).catch(function (r) {

                        console.log(r)
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        } else {
                            $.growl.error({message: "It has Some Error!"});
                        }

                    }).finally(function () {


                    });
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

                $scope.savingErro = 'Something wet worng!';

            }).finally(function () {

                $scope.savingData = false;

            })


        }


        $scope.saveSuccess = false;
        // -------------------------------------End Saveing Function-------------------------------------//
        // $scope.addItems = function () {
        //
        //     var  data = {
        //
        //         item_Code:$scope.item_Code_id,
        //         item_weight:$scope.item_weight,
        //         item_package:$scope.item_package,
        //         manf_id:$scope.Manifest_ID
        //
        //     }
        //
        //     console.log(data);
        //     console.log($scope.Manifest_ID)
        //     $http.post("/api/itemsInsertAll",data)              //function 7
        //         .then(function (data) {
        //             $scope.saveSuccessItems = true;
        //            $scope.savingSuccessitems='Saved Successfully.';
        //
        //
        //
        //             $scope.getSelectItemsShow($scope.Manifest_ID);
        //         }).catch(function () {
        //
        //         $scope.sav ='Something wet worng!';
        //
        //     }).finally(function () {
        //
        //         $scope.savi =false;
        //
        //     })
        //
        //
        // }


        $scope.addItems = function (form) {

            if (form.$valid) {//if form is valid

                if (checkDuplicate() == true) {
                    $scope.itemErrorMsg = true;
                    $scope.itemErrorMsgTxt = "Can't add an item twice!"
                    $("#itemError").show().fadeTo(1500, 500).slideUp(1500, function () {
                        $("#itemError").slideUp(1000);
                    });
                    return;
                }

                var data = {
                    item_Code: $scope.item_Code_id,
                    item_weight: $scope.item_weight,
                    item_package: $scope.item_package,
                    manf_id: $scope.Manifest_ID
                }

                $scope.savingMultiItem = true;
                // console.log($scope.Manifest_id)
                $http.post("/api/itemsInsertAll", data)
                    .then(function (data) {
                        $scope.itemSuccessMsg = true;
                        $scope.itemSuccessMsgTxt = 'Saved ';

                        $scope.insertSuccessMsg = true;
                        $("#itemSuccess").show().fadeTo(1500, 500).slideUp(500, function () {
                            $("#itemSuccess").slideUp(1000);
                        });

                        $scope.getSelectItemsShow($scope.Manifest_ID);

                        itemBlank();
                    }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }

                    $scope.sav = 'Something wet worng!';

                }).finally(function () {
                    $scope.savingMultiItem = false;
                })

            }
            else {
                $scope.multiItemFormSubmit = true;
                return;
            }

        }

        $scope.validationPosting = function () {
            if ($scope.postingform.$invalid) {
                $scope.submittedPostingForm = true;
                return false;
            } else {
                $scope.submittedPostingForm = false;
                return true;
            }
        }

        var itemBlank = function () {
            $scope.item_Code_id = '';
            $scope.item_weight = '';
            $scope.item_package = '';
            $scope.it_id = '';
            $scope.multiItemFormSubmit = false;
        }


        $scope.selectItemsShow = function () {

            // console.log($scope.Manifest_ID)

            $scope.getSelectItemsShow($scope.Manifest_ID);
            // $scope.updateSuccess = false;
            //   console.log($scope.Manifest_ID);
            // $scope.saveSuccessManifiest = false;
            // $scope.updateBtnItems = false;

            $scope.updateSuccess = false;
            $scope.multiItemFormSubmit = false;


        }

        $scope.getSelectItemsShow = function (m_id) {

            $http.get("/assessment/api/get-all-items-data/" + m_id)

                .then(function (data) {
                    $scope.saveSuccessItems = false;
                    $scope.updateSuccess = false;
                    $scope.allItemsData = data.data;
                    //  $scope.updateBtnItems = false;

                    //   console.log($scope.allItemsData)

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

        // $scope.deleteItems=function (i) {
        //     //bd_truck_id m_id
        //     console.log(i.bd_truck_id)
        //     $scope.updateSuccess = false;
        //     $http.get("/api/deleteItemsList/"+i.item_code)                  //function 9
        //         .then(function (data) {
        //
        //             $scope.getSelectItemsShow($scope.Manifest_ID);
        //
        //         }).finally(function () {
        //     })
        // }


        $scope.deleteItems = function (i) {

            //bd_truck_id m_id
            // console.log(i.bd_truck_id)
            $scope.updateSuccess = false;
            $http.get("/posting/api/delete-items/" + i.item_code)
                .then(function (data) {
                    $scope.itemSuccessMsg = true;
                    $scope.itemSuccessMsgTxt = 'Deleted';

                    $scope.insertSuccessMsg = true;
                    $("#itemSuccess").show().fadeTo(1500, 500).slideUp(500, function () {
                        $("#itemSuccess").slideUp(1000);
                    });
                    $scope.getSelectItemsShow($scope.Manifest_ID);
                    itemBlank();

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

        // $scope.editgoodsItems = function (id) {
        //
        //     $scope.item_Code_id = id.id;
        //     $scope.item_weight = id.item_weight;
        //     $scope.item_package = id.item_package;
        //     $scope.it_id = id.it_id;
        //     $scope.saveSuccessItems = false;
        //     //console.log(id.Description)
        //     console.log($scope.it_id)
        //
        //        $scope.updateBtnItems = true;
        //
        //     $scope.getSelectItemsShow($scope.Manifest_ID);
        //
        // }


        $scope.editgoodsItems = function (id) {
            // console.log(id)

            $scope.item_Code_id = id.id;//14
            $scope.cache_item_Code_id = id.id;//14

            $scope.item_weight = parseInt(id.item_weight);
            $scope.item_package = parseInt(id.item_package);
            $scope.it_id = id.it_id;//sl
            $scope.saveSuccessItems = false;
            //console.log(id.Description)
            //  console.log($scope.it_id)

            $scope.updateBtnItems = true;

            // $scope.getSelectItemsShow($scope.Manifest_ID);

        }

        //   $scope.manif_posted_btn_disable = true       // Initially manif_posted_btn_disable is true :)

        //
        //     $scope.updateitems = function (items) {
        //
        //         var data = {
        //             item_Code:$scope.item_Code_id,
        //             item_weight:$scope.item_weight,
        //             item_package:$scope.item_package,
        //             manf_id:$scope.Manifest_ID,
        //             it_id:$scope.it_id
        //
        //
        //
        //         }
        //         $http.put("/api/updateItemsInfo", data)                                     //function 10
        //         .then(function (data) {
        //             $scope.updateSuccess = true;
        //             $scope.updateSuccessitems='Update Successfully.';
        //             $scope.updateBtnItems = false;
        //             $scope.getSelectItemsShow($scope.Manifest_ID);
        //
        //         }).catch(function () {
        //             console.log('error')
        //
        //         }).finally(function () {
        //
        //         })
        //
        // }


        $scope.updateitems = function (form) {


            if (form.$valid) {


                if ($scope.item_Code_id != $scope.cache_item_Code_id) {

                    if (checkDuplicate() == true) {
                        $scope.itemErrorMsg = true;
                        $scope.itemErrorMsgTxt = "Can't add an item twice!"
                        $("#itemError").show().fadeTo(1500, 500).slideUp(1500, function () {
                            $("#itemError").slideUp(1000);
                        });
                        return;
                    }
                }


                var data = {
                    item_Code: $scope.item_Code_id,
                    item_weight: $scope.item_weight,
                    item_package: $scope.item_package,
                    manf_id: $scope.Manifest_ID,
                    it_id: $scope.it_id


                }
                $http.put("/api/updateItemsInfo", data)                                     //function 10
                    .then(function (data) {
                        $scope.itemSuccessMsg = true;
                        $scope.itemSuccessMsgTxt = 'Updated';

                        $scope.insertSuccessMsg = true;
                        $("#itemSuccess").show().fadeTo(1500, 500).slideUp(500, function () {
                            $("#itemSuccess").slideUp(1000);
                        });
                        $scope.getSelectItemsShow($scope.Manifest_ID);
                        $scope.updateBtnItems = false;

                        itemBlank();
                    }).catch(function (r) {
                    console.log('error')

                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }

                }).finally(function () {
                });
            }
            else {
                $scope.multiItemFormSubmit = true;
                return;
            }
        }


        var checkDuplicate = function () {
            var duplicateItem = false;
            angular.forEach($scope.allItemsData, function (v, k) {

                if (v.id == $scope.item_Code_id) {
                    duplicateItem = true;
                }

            });

            return duplicateItem;

        }

        $scope.vat_no = '0';

        $scope.edit = function (mani) {

            // console.log($scope.vat_no)
            // console.log(mani)
            $scope.t_gweight_wbridge_truck = mani.t_gweight_wbridge;

            $scope.WeightEmty = '';
            $scope.WeightDone = '';

            if ($scope.t_gweight_wbridge_truck == null) {
                $scope.WeightTruckEmty = "WeightBridge";
                $scope.WeightTruckDone = '';
            } else {
                $scope.WeightTruckDone = "WeightBridge";
                $scope.WeightTruckEmty = '';
            }


            $scope.imp_name_from_Importer = false;
            $scope.selectedTruckNoShowDiv = true;

            // $scope.showWhenUpdatebtnClick = false;

            $scope.vat_no_after_Vat = true;
            $scope.hidemanifestWhenUpdatebtnClick = false;
            $scope.savingSuccess = '';
            // console.log(mani)
            $scope.selectedStyle = mani.t_id;
            //  var manifest_alreadyposted=mani.package_no;
            //  console.log($scope.truckNoEdit);
            $scope.truckNoEdit = mani.t_truck_no; // this truck no is need for update data in truck table
            // if (manifest_alreadyposted != null) {//manifest manifest is done
            $scope.m_id = mani.m_id;
            $scope.m_manifest = mani.m_manifest;
            $scope.m_manifest_date = mani.m_manifest_date;
            $scope.m_marks_no = mani.m_marks_no;
            $scope.m_good_id = mani.m_good_id;
            $scope.m_gweight = mani.m_gweight;
            $scope.m_nweight = mani.m_nweight;
            $scope.m_package_no = mani.m_package_no;
            $scope.m_package_type = mani.m_package_type;
            $scope.m_cnf_value = mani.m_cnf_value;
            $scope.m_exporter_name_addr = mani.m_exporter_name_addr;
            $scope.m_vat_id = mani.m_vat_id;
            $scope.m_lc_no = mani.m_lc_no;
            $scope.m_lc_date = mani.m_lc_date;
            $scope.m_ind_be_no = mani.m_ind_be_no;
            $scope.m_ind_be_date = mani.m_ind_be_date;

            $scope.m_vat_name = mani.importer;
            $scope.remark = mani.posting_remark;
            // $scope.vat_no = '0';
            // $scope.hideVatNOorImporterName($scope.vat_no = 0);


            //TRuck
            $scope.t_gweight = mani.t_gweight;
            $scope.t_posted_yard_shed = mani.t_posted_yard_shed;

            $scope.t_nweight = mani.t_nweight
            //  $scope.t_posted_yard_shed=mani.t_posted_yard_shed;

            //      $scope.manif_posted_btn_disable = false

            // }

            //     else
            //     {// manifest must be updated

            //       $scope.manif_posted_btn_disable = false
            //  }
            $scope.label = true;
            // console.log(mani.manifest)
        }

        // $scope.search = function (mani) {
        //     $scope.manifest=mani.manifest;
        //     $scope.manifest_date=mani.manifest_date;
        //     $scope.id=mani.id;
        //     $scope.goods_id=mani.goods_id;
        //
        // }

        $scope.blank = function () {
            $("#importerNameLabel").html('');
            $scope.goods_id=null;
            $scope.yard_count_no = null;
            $scope.message_1 = null;
            $scope.message_2 = null;
            //$scope.t_posted_yard_shed = null;
            $('.selectpicker').val([]);
            $('.selectpicker').trigger('change.abs.preserveSelected');
            $('.selectpicker').selectpicker('refresh');
            $scope.Manifest_ID = null;
            $scope.m_manifest = null;
            $scope.m_good_id = null;
            $scope.m_manifest_date = null;
            $scope.m_gweight = null;
            $scope.m_nweight = null;
            $scope.m_marks_no = null;
            $scope.m_package_no = null;
            $scope.m_package_type = null;
            $scope.m_cnf_value = null;
            $scope.m_exporter_name_addr = null;
            $('#m_Importer_Name').val('');
            // m_vat_id_id
            $('#m_vat_id_id').val('');
            $scope.m_vat_id = null;
            $scope.ImorterName = null;
            $scope.m_vat_name = null;
            // $("#m_Importer_Name").val(ui.item.id)
            //$scope.$("#m_Importer_Name").val() = null;
            $scope.m_vat_id_id = null;
            // m_vat_id_id
            $scope.vatId_importer_name = null;
            $scope.m_Importer_Name = null;
            $scope.remark = null;
            $scope.m_lc_no = null;
            $scope.m_lc_date = null;
            $scope.m_ind_be_no = null;
            $scope.m_ind_be_date = null;
            $scope.t_gweight_wbridge = null;
            
            $scope.importerNameLabelinput = null;
            $scope.importerNameInput = false;
        }
        $scope.showBlank = function () {
            $scope.m_manifest_show = null;
            $scope.m_manifest_date_show = null;
            $scope.m_package_no_show = null;
            $scope.m_cnf_value_show = null;
            $scope.m_gweight_show = null;
            // $scope.m_good_id=data.data[0].cargo_name;
            // $scope.m_vat_id=data.data[0].m_vat_id;
            $scope.t_posted_yard_shed_show = null;
            // $scope.m_nweight=data.data[0].m_nweight;
            // $scope.m_marks_no=data.data[0].m_marks_no;
            // $scope.driver_card=data.data[0].driver_card;
            // $scope.driver_name=data.data[0].driver_name;
            // $scope.m_package_type=data.data[0].m_package_type;
            // $scope.m_exporter_name_addr=data.data[0].m_exporter_name_addr;
            $scope.m_vat_name_show = null;


        }

        //BIN Number Addition Properties

        $scope.BlankBin = function () {
            $scope.BINNO = null;
            $scope.BINNAME = null;
            $scope.ADD1 = null;
            $scope.ADD2 = null;
            $scope.ADD3 = null;
            $scope.ADD4 = null;
        }


        $scope.ValidationBin = function () {
            // if($scope.exist == true) {
            //         $scope.submitted = true;
            //         return false;
            // }

            if ($scope.importerForm.$invalid) {
                $scope.submittedBin = true;
                return false;
            } else {
                $scope.submittedBin = false;
                return true;
            }
        }

        $scope.SaveBin = function () {
            if ($scope.ValidationBin() == false) {
                return;
            }
            //return;
            $scope.dataLoadingBin = true;
            var data = {
                BIN: $scope.BINNO,
                NAME: $scope.BINNAME,
                ADD1: $scope.ADD1,
                ADD2: $scope.ADD2,
                ADD3: $scope.ADD3,
                ADD4: $scope.ADD4
            }

            $http.post("/posting/api/save-importer-from-posting", data)
                .then(function (data) {
                    console.log(data);
                    $scope.savingSuccessBin = 'Importer details saved successfully.'
                    $('#savingSuccessBin').show().delay(5000).slideUp(1000, function () {
                        $('#addImporter').modal('hide');
                    });
                    $scope.BlankBin();
                }).catch(function (r) {
                if (respose.status == 401) {
                    $.growl.error({message: r.data});
                    $scope.savingErrorBin = respose.data.duplicate;
                    $('#savingErrorBin').show().delay(5000).slideUp(1000);
                    return;
                }else {
                    $.growl.error({message: "It has Some Error!"});
                    $scope.savingErrorBin = 'Something went wrong.'
                    $('#savingErrorBin').show().delay(5000).slideUp(1000);
                }

            }).finally(function () {
                $scope.dataLoadingBin = false;
            })
        }
        //BIN Number Addition Properties


        //=================
        //1-6-17 ======== For Manifest Input
        //service added 7-6-2017

        $scope.keyBoard = function (event) {
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
            console.log($scope.keyboardFlag);
        };

        $scope.$watchGroup(['ManifestNo'/*,'m_manifest'*/], function () {
            $scope.ManifestNo = manifestService.addYearWithManifest($scope.ManifestNo, $scope.keyboardFlag);
            //$scope.m_manifest = manifestService.addYearWithManifest($scope.m_manifest, $scope.keyboardFlag);
            console.log($scope.ManifestNo);
        });

        //=================

    }).filter('capitalize', function () {
    return function (input, all) {
        return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }) : '';
    }
});


// .filter('capitalize', function() {
//     return function(input, scope) {
//         if (input!=undefined) {
//             if (input!=null)
//                 input = input.toLowerCase();
//             return input.substring(0,1).toUpperCase()+input.substring(1);
//         }
//     }
// });