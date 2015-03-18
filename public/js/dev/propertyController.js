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
    return {
        apiCall: function (operation, method, functionUrl, propertyData) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + functionUrl,
                method: method,
                data: (propertyData!=undefined) ? $.param(propertyData) : ''
            })
            .success(function (jsonData) {
            });
        }
    }
}]);


propertyApp.controller('mainCtrl', ['$scope', 'propertyService',  function($scope, propertyService) {

}]);

propertyApp.controller('propertyController', ['$scope', 'propertyService',  function($scope, propertyService) {
   //propertyService.getProperties().then(function(propertyData) {

    var method = 'GET';
    var functionUrl = 'property';

   propertyService.apiCall('getProperties', method, functionUrl).then(function(propertyData) {
       $scope.properties = propertyData.data;
    });
}]);

propertyApp.controller('propertyAddCtrl', ['$scope', 'propertyService' , '$routeParams', '$location',  function($scope, propertyService, $routeParams, $location) {
    $scope.property ={};
    $scope.submitClicked = false;

    /* Check if the form is in edit mode with property id in the url */
    if($routeParams.id) {
        var propertyId = $scope.property.id = $routeParams.id;
        var method = 'GET';
        var functionUrl = 'property/' + propertyId + '/edit';
        propertyService.apiCall('getSingleProperty', method, functionUrl).then(function(propertyData) {
            if(propertyData.data) {
                $scope.property = propertyData.data;
            }
        });

        $scope.save_property = function() {
            $scope.submitClicked = true;
            /* Check for Validation */
            if($scope.addPropertyForm.$invalid) {

            }else {
                var method = 'PUT';
                var functionUrl = 'property/' + propertyId;
                propertyService.apiCall('updateProperty', method, functionUrl, $scope.property).then(function (propertyData) {
                    $location.path('/');
                });
            }
        }
    }else {

        $scope.save_property = function () {
            $scope.submitClicked = true;
            if($scope.addPropertyForm.$invalid) {

            }else {
                var method = 'POST';
                var functionUrl = 'property/';
                propertyService.apiCall('addProperty', method, functionUrl, $scope.property).then(function (propertyData) {
                 $location.path('/');
                 });
            }
        }
    }

    /* To put error class for input */
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
    }

   /*propertyService.addProperty().then(function() {
       $scope.properties = propertyData.data;
        console.log(propertyData);
    });*/

}]);