var requirementApp = angular.module('requirementApp', ['ngRoute', 'ui.bootstrap']);
requirementApp.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
requirementApp.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider) {
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
}]);

requirementApp.factory('requirementService', ['$http', '$rootScope', function($http,$rootScope) {
    return {
        apiCall: function (operation, method, functionUrl, requirementData) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + functionUrl,
                method: method,
                data: (requirementData!=undefined) ? $.param(requirementData) : ''
            })
            .success(function (jsonData) {
                console.log('SUCESS===', jsonData);
            })
            .error(function(data, status, headers, config) {
                console.log('ERROR===', data);

            });
        }
    }
}]);


requirementApp.controller('mainCtrl', ['$scope', 'requirementService',  function($scope, requirementService) {

    $scope.$on('MsgEvent', function(event, data) {
        $scope.infoMsg = data;
    });
    $scope.infoMsg = '';
}]);

requirementApp.controller('requirementController', ['$scope', 'requirementService', '$location',  function($scope, requirementService,$location) {

    var method = 'GET';
    var functionUrl = 'req-list';
    requirementService.apiCall('getRequirements', method, functionUrl).then(function(requirementData) {
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
        requirementService.apiCall('DeleteRequirement', method, functionUrl).then(function(requirementData) {
            //console.log('Delete Msg : ' + JSON.stringify(requirementData));
            $scope.$emit('MsgEvent', requirementData.data.message);
            $location.path('/');
        });
    }
}]);


requirementApp.controller('requirementViewCtrl', ['$scope', 'requirementService', '$routeParams',  function($scope, requirementService, $routeParams) {

    $scope.$emit('MsgEvent', '');
    if($routeParams.id) {

        var requirementId = $routeParams.id;
        var method = 'GET';
        var functionUrl = 'req-list/' + requirementId+ '/edit';
        requirementService.apiCall('getSingleRequirement', method, functionUrl).then(function(requirementData) {
            if(requirementData.data) {
                $scope.requirement = requirementData.data;
            }
        });
    }
}]);

requirementApp.controller('requirementAddCtrl', ['$scope', 'requirementService', '$routeParams', '$location',  function($scope, requirementService, $routeParams, $location) {
    $scope.requirement ={};
    $scope.submitClicked = false;
    $scope.$emit('MsgEvent', '');

    if($routeParams.id) {
        //Call For Edit
        var requirementId = $scope.requirement.id = $routeParams.id;
        var method = 'GET';
        var functionUrl = 'req-list/' + requirementId + '/edit';
        requirementService.apiCall('getSingleRequirement', method, functionUrl).then(function(requirementData) {
            if(requirementData.data) {
                $scope.requirement = requirementData.data;
            }
        });

        $scope.save_requirement = function() {
            $scope.submitClicked = true;
            if($scope.addRequirementForm.$invalid) {

            }else {
                var method = 'PUT';
                var functionUrl = 'req-list/' + requirementId;
                requirementService.apiCall('updateRequirement', method, functionUrl, $scope.requirement)
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
                requirementService.apiCall('addRequirement', method, functionUrl, $scope.requirement)
                    .then(function (requirementData) {
                        console.log('Add Msg ==== ' + JSON.stringify(requirementData));
                        $scope.$emit('MsgEvent', requirementData.data.message);
                        $location.path('/');
                    });
                    /*.catch(function(fallback) {
                        console.log('Error add Msg ==== ' + JSON.stringify(fallback));
                        $scope.$emit('MsgEvent', fallback.data.message+fallback.data.data);
                        $location.path('/');
                    });*/
            }
        }
    }
    /* To put error class for input */
    /*$scope.show_error = function(name) {
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
    }*/

}]);