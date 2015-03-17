var propertyApp = angular.module('propertyApp', ['ngRoute']);

propertyApp.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {

    $routeProvider
        .when('/list', {
            title: 'View Properties',
            templateUrl: base_url + '/properties/list',
            controller:'propertyController'
        })
        .when('/add', {
            title: 'Add properties',
            templateUrl: base_url + '/properties/add',
            controller: 'propertyAddCtrl'
        })
        .when('/edit/:id', {
            title: 'Edit property',
            templateUrl: base_url + '/properties/add',
            controller: 'propertyAddCtrl'
        })
        .otherwise({
            redirectTo: '/list'
        });
}]);

    propertyApp.factory('propertyService', ['$http', '$rootScope', function($http, $rootScope) {
    var properties = [];
    return {
        getProperties: function () {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'property',
                method: "GET"
            })
            .success(function (jsonData) {
                if (jsonData) {
                    properties = jsonData.data;
                }
                else {
                    properties = {};
                }
                // quiz = addData;
                $rootScope.$broadcast('handleProjectsBroadcast', properties);
            });
        },
        saveProperty: function (propertyData) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'property',
                method: "POST",
                data: $.param(propertyData)
            })
            .success(function (propertyData) {
            });
        },
        getProperty: function (property_id) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'property/' + property_id + '/edit',
                method: "GET"
            })
            .success(function (propertyData) {
            });
        },
        updateProperty: function (property_id, propertyData) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'property/' + property_id,
                method: "PUT",
                data: $.param(propertyData)
            })
            .success(function (propertyData) {
            });
        }
    }
}]);

propertyApp.controller('mainCtrl', ['$scope', 'propertyService',  function($scope, propertyService) {

}]);

propertyApp.controller('propertyController', ['$scope', 'propertyService',  function($scope, propertyService) {
   propertyService.getProperties().then(function(propertyData) {
       $scope.properties = propertyData.data;
        console.log(propertyData);
    });
}]);

propertyApp.controller('propertyAddCtrl', ['$scope', 'propertyService' , '$routeParams', '$location',  function($scope, propertyService, $routeParams, $location) {
    $scope.property ={};
    $scope.submitClicked = false;
    if($routeParams.id) {
        var propertyId = $scope.property.id = $routeParams.id;
        propertyService.getProperty(propertyId).then(function(propertyData) {
            if(propertyData.data) {
                $scope.property = propertyData.data;
            }
        });

        $scope.save_property = function() {
            $scope.submitClicked = true;
            if($scope.addPropertyForm.$invalid) {
                console.log($scope.addPropertyForm);

            }else {
                propertyService.updateProperty(propertyId, $scope.property).then(function (propertyData) {
                    $location.path('/');
                });
            }
        }
    }else {

        $scope.save_property = function () {
            $scope.submitClicked = true;
            if($scope.addPropertyForm.$invalid) {
                console.log($scope.addPropertyForm);

            }else {
                console.log($scope.addPropertyForm);
                propertyService.saveProperty($scope.property).then(function (propertyData) {
                 $location.path('/');
                 });
            }
        }
    }

    /* To put error class for input
    $scope.show_error = function(name) {
        if($scope.submitClicked) {
            if($scope.addPropertyForm[name].$invalid) {
                return 'error';
            }
        }
        else {
            if($scope.addPropertyForm[name].$invalid && $scope.addPropertyForm[name].$dirty) {
                return 'error';
            }
        }
        return '';
    } */

   /*propertyService.addProperty().then(function() {
       $scope.properties = propertyData.data;
        console.log(propertyData);
    });*/

}]);