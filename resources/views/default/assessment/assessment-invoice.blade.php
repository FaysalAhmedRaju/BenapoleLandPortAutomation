@extends('layouts.master')
@section('title', 'Challan')
@section('script')
    <script type="text/javascript">
        angular.module('ChallanApp', ['customServiceModule'])
            .controller('ChallanCtrl', function($scope, $http, manifestService, $filter){
                $scope.keyBoard = function (event) {
                    $scope.keyboardFlag = manifestService.getKeyboardStatus(event);
                }

                $scope.$watch('manifest', function () {
                    $scope.manifest = manifestService.addYearWithManifest($scope.manifest, $scope.keyboardFlag);
                });
                $scope.$watch('manifest', function (val) {
                    $scope.manifest = $filter('uppercase')(val);

                }, true);
                $scope.partial_status = null;
                $scope.partial_number_list = [];
                $scope.getPartialList = function() {
                    if($scope.ChallanForm.manifest.$error.pattern) {
                        console.log('error');
                        return;
                    }
                    console.log('hit');
                    $http.get("/assessment/api/get-partial-list/"+$scope.manifest)
                        .then(function(data){
                            console.log(data);
                            if(data.status == 203) {
                                $scope.errorMessage = data.data.message;
                                $('#errorMessage').show().delay(5000).slideUp(1000);
                                $.growl.error({message: data.data.message});
                                return;
                            }
                            console.log(data);
                            $scope.partial_number = data.data;
                            console.log($scope.partial_status);
                            console.log('Partial Number:');
                            console.log($scope.partial_number);
                            console.log($scope.partial_number_list);
                            for (var x = 0; x < $scope.partial_number; x++) {
                                $scope.partial_number_list[x] = x+1;
                            }
                            console.log($scope.partial_number_list);
                            if($scope.partial_status == null) {
                                $scope.partial_status = $scope.partial_number_list[$scope.partial_number-1];
                            }
                        }).catch(function(r) {
                            if(r.status == 401) {
                                $.growl.error({message: r.data});
                            }
                        })
                }
            })
    </script>
@endsection
@section('content')
        <div class="col-md-12" ng-app="ChallanApp" ng-controller="ChallanCtrl">
            <div class="col-md-6 col-md-offset-3" style="background-color: #f8f9f9; border-radius: 5px; padding: 10px 0 5px 10px;">
                <form name="ChallanForm" action="{{ route('assessment-get-assessment-invoice-report') }}" target="_blank" method="POST">
                    {{ csrf_field() }}
                    <div class="col-md-12">
                        <table>
                        <br>
                            <tr>
                                <th>Manifest:</th>
                                <td>
                                    <input class="form-control" type="text" name="manifest" id="manifest" ng-change="getPartialList()" ng-pattern="/^([0-9]{1,10}|[0-9P]{2,6})[\/]{1}([0-9]{1,3}|[(A-Z)]{1}|[(A-Z-A-Z)]{3})[\/]{1}[0-9]{4}$/" ng-model="manifest"  ng-keydown="keyBoard($event)" ng-model-options="{allowInvalid : true}" required="required" ng-change="getPartialList()">
                                </td>
                                <td colspan="2">
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </td>
                                <td>
                                    <select  {{--ng-if="partial_number_list.length>0"--}}  title="" style="width: 130px;" required="required"
                                            class="form-control"
                                            ng-change="get_partial(searchText,partial_status)"
                                            name="partial_status"
                                            ng-model="partial_status"
                                            ng-options="item as item for item in partial_number_list">
                                        <option value="">Select Partial</option>
                                    </select>
                                    <input type="hidden" name="partial_status_for_challan" value="@{{partial_status}}">
                                </td> 
                                 <td style="padding-left: 10px;">
                                    <button type="submit" class="btn btn-primary center-block">Show</button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <span class="error" ng-show='ChallanForm.manifest.$error.pattern'>
                                        Input like: 256/12/2017 Or 256/A/2017
                                    </span>
                                    <span class="error" id="errorMessage" ng-show="errorMessage">
                                        @{{errorMessage}}
                                    </span>
                                </td>
                            </tr>
                        </table>
                        <br>
                    </div>
                </form>
            </div>
        </div>
@endsection