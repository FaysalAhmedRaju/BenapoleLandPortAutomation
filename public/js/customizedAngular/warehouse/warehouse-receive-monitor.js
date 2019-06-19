angular.module('dateWiseWarehouseEntryMonitorApp',['angularUtils.directives.dirPagination'])
	.controller('dateWiseWarehouseEntryMonitorCtrl', function($scope, $http){
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
			$http.get("/warehouse/receive/api/get-warehouse-receive-entry-details-for-monitor/"+date)
				.then(function(data){
					//console.log(data);
					if(data.data.length > 0 ) {
						$scope.allTruckReceive = data.data;
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
}).filter('vehicleTypeFilter', function () {
    return function (val) {
        var vehicle;
        if(val==1){
            return vehicle='Truck';
        } else if(val == 2) {
            return vehicle='Chassis(On Truck)';
        }else if(val == 3) {
            return vehicle='Trucktor(On Truck)';
        }else if(val == 11) {
            return vehicle='Chassis(Self)';
        }else if(val == 12) {
            return vehicle='Trucktor(Self)';
        }else if(val == 13) {
            return vehicle='Bus';
        }else if(val == 14) {
            return vehicle='Three Wheller';
        }else if(val == 15) {
            return vehicle='Rickshaw';
        }else  if(val == 16){
            return vehicle = 'Car(self)';
        }else if (val == 17){
            return vehicle = 'Pick Up(self)';
        }
    }
});

