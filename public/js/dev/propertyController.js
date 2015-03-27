var propertyApp = angular.module('propertyApp', ['propagateServiceModule', 'ngRoute', 'ui.bootstrap']);

/* CODE FOR PAGINATION */
propertyApp.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});

/** ROUTES CONFIGURATION STARTS **/
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
/** ROUTES CONFIGURATION ENDS **/

/** FACTORY METHOD ENDS **/


    propertyApp.controller('mainCtrl', ['$scope', 'propagateService',  function($scope, propagateService) {

    $scope.$on('MsgEvent', function(event, data) {
        $scope.infoMsg = data;
    });
    $scope.infoMsg = '';

}]);

propertyApp.controller('propertyController', ['$scope', 'propagateService', '$location', function($scope, propagateService,$location) {
   //propagateService.getProperties().then(function(propertyData) {

    var method = 'GET';
    var functionUrl = 'property';

   propagateService.apiCall('getProperties', method, functionUrl).then(function(propertyData) {
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
        //console.log(propertyId);
        var method = 'DELETE';
        var functionUrl =  'property/' + propertyId;

        propagateService.apiCall('DeleteProperty', method, functionUrl).then(function(propertyData) {
            //console.log('Delete Msg : ' + JSON.stringify(propertyData));
            $scope.$emit('MsgEvent', propertyData.data.message);
            $location.path('/');
        });

    }
}]);

propertyApp.controller('propertyViewCtrl', ['$scope', 'propagateService', '$routeParams',  function($scope, propagateService, $routeParams) {

    $scope.$emit('MsgEvent', '');
    if($routeParams.id) {

        var propertyId = $routeParams.id;
        var method = 'GET';
        var functionUrl = 'property/' + propertyId+ '/edit';
        propagateService.apiCall('getSingleProperty', method, functionUrl).then(function(propertyData) {
            if(propertyData.data) {
                $scope.property = propertyData.data;
            }
        });
    }
}]);

propertyApp.controller('propertyAddCtrl', ['$scope', 'propagateService' , '$routeParams', '$location',  function($scope, propagateService, $routeParams, $location) {
    $scope.property ={};
    $scope.submitClicked = false;
    $scope.$emit('MsgEvent', '');

    /* Check if the form is in edit mode with property id in the url */
    if($routeParams.id) {
        var propertyId = $scope.property.id = $routeParams.id;
        var method = 'GET';
        var functionUrl = 'property/' + propertyId + '/edit';
        propagateService.apiCall('getSingleProperty', method, functionUrl).then(function(propertyData) {
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
                propagateService.apiCall('updateProperty', method, functionUrl, $scope.property).then(function (propertyData) {

                    //console.log('Update Msg : ' + propertyData.data);
                    $scope.$emit('MsgEvent', propertyData.data.message);
                    $location.path('/');
                }).catch(function(fallback) {
                    //console.log('Error Update Msg ==== ' + JSON.stringify(fallback));
                    $scope.$emit('MsgEvent', fallback.data.message+fallback.data.data);
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
                propagateService.apiCall('addProperty', method, functionUrl, $scope.property).then(function (propertyData) {
                        $scope.$emit('MsgEvent', propertyData.data.message);
                        $location.path('/');
                    })
                    .catch(function(fallback) {
                        //console.log('Error add Msg ==== ' + JSON.stringify(fallback));
                        $scope.$emit('MsgEvent', fallback.data.message+fallback.data.data);
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

   /*propagateService.addProperty().then(function() {
       $scope.properties = propertyData.data;
        console.log(propertyData);
    });*/

}]);