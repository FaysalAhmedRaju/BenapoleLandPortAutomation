angular.module('localTruckGateOutApp',['angularUtils.directives.dirPagination','customServiceModule'])
	.controller('localTruckGateOutCtrl', function($scope,$http,$filter,manifestService){


        //capitalize the TruckType
        $scope.$watch('searchText', function (val) {

            $scope.searchText = $filter('uppercase')(val);

        }, true);


		$scope.serachField = true;
        $scope.selection =  null;
        $scope.showWhenNew = false;

        $scope.select = function() {
            if($scope.selection=='manifestNo') {
            	$scope.placeHolder = 'Enter Manifest No';
                $scope.serachField = false;
            } else if($scope.selection=='truckNo'){
                $scope.placeHolder = 'Enter Truck No';
                $scope.serachField = false;
            } else {
                $scope.placeHolder = null;
                $scope.serachField = true;
            }
        }

        $scope.manifestOrTruckNoSearch = function(searchText) {
			var data = {
				searchBy : $scope.selection,
				searchKey : searchText
			}
			//console.log(data);
			$http.post("/gateout/api/get-local-trucks-data-details", data)
				.then(function (data) {
					//console.log(data.data);
					if(data.data.length>0) {
						$scope.allManifestWithLocalTruckData = data.data;
						$scope.showWhenNew = true;
						$scope.table = true;
						$scope.notFoundError = false;
					} else {
						$scope.notFoundError = "Not Found.";
					}
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.notFoundError = "Something went wrong";
				}).finally(function() {
			})
		}

		$scope.modalInfo = function(manifestWithLocalTruck) {
			$scope.exit_id = manifestWithLocalTruck.id;
			$scope.exit_manifest = manifestWithLocalTruck.manifest;
			$scope.exit_manifest_date = manifestWithLocalTruck.manifest_date;
			$scope.exit_truck_no = manifestWithLocalTruck.truck_no;
			$scope.exit_be_no = manifestWithLocalTruck.be_no;
			$scope.exit_be_date = manifestWithLocalTruck.be_date;
			$scope.exit_cargo_name = manifestWithLocalTruck.cargo_name;
			$scope.exit_marks_no = manifestWithLocalTruck.marks_no;
			$scope.exit_loading_unit = manifestWithLocalTruck.loading_unit;

			$scope.whenExitSuccessfull = false;
			$scope.exitSuccessfull = false;
			$scope.exitError = false;

			$scope.whenEntrySuccessfull = false;
			$scope.entrySuccessfull = false;
			$scope.entryError = false;
		}

		$scope.getIn = function() {
			var data = {
				id : $scope.exit_id,
				entry_comment : $scope.entry_comment
			}
			$http.post("/gateout/api/save-local-truck-entry-data", data)
				.then(function (data) {
					//console.log(data.data);
					$scope.entrySuccessfull = true;
					$scope.entry_comment = null;
					$scope.whenEntrySuccessfull = true;
					//console.log($scope.whenExitSuccessfull);
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.entryError = true;
				}).finally(function() {
					$scope.manifestOrTruckNoSearch($scope.searchText);
			})
		}

		$scope.getOut = function() {
			var data = {
				id : $scope.exit_id,
				exit_comment : $scope.exit_comment
			}
			$http.post("/gateout/api/save-local-truck-exit-data", data)
				.then(function (data) {
					//console.log(data.data);
					$scope.exitSuccessfull = true;
					$scope.exit_comment = null;
					$scope.whenExitSuccessfull = true;
					//console.log($scope.whenExitSuccessfull);
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.exitError = true;
				}).finally(function() {
					$scope.manifestOrTruckNoSearch($scope.searchText);
			})
		}

		$scope.clear = function() {
			//console.log("clea");
			$scope.table = false;
			$scope.notFoundError = false;
		}

		//New Manifest Added Start- 8/6/17
		$scope.keyBoard = function(event) {
			$scope.keyboardFlag = manifestService.getKeyboardStatus(event);
		}

		$scope.$watch('searchText', function(){
			$scope.searchText = manifestService.addYearWithManifest($scope.searchText, $scope.keyboardFlag, $scope.selection);
		});
		//New Manifest Added End- 8/6/17

	});