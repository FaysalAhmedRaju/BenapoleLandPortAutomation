angular.module('budgetAddApp', ['angularUtils.directives.dirPagination', 'customServiceModule'])
    .controller('budgetAddController', function ($http, $scope, enterKeyService) {

        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        var min = new Date().getFullYear() - 1;
        var max = min + 1;
        $scope.years = [];
        var j = 0;
        for (var i = min; i <= max; i++) {
            $scope.years[j++] = {value: i, text: i};
        }
        // console.log($scope.years);

        $scope.subhead_id = 0;

        $('#subhead_name').autocomplete({
            source: "/admin/api/expenditure/budget/get-subhead-list",
            minLength: 3,
            highlightItem: true,
            // autoFocus:true,
            // displayKey: 'Importer_Name',
            response: function (event, ui) {
                // ui.content is the array that's about to be sent to the response callback.
                if (ui.content.length === 0) {
                    $("#subhead-not-found").text("No Subhead Found");
                } else {
                    $("#subhead-not-found").empty();
                }
            },
            select: function (event, ui) {
                // event.preventDefault();
                console.log(ui.item);
                $scope.subhead_id = ui.item.subhead_id;
                $("#subhead_name").val(ui.item.value);
                $scope.subhead_name = ui.item.value;
                return false;
            },
            change: function (event, ui) {

                if (ui.item == null) {
                    console.log('no match');
                    $("#subhead_name").val('').focus();
                    // $("#subhead_name");
                    $scope.subhead_id = null;
                    $scope.subhead_name = null;
                    $("#subhead-not-found").empty();
                }
            },
            focus: function (event, ui) {
                console.log('facus');
                if (ui != null) {
                    defaultVal = ui.item.label;
                }
            },
            search: function () {
                // console.log('8');

            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div>" + item.label + "<br>" + item.desc + "</div>")
                .appendTo(ul);
        };


        $scope.limitData = function () {
            $http.get("/admin/api/expenditure/budget/get-all-budget-data")
                .then(function (data) {

                    console.log(data);
                    if (data.data.length > 0) {
                        $scope.allBudgetData = data.data;

                        /*  var flags = [], output = [], l = data.data.length, i;
                         for (i = 0; i < l; i++) {
                         if (flags[data.data[i].restriction_code]) continue;
                         flags[data.data[i].restriction_code] = true;
                         output.push(data.data[i]);
                         }
                         console.log(output)

                         $scope.uniqueLimitData = output;*/
                    }
                    else {

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
        };
        $scope.limitData();


        $scope.editBudgetData = function (i) {
            console.log(i)

            $scope.updateBtn = true;
            $scope.edit = {};
            $scope.edit.id = i.id;
            $scope.edit.subhead_id = i.subhead_id;
            $scope.amount = i.amount;
            $scope.subhead_id = i.subhead_id;
            $scope.subhead_name = i.sub_head_name;
            $scope.monthly_yearly_flag = i.monthly_yearly_flag.toString();
            $scope.fiscal_year = i.fiscal_year;


        }


        $scope.saveBudget = function (form) {
            console.log($scope.subhead_name);
            if (form.$invalid || !$scope.subhead_id) {
                $scope.submitted = true;
            }
            else {

                // var restriction_name = "";
                // angular.forEach($scope.uniqueLimitData, function (v, k) {
                //     if (v.restriction_code == $scope.restriction_code) {
                //         restriction_name = v.restriction_name;
                //     }
                //
                // })

                //check if limit is already set for a year
                var keepGoing = true;
                if ($scope.updateBtn) {
                    angular.forEach($scope.allBudgetData, function (v, k) {
                        if (keepGoing) {
                            if (v.id != $scope.edit.id) {
                                if (v.subhead_id == $scope.subhead_id
                                    && v.monthly_yearly_flag == $scope.monthly_yearly_flag
                                    && v.fiscal_year == $scope.fiscal_year) {
                                    keepGoing = false;

                                }
                            }

                        }
                    })
                }
                else {
                    angular.forEach($scope.allBudgetData, function (v, k) {
                        if (keepGoing) {
                            if (v.subhead_id == $scope.subhead_id
                                && v.monthly_yearly_flag == $scope.monthly_yearly_flag
                                && v.fiscal_year == $scope.fiscal_year) {

                                keepGoing = false;

                            }
                        }
                    })
                }
                console.log(keepGoing);
                if (!keepGoing) {
                    $scope.error = true;
                    $scope.error = "Can't Add Expenditure Twice In A Year!";
                    $("#error").show().delay(2000).slideUp(1000);
                    return
                }

                //check if limit is already set for a year

                var data = {
                    subhead_id: $scope.subhead_id,
                    monthly_yearly_flag: $scope.monthly_yearly_flag,
                    amount: $scope.amount,
                    fiscal_year: $scope.fiscal_year


                }
                if ($scope.updateBtn) {


                    var data = {
                        subhead_id: $scope.subhead_id,
                        monthly_yearly_flag: $scope.monthly_yearly_flag,
                        amount: $scope.amount,
                        fiscal_year: $scope.fiscal_year,
                        id: $scope.edit.id

                    }

                }
                console.log(data)

                $http.post("/admin/api/expenditure/budget/save-budget-data", data)
                    .then(function (data) {
                        console.log(data)
                        $scope.success = true;
                        if ($scope.updateBtn) {
                            $scope.success = 'Updated Successfully';
                        }
                        else {
                            $scope.success = 'Saved Successfully';
                        }

                        $("#success").show().delay(1000).slideUp(1000);

                        $scope.limitData();
                        $scope.updateBtn = false;
                        $scope.submitted = false;

                        $scope.restriction_code = null;
                        $scope.amount = null;
                        $scope.year = null;
                        $scope.edit = {};
                        $("#subhead_name").val('');
                        $scope.subhead_id = null;
                        $scope.subhead_name = null;

                    }).catch(function (r, s) {
                    console.log(r)
                    $scope.error = true;
                    $("#error").show().delay(2000).slideUp(1000);
                    if (r.status == 401) {
                        $.growl.error({message: r.data});
                        $scope.error = 'The Budget Already Fixed Before!';
                    }
                    else {
                        $.growl.error({message: "It has Some Error!"});
                        $scope.error = 'Something wet worng!';

                    }

                }).finally(function () {

                })

            }

        }


        $scope.deleteBudgetData = function (i) {
            var id = i.id;
            console.log(i);
            // console.log(designationName);

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
                    if (result) {
                        $http.delete("/admin/api/expenditure/budget/delete-budget-data/" + id)
                            .then(function (data) {
                                $scope.success = true;
                                $scope.success = 'Deleted successfully.';
                                $("#success").show().delay(1000).slideUp(1000);

                            }).catch(function (r) {
                            console.log(r)
                            if (r.status == 401) {
                                $.growl.error({message: r.data});
                            } else {
                                $.growl.error({message: "It has Some Error!"});
                            }
                            $scope.error = true;
                            $scope.error = 'Something went wrong.';
                            $("#error").show().delay(1000).slideUp(1000);

                        }).finally(function () {

                            $scope.limitData();

                        })
                    }
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


    });