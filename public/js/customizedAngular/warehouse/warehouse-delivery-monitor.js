angular.module('dateWiseWarehouseDeliveryMonitorApp',['angularUtils.directives.dirPagination'])
	.controller('dateWiseWarehouseDeliveryMonitorCtrl', function($scope, $http){
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

        $scope.serial = 1;
        $scope.itemPerpage = 15;
        $scope.getPageCount = function(n){
            $scope.serial = n * $scope.itemPerpage  - ($scope.itemPerpage -1);
        }

		$scope.getTruckDetails = function(date) {
			$scope.dataLoading = true;
			$scope.dataDiv = false;
			$scope.noDataDiv = false;
			$http.get("/warehouse/delivery/api/get-entry-details-for-monitor/"+date)
				.then(function(data){
					console.log(data);
					if(data.data.length > 0 ) {
						$scope.allTruckDelivery = data.data;
						$scope.dataDiv = true;
						$scope.noDataDiv = false;
					} else {
						$scope.noDataDiv = true;
						$scope.dataDiv = false;
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
		$scope.getTruckDetails($scope.date);
});
