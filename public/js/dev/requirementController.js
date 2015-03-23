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
        .otherwise({
            redirectTo: '/list'
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
        },
        saveRequirement: function (requirementData) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'req-list',
                method: "POST",
                data: $.param(requirementData)
            })
            .success(function (requirementData) {
            });
        },
        getRequirement: function (requirement_id) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'req-list/' + requirement_id + '/edit',
                method: "GET"
            })
            .success(function (requirementData) {
            });
        },
        updateRequirement: function (requirement_id, requirementData) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'req-list/' + requirement_id,
                method: "PUT",
                data: $.param(requirementData)
            })
            .success(function (requirementData) {
            });
        },
        deleteRequirement: function (requirement_id){
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + 'req-list/' + requirement_id,
                method: "DELETE"
            })
            .success(function(requirementData){
            });
        }
    }
}]);

requirementApp.controller('mainCtrl', ['$scope', 'requirementService',  function($scope, requirementService) {
    $scope.infoMsg = 'dummy';
    $scope.$on('MsgEvent', function(event, data) {
        //console.log('mi:'+data);
        $scope.infoMsg = data;
    });
    $scope.infoMsg = '';
}]);

requirementApp.controller('requirementController', ['$scope', 'requirementService', '$location',  function($scope, requirementService,$location) {

     requirementService.getRequirements().then(function(requirementData) {
        $scope.requirements = requirementData.data;
         //console.log(requirementData);

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
        requirementService.deleteRequirement(requirementId).then(function(requirementData) {
            //console.log('Delete Msg : ' + requirementData.data);
            $scope.$emit('MsgEvent', requirementData.data);
            $location.path('/');
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
        requirementService.getRequirement(requirementId).then(function(requirementData) {
            if(requirementData.data) {
                $scope.requirement = requirementData.data;
            }
        });

        $scope.save_requirement = function() {
            $scope.submitClicked = true;
            if($scope.addRequirementForm.$invalid) {
                console.log($scope.addRequirementForm);

            }else {
                requirementService.updateRequirement(requirementId, $scope.requirement).then(function (requirementData) {

                    //console.log('Update Msg : ' + requirementData.data);
                    $scope.$emit('MsgEvent', requirementData.data);
                    $location.path('/');
                });
            }
        }
    }else {
        console.log('In add');
        //Call For Add
        $scope.save_requirement = function () {
            $scope.submitClicked = true;
            //console.log('In save add');
            if($scope.addRequirementForm.$invalid) {
                //console.log('In if');
                //console.log('IF'+$scope.addRequirementForm);

            }else {
                //console.log('In Else');
                //console.log('Else'+$scope.addRequirementForm);
                requirementService.saveRequirement($scope.requirement).then(function (requirementData) {

                    //console.log('Add Msg : ' + requirementData.data);
                    $scope.$emit('MsgEvent', requirementData.data);
                    $location.path('/');
                });
            }
        }
    }

}]);