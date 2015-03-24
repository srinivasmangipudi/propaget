var propertyApp = angular.module('propertyApp', ['ngRoute', 'ui.bootstrap']);

propertyApp.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
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
        .when('/view/:id',{
            title: 'View property',
            templateUrl: base_url + 'properties/view',
            controller : 'propertyViewCtrl'
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
        },
        deleteProperty: function (propertyId){
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'property/' + propertyId,
                method: "DELETE"
            })
            .success(function(propertyData){
            });
        }
    }
}]);


propertyApp.controller('mainCtrl', ['$scope', 'propertyService',  function($scope, propertyService) {

    $scope.infoMsg = 'dummy';
    $scope.$on('MsgEvent', function(event, data) {
        //console.log('mi:'+data);
        $scope.infoMsg = data;
    });
    $scope.infoMsg = '';

}]);

propertyApp.controller('propertyController', ['$scope', 'propertyService', '$location', function($scope, propertyService,$location) {
   //propertyService.getProperties().then(function(propertyData) {

    var method = 'GET';
    var functionUrl = 'property';

   propertyService.apiCall('getProperties', method, functionUrl).then(function(propertyData) {
       $scope.properties = propertyData.data;

       $scope.currentPage = 1; //current page
       $scope.entryLimit = 5; //max no of items to display in a page
       $scope.filteredItems = $scope.properties.length; //Initially for no filter
       $scope.totalItems = $scope.properties.length;

    });

    /** Function for angular pager starts **/
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };

    $scope.filter = function() {
        $timeout(function() {
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };

    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };

    $scope.deleteProperty = function (propertyId)
    {
        console.log(propertyId);
        propertyService.deleteProperty(propertyId).then(function(propertyData) {
            //console.log('Delete Msg : ' + propertyData.data);
            $scope.$emit('MsgEvent', propertyData.data);
            $location.path('/');
        });

    }

}]);

propertyApp.controller('propertyViewCtrl', ['$scope', 'propertyService', '$routeParams',  function($scope, propertyService, $routeParams) {
    if($routeParams.id) {

        var propertyId = $routeParams.id;
        var method = 'GET';
        var functionUrl = 'property/' + propertyId+ '/edit';
        propertyService.apiCall('getSingleProperty', method, functionUrl).then(function(propertyData) {
            if(propertyData.data) {
                $scope.property = propertyData.data;
            }
        });
    }

}]);

propertyApp.controller('propertyAddCtrl', ['$scope', 'propertyService' , '$routeParams', '$location',  function($scope, propertyService, $routeParams, $location) {
    $scope.property ={};
    $scope.submitClicked = false;
    $scope.$emit('MsgEvent', '');

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

                    //console.log('Update Msg : ' + propertyData.data);
                    $scope.$emit('MsgEvent', propertyData.data);
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
                    //console.log('Add Msg : ' + propertyData.data);
                    $scope.$emit('MsgEvent', propertyData.data);
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