angular.module('dateWiseTruckMonitorApp',['angularUtils.directives.dirPagination'])
	.controller('dateWiseTruckMonitorCtrl', function($scope, $http){
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

		$scope.getTruckDetails = function(vehicleform) {
			$scope.dataLoading = true;
			$scope.dataDiv = false;
			$scope.noDataDiv = false;


            console.log($scope.vehile_type_flage_pdf);
            if($scope.vehile_type_flage_pdf == undefined){
                $scope.vehile_type_flage_pdf = 1;
            }
           console.log($scope.vehile_type_flage_pdf);
            console.log($scope.date);

			$http.get("/truck/api/get-truck-details-for-monitor/"+ $scope.date +"/"+ $scope.vehile_type_flage_pdf)
				.then(function(data){
					//console.log(data.data[0].length);
                    console.log(data.data[1][0]);
					if(data.data[0].length > 0 ) {
						$scope.allTruck = data.data[0];
						$scope.dataDiv = true;
						$scope.noDataDiv = false;
						//console.log($scope.allTruck);
						$scope.total_goods = data.data[1][0].total_goods;
                        $scope.total_trucktor = data.data[1][0].total_trucktor;
                        $scope.total_chassis_self = data.data[1][0].total_chassis_self;

                        $scope.total_car_self = data.data[1][0].total_car_self;
                        $scope.total_pick_up_self = data.data[1][0].total_pick_up_self;
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
		$scope.getTruckDetails(vehicleform);
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
