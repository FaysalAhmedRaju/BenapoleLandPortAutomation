angular.module('AssessmentVerificationApp',['angularUtils.directives.dirPagination','assessmentApp'])
	.controller('AssessmentVerificationCtrl', function($scope,$http){





           $scope.ManifestNo =manifestNo
		$scope.Math = window.Math;


		$scope.verify = function() {
			var data = {
				manifestNo : manifestNo,
				verify_comm : $scope.verify_comm
			}
			$http.post("/api/verifyAssessmentVerification", data)
				.then(function(data){
					console.log(data);
					$scope.savingSuccess = "Assessment verification completed for manifest No "+ manifestNo;
					$scope.show = true;
					$scope.verify_comm = null;
				}).catch(function(){
					$scope.savingError = "Something went wrong."
				}).finally(function(){

				})
		}

		$scope.reject = function() {
			var data = {
				manifestNo : manifestNo,
				verify_comm : $scope.verify_comm
			}
			$http.post("/api/rejectAssessmentVerification", data)
				.then(function(data){
					console.log(data);
					//$scope.savingSuccess = "Assessment verification rejected for manifest No "+ manifestNo;
					$scope.savingError = "Assessment verification rejected for manifest No "+ manifestNo;
					$scope.show = true;
					$scope.verify_comm = null;
				}).catch(function(){
					$scope.savingError = "Something went wrong."
				}).finally(function(){

				})

		}
	});