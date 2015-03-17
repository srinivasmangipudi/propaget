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

propertyApp.controller('propertyAddCtrl', ['$scope', 'propertyService' , '$routeParams',  function($scope, propertyService, $routeParams) {
    $scope.property ={};

    if($routeParams.id) {
        var propertyId = $routeParams.id;
        propertyService.getProperty(propertyId).then(function(propertyData) {
            if(propertyData.data) {
                $scope.property = propertyData.data;
            }
        });
    }

    $scope.save_property = function() {

        propertyService.saveProperty($scope.property).then(function(propertyData) {

        });
    }

   /*propertyService.addProperty().then(function() {
       $scope.properties = propertyData.data;
        console.log(propertyData);
    });*/

}]);