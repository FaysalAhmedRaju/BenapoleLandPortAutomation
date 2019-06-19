angular.module('PerishableItemsApp', ['angularUtils.directives.dirPagination'])
	.controller('PerishableItemsCtrl', function ($scope, $http) {


		$scope.getAllItems = function() {
			$scope.dataLoading = true;
			$http.get('/transshipment/api/get-all-items')
				.then(function(data) {
					console.log('lkkjjsd')

					$scope.allItems = data.data;
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
				}).finally(function() {
					$scope.dataLoading = false;
				})
		};


		$scope.getAllItems();

		$scope.SavePerishableItem = function(item) {
			console.log(item);
			$scope.dataLoading = true;
			var data = {
				id : item.id,
				perishable_flag : item.perishable_flag,
				perishable_flag_created_at : item.perishable_flag_created_at
			}
			$http.post('/transshipment/api/save-parishable-item', data)
				.then(function(successRequest) {
					//console.log(successRequest);
					if(successRequest.status == 200) {
						$scope.savingSuccess = item.Description + " is Successfully marked as perishable.";
						$('#savingSuccess').show().delay(5000).slideUp(2000);
					}
					if(successRequest.status == 201) {
						$scope.savingSuccess = item.Description + " is Successfully changed as perishable.";
						$('#savingSuccess').show().delay(5000).slideUp(2000);
					}
				}).catch(function(r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

					$scope.savingError = "Something went wrong.";
					$('#savingError').show().delay(5000).slideUp(2000);
				}).finally(function() {
					$scope.dataLoading = false;
				})
		}
	});