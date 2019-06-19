angular.module('WeightReportApp', ['angularUtils.directives.dirPagination', 'customServiceModule'])
	.controller('WeightReportController', function($scope, $http, manifestService){
		
		$scope.Validation = function() {
			if($scope.WeightReportForm.$invalid) {
				$scope.submitted = true;
				return false;
			} else {
				$scope.submitted = false;
				return true;
			}
		}

		$scope.Search = function(manifest) {
			if($scope.Validation() == false) {
				return;
			}

			$http.get('/weighbridge/api/get-manifest-details-data/'+manifest)
				.then(function(data){
					if(data.data.length>0) {
						$scope.allTruck = data.data;
						$scope.dataTable = true;
					} else {
						$scope.dataTable = false;
						$scope.notFoundError = 'This manifest is not assigned as weightbridge.'
						$('#notFoundError').show().delay(5000).slideUp(1000);
					}
				}).catch(function(r){

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
					$scope.notFoundError = response.statusText;
					$('#notFoundError').show().delay(5000).slideUp(1000);
				}).finally(function(){

				});
		}

		$scope.Clear = function() {
			$scope.dataTable = false;
			$scope.allTruck = null;
			$scope.notFoundError = null;
		}

		//For Manifest Input Like 947/2/2017
		//service added 7-6-2017

        $scope.keyBoard = function(event) {
            $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
        }

        $scope.$watch('manifest',function() {
            $scope.manifest = manifestService.addYearWithManifest($scope.manifest, $scope.keyboardFlag);
        });

	});