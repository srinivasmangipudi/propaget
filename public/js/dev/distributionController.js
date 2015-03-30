var distributionApp = angular.module('distributionApp', ['propagateServiceModule','ngRoute', 'ui.bootstrap']);
distributionApp.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
distributionApp.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
    $routeProvider
        .when('/list',{
            title: 'List Distribution',
            templateUrl: base_url +'/distribution/list',
            controller:'distributionController'
        })
        .when('/view/:id',{
            title: 'View Distribution',
            templateUrl: base_url + 'distribution/view',
            controller : 'distributionViewCtrl'
        })
        .otherwise({
            redirectTo: '/list'
        });
}]);

distributionApp.controller('mainCtrl', ['$scope', 'propagateService',  function($scope, propagateService) {

    $scope.$on('MsgEvent', function(event, data) {
        $scope.infoMsg = data;
    });
    $scope.infoMsg = '';
}]);

distributionApp.controller('distributionController', ['$scope', 'propagateService', '$location',  function($scope, propagateService,$location) {

    var method = 'GET';
    var functionUrl = 'dist-list';
    propagateService.apiCall('getDistributions', method, functionUrl).then(function(distributionData) {
         $scope.distributionlist = distributionData.data;

         $scope.currentPage = 1; //current page
         $scope.entryLimit = 10; //max no of items to display in a page
         $scope.filteredItems = $scope.distributionlist.length; //Initially for no filter
         $scope.totalItems = $scope.distributionlist.length;

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

    $scope.deleteDistribution = function (distributionId)
    {
        //console.log(distributionId);
        var method = 'DELETE';
        var functionUrl =  'dist-list/' + distributionId;
        propagateService.apiCall('DeleteDistribution', method, functionUrl).then(function(distributionData) {
            //console.log('Delete Msg : ' + distributionData.data);
            $scope.$emit('MsgEvent', distributionData.data);
            $location.path('/');
        });
    }
}]);


distributionApp.controller('distributionViewCtrl', ['$scope', 'propagateService', '$routeParams',  function($scope, propagateService, $routeParams) {

    $scope.$emit('MsgEvent', '');
    if($routeParams.id) {

        var distributionId = $routeParams.id;
        var method = 'GET';
        var functionUrl = 'dist-list/' + distributionId;
        propagateService.apiCall('getSingleDistribution', method, functionUrl).then(function(distributionData) {

            //console.log('listing' + JSON.stringify(distributionData));
            if(distributionData.data) {
                $scope.distributionMemberlist = distributionData.data;

                $scope.currentPage = 1; //current page
                $scope.entryLimit = 10; //max no of items to display in a page
                $scope.filteredItems = $scope.distributionMemberlist.length; //Initially for no filter
                $scope.totalItems = $scope.distributionMemberlist.length;
            }
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
    }
}]);
