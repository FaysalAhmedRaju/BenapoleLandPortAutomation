angular.module('OrganizationEntryApp',['angularUtils.directives.dirPagination','customServiceModule'])
	.controller('OrganizationEntryController', function($scope, $http,enterKeyService){

        $scope.btnSave = true;
        $scope.btnUpdate = false;

		$http.post("/organization/api/organization/get-organization-type")
            .then(function(data){
                    $scope.allOrgTypeData = data.data;
                }).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });

        $http.post("/organization/api/organization/get-port-details")
        	.then(function(data){
        		$scope.allPortData = data.data;
        	}).catch(function (r) {

            console.log(r)
            if (r.status == 401) {
                $.growl.error({message: r.data});
            } else {
                $.growl.error({message: "It has Some Error!"});
            }

        }).finally(function () {


        });

        $scope.allOrganizationList = function() {
        	$http.post("/organization/api/organization/get-all-organization")
        		.then(function(data){
        			$scope.allOrganization = data.data;
        		}).catch(function (r) {

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {


            });
        }
        $scope.allOrganizationList();

        enterKeyService.enterKey('#OrganizationEntryForm input ,#OrganizationEntryForm button')

        $scope.save = function() {
            if($scope.org_type_id == null || $scope.org_name == null || 
                $scope.add1 == null || $scope.add2 == null || $scope.port_id == null || 
                $scope.propriter_name == null || $scope.phone == null || $scope.mobile == null
                || $scope.email == null ) {

                if($scope.org_type_id == null) {
                    $scope.org_type_id_required = true;
                }
                if($scope.org_name == null) {
                   $scope.org_name_required = true;
                }
                if($scope.add1 == null) {
                   $scope.add1_required = true;
                }
                if( $scope.add2 == null) {
                   $scope.add2_required = true;
                }
                if($scope.port_id == null) {
                   $scope.port_id_required = true;
                }
                if($scope.propriter_name == null) {
                   $scope.propriter_name_required = true;
                }
                if($scope.phone == null) {
                   $scope.phone_required = true;
                }
                if($scope.mobile == null) {
                   $scope.mobile_required = true;
                }
                if($scope.email == null) {
                   $scope.email_required = true;
                }
                return false;
            } else {
                //$scope.posted_yard_shed_required = false;
                $scope.org_type_id_required = false;
                $scope.org_name_required = false;
                $scope.add1_required = false;
                $scope.add2_required = false;
                $scope.port_id_required = false;
                $scope.propriter_name_required = false;
                $scope.phone_required = false;
                $scope.mobile_required = false;
                $scope.email_required = false;
            }

        	var data = {
        		org_type_id : $scope.org_type_id,
        		org_name : $scope.org_name,
        		add1 : $scope.add1,
        		add2 : $scope.add2,
        		port_id : $scope.port_id,
        		propriter_name : $scope.propriter_name,
        		phone : $scope.phone,
        		mobile : $scope.mobile,
        		email : $scope.email
        	}
        	$http.post("/organization/api/organization/save-organization-data", data)
        		.then(function(data){
                    //console.log(data);
        			$scope.savingSuccess = "Organization Entry Saved Successfully.";
                    $scope.org_type_id = null;
                    $scope.org_name = null;
                    $scope.add1 = null;
                    $scope.add2 = null;
                    $scope.port_id = null;
                    $scope.propriter_name = null;
                    $scope.phone = null;
                    $scope.mobile = null;
                    $scope.email = null;
        		}).catch(function(r){

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
        			$scope.savingError = "Something Went Wrong.";
        		}).finally(function(){
        			$scope.allOrganizationList();
        		})
        }

        $scope.pressUpdateBtn = function(organization) {
            $scope.btnSave = false;
            $scope.btnUpdate = true;
            $scope.id = organization.id;
            $scope.selectedStyle = organization.id;

            $scope.org_type_id = organization.org_type_id;
            $scope.org_name = organization.org_name;
            $scope.add1 = organization.add1;
            $scope.add2 = organization.add2;
            $scope.port_id = organization.port_id;
            $scope.propriter_name = organization.propriter_name;
            $scope.phone = parseInt(organization.phone);
            $scope.mobile = parseInt(organization.mobile);
            $scope.email = organization.email;
        }

        $scope.update = function() {
            if($scope.org_type_id == null || $scope.org_name == null || 
                $scope.add1 == null || $scope.add2 == null || $scope.port_id == null || 
                $scope.propriter_name == null || $scope.phone == null || $scope.mobile == null
                || $scope.email == null ) {

                if($scope.org_type_id == null) {
                    $scope.org_type_id_required = true;
                }
                if($scope.org_name == null) {
                   $scope.org_name_required = true;
                }
                if($scope.$scope.add1 == null) {
                   $scope.add1_required = true;
                }
                if( $scope.add2 == null) {
                   $scope.add2_required = true;
                }
                if($scope.port_id == null) {
                   $scope.port_id_required = true;
                }
                if($scope.propriter_name == null) {
                   $scope.propriter_name_required = true;
                }
                if($scope.phone == null) {
                   $scope.phone_required = true;
                }
                if($scope.mobile == null) {
                   $scope.mobile_required = true;
                }
                if($scope.email == null) {
                   $scope.email_required = true;
                }
                return false;
            } else {
                //$scope.posted_yard_shed_required = false;
                $scope.org_type_id_required = false;
                $scope.org_name_required = false;
                $scope.add1_required = false;
                $scope.add2_required = false;
                $scope.port_id_required = false;
                $scope.propriter_name_required = false;
                $scope.phone_required = false;
                $scope.mobile_required = false;
                $scope.email_required = false;
            }

            var data = {
                id : $scope.id,
                org_type_id : $scope.org_type_id,
                org_name : $scope.org_name,
                add1 : $scope.add1,
                add2 : $scope.add2,
                port_id : $scope.port_id,
                propriter_name : $scope.propriter_name,
                phone : $scope.phone,
                mobile : $scope.mobile,
                email : $scope.email
            }

            $http.post("/organization/api/organization/update-organization-data", data)
                .then(function(data){
                    //console.log(data);
                    $scope.savingSuccess = "Organization Entry Updated Successfully.";
                    $scope.id = null;
                    $scope.org_type_id = null;
                    $scope.org_name = null;
                    $scope.add1 = null;
                    $scope.add2 = null;
                    $scope.port_id = null;
                    $scope.propriter_name = null;
                    $scope.phone = null;
                    $scope.mobile = null;
                    $scope.email = null;
                    $scope.btnSave = true;
                    $scope.btnUpdate = false;
                }).catch(function(r){

                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                    $scope.savingError = "Something Went Wrong.";
                }).finally(function(){
                    $scope.allOrganizationList();
                })
        }

        $scope.pressDeleteBtn = function(organization) {
            var confirmation = confirm("Do You Want To Delete This Data?");
            //console.log(confirmation);
            if(confirmation) {
                var data = {
                    id : organization.id
                }
                $http.post("/organization/api/organization/delete-organization-data", data)
                    .then(function(data){
                        //console.log(data);
                        $scope.savingSuccess = "Organization Entry Deleted Successfully.";
                    }).catch(function(r){

                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                        $scope.savingError = "Something Went Wrong.";
                    }).finally(function(){
                        $scope.allOrganizationList();
                    })
            } else {
                return false;
            }

        }

	});