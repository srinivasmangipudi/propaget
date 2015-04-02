var profileApp = angular.module('profileApp', ['propagateServiceModule','ngRoute', 'ui.bootstrap']);

profileApp.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
    $routeProvider
        .when('/adddetailpage', {
            title: 'Add Detail Profile',
            templateUrl: base_url + '/profilepages/adddetailpage',
            controller: 'profileAddDetailCtrl'
        })
        .otherwise({
            redirectTo: '/adddetailpage'
        });
}]);

profileApp.controller('mainCtrl', ['$scope', 'propagateService',  function($scope, propagateService) {
}]);

profileApp.controller('profileAddDetailCtrl', ['$scope', 'propagateService','$location',  function($scope, propagateService ,$location) {
    $scope.userdata = {};
    $scope.userdata.role = 'agent';
    $scope.save_profile = function() {
        console.log('In save');
        console.log($scope.userdata);
        var method = 'PUT';
        var functionUrl = 'profile/' + 1;
        propagateService.apiCall('updateProfile', method, functionUrl, $scope.userdata)
            .then(function (userData) {
                console.log(userData);
                window.location.href = '/';
            });
    }
}]);