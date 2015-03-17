var requirementApp = angular.module('requirementApp', ['ngRoute']);

requirementApp.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
    $routeProvider
        .when('/list',{
            title: 'View Requirement',
            templateUrl: base_url +'/requirements/list',
            controller:'requirementController'
        })
        .otherwise({
            redirectTo: '/'
        });
}]);

requirementApp.factory('requirementService', ['$http', '$rootScope', function($http, $rootScope) {
    var requirements = [];
    return {
        getRequirements: function () {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'req-list',
                method: "GET"
            })
            .success(function (jsonData) {
                if (jsonData) {
                    requirements = jsonData.data;
                }
                else {
                    requirements = {};
                }
                // quiz = addData;
                $rootScope.$broadcast('handleProjectsBroadcast', requirements);
            });
        }
    }
}]);

requirementApp.controller('mainCtrl', ['$scope', 'requirementService',  function($scope, requirementService) {

}]);

requirementApp.controller('requirementController', ['$scope', 'requirementService',  function($scope, requirementService) {

     $scope.name = 'Urmi';
     requirementService.getRequirements().then(function(requirementData) {
        $scope.requirements = requirementData.data;
         console.log(requirementData);
     });

     $scope.addRequirement = function()
     {
         console.log('i m add req');

     }
}]);