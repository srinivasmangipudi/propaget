var requirementApp = angular.module('requirementApp', ['propagateServiceModule','ngRoute', 'ui.bootstrap']);
requirementApp.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
requirementApp.config(['$routeProvider', '$locationProvider', '$httpProvider', function($routeProvider, $locationProvider, $httpProvider) {
    $routeProvider
        .when('/list',{
            title: 'View Requirement',
            templateUrl: base_url +'/requirements/list',
            controller:'requirementController'
        })
        .when('/add', {
            title: 'Add Requirement',
            templateUrl: base_url + '/requirements/add',
            controller: 'requirementAddCtrl'
        })
        .when('/edit/:id', {
            title: 'Edit Requirement',
            templateUrl: base_url + '/requirements/add',
            controller: 'requirementAddCtrl'
        })
        .when('/view/:id',{
            title: 'View Requirement',
            templateUrl: base_url + 'requirements/view',
            controller : 'requirementViewCtrl'
        })
        .otherwise({
            redirectTo: '/list'
        });

    $httpProvider.defaults.withCredentials = true;
}]);

requirementApp.controller('mainCtrl', ['$scope', 'propagateService',  function($scope, propagateService) {

    $scope.$on('MsgEvent', function(event, data) {
        $scope.infoMsg = data;
    });
    $scope.infoMsg = '';
}]);

requirementApp.controller('requirementController', ['$scope', 'propagateService', '$location',  function($scope, propagateService,$location) {

    var method = 'GET';
    var functionUrl = 'req-list';
    propagateService.apiCall('getRequirements', method, functionUrl).then(function(requirementData) {
         $scope.requirements = requirementData.data;

         $scope.currentPage = 1; //current page
         $scope.entryLimit = 10; //max no of items to display in a page
         $scope.filteredItems = $scope.requirements.length; //Initially for no filter
         $scope.totalItems = $scope.requirements.length;

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

    $scope.deleteRequirement = function (requirementId)
    {
        //console.log(requirementId);
        var method = 'DELETE';
        var functionUrl =  'req-list/' + requirementId;
        propagateService.apiCall('DeleteRequirement', method, functionUrl).then(function(requirementData) {
            //console.log('Delete Msg : ' + JSON.stringify(requirementData));
            $scope.$emit('MsgEvent', requirementData.data.message);
            $location.path('/');
        });
    }
}]);


requirementApp.controller('requirementViewCtrl', ['$scope', 'propagateService', '$routeParams', '$location',  function($scope, propagateService, $routeParams ,$location) {

    console.log('UI AM HERE');
    console.log('ACESS TOKEN===', readCookie('access_token'));

    $scope.$emit('MsgEvent', '');
    if($routeParams.id) {

        var requirementId = $routeParams.id;
        var method = 'GET';
        var functionUrl = 'req-list/' + requirementId;
        propagateService.apiCall('getSingleRequirement', method, functionUrl).then(function(requirementData) {
            if(requirementData.data) {
                $scope.requirement = requirementData.data;
            }
        }).catch(function(fallback) {
            //console.log('Error Update Msg ==== ' + JSON.stringify(fallback));
            $scope.$emit('MsgEvent', fallback.data.message);
            $location.path('/');
        });
    }
}]);

requirementApp.controller('requirementAddCtrl', ['$scope', 'propagateService', '$routeParams', '$location',  function($scope, propagateService, $routeParams, $location) {
    $scope.requirement ={};
    $scope.submitClicked = false;
    $scope.$emit('MsgEvent', '');

    if($routeParams.id) {
        //Call For Edit
        var requirementId = $scope.requirement.id = $routeParams.id;
        var method = 'GET';
        var functionUrl = 'req-list/' + requirementId + '/edit';
        propagateService.apiCall('getSingleRequirement', method, functionUrl).then(function(requirementData) {
            if(requirementData.data) {
                $scope.requirement = requirementData.data;
            }
        }).catch(function(fallback) {
            //console.log('Error Update Msg ==== ' + JSON.stringify(fallback));
            $scope.$emit('MsgEvent', fallback.data.message);
            $location.path('/');
        });

        $scope.save_requirement = function() {
            $scope.submitClicked = true;
            if($scope.addRequirementForm.$invalid) {

            }else {
                var method = 'PUT';
                var functionUrl = 'req-list/' + requirementId;
                propagateService.apiCall('updateRequirement', method, functionUrl, $scope.requirement)
                    .then(function (requirementData) {
                    //console.log('Update Msg ==== ' + JSON.stringify(requirementData));
                    $scope.$emit('MsgEvent', requirementData.data.message);
                    $location.path('/');
                    })
                    .catch(function(fallback) {
                        //console.log('Error Update Msg ==== ' + JSON.stringify(fallback));
                        $scope.$emit('MsgEvent', fallback.data.message+fallback.data.data);
                    });
            }
        }
    }else {

        $scope.save_requirement = function () {
            $scope.submitClicked = true;
            if($scope.addRequirementForm.$invalid) {

            }else {
                var method = 'POST';
                var functionUrl = 'req-list/';
                propagateService.apiCall('addRequirement', method, functionUrl, $scope.requirement).then(function (requirementData) {
                        //console.log('Add Msg ==== ' + JSON.stringify(requirementData));
                        $scope.$emit('MsgEvent', requirementData.data.message);
                        $location.path('/');
                    })
                    .catch(function(fallback) {
                        //console.log('Error add Msg ==== ' + JSON.stringify(fallback));
                        //$scope.$emit('MsgEvent', fallback.data.message+fallback.data.data);
                    });
            }
        }
    }
    /* To put error class for input */
    $scope.show_error = function(name) {
        if($scope.submitClicked) {
            if($scope.addRequirementForm[name].$invalid) {
                return 'error';
            }
        }
        else {
            if($scope.addRequirementForm[name].$invalid && $scope.addRequirementForm[name].$dirty) {
                return 'error';
            }
        }
        return '';
    }

}]);