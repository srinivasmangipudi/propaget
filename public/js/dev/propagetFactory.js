/**
 * Created by urmila on 26/3/15.
 */

var propagateServiceModule= angular.module('propagateServiceModule', []);

/** FACTORY METHOD STARTS **/
propagateServiceModule.factory('propagateService', ['$http', '$rootScope', function($http, $rootScope) {
    return {
        apiCall: function (operation, method, functionUrl, propagateData) {
            return $http({
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                url: base_url + functionUrl + '?access_token=0f3027786012f371f687c50e9a75b2b5c9be86bf',
                method: method,
                data: (propagateData!=undefined) ? $.param(propagateData) : ''
            })
            .success(function (jsonData) {
                console.log('SUCESS=:=', jsonData);
            })
            .error(function(data, status, headers, config) {
                console.log('ERROR=:=', data);
            });
        }
    }
}]);