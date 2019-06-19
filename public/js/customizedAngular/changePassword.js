angular.module('changePasswordApp', ['customServiceModule'])
	.controller('changePasswordCtrl', function($scope, $http, $timeout, enterKeyService){

		$scope.saveBtn = true;
		enterKeyService.enterKey('#changePasswordForm input , #changePasswordForm button');

		$scope.Validation = function() {
			if($scope.changePasswordForm.$invalid) {
				$scope.submitted = true;
				return false;
			} else {
				$scope.submitted = false;
				return true;
			}
		}

		$scope.Blank = function() {
			$scope.old_password = null;
			$scope.new_password = null;
			$scope.confirm_password = null;
		}

		$scope.ChangePassword = function() {
			if($scope.Validation() == false) {
				return;
			}
			$scope.dataLoading = true;
			var data = {
				old_password : $scope.old_password,
				new_password : $scope.new_password,
				confirm_password : $scope.confirm_password
			}
			$http.post("/user/api/save-change-password",data)
				.then(function(data){
					//console.log(data);
					if(data.status==202) {
						$scope.savingSuccess = data.data.changed;
						$('#savingSuccess').show().delay(3000).slideUp(1000);
						$timeout(function () {
						    $scope.savingSuccess = "You are redirected to home page within 2 seconds.";
							$('#savingSuccess').show().delay(2000).slideUp(1000);
						}, 5000);
						$timeout(function () {
						    location.reload();
						}, 8000);
					}
					$scope.Blank();
				}).catch(function(r){
					//console.log(response);
					if(r.status==401) {
                        $.growl.error({message: r.data});
						$scope.savingError = r.data.notMatch;
					} else if(r.status==402) {
						$scope.savingError = r.data.wrongPassword;
					} else if(r.status==403) {
						$scope.savingError = r.data.noChange;
					} else {
						$scope.savingError = r.statusText;
                        $.growl.error({message: "It has Some Error!"});
					}
					$('#savingError').show().delay(5000).slideUp(1000);
				}).finally(function(){
					$scope.dataLoading = false;
				})
		}
		
	});
