angular.module('passportEntryApp',['angularUtils.directives.dirPagination'])
	.controller('passportEntryController', function($scope, $http){

        $scope.showPassportDetails = false;
        $scope.showPassportDetailsSearch = false;
		$scope.save = function() {

			if($scope.passport_no==null || $scope.country_code==null || $scope.sur_name==null
				|| $scope.given_name==null || $scope.given_name==null || $scope.nationality==null
				|| $scope.date_of_birth==null || $scope.place_of_issue==null || $scope.date_of_issue==null
				|| $scope.date_of_expired==null || $scope.place_of_birth==null) {

				if($scope.passport_no==null) {
					$scope.passport_no_required = true;
				} else {
					$scope.passport_no_required = false;
				}
				if($scope.country_code==null) {
					$scope.country_code_required = true;
				} else {
					$scope.country_code_required = false;
				}
				if($scope.sur_name==null) {
					$scope.sur_name_required = true;
				} else {
					$scope.sur_name_required = false;
				}
				if($scope.given_name==null) {
					$scope.given_name_required = true;
				} else {
					$scope.given_name_required = false;
				}
				if($scope.nationality==null) {
					$scope.nationality_required = true;
				} else {
					$scope.nationality_required = false;
				}
				if($scope.date_of_birth==null) {
					$scope.date_of_birth_required = true;
				} else {
					$scope.date_of_birth_required = false;
				}
				if($scope.place_of_issue==null) {
					$scope.place_of_issue_required = true;
				} else {
					$scope.place_of_issue_required = false;
				}
				if($scope.date_of_issue==null) {
					$scope.date_of_issue_required = true;
				} else {
					$scope.date_of_issue_required = false;
				}
				if($scope.date_of_expired==null) {
					$scope.date_of_expired_required = true;
				} else {
					$scope.date_of_expired_required = false;
				}
				if($scope.place_of_birth==null) {
					$scope.place_of_birth_required = true;
				} else {
					$scope.place_of_birth_required = false;
				}
				return false;
			} else {
				$scope.passport_no_required = false;
				$scope.country_code_required = false;
				$scope.sur_name_required = false;
				$scope.given_name_required = false;
				$scope.nationality_required = false;
				$scope.date_of_birth_required = false;
				$scope.place_of_issue_required = false;
				$scope.date_of_issue_required = false;
				$scope.date_of_expired_required = false;
				$scope.place_of_birth_required = false;
			}
			var data = {
				getId : $scope.getPassport_id,
				passport_no : $scope.passport_no,
				country_code : $scope.country_code,
				sur_name : $scope.sur_name,
				given_name : $scope.given_name,
				nationality : $scope.nationality,
				sex : $scope.sex,
				date_of_birth : $scope.date_of_birth,
				place_of_birth : $scope.place_of_birth,
				place_of_issue : $scope.place_of_issue,
				date_of_issue : $scope.date_of_issue,
				date_of_expired : $scope.date_of_expired
			}
			//console.log(data);
			$http.post("/api/postPassportEntry", data)
				.then(function(data){
					console.log(data.data);
					$scope.savingSuccess = data.data;
					//$scope.passportInfo($scope.passport_no);
					//$scope.blank();
				}).catch(function(){
					$scope.savingError = "Something Went Wrong.";
				}).finally(function(data){

				})
		}


$scope.getPassportVisaDetails = function (v_passport_no) {

    var data = {
        v_passport_no:v_passport_no
    }
    $http.post("/api/allVisaInfoForShow",data)
        .then(function (data) {

            $scope.AllVisaInfoDataForShow = data.data;

            $scope.showPassportDetails = true;
            // console.log(data.data)
            // console.log(data.data[0])
            // console.log($scope.AllVisaInfoDataForShow)



            $scope.v_passport_no = null;
            $scope.v_place_of_issue = null;
            $scope.v_date_of_issue = null;
            $scope.v_date_of_expired = null;
            $scope.type = null;
            $scope.numbers_of_entries = null;
            $scope.duration_of_stay = null;
            $scope.v_sur_name = null;
            $scope.v_date_of_birth = null;
            $scope.v_sex = null;
            $scope.v_nationality = null;
            $scope.remarks = null;





        }).catch(function () {
    }).finally(function () {

    })

}



		$scope.saveVisa = function () {

            var data = {
                 // passport_no: $scope.v_passport_no,
                // place_of_issue: $scope.place_of_issue,
                // date_of_issue: $scope.date_of_issue,
                // date_of_expired: $scope.date_of_expired,

                type: $scope.type,
                numbers_of_entries: $scope.numbers_of_entries,
                duration_of_stay: $scope.duration_of_stay,
                remarks: $scope.remarks,
				 passport_id: $scope.passport_id
                // sur_name: $scope.sur_name,
                // date_of_birth: $scope.date_of_birth,
                // sex: $scope.sex,
                // nationlity: $scope.nationlity,


            }
            console.log(data);
            // console.log(data)
            //    console.log( $scope.v_passport_no)

            $http.post("/api/visaDetailsSaveJson",data)
                .then(function (data) {
                    $scope.savingSuccessVisa = 'Saved Successfully!';
                    $scope.getPassportVisaDetails($scope.v_passport_no);
                    // $scope.passport_no = null;
                    // $scope.place_of_issue = null;
                    // $scope.date_of_issue = null;
                    // $scope.date_of_expired =null;
                    // $scope.type = null;
                    // $scope.numbers_of_entries = null;
                    // $scope.duration_of_stay = null;
                    // $scope.sur_name = null;
                    // $scope.date_of_birth = null;
                    // $scope.sex = null;
                    // $scope.nationlity = null;
                    // $scope.remarks = null;
                    //$scope.savingSuccess = null;
                }).catch(function () {

                $scope.savingErroVisa='Something went Wrong!';
                // $scope.passport_no = null;
                // $scope.place_of_issue = null;
                // $scope.date_of_issue = null;
                // $scope.date_of_expired =null;
                // $scope.type = null;
                // $scope.numbers_of_entries = null;
                // $scope.duration_of_stay = null;
                // $scope.sur_name = null;
                // $scope.date_of_birth = null;
                // $scope.sex = null;
                // $scope.nationlity = null;
                // $scope.remarks = null;

            }).finally(function () {

                $scope.savingData=false;

            })




			// console.log($scope.v_passport_no)
			// console.log(data)





        }

		$scope.passportInfo = function(passport_no) {

			var data = {
				passport_no : passport_no
			}
            //
			// console.log(data)
			// console.log(passport_no)

			$http.post("/api/getPassportInfo", data)
				.then(function(data){


 if(data.data.length >=1) {
     $scope.passport_no = data.data[0].passport_no;
     $scope.country_code = data.data[0].country_code;
     $scope.sur_name = data.data[0].sur_name;
     $scope.given_name = data.data[0].given_name;
     $scope.nationality = data.data[0].nationality;
     $scope.sex = data.data[0].sex;
     $scope.date_of_birth = data.data[0].date_of_birth;
     $scope.place_of_birth = data.data[0].place_of_birth;
     $scope.place_of_issue = data.data[0].place_of_issue;
     $scope.date_of_issue = data.data[0].date_of_issue;
     $scope.date_of_expired = data.data[0].date_of_expired;
     $scope.getPassport_id = data.data[0].id;
     console.log($scope.getPassport_id);
     $scope.getPassportVisaDetails(passport_no);

 }
 else {
     $scope.passport_no = passport_no;
     $scope.country_code = null;
     $scope.sur_name = null;
     $scope.given_name = null;
     $scope.nationality = null;
     $scope.sex = null;
     $scope.date_of_birth = null;
     $scope.place_of_birth = null;
     $scope.place_of_issue = null;
     $scope.date_of_issue =null;
     $scope.date_of_expired = null;

 }


                    $scope.passportInfoData = data.data;
					$scope.showPassportDetails = false;
					 $scope.showPassportDetailsSearch =false;

				})

		}

		$scope.SearchPassNOEntryExit = function (PassportNo) {
			var data = {
                passport_no : PassportNo

			}
            $http.post("/api/searchEntryExit",data)
                .then(function (data) {
                    $scope.Allpassportdata = data.data;
                    $scope.passportID = data.data[0].id;
                    $scope.passport_no = data.data[0].passport_no;
                    // console.log($scope.Allpassportdata)
					console.log($scope.passportID)




                }).catch(function () {

            }).finally(function () {

            })


        }
        $scope.saveEntryExit = function () {

            var data = {
                date : $scope.date,
                entry_reasons : $scope.entry_reasons,
                comment : $scope.comment,
                passport_id : $scope.passportID,
                entry_exit_status : $scope.entry_exit_status
            }
            console.log($scope.passportID)
            // console.log($scope.entry_date)
            $http.post("/api/saveEntryExitVisaInfo", data)
                .then(function(data){

                    $scope.savingSuccessEntryExit = "Saved Successfully.";
                    //
                    // console.log(data.data)
                    // console.log(data.data[0])

                    $scope.date = null;
                    $scope.entry_reasons = null;
                    $scope.comment = null;
                    $scope.entry_exit_status = null;


                }).catch(function(){

                $scope.savingErrorEntryExit = "Something Went Wrong.";

            }).finally(function(data){

            })

            var data = {
                exit :  $scope.passport_no
                // passport_id : $scope.passportID
            }
            // console.log($scope.passport_no)
            // // console.log(exit)
            // console.log(data)

            $http.post("/api/getAllExitEntryForShow",data)
                .then(function (data) {

                    $scope.getAllExitEntryData = data.data;
                    $scope.showPassportDetails = true;
                    $scope.showPassportDetailsSearch = false;
                    // console.log($scope.getAllExitEntryData)
                    // console.log(data.data)
                    // console.log(data.data[0])

                }).catch(function () {
            }).finally(function () {

            })



        }

		$scope.visaDetails = function (text) {

            var data={
                passport_no:text
            }
            console.log(data)
			console.log(text)
            $http.post("/api/showVisaInfo",data)
                .then(function (data) {

                    // console.log(data.data)
                    // console.log(data.data[0])

                    // $scope.m_manifest=data.data[0].m_manifest;

                    // console.log(data.data[0].passport_no)
                    // console.log(data.data[0].sur_name)
                    // console.log(data.data[0].id)

					$scope.passport_id =data.data[0].id;
					$scope.v_passport_no = data.data[0].passport_no;
                    $scope.v_place_of_issue = data.data[0].place_of_issue;
                    $scope.v_date_of_issue = data.data[0].date_of_issue;
                    $scope.v_date_of_expired = data.data[0].date_of_expired;
                    $scope.v_sur_name = data.data[0].sur_name;
                    $scope.v_date_of_birth = data.data[0].date_of_birth;
                    $scope.v_sex = data.data[0].sex;
                    $scope.v_nationality = data.data[0].nationality;
                    $scope.showPassportDetailsSearch = false;
                    console.log( $scope.v_sex)


                })
                .catch(function () {
                    console.log('error')

                })
                .finally(function () {

                })


        }




		$scope.blank = function() {

			$scope.passport_no = null;
			$scope.country_code = null;
			$scope.sur_name = null;
			$scope.given_name = null;
			$scope.nationality = null;
			$scope.sex = null;
			$scope.date_of_birth = null;
			$scope.place_of_birth = null;
			$scope.place_of_issue = null;
			$scope.date_of_issue = null;
			$scope.date_of_expired = null;

		}

	}).filter('sexFilter', function () {
        return function (val) {
            var sex;
            if(val==1){
                return sex='Male';
            } else if(val ==0) {
                return sex='Female';
            }
            return sex='';
        }
    });