var requirementApp = angular.module('requirementApp', []);

requirementApp.factory('requirementService', ['$http', '$rootScope', function($http, $rootScope) {
    var requirements = [];
    return {
        getRequirements: function () {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'req-list',
                method: "GET",
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
        },
    }
}]);

requirementApp.controller('requirementController', ['$scope', 'requirementService',  function($scope, requirementService) {
    $scope.name = 'Amitav';
    requirementService.getRequirements().then(function(requirementData) {
       $scope.requirements = requirementData.data;
        console.log(requirementData);
    });
}]);