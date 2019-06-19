angular.module('GatePassApp', ['angularUtils.directives.dirPagination'])
    .controller('GatePassCtrl', function($scope, $http,$filter){
        $scope.dataDiv = false;
        $scope.noDataDiv = false;

        var today = new Date();
        var Y = today.getFullYear();
        var M = today.getMonth()+1;
        var D = today.getDate();
        if(today.getMonth()+1 < 10)
            M = "0"+M;
        if(today.getDate() < 10)
            D = "0"+D;
        $scope.date = Y+"-"+M+"-"+D;

        $scope.totalAssessmentValue=0;
        $scope.totalAssessmentVat=0;
        $scope.created_at_show = true;
        $scope.done_at_show = false;





        $scope.serial = 1;
        $scope.itemPerPage=500;
        $scope.indexCount = function(newPageNumber){

            $scope.serial = newPageNumber * $scope.itemPerPage - ($scope.itemPerPage-1);
        }


        $scope.getCompletedAssessment = function(date) {
            console.log(date);
            $scope.dataLoading = true;
            $scope.dataDiv = false;
            $scope.noDataDiv = false;
            $scope.totalAssessmentValue=0;
            $scope.totalAssessmentVat=0;
            var assessment_values = null;
            var vat = 0;

            $http.get("/gate-pass/api/get-date-wise-gate-pass-manifest-data/"+date)
                .then(function(data){
                    console.log(data);
                    if(data.data.length > 0 ) {
                        console.log(data.data);


                        // if(a){
                        //     $scope.created_at_show = false;
                        //     $scope.done_at_show = true;
                            $scope.buttonMessage = null;
                        //
                        // }else {
                        //     $scope.created_at_show = true;
                        //     $scope.done_at_show = false;
                        //     $scope.buttonMessage = 'Created Report';
                        // }

                        $scope.allGatePassData = data.data;
                        console.log($scope.allGatePassData);
                        $scope.dataDiv = true;
                        $scope.noDataDiv = false;

                        // angular.forEach(data.data,function (v,k) {
                        //     console.log(v.totalAssessmentValue);
                        //     $scope.totalAssessmentValue+=parseFloat(v.totalAssessmentValue);
                        //     var assessment_values = JSON.parse(v.assessment_values);
                        //     if(assessment_values.vat == 1) {
                        //         vat = (v.totalAssessmentValue/100)*15;
                        //     } else {
                        //         vat = 0;
                        //     }
                        //     var assessment_values = null;
                        //     $scope.totalAssessmentVat+=parseFloat(vat);
                        // })



                    } else {
                        $scope.noDataDiv = true;
                        $scope.dataDiv = false;
                        $scope.buttonMessage = 'Data Not Found';
                    }
                }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            }).finally(function(){
                $scope.dataLoading = false;
            })
        }
        //console.log($scope.date);
        // $scope.getCompletedAssessment($scope.date);

        $scope.PreDataDiv = false;
        $scope.noPreDataDiv = false;
        $scope.getPreviousCompletedAssessment = function() {
            $scope.PreDataLoading = true;
            $scope.PreDataDiv = false;
            $scope.noPreDataDiv = false;
            $http.get("/assessment-admin/api/get-previous-completed-assessment/")
                .then(function(data){
                    if(data.data.length > 0 ) {
                        $scope.allPreviousCompletedAssessment = data.data;
                        $scope.PreDataDiv = true;
                        $scope.noPreDataDiv = false;
                    } else {
                        $scope.noPreDataDiv = true;
                        $scope.PreDataDiv = false;
                    }
                }).catch(function(r){
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            }).finally(function(){
                $scope.PreDataLoading = false;
            })
        }
        //$scope.getPreviousCompletedAssessment();



    }).filter('stringToDate', function ($filter) {
    return function (ele, dateFormat) {
        return $filter('date')(new Date(ele), dateFormat);
    }
}).filter('dateShort', function ($filter) {
    console.log(ele)
    return function (ele, dateFormat) {
        return $filter('date')(new Date(ele), dateFormat);
    }
}).filter('getValue', function() {
        return function (ele, peram, optional_param = null) {
            var values = JSON.parse(ele);
            //console.log(values);
            if(peram == 'good_description') {
                return values.good_description;
            }
            if(peram == 'vat') {
                if(values.vat == 1) {
                    return (optional_param/100)*15;
                } else {
                    return 0;
                }
            }
            if(peram == 'total') {
                var vat = 0;
                if(values.vat == 1) {
                    vat = (optional_param/100)*15;
                    return Math.ceil(parseFloat(vat) + parseFloat(optional_param));
                } else {
                    return Math.ceil(optional_param);
                }
            }

        }
    }
);