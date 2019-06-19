angular.module('truckAllReportsApp', ['angularUtils.directives.dirPagination'])
    .controller('truckAllReportsCtrl', function ($scope, $http) {
        
        $scope.$watchGroup(['from_date_v', 'to_date_v'], function () {
            if($scope.from_date_v != null && $scope.to_date_v != null) {
                $scope.getMonthWiseTruckEntrySl($scope.from_date_v, $scope.to_date_v);
            }
        });

        $scope.getMonthWiseTruckEntrySl = function(firstDate, lastDate) {
            $http.get('/truck/api/get-date-range-wise-sl-for-entry-report/'+firstDate+'/'+lastDate)
                .then(function(data) {
                    //console.log(data);
                    $scope.slValues = data.data;

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

        $scope.getRange = function(slValue) {
            $scope.getRangeFromOb = $scope.slValues[slValue].firstSl
                +"-"+ $scope.slValues[slValue].lastSl;
            $('#range').val($scope.getRangeFromOb);
            //$scope.range = $scope.getRangeFromOb;
        }

        
    });