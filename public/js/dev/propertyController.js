var propertyApp = angular.module('propertyApp', []);

propertyApp.factory('propertyService', ['$http', '$rootScope', function($http, $rootScope) {
    var properties = [];
    return {
        getProperties: function () {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'property',
                method: "GET",
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
    }
}]);

propertyApp.controller('propertyController', ['$scope', 'propertyService',  function($scope, propertyService) {
    $scope.name = 'Amitav';
   propertyService.getProperties().then(function(propertyData) {
       $scope.properties = propertyData.data;
        console.log(propertyData);
    });
}]);