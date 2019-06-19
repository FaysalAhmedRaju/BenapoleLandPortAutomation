angular.module('AssessmentDetailsApp', ['angularUtils.directives.dirPagination', 'ngAnimate', 'ngTagsInput', 'customServiceModule'])
    .controller('AssessmentDetailsCtrl', function ($scope, $http, $timeout, $filter, manifestService, amountToTextService) {

        $scope.Done = function () {
            $scope.savingData = true;
            $http.get("/assessment-admin/api/assessment-done/" + manifest_id + "/" + assessment_id + "/" + partial_status)
                .then(function (data) {
                    console.log(data.data)
                    $scope.insertSuccessMsg = true;
                    $('#saveSuccess').show().delay(3000).slideUp(1000);
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                }
                $scope.insertErrorMsg = true;
                $('#saveError').show().delay(3000).slideUp(1000);
                $scope.insertErrorMsgTxt = 'Something Went Wrong!';
            }).finally(function () {
                $scope.savingData = false;
                $scope.CheckAssessmentDone();
            })
        }
        //$scope.showDoneButton = false;


        var mani = $("#dd").val();
        $scope.CheckAssessmentDone = function () {


            var data = {
                mani_no: mani,
                manifest_id: manifest_id,
                partial_status: partial_status,
                assessment_id: assessment_id
            };
            console.log(data);
            $http.post("/assessment-admin/api/check-assessment-done", data)
                .then(function (data) {
                    console.log(data.data[0][0].done);
                    if (data.data[0][0].done == 0) {
                        $scope.showDoneButton = true;
                        $scope.showAlreadyDone = false;
                    } else if (data.data[0][0].done == 1) {
                        $scope.showAlreadyDone = true;
                        $scope.showDoneButton = false;
                    }

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
        $scope.CheckAssessmentDone();




//====================Manifest search==========================




    }).filter('ceil', function () {
    return function (input) {
        return Math.ceil(input);
    };
}).filter('stringToDate', function ($filter) {
        return function (ele, dateFormat) {
            return $filter('date')(new Date(ele), dateFormat);
        }
    })

    .filter('dateShort', function ($filter) {
        return function (ele, dateFormat) {
            return $filter('date')(new Date(ele), dateFormat);
        }
    })
    .filter('item_type', function () {
        return function (val) {

            var type;
            if (val == 1) {
                return type = 'Volumn';
            }
            else if (val == 2) {
                return type = 'Unit';
            }
            else if (val == 3) {
                return type = 'Package';
            }
            else {
                return type = 'Weight';
            }

        };
    })
    .filter('dangerous', function () {
        return function (val) {
            var type;
            if (val == 1) {
                return type = '200%';
            }
            else {
                return type = '';
            }
        };
    }).filter('capitalize', function () {
    return function (input, all) {
        return (!!input) ? input.replace(/([^\W_]+[^\s-]*) */g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        }) : '';
    }
});