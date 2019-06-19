angular.module('HeadOrSubHeadApp', ['angularUtils.directives.dirPagination'])
    .controller('HeadOrSubHeadController', function ($http, $scope) {

        // $scope.Validation = function() {
        //     if($scope.Account_Form.$invalid) {
        //         $scope.submitted = true;
        //         return false;
        //     } else {
        //         $scope.submitted = false;
        //         return true;
        //     }
        // }

        $scope.headPerPage = 5;
        $scope.headSerial = 1;
        $scope.getPageCount = function (n) {
            $scope.headSerial = n * $scope.headPerPage - ($scope.headPerPage - 1);
            console.log(n)
        };

        $scope.getHead = function () {
            $http.get("/accounts/api/get-head-details-data")
                .then(function (data) {
                    console.log(data.data);
                    console.log(data.data.length)
                    if (data.data.length > 0) {
                        $scope.headTable = true;
                        $scope.allHeadData = data.data;
                        $scope.head_id = data.data[0].id;

                        console.log(data.data[0])
                        console.log($scope.head_id);
                        $scope.getSubHead($scope.head_id);


                    } else {
                        $scope.headTable = false;
                    }
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
            }).finally(function () {

            })
        }
        $scope.getHead();

        $scope.HeadAddBtn = true;
        $scope.HeadEditBtn = false;

        $scope.saveHead = function () {

            // if($scope.Validation() == false) {
            //     return;
            // }

            var data = {
                acc_head: $scope.acc_head,
                in_ex_status: $scope.type
            }
            console.log($scope.type)
            console.log($scope.acc_head)


            $http.post("/accounts/api/save-head-data", data)
                .then(function (data) {
                    console.log(data);
                    $scope.acc_head = null;
                    $scope.savingSuccessHead = 'Head added successfully.';
                    $("#savingSuccessHead").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#savingSuccessHead").slideUp(7000);
                    });
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingErrorHead = 'Something went wrong.';
                $("#savingErrorHead").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#savingErrorHead").slideUp(7000);
                });
            }).finally(function () {
                $scope.getHead();
            })
        }


        $scope.editHeadBtn = function (head) {
            $scope.HeadAddBtn = false;
            $scope.HeadEditBtn = true;
            $scope.id = head.id;
            $scope.acc_head = head.acc_head;
            $scope.type = head.in_ex_status.toString();

            // console.log(head.in_ex_status)
        }

        $scope.editHead = function () {
            var data = {
                id: $scope.id,
                acc_head: $scope.acc_head,
                in_ex_status: $scope.type
            }

            $http.put("/accounts/api/edit-head-data", data)
                .then(function (data) {
                    console.log(data);
                    $scope.acc_head = null;
                    $scope.HeadAddBtn = true;
                    $scope.HeadEditBtn = false;
                    $scope.savingSuccessHead = 'Head edited successfully.';
                    $("#savingSuccessHead").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#savingSuccessHead").slideUp(7000);
                    });
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingErrorHead = 'Something went wrong.';
                $("#savingErrorHead").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#savingErrorHead").slideUp(7000);
                });
            }).finally(function () {
                $scope.getHead();
            })
        }

        $scope.deleteHeadBtn = function (head) {
            var id = head.id;
            var headName = head.acc_head;
            bootbox.confirm({
                message: "Do you want to delete?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    $scope.deleteHead(result, id, headName);
                }
            }).css({
                'text-align': 'center',
                'top': '0',
                'bottom': '0',
                'left': '0',
                'right': '0',
                'margin': 'auto'
            });
        }

        $scope.deleteHead = function (result, id, headName) {
            //console.log(result);
            //console.log(id);
            if (result == true) {
                $http.delete("/accounts/api/delete-head-data/" + id)
                    .then(function (data) {
                        console.log(data.data);
                        if (data.data == 'subHeadExist') {
                            $scope.savingErrorHead = '"' + headName + '" has Sub-head. Delete them first.';
                            $("#savingErrorHead").show().fadeTo(6500, 500).slideUp(500, function () {
                                $("#savingErrorHead").slideUp(7000);
                            });
                        } else if (data.data == 'Deleted') {
                            $scope.savingSuccessHead = 'Head "' + headName + '" deleted successfully.';
                            $("#savingSuccessHead").show().fadeTo(6500, 500).slideUp(500, function () {
                                $("#savingSuccessHead").slideUp(7000);
                            });
                        }
                    }).catch(function (r) {
                    console.log(r)
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                    } else {
                        $.growl.error({message: "It has Some Error!"});
                    }
                    $scope.savingErrorHead = 'Something went wrong.';
                    $("#savingErrorHead").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#savingErrorHead").slideUp(7000);
                    });
                }).finally(function () {
                    $scope.getHead();
                })
            } else {
                return false;
            }
        }

        //=======================SUB-HEAD=====================


        $scope.subHeadPerPage = 5;
        $scope.subHeadSerial = 1;
        $scope.getSubHeadPageCount = function (n) {
            $scope.subHeadSerial = n * $scope.subHeadPerPage - ($scope.subHeadPerPage - 1);
            console.log(n)
        };


        $scope.subHeadAddBtn = true;
        $scope.subHeadEditBtn = false;

        $scope.getSubHead = function (head_id) {
            console.log(head_id);
            $http.get("/accounts/api/get-sub-head-data/" + head_id)
                .then(function (data) {
                    console.log(data);
                    if (data.data.length > 0) {
                        $scope.subHeadTable = true;
                        $scope.allSubHeadData = data.data;
                    } else {
                        $scope.subHeadTable = false;
                    }
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }

            }).finally(function () {

            })
        }

        $scope.postSubHead = function () {
            var data = {
                head_id: $scope.head_id,
                acc_sub_head: $scope.acc_sub_head
            }
            $http.post("/accounts/api/save-sub-head-data", data)
                .then(function (data) {
                    $scope.acc_sub_head = null;
                    $scope.savingSuccessSubHead = 'Sub-Head added successfully.';
                    $("#savingSuccessSubHead").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#savingSuccessSubHead").slideUp(7000);
                    });
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingErrorSubHead = 'Something went wrong.';
                $("#savingErrorSubHead").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#savingErrorSubHead").slideUp(7000);
                });
            }).finally(function () {
                $scope.getSubHead($scope.head_id);
            })
        }

        $scope.editSubHeadBtn = function (subHead) {
            $scope.subHeadAddBtn = false;
            $scope.subHeadEditBtn = true;
            $scope.acc_sub_head_id = subHead.id;
            $scope.acc_sub_head = subHead.acc_sub_head;
            $scope.head_id = subHead.head_id;
        }

        $scope.editSubHead = function () {
            var data = {
                id: $scope.acc_sub_head_id,
                head_id: $scope.head_id,
                acc_sub_head: $scope.acc_sub_head
            }

            $http.put("/accounts/api/edit-sub-head-data", data)
                .then(function (data) {
                    //console.log(data);
                    $scope.acc_sub_head = null;
                    $scope.subHeadAddBtn = true;
                    $scope.subHeadEditBtn = false;
                    $scope.savingSuccessSubHead = 'Sub-Head edited successfully.';
                    $("#savingSuccessSubHead").show().fadeTo(6500, 500).slideUp(500, function () {
                        $("#savingSuccessSubHead").slideUp(7000);
                    });
                }).catch(function (r) {
                console.log(r)
                if (r.status == 401) {
                    $.growl.error({message: r.data});
                } else {
                    $.growl.error({message: "It has Some Error!"});
                }
                $scope.savingErrorSubHead = 'Something went wrong.';
                $("#savingErrorSubHead").show().fadeTo(6500, 500).slideUp(500, function () {
                    $("#savingErrorSubHead").slideUp(7000);
                });
            }).finally(function () {
                $scope.getSubHead($scope.head_id);
            })
        }

        $scope.deleteSubHeadBtn = function (subHead) {
            var sub_head_id = subHead.id;
            var subhHeadName = subHead.acc_sub_head;
            bootbox.confirm({
                message: "Do you want to delete?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    $scope.deleteSubHead(result, sub_head_id, subhHeadName);
                }
            }).css({
                'text-align': 'center',
                'top': '0',
                'bottom': '0',
                'left': '0',
                'right': '0',
                'margin': 'auto'
            });

            $scope.deleteSubHead = function (result, sub_head_id, subhHeadName) {
                if (result == true) {
                    $http.delete("/accounts/api/delete-sub-head/" + sub_head_id)
                        .then(function (data) {
                            //console.log(data.data);
                            $scope.savingSuccessSubHead = 'Sub-Head "' + subhHeadName + '" deleted successfully.';
                            $("#savingSuccessSubHead").show().fadeTo(6500, 500).slideUp(500, function () {
                                $("#savingSuccessSubHead").slideUp(7000);
                            });
                        }).catch(function (r) {
                        console.log(r)
                        if (r.status == 401) {
                            $.growl.error({message: r.data});
                        } else {
                            $.growl.error({message: "It has Some Error!"});
                        }
                        $scope.savingErrorHead = 'Something went wrong.';
                        $("#savingErrorSubHead").show().fadeTo(6500, 500).slideUp(500, function () {
                            $("#savingErrorSubHead").slideUp(7000);
                        });
                    }).finally(function () {
                        $scope.getSubHead($scope.head_id);
                    })
                } else {
                    return false;
                }
            }
        }
    }).filter('accountTypeFilter', function () {
    return function (val) {
        var account;
        if (val == 0) {
            return account = 'Income';
        } else if (val == 1) {
            return account = 'Expenditure';
        }else {
            return account = 'Income(Others)';
        }
    }
});