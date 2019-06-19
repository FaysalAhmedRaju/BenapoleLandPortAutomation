angular.module('salaryReportApp', [])
	.controller('salaryReportCtrl', function($scope, $http){
		$http.get("/accounts/salary/facilities-deduction/monthly-deduction/api/get-all-valid-employees")
            .then(function(data){
                $scope.allValidEmployees = data.data;
            })
        //Per Person Wise Monthly
        $scope.validationPerPersonWiseMonthlyReport = function(perPersonWiseMonthlyReportForm) {
            if(perPersonWiseMonthlyReportForm.$invalid) {
                $scope.submittedPerPersonWiseMonthlyReport = true;
                return false;
            } else {
                $scope.submittedPerPersonWiseMonthlyReport = false;
                //document.forms['PerPersonWiseMonthlyReportForm'].submit();
            }
        }

        //Per Person Wise Yearly
        // var min = new Date().getFullYear()-5;
        // var max = min + 10;
        // $scope.years = [];
        // var j=0;
        // for (var i = min; i<=max; i++){
        //    $scope.years[j++] = {value: i, text: i}; 
        // }

        $scope.validationPerPersonWiseYearlyReport = function(perPersonWiseYearlyReportForm) {
            if(perPersonWiseYearlyReportForm.$invalid) {
                $scope.submittedPerPersonWiseYearly = true;
                return false;
            } else {
                $scope.submittedPerPersonWiseYearly = false;
                //document.forms['PerPersonWiseMonthlyReportForm'].submit();
            }
        }

	});