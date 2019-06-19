var module = angular.module('graphApp', ['ui.bootstrap']);

module.controller('graphCtrl', function ($scope, $uibModal, $http) {
    $scope.rows = [
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10
    ];

    $scope.columns = [
        'A',
        'B',
        'C',
        'D',
        'E'
    ];


    $http.get("api/getYardGraphDetails")

        .then(function (r) {
            console.log(r.data)

            $scope.weights = r.data

        }).catch(function (r) {

        console.log(r)
        if (r.status == 401) {
            $.growl.error({message: r.data});
        } else {
            $.growl.error({message: "It has Some Error!"});
        }

    }).finally(function () {


    });


    $scope.onCellselect = function (row, column, weight) {

        console.log(weight)
        var modalInstance = $uibModal.open({
            templateUrl: 'yardModal',
            controller: 'ModalInstanceCtrl',
            // backdrop: false,
            resolve: {
                row: function () {
                    return row;
                },
                column: function () {
                    return column;
                },
                weight: function () {
                    return weight;
                }
            }
        }).result.then(function () {
        }, function (res) {
        })

        // modalInstance.result.then(loadData);
    };


    $http.get("api/getYardGraphDetails")

        .then(function (r) {
            console.log(r.data)

            $scope.weights = r.data

        }).catch(function (r) {

        console.log(r)
        if (r.status == 401) {
            $.growl.error({message: r.data});
        } else {
            $.growl.error({message: "It has Some Error!"});
        }

    }).finally(function () {


    });


    $scope.getWeight = function (row, column) {

        //  console.log($scope.weights)
        var record = _.find($scope.weights, {
            row: row,
            column: column
        });

        // Was a record found with the row and column?
        if (record) {

            $scope.weight_in_cell = record.weight
            // If so return its weight.
            return record.weight;
        }


    }


});


module.controller('ModalInstanceCtrl', function ($scope, row, column, weight, $http, $uibModalInstance) {
    $scope.row = row;
    $scope.column = column;
    $scope.button_text = 'Save'

    if (weight) {
        console.log(weight)
        $scope.weight = weight;
        $scope.button_text = 'Update'
    }


    $scope.save = function () {
        $http.post('api/saveGraphWeight', {
            row: row,
            column: column,
            weight: $scope.weight
        }).then(function () {
            $uibModalInstance.close();


        });
    };


    $scope.close = function () {
        $uibModalInstance.close();
    };

});
/*

 module.factory('fakeHttp', function ($q,$http) {


 var database = {};

 var fakeHttp = {};

 $http.get("api/getYardGraphDetails")

 .then(function (r) {
 console.log(r.data)
 fakeHttp.weights =r.data

 })
 database[createKey({
 row: 'A',
 column: 1
 })] = 12;
 database[createKey({
 row: 'A',
 column: 5
 })] = 12;

 fakeHttp.get = function (url, data) {
 if (url === 'api/weight') {
 var key = createKey(data);
 return $q.when({ data:
 database[key]
 });
 } else if (url === 'api/weights') {
 // Make the data in the "database" object reflect
 // what the server will be returning.
 var data = _.chain(database)
 .toPairs()
 .map(function (pair) {
 var key = pair[0];
 var value = pair[1];
 var result = JSON.parse(key);
 result.weight = value;
 console.log(result);
 return result;
 })
 .value();
 return $q.when({ data:
 data
 });
 } else {
 alert('invalid url: ' + url);
 }
 };
 fakeHttp.post = function (url, data) {
 if (url === 'api/weight') {
 var key = createKey(data);
 database[key] = data.weight;
 return $q.when({});
 } else {
 alert('invalid url: ' + url);
 }
 };

 return fakeHttp;

 function createKey(data) {
 var key = {
 row: data.row,
 column: data.column
 };

 return JSON.stringify(key);
 }
 });
 */
